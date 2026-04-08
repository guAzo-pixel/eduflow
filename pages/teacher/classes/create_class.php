<?php
session_start();

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "teacher"){
    header("Location: ../../../index.php");
    exit();
}

include '../../../includes/db.php'; 

if (isset($_POST['crear'])){
    /*Recogemos los datos del formulario*/
    /* trim  es para pasar los datros sin espacios en blanco */
    $new_material = trim($_POST['material']);
    $new_course = trim($_POST['course']);
    $new_creator = $_SESSION['user_id'];

    try{
        $sql = "INSERT INTO Class (material, course, id_teacher) VALUES (:new_material, :new_course, :new_creator)";
        
        $stmt = $pdo->prepare($sql);
            
        $stmt->execute([':new_material' => $new_material, ':new_course' => $new_course, ':new_creator' => $new_creator]);

        $_SESSION['success_message'] = "Clase creada correctamente.";
        header("Location: classes.php");
        exit();
    } 
    catch (PDOException $e) {
        $error = "Error al crear: " . $e->getMessage();
    }
}

include '../../../includes/header.php';

?>
<main>
    <h1>Crear Clase</h1>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="create_class.php">
        <label>Materia:</label>
        <input type="text" name="material" placeholder="Materia..." required>
        
        <label>Curso:</label>
        <input type="text" name="course" placeholder="Curso..." required>

        <label>Creador:</label>
        <input type="text" value="<?php 
            /* Usamos los datos de la sesion para evitar cosultas inecesarias*/
            echo $_SESSION['user_name'] . ' ' . $_SESSION['user_lastName']; ?>" disabled>

        <button type="submit" name="crear">Crear Clase</button>
        <a href="classes.php">Cancelar</a>
    </form>
</main>

<?php include '../../../includes/footer.php'; ?>