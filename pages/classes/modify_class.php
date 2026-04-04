<?php
session_start();
/* Si el usuario no esta logeado o no es admin le echamos */
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "admin"){
    header("Location: ../../index.php");
    exit();
}

include '../../includes/db.php'; 


/* Logica para mostrar los datos del usuario id */
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
    $new_creator = trim($_POST['creator']);

    /* Logica para actualizar los datos */
    try {
        /* Identificamos al usuario */
        $id = $_GET['id'];
    
        $sql = "UPDATE Class SET material = :new_material, course = :new_course, id_teacher = :new_creator WHERE id_class = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([':id' => $id, ':new_material' => $new_material, ':new_course' => $new_course, ':new_creator' => $new_creator]);
        

        $_SESSION['success_message'] = "Clase modificado correctamente.";
        header("Location: classes.php");
        exit();
        
    }
    catch (PDOException $e) {
        $error = "Error al actualizar: " . $e->getMessage();
    }
    
}
    try {
        $sql_teachers = "SELECT id_user, name, rol, lastName FROM Users WHERE rol = 'teacher' OR rol = 'admin'";
        $stmt_teachers = $pdo->query($sql_teachers);
        $users = $stmt_teachers->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Error al cargar los profesores: " . $e->getMessage();
    }
include '../../includes/header.php';

?>

<main>
    <h1>Modificar Clase</h1>

    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" action="modify_class.php?id=<?php echo $class['id_class']; ?>">

        <label>Materia:</label>
        <input type="text" name="material" value="<?php echo htmlspecialchars($class['material']); ?>" required>
        
        <label>Curso:</label>
        <input type="text" name="course" value="<?php echo htmlspecialchars($class['course']); ?>" required>

        <label>Creador:</label>
        <select name="creator" required>
            <option value="">-- Selecciona un profesor --</option>
            <?php foreach ($users as $user): ?>
                <?php if($user['id_user'] == $_SESSION['user_id']):?>
                    <option value="<?php echo $user['id_user']; ?>">
                        Administrador
                    </option>
                
                <?php elseif($user['rol'] == "teacher"):?>
                    <option value="<?php echo $user['id_user']; ?>" <?php if($user['id_user'] == $class['id_teacher']) { echo 'selected'; } ?> >
                        <?php echo htmlspecialchars($user['name'] . ' ' . $user['lastName']); ?>
                    </option>
                    

                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        
        <button type="submit" name="modificar">Guardar Cambios</button>
        <a href="classes.php">Cancelar</a>
    </form>
</main>

<?php include '../../includes/footer.php'; ?>