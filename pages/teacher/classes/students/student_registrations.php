<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_teacher.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/verify_teacher_class.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "INSERT INTO Registrations (id_student, id_class) VALUES (:id, :id_class)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id, ':id_class' => $id_class]);

        $sql_student = "SELECT name, lastName FROM Users WHERE id_user = :id";
        $stmt_student = $pdo->prepare($sql_student);
        $stmt_student->execute([':id' => $id]);
        $student = $stmt_student->fetch(PDO::FETCH_ASSOC);

        $student_name = $student ? $student['name'] . ' ' . $student['lastName'] : 'Desconocido';
        $class_name = $classes['material'] . ' ' . $classes['course']; // La variable $classes ya viene verificada

        $_SESSION['success_message'] = "Alumno " . htmlspecialchars($student_name) . " añadido a la clase " . htmlspecialchars($class_name) . ".";
        header("Location: add_student_in.php?id_class=$id_class");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error al añadir alumno: " . $e->getMessage();
    }
}
header("Location: add_student_in.php?id_class=$id_class");
exit();