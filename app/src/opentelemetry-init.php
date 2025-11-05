<?php

/**
 * Sistema de Logs Ultra Simplificado
 * Usa apenas funcionalidades nativas do PHP + extensão OpenTelemetry
 */

// Cria diretório de logs se não existir
if (!is_dir('/var/www/html/storage/logs')) {
    mkdir('/var/www/html/storage/logs', 0755, true);
}

/**
 * Sistema de logs básico que sempre funciona
 */
function logMessage($level, $message, $context = []) {
    // Prepara dados do log
    $logData = [
        'timestamp' => date('c'),
        'level' => strtoupper($level),
        'message' => $message,
        'context' => $context,
        'service' => 'php-app',
        'pid' => getmypid(),
        'memory_usage' => memory_get_usage(true)
    ];
    
    // Formata a linha de log em JSON
    $logLine = json_encode($logData) . PHP_EOL;
    
    // Escreve no arquivo de log local (sempre funciona)
    @file_put_contents(
        '/var/www/html/storage/logs/app.log', 
        $logLine, 
        FILE_APPEND | LOCK_EX
    );
    
    // Também escreve no stderr (capturado pelo Docker) se STDERR estiver definido
    if (defined('STDERR')) {
        fwrite(STDERR, $logLine);
    } else {
        // Alternativa: usar error_log para enviar para o log do PHP
        error_log($logLine);
    }
    
    // Tenta usar OpenTelemetry se a extensão estiver disponível
    if (extension_loaded('opentelemetry')) {
        otelLogMessage($level, $message, $context);
    }
}

/**
 * Tentativa de usar OpenTelemetry (opcional)
 */
function otelLogMessage($level, $message, $context = []) {
    if (!extension_loaded('opentelemetry')) {
        return;
    }
    
    try {
        // Usa a API do OpenTelemetry se disponível
        $logger = OpenTelemetry\API\Globals::loggerProvider()->getLogger('php-app');
        $logRecord = new OpenTelemetry\API\Logs\LogRecord();
        
        $logRecord->setBody($message);
        $logRecord->setAttributes(array_merge(['level' => $level], $context));
        $logRecord->setSeverityText($level);
        $logRecord->setTimestamp((int) (microtime(true) * 1000000000));
        
        $logger->emit($logRecord);
    } catch (Exception $e) {
        // Silenciosamente ignora erros do OpenTelemetry
    }
}

/**
 * Funções auxiliares para diferentes níveis de log
 */
function logInfo($message, $context = []) {
    logMessage('info', $message, $context);
}

function logError($message, $context = []) {
    logMessage('error', $message, $context);
}

function logWarning($message, $context = []) {
    logMessage('warning', $message, $context);
}

function logDebug($message, $context = []) {
    logMessage('debug', $message, $context);
}

// Log de inicialização apenas se não estiver sendo chamado via web com output
if (php_sapi_name() !== 'cli') {
    // Em contexto web, apenas inicializa sem log inicial para evitar erros
    register_shutdown_function(function() {
        logInfo('Sistema de logs simplificado inicializado', [
            'php_version' => PHP_VERSION,
            'otel_extension' => extension_loaded('opentelemetry') ? 'available' : 'not_available',
            'memory_limit' => ini_get('memory_limit')
        ]);
    });
} else {
    logInfo('Sistema de logs simplificado inicializado', [
        'php_version' => PHP_VERSION,
        'otel_extension' => extension_loaded('opentelemetry') ? 'available' : 'not_available',
        'memory_limit' => ini_get('memory_limit')
    ]);
}