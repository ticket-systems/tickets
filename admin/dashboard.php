<?php
global $conn;
include("../config/db.php");

$b = $_GET['b'] ?? '';

$sql = $conn->prepare("
    SELECT t.id, t.titulo, t.estado, u.nombre
    FROM tickets t
    JOIN usuarios u ON u.id = t.usuario_id
    WHERE t.titulo LIKE CONCAT('%', ?, '%')
       OR t.descripcion LIKE CONCAT('%', ?, '%')
");
$sql->bind_param("ss", $b, $b);
$sql->execute();
$r = $sql->get_result();
?>
<style>
    *{
        box-sizing:border-box;
        font-family: "Segoe UI", sans-serif;
    }

    body{
        margin:0;
        min-height:100vh;
        background:linear-gradient(120deg,#1b7f3b,#cfead7);
        display:flex;
        justify-content:center;
    }

    .page-wrapper{
        width:100%;
        max-width:1200px;
        padding:30px 20px;
    }
    .container{
        width:100%;
    }

    .card{
        width:100%;
        margin:0 auto;
    }

    h2{
        margin:0 0 20px;
        text-align:center;
        color:#1b7f3b;
    }

    /* BUSCADOR */
    .search-box{
        margin-bottom:15px;
    }

    .search-box input{
        width:100%;
        padding:12px 15px;
        border-radius:10px;
        border:1px solid #ccc;
        outline:none;
    }

    /* TABLA */
    .table-responsive{
        overflow-x:auto;
    }

    .tickets-table{
        width:100%;
        border-collapse:collapse;
    }

    .tickets-table thead{
        background:#1b7f3b;
        color:#fff;
    }

    .tickets-table th,
    .tickets-table td{
        padding:12px;
        text-align:left;
    }

    .tickets-table tbody tr{
        border-bottom:1px solid #eee;
    }

    .tickets-table tbody tr:hover{
        background:#f6fdf9;
    }

    /* ESTADOS */
    .badge{
        padding:5px 12px;
        border-radius:20px;
        font-size:13px;
        font-weight:600;
        color:#fff;
    }

    .abierto{
        background:#28a745;
    }

    .cerrado{
        background:#dc3545;
    }

    .resuelto{
        background:#0d6efd;
    }

    /* BOTONES */
    .btn-ver{
        background:#6f42c1;
        color:#fff;
        padding:6px 14px;
        border-radius:8px;
        text-decoration:none;
        font-size:14px;
    }

    .btn-ver:hover{
        opacity:.85;
    }

    .btn-volver{
        margin-top:20px;
        width:100%;
        padding:12px;
        border:none;
        border-radius:10px;
        background:#107726;
        color:#fff;
        font-size:16px;
        cursor:pointer;
    }

    .btn-volver:hover{
        background:#23bc0b;
    }

    .empty{
        text-align:center;
        color:#777;
        padding:20px;
    }

        }
    }

</style>
<link rel="stylesheet" href="../style.css">
<br>
<body>
<div class="logo-top">
    <img src="../assets/logo.png" alt="Logo">
</div>
<div class="page-wrapper">
    <div class="container card">

        <h2>🎫 Tickets</h2>

        <!-- BUSCADOR -->
        <form class="search-box" method="get">
            <input type="text" name="b" placeholder="Buscar ticket..." value="<?= htmlspecialchars($b) ?>">
        </form>

        <!-- TABLA -->
        <div class="table-responsive">
            <table class="tickets-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Usuario</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($r->num_rows > 0): ?>
                    <?php while ($t = $r->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= $t['id'] ?></td>
                            <td><?= htmlspecialchars($t['titulo']) ?></td>
                            <td><?= htmlspecialchars($t['nombre']) ?></td>
                            <td>
                                <span class="badge <?= $t['estado'] ?>">
                                    <?= ucfirst($t['estado']) ?>
                                </span>
                            </td>
                            <td>
                                <a class="btn-ver" href="../tickets/ver_ticket.php?id=<?= $t['id'] ?>">Ver</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty">No se encontraron tickets</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <button class="btn-volver" onclick="history.back()">⬅ Volver</button>

    </div>
</div>
</body>
