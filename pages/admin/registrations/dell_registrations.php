<?php
session_start();
/* Si el usuario no esta logeado o no es admin le echamos */
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "admin"){
    header("Location: ../../../index.php");
    exit();
}
include '../../../includes/db.php'; 

/*Comprobación si el id esta vacio, y si esta el del estudiante*/
if (isset($_GET['id_reg']) && isset($_GET['id_student'])){
    $id_reg = $_GET['id_reg'];
    $id_student = $_GET['id_student'];

/* Encapsulamos la logica de borrado para prevenir errores*/
    try {
        $sql = "DELETE FROM Registrations WHERE id_registrations = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([':id' => $id_reg]);

        $_SESSION['success_message'] = "Matriculación eliminado satisfactoriamente";
    }
    catch (PDOException $e) {
            // Si sale mal (ej: borrar a Laura), guardamos el error temporal
            $_SESSION['error_message'] = "No se puede eliminar la matriculación";
    }
    header("Location: student_registrations.php?id=$id_student");
    exit();
}
else {
    // Si alguien entra a la página sin IDs en la URL, lo mandamos a la lista general
    header("Location: registrations.php");
    exit();
}
?>
