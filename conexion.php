<?php
// Credenciales de la Base de Datos
$servidor = "localhost";
$usuario  = "root";      // Usuario por defecto de XAMPP
$password = "";          // Contraseña por defecto de XAMPP (vacía)
$base_datos = "mi_proyecto_db";

// Crear la conexión
$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

// Verificar si hubo error en la conexión
if (!$conexion) {
    die("Fallo crítico de conexión: " . mysqli_connect_error());
}

if (!mysqli_set_charset($conexion, "utf8mb4")) {
    printf("Error cargando el conjunto de caracteres utf8mb4: %s\n", mysqli_error($conexion));
    exit();
}
?>