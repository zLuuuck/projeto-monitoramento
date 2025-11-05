<?php
require_once 'opentelemetry-init.php';

echo "<h1>Teste de Sistema de Logs</h1>";

// Teste de logs com diferentes níveis
logInfo('Teste de log INFO - Aplicação PHP iniciada', [
    'service' => 'php-app',
    'environment' => 'development',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
    'endpoint' => 'test-logs.php'
]);

logWarning('Teste de log WARNING - Operação suspeita', [
    'user_id' => 123,
    'action' => 'test_logging',
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
]);

logError('Teste de log ERROR - Erro simulado', [
    'error_code' => 'TEST_001',
    'file' => __FILE__,
    'line' => __LINE__,
    'timestamp' => time()
]);

logDebug('Teste de log DEBUG - Informações detalhadas', [
    'memory_usage' => memory_get_usage(),
    'peak_memory' => memory_get_peak_usage(),
    'include_path' => get_include_path(),
    'php_version' => PHP_VERSION
]);

echo "<div style='background: #f0f0f0; color: #333; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
echo "<h3>Logs enviados com sucesso!</h3>";
echo "<p>Verifique os logs no SigNoz:</p>";
echo "<ul>";
echo "<li><strong>Serviço:</strong> php-app</li>";
echo "<li><strong>Níveis testados:</strong> INFO, WARNING, ERROR, DEBUG</li>";
echo "<li><strong>Timestamp:</strong> " . date('Y-m-d H:i:s') . "</li>";
echo "</ul>";

echo "<h4>Próximos passos:</h4>";
echo "<ol>";
echo "<li>Acesse: https://signoz.io</li>";
echo "<li>Vá para a aba 'Logs'</li>";
echo "<li>Filtre por: <code>service.name = 'php-app'</code></li>";
echo "<li>Ou procure por: <code>php-app</code></li>";
echo "</ol>";
echo "</div>";

// Teste adicional: simulação de operação com banco de dados
echo "<h3>Simulação de Operação com Banco de Dados</h3>";

try {
    logInfo('Iniciando consulta simulada ao banco de dados', [
        'operation' => 'select_users',
        'table' => 'usuarios',
        'simulated' => true
    ]);
    
    // Simula um processamento
    usleep(100000); // 100ms
    
    // Simula um erro aleatório (50% de chance)
    if (rand(0, 1) === 1) {
        throw new Exception('Erro simulado de conexão com o banco de dados');
    }
    
    logInfo('Consulta simulada concluída com sucesso', [
        'rows_affected' => 5,
        'duration_ms' => 100,
        'status' => 'success'
    ]);
    
    echo "<p style='color: green;'>✅ Operação com banco simulada com SUCESSO</p>";
    
} catch (Exception $e) {
    logError('Erro na operação com banco de dados', [
        'error_message' => $e->getMessage(),
        'error_code' => 'DB_001',
        'operation' => 'select_users',
        'timestamp' => date('c')
    ]);
    
    echo "<p style='color: red;'>❌ Operação com banco simulada com ERRO: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><strong>Status do OpenTelemetry:</strong> " . (extension_loaded('opentelemetry') ? '✅ Extensão carregada' : '❌ Extensão não disponível') . "</p>";
echo "<p><strong>Logs locais:</strong> Verifique <code>/var/www/html/storage/logs/app.log</code></p>";