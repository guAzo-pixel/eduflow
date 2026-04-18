<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_teacher.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/verify_teacher_class.php';

try {
    /*Logica de la funcion para buscar usuario */
    /* "$_GET" viaja en la url, es decir se ecribe ahi "/users.php?search=" */
    if (isset($_GET['search']) && !empty($_GET['search'])){
        $search = $_GET['search'];
        
        $sql = "SELECT * FROM Users WHERE rol = 'student' AND (name LIKE :search OR email LIKE :search OR id_user LIKE :search) ORDER BY time DESC";
        
        $stmt = $pdo->prepare($sql);

        /* Añadimos "%" en la petición para que busque coencidencias */
        $stmt->execute([':search' => '%' . $search . '%']); 
    }
    else {
         /* Si no se busca nada trae los 10 usuarios añadidos mas recientemente */
        $sql = "SELECT * FROM Users WHERE rol = 'student' ORDER BY time";
        $stmt = $pdo->query($sql);
    }
    /* Rcogemos los datos de la respuesta */
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    $error = "Error al cargar los usuarios: " . $e->getMessage();
}

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';

?>

<main>
    <h1>Gestion de Matriculas</h1>

    <div class="management-menu">
        <form method="GET" action="registrations.php">
            <input type="text" name="search" placeholder="Buscar por nombre, email, id...">
            <button type="submit">Buscar</button>
            <?php 
                /*Funcion para borrar la busqueda */
                if (!empty($_GET['search'])){
                    echo "<a href='registrations.php'><button type='button'>Borrar busqueda</button></a>";
                }
            ?>
        </form>
    </div>
    <?php 
    /* Mensaje de error o exito en caso de borrar un usuario */
    if(isset($_SESSION['success_message'])) {
        echo "<div class='success'>" . $_SESSION['success_message'] . "</div>";
        unset($_SESSION['success_message']);
    }
    if(isset($_SESSION['error_message'])) {
        echo "<div class='error'>" . $_SESSION['error_message'] . "</div>";
        unset($_SESSION['error_message']);
    }
    ?>
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
            <?php if (isset($students) && count($students) > 0): ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo $student['id_user']; ?></td>
                        <td><?php echo htmlspecialchars($student['name'] . ' ' . $student['lastName']); ?></td>
                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                        <td><?php echo htmlspecialchars($student['rol']); ?></td>
                        <td><?php echo $student['time']; ?></td>
                        <td>
                            <a href="student_registrations.php?id=<?php echo $student['id_user']; ?>">
                                <button>Añadir</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">No se encontraron students.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    
</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>