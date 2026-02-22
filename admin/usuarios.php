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
// 2. LÓGICA: ELIMINAR (DELETE)
// ==========================================
if (isset($_GET['borrar'])) {
    $id_borrar = $_GET['borrar'];
    
    // PROTECCIÓN: No puedes borrarte a ti mismo
    if ($id_borrar == $_SESSION['id']) {
        $mensaje = "❌ No puedes eliminar tu propia cuenta mientras estás conectado.";
    } else {
        $sql_borrar = "DELETE FROM usuarios WHERE id = '$id_borrar'";
        if (mysqli_query($conexion, $sql_borrar)) {
            $mensaje = "🗑️ Usuario eliminado correctamente.";
        } else {
            $mensaje = "❌ Error al eliminar: " . mysqli_error($conexion);
        }
    }
}

// ==========================================
// 3. LÓGICA: GUARDAR / ACTUALIZAR (CREATE / UPDATE)
// ==========================================
// (Variables por defecto para el formulario)
$id = ""; $dni = ""; $nombre = ""; $email = ""; $rol = ""; $grado = ""; 
$boton = "Crear Usuario";

// Si pulsamos "Editar", rellenamos el formulario
if (isset($_GET['editar'])) {
    $id_editar = $_GET['editar'];
    $resultado = mysqli_query($conexion, "SELECT * FROM usuarios WHERE id = '$id_editar'");
    if ($fila = mysqli_fetch_assoc($resultado)) {
        $id = $fila['id'];
        $dni = $fila['dni'];
        $nombre = $fila['nombre'];
        $email = $fila['email'];
        $rol = $fila['rol'];
        $grado = $fila['grado'];
        $boton = "Actualizar Usuario";
    }
}

// Procesar el formulario (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_post = $_POST['id_usuario'];
    $dni_post = $_POST['dni'];
    $nombre_post = $_POST['nombre'];
    $email_post = $_POST['email'];
    $rol_post = $_POST['rol'];
    $grado_post = $_POST['grado'];
    $pass_post = $_POST['password'];

    if (!empty($id_post)) {
        // UPDATE
        if (!empty($pass_post)) {
            $pass_hash = password_hash($pass_post, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET dni='$dni_post', nombre='$nombre_post', email='$email_post', password='$pass_hash', rol='$rol_post', grado='$grado_post' WHERE id='$id_post'";
        } else {
            $sql = "UPDATE usuarios SET dni='$dni_post', nombre='$nombre_post', email='$email_post', rol='$rol_post', grado='$grado_post' WHERE id='$id_post'";
        }
        if (mysqli_query($conexion, $sql)) {
            $mensaje = "✅ Usuario actualizado.";
            // Limpiamos
            $id = ""; $dni = ""; $nombre = ""; $email = ""; $grado = ""; $boton = "Crear Usuario";
        }
    } else {
        // INSERT
        $pass_hash = password_hash($pass_post, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (dni, nombre, email, password, rol, grado) VALUES ('$dni_post', '$nombre_post', '$email_post', '$pass_hash', '$rol_post', '$grado_post')";
        if (mysqli_query($conexion, $sql)) {
            $mensaje = "✅ Usuario creado.";
        }
    }
}

// ==========================================
// 4. LÓGICA: BUSCAR Y ORDENAR (READ AVANZADO)
// ==========================================

// Búsqueda: Si escriben algo, añadimos un WHERE
$condicion_busqueda = "";
$busqueda_actual = "";

if (isset($_GET['busqueda'])) {
    $busqueda_actual = $_GET['busqueda'];
    // LIKE %texto% busca coincidencias parciales
    $condicion_busqueda = "WHERE nombre LIKE '%$busqueda_actual%' OR dni LIKE '%$busqueda_actual%' OR email LIKE '%$busqueda_actual%'";
}

// Orden: Si hacen clic en un título, añadimos ORDER BY
$orden = "id"; // Orden por defecto
if (isset($_GET['orden'])) {
    $orden = $_GET['orden'];
}
// SQL Final Dinámico
$sql_final = "SELECT * FROM usuarios $condicion_busqueda ORDER BY $orden ASC";
$consulta = mysqli_query($conexion, $sql_final);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        .barra-herramientas { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .formulario-busqueda { display: flex; gap: 10px; }
        .acciones { display: flex; gap: 5px; }
        .btn-borrar { color: white; background: red; text-decoration: none; padding: 5px; border-radius: 3px; }
        .btn-editar { color: white; background: orange; text-decoration: none; padding: 5px; border-radius: 3px; }
    </style>
</head>
<body>

    <h1>Gestión de Usuarios</h1>
    <a href="panel.php">⬅ Volver al Panel</a>
    <hr>

    <?php if ($mensaje != "") { echo "<h3>$mensaje</h3>"; } ?>

    <div style="background: #f4f4f4; padding: 20px; border-radius: 5px; margin-bottom: 30px;">
        <h2><?php echo $boton; ?></h2>
        <form action="" method="POST">
            <input type="hidden" name="id_usuario" value="<?php echo $id; ?>">
            <input type="text" name="dni" placeholder="DNI" value="<?php echo $dni; ?>" required>
            <input type="text" name="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?php echo $email; ?>" required>
            <input type="password" name="password" placeholder="<?php echo empty($id) ? 'Contraseña (Obligatoria)' : 'Contraseña (Opcional)'; ?>" <?php echo empty($id) ? 'required' : ''; ?>>
            <select name="rol">
                <option value="alumno" <?php if($rol == 'alumno') echo 'selected'; ?>>Alumno</option>
                <option value="profesor" <?php if($rol == 'profesor') echo 'selected'; ?>>Profesor</option>
                <option value="administrador" <?php if($rol == 'administrador') echo 'selected'; ?>>Administrador</option>
            </select>
            <input type="text" name="grado" placeholder="Grado (Solo alumnos)" value="<?php echo $grado; ?>">
            
            <button type="submit"><?php echo $boton; ?></button>
            <?php if(!empty($id)): ?>
                <a href="usuarios.php" style="color: red; margin-left: 10px;">Cancelar</a>
            <?php endif; ?>
        </form>
    </div>

    <hr>

    <div class="barra-herramientas">
        <h2>Lista de Usuarios</h2>
        
        <form action="" method="GET" class="formulario-busqueda">
            <input type="text" name="busqueda" placeholder="Buscar por nombre, DNI..." value="<?php echo $busqueda_actual; ?>">
            <button type="submit">🔍 Buscar</button>
            <?php if($busqueda_actual != ""): ?>
                <a href="usuarios.php"><button type="button">Limpiar</button></a>
            <?php endif; ?>
        </form>
    </div>

    <table border="1" width="100%" cellpadding="10">
        <thead style="background: #333; color: white;">
            <tr>
                <th><a href="?orden=dni&busqueda=<?php echo $busqueda_actual; ?>" style="color: white;">DNI ↕</a></th>
                <th><a href="?orden=nombre&busqueda=<?php echo $busqueda_actual; ?>" style="color: white;">Nombre ↕</a></th>
                <th><a href="?orden=rol&busqueda=<?php echo $busqueda_actual; ?>" style="color: white;">Rol ↕</a></th>
                <th><a href="?orden=email&busqueda=<?php echo $busqueda_actual; ?>" style="color: white;">Email ↕</a></th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (mysqli_num_rows($consulta) > 0) {
            while ($fila = mysqli_fetch_assoc($consulta)) {
                echo "<tr>";
                echo "<td>" . $fila['dni'] . "</td>";
                echo "<td>" . $fila['nombre'] . "</td>";
                echo "<td>" . $fila['rol'] . "</td>";
                echo "<td>" . $fila['email'] . "</td>";
                echo "<td class='acciones'>";
                
                // Botón Editar
                echo "<a href='usuarios.php?editar=" . $fila['id'] . "' class='btn-editar'>✏️</a>";
                
                // Botón Eliminar con confirmación JS
                // return confirm() detiene el enlace si el usuario dice "Cancelar"
                echo "<a href='usuarios.php?borrar=" . $fila['id'] . "' class='btn-borrar' onclick='return confirm(\"¿Estás seguro de querer eliminar a este usuario?\")'>🗑️</a>";
                
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5' align='center'>No se encontraron usuarios.</td></tr>";
        }
        ?>
        </tbody>
    </table>

</body>
</html>