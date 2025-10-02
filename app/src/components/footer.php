<?php
$companyName = "Guri Games";
$currentYear = date('Y');

$socialMedia = [
    "Instagram" => [
        "url" => "https://www.instagram.com/z_luuuck/",
        "icon_class" => "fab fa-instagram",
        "hover_color" => "hover:text-pink-500"
    ],
    "YouTube"   => [
        "url" => "https://www.youtube.com/@zLuuck",
        "icon_class" => "fab fa-youtube",
        "hover_color" => "hover:text-red-500"
    ],
    "WhatsApp"  => [
        "url" => "https://wa.me/5541999016605",
        "icon_class" => "fab fa-whatsapp",
        "hover_color" => "hover:text-green-500"
    ],
    "LinkedIn"  => [
        "url" => "https://www.linkedin.com/in/lucastoterol/",
        "icon_class" => "fab fa-linkedin",
        "hover_color" => "hover:text-blue-400"
    ],
    "GitHub"    => [
        "url" => "https://github.com/zLuuuck",
        "icon_class" => "fab fa-github",
        "hover_color" => "hover:text-stone-50"
    ]
];
?>

<footer class="flex-shrink-0 bg-gray-900/95 backdrop-blur-md border-t-2 border-cyan-400 text-white py-12 mt-auto relative overflow-hidden">
    <!-- Efeito de borda superior -->
    <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-cyan-400 to-transparent shadow-lg shadow-cyan-500/50"></div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Ícones sociais -->
        <div class="flex justify-center items-center space-x-4 sm:space-x-6 mb-8">
            <?php foreach ($socialMedia as $platform => $data): ?>
                <a
                    href="<?php echo htmlspecialchars($data['url']); ?>"
                    target="_blank"
                    aria-label="Visite nosso <?php echo $platform; ?>"
                    class="group flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 bg-gray-800/50 border border-cyan-400/30 rounded-full text-cyan-400 text-lg sm:text-xl transition-all duration-300 relative overflow-hidden
                           hover:border-current hover:scale-110 hover:shadow-2xl transform-gpu <?php echo $data['hover_color']; ?>">
                    <!-- Efeito de brilho -->
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-current to-transparent opacity-0 group-hover:opacity-20 -translate-x-full group-hover:translate-x-full transition-all duration-500"></div>

                    <i class="<?php echo htmlspecialchars($data['icon_class']); ?> relative z-10"></i>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Direitos autorais -->
        <div class="text-center border-t border-cyan-400/20 pt-6">
            <p class="text-gray-400 text-sm">
                © <?php echo $currentYear; ?> <?php echo $companyName; ?>. Todos os direitos reservados.
            </p>
            <p class="text-gray-500 text-xs mt-2">
                Desenvolvido por Lucas Toterol - Adrian - Caio - Jorge - Marcelo.
            </p>
        </div>
</footer>