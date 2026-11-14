<?php
require_once __DIR__ . '/includes/auth.php';
if (!empty($_SESSION['usuario_id'])) redirigir('dashboard.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validar_csrf();
    $correo = strtolower(trim($_POST['correo'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Correo electrónico o contraseña incorrectos.';
    } else {
        $stmt = $pdo->prepare('SELECT id, nombre, correo, password FROM usuarios WHERE correo = ? LIMIT 1');
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password'])) {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = (int)$usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_correo'] = $usuario['correo'];
            redirigir('dashboard.php');
        }
        $error = 'Correo electrónico o contraseña incorrectos.';
    }
}
$titulo = 'Iniciar sesión';
require __DIR__ . '/includes/header.php';
?>
<section class="auth-card">
    <p class="eyebrow">SU ESPACIO PERSONAL</p>
    <h1>Iniciar sesión</h1>
    <p class="muted">Acceda a sus contactos de forma segura.</p>
    <?php if (!empty($_SESSION['mensaje'])): ?>
        <div class="alert success"><?= e($_SESSION['mensaje']) ?></div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label for="correo">Correo electrónico</label>
        <input id="correo" name="correo" type="email" required autocomplete="username">
        <label for="password">Contraseña</label>
        <input id="password" name="password" type="password" required autocomplete="current-password">
        <button class="button primary full" type="submit">Ingresar</button>
    </form>
    <p class="form-foot">¿No tiene una cuenta? <a href="registro.php">Regístrese aquí</a></p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
