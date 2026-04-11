<?php
session_start();
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "teacher"){
    header("Location: ../../../../../index.php");
    exit();
}
include '../../../../../includes/db.php'; 

$id_class = $_GET['id_class'] ?? null;
$id_topic = $_GET['id_topic'] ?? null;
$id_content = $_GET['id'] ?? null;

if ($id_content && $id_class && $id_topic){
    $id_teacher = $_SESSION['user_id'];

    try {
        $sql_check = "SELECT C.id_content FROM Content C 
                      INNER JOIN Topic T ON C.id_topic = T.id_topic 
                      INNER JOIN Class CL ON T.id_class = CL.id_class 
                      WHERE C.id_content = :id_c AND CL.id_teacher = :id_t";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([':id_c' => $id_content, ':id_t' => $id_teacher]);

        if ($stmt_check->fetch()) {
            $sql_del = "DELETE FROM Content WHERE id_content = :id_c";
            $stmt_del = $pdo->prepare($sql_del);
            $stmt_del->execute([':id_c' => $id_content]);
            $_SESSION['success_message'] = "Material eliminado.";
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error al eliminar.";
    }
}

if (!$id_class || !$id_topic) {
    header("Location: ../../classes.php");
} else {
    header("Location: ../topic_details.php?id_class=$id_class&id_topic=$id_topic");
}
exit();