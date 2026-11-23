<?php
$dbHost = '127.0.0.1';
$dbPort = 8889;
$dbName = 'palais_des_fetes';
$dbUser = 'root';
$dbPass = 'root';

$dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (PDOException $e) {
    exit('Erreur de connexion à la base de données.');
}
