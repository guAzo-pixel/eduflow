<?php
include '../../../includes/auth_teacher.php';
include '../../../includes/db.php'; 

/* Comprobamos que el ID venga por la URL */
if (!isset($_GET['id'])) {
    header("Location: classes.php");
    exit();
}


/* Logica para mostrar los datos de la clase id */
if (isset($_GET['id'])){
    $id = $_GET['id'];

    $sql = "SELECT * FROM Class WHERE id_class = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([':id' => $id]);
    
    $class = $stmt->fetch(PDO::FETCH_ASSOC);

    /* Si la clase no existe volvemos a la página de modificación de usuarios */
    if (!$class) {
        header("Location: classes.php");
        exit();
    }
}
else{
    header("Location: classes.php");
    exit();
}

/*Si se pulsa el boton de guardar */
if (isset($_POST['modificar'])){
    /*Recogemos los datos del formulario*/
    /* trim  es para pasar los datros sin espacios en blanco */
    $new_material = trim($_POST['material']);
    $new_course = trim($_POST['course']);
    $new_subtitle = trim($_POST['subtitle']);

    /* Logica para actualizar los datos */
    try {
        /* Identificamos al usuario */
        $id = $_GET['id'];
    
        $sql = "UPDATE Class SET material = :new_material, course = :new_course,  subtitle = :new_subtitle WHERE id_class = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([':id' => $id, ':new_material' => $new_material, ':new_course' => $new_course, ':new_subtitle' => $new_subtitle]);
        

        $_SESSION['success_message'] = "Clase modificada correctamente.";
        header("Location: classes.php");
        exit();
        
    }
    catch (PDOException $e) {
        $error = "Error al actualizar: " . $e->getMessage();
    }
    
}
include '../../../includes/header.php';

?>

<main>
    <h1>Modificar Clase</h1>

    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" action="modify_class.php?id=<?php echo $class['id_class']; ?>">

        <label>Materia:</label>
        <input type="text" name="material" value="<?php echo htmlspecialchars($class['material']); ?>" required>
        
        <label>Curso:</label>
        <input type="text" name="course" value="<?php echo htmlspecialchars($class['course']); ?>" required>

        <label>Descripción:</label>

        <textarea name="subtitle"><?php echo htmlspecialchars($class['subtitle']); ?></textarea>
        
        <button type="submit" name="modificar">Guardar Cambios</button>
        <a href="classes.php">Cancelar</a>
    </form>
</main>

<?php include '../../../includes/footer.php'; ?>