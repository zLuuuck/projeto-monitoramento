<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../scripts/funcLogin.php";

verificarSeEstaLogado("Deslogado");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Guri Games</title>
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

<body class="min-h-screen bg-gray-900 text-white flex flex-col relative overflow-x-hidden">

    <?php include_once '../components/navbar.php'; ?>

    <!-- Efeito de partículas -->
    <div class="particles" id="particles"></div>

    <main class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="w-full max-w-2xl">
            <!-- Card do Perfil -->
            <div class="bg-gray-900/95 backdrop-blur-md border-2 border-cyan-400 rounded-2xl p-8 md:p-12 shadow-2xl shadow-cyan-500/30 animate-fade-in-up">

                <!-- Cabeçalho -->
                <div class="text-center mb-10">
                    <div class="flex justify-center mb-6">
                        <div class="w-24 h-24 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full flex items-center justify-center shadow-2xl shadow-cyan-500/50">
                            <i class="fas fa-user text-3xl text-gray-900"></i>
                        </div>
                    </div>

                    <h1 class="text-3xl md:text-4xl font-black bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent uppercase tracking-wider mb-4">
                        Seu Perfil
                    </h1>
                    <div class="w-32 h-1 bg-gradient-to-r from-cyan-400 to-blue-500 mx-auto rounded-full shadow-lg shadow-cyan-500/50"></div>
                </div>

                <!-- Informações do Perfil -->
                <div class="space-y-6">
                    <!-- ID -->
                    <div class="group">
                        <label class="block text-cyan-400 font-bold text-sm uppercase tracking-wider mb-3 flex items-center">
                            <i class="fas fa-id-card mr-2"></i>
                            ID:
                        </label>
                        <div class="bg-black/90 border border-cyan-400 rounded-xl py-4 px-5 text-white transition-all duration-300 group-hover:shadow-2xl group-hover:shadow-cyan-500/30 group-hover:-translate-y-1">
                            <span class="font-semibold"><?php echo htmlspecialchars($_SESSION['user_id']); ?></span>
                        </div>
                    </div>

                    <!-- Nome de Usuário -->
                    <div class="group">
                        <label class="block text-cyan-400 font-bold text-sm uppercase tracking-wider mb-3 flex items-center">
                            <i class="fas fa-user-circle mr-2"></i>
                            Nome de Usuário:
                        </label>
                        <div class="bg-black/90 border border-cyan-400 rounded-xl py-4 px-5 text-white transition-all duration-300 group-hover:shadow-2xl group-hover:shadow-cyan-500/30 group-hover:-translate-y-1">
                            <span class="font-semibold"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        </div>
                    </div>

                    <!-- Nome Completo -->
                    <div class="group">
                        <label class="block text-cyan-400 font-bold text-sm uppercase tracking-wider mb-3 flex items-center">
                            <i class="fas fa-user mr-2"></i>
                            Nome Completo:
                        </label>
                        <div class="bg-black/90 border border-cyan-400 rounded-xl py-4 px-5 text-white transition-all duration-300 group-hover:shadow-2xl group-hover:shadow-cyan-500/30 group-hover:-translate-y-1">
                            <span class="font-semibold"><?php echo htmlspecialchars($_SESSION['nome']); ?></span>
                        </div>
                    </div>

                    <!-- E-mail -->
                    <div class="group">
                        <label class="block text-cyan-400 font-bold text-sm uppercase tracking-wider mb-3 flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            E-mail:
                        </label>
                        <div class="bg-black/90 border border-cyan-400 rounded-xl py-4 px-5 text-white transition-all duration-300 group-hover:shadow-2xl group-hover:shadow-cyan-500/30 group-hover:-translate-y-1">
                            <span class="font-semibold"><?php echo htmlspecialchars($_SESSION['email']); ?></span>
                        </div>
                    </div>

                    <!-- Data de Nascimento -->
                    <div class="group">
                        <label class="block text-cyan-400 font-bold text-sm uppercase tracking-wider mb-3 flex items-center">
                            <i class="fas fa-birthday-cake mr-2"></i>
                            Data de Nascimento:
                        </label>
                        <div class="bg-black/90 border border-cyan-400 rounded-xl py-4 px-5 text-white transition-all duration-300 group-hover:shadow-2xl group-hover:shadow-cyan-500/30 group-hover:-translate-y-1">
                            <span class="font-semibold"><?php echo htmlspecialchars(date('d/m/Y', strtotime($_SESSION['birth']))); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="mt-10 pt-8 border-t border-cyan-400/20">

                    <a href="../pages/logout.php"
                        class="bg-gradient-to-r from-red-400 to-red-600 text-white font-bold py-3 px-6 rounded-xl text-center uppercase tracking-wide hover:shadow-2xl hover:shadow-red-500/40 transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Sair
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
    </script>
</body>

</html>