<?php
session_start();
/* Si el usuario no esta logeado o no es admin le echamos */
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "admin"){
    header("Location: ../index.php");
    exit();
}

include '../includes/db.php'; 


/* Logica para mostrar los datos del usuario id */
if (isset($_GET['id'])){
    $id = $_GET['id'];

    $sql = "SELECT * FROM Users WHERE id_user = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([':id' => $id]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    /* Si el usuario no existe volvemos a la página de modificación de usuarios */
    if (!$user) {
        header("Location: users.php");
        exit();
    }
}
else{
    header("Location: users.php");
    exit();
}

/*Si se pulsa el boton de guardar */
if (isset($_POST['modificar'])){
    /*Recogemos los datos del formulario*/
    /* trim  es para pasar los datros sin espacios en blanco */
    $new_name = trim($_POST['name']);
    $new_lastname = trim($_POST['lastName']);
    $new_email = trim($_POST['email']);
    $new_password = $_POST['new_password'];
    $new_rol = $_POST['rol'];

    /* Logica para actualizar los datos */
    try {
        /* Identificamos al usuario */
        $id = $_GET['id'];

        /* Logica para canviar la contraseña, solo la canviamos si esta vacia*/
        if (!empty($new_password)){
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

            $sql = "UPDATE Users SET name = :new_name, lastName = :new_lastname, email = :new_email, rol = :new_rol, password = :new_password WHERE id_user = :id";

            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':id' => $id, ':new_name' => $new_name, ':new_lastname' => $new_lastname, ':new_email' => $new_email, ':new_rol' => $new_rol,':new_password' => $password_hash]);
        }
        /* Si no esta modificado el campo contraseña mandamos todos los datos menos la password */
        else{
            $sql = "UPDATE Users SET name = :new_name, lastName = :new_lastname, email = :new_email, rol = :new_rol WHERE id_user = :id";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([':id' => $id, ':new_name' => $new_name, ':new_lastname' => $new_lastname, ':new_email' => $new_email, ':new_rol' => $new_rol]);
        }

        $_SESSION['success_message'] = "Usuario modificado correctamente.";
        header("Location: users.php");
        exit();
        
    }
    catch (PDOException $e) {
        $error = "Error al actualizar: " . $e->getMessage();
    }
}

include '../includes/header.php';

?>

<main>
    <h1>Modificar Usuario</h1>

    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" action="modify_user.php?id=<?php echo $user['id_user']; ?>">

        <label>Nombre</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        
        <label>Apellidos:</label>
        <input type="text" name="lastName" value="<?php echo htmlspecialchars($user['lastName']); ?>" required>
        
        <label>Correo Electrónico:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        
        <label>Nueva Contraseña:</label>
        <input type="text" name="new_password" placeholder="Escribe para cambiarla">

        <label>Rol:</label>
        <select name="rol" required>
            <option value="students" <?php if($user['rol'] == 'students') echo 'selected'; ?>>Alumno</option>
            <option value="teacher" <?php if($user['rol'] == 'teacher') echo 'selected'; ?>>Profesor</option>
            <option value="admin" <?php if($user['rol'] == 'admin') echo 'selected'; ?>>Administrador</option>
        </select>
        
        <button type="submit" name="modificar">Guardar Cambios</button>
        <a href="users.php">Cancelar</a>
    </form>
</main>

<?php include '../includes/footer.php'; ?>