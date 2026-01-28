<?php
global $conn;
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION['rol'] === 'usuario') {
    $stmt = $conn->prepare("SELECT * FROM tickets WHERE usuario_id = ?");
    $stmt->bind_param("i", $_SESSION['id']);
} else {
    $stmt = $conn->prepare("SELECT * FROM tickets");
}

$stmt->execute();
$tickets = $stmt->get_result();
?>

<!DOCTYPE html>
<style>
.container {
max-width: 1000px;
margin: 60px auto;
padding: 20px;
}

.card {
background: #ffffff;
border-radius: 12px;
box-shadow: 0 10px 30px rgba(0,0,0,.1);
}

/* TÍTULO */
h2 {
text-align: center;
margin-bottom: 20px;
}

/* TABLA */
table {
width: 100%;
border-collapse: collapse;
overflow: hidden;
border-radius: 10px;
}

th {
background: #1e3c72;
color: #fff;
padding: 12px;
text-align: left;
}

td {
padding: 12px;
border-bottom: 1px solid #ddd;
}

tr:hover {
background: #f2f6ff;
}

/* BOTÓN VER */
.btn-ver {
background: #107726;
color: white;
padding: 6px 12px;
border-radius: 6px;
text-decoration: none;
font-size: 14px;
}

.btn-ver:hover {
background: #107726;
}

/* LINK VOLVER */
a {
text-decoration: none;
color: #ffffff;
font-weight: 500;
}

a:hover {
text-decoration: underline;
}

/* RESPONSIVE */
@media (max-width: 768px) {
table, thead, tbody, th, td, tr {
display: block;
}

tr {
margin-bottom: 15px;
}

th {
display: none;
}

td {
padding-left: 50%;
position: relative;
}

td::before {
position: absolute;
left: 10px;
font-weight: bold;
}

td:nth-child(1)::before { content: "Título"; }
td:nth-child(2)::before { content: "Estado"; }
td:nth-child(3)::before { content: "Prioridad"; }
td:nth-child(4)::before { content: "Acción"; }
}
</style>
<link rel="stylesheet" href="../style.css">
<body>
<div class="logo-top">
    <img src="../assets/logo.png" alt="Logo">
</div>
<div class="container card">
    <h2>📋 Lista de Tickets</h2>

    <table>
        <tr>
            <th>Título</th>
            <th>Estado</th>
            <th>Prioridad</th>
            <th>Acción</th>
        </tr>

        <?php while ($t = $tickets->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($t['titulo']) ?></td>
                <td><?= $t['estado'] ?></td>
                <td><?= $t['prioridad'] ?></td>
                <td>
                    <a href="ver_ticket.php?id=<?= $t['id'] ?>" class="btn-ver">
                        👁 Ver
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <button type="submit" name="Volver"><a href="../index.php">⬅ Volver</a></button>

</div>
</body>