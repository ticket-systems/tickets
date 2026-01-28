<?php
global $conn;
include __DIR__ . '/../config/db.php';

if (!isset($_SESSION['id'])) {
    die("No autorizado");
}

if (!isset($_POST['ticket_id'], $_POST['comentario'])) {
    die("Datos incompletos");
}

$ticket_id  = (int) $_POST['ticket_id'];
$usuario_id = $_SESSION['id'];
$comentario = trim($_POST['comentario']);

$stmt = $conn->prepare("
    INSERT INTO ticket_comentarios (ticket_id, usuario_id, comentario)
    VALUES (?, ?, ?)
");
$stmt->bind_param("iis", $ticket_id, $usuario_id, $comentario);
$stmt->execute();

header("Location: ver_ticket.php?id=$ticket_id");
exit;
