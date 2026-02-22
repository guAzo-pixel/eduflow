<?php
session_start();
require "conexion.php";

// 1. VALIDACIÓN PREVIA (Evita errores si se entra directo)
if (!isset($_POST['email']) || !isset($_POST['password'])) {
    // Si no hay datos, lo mandamos de vuelta al formulario
    header("Location: index.html"); 
    exit();
}

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT id, nombre, password, rol FROM usuarios WHERE email = ?";
$stmt = $conexion->prepare($sql);

// 2. CONTROL DE ERRORES SQL (Por si te equivocaste al escribir la consulta)
if ($stmt === false) {
    die("Error en la preparación: " . $conexion->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();

    if (password_verify($password, $usuario['password'])) {
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];

        // 3. REDIRECCIÓN SEGÚN ROL (La lógica de negocio)
        if ($usuario['rol'] === 'administrador') {
            header("Location: admin/panel.php");
        } elseif ($usuario['rol'] === 'profesor') {
            header("Location: profesor/panel.php");
        } else {
            header("Location: alumno/panel.php");
        }
        exit(); // Siempre usa exit después de un header location

    } else {
        // Contraseña incorrecta
        echo "Credenciales incorrectas"; 
    }
} else {
    // Usuario no encontrado
    echo "Credenciales incorrectas";
}
?>