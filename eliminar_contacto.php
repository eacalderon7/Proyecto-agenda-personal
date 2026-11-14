<?php
require_once __DIR__ . '/includes/auth.php';
requerir_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido.');
}
validar_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) redirigir('dashboard.php');

// Solo elimina si el contacto pertenece al usuario autenticado.
$stmt = $pdo->prepare('DELETE FROM contactos WHERE id = ? AND id_usuario = ?');
$stmt->execute([$id, $_SESSION['usuario_id']]);

$_SESSION['mensaje'] = $stmt->rowCount()
    ? 'Contacto eliminado correctamente.'
    : 'No se encontró el contacto solicitado.';
redirigir('dashboard.php');
