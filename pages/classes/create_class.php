<?php
session_start();
/* Si el usuario no esta logeado o no es admin le echamos */
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "admin"){
    header("Location: ../../index.php");
    exit();
}

include '../../includes/db.php'; 

if (isset($_POST['crear'])){
    /*Recogemos los datos del formulario*/
    /* trim  es para pasar los datros sin espacios en blanco */
    $new_material = trim($_POST['material']);
    $new_course = trim($_POST['course']);
    $new_creator = trim($_POST['creator']);

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

/*Cargamos la lista de profes*/
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
    <h1>Crear Clase</h1>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="create_class.php">
        <label>Materia:</label>
        <input type="text" name="material" placeholder="Materia..." required>
        
        <label>Curso:</label>
        <input type="text" name="course" placeholder="Curso..." required>

        <label>Creador:</label>
        <select name="creator" required>
            <option value="">-- Selecciona un profesor --</option>
            <?php foreach ($users as $user): ?>
                <?php if($user['id_user'] == $_SESSION['user_id']):?>
                    <option value="<?php echo $user['id_user']; ?>">
                        Crear Como Administrador
                    </option>
                
                <?php elseif($user['rol'] == "teacher"):?>
                    <option value="<?php echo $user['id_user']; ?>">
                        <?php echo htmlspecialchars($user['name'] . ' ' . $user['lastName']); ?>
                    </option>

                <?php endif; ?>
            <?php endforeach; ?>
        </select>

        <button type="submit" name="crear">Crear Clase</button>
        <a href="classes.php">Cancelar</a>
    </form>
</main>

<?php include '../../includes/footer.php'; ?>