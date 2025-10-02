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
    $mensagem = cadastro();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Guri Games</title>
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
                            '0%, 100%': { opacity: 0.5 },
                            '50%': { opacity: 1 },
                        },
                        floatParticle: {
                            '0%': { transform: 'translateY(100vh) translateX(0) rotate(0deg)', opacity: 0 },
                            '10%': { opacity: 1 },
                            '90%': { opacity: 1 },
                            '100%': { transform: 'translateY(-100px) translateX(100px) rotate(360deg)', opacity: 0 },
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
        <div class="w-full max-w-lg">
            <!-- Card de Cadastro -->
            <div class="bg-gray-900/95 border-2 border-cyan-400 rounded-2xl p-10 md:p-12 text-center relative overflow-hidden shadow-2xl shadow-cyan-500/30">
                <!-- Efeito de borda animada -->
                <div class="absolute inset-0 rounded-2xl border-2 border-transparent bg-gradient-to-r from-cyan-400 to-blue-500 opacity-50 animate-border-glow -z-10"></div>

                <!-- Título -->
                <h1 class="text-3xl md:text-4xl font-black mb-10 bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent uppercase tracking-wider relative inline-block mx-auto">
                    🚀 Cadastro
                    <div class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-cyan-400 to-blue-500 mt-8 shadow-lg shadow-cyan-500/50"></div>
                </h1>

                <!-- Mensagens -->
                <?php if ($_SERVER["REQUEST_METHOD"] === "POST" && $mensagem): ?>
                    <div class="<?= strpos($mensagem, 'sucesso') !== false ? 'bg-green-500/20 border-green-600 text-green-600' : 'bg-red-500/20 border-red-600 text-red-600' ?> border rounded-lg p-4 mb-6 backdrop-blur-sm">
                        <?= $mensagem ?>
                    </div>
                    <?php if (strpos($mensagem, 'sucesso') !== false): ?>
                        <script>
                            setTimeout(() => {
                                window.location.href = './login.php';
                            }, 3000)
                        </script>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Formulário -->
                <form method="post" class="space-y-6" novalidate>
                    
                    <!-- Usuário -->
                    <div class="text-left">
                        <label for="username" class="block text-cyan-400 font-bold uppercase tracking-wider text-sm mb-2">
                            Usuário:
                        </label>
                        <input type="text" id="username" name="username" required minlength="3"
                               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                               placeholder="Escolha um nome de usuário"
                               class="w-full bg-black/90 border border-cyan-400 rounded-xl py-3 px-5 text-white transition-all duration-300 focus:outline-none focus:border-blue-500 focus:shadow-lg focus:shadow-cyan-500/40">
                    </div>

                    <!-- Nome Completo -->
                    <div class="text-left">
                        <label for="nome" class="block text-cyan-400 font-bold uppercase tracking-wider text-sm mb-2">
                            Nome Completo:
                        </label>
                        <input type="text" id="nome" name="nome" required minlength="3"
                               value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>"
                               placeholder="Seu nome completo"
                               class="w-full bg-black/90 border border-cyan-400 rounded-xl py-3 px-5 text-white transition-all duration-300 focus:outline-none focus:border-blue-500 focus:shadow-lg focus:shadow-cyan-500/40">
                    </div>

                    <!-- Email -->
                    <div class="text-left">
                        <label for="email" class="block text-cyan-400 font-bold uppercase tracking-wider text-sm mb-2">
                            Email:
                        </label>
                        <input type="email" id="email" name="email" required
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               placeholder="seu@email.com"
                               class="w-full bg-black/90 border border-cyan-400 rounded-xl py-3 px-5 text-white transition-all duration-300 focus:outline-none focus:border-blue-500 focus:shadow-lg focus:shadow-cyan-500/40">
                    </div>

                    <!-- Data de nascimento -->
                    <div class="text-left">
                        <label for="birth" class="block text-cyan-400 font-bold uppercase tracking-wider text-sm mb-2">
                            Data de Nascimento:
                        </label>
                        <input type="date" id="birth" name="birth" required
                               value="<?= htmlspecialchars($_POST['birth'] ?? '') ?>"
                               max="<?= date('Y-m-d'); ?>"
                               class="w-full bg-black/90 border border-cyan-400 rounded-xl py-3 px-5 text-white transition-all duration-300 focus:outline-none focus:border-blue-500 focus:shadow-lg focus:shadow-cyan-500/40">
                    </div>

                    <!-- Senha -->
                    <div class="text-left">
                        <label for="password" class="block text-cyan-400 font-bold uppercase tracking-wider text-sm mb-2">
                            Senha:
                        </label>
                        <input type="password" id="password" name="password" required minlength="6"
                               placeholder="Mínimo 6 caracteres"
                               class="w-full bg-black/90 border border-cyan-400 rounded-xl py-3 px-5 text-white transition-all duration-300 focus:outline-none focus:border-blue-500 focus:shadow-lg focus:shadow-cyan-500/40">
                        <div class="h-2 mt-2 w-full bg-gray-700 rounded-full overflow-hidden">
                            <div id="password-strength-bar" class="h-2 bg-cyan-400 transition-all duration-300"></div>
                        </div>
                    </div>

                    <!-- Confirmação de senha -->
                    <div class="text-left">
                        <label for="password-confirm" class="block text-cyan-400 font-bold uppercase tracking-wider text-sm mb-2">
                            Confirmar Senha:
                        </label>
                        <input type="password" id="password-confirm" name="password-confirm" required minlength="6"
                               placeholder="Digite a senha novamente"
                               class="w-full bg-black/90 border border-cyan-400 rounded-xl py-3 px-5 text-white transition-all duration-300 focus:outline-none focus:border-blue-500 focus:shadow-lg focus:shadow-cyan-500/40">
                    </div>

                    <!-- Botão -->
                    <button type="submit"
                            class="cta-button inline-block bg-gradient-to-r from-cyan-400 to-blue-500 text-gray-900 font-bold py-4 px-8 rounded-lg text-lg uppercase tracking-wide hover:shadow-2xl hover:shadow-cyan-500/40 transform hover:-translate-y-1 transition-all duration-300 w-full">
                        Criar Conta
                    </button>
                </form>

                <!-- Link para login -->
                <p class="mt-8 text-gray-300 text-base">
                    Já tem uma conta?
                    <a href="./login.php"
                       class="relative font-bold text-cyan-400 transition-all duration-300 mx-1
                       after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-bottom-1
                       after:h-[2px] after:w-0 after:bg-cyan-400 after:transition-all after:duration-300 
                       hover:after:w-full">
                       Faça login
                    </a>
                </p>
            </div>
        </div>
    </main>

    <?php include_once '../components/footer.php'; ?>

    <script>
        // Partículas
        function createRegistroParticles() {
            const particlesContainer = document.getElementById('particles');
            if (!particlesContainer) return;

            const particleCount = 25;
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                particle.style.left = Math.random() * 100 + 'vw';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (15 + Math.random() * 15) + 's';
                particlesContainer.appendChild(particle);
            }
        }
        document.addEventListener('DOMContentLoaded', createRegistroParticles);

        // Força da senha
        function checkPasswordStrength(password) {
            const bar = document.getElementById('password-strength-bar');
            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            bar.style.width = `${(strength / 5) * 100}%`;
            bar.className = "h-2 transition-all duration-300 " +
                (strength <= 2 ? "bg-red-500" :
                 strength <= 4 ? "bg-yellow-400" : "bg-green-500");
        }

        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            passwordInput.addEventListener('input', function () {
                checkPasswordStrength(this.value);
            });
        }

        // Confirmação de senha
        const passwordConfirm = document.getElementById('password-confirm');
        if (passwordConfirm) {
            passwordConfirm.addEventListener('input', function () {
                const password = document.getElementById('password').value;
                this.style.borderColor = (this.value !== password) ? '#ff5555' : '#00ffaa';
            });
        }

        // Botão loading
        document.querySelector('form').addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            button.innerHTML = '<i class="fas fa-spinner animate-spin-slow"></i>';
            button.disabled = true;
        });
    </script>
</body>
</html>
