<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_teacher.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/verify_teacher_class.php';

try {
    /*Logica de la funcion para buscar usuario */
    /* "$_GET" viaja en la url, es decir se ecribe ahi "/classs.php?search=" */
    if (isset($_GET['search']) && !empty($_GET['search'])){
        $search = $_GET['search'];
        
        /*A diferenccia de en la pagina users.php aqui necesitamos hacer una una consulta relacional con JOIN*/
        $sql = "SELECT Users.*, Registrations.id_registrations, Registrations.time as fecha_alta 
                FROM Registrations 
                INNER JOIN Users ON Registrations.id_student = Users.id_user 
                WHERE Registrations.id_class = :id_class 
                AND (Users.name LIKE :search OR Users.email LIKE :search)";
        
        $stmt = $pdo->prepare($sql);

        /* Añadimos "%" en la petición para que busque coencidencias */
        $stmt->execute([':id_class' => $id_class, ':search' => '%' . $search . '%']);
    }
    else {
        $id_class = $_GET['id_class'];
         /* Si no se busca nada trae los usuarios añadidos mas recientemente */
        $sql = "SELECT Users.*, Registrations.id_registrations, Registrations.time as fecha_alta 
                FROM Registrations 
                INNER JOIN Users ON Registrations.id_student = Users.id_user 
                WHERE Registrations.id_class = :id_class";

        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([':id_class' => $id_class]);
    
        
    }
    
    /* Recogemos los datos de la respuesta */
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    $error = "Error al cargar las matriculas: " . $e->getMessage();
}

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'; 
?>

<main>
    <h1>Gestion de Matriculas de <?php echo htmlspecialchars($classes['material']); ?>. Curso: <?php echo htmlspecialchars($classes['course']); ?></h1>

    <div class="management-menu">
        <form method="GET" action="students_in.php">
            <input type="hidden" name="id_class" value="<?php echo htmlspecialchars($id_class); ?>">
            <input type="text" name="search" placeholder="Buscar por curso, material, id...">
            <button type="submit">Buscar</button>
            <?php 
                /*Funcion para borrar la busqueda */
                if (!empty($_GET['search'])){
                    echo "<a href='students_in.php?id_class=" . htmlspecialchars($id_class) . "'><button type='button'>Borrar busqueda</button></a>";
                }
            ?>
        </form>
        <a href="add_student_in.php?id_class=<?php echo $id_class; ?>">
            <button>+ Matricular Alumno</button>
        </a>
    </div>
    <?php 
    /* Mensaje de error o exito en caso de borrar un clase */
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
                <th>Email</th>
                <th>Nombre</th>
                <th>Fecha de Alta</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($students) && count($students) > 0): ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo $student['id_user']; ?></td>
                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                        <td><?php echo htmlspecialchars($student['name'] . ' ' . $student['lastName']); ?></td>
                        <td><?php echo $student['time']; ?></td>
                        <td>
                            <a href="dell_class.php?id=<?php echo $student['id_registrations']; ?>">
                                <button style="color: red;">Eliminar</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">No se encontraron clases.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    
</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>