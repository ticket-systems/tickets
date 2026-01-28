<?php global $conn;
include("../config/db.php");
$id=$_SESSION['user']['id'];
$b=$_GET['b']??'';
$r=$conn->query("SELECT * FROM tickets WHERE usuario_id=$id AND (titulo LIKE '%$b%' OR descripcion LIKE '%$b%')");
?>
<link rel="stylesheet" href="../style.css">
<div class="container">
<div class="card">
<form><label>
        <input name="b" placeholder="Buscar">
    </label></form>
<a href="crear_ticket.php">Crear nuevo ticket</a>
</div>
<?php while($t=$r->fetch_assoc()){ ?>
<div class="card ticket">
<div><b><?=$t['titulo']?></b><span class="badge <?=$t['estado']?>"><?=$t['estado']?></span></div>
<a href="../tickets/ver_ticket.php?id=<?=$t['id']?>">Ver</a>
</div><?php } ?></div>