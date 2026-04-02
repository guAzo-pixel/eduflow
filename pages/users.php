<?php
session_start();
/* Si el usuario no esta logeado o no es admin le echamos */
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "admin"){
    header("Location: ../index.php");
    exit();
}
include '../includes/header.php';
include '../includes/db.php'; 

try {
    /*Logica de la funcion para buscar usuario */
    /* "$_GET" viaja en la url, es decir se ecribe ahi "/users.php?search=" */
    if (isset($_GET['search']) && !empty($_GET['search'])){
        $search = $_GET['search'];
        
        $sql = "SELECT * FROM Users WHERE name LIKE :search OR email LIKE :search OR id_user LIKE :search ORDER BY time DESC";
        
        $stmt = $pdo->prepare($sql);

        /* Añadimos "%" en la petición para que busque coencidencias */
        $stmt->execute([':search' => '%' . $search . '%']); 
    }
    else {
         /* Si no se busca nada trae los 10 usuarios añadidos mas recientemente */
        $sql = "SELECT * FROM Users ORDER BY time DESC LIMIT 10";
        $stmt = $pdo->query($sql);
    }
    /* Rcogemos los datos de la respuesta */
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    $error = "Error al cargar los usuarios: " . $e->getMessage();
}
?>
<main>
    <h1>Gestion de Usuarios</h1>

    <div class="management-menu">
        <form method="GET" action="users.php">
            <input type="text" name="search" placeholder="Buscar por nombre, email, id...">
            <button type="submit">Buscar</button>
        </form>
        <?php 
            /*Funcion para borrar la busqueda */
            if (!empty($_GET['search'])){
                echo "<a href='users.php'><button type='button'>Borrar busqueda</button></a>";
            }
        ?>
        <button>+ Añadir Usuario</button>
    </div>
    <table border="1" width="100%" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Fecha de Alta</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($users) && count($users) > 0): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id_user']; ?></td>
                        <td><?php echo htmlspecialchars($user['name'] . ' ' . $user['lastName']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['rol']); ?></td>
                        <td><?php echo $user['time']; ?></td>
                        <td>
                            <button>Modificar</button>
                            <a href="dell_user.php?id=<?php echo $user['id_user']; ?>">
                                <button style="color: red;">Eliminar</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">No se encontraron usuarios.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    
</main>
<?php include '../includes/footer.php'; ?>