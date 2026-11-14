<?php
$titulo = $titulo ?? 'Agenda Personal';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo) ?> | Agenda Personal</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<header class="topbar">
    <a class="brand" href="index.php">Agenda Personal</a>
    <nav aria-label="Navegación principal">
        <?php if (!empty($_SESSION['usuario_id'])): ?>
            <a href="dashboard.php">Mis contactos</a>
            <a href="logout.php">Cerrar sesión</a>
        <?php else: ?>
            <a href="login.php">Iniciar sesión</a>
            <a href="registro.php">Crear cuenta</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container">
