<?php
session_start();
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "teacher"){
    header("Location: ../../../../../index.php");
    exit();
}
include '../../../../../includes/db.php'; 

// 1. Recogemos los tres IDs necesarios
$id_class = $_GET['id_class'] ?? null;
$id_topic = $_GET['id_topic'] ?? null;
$id_task = $_GET['id'] ?? null; // Este es el ID de la tarea

if ($id_task && $id_class && $id_topic){
    $id_teacher = $_SESSION['user_id'];

    try {
        // 2. Verificamos que la TAREA pertenezca a este profesor
        $sql_check = "SELECT TK.id_task FROM Task TK 
                      INNER JOIN Topic T ON TK.id_topic = T.id_topic 
                      INNER JOIN Class CL ON T.id_class = CL.id_class 
                      WHERE TK.id_task = :id_tk AND CL.id_teacher = :id_t";
        
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([':id_tk' => $id_task, ':id_t' => $id_teacher]);

        if ($stmt_check->fetch()) {
            // 3. Si es suya, borramos
            $sql_del = "DELETE FROM Task WHERE id_task = :id_tk";
            $stmt_del = $pdo->prepare($sql_del);
            $stmt_del->execute([':id_tk' => $id_task]);
            $_SESSION['success_message'] = "Tarea eliminada correctamente.";
        }
    } catch (PDOException $e) {
        // Nota: Si hay alumnos que ya entregaron la tarea, fallará por integridad referencial
        $_SESSION['error_message'] = "No se puede borrar: hay alumnos que ya han entregado esta tarea.";
    }
}

// 4. Redirección de vuelta
if (!$id_class || !$id_topic) {
    header("Location: ../../classes.php");
} else {
    header("Location: ../topic_details.php?id_class=$id_class&id_topic=$id_topic");
}
exit();