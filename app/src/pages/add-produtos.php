<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== 1) {
    echo "<script>alert('Você precisa estar logado para acessar esta página!');</script>";
    header("Refresh: 0;url=./login.php");
    exit();
}

$nome = $_POST['nome'] ?? '';
$modelo = $_POST['modelo'] ?? '';
$cor = $_POST['cor'] ?? '';
$quantidade = $_POST['quantidade'] ?? '';

require_once '../scripts/conectarBanco.php';
require_once '../scripts/func_produtos.php';

function verificarDados($db, $nome, $modelo, $cor, $quantidade, $arquivoImagem)
{
    if (strlen($nome) < 2 || strlen($modelo) < 2 || strlen($cor) < 2) {
        return "Todos os campos devem ter pelo menos 2 caracteres.";
    }
    if (!is_numeric($quantidade) || $quantidade <= 0) {
        return "Quantidade inválida.";
    }
    $stmt = $db->prepare("SELECT COUNT(*) FROM produtos WHERE modelo = ?");
    $stmt->execute([$modelo]);
    if ($stmt->fetchColumn() > 0) {
        return "Já existe um produto com esse modelo.";
    }

    // Validação da imagem
    if ($arquivoImagem['error'] !== UPLOAD_ERR_OK) {
        return "Erro no envio da imagem.";
    }

    $permitidas = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    // Verificar o tipo MIME real do arquivo
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($arquivoImagem['tmp_name']);

    if (!in_array($mime, $permitidas)) {
        return "Tipo de imagem inválido. Use JPG, PNG, GIF ou WEBP.";
    }

    $tamanhoMaxMB = 2;
    if ($arquivoImagem['size'] > $tamanhoMaxMB * 1024 * 1024) {
        return "Imagem muito grande. O limite é {$tamanhoMaxMB}MB.";
    }

    return ''; // sem erros
}

function salvarImagem($arquivo)
{
    if ($arquivo['error'] !== UPLOAD_ERR_OK) return "Erro ao enviar imagem.";

    $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $permitidas)) return "Extensão de imagem inválida.";

    $uploadDir = __DIR__ . '/../uploads/'; // Pasta fora de /pages
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $nomeSeguro = uniqid('produto_', true) . "." . $ext;
    $destinoAbsoluto = $uploadDir . $nomeSeguro;

    if (!move_uploaded_file($arquivo['tmp_name'], $destinoAbsoluto)) {
        return "Erro ao mover a imagem.";
    }

    // Caminho relativo para acesso via HTML a partir da pasta /pages
    return '../uploads/' . $nomeSeguro;
}

function cadastrarProduto($db, $nome, $modelo, $cor, $quantidade, $imagemPath)
{
    $stmt = $db->prepare("INSERT INTO produtos (nome, modelo, cor, quantidade, imagem) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$nome, $modelo, $cor, $quantidade, $imagemPath]);
}

$mensagem = "";
$db = conectarBanco();

$mensagem_cor = 'darkred';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $modelo = trim($_POST['modelo']);
    $cor = trim($_POST['cor']);
    $quantidade = trim($_POST['quantidade']);
    $imagemFile = $_FILES['imagem'];

    $erroValidacao = verificarDados($db, $nome, $modelo, $cor, $quantidade, $imagemFile);
    if ($erroValidacao !== '') {
        $mensagem = mensagem($erroValidacao, 'ERROR');
    } else {
        $imagemPath = salvarImagem($_FILES['imagem']);
        if (str_contains($imagemPath, 'Erro')) {
            $mensagem = mensagem($imagemPath, 'ERROR');
        } else {
            if (cadastrarProduto($db, $nome, $modelo, $cor, (int)$quantidade, $imagemPath)) {
                $mensagem = mensagem('Produto adicionado com sucesso!', 'SUCCESS');
                $nome = $modelo = $cor = $quantidade = '';
            } else {
                $mensagem = mensagem('Erro ao adicionar produto', 'ERROR');
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produtos - Guri Games</title>

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
</head>

<body class="min-h-screen bg-gray-900 text-white flex flex-col relative overflow-x-hidden">

    <?php include_once '../components/navbar.php'; ?>

    <!-- Efeito de partículas -->
    <div class="particles" id="particles"></div>

    <main class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="w-full max-w-2xl">
            <!-- Card do Formulário -->
            <div class="bg-gray-900/95 backdrop-blur-md border-2 border-cyan-400 rounded-2xl p-8 md:p-12 shadow-2xl shadow-cyan-500/30 animate-fade-in-up">

                <!-- Cabeçalho -->
                <div class="text-center mb-10">
                    <h1 class="text-3xl md:text-4xl font-black mb-4 bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent uppercase tracking-wider">
                        <i class="fas fa-plus-circle mr-3"></i>
                        Adicionar Produto
                    </h1>
                    <p class="text-gray-300 text-lg mb-6">Preencha os dados do novo produto</p>
                    <div class="w-48 h-1 bg-gradient-to-r from-cyan-400 to-blue-500 mx-auto rounded-full shadow-lg shadow-cyan-500/50"></div>
                </div>

                <!-- Mensagens -->
                <?php if ($_SERVER["REQUEST_METHOD"] === "POST" && $mensagem): ?>
                    <div class="<?= strpos($mensagem, 'sucesso') !== false || strpos($mensagem, 'logado') !== false ? 'bg-green-500/20 border-green-600 text-green-600' : 'bg-red-500/20 border-red-600 text-red-600' ?> border rounded-lg p-4 mb-6 backdrop-blur-sm">
                        <?= $mensagem ?>
                    </div>
                    <?php if (strpos($mensagem, 'sucesso') !== false || strpos($mensagem, 'logado') !== false): ?>
                        <script>
                            setTimeout(() => {
                                window.location.href = '../index.php';
                            }, 3000)
                        </script>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Formulário -->
                <form action="" method="post" enctype="multipart/form-data" class="space-y-8">
                    <!-- Nome -->
                    <div class="text-left">
                        <label for="nome" class="block text-cyan-400 font-bold uppercase tracking-wider text-sm mb-3 flex items-center">
                            <i class="fas fa-tag mr-2"></i>
                            Nome do Produto:
                        </label>
                        <input
                            type="text"
                            id="nome"
                            name="nome"
                            required
                            value="<?= htmlspecialchars($nome) ?>"
                            class="w-full bg-black/90 border border-cyan-400 rounded-xl py-4 px-5 text-white transition-all duration-300 focus:outline-none focus:border-blue-500 focus:shadow-2xl focus:shadow-cyan-500/50 focus:-translate-y-1 shadow-lg shadow-cyan-500/20"
                            placeholder="Digite o nome do produto">
                    </div>

                    <!-- Modelo -->
                    <div class="text-left">
                        <label for="modelo" class="block text-cyan-400 font-bold uppercase tracking-wider text-sm mb-3 flex items-center">
                            <i class="fas fa-laptop mr-2"></i>
                            Modelo:
                        </label>
                        <input
                            type="text"
                            id="modelo"
                            name="modelo"
                            required
                            value="<?= htmlspecialchars($modelo) ?>"
                            class="w-full bg-black/90 border border-cyan-400 rounded-xl py-4 px-5 text-white transition-all duration-300 focus:outline-none focus:border-blue-500 focus:shadow-2xl focus:shadow-cyan-500/50 focus:-translate-y-1 shadow-lg shadow-cyan-500/20"
                            placeholder="Digite o modelo do produto">
                    </div>

                    <!-- Cor -->
                    <div class="text-left">
                        <label for="cor" class="block text-cyan-400 font-bold uppercase tracking-wider text-sm mb-3 flex items-center">
                            <i class="fas fa-palette mr-2"></i>
                            Cor:
                        </label>
                        <input
                            type="text"
                            id="cor"
                            name="cor"
                            required
                            value="<?= htmlspecialchars($cor) ?>"
                            class="w-full bg-black/90 border border-cyan-400 rounded-xl py-4 px-5 text-white transition-all duration-300 focus:outline-none focus:border-blue-500 focus:shadow-2xl focus:shadow-cyan-500/50 focus:-translate-y-1 shadow-lg shadow-cyan-500/20"
                            placeholder="Digite a cor do produto">
                    </div>

                    <!-- Quantidade -->
                    <div class="text-left">
                        <label for="quantidade" class="block text-cyan-400 font-bold uppercase tracking-wider text-sm mb-3 flex items-center">
                            <i class="fas fa-boxes mr-2"></i>
                            Quantidade:
                        </label>
                        <input
                            type="number"
                            id="quantidade"
                            name="quantidade"
                            min="1"
                            required
                            value="<?= htmlspecialchars($quantidade) ?>"
                            class="w-full bg-black/90 border border-cyan-400 rounded-xl py-4 px-5 text-white transition-all duration-300 focus:outline-none focus:border-blue-500 focus:shadow-2xl focus:shadow-cyan-500/50 focus:-translate-y-1 shadow-lg shadow-cyan-500/20"
                            placeholder="Digite a quantidade">
                    </div>

                    <!-- Imagem -->
                    <div class="text-left">
                        <label for="imagem" class="block text-cyan-400 font-bold uppercase tracking-wider text-sm mb-3 flex items-center">
                            <i class="fas fa-image mr-2"></i>
                            Imagem do Produto:
                        </label>
                        <input
                            type="file"
                            id="imagem"
                            name="imagem"
                            accept="image/*"
                            required
                            class="w-full text-white file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-cyan-500 file:text-gray-900 hover:file:bg-cyan-400 transition-all duration-300">
                        <p class="text-gray-400 text-xs mt-2">Formatos permitidos: JPG, PNG, GIF, WEBP (Máx. 2MB)</p>
                    </div>

                    <!-- Botão Submit -->
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-cyan-400 to-blue-500 text-gray-900 font-bold py-4 px-8 rounded-xl text-lg uppercase tracking-wide hover:shadow-2xl hover:shadow-cyan-500/40 transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Adicionar Produto
                    </button>
                </form>

                <!-- Link para voltar -->
                <div class="text-center mt-8 pt-6 border-t border-cyan-400/20">
                    <a href="../pages/produtos.php"
                        class="text-cyan-400 hover:text-blue-500 font-semibold transition-all duration-300 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar para Lista de Produtos
                    </a>
                </div>
            </div>
        </div>
    </main>

    <?php include_once '../components/footer.php'; ?>

    <script>
        // Efeito de partículas
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            if (!particlesContainer) return;

            const particleCount = 30;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');

                // Posição aleatória
                particle.style.left = Math.random() * 100 + 'vw';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (15 + Math.random() * 10) + 's';

                particlesContainer.appendChild(particle);
            }
        }

        // Inicializar partículas quando a página carregar
        document.addEventListener('DOMContentLoaded', createParticles);

        // Efeito de loading no botão
        document.querySelector('form').addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner animate-spin mr-2"></i> Processando...';
            button.disabled = true;

            // Restaurar após 5 segundos (caso algo dê errado)
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 5000);
        });
    </script>
</body>

</html>