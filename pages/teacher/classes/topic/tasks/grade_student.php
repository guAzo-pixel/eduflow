<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_teacher.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';

if (!isset($_GET['id_answer'])) {
    header("Location: /pages/teacher/classes/classes.php");
    exit();
}

$id_answer = $_GET['id_answer'];

try {
    // Obtener los datos de la entrega, la tarea y el alumno
    $sql = "SELECT 
                Answer.*,
                Task.name AS task_name,
                Task.id_task,
                Topic.id_topic,
                Topic.id_class,
                Users.name AS student_name,
                Users.lastName AS student_lastName
            FROM Answer
            INNER JOIN Task ON Answer.id_task = Task.id_task
            INNER JOIN Topic ON Task.id_topic = Topic.id_topic
            INNER JOIN Users ON Answer.id_student = Users.id_user
            WHERE Answer.id_answer = :id_answer";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_answer' => $id_answer]);
    $answer_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$answer_data) {
        header("Location: /pages/teacher/classes/classes.php");
        exit();
    }

    if (isset($_POST['calificar'])) {
        $note = $_POST['note'] !== '' ? $_POST['note'] : null;

        // Actualizar la nota en la base de datos
        $sql_update = "UPDATE Answer SET note = :note WHERE id_answer = :id_answer";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            ':note' => $note,
            ':id_answer' => $id_answer
        ]);

        $_SESSION['success_message'] = "Calificación guardada correctamente.";
        header("Location: view_answers.php?id_task=" . $answer_data['id_task']);
        exit();
    }
} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<main>
    <h1>Calificar entrega de <?php echo htmlspecialchars($answer_data['student_name'] . ' ' . $answer_data['student_lastName']); ?></h1>
    <h2>Tarea: <?php echo htmlspecialchars($answer_data['task_name']); ?></h2>
    
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <div class="answer-details">
        <p><strong>Fecha de entrega:</strong> <?php echo $answer_data['time']; ?></p>
    </div>

    <form method="POST" action="grade_student.php?id_answer=<?php echo $id_answer; ?>">
        <label>Nota del alumno:</label>
        <input type="number" step="0.01" name="note" value="<?php echo htmlspecialchars($answer_data['note'] ?? ''); ?>" placeholder="Ej: 8.5" required>
        
        <button type="submit" name="calificar">Guardar Calificación</button>
        <a href="view_answers.php?id_task=<?php echo $answer_data['id_task']; ?>">Cancelar</a>
    </form>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
