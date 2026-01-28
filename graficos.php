<?php
global $conn;
session_start();
require_once __DIR__ . '/config/db.php';

// 🔒 Validar sesión
if (!isset($_SESSION['id'], $_SESSION['rol'])) {
    die("No autorizado");
}

// 🔐 Validar rol
if (!in_array(strtolower($_SESSION['rol']), ['admin', 'tecnico'])) {
    die("No autorizado");
}

/* =========================
   📊 TICKETS RESUELTOS POR DÍA
   ========================= */
$porDia = [];
$sqlDia = "
    SELECT DATE(fecha_cierre) AS dia, COUNT(*) AS total
    FROM tickets
    WHERE estado = 'resuelto'
    GROUP BY dia
    ORDER BY dia
";
$resultDia = $conn->query($sqlDia);

while ($row = $resultDia->fetch_assoc()) {
    $porDia['labels'][] = $row['dia'];
    $porDia['data'][]   = $row['total'];
}

/* =========================
   📊 TICKETS RESUELTOS POR MES
   ========================= */
$porMes = [];
$sqlMes = "
    SELECT DATE_FORMAT(fecha_cierre, '%Y-%m') AS mes, COUNT(*) AS total
    FROM tickets
    WHERE estado = 'resuelto'
    GROUP BY mes
    ORDER BY mes
";
$resultMes = $conn->query($sqlMes);

while ($row = $resultMes->fetch_assoc()) {
    $porMes['labels'][] = $row['mes'];
    $porMes['data'][]   = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Tickets Resueltos</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: #c9f2c0;
            font-family: Arial, sans-serif;
        }
        .container {
            width: 500px;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
        }
        h2 {
            text-align: center;
        }
        canvas {
            margin-top: 20px;
        }
        .volver {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            background: #0a7d28;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>📊 Reporte de Tickets Resueltos</h2>

    <h3>Por Día</h3>
    <canvas id="graficoDia"></canvas>

    <h3>Por Mes</h3>
    <canvas id="graficoMes"></canvas>

    <button class="volver" onclick="history.back()">← Volver</button>

</div>

<script>
    /* ====== GRAFICO POR DÍA ====== */
    new Chart(document.getElementById('graficoDia'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($porDia['labels'] ?? []) ?>,
            datasets: [{
                label: 'Tickets Resueltos',
                data: <?= json_encode($porDia['data'] ?? []) ?>,
                backgroundColor: '#4da3ff'
            }]
        }
    });

    /* ====== GRAFICO POR MES ====== */
    new Chart(document.getElementById('graficoMes'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($porMes['labels'] ?? []) ?>,
            datasets: [{
                label: 'Tickets Resueltos',
                data: <?= json_encode($porMes['data'] ?? []) ?>,
                backgroundColor: '#6ad48a'
            }]
        }
    });

</script>

</body>

</html>

