<?php
global $conn;
require_once __DIR__ . '/../config/db.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// 🔒 Validar sesión
if (!isset($_SESSION['id'], $_SESSION['rol'])) {
    die("No autorizado");
}

// 🔐 Validar rol
if (!in_array(strtolower($_SESSION['rol']), ['admin', 'tecnico'])) {
    die("No autorizado");
}

// 📌 Validar ID
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    die("ID inválido");
}

$id_ticket = (int) $_POST['id'];

// Obtener fecha de creación
$stmt = $conn->prepare(
    "SELECT fecha_creacion 
     FROM tickets 
     WHERE id = ? AND estado = 'abierto'"
);
$stmt->bind_param("i", $id_ticket);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Ticket no encontrado o ya cerrado");
}

$ticket = $result->fetch_assoc();

$fecha_creacion = new DateTime($ticket['fecha_creacion']);
$fecha_cierre   = new DateTime($ticket['fecha_cierre']);

// ⏱ Calcular diferencia
$diff = $fecha_creacion->diff($fecha_cierre);

// Formato del tiempo
$tiempo = "";
if ($diff->d > 0) $tiempo .= $diff->d . " días ";
if ($diff->h > 0) $tiempo .= $diff->h . " horas ";
$tiempo .= $diff->i . " minutos";

// 🔒 Cerrar ticket
$update = $conn->prepare(
    "UPDATE tickets 
     SET estado = 'resuelto',
         fecha_cierre = NOW(),
         tiempo_cierre = ?
     WHERE id = ?"
);
$update->bind_param("si", $tiempo, $id_ticket);
$update->execute();

// 🔁 Redirigir
header("Location: ver_ticket.php?id=" . $id_ticket);
exit;
