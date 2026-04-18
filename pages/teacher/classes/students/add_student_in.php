<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_teacher.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/verify_teacher_class.php';

try {
    if (isset($_GET['search']) && !empty($_GET['search'])){
        $search = $_GET['search'];
        
        $sql = "SELECT * FROM Users 
                WHERE rol = 'student' 
                AND id_user NOT IN (SELECT id_student FROM Registrations WHERE id_class = :id_class)
                AND (name LIKE :search OR email LIKE :search OR id_user LIKE :search) 
                ORDER BY time DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_class' => $id_class, ':search' => '%' . $search . '%']); 
    }
    else {
        $sql = "SELECT * FROM Users 
                WHERE rol = 'student' 
                AND id_user NOT IN (SELECT id_student FROM Registrations WHERE id_class = :id_class)
                ORDER BY time DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_class' => $id_class]);
    }
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    $error = "Error al cargar los usuarios: " . $e->getMessage();
}

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';

?>

<main>
    <h1>Gestion de Matriculas de <?php echo htmlspecialchars($classes['material'] . ' ' . $classes['course']);?></h1>

    <div class="management-menu">
        <form method="GET" action="add_student_in.php">
            <input type="hidden" name="id_class" value="<?php echo htmlspecialchars($id_class); ?>">
            <input type="text" name="search" placeholder="Buscar por nombre, email, id...">
            <button type="submit">Buscar</button>
            <?php 
                if (!empty($_GET['search'])){
                    echo "<a href='add_student_in.php?id_class=" . htmlspecialchars($id_class) . "'><button type='button'>Borrar busqueda</button></a>";
                }
            ?>
        </form>
        <a href="students_in.php?id_class=<?php echo htmlspecialchars($id_class); ?>">
            <button>Volver</button>
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
                            <a href="student_registrations.php?id=<?php echo $student['id_user']; ?>&id_class=<?php echo htmlspecialchars($id_class); ?>">
                                <button>Añadir</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">No se encontraron alumnos disponibles para añadir.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>