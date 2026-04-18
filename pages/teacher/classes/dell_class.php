<?php
include '../../../includes/auth_teacher.php';
include '../../../includes/db.php'; 


/*Comprobación si el id esta vacio*/
if (isset($_GET['id'])){
$id = $_GET['id'];

/* Encapsulamos la logica de borrado para prevenir errores*/
    try {
        $sql = "DELETE FROM Class WHERE id_class = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([':id' => $id]);

        $_SESSION['success_message'] = "Clase eliminada satisfactoriamente";
    }
    catch (PDOException $e) {
            $_SESSION['error_message'] = "No se puede eliminar el Clase. Es posible que tenga datos asociados.";}
}
header("Location: classes.php");
            
exit();
?>
