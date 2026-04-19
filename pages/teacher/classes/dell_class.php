<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_teacher.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/verify_teacher_class.php';

/* Encapsulamos la logica de borrado para prevenir errores*/
// verify_teacher_class.php ya nos da $id_class y $id_teacher y confirma que el profesor es el dueño.
try {
    // Añadimos id_teacher a la consulta para una capa extra de seguridad.
    $sql = "DELETE FROM Class WHERE id_class = :id_class AND id_teacher = :id_teacher";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_class' => $id_class, ':id_teacher' => $id_teacher]);
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['success_message'] = "Clase eliminada satisfactoriamente.";
    } else {
        $_SESSION['error_message'] = "No se pudo eliminar la clase.";
    }
}
catch (PDOException $e) {
    $_SESSION['error_message'] = "No se puede eliminar la clase. Asegúrate de que no tenga temas o alumnos matriculados.";
}
header("Location: classes.php");
exit();
?>
