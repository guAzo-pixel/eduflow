<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_teacher.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/verify_teacher_class.php';

if (!isset($_GET['id_topic'])) {
    header("Location: ../classes.php");
    exit();
}

$id_topic = $_GET['id_topic'];

try {
    $sql_topic = "SELECT * FROM Topic WHERE id_topic = :id_topic AND id_class = :id_class";
    $stmt_topic = $pdo->prepare($sql_topic);
    $stmt_topic->execute([':id_topic' => $id_topic, ':id_class' => $id_class]);
    $topic = $stmt_topic->fetch(PDO::FETCH_ASSOC);

    $sql_contents = "SELECT * FROM Content WHERE id_topic = :id_topic ORDER BY time DESC";
    $stmt_contents = $pdo->prepare($sql_contents);
    $stmt_contents->execute([':id_topic' => $id_topic]);
    $contents = $stmt_contents->fetchAll(PDO::FETCH_ASSOC);

    $sql_tasks = "SELECT * FROM Task WHERE id_topic = :id_topic ORDER BY time DESC";
    $stmt_tasks = $pdo->prepare($sql_tasks);
    $stmt_tasks->execute([':id_topic' => $id_topic]);
    $tasks = $stmt_tasks->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Error al cargar los detalles: " . $e->getMessage();
}

include '../../../../includes/header.php';
?>

<main>
    <div class="header-section">
        <h1>UT<?php echo $topic['number']; ?> - <?php echo htmlspecialchars($topic['title']); ?></h1>
        <p>Asignatura: <?php echo htmlspecialchars($classes['material']); ?></p>
        
        <div class="management-menu">
            <a href="content/create_content.php?id_topic=<?php echo $id_topic; ?>&id_class=<?php echo $id_class; ?>">
                <button>+ Nuevo Contenido</button>
            </a>
            <a href="tasks/create_task.php?id_topic=<?php echo $id_topic; ?>&id_class=<?php echo $id_class; ?>">
                <button>+ Nueva Tarea</button>
            </a>
            <a href="../class_dashboard.php?id_class=<?php echo $id_class; ?>">
                <button>Volver al Temario</button>
            </a>
        </div>
    </div>

    <hr>

    <section>
        <h2>Materiales de estudio</h2>
        <div class="topics-list">
            <?php if (count($contents) > 0): ?>
                <?php foreach ($contents as $content): ?>
                    <div class="topic-card">
                        <div class="topic-content">
                            <h3><?php echo htmlspecialchars($content['name']); ?></h3>
                            <p><?php echo htmlspecialchars($content['subtitle']); ?></p>
                            <?php if($content['archive']): ?>
                                <small>Archivo: <?php echo htmlspecialchars($content['archive']); ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="card-actions">
                            <a href="content/modify_content.php?id=<?php echo $content['id_content']; ?>&id_class=<?php echo $id_class; ?>&id_topic=<?php echo $id_topic; ?>"><button>Editar</button></a>
                            <a href="content/delete_content.php?id=<?php echo $content['id_content']; ?>&id_class=<?php echo $id_class; ?>&id_topic=<?php echo $id_topic; ?>"><button style="color:red;">Borrar</button></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay materiales en este tema.</p>
            <?php endif; ?>
        </div>
    </section>

    <hr>

    <section>
        <h2>Tareas y Actividades</h2>
        <div class="topics-list">
            <?php if (count($tasks) > 0): ?>
                <?php foreach ($tasks as $task): ?>
                    <div class="topic-card" style="border-left: 5px solid orange;">
                        <div class="topic-content">
                            <h3><?php echo htmlspecialchars($task['name']); ?></h3>
                            <p>Fecha límite: <?php echo $task['timeMax'] ? $task['timeMax'] : 'Sin límite'; ?></p>
                        </div>
                        <div class="card-actions">
                            <a href="tasks/view_answers.php?id_task=<?php echo $task['id_task']; ?>"><button>Ver Entregas</button></a>
                            <a href="tasks/delete_task.php?id=<?php echo $task['id_task']; ?>&id_class=<?php echo $id_class; ?>&id_topic=<?php echo $id_topic; ?>"><button style="color:red;">Borrar</button></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay tareas asignadas.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include '../../../../includes/footer.php'; ?>