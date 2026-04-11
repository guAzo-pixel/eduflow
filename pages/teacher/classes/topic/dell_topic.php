<?php
session_start();

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "teacher"){
    header("Location: ../../../../index.php");
    exit();
}

include '../../../../includes/db.php'; 

if (isset($_GET['id']) && isset($_GET['id_class'])){
    $id_topic = $_GET['id'];
    $id_class = $_GET['id_class'];
    $id_teacher = $_SESSION['user_id'];

    try {
        $sql_check = "SELECT T.id_topic 
                      FROM Topic T
                      INNER JOIN Class C ON T.id_class = C.id_class
                      WHERE T.id_topic = :id_topic 
                      AND C.id_teacher = :id_teacher";
        
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([':id_topic' => $id_topic, ':id_teacher' => $id_teacher]);

        if ($stmt_check->fetch()) {
            $sql_del = "DELETE FROM Topic WHERE id_topic = :id_topic";
            $stmt_del = $pdo->prepare($sql_del);
            $stmt_del->execute([':id_topic' => $id_topic]);

            $_SESSION['success_message'] = "Tema eliminado satisfactoriamente.";
        } else {
            $_SESSION['error_message'] = "No tienes permiso para eliminar este tema.";
        }

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "No se puede eliminar: asegúrate de borrar primero las tareas y contenidos de este tema.";
    }
}

header("Location: ../class_dashboard.php?id_class=$id_class");
exit();
?>
