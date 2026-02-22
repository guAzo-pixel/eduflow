<?php
session_start();
include("../conexion.php");

// 1. SEGURIDAD
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'administrador') {
    header("Location: ../index.php");
    exit();
}

$mensaje = "";

// 2. LÓGICA: MATRICULAR
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $alumno_id = $_POST['alumno_id'];
    $asignatura_id = $_POST['asignatura_id'];

    // Comprobar duplicados
    $check = mysqli_query($conexion, "SELECT * FROM matriculas WHERE alumno_id='$alumno_id' AND asignatura_id='$asignatura_id'");
    
    if (mysqli_num_rows($check) > 0) {
        $mensaje = "⚠️ El alumno ya está en esa clase.";
    } else {
        $sql = "INSERT INTO matriculas (alumno_id, asignatura_id) VALUES ('$alumno_id', '$asignatura_id')";
        if (mysqli_query($conexion, $sql)) {
            $mensaje = "✅ Matrícula realizada.";
        } else {
            $mensaje = "❌ Error: " . mysqli_error($conexion);
        }
    }
}

// 3. LÓGICA: BORRAR
if (isset($_GET['borrar'])) {
    $id_borrar = $_GET['borrar'];
    mysqli_query($conexion, "DELETE FROM matriculas WHERE id='$id_borrar'");
    $mensaje = "🗑️ Matrícula eliminada.";
}

// 4. CARGAR DATOS
$alumnos = mysqli_query($conexion, "SELECT * FROM usuarios WHERE rol='alumno' ORDER BY nombre ASC");
$asignaturas = mysqli_query($conexion, "SELECT * FROM asignaturas ORDER BY nombre ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Matrículas</title>
    <link rel="stylesheet" href="../css/estilos.css">
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        .select2-container .select2-selection--single {
            height: 38px;
            padding: 5px;
        }
    </style>
</head>
<body>

    <h1>Gestión de Matrículas</h1>
    <a href="panel.php">⬅ Volver al Panel</a>
    <hr>

    <?php if ($mensaje != "") { echo "<h3>$mensaje</h3>"; } ?>

    <div style="background: #e8f5e9; padding: 20px; border-radius: 5px;">
        <h2>Nueva Matrícula</h2>
        <form action="" method="POST">
            
            <label>1. Buscar Alumno:</label><br>
            <select name="alumno_id" class="buscador-inteligente" required style="width: 100%;">
                <option value="">Escribe para buscar alumno...</option>
                <?php 
                // Reiniciamos el puntero por si acaso
                mysqli_data_seek($alumnos, 0);
                while($alu = mysqli_fetch_assoc($alumnos)): 
                ?>
                    <option value="<?php echo $alu['id']; ?>">
                        <?php echo $alu['nombre']; ?> (DNI: <?php echo $alu['dni']; ?>)
                    </option>
                <?php endwhile; ?>
            </select>

            <br>

            <label>2. Buscar Asignatura:</label><br>
            <select name="asignatura_id" class="buscador-inteligente" required style="width: 100%;">
                <option value="">Escribe para buscar clase...</option>
                <?php 
                mysqli_data_seek($asignaturas, 0);
                while($asig = mysqli_fetch_assoc($asignaturas)): 
                ?>
                    <option value="<?php echo $asig['id']; ?>">
                        <?php echo $asig['nombre']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            
            <br><br>
            <button type="submit" style="width: 100%; padding: 10px;">Matricular Alumno</button>
        </form>
    </div>

    <hr>

    <h2>Matrículas Activas</h2>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Alumno</th>
                <th>Asignatura</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql_lista = "SELECT m.id, u.nombre AS nombre_alumno, a.nombre AS nombre_asignatura 
                      FROM matriculas m 
                      JOIN usuarios u ON m.alumno_id = u.id 
                      JOIN asignaturas a ON m.asignatura_id = a.id
                      ORDER BY m.id DESC";
        $consulta = mysqli_query($conexion, $sql_lista);

        while ($fila = mysqli_fetch_assoc($consulta)) {
            echo "<tr>";
            echo "<td>" . $fila['id'] . "</td>";
            echo "<td>" . $fila['nombre_alumno'] . "</td>";
            echo "<td><strong>" . $fila['nombre_asignatura'] . "</strong></td>";
            echo "<td><a href='matriculas.php?borrar=" . $fila['id'] . "' onclick='return confirm(\"¿Seguro?\")' style='color:red'>Quitar</a></td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            // Esto convierte todos los selects con la clase .buscador-inteligente en buscadores
            $('.buscador-inteligente').select2();
        });
    </script>

</body>
</html>