<?php
global $conn;
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['admin', 'tecnico', 'usuario'])) {
    die ("No autorizado" );
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido");
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM tickets WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$ticket = $res->fetch_assoc();

if (!$ticket) {
    die("Ticket no encontrado");
}
?>

<link rel="stylesheet" href="../style.css">
<body>
<div class="logo-top">
    <img src="../assets/logo.png" alt="Logo">
</div>
<div class="ver_ticket card">

    <h2><?= htmlspecialchars($ticket['titulo']) ?></h2>
    <p><b>Descripcion:</b><?= ucfirst($ticket['descripcion'])?>     </p>
    <p><b>Estado:</b> <?= ucfirst($ticket['estado']) ?></p>
    <p><b>Prioridad:</b> <?= ucfirst($ticket['prioridad']) ?></p>
    <p><b>Fecha de Creación:</b> <?= $ticket['fecha_creacion'] ?></p>
    <p><b>Fecha de Cierre:</b> <?= $ticket['fecha_cierre'] ?? '' ?></p>
    <p><b>Tiempo de Cierre:</b>
        <?= $ticket['tiempo_cierre']
                ? htmlspecialchars($ticket['tiempo_cierre'])
                : '<span style="color:#999">En proceso</span>'
        ?>
    <hr>

    <form method="POST" action="agregar_comentario.php">
        <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
        <textarea name="comentario" required placeholder="Escribe tu comentario"></textarea>
        <button type="submit">💬 Agregar comentario</button>
    </form>

    <?php
    $estado = strtolower($ticket['estado']);
    $rol = strtolower($_SESSION['rol'] ?? '');
    ?>

    <?php if ($estado === 'abierto' && in_array($rol, ['admin', 'tecnico'])): ?>
        <form method="POST" action="cerrar_ticket.php" style="margin-top:15px;">
            <input type="hidden" name="id" value="<?= $ticket['id'] ?>">
            <button type="submit" class="btn-cerrar">🔒 Cerrar Ticket
            </button>
        </form>
    <?php endif; ?>

    <hr>

    <?php
    $comentarios = $conn->prepare("
        SELECT c.comentario, c.fecha, u.nombre
        FROM ticket_comentarios c
        JOIN usuarios u ON c.usuario_id = u.id
        WHERE c.ticket_id = ?
        ORDER BY c.fecha ASC
    ");
    $comentarios->bind_param("i", $ticket['id']);
    $comentarios->execute();
    $resComentarios = $comentarios->get_result();
    ?>

    <h3>Comentarios</h3>

    <?php if ($resComentarios->num_rows === 0): ?>
        <p style="color:#777">No hay comentarios aún</p>
    <?php endif; ?>

    <?php while ($c = $resComentarios->fetch_assoc()): ?>
        <div class="comentario">
            <strong><?= htmlspecialchars($c['nombre']) ?></strong><br>
            <?= nl2br(htmlspecialchars($c['comentario'])) ?><br>
            <small><?= $c['fecha'] ?></small>
        </div>
    <?php endwhile; ?>

    <button class="btn-volver" onclick="history.back()">⬅ Volver</button>

</div>
</body>
