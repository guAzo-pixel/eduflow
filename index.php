<?php 
include 'includes/header.php';
include 'includes/db.php'; 
?>

<main>
    <h1>Bienvenido a Eduflow</h1>
    <h2>Usuarios registrados</h2>
    <ul>
        <?php
        if ($rol === "admin"){
        /*Hacemos la consulta */
        $sql = "SELECT * FROM Users";

        /*Enviamos la consulta al embajador (PDO) query es la petición*/
        $smnt = $pdo->query($sql);

        /* Procesamos los datos recibidos*/
        $users = $smnt->fetchAll(PDO::FETCH_ASSOC);

        /* Recorremos los datos obtenidos de la petición y los imprimimos*/
        foreach($users as $user){
            $name = $user['name'];
            $lastname = $user['lastName'];
            $rol = $user['rol'];
            echo "<li>$name $lastname - Rol: $rol</li>";
        }
        }
        else{
            echo "<p>No tienes acceso</p>";
        }
        ?>
    </ul>
</main>

<?php include 'includes/footer.php'; ?>