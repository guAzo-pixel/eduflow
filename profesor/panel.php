<?php
session_start();
include("../conexion.php");

// SEGURIDAD
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'profesor') {
    header("Location: ../login.php");
    exit();
}

$id_profesor = $_SESSION['id'];
$sql_asignaturas = "SELECT * FROM asignaturas WHERE profesor_id = '$id_profesor' ";
$resultado = mysqli_query($conexion, $sql_asignaturas);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Profesor</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        /* Estilos para que parezca un panel moderno */
        .grid-clases { display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px; }
        .tarjeta-clase { background: #f8f9fa; border: 1px solid #dee2e6; padding: 20px; border-radius: 8px; width: 300px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .tarjeta-clase h3 { margin-top: 0; color: #2c3e50; }
        .btn-entrar { display: inline-block; background: #2c3e50; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; margin-top: 10px; }
        .btn-entrar:hover { background: #1a252f; }
    </style>
</head>
<body>

    <h1>👨‍🏫 Panel del Profesor: <?php echo $_SESSION['nombre']; ?></h1>
    
    <a href="../index.html" style="color: red;">Cerrar Sesión</a>
    <hr>

    <h2>Mis Clases Asignadas</h2>

    <div class="grid-clases">
        <?php
        // 2. Comprobamos si el administrador le ha asignado clases a este profesor
        if (mysqli_num_rows($resultado) > 0) {
            
            // 3. Recorremos las clases y dibujamos una "tarjeta" por cada una
            while ($asignatura = mysqli_fetch_assoc($resultado)) {
                echo "<div class='tarjeta-clase'>";
                echo "<h3>" . $asignatura['nombre'] . "</h3>";
                echo "<p>" . $asignatura['descripcion'] . "</p>";
                
                // 4. EL ENLACE MÁGICO: Lleva el ID de la clase en la URL
                echo "<a href='clase.php?id=" . $asignatura['id'] . "' class='btn-entrar'>Entrar al Aula ➡️</a>";
                echo "</div>";
            }
            
        } else {
            // Si el query devuelve 0 filas
            echo "<p style='color: #666; font-style: italic;'>Aún no tienes clases asignadas. Contacta con el administrador.</p>";
        }
        ?>
    </div>

</body>
</html>