<?php
session_start();

// 🔒 Si NO hay sesión → login
if (!isset($_SESSION['id'])) {
    header("Location: auth/login.php");
    exit;

}
?>



<!DOCTYPE html>
    <html lang="es">
        <head>
            <meta charset="UTF-8">
                <title>Sistema de Tickets</title>
                    <link rel="stylesheet" href="style.css">
        </head>
    <body>
        <div class="logo-top">
            <img src="assets/logo.png" alt="Logo">
        </div>
            <div class="page-wrapper">
                <div class="container">
                    <h1>Bienvenido <?= $_SESSION['nombre'] ?></h1>
                         <p>Rol: <?= $_SESSION['rol'] ?></p>

    <div class="menu">
        <?php if ($_SESSION['rol'] === 'usuario'): ?>
            <a href="usuario/crear_ticket.php" class="btn-crear">➕ Crear Ticket</a>
        <?php endif; ?>

        <?php if ($_SESSION['rol'] === 'usuario'):?>
            <a href="tickets/index.php" class="btn-ver">📋 Ver Mis Tickets</a>
        <?php endif; ?>
        <?php if ($_SESSION['rol'] === 'admin'): ?>
        <?php endif; ?>

        <?php if (in_array($_SESSION['rol'], ['admin','tecnico'])): ?>
            <a href="admin/dashboard.php" class="btn-admin">🛠 Panel Técnico</a>
        <?php endif; ?>

        <?php if (in_array($_SESSION['rol'], ['admin','tecnico'])): ?>
        <div class="menu">
            <a href="graficos.php" class="btn-graficos">📊 Gráficos</a>
        </div>
        <?php endif; ?>
            <a href="auth/logout.php" class="btn-logout">🚪 Cerrar Sesión</a>
    </div>
                <div class="footer">Sistema de Tickets © <?= date('Y') ?>
    </div>
</body>
<style>.menu a.btn-graficos {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        color: #fff;
    }

    .menu a.btn-graficos:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e3a8a);
    }
</style>