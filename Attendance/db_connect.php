<?php
function getConnection() {
    //charge configuration
    $config = include 'config.php';
    try { 
        $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset=utf8";
        $pdo = new PDO($dsn, $config['username'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        //(optionnel:save errors in log file)
        file_put_contents('db_errors.log', date("Y-m-d H:i:s") . " - " . $e->getMessage() . "\n", FILE_APPEND);
        return null;
    }
}