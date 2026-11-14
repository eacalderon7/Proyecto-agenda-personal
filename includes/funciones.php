<?php
function e($valor): string {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validar_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        exit('Solicitud no válida. Actualice la página e inténtelo nuevamente.');
    }
}

function redirigir(string $ruta): never {
    header("Location: $ruta");
    exit;
}
