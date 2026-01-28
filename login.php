<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../config/db.php';
global $conn;
$error = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $correo   = $_POST['correo'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $usuario = $res->fetch_assoc();

        if (password_verify($password, $usuario['password'])) {

            $_SESSION['id']     = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol']    = $usuario['rol'];
            echo "LOGIN OK";

            header("Location: /sistema_tickets/index.php");
            exit;
        }

    }

    $error = "Correo o contraseña incorrectos";
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="logo-top">
    <img src="../assets/logo.png" alt="Logo">
</div>

<div class="login-box card">

    <h2>Iniciar sesión</h2>

    <?php if ($error): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
        <a   href="register.php">Registrarse</a>
    </form>

</div>
</body>
</html>