<?php
global $conn;
session_start();
require_once "../config/db.php";

// 🔒 Validar sesión
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// 🔐 Validar rol
if ($_SESSION['rol'] !== 'usuario') {
    die("No autorizado");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo      = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $prioridad   = $_POST['prioridad'];
    $usuario_id  = $_SESSION['id'];
    $stmt = $conn->prepare("
    INSERT INTO tickets (
        titulo,
        descripcion,
        estado,
        usuario_id,
        prioridad,
        fecha_creacion
    )
    VALUES (?, ?, 'abierto', ?, ?, NOW())
");
    $stmt->bind_param(
            "ssis",           // s = titulo, s = descripcion, i = usuario_id, s = prioridad
            $titulo,
            $descripcion,
            $usuario_id,
            $prioridad
    );
    $stmt->execute();

    $ticket_id = $stmt->insert_id;

    header("Location: ../tickets/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Ticket</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="logo-top">
    <img src="../assets/logo.png" alt="Logo">
</div>

<div class="crear-ticket card">
    <h2>Nuevo Ticket Soporte IT</h2>

    <form method="POST" action="crear_ticket.php">

        <input type="text" name="titulo" placeholder="Titulo: Area (Equipo, maquina, impresora, etc...)" required>

        <textarea name="descripcion" placeholder="Descripción explicita de la falla" required></textarea>

        <label>Prioridad</label>
        <select name="prioridad">
            <option value="Alta">Alta</option>
            <option value="Media" selected>Media</option>
            <option value="Baja">Baja</option>
        </select>

        <button type="submit">Crear Ticket</button>
        <button class="btn-volver" onclick="history.back()">⬅ Volver</button>

</div>

</body>
</html>
