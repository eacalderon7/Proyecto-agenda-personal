<?php
require_once __DIR__ . '/includes/auth.php';

$errores = [];
$nombre = '';
$correo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validar_csrf();
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = strtolower(trim($_POST['correo'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirmar = $_POST['confirmar_password'] ?? '';

    if ($nombre === '' || mb_strlen($nombre) < 3) $errores[] = 'Ingrese un nombre de al menos 3 caracteres.';
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = 'Ingrese un correo electrónico válido.';
    if (strlen($password) < 8) $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
    if ($password !== $confirmar) $errores[] = 'Las contraseñas no coinciden.';

    if (!$errores) {
        $consulta = $pdo->prepare('SELECT id FROM usuarios WHERE correo = ?');
        $consulta->execute([$correo]);
        if ($consulta->fetch()) {
            $errores[] = 'El correo electrónico ya se encuentra registrado.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insertar = $pdo->prepare('INSERT INTO usuarios (nombre, correo, password) VALUES (?, ?, ?)');
            $insertar->execute([$nombre, $correo, $hash]);
            $_SESSION['mensaje'] = 'Cuenta creada correctamente. Ya puede iniciar sesión.';
            redirigir('login.php');
        }
    }
}
$titulo = 'Crear cuenta';
require __DIR__ . '/includes/header.php';
?>
<section class="auth-card">
    <p class="eyebrow">BIENVENIDO</p>
    <h1>Crear una cuenta</h1>
    <p class="muted">Regístrese para administrar su agenda personal.</p>
    <?php foreach ($errores as $error): ?><div class="alert error"><?= e($error) ?></div><?php endforeach; ?>
    <form method="POST" autocomplete="on">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label for="nombre">Nombre completo</label>
        <input id="nombre" name="nombre" type="text" maxlength="120" required value="<?= e($nombre) ?>">
        <label for="correo">Correo electrónico</label>
        <input id="correo" name="correo" type="email" maxlength="190" required value="<?= e($correo) ?>">
        <label for="password">Contraseña</label>
        <input id="password" name="password" type="password" minlength="8" required>
        <label for="confirmar_password">Confirmar contraseña</label>
        <input id="confirmar_password" name="confirmar_password" type="password" minlength="8" required>
        <button class="button primary full" type="submit">Registrarme</button>
    </form>
    <p class="form-foot">¿Ya tiene una cuenta? <a href="login.php">Inicie sesión</a></p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
