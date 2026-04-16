<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_teacher.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php'; 
include $_SERVER['DOCUMENT_ROOT'] . '/includes/verify_teacher_class.php';

/* Logica para mostrar los datos de la clase id */
if (isset($_GET['id'])){
    $id = $_GET['id'];

    $sql = "SELECT * FROM Topic WHERE id_topic = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([':id' => $id]);
    
    $topic = $stmt->fetch(PDO::FETCH_ASSOC);

    /* Si el tema no existe volvemos a la página de modificación de usuarios */
    if (!$topic) {
        header("Location: ../class_dashboard.php?id_class=$id_class");
        exit();
    }
}
else{
    header("Location: ../class_dashboard.php?id_class=$id_class");
    exit();
}

/*Si se pulsa el boton de guardar */
if (isset($_POST['modificar'])){
    /*Recogemos los datos del formulario*/
    /* trim  es para pasar los datros sin espacios en blanco */
    $new_number = trim($_POST['number']);
    $new_title = trim($_POST['title']);
    $new_subtitle = trim($_POST['subtitle']);

    /* Logica para actualizar los datos */
    try {
        /* Identificamos al usuario */
        $id = $_GET['id'];
    
        $sql = "UPDATE Topic SET number = :new_number, title = :new_title,  subtitle = :new_subtitle WHERE id_topic = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([':id' => $id, ':new_number' => $new_number, ':new_title' => $new_title, ':new_subtitle' => $new_subtitle]);
        

        $_SESSION['success_message'] = "Clase modificada correctamente.";
        header("Location: ../class_dashboard.php?id_class=$id_class");
        exit();
        
    }
    catch (PDOException $e) {
        $error = "Error al actualizar: " . $e->getMessage();
    }
    
}
include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';

?>

<main>
    <h1>Modificar Tema</h1>

    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" action="modify_topic.php?id=<?php echo $topic['id_topic']; ?>&id_class=<?php echo htmlspecialchars($_GET['id_class']); ?>">

        <label>Orden:</label>
        <input type="text" name="number" value="<?php echo htmlspecialchars($topic['number']); ?>" required>
        
        <label>Titulo:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($topic['title']); ?>" required>

        <label>Descripción:</label>
        <input type="text" name="subtitle" value="<?php echo htmlspecialchars($topic['subtitle']); ?>">
        
        <button type="submit" name="modificar">Guardar Cambios</button>
        <a href="../class_dashboard.php?id_class=<?php echo htmlspecialchars($_GET['id_class']); ?>">Cancelar</a>
    </form>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>