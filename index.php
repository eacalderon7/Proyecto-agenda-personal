<?php
require_once __DIR__ . '/includes/auth.php';
if (!empty($_SESSION['usuario_id'])) {
    redirigir('dashboard.php');
}
redirigir('login.php');
