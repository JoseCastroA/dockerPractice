<?php
// Configuración de base de datos usando variables de entorno
$host = $_ENV['DB_HOST'] ?? 'db';
$dbname = $_ENV['DB_NAME'] ?? 'appdb';
$username = $_ENV['DB_USER'] ?? 'appuser';
$password = $_ENV['DB_PASSWORD'] ?? 'app123';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}
?>
