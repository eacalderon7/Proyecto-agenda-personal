<?php
require_once __DIR__ . '/includes/auth.php';
requerir_login();

$stmt = $pdo->prepare('SELECT id, nombre, telefono, correo, ciudad FROM contactos WHERE id_usuario = ? ORDER BY nombre ASC');
$stmt->execute([$_SESSION['usuario_id']]);
$contactos = $stmt->fetchAll();

$titulo = 'Mis contactos';
require __DIR__ . '/includes/header.php';
?>
<section class="welcome">
    <div>
        <p class="eyebrow">PANEL PRINCIPAL</p>
        <h1>Hola, <?= e($_SESSION['usuario_nombre']) ?></h1>
        <p class="muted"><?= e($_SESSION['usuario_correo']) ?></p>
    </div>
    <a class="button primary" href="agregar_contacto.php">+ Nuevo contacto</a>
</section>

<div class="section-heading">
    <div><h2>Mis contactos</h2><p class="muted"><?= count($contactos) ?> contacto(s) registrado(s)</p></div>
</div>

<?php if (!empty($_SESSION['mensaje'])): ?>
    <div class="alert success"><?= e($_SESSION['mensaje']) ?></div>
    <?php unset($_SESSION['mensaje']); ?>
<?php endif; ?>

<?php if (!$contactos): ?>
    <div class="empty-state">
        <h3>Aún no tiene contactos</h3>
        <p>Agregue su primer contacto para comenzar a organizar su agenda.</p>
        <a class="button primary" href="agregar_contacto.php">Agregar contacto</a>
    </div>
<?php else: ?>
<div class="table-wrap">
<table>
    <thead><tr><th>Nombre</th><th>Teléfono</th><th>Correo</th><th>Ciudad</th><th>Acciones</th></tr></thead>
    <tbody>
    <?php foreach ($contactos as $contacto): ?>
        <tr>
            <td data-label="Nombre"><strong><?= e($contacto['nombre']) ?></strong></td>
            <td data-label="Teléfono"><?= e($contacto['telefono']) ?></td>
            <td data-label="Correo"><?= e($contacto['correo']) ?></td>
            <td data-label="Ciudad"><?= e($contacto['ciudad']) ?></td>
            <td data-label="Acciones" class="actions">
                <a class="button small secondary" href="editar_contacto.php?id=<?= (int)$contacto['id'] ?>">Editar</a>
                <form method="POST" action="eliminar_contacto.php" onsubmit="return confirm('¿Está seguro de eliminar este contacto?');">
                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                    <input type="hidden" name="id" value="<?= (int)$contacto['id'] ?>">
                    <button class="button small danger" type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
