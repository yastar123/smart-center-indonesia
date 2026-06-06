<?php
$host = '127.0.0.1';
$port = 3306;
$user = 'root';
$pass = '';
$db = 'scenter';
try {
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "DB_CREATED\n";
} catch (Exception $e) {
    echo "ERR:" . $e->getMessage() . "\n";
    exit(1);
}
