<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    // Cookies de sesión con medidas básicas de seguridad.
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax'
    ]);
    session_start();
}

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/funciones.php';

function requerir_login(): void {
    if (empty($_SESSION['usuario_id'])) {
        redirigir('login.php');
    }
}
