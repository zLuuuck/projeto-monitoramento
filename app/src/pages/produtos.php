<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isAdmin = isset($_SESSION['user_id']) && $_SESSION['user_id'] === 1;

require_once '../scripts/conectarBanco.php';
require_once '../scripts/func_produtos.php';

$db = conectarBanco();

function buscarProdutos($db)
{
    $filtro = trim($_GET['filtro'] ?? '');
    $ordenar = $_GET['ordenar'] ?? '';

    $sql = "SELECT * FROM produtos";
    $params = [];

    if ($filtro !== '') {
        $sql .= " WHERE nome LIKE :filtro";
        $params[':filtro'] = "%$filtro%";
    }

    switch ($ordenar) {
        case 'az':
            $sql .= " ORDER BY nome ASC";
            break;
        case 'za':
            $sql .= " ORDER BY nome DESC";
            break;
        case 'antigo':
            $sql .= " ORDER BY id ASC";
            break;
        case 'novo':
            $sql .= " ORDER BY id DESC";
            break;
        case 'maior_qtd':
            $sql .= " ORDER BY quantidade DESC";
            break;
        case 'menor_qtd':
            $sql .= " ORDER BY quantidade ASC";
            break;
        default:
            $sql .= " ORDER BY id DESC"; // padrão
            break;
    }

    $stmt = $db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function deletarProduto($db, $id)
{
    // Buscar imagem antes de deletar
    $stmt = $db->prepare("SELECT imagem FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto && file_exists($produto['imagem'])) {
        unlink($produto['imagem']); // Remove a imagem do servidor
    }

    $stmt = $db->prepare("DELETE FROM produtos WHERE id = ?");
    return $stmt->execute([$id]);
}

function editarProduto($db, $id, $nome, $modelo, $cor, $quantidade, $imagemFile = null)
{
    // Validações básicas
    if (
        strlen($nome) < 2 || strlen($modelo) < 2 ||
        strlen($cor) < 2 || !is_numeric($quantidade) || $quantidade <= 0
    ) {
        return ['status' => 'error', 'msg' => "Dados inválidos para edição."];
    }

    // Verifica se o modelo já existe em outro produto
    $stmt = $db->prepare("SELECT COUNT(*) FROM produtos WHERE modelo = ? AND id != ?");
    $stmt->execute([$modelo, $id]);
    if ($stmt->fetchColumn() > 0) {
        return ['status' => 'error', 'msg' => "Já existe outro produto com esse modelo."];
    }

    $imagemPath = null;

    // Se enviou nova imagem
    if ($imagemFile && $imagemFile['error'] === UPLOAD_ERR_OK) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($imagemFile['tmp_name']);
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!in_array($mime, $tiposPermitidos)) {
            return ['status' => 'error', 'msg' => "Tipo de imagem inválido. Use JPG, PNG, GIF ou WEBP."];
        }

        if (!getimagesize($imagemFile['tmp_name'])) {
            return ['status' => 'error', 'msg' => "Arquivo enviado não é uma imagem válida."];
        }

        $tamanhoMaxMB = 2;
        if ($imagemFile['size'] > $tamanhoMaxMB * 1024 * 1024) {
            return ['status' => 'error', 'msg' => "Imagem muito grande. Máximo de {$tamanhoMaxMB}MB."];
        }

        // Criar pasta, gerar nome seguro e salvar
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = strtolower(pathinfo($imagemFile['name'], PATHINFO_EXTENSION));
        $nomeSeguro = 'produto_' . $id . '_' . time() . '.' . $ext;
        $destinoAbsoluto = $uploadDir . $nomeSeguro;
        $destinoRelativo = '../uploads/' . $nomeSeguro;

        if (!move_uploaded_file($imagemFile['tmp_name'], $destinoAbsoluto)) {
            return ['status' => 'error', 'msg' => "Falha ao mover a imagem."];
        }

        // Apaga a imagem antiga
        $stmt = $db->prepare("SELECT imagem FROM produtos WHERE id = ?");
        $stmt->execute([$id]);
        $antiga = $stmt->fetchColumn();

        if ($antiga) {
            // Remove '../' do começo se existir
            $caminhoRelativo = ltrim($antiga, './'); // Remove './' e '../' se houver
            $caminhoCompleto = realpath(__DIR__ . '/../' . $caminhoRelativo);

            if ($caminhoCompleto && file_exists($caminhoCompleto)) {
                unlink($caminhoCompleto);
            } else {
                error_log("Imagem antiga não encontrada para exclusão: $caminhoCompleto");
            }
        }

        $imagemPath = $destinoRelativo;
    }

    // Monta SQL com ou sem nova imagem
    if ($imagemPath) {
        $sql = "UPDATE produtos SET nome = ?, modelo = ?, cor = ?, quantidade = ?, imagem = ? WHERE id = ?";
        $params = [$nome, $modelo, $cor, $quantidade, $imagemPath, $id];
    } else {
        $sql = "UPDATE produtos SET nome = ?, modelo = ?, cor = ?, quantidade = ? WHERE id = ?";
        $params = [$nome, $modelo, $cor, $quantidade, $id];
    }

    $stmt = $db->prepare($sql);
    $stmt->execute($params);

    return ['status' => 'success', 'msg' => "Produto atualizado com sucesso."];
}

// Processar exclusão se for admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isAdmin && isset($_POST['excluir_id'])) {
    deletarProduto($db, (int)$_POST['excluir_id']);
    header("Location:produtos.php");
    exit();
}

// Processar edição se for admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'editar' && $isAdmin) {
    header('Content-Type: application/json; charset=utf-8');  // Cabeçalho JSON para resposta AJAX

    // Recebe os dados da edição
    $id = $_POST['id'] ?? null;
    $nome = trim($_POST['nome'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $cor = trim($_POST['cor'] ?? '');
    $quantidade = intval($_POST['quantidade'] ?? 0);
    $imagemFile = $_FILES['imagem'] ?? null;

    if (!$id) {
        echo json_encode(['status' => 'error', 'msg' => 'ID do produto ausente.']);
        exit;
    }

    $resultado = editarProduto($db, $id, $nome, $modelo, $cor, $quantidade, $imagemFile);
    echo json_encode($resultado);
    exit;
}

$produtos = buscarProdutos($db);
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isAdmin && isset($_POST['atualizar_id'])) {
    $atualizar_id = $_POST['atualizar_id'];
    $novoNome = trim($_POST['novo_nome']);
    $novoModelo = trim($_POST['novo_modelo']);
    $novaCor = trim($_POST['nova_cor']);
    $novaQtd = (int)$_POST['nova_quantidade'];

    $resultado = editarProduto($db, $atualizar_id, $novoNome, $novoModelo, $novaCor, $novaQtd);
    if ($resultado['status'] === 'success') {
        $mensagem = mensagem($resultado['msg'], 'success');
    } else {
        $mensagem = mensagem($resultado['msg'], 'error');
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Guri Games</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Configuração personalizada do Tailwind -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'cyber-cyan': '#00ffff',
                        'cyber-blue': '#0080ff',
                        'dark-bg': '#0a0a0a',
                    },
                    animation: {
                        'border-glow': 'borderGlow 3s ease-in-out infinite',
                        'float-particle': 'floatParticle 15s linear infinite',
                        'fade-in-up': 'fadeInUp 0.6s ease-out',
                    },
                    keyframes: {
                        borderGlow: {
                            '0%, 100%': {
                                opacity: 0.5
                            },
                            '50%': {
                                opacity: 1
                            },
                        },
                        floatParticle: {
                            '0%': {
                                transform: 'translateY(100vh) translateX(0) rotate(0deg)',
                                opacity: 0,
                            },
                            '10%': {
                                opacity: 1
                            },
                            '90%': {
                                opacity: 1
                            },
                            '100%': {
                                transform: 'translateY(-100px) translateX(100px) rotate(360deg)',
                                opacity: 0,
                            },
                        },
                        fadeInUp: {
                            'from': {
                                opacity: 0,
                                transform: 'translateY(30px)'
                            },
                            'to': {
                                opacity: 1,
                                transform: 'translateY(0)'
                            }
                        }
                    }
                }
            }
        }
    </script>

    <script src="https://kit.fontawesome.com/0dc50eaa4b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../styles/style.css">
</head>

<body class="min-h-screen bg-gray-900 text-white flex flex-col">
    <?php include_once '../components/navbar.php'; ?>
    <div class="particles" id="particles"></div>

    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Título Principal -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-black mb-4 bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent uppercase tracking-wider animate-fade-in-up">
                <i class="fas fa-gamepad mr-3"></i>
                Nossos Produtos
            </h1>
            <div class="w-48 h-1 bg-gradient-to-r from-cyan-400 to-blue-500 mx-auto rounded-full shadow-lg shadow-cyan-500/50"></div>
        </div>

        <!-- Formulário de Filtro -->
        <div class="max-w-4xl mx-auto mb-12">
            <form method="get" class="bg-gray-900/95 border-2 border-cyan-400 rounded-2xl p-6 md:p-8 shadow-2xl shadow-cyan-500/30 backdrop-blur-sm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                    <!-- Buscar por nome -->
                    <div>
                        <label for="filtro" class="block text-cyan-400 font-bold text-sm uppercase tracking-wider mb-2">
                            <i class="fas fa-search mr-2"></i>
                            Buscar por nome:
                        </label>
                        <input
                            type="text"
                            id="filtro"
                            name="filtro"
                            value="<?= htmlspecialchars($_GET['filtro'] ?? '') ?>"
                            placeholder="Digite o nome do produto..."
                            class="w-full bg-black/90 border border-cyan-400 rounded-xl py-3 px-4 text-white transition-all duration-300 focus:outline-none focus:border-blue-500 focus:shadow-2xl focus:shadow-cyan-500/50 shadow-lg shadow-cyan-500/20">
                    </div>

                    <!-- Ordenar por -->
                    <div>
                        <label for="ordenar" class="block text-cyan-400 font-bold text-sm uppercase tracking-wider mb-2">
                            <i class="fas fa-sort mr-2"></i>
                            Ordenar por:
                        </label>
                        <select
                            name="ordenar"
                            id="ordenar"
                            class="w-full bg-black/90 border border-cyan-400 rounded-xl py-3 px-4 text-white transition-all duration-300 focus:outline-none focus:border-blue-500 focus:shadow-2xl focus:shadow-cyan-500/50 shadow-lg shadow-cyan-500/20">
                            <option value="">-- Selecione --</option>
                            <option value="az" <?= ($_GET['ordenar'] ?? '') === 'az' ? 'selected' : '' ?>>Nome A-Z</option>
                            <option value="za" <?= ($_GET['ordenar'] ?? '') === 'za' ? 'selected' : '' ?>>Nome Z-A</option>
                            <option value="novo" <?= ($_GET['ordenar'] ?? '') === 'novo' ? 'selected' : '' ?>><i class="fas fa-bullseye mr-2"></i> Mais novo</option>
                            <option value="antigo" <?= ($_GET['ordenar'] ?? '') === 'antigo' ? 'selected' : '' ?>><i class="fas fa-clock mr-2"></i> Mais antigo</option>
                            <option value="maior_qtd" <?= ($_GET['ordenar'] ?? '') === 'maior_qtd' ? 'selected' : '' ?>><i class="fas fa-arrow-up mr-2"></i> Maior quantidade</option>
                            <option value="menor_qtd" <?= ($_GET['ordenar'] ?? '') === 'menor_qtd' ? 'selected' : '' ?>><i class="fas fa-arrow-down mr-2"></i> Menor quantidade</option>
                        </select>
                    </div>

                    <!-- Botão Filtrar -->
                    <div>
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-cyan-400 to-blue-500 text-gray-900 font-bold py-3 px-6 rounded-xl text-lg uppercase tracking-wide hover:shadow-2xl hover:shadow-cyan-500/40 transform hover:-translate-y-1 transition-all duration-300 shadow-lg shadow-cyan-500/20">
                            <i class="fas fa-rocket mr-2"></i>
                            Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- MENSAGENS DE SUCESSO/ERRO -->
        <?php if ($mensagem): ?>
            <div class="max-w-4xl mx-auto mb-8">
                <div class="<?= strpos($mensagem, 'sucesso') !== false ? 'bg-green-500/20 border-green-600 text-green-600' : 'bg-red-500/20 border-red-600 text-red-600' ?> border rounded-lg p-4 backdrop-blur-sm text-center">
                    <?= $mensagem ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (empty($produtos)): ?>
            <!-- Estado Vazio -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <p class="text-2xl text-gray-300 mb-4">
                        <i class="fas fa-frown text-gray-400 mr-2"></i>
                        Nenhum produto encontrado.
                    </p>
                    <p class="text-lg text-gray-400">Tente ajustar os filtros ou verifique novamente mais tarde.</p>
                </div>
            </div>
        <?php else: ?>
            <!-- Grid de Produtos - CENTRALIZADO -->
            <div class="flex justify-center mb-12">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 justify-items-center">
                    <?php foreach ($produtos as $produto): ?>
                        <div class="bg-gray-900/95 border-2 border-cyan-400 rounded-2xl p-6 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-cyan-500/30 backdrop-blur-sm animate-fade-in-up group w-full max-w-sm">
                            <!-- Modo Visualização -->
                            <div class="view-mode">
                                <!-- Nome do Produto -->
                                <h3 class="text-xl font-bold text-cyan-400 mb-4 pb-3 border-b border-cyan-400/30 group-hover:text-cyan-300 transition-colors text-center">
                                    <?= htmlspecialchars($produto['nome']) ?>
                                </h3>

                                <!-- Informações do Produto -->
                                <div class="space-y-3 mb-4">
                                    <p class="text-gray-300 text-center">
                                        <strong class="text-cyan-400">
                                            <i class="fas fa-id-card mr-2"></i>Modelo:
                                        </strong><br>
                                        <?= htmlspecialchars($produto['modelo']) ?>
                                    </p>
                                    <p class="text-gray-300 text-center">
                                        <strong class="text-cyan-400">
                                            <i class="fas fa-palette mr-2"></i>Cor:
                                        </strong><br>
                                        <?= htmlspecialchars($produto['cor']) ?>
                                    </p>
                                    <p class="text-gray-300 text-center">
                                        <strong class="text-cyan-400">
                                            <i class="fas fa-boxes mr-2"></i>Quantidade:
                                        </strong><br>
                                        <?= $produto['quantidade'] ?>
                                    </p>
                                </div>

                                <!-- Imagem do Produto -->
                                <?php if (!empty($produto['imagem'])): ?>
                                    <div class="mb-4 flex justify-center">
                                        <img
                                            src="<?= htmlspecialchars($produto['imagem']) ?>"
                                            alt="Imagem do produto <?= htmlspecialchars($produto['nome']) ?>"
                                            class="w-full max-w-xs h-48 object-cover rounded-xl border-2 border-cyan-400 shadow-lg shadow-cyan-500/20" />
                                    </div>
                                <?php else: ?>
                                    <div class="mb-4 text-center py-8 bg-gray-800/50 rounded-xl border border-cyan-400/30">
                                        <p class="text-gray-400 italic">
                                            <i class="fas fa-image mr-2"></i>
                                            Imagem não disponível
                                        </p>
                                    </div>
                                <?php endif; ?>

                                <!-- Ações do Admin -->
                                <?php if ($isAdmin): ?>
                                    <div class="flex flex-col sm:flex-row gap-3 mt-6">
                                        <button class="btn-editar flex-1 bg-gradient-to-r from-green-400 to-green-600 text-gray-900 font-bold py-2 px-4 rounded-lg hover:shadow-lg hover:shadow-green-500/40 transform hover:-translate-y-1 transition-all duration-300">
                                            <i class="fas fa-edit mr-2"></i>
                                            Editar
                                        </button>
                                        <form method="post" action="" class="flex-1" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                                            <input type="hidden" name="excluir_id" value="<?= $produto['id'] ?>" />
                                            <button type="submit" class="w-full bg-gradient-to-r from-red-400 to-red-600 text-white font-bold py-2 px-4 rounded-lg hover:shadow-lg hover:shadow-red-500/40 transform hover:-translate-y-1 transition-all duration-300">
                                                <i class="fas fa-trash mr-2"></i>
                                                Excluir
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Modo Edição (Admin) -->
                            <?php if ($isAdmin): ?>
                                <form class="edit-mode hidden mt-6 bg-gray-800/50 border-2 border-blue-500 rounded-xl p-4 space-y-4">
                                    <input type="hidden" name="id" value="<?= $produto['id'] ?>" />

                                    <div>
                                        <label class="block text-cyan-400 font-bold text-sm uppercase tracking-wider mb-2 text-center">
                                            <i class="fas fa-tag mr-2"></i>
                                            Nome:
                                        </label>
                                        <input
                                            type="text"
                                            name="nome"
                                            value="<?= htmlspecialchars($produto['nome']) ?>"
                                            required
                                            class="w-full bg-black/90 border border-cyan-400 rounded-lg py-2 px-3 text-white focus:outline-none focus:border-blue-500 focus:shadow-lg focus:shadow-cyan-500/50 text-center" />
                                    </div>

                                    <div>
                                        <label class="block text-cyan-400 font-bold text-sm uppercase tracking-wider mb-2 text-center">
                                            <i class="fas fa-id-card mr-2"></i>
                                            Modelo:
                                        </label>
                                        <input
                                            type="text"
                                            name="modelo"
                                            value="<?= htmlspecialchars($produto['modelo']) ?>"
                                            required
                                            class="w-full bg-black/90 border border-cyan-400 rounded-lg py-2 px-3 text-white focus:outline-none focus:border-blue-500 focus:shadow-lg focus:shadow-cyan-500/50 text-center" />
                                    </div>

                                    <div>
                                        <label class="block text-cyan-400 font-bold text-sm uppercase tracking-wider mb-2 text-center">
                                            <i class="fas fa-palette mr-2"></i>
                                            Cor:
                                        </label>
                                        <input
                                            type="text"
                                            name="cor"
                                            value="<?= htmlspecialchars($produto['cor']) ?>"
                                            required
                                            class="w-full bg-black/90 border border-cyan-400 rounded-lg py-2 px-3 text-white focus:outline-none focus:border-blue-500 focus:shadow-lg focus:shadow-cyan-500/50 text-center" />
                                    </div>

                                    <div>
                                        <label class="block text-cyan-400 font-bold text-sm uppercase tracking-wider mb-2 text-center">
                                            <i class="fas fa-boxes mr-2"></i>
                                            Quantidade:
                                        </label>
                                        <input
                                            type="number"
                                            name="quantidade"
                                            value="<?= $produto['quantidade'] ?>"
                                            required
                                            min="1"
                                            class="w-full bg-black/90 border border-cyan-400 rounded-lg py-2 px-3 text-white focus:outline-none focus:border-blue-500 focus:shadow-lg focus:shadow-cyan-500/50 text-center" />
                                    </div>

                                    <div>
                                        <label class="block text-cyan-400 font-bold text-sm uppercase tracking-wider mb-2 text-center">
                                            <i class="fas fa-image mr-2"></i>
                                            Imagem:
                                        </label>
                                        <input
                                            type="file"
                                            name="imagem"
                                            accept="image/*"
                                            class="w-full text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-500 file:text-gray-900 hover:file:bg-cyan-400" />
                                    </div>

                                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                                        <button type="submit" class="flex-1 bg-gradient-to-r from-cyan-400 to-blue-500 text-gray-900 font-bold py-2 px-4 rounded-lg hover:shadow-lg hover:shadow-cyan-500/40 transform hover:-translate-y-1 transition-all duration-300">
                                            <i class="fas fa-save mr-2"></i>
                                            Salvar
                                        </button>
                                        <button type="button" class="btn-cancelar flex-1 bg-gradient-to-r from-gray-400 to-gray-600 text-white font-bold py-2 px-4 rounded-lg hover:shadow-lg hover:shadow-gray-500/40 transform hover:-translate-y-1 transition-all duration-300">
                                            <i class="fas fa-times mr-2"></i>
                                            Cancelar
                                        </button>
                                    </div>

                                    <div class="msg-resultado text-center text-sm font-semibold mt-3"></div>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <?php include_once '../components/footer.php'; ?>

    <script>
        // Efeito de partículas para home (mantenha seu código)
        function createHomeParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 30;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');

                // Posição aleatória
                particle.style.left = Math.random() * 100 + 'vw';
                particle.style.animationDelay = Math.random() * 15 + 's';
                particle.style.animationDuration = (10 + Math.random() * 10) + 's';

                particlesContainer.appendChild(particle);
            }
        }

        // Inicializar partículas quando a página carregar
        document.addEventListener('DOMContentLoaded', createHomeParticles);

        // JavaScript para controle dos modos de visualização/edição
        document.querySelectorAll('.btn-editar').forEach(btn => {
            btn.addEventListener('click', () => {
                const produtoDiv = btn.closest('.bg-gray-900');
                produtoDiv.querySelector('.view-mode').classList.add('hidden');
                produtoDiv.querySelector('.edit-mode').classList.remove('hidden');
            });
        });

        document.querySelectorAll('.btn-cancelar').forEach(btn => {
            btn.addEventListener('click', () => {
                const produtoDiv = btn.closest('.bg-gray-900');
                produtoDiv.querySelector('.edit-mode').classList.add('hidden');
                produtoDiv.querySelector('.view-mode').classList.remove('hidden');
            });
        });

        document.querySelectorAll('.edit-mode').forEach(form => {
            form.addEventListener('submit', e => {
                e.preventDefault();

                const formData = new FormData(form);
                formData.append('action', 'editar');

                fetch('produtos.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        const msgDiv = form.querySelector('.msg-resultado');

                        // Aplicar o mesmo estilo das outras mensagens
                        msgDiv.className = 'msg-resultado border rounded-lg p-4 mb-6 backdrop-blur-sm text-center';

                        if (data.status === 'success') {
                            msgDiv.classList.add('bg-green-500/20', 'border-green-600', 'text-green-600');
                            msgDiv.textContent = data.msg;

                            // Atualizar dados na view
                            const produtoDiv = form.closest('.bg-gray-900');
                            const viewMode = produtoDiv.querySelector('.view-mode');

                            viewMode.querySelector('h3').textContent = form.nome.value;
                            viewMode.querySelector('p:nth-of-type(1)').innerHTML = `<strong class="text-cyan-400"><i class="fas fa-id-card mr-2"></i>Modelo:</strong> ${form.modelo.value}`;
                            viewMode.querySelector('p:nth-of-type(2)').innerHTML = `<strong class="text-cyan-400"><i class="fas fa-palette mr-2"></i>Cor:</strong> ${form.cor.value}`;
                            viewMode.querySelector('p:nth-of-type(3)').innerHTML = `<strong class="text-cyan-400"><i class="fas fa-boxes mr-2"></i>Quantidade:</strong> ${form.quantidade.value}`;

                            // Atualizar imagem se foi enviada nova
                            if (form.imagem.files.length > 0) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    let imgContainer = viewMode.querySelector('div:has(img)');
                                    if (!imgContainer) {
                                        imgContainer = document.createElement('div');
                                        imgContainer.className = 'mb-4';
                                        viewMode.insertBefore(imgContainer, viewMode.querySelector('.flex'));
                                    }
                                    let img = imgContainer.querySelector('img');
                                    if (!img) {
                                        img = document.createElement('img');
                                        img.className = 'w-full h-48 object-cover rounded-xl border-2 border-cyan-400 shadow-lg shadow-cyan-500/20';
                                        imgContainer.appendChild(img);
                                    }
                                    img.src = e.target.result;
                                }
                                reader.readAsDataURL(form.imagem.files[0]);
                            }

                            setTimeout(() => {
                                form.classList.add('hidden');
                                viewMode.classList.remove('hidden');
                                msgDiv.textContent = '';
                                msgDiv.className = 'msg-resultado';
                            }, 2000);
                        } else {
                            msgDiv.classList.add('bg-red-500/20', 'border-red-600', 'text-red-600');
                            msgDiv.textContent = data.msg;

                            setTimeout(() => {
                                msgDiv.textContent = '';
                                msgDiv.className = 'msg-resultado';
                            }, 3000);
                        }
                    })
                    .catch(() => {
                        const msgDiv = form.querySelector('.msg-resultado');
                        msgDiv.className = 'msg-resultado border rounded-lg p-4 mb-6 backdrop-blur-sm text-center bg-red-500/20 border-red-600 text-red-600';
                        msgDiv.textContent = '<i class="fas fa-exclamation-triangle mr-2"></i> Erro na comunicação com o servidor.';

                        setTimeout(() => {
                            msgDiv.textContent = '';
                            msgDiv.className = 'msg-resultado';
                        }, 3000);
                    });
            });
        });
    </script>
</body>

</html>