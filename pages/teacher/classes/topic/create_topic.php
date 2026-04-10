<?php
session_start();

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "teacher"){
    header("Location: ../../../../index.php");
    exit();
}

include '../../../../includes/db.php'; 

/* Comprobamos que el ID venga por la URL */
if (!isset($_GET['id_class'])) {
    header("Location: classes.php");
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
    
    /*Consultamos el mumero actual de temas para saber que numero recomendar*/
    $sql_max = "SELECT MAX(number) as ultimo FROM Topic WHERE id_class = :id_class";
    $stmt_max = $pdo->prepare($sql_max);
    $stmt_max->execute([':id_class' => $id_class]);
    $res_max = $stmt_max->fetch(PDO::FETCH_ASSOC);
    $sugerencia_numero = ($res_max['ultimo'] ?? 0) + 1;


    /*logica del formularrio */
    if (isset($_POST['crear'])){
        /*Recogemos los datos del formulario*/
        /* trim  es para pasar los datros sin espacios en blanco */
        $new_number = trim($_POST['number']);
        $new_title = trim($_POST['title']);
        $new_subtitle = trim($_POST['subtitle']);

    
        $sql = "INSERT INTO Topic (id_class, number, title, subtitle) VALUES (:id_class, :new_number, :new_title, :new_subtitle)";
        
        $stmt = $pdo->prepare($sql);
            
        $stmt->execute([':id_class' => $id_class, ':new_number' => $new_number, ':new_title' => $new_title, ':new_subtitle' => $new_subtitle]);

        $_SESSION['success_message'] = "Tema creado correctamente.";
        header("Location: ../class_dashboard.php?id_class=$id_class");
        exit();
    }
}
catch (PDOException $e) {
    $error = "Error al cargar la clase: " . $e->getMessage();
}

include '../../../../includes/header.php';

?>

<main>
    <h1>Crear Tema de: <?php echo htmlspecialchars($classes['material']); ?> <?php echo htmlspecialchars($classes['course']); ?></h1>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="create_topic.php?id_class=<?php echo $id_class; ?>">
        <label>Orden:</label>
        <input type="text" name="number" placeholder="<?php echo $sugerencia_numero; ?>" required>
        
        <label>Titulo:</label>
        <input type="text" name="title" placeholder="Titulo del tema (Ej: Numeros Reales)..." required>

        <label>Descripción:</label>
        <input type="text" name="subtitle" placeholder="Historia básica de la penins...">


        <button type="submit" name="crear">Crear Tema</button>
        <a href="../class_dashboard.php?id_class=<?php echo $id_class; ?>">Cancelar</a>
    </form>
</main>

<?php include '../../../../includes/footer.php'; ?>
    
