<?php
include '../includes/header.php';
include '../includes/db.php'; 
 
if ($_SESSION['user_rol'] !== "admin"){
    header("Location: index.php");
    exit();
}

try {
    /* Trae los 10 usuarios añadidos mas recientemente */
    $sql = "SELECT * FROM Users ORDER BY time DESC LIMIT 10";

    /*Enviamos la consulta al embajador (PDO) query es la petición*/
    $stmt = $pdo->query($sql);

    /* Procesamos los datos recibidos*/
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
                            <button style="color: red;">Eliminar</button>
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