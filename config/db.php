<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sistema_tickets";
date_default_timezone_set('America/Mexico_City');
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión");
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

