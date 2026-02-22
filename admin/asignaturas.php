<?php
session_start();
include("../conexion.php");

// 1. SEGURIDAD
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'administrador') {
    header("Location: ../index.php");
    exit();
}

$mensaje = "";

// ==========================================
// 2. OBTENER LISTA DE PROFESORES (Para el Select)
// ==========================================
// Necesitamos esto ANTES de mostrar el formulario para llenar el desplegable
$sql_profes = "SELECT * FROM usuarios WHERE rol = 'profesor'";
$res_profes = mysqli_query($conexion, $sql_profes);

// ==========================================
// 3. LÓGICA: ELIMINAR
// ==========================================
if (isset($_GET['borrar'])) {
    $id_borrar = $_GET['borrar'];
    if (mysqli_query($conexion, "DELETE FROM asignaturas WHERE id = '$id_borrar'")) {
        $mensaje = "🗑️ Asignatura eliminada.";
    } else {
        $mensaje = "❌ Error: " . mysqli_error($conexion);
    }
}

// ==========================================
// 4. LÓGICA: GUARDAR / EDITAR
// ==========================================
$id = ""; $nombre = ""; $desc = ""; $profe_id = ""; 
$boton = "Crear Asignatura";

// Si editamos, rellenamos datos
if (isset($_GET['editar'])) {
    $id_editar = $_GET['editar'];
    $res = mysqli_query($conexion, "SELECT * FROM asignaturas WHERE id = '$id_editar'");
    if ($fila = mysqli_fetch_assoc($res)) {
        $id = $fila['id'];
        $nombre = $fila['nombre'];
        $desc = $fila['descripcion'];
        $profe_id = $fila['profesor_id'];
        $boton = "Actualizar Asignatura";
    }
}

// Procesar Formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_post = $_POST['id_asignatura'];
    $nombre_post = $_POST['nombre'];
    $desc_post = $_POST['descripcion'];
    $profe_post = $_POST['profesor_id']; // Aquí recibimos el ID del profesor seleccionado

    if (!empty($id_post)) {
        // UPDATE
        $sql = "UPDATE asignaturas SET nombre='$nombre_post', descripcion='$desc_post', profesor_id='$profe_post' WHERE id='$id_post'";
        if (mysqli_query($conexion, $sql)) {
            $mensaje = "✅ Asignatura actualizada.";
            $id = ""; $nombre = ""; $desc = ""; $profe_id = ""; $boton = "Crear Asignatura";
        }
    } else {
        // INSERT
        $sql = "INSERT INTO asignaturas (nombre, descripcion, profesor_id) VALUES ('$nombre_post', '$desc_post', '$profe_post')";
        if (mysqli_query($conexion, $sql)) {
            $mensaje = "✅ Asignatura creada.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Asignaturas</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

    <h1>Gestión de Asignaturas</h1>
    <a href="panel.php">⬅ Volver al Panel</a>
    <hr>

    <?php if ($mensaje != "") { echo "<h3>$mensaje</h3>"; } ?>

    <div style="background: #e3f2fd; padding: 20px; border-radius: 5px;">
        <h2><?php echo $boton; ?></h2>
        <form action="" method="POST">
            <input type="hidden" name="id_asignatura" value="<?php echo $id; ?>">
            
            <label>Nombre de la Asignatura:</label><br>
            <input type="text" name="nombre" value="<?php echo $nombre; ?>" required style="width: 100%"><br><br>
            
            <label>Descripción:</label><br>
            <textarea name="descripcion" rows="3" style="width: 100%"><?php echo $desc; ?></textarea><br><br>

            <label>Profesor Asignado:</label><br>
            <select name="profesor_id" required style="width: 100%; padding: 5px;">
                <option value="">-- Selecciona un Profesor --</option>
                <?php
                // Reiniciamos el puntero de profesores para recorrerlos de nuevo
                mysqli_data_seek($res_profes, 0); 
                while($profe = mysqli_fetch_assoc($res_profes)): 
                ?>
                    <option value="<?php echo $profe['id']; ?>" 
                        <?php if($profe_id == $profe['id']) echo 'selected'; ?>>
                        <?php echo $profe['nombre']; ?> (<?php echo $profe['email']; ?>)
                    </option>
                <?php endwhile; ?>
            </select><br><br>
            
            <button type="submit"><?php echo $boton; ?></button>
            <?php if(!empty($id)): ?>
                <a href="asignaturas.php" style="color: red; margin-left: 10px;">Cancelar</a>
            <?php endif; ?>
        </form>
    </div>

    <hr>

    <h2>Clases Existentes</h2>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>Asignatura</th>
                <th>Descripción</th>
                <th>Profesor</th> <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // LA MAGIA DEL JOIN:
        // "Traeme todo de asignaturas Y el nombre del usuario que coincida con el profesor_id"
        $sql_listado = "SELECT asignaturas.*, usuarios.nombre AS nombre_profe 
                        FROM asignaturas 
                        LEFT JOIN usuarios ON asignaturas.profesor_id = usuarios.id";
        
        $consulta = mysqli_query($conexion, $sql_listado);

        while ($fila = mysqli_fetch_assoc($consulta)) {
            echo "<tr>";
            echo "<td><strong>" . $fila['nombre'] . "</strong></td>";
            echo "<td>" . $fila['descripcion'] . "</td>";
            
            // Si borraste al profesor, el nombre saldrá vacío, así que controlamos eso:
            $nombre_profesor = $fila['nombre_profe'] ? $fila['nombre_profe'] : "<span style='color:red'>Sin asignar</span>";
            echo "<td>" . $nombre_profesor . "</td>";
            
            echo "<td>";
            echo "<a href='asignaturas.php?editar=" . $fila['id'] . "'>✏️</a> ";
            echo "<a href='asignaturas.php?borrar=" . $fila['id'] . "' onclick='return confirm(\"¿Borrar clase?\")'>🗑️</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>

</body>
</html>