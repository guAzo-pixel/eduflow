<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_student.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';

if (!isset($_GET['id_task'])) {
    header("Location: my_tasks.php");
    exit();
}

$id_task = $_GET['id_task'];
$id_student = $_SESSION['user_id'];

try {
    $sql_task = "SELECT Task.*, Class.material FROM Task 
                 INNER JOIN Topic ON Task.id_topic = Topic.id_topic 
                 INNER JOIN Class ON Topic.id_class = Class.id_class
                 WHERE Task.id_task = :id_task";
    $stmt_task = $pdo->prepare($sql_task);
    $stmt_task->execute([':id_task' => $id_task]);
    $task = $stmt_task->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        header("Location: my_tasks.php");
        exit();
    }

    $sql_answer = "SELECT * FROM Answer WHERE id_task = :id_task AND id_student = :id_student";
    $stmt_answer = $pdo->prepare($sql_answer);
    $stmt_answer->execute([':id_task' => $id_task, ':id_student' => $id_student]);
    $answer = $stmt_answer->fetch(PDO::FETCH_ASSOC);

    if (isset($_POST['entregar']) && !$answer) {
        $sql_insert = "INSERT INTO Answer (id_student, id_task) VALUES (:id_student, :id_task)";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([':id_student' => $id_student, ':id_task' => $id_task]);
        $_SESSION['success_message'] = "Tarea entregada correctamente.";
        header("Location: submit_task.php?id_task=" . $id_task);
        exit();
    }
    
    if (isset($_POST['anular']) && $answer && $answer['note'] === null) {
        $sql_delete = "DELETE FROM Answer WHERE id_answer = :id_answer";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->execute([':id_answer' => $answer['id_answer']]);
        $_SESSION['success_message'] = "Entrega anulada.";
        header("Location: submit_task.php?id_task=" . $id_task);
        exit();
    }
} catch (PDOException $e) {
    $error = $e->getMessage();
}

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<main>
    <h1><?php echo htmlspecialchars($task['name']); ?></h1>
    <h2>Asignatura: <?php echo htmlspecialchars($task['material']); ?></h2>
    <p><?php echo nl2br(htmlspecialchars($task['subtitle'] ?? '')); ?></p>
    <p><strong>Fecha límite:</strong> <?php echo $task['timeMax'] ? $task['timeMax'] : 'Sin límite'; ?></p>

    <?php if(isset($_SESSION['success_message'])) { echo "<div class='success'>" . $_SESSION['success_message'] . "</div>"; unset($_SESSION['success_message']); } ?>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <div class="answer-details">
        <h3>Tu Entrega</h3>
        <?php if ($answer): ?>
            <p>Estado: Entregado el <?php echo $answer['time']; ?></p>
            <p>Nota: <?php echo $answer['note'] !== null ? $answer['note'] : 'Pendiente de corrección'; ?></p>
            <?php if ($answer['note'] === null): ?>
                <form method="POST">
                    <button type="submit" name="anular">Anular Entrega</button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <form method="POST"><button type="submit" name="entregar">Entregar Tarea</button></form>
        <?php endif; ?>
    </div>
    <a href="my_tasks.php"><button>Volver a Mis Trabajos</button></a>
</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>