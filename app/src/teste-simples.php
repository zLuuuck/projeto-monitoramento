<?php
echo "<h1>Teste Simples PHP</h1>";
echo "<p>PHP está funcionando! Versão: " . PHP_VERSION . "</p>";
echo "<p>Extensão OpenTelemetry: " . (extension_loaded('opentelemetry') ? 'Carregada' : 'Não carregada') . "</p>";

// Teste básico sem usar as funções de log
file_put_contents('/var/www/html/storage/logs/teste.log', date('Y-m-d H:i:s') . " - Teste simples\n", FILE_APPEND);
echo "<p>Log escrito em /var/www/html/storage/logs/teste.log</p>";