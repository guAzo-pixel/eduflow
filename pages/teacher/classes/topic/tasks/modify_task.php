<?php
session_start();
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "teacher"){
    header("Location: ../../../../../index.php");
    exit();
}
include '../../../../../includes/db.php'; 

$id_task = $_GET['id'];
$id_class = $_GET['id_class'];
$id_teacher = $_SESSION['user_id'];

try {
    $sql_topic = "SELECT id_topic FROM Task WHERE id_task = :id_tk";
    $stmt_id = $pdo->prepare($sql_topic);
    $stmt_id->execute([':id_tk' => $id_task]);
    $res_id = $stmt_id->fetch();
    $id_topic = $res_id['id_topic'];

    $sql = "SELECT TK.* FROM Task TK 
            INNER JOIN Topic T ON TK.id_topic = T.id_topic 
            INNER JOIN Class CL ON T.id_class = CL.id_class 
            WHERE TK.id_task = :id_tk AND CL.id_teacher = :id_t";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_tk' => $id_task, ':id_t' => $id_teacher]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        header("Location: ../../classes.php");
        exit();
    }

    if (isset($_POST['modificar'])) {
        $name = trim($_POST['name']);
        $subtitle = trim($_POST['subtitle']);
        $timeMax = !empty($_POST['timeMax']) ? $_POST['timeMax'] : null;
        
        $sql_upd = "UPDATE Task SET name = :nom, subtitle = :sub, timeMax = :tmax WHERE id_task = :id_tk";
        $stmt_upd = $pdo->prepare($sql_upd);
        $stmt_upd->execute([':nom' => $name, ':sub' => $subtitle, ':tmax' => $timeMax, ':id_tk' => $id_task]);
        
        $_SESSION['success_message'] = "Tarea actualizada.";
        header("Location: ../topic_details.php?id_class=$id_class&id_topic=$id_topic");
        exit();
    }
} catch (PDOException $e) { $error = $e->getMessage(); }

include '../../../../../includes/header.php';
?>
<main>
    <h1>Modificar Tarea</h1>
    <form method="POST" action="modify_task.php?id=<?php echo $id_task; ?>&id_class=<?php echo $id_class; ?>">
        <label>Nombre:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($task['name']); ?>" required>
        <label>Instrucciones:</label>
        <textarea name="subtitle"><?php echo htmlspecialchars($task['subtitle']); ?></textarea>
        <label>Fecha Límite:</label>
        <input type="datetime-local" name="timeMax" value="<?php echo $task['timeMax'] ? date('Y-m-d\TH:i', strtotime($task['timeMax'])) : ''; ?>">
        <button type="submit" name="modificar">Actualizar</button>
        <a href="../topic_details.php?id_class=<?php echo $id_class; ?>&id_topic=<?php echo $id_topic; ?>">Cancelar</a>
    </form>
</main>
<?php include '../../../../../includes/footer.php'; ?>