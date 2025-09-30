<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function conectarBanco() {
    $host = getenv('DB_HOST') ?: 'mysql';
    $dbname = getenv('DB_NAME') ?: 'ed2_db';
    $username = getenv('DB_USER') ?: 'ed2_user';
    $password = getenv('DB_PASS') ?: 'ed2_password';

    try {
        $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        die("Erro ao conectar ao banco de dados: " . $e->getMessage());
    }
}
?>