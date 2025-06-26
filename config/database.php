<?php
// Configuración de conexión a la base de datos MySQL para ALI 3000
$host = 'localhost';
$db   = 'ali3000_db';
$user = 'root'; // Cambia por tu usuario si es diferente
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    exit('Error de conexión a la base de datos: ' . $e->getMessage());
}

// Conexión mysqli para servicios que usan mysqli en vez de PDO
function getDbConnection() {
    $host = 'localhost';
    $user = 'root'; // Cambia por tu usuario si es diferente
    $pass = '';
    $db   = 'ali3000_db';
    $mysqli = new mysqli($host, $user, $pass, $db);
    if ($mysqli->connect_errno) {
        die('Error de conexión: ' . $mysqli->connect_error);
    }
    $mysqli->set_charset('utf8mb4');
    return $mysqli;
}
