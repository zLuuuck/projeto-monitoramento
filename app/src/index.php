<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guri Games - Sua Loja Gamer</title>
    <link rel="icon" type="image/x-icon" href="guri_games_icon.png">


    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>


    <!-- Seus estilos atuais (mantenha por enquanto) -->
    <link rel="stylesheet" href="./styles/style.css">
    <script src="https://kit.fontawesome.com/0dc50eaa4b.js" crossorigin="anonymous"></script>

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
                    }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen bg-gray-900 text-white flex flex-col">

    <?php include_once './components/navbar.php'; ?>

    <!-- Efeito de partículas para home -->
    <div class="particles" id="particles"></div>

    <main class="flex-1 flex items-center justify-center p-4 md:p-6">
        <!-- Versão com Tailwind - mantenha a original também por enquanto -->
        <div class="welcome-box bg-gray-900/90 border-2 border-cyan-400 rounded-xl p-8 md:p-12 text-center max-w-2xl w-full relative overflow-visible shadow-2xl shadow-cyan-500/30">

            <!-- Título com efeito de digitação (mantenha seu CSS) -->
            <h1 class="typing-effect text-3xl md:text-4xl lg:text-5xl font-black mb-6 bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent uppercase tracking-wider">
                Bem-vindo ao Guri Games!
            </h1>

            <p class="text-lg md:text-xl text-gray-300 mb-8 leading-relaxed">
                Sua loja de equipamentos gamers de alta performance.
            </p>

            <a href="./pages/produtos.php"
                class="cta-button inline-block bg-gradient-to-r from-cyan-400 to-blue-500 text-gray-900 font-bold py-4 px-8 rounded-lg text-lg md:text-xl uppercase tracking-wide hover:shadow-2xl hover:shadow-cyan-500/40 transform hover:-translate-y-1 transition-all duration-300">
                Explorar Produtos
            </a>
        </div>
    </main>

    <?php include_once './components/footer.php'; ?>

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
    </script>
</body>

</html>