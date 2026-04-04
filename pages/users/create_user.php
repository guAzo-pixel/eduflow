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
    $new_name = trim($_POST['name']);
    $new_lastname = trim($_POST['lastName']);
    $new_email = trim($_POST['email']);
    $new_password = $_POST['new_password'];
    $new_rol = $_POST['rol'];

    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

    try{
        $sql = "INSERT INTO Users (name, lastName, email, password, rol) VALUES (:new_name, :new_lastname, :new_email, :new_password, :new_rol)";
        
        $stmt = $pdo->prepare($sql);
            
        $stmt->execute([':new_name' => $new_name, ':new_lastname' => $new_lastname, ':new_email' => $new_email, ':new_rol' => $new_rol,':new_password' => $password_hash]);

        $_SESSION['success_message'] = "Usuario creado correctamente.";
        header("Location: users.php");
        exit();
    } 
    catch (PDOException $e) {
        $error = "Error al crear: " . $e->getMessage();
    }
}
include '../../includes/header.php';
?>
<main>
    <h1>Crear Usuario</h1>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="create_user.php">
        <label>Nombre</label>
        <input type="text" name="name" placeholder="Nombre..." required>
        
        <label>Apellidos:</label>
        <input type="text" name="lastName" placeholder="Apellido..." required>
        
        <label>Correo Electrónico:</label>
        <input type="email" name="email" placeholder="Correo..." required>
        
        <label>Contraseña:</label>
        <input type="text" name="new_password" placeholder="Contraseña" required>

        <label>Rol:</label>
        <select name="rol" required>
            <option value="student" selected>Alumno</option>
            <option value="teacher">Profesor</option>
            <option value="admin">Administrador</option>
        </select>

        <button type="submit" name="crear">Crear Usuario</button>
        <a href="users.php">Cancelar</a>
    </form>
</main>

<?php include '../../includes/footer.php'; ?>