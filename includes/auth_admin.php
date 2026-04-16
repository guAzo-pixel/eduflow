<?php
// Iniciamos la sesión de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Comprobamos que exista la sesión y que el rol sea de administrador
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "admin") {
    header("Location: /index.php");
    exit();
}