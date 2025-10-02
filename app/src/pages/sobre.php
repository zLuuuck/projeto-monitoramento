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
    <title>Sobre - Guri Games</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="../guri_games_icon.png">
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

    <main class="flex-1 py-12 px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-7xl mx-auto">
            <!-- Título Principal -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent uppercase tracking-wider">
                    Quem Somos
                </h1>
                <div class="w-48 h-1 bg-gradient-to-r from-cyan-400 to-blue-500 mx-auto rounded-full shadow-lg shadow-cyan-500/50"></div>
            </div>

            <!-- Conteúdo Principal -->
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-12 items-start">
                <!-- Mapa - Ocupa 3 colunas -->
                <div class="lg:col-span-3">
                    <div class="relative group h-full">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3603.3953059451933!2d-49.3212071!3d-25.4250443!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94dce6da131d6d1b%3A0x9b7d03b3efdf4053!2sUniversidade%20Tuiuti%20do%20Paran%C3%A1!5e0!3m2!1spt-BR!2sbr!4v1745708216016!5m2!1spt-BR!2sbr"
                            width="100%"
                            height="500"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Localização da Guri Games"
                            class="w-full h-full min-h-[500px] xl:min-h-[980px] lg:min-h-[1050px] border-2 border-cyan-400 rounded-2xl shadow-2xl shadow-cyan-500/30 transition-all duration-300 group-hover:shadow-cyan-500/50 group-hover:-translate-y-1">
                        </iframe>

                        <!-- Efeito de brilho no hover -->
                        <div class="absolute inset-0 rounded-2xl border-2 border-transparent bg-gradient-to-r from-cyan-400 to-blue-500 opacity-0 group-hover:opacity-20 transition-opacity duration-300 -z-10"></div>
                    </div>
                </div>

                <!-- Texto e Informações - Ocupa 2 colunas -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Descrição -->
                    <div class="bg-gray-900/90 backdrop-blur-sm border-2 border-cyan-400 rounded-2xl p-6 shadow-2xl shadow-cyan-500/30">
                        <p class="text-gray-300 text-base leading-relaxed">
                            A <strong class="text-cyan-400">Guri Games</strong> é referência no mercado de computadores gamers montados, levando
                            performance, qualidade e confiança para jogadores de todo o Brasil. Com mais de <strong class="text-cyan-400">15 anos de
                                experiência</strong>, nos dedicamos a entregar máquinas poderosas, configuradas com excelência
                            para proporcionar a melhor experiência gamer.
                        </p>
                    </div>

                    <!-- Missão, Visão e Valores -->
                    <div class="space-y-6">
                        <!-- Missão -->
                        <div class="bg-gray-900/90 backdrop-blur-sm border-2 border-cyan-400 rounded-2xl p-6 shadow-2xl shadow-cyan-500/30 hover:shadow-cyan-500/50 hover:-translate-y-1 transition-all duration-300">
                            <h3 class="text-cyan-400 font-bold text-lg mb-3 uppercase tracking-wide flex items-center">
                                <i class="fas fa-bullseye text-cyan-400 mr-3 text-xl"></i>
                                MISSÃO
                            </h3>
                            <p class="text-gray-300 text-sm leading-relaxed">
                                Fornecer equipamentos gamers de alta performance com qualidade superior
                            </p>
                        </div>

                        <!-- Visão -->
                        <div class="bg-gray-900/90 backdrop-blur-sm border-2 border-cyan-400 rounded-2xl p-6 shadow-2xl shadow-cyan-500/30 hover:shadow-cyan-500/50 hover:-translate-y-1 transition-all duration-300">
                            <h3 class="text-cyan-400 font-bold text-lg mb-3 uppercase tracking-wide flex items-center">
                                <i class="fas fa-eye text-cyan-400 mr-3 text-xl"></i>
                                VISÃO
                            </h3>
                            <p class="text-gray-300 text-sm leading-relaxed">
                                Ser a loja preferida dos gamers brasileiros
                            </p>
                        </div>

                        <!-- Valores -->
                        <div class="bg-gray-900/90 backdrop-blur-sm border-2 border-cyan-400 rounded-2xl p-6 shadow-2xl shadow-cyan-500/30 hover:shadow-cyan-500/50 hover:-translate-y-1 transition-all duration-300">
                            <h3 class="text-cyan-400 font-bold text-lg mb-3 uppercase tracking-wide flex items-center">
                                <i class="fas fa-star text-cyan-400 mr-3 text-xl"></i>
                                VALORES
                            </h3>
                            <p class="text-gray-300 text-sm leading-relaxed">
                                Qualidade, performance, confiança e paixão por games
                            </p>
                        </div>
                    </div>

                    <!-- Informações de Contato -->
                    <div class="bg-cyan-400/10 backdrop-blur-sm border-2 border-cyan-400 rounded-2xl p-6 shadow-2xl shadow-cyan-500/30 border-l-4 border-l-cyan-400">
                        <p class="text-cyan-400 font-bold text-lg mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt mr-3"></i>
                            NOSSO ENDEREÇO
                        </p>
                        <div class="space-y-3 text-gray-300">
                            <p class="flex items-center">
                                <i class="fas fa-university text-cyan-400 mr-3 w-5"></i>
                                Universidade Tuiuti do Paraná
                            </p>
                            <p class="flex items-center text-sm">
                                <i class="fas fa-road text-cyan-400 mr-3 w-5"></i>
                                Rua: [Endereço completo]
                            </p>
                            <p class="flex items-center text-sm">
                                <i class="fas fa-city text-cyan-400 mr-3 w-5"></i>
                                Curitiba - PR
                            </p>
                            <div class="border-t border-cyan-400/20 pt-3 mt-3">
                                <p class="flex items-center text-sm">
                                    <i class="fas fa-phone text-cyan-400 mr-3 w-5"></i>
                                    (41) 99901-6605
                                </p>
                                <p class="flex items-center text-sm mt-2">
                                    <i class="fas fa-envelope text-cyan-400 mr-3 w-5"></i>
                                    contato@gurigames.com
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Destaques Adicionais -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-12">
                <div class="bg-gray-900/90 backdrop-blur-sm border-2 border-cyan-400 rounded-2xl p-6 text-center shadow-2xl shadow-cyan-500/30 hover:shadow-cyan-500/50 hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-history text-cyan-400 text-3xl mb-3"></i>
                    <h4 class="text-cyan-400 font-bold mb-2">15+ Anos</h4>
                    <p class="text-gray-300 text-sm">de experiência no mercado</p>
                </div>

                <div class="bg-gray-900/90 backdrop-blur-sm border-2 border-cyan-400 rounded-2xl p-6 text-center shadow-2xl shadow-cyan-500/30 hover:shadow-cyan-500/50 hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-award text-cyan-400 text-3xl mb-3"></i>
                    <h4 class="text-cyan-400 font-bold mb-2">Qualidade</h4>
                    <p class="text-gray-300 text-sm">garantida em todos os produtos</p>
                </div>

                <div class="bg-gray-900/90 backdrop-blur-sm border-2 border-cyan-400 rounded-2xl p-6 text-center shadow-2xl shadow-cyan-500/30 hover:shadow-cyan-500/50 hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-tachometer-alt text-cyan-400 text-3xl mb-3"></i>
                    <h4 class="text-cyan-400 font-bold mb-2">Performance</h4>
                    <p class="text-gray-300 text-sm">máxima para gaming</p>
                </div>

                <div class="bg-gray-900/90 backdrop-blur-sm border-2 border-cyan-400 rounded-2xl p-6 text-center shadow-2xl shadow-cyan-500/30 hover:shadow-cyan-500/50 hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-handshake text-cyan-400 text-3xl mb-3"></i>
                    <h4 class="text-cyan-400 font-bold mb-2">Confiança</h4>
                    <p class="text-gray-300 text-sm">de milhares de clientes</p>
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

            const particleCount = 50;

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