<?php
session_start();

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "teacher"){
    header("Location: ../../../../index.php");
    exit();
}

include '../../../../includes/db.php'; 


/* Comprobamos que el ID venga por la URL */
if (!isset($_GET['id_class'])) {
    header("Location: ../classes.php");
    exit();
}

try {
    /*Barrera de seguridad, solo el profe dueño de la clase puede acceder a la modificación de esta*/
    $id_class = $_GET['id_class'];

    $id = $_SESSION['user_id'];

    $sql = "SELECT * FROM Class WHERE id_teacher = :id AND id_class = :id_class"; 
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([':id' => $id, ':id_class' => $id_class]); 
    
    $classes = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$classes){
        header("Location: ../classes.php");
        exit();
    }
}
catch (PDOException $e) {
    $error = "Error al cargar la clase: " . $e->getMessage();
}
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
include '../../../../includes/header.php';

?>

<main>
    <h1>Modificar Tema</h1>

    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" action="modify_topic.php?id=<?php echo $topic['id_topic']; ?>&id_class=<?php echo $_GET['id_class']; ?>">

        <label>Orden:</label>
        <input type="text" name="number" value="<?php echo htmlspecialchars($topic['number']); ?>" required>
        
        <label>Titulo:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($topic['title']); ?>" required>

        <label>Descripción:</label>
        <input type="text" name="subtitle" value="<?php echo htmlspecialchars($topic['subtitle']); ?>">
        
        <button type="submit" name="modificar">Guardar Cambios</button>
        <a href="../class_dashboard.php?id_class=<?php echo $_GET['id_class']; ?>">Cancelar</a>
    </form>
</main>

<?php include '../../../../includes/footer.php'; ?>