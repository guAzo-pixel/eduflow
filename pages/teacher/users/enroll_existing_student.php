<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_teacher.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';

if (!isset($_GET['id_user'])) {
    header("Location: my_students.php");
    exit();
}

$id_student = $_GET['id_user'];
$id_teacher = $_SESSION['user_id'];

try {
    // Obtener detalles del estudiante
    $sql_student = "SELECT * FROM Users WHERE id_user = :id_student";
    $stmt_student = $pdo->prepare($sql_student);
    $stmt_student->execute([':id_student' => $id_student]);
    $student = $stmt_student->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        header("Location: my_students.php");
        exit();
    }

    // Obtener TODAS las clases del profesor y si el alumno está matriculado
    $sql_classes = "SELECT Class.*, Registrations.id_registrations 
                    FROM Class 
                    LEFT JOIN Registrations ON Class.id_class = Registrations.id_class AND Registrations.id_student = :id_student
                    WHERE Class.id_teacher = :id_teacher";
    $stmt_classes = $pdo->prepare($sql_classes);
    $stmt_classes->execute([':id_teacher' => $id_teacher, ':id_student' => $id_student]);
    $classes = $stmt_classes->fetchAll(PDO::FETCH_ASSOC);

    // Lógica para matricular
    if (isset($_POST['matricular'])) {
        $id_class_to_enroll = $_POST['id_class'];
        
        $sql_check = "SELECT id_class FROM Class WHERE id_class = :id_class AND id_teacher = :id_teacher";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([':id_class' => $id_class_to_enroll, ':id_teacher' => $id_teacher]);
        
        if ($stmt_check->fetch()) {
            $sql_insert = "INSERT INTO Registrations (id_student, id_class) VALUES (:id_student, :id_class)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([':id_student' => $id_student, ':id_class' => $id_class_to_enroll]);
            
            $_SESSION['success_message'] = "Alumno matriculado con éxito.";
        } else {
            $_SESSION['error_message'] = "Clase no válida.";
        }
        header("Location: enroll_existing_student.php?id_user=" . $id_student);
        exit();
    }

    // Lógica para desmatricular
    if (isset($_POST['eliminar'])) {
        $id_reg_to_delete = $_POST['id_registrations'];
        
        $sql_check = "SELECT Registrations.id_registrations 
                      FROM Registrations 
                      INNER JOIN Class ON Registrations.id_class = Class.id_class 
                      WHERE Registrations.id_registrations = :id_reg 
                      AND Class.id_teacher = :id_teacher";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([':id_reg' => $id_reg_to_delete, ':id_teacher' => $id_teacher]);
        
        if ($stmt_check->fetch()) {
            $sql_del = "DELETE FROM Registrations WHERE id_registrations = :id_reg";
            $stmt_del = $pdo->prepare($sql_del);
            $stmt_del->execute([':id_reg' => $id_reg_to_delete]);
            
            $_SESSION['success_message'] = "Alumno eliminado de la clase con éxito.";
        } else {
            $_SESSION['error_message'] = "Matrícula no válida.";
        }
        header("Location: enroll_existing_student.php?id_user=" . $id_student);
        exit();
    }

} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<main>
    <h1>Gestionar clases de <?php echo htmlspecialchars($student['name'] . ' ' . $student['lastName']); ?></h1>
    
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
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

    <div class="management-menu">
        <a href="my_students.php"><button>Volver</button></a>
    </div>

    <table border="1" width="100%" cellpadding="10">
        <thead>
            <tr>
                <th>Materia</th>
                <th>Curso</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($classes) > 0): ?>
                <?php foreach ($classes as $c): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($c['material']); ?></td>
                        <td><?php echo htmlspecialchars($c['course']); ?></td>
                        <td>
                            <?php if ($c['id_registrations']): ?>
                                <span style="color: green; font-weight: bold;">Matriculado</span>
                            <?php else: ?>
                                <span style="color: gray;">No matriculado</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($c['id_registrations']): ?>
                                <form method="POST">
                                    <input type="hidden" name="id_registrations" value="<?php echo $c['id_registrations']; ?>">
                                    <button type="submit" name="eliminar" style="color: red;">Eliminar de la clase</button>
                                </form>
                            <?php else: ?>
                                <form method="POST">
                                    <input type="hidden" name="id_class" value="<?php echo $c['id_class']; ?>">
                                    <button type="submit" name="matricular">Matricular</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">No tienes clases creadas.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>