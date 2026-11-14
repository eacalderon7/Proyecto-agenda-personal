<?php
require_once __DIR__ . '/includes/auth.php';
requerir_login();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { http_response_code(404); exit('Contacto no encontrado.'); }

// La consulta comprueba simultáneamente el ID y el propietario.
$stmt = $pdo->prepare('SELECT id, nombre, telefono, correo, ciudad FROM contactos WHERE id = ? AND id_usuario = ?');
$stmt->execute([$id, $_SESSION['usuario_id']]);
$contacto = $stmt->fetch();
if (!$contacto) { http_response_code(404); exit('Contacto no encontrado.'); }

$errores = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validar_csrf();
    $datos = [
        'nombre' => trim($_POST['nombre'] ?? ''),
        'telefono' => trim($_POST['telefono'] ?? ''),
        'correo' => trim($_POST['correo'] ?? ''),
        'ciudad' => trim($_POST['ciudad'] ?? '')
    ];
    if ($datos['nombre'] === '') $errores[] = 'El nombre es obligatorio.';
    if (!preg_match('/^[0-9+\s().-]{7,20}$/', $datos['telefono'])) $errores[] = 'Ingrese un teléfono válido.';
    if ($datos['correo'] !== '' && !filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) $errores[] = 'Ingrese un correo válido.';
    if ($datos['ciudad'] === '') $errores[] = 'La ciudad es obligatoria.';

    if (!$errores) {
        // La condición id_usuario evita modificar registros de otra cuenta.
        $update = $pdo->prepare('UPDATE contactos SET nombre=?, telefono=?, correo=?, ciudad=? WHERE id=? AND id_usuario=?');
        $update->execute([$datos['nombre'], $datos['telefono'], $datos['correo'] ?: null, $datos['ciudad'], $id, $_SESSION['usuario_id']]);
        $_SESSION['mensaje'] = 'Contacto actualizado correctamente.';
        redirigir('dashboard.php');
    }
    $contacto = array_merge($contacto, $datos);
}
$titulo = 'Editar contacto';
require __DIR__ . '/includes/header.php';
?>
<section class="form-card">
    <a class="back-link" href="dashboard.php">← Volver a mis contactos</a>
    <h1>Editar contacto</h1>
    <?php foreach ($errores as $error): ?><div class="alert error"><?= e($error) ?></div><?php endforeach; ?>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label for="nombre">Nombre completo</label>
        <input id="nombre" name="nombre" maxlength="120" required value="<?= e($contacto['nombre']) ?>">
        <label for="telefono">Teléfono</label>
        <input id="telefono" name="telefono" maxlength="20" required value="<?= e($contacto['telefono']) ?>">
        <label for="correo">Correo electrónico</label>
        <input id="correo" name="correo" type="email" maxlength="190" value="<?= e($contacto['correo']) ?>">
        <label for="ciudad">Ciudad</label>
        <input id="ciudad" name="ciudad" maxlength="100" required value="<?= e($contacto['ciudad']) ?>">
        <button class="button primary full" type="submit">Guardar cambios</button>
    </form>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
