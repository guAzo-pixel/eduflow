<?php
session_start();
include 'includes/db.php';


/* Si pulsan el boton entrar se cumple esta condicion */
/* "$_POST" es una variable global se puede consultar en cualquier pagina*/
if (isset($_POST['entrar'])){
    $email = $_POST['email'];
    $password_written = $_POST['password'];


    /* Se hace la consulta de manera segura para saber si el usuario existe (con "prepare" y = :email)*/
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = :email");
    /* Una vez mandada la estructura usamos "execute" para hacer la consulta en texto plano (Para evitar ataques de SQL injection)*/
    $stmt->execute([':email' => $email]);
    /* Si el usuario existe la variable user se rellena con sus datos, si no existe el valor es false*/
    $usuario = $stmt->fetch();

    /* Si existe el usuario */
    if ($usuario) {
        /* Si la contraseña coincide */
        if (password_verify($password_written, $usuario['password'])){
            /* "$_SESSION" es otra variable golobal 
            Rescatamos todos estos datos para mantener la sesión y datos para la pesonalización de opciones*/
            $_SESSION['user_id'] = $usuario['id_user'];
            $_SESSION['user_name'] = $usuario['name'];
            $_SESSION['user_rol'] = $usuario['rol'];

            /*Redirigimos a la página index*/
            header("Location: index.php");
            /*salimos de la concición*/
            exit();
        }
    }
}
?>
<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit" name="entrar">Entrar</button>
</form>


<?php 
/*si no se cumple la condicion inicial da error*/
if(isset($error)) echo "<p>$error</p>"; ?>