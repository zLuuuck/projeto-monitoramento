<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../scripts/conectarBanco.php";
require_once "../scripts/funcLogin.php";

verificarSeEstaLogado('Logado');

$mensagem = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mensagem = login();
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Guri Games</title>
    <link rel="icon" type="image/x-icon" href="../guri_games_icon.png">

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
                        'spin-slow': 'spin 1s linear infinite',
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
                        }
                    }
                }
            }
        }
    </script>

    <script src="https://kit.fontawesome.com/0dc50eaa4b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../styles/style.css">
</head>

<body class="min-h-screen bg-gray-900 text-white flex flex-col relative overflow-x-hidden">

    <?php include_once '../components/navbar.php'; ?>

    <!-- Efeito de partículas -->
    <div class="particles" id="particles"></div>

    <main class="flex-1 flex items-center justify-center p-4 md:p-6 relative z-10">
        <div class="w-full max-w-md">
            <!-- Card de Login -->
            <div class="bg-gray-900/95 border-2 border-cyan-400 rounded-2xl p-12 md:p-14 text-center relative overflow-hidden shadow-2xl shadow-cyan-500/30">
                <!-- Efeito de borda animada -->
                <div class="absolute inset-0 rounded-2xl border-2 border-transparent bg-gradient-to-r from-cyan-400 to-blue-500 opacity-50 animate-border-glow -z-10"></div>

                <!-- Título -->
                <h1 class="text-3xl md:text-4xl font-black mb-12 bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent uppercase tracking-wider relative inline-block mx-auto">
                    🎮 Login
                    <div class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-cyan-400 to-blue-500 mt-10 shadow-lg shadow-cyan-500/50"></div>
                </h1>

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
                <form action="login.php" method="post" class="space-y-8">
                    <!-- Campo Usuário -->
                    <div class="text-left">
                        <label for="username" class="block text-cyan-400 font-bold uppercase tracking-wider text-sm mb-3 text-shadow shadow-cyan-500/50">
                            Usuário:
                        </label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            required
                            class="w-full bg-black/90 border border-cyan-400 rounded-xl py-4 px-5 text-white transition-all duration-300 focus:outline-none focus:border-blue-500 focus:shadow-2xl focus:shadow-cyan-500/50 focus:-translate-y-1 shadow-lg shadow-cyan-500/20">
                    </div>

                    <!-- Campo Senha -->
                    <div class="text-left">
                        <label for="password" class="block text-cyan-400 font-bold uppercase tracking-wider text-sm mb-3 text-shadow shadow-cyan-500/50">
                            Senha:
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="w-full bg-black/90 border border-cyan-400 rounded-xl py-4 px-5 text-white transition-all duration-300 focus:outline-none focus:border-blue-500 focus:shadow-2xl focus:shadow-cyan-500/50 focus:-translate-y-1 shadow-lg shadow-cyan-500/20">
                    </div>

                    <!-- Botão Submit -->
                    <button
                        type="submit"
                        class="cta-button inline-block bg-gradient-to-r from-cyan-400 to-blue-500 text-gray-900 font-bold py-4 px-8 rounded-lg text-lg md:text-xl uppercase tracking-wide hover:shadow-2xl hover:shadow-cyan-500/40 transform hover:-translate-y-1 transition-all duration-300 w-full">
                        Entrar
                    </button>
                </form>

                <!-- Link para cadastro -->
                <p class="mt-10 text-gray-300 text-base">
                    Não tem uma conta?
                    <a href="./registro.php"
                        class="relative font-bold text-cyan-400 transition-all duration-300 mx-1
                        after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-bottom-1
                        after:h-[2px] after:w-0 after:bg-cyan-400 after:transition-all after:duration-300 
                        hover:after:w-full">
                        Cadastre-se
                    </a>
                </p>
            </div>
        </div>
    </main>

    <?php include_once '../components/footer.php'; ?>

    <script>
        // Efeito de partículas
        function createLoginParticles() {
            const particlesContainer = document.getElementById('particles');
            if (!particlesContainer) return;

            const particleCount = 20;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');

                particle.style.left = Math.random() * 100 + 'vw';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (15 + Math.random() * 15) + 's';

                particlesContainer.appendChild(particle);
            }
        }

        document.addEventListener('DOMContentLoaded', createLoginParticles);

        // Efeito de loading no botão
        document.querySelector('form').addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner animate-spin-slow"></i>';
            button.disabled = true;

            // Restaurar após 3 segundos (caso algo dê errado)
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 3000);
        });
    </script>
</body>

</html>