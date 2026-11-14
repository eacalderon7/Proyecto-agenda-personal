<?php
require_once __DIR__ . '/includes/auth.php';
requerir_login();

$errores = [];
$datos = ['nombre'=>'', 'telefono'=>'', 'correo'=>'', 'ciudad'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validar_csrf();
    foreach ($datos as $campo => $_) $datos[$campo] = trim($_POST[$campo] ?? '');

    if ($datos['nombre'] === '') $errores[] = 'El nombre es obligatorio.';
    if ($datos['telefono'] === '' || !preg_match('/^[0-9+\s().-]{7,20}$/', $datos['telefono'])) $errores[] = 'Ingrese un teléfono válido.';
    if ($datos['correo'] !== '' && !filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) $errores[] = 'Ingrese un correo válido.';
    if ($datos['ciudad'] === '') $errores[] = 'La ciudad es obligatoria.';

    if (!$errores) {
        $stmt = $pdo->prepare('INSERT INTO contactos (id_usuario, nombre, telefono, correo, ciudad) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$_SESSION['usuario_id'], $datos['nombre'], $datos['telefono'], $datos['correo'] ?: null, $datos['ciudad']]);
        $_SESSION['mensaje'] = 'Contacto agregado correctamente.';
        redirigir('dashboard.php');
    }
}
$titulo = 'Agregar contacto';
require __DIR__ . '/includes/header.php';
?>
<section class="form-card">
    <a class="back-link" href="dashboard.php">← Volver a mis contactos</a>
    <h1>Nuevo contacto</h1>
    <p class="muted">Complete la información del contacto.</p>
    <?php foreach ($errores as $error): ?><div class="alert error"><?= e($error) ?></div><?php endforeach; ?>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label for="nombre">Nombre completo</label>
        <input id="nombre" name="nombre" maxlength="120" required value="<?= e($datos['nombre']) ?>">
        <label for="telefono">Teléfono</label>
        <input id="telefono" name="telefono" maxlength="20" required value="<?= e($datos['telefono']) ?>">
        <label for="correo">Correo electrónico</label>
        <input id="correo" name="correo" type="email" maxlength="190" value="<?= e($datos['correo']) ?>">
        <label for="ciudad">Ciudad</label>
        <input id="ciudad" name="ciudad" maxlength="100" required value="<?= e($datos['ciudad']) ?>">
        <button class="button primary full" type="submit">Guardar contacto</button>
    </form>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
