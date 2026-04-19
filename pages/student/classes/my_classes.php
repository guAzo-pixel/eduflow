<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_student.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';

$id_student = $_SESSION['user_id'];

try {
    $sql = "SELECT Class.*, Users.name AS teacher_name, Users.lastName AS teacher_lastName 
            FROM Class 
            INNER JOIN Registrations ON Class.id_class = Registrations.id_class 
            INNER JOIN Users ON Class.id_teacher = Users.id_user 
            WHERE Registrations.id_student = :id_student";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_student' => $id_student]);
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
}

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<main>
    <h1>Mis Clases</h1>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    
    <table border="1" width="100%" cellpadding="10">
        <thead>
            <tr>
                <th>Materia</th>
                <th>Curso</th>
                <th>Profesor</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($classes)): ?>
                <?php foreach ($classes as $class): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($class['material']); ?></td>
                        <td><?php echo htmlspecialchars($class['course']); ?></td>
                        <td><?php echo htmlspecialchars($class['teacher_name'] . ' ' . $class['teacher_lastName']); ?></td>
                        <td>
                            <a href="class_dashboard.php?id_class=<?php echo $class['id_class']; ?>">
                                <button>Entrar a la Clase</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">No estás matriculado en ninguna clase.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>