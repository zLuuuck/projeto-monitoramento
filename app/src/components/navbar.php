<?php
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['user_id']) && $_SESSION['user_id'] === 1;
?>

<nav class="fixed top-0 left-0 w-full bg-gray-900/95 backdrop-blur-md border-b-2 border-cyan-400 shadow-2xl shadow-cyan-500/30 z-50 h-20 flex items-center justify-between px-4 sm:px-8 lg:px-12">
    <!-- Logo -->
    <a href="../index.php" class="text-xl sm:text-2xl font-black bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent uppercase tracking-wider hover:scale-105 transition-transform duration-300">
        Guri Games
    </a>

    <!-- Menu Hambúrguer (Mobile) -->
    <button class="md:hidden text-cyan-400 text-2xl hover:text-blue-500 hover:scale-110 transition-all duration-300 focus:outline-none" id="hamburger">
        &#9776;
    </button>

    <!-- Menu Desktop -->
    <ul class="hidden md:flex items-center space-x-6 lg:space-x-8 flex-grow justify-end" id="navbar-list">
        <li>
            <a href="../index.php"
                class="relative font-bold text-gray-300 hover:text-cyan-400 transition-all duration-300 mx-1 py-2 px-4 uppercase tracking-wide
                      after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-bottom-1
                      after:h-[2px] after:w-0 after:bg-cyan-400 after:transition-all after:duration-300 
                      hover:after:w-full">
                Home
            </a>
        </li>
        <li>
            <a href="../pages/produtos.php"
                class="relative font-bold text-gray-300 hover:text-cyan-400 transition-all duration-300 mx-1 py-2 px-4 uppercase tracking-wide
                      after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-bottom-1
                      after:h-[2px] after:w-0 after:bg-cyan-400 after:transition-all after:duration-300 
                      hover:after:w-full">
                Produtos
            </a>
        </li>

        <?php if ($isLoggedIn): ?>
            <?php if ($isAdmin): ?>
                <li>
                    <a href="../pages/add-produtos.php"
                        class="relative font-bold text-gray-300 hover:text-cyan-400 transition-all duration-300 mx-1 py-2 px-4 uppercase tracking-wide
                              after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-bottom-1
                              after:h-[2px] after:w-0 after:bg-cyan-400 after:transition-all after:duration-300 
                              hover:after:w-full">
                        Adicionar Produtos
                    </a>
                </li>
            <?php endif; ?>
            <li>
                <a href="../pages/perfil.php"
                    class="relative font-bold text-gray-300 hover:text-cyan-400 transition-all duration-300 mx-1 py-2 px-4 uppercase tracking-wide
                          after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-bottom-1
                          after:h-[2px] after:w-0 after:bg-cyan-400 after:transition-all after:duration-300 
                          hover:after:w-full">
                    Conta
                </a>
            </li>
            <li>
                <a href="../pages/logout.php"
                    class="relative font-bold text-red-400 hover:text-red-500 transition-all duration-300 mx-1 py-2 px-4 uppercase tracking-wide
                          after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-bottom-1
                          after:h-[2px] after:w-0 after:bg-red-500 after:transition-all after:duration-300 
                          hover:after:w-full">
                    Sair
                </a>
            </li>
        <?php else: ?>
            <li>
                <a href="../pages/login.php"
                    class="relative font-bold text-gray-300 hover:text-cyan-400 transition-all duration-300 mx-1 py-2 px-4 uppercase tracking-wide
                          after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-bottom-1
                          after:h-[2px] after:w-0 after:bg-cyan-400 after:transition-all after:duration-300 
                          hover:after:w-full">
                    Entrar
                </a>
            </li>
        <?php endif; ?>

        <li>
            <a href="../pages/sobre.php"
                class="relative font-bold text-gray-300 hover:text-cyan-400 transition-all duration-300 mx-1 py-2 px-4 uppercase tracking-wide
                      after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-bottom-1
                      after:h-[2px] after:w-0 after:bg-cyan-400 after:transition-all after:duration-300 
                      hover:after:w-full">
                Sobre
            </a>
        </li>
    </ul>

    <!-- Menu Mobile (Dropdown) -->
    <div class="md:hidden absolute top-full left-0 w-full bg-gray-900/95 backdrop-blur-lg border-b-2 border-cyan-400 shadow-2xl shadow-cyan-500/30 hidden" id="mobile-menu">
        <ul class="flex flex-col py-4">
            <li class="border-b border-cyan-400/20 last:border-b-0">
                <a href="../index.php"
                    class="block relative font-bold text-gray-300 hover:text-cyan-400 hover:bg-cyan-400/10 transition-all duration-300 py-4 px-6 text-center uppercase tracking-wide
                          after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-bottom-1
                          after:h-[2px] after:w-0 after:bg-cyan-400 after:transition-all after:duration-300 
                          hover:after:w-full">
                    Home
                </a>
            </li>
            <li class="border-b border-cyan-400/20 last:border-b-0">
                <a href="../pages/produtos.php"
                    class="block relative font-bold text-gray-300 hover:text-cyan-400 hover:bg-cyan-400/10 transition-all duration-300 py-4 px-6 text-center uppercase tracking-wide
                          after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-bottom-1
                          after:h-[2px] after:w-0 after:bg-cyan-400 after:transition-all after:duration-300 
                          hover:after:w-full">
                    Produtos
                </a>
            </li>

            <?php if ($isLoggedIn): ?>
                <?php if ($isAdmin): ?>
                    <li class="border-b border-cyan-400/20 last:border-b-0">
                        <a href="../pages/add-produtos.php"
                            class="block relative font-bold text-gray-300 hover:text-cyan-400 hover:bg-cyan-400/10 transition-all duration-300 py-4 px-6 text-center uppercase tracking-wide
                                  after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-bottom-1
                                  after:h-[2px] after:w-0 after:bg-cyan-400 after:transition-all after:duration-300 
                                  hover:after:w-full">
                            Adicionar Produtos
                        </a>
                    </li>
                <?php endif; ?>
                <li class="border-b border-cyan-400/20 last:border-b-0">
                    <a href="../pages/perfil.php"
                        class="block relative font-bold text-gray-300 hover:text-cyan-400 hover:bg-cyan-400/10 transition-all duration-300 py-4 px-6 text-center uppercase tracking-wide
                              after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-bottom-1
                              after:h-[2px] after:w-0 after:bg-cyan-400 after:transition-all after:duration-300 
                              hover:after:w-full">
                        Conta
                    </a>
                </li>
                <li class="border-b border-cyan-400/20 last:border-b-0">
                    <a href="../pages/logout.php"
                        class="block relative font-bold text-red-400 hover:text-red-500 hover:bg-red-400/10 transition-all duration-300 py-4 px-6 text-center uppercase tracking-wide
                              after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-bottom-1
                              after:h-[2px] after:w-0 after:bg-red-500 after:transition-all after:duration-300 
                              hover:after:w-full">
                        Sair
                    </a>
                </li>
            <?php else: ?>
                <li class="border-b border-cyan-400/20 last:border-b-0">
                    <a href="../pages/login.php"
                        class="block relative font-bold text-gray-300 hover:text-cyan-400 hover:bg-cyan-400/10 transition-all duration-300 py-4 px-6 text-center uppercase tracking-wide
                              after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-bottom-1
                              after:h-[2px] after:w-0 after:bg-cyan-400 after:transition-all after:duration-300 
                              hover:after:w-full">
                        Entrar
                    </a>
                </li>
            <?php endif; ?>

            <li class="border-b border-cyan-400/20 last:border-b-0">
                <a href="../pages/sobre.php"
                    class="block relative font-bold text-gray-300 hover:text-cyan-400 hover:bg-cyan-400/10 transition-all duration-300 py-4 px-6 text-center uppercase tracking-wide
                          after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-bottom-1
                          after:h-[2px] after:w-0 after:bg-cyan-400 after:transition-all after:duration-300 
                          hover:after:w-full">
                    Sobre
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- Espaço para a navbar fixa -->
<div class="h-20"></div>

<script>
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobile-menu');
    const navbarList = document.getElementById('navbar-list');

    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            hamburger.classList.toggle('text-blue-500');
        });

        // Fechar menu ao clicar fora
        document.addEventListener('click', (e) => {
            if (!hamburger.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.add('hidden');
                hamburger.classList.remove('text-blue-500');
            }
        });

        // Fechar menu ao redimensionar para desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                mobileMenu.classList.add('hidden');
                hamburger.classList.remove('text-blue-500');
            }
        });
    }
</script>