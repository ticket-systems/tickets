<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requiereLogin() {
    if (!isset($_SESSION['id'])) {
        header("Location: ../auth/login.php");
        exit;
    }
}

function requiereRol($roles) {
    if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], (array)$roles)) {
        die("🚫 No autorizado");
    }
}
