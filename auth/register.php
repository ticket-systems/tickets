<?php
global $conn;
session_start();
require_once '../config/db.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre   = trim($_POST['nombre']);
    $correo   = trim($_POST['correo']);
    $password = $_POST['password'];
    $rol      = 'usuario'; // 👈 POR DEFECTO

    // Validaciones básicas
    if (!$nombre || !$correo || !$password) {
        $error = "Todos los campos son obligatorios";
    } else {

        // Verificar si correo existe
        $check = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $check->bind_param("s", $correo);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {
            $error = "Este correo ya está registrado";
        } else {

            // Encriptar contraseña
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insertar usuario
            $stmt = $conn->prepare(
                    "INSERT INTO usuarios (nombre, correo, password, rol) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("ssss", $nombre, $correo, $hash, $rol);

            if ($stmt->execute()) {
                $success = "Registro exitoso. Ahora puedes iniciar sesión.";
            } else {
                $error = "Error al registrar usuario";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="logo-top">
        <img src="../assets/logo.png" alt="Logo">
    </div>
<div class="register-box card">
    <h2>Registro</h2>

    <?php if ($error): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color:green"><?= $success ?></p>
        <a href="login.php">Iniciar sesión</a>
    <?php endif; ?>

    <?php if (!$success): ?>
        <form method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Registrarse</button>
            <a href="login.php">Volver</a>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
