<?php
// Iniciamos la sesión de forma segura verificando si no se ha iniciado ya.
// Esto evita los típicos errores "session had already been started".
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Comprobamos que exista la sesión y que el rol sea el correcto.
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "teacher") {
    // Redirección absoluta desde la raíz del dominio web
    header("Location: /index.php");
    exit();
}