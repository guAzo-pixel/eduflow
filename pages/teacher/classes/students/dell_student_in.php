<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_teacher.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/verify_teacher_class.php';

if (isset($_GET['id'])) {
    $id_reg = $_GET['id'];

    try {
        $sql = "DELETE FROM Registrations WHERE id_registrations = :id_reg AND id_class = :id_class";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_reg' => $id_reg, ':id_class' => $id_class]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['success_message'] = "Alumno desmatriculado de la clase correctamente.";
        } else {
            $_SESSION['error_message'] = "No se pudo desmatricular al alumno (es posible que no exista la matrícula).";
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error al eliminar la matrícula: " . $e->getMessage();
    }
}

header("Location: students_in.php?id_class=" . htmlspecialchars($id_class));
exit();
?>