<?php
// Configuración de conexión a MySQL mediante PDO.
$host = 'localhost';
$db   = 'agenda_personal';
$user = 'root';
$pass = ''; // En producción, use variables de entorno y una contraseña segura.
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
    // No mostrar detalles internos de conexión al usuario.
    error_log($e->getMessage());
    http_response_code(500);
    exit('No fue posible conectar con la base de datos.');
}
