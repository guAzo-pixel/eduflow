<?php
session_start();
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "admin"){
    header("Location: ../index.php");
    exit();
}
include '../includes/db.php';

/*Comprobación si el id esta vacio*/
if (isset($_GET['id'])){
$id = $_GET['id'];

/* Encapsulamos la logica de borrado para prevenir errores*/
    try {
        $sql = "DELETE FROM Users WHERE id_user = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([':id' => $id]);

        $_SESSION['success_message'] = "Usuario eliminado satisfactoriamente";
    }
    catch (PDOException $e) {
            // Si sale mal (ej: borrar a Laura), guardamos el error temporal
            $_SESSION['error_message'] = "No se puede eliminar el usuario. Es posible que tenga datos asociados.";}
}
header("Location: users.php");
            
exit();
?>