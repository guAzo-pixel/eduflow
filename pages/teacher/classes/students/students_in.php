<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_teacher.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/verify_teacher_class.php';

try {
    /*Logica de la funcion para buscar usuario */
    if (isset($_GET['search']) && !empty($_GET['search'])){
        $search = $_GET['search'];
        
        $sql = "SELECT Users.*, Registrations.id_registrations, Registrations.time as fecha_alta 
                FROM Registrations 
                INNER JOIN Users ON Registrations.id_student = Users.id_user 
                WHERE Registrations.id_class = :id_class 
                AND (Users.name LIKE :search OR Users.email LIKE :search)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_class' => $id_class, ':search' => '%' . $search . '%']);
    }
    else {
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
    <h1>Gestion de Matriculas de <?php echo htmlspecialchars($classes['material'] . ' ' . $classes['course']);?></h1>

    <div class="management-menu">
        <form method="GET" action="students_in.php">
            <input type="hidden" name="id_class" value="<?php echo htmlspecialchars($id_class); ?>">
            <input type="text" name="search" placeholder="Buscar por curso, material, id...">
            <button type="submit">Buscar</button>
            <?php 
                if (!empty($_GET['search'])){
                    echo "<a href='students_in.php?id_class=" . htmlspecialchars($id_class) . "'><button type='button'>Borrar busqueda</button></a>";
                }
            ?>
        </form>
        <a href="add_student_in.php?id_class=<?php echo htmlspecialchars($id_class); ?>">
            <button>+ Matricular Alumno</button>
        </a>
        <a href="../class_dashboard.php?id_class=<?php echo htmlspecialchars($id_class); ?>">
            <button>Volver a la Clase</button>
        </a>
    </div>
    <?php 
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
                            <a href="dell_student_in.php?id=<?php echo $student['id_registrations']; ?>&id_class=<?php echo htmlspecialchars($id_class); ?>">
                                <button style="color: red;">Eliminar</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No se encontraron alumnos matriculados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
