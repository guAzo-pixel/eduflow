<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_student.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';

if (!isset($_GET['id_class'])) {
    header("Location: my_classes.php");
    exit();
}

$id_class = $_GET['id_class'];
$id_student = $_SESSION['user_id'];

try {
    $sql_check = "SELECT * FROM Registrations WHERE id_class = :id_class AND id_student = :id_student";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':id_class' => $id_class, ':id_student' => $id_student]);
    
    if (!$stmt_check->fetch()) {
        header("Location: my_classes.php");
        exit();
    }

    $sql_class = "SELECT * FROM Class WHERE id_class = :id_class";
    $stmt_class = $pdo->prepare($sql_class);
    $stmt_class->execute([':id_class' => $id_class]);
    $class_info = $stmt_class->fetch(PDO::FETCH_ASSOC);

    $sql_topics = "SELECT * FROM Topic WHERE id_class = :id_class ORDER BY number ASC";
    $stmt_topics = $pdo->prepare($sql_topics);
    $stmt_topics->execute([':id_class' => $id_class]);
    $topics = $stmt_topics->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = $e->getMessage();
}

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<main>
    <h1><?php echo htmlspecialchars($class_info['material'] . ' - ' . $class_info['course']); ?></h1>
    <a href="my_classes.php"><button>Volver a Mis Clases</button></a>
    
    <section class="topics-list">
        <?php if (!empty($topics)): ?>
            <?php foreach ($topics as $topic): ?>
                <div class="topic-card">
                    <h2>UT<?php echo $topic['number']; ?> - <?php echo htmlspecialchars($topic['title']); ?></h2>
                    <p><?php echo htmlspecialchars($topic['subtitle'] ?? ''); ?></p>
                    
                    <?php
                    $sql_tasks = "SELECT * FROM Task WHERE id_topic = :id_topic";
                    $stmt_tasks = $pdo->prepare($sql_tasks);
                    $stmt_tasks->execute([':id_topic' => $topic['id_topic']]);
                    $tasks = $stmt_tasks->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    
                    <?php if (!empty($tasks)): ?>
                        <ul>
                            <?php foreach ($tasks as $task): ?>
                                <li>
                                    <?php echo htmlspecialchars($task['name']); ?> 
                                    <a href="/pages/student/tasks/submit_task.php?id_task=<?php echo $task['id_task']; ?>">
                                        <button>Ver Tarea</button>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay temas en esta clase.</p>
        <?php endif; ?>
    </section>
</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>