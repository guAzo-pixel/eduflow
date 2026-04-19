<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_student.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';

$id_student = $_SESSION['user_id'];

try {
    $sql = "SELECT 
                Task.id_task, Task.name AS task_name, Task.timeMax,
                Class.material, Class.id_class,
                Answer.id_answer, Answer.note
            FROM Task
            INNER JOIN Topic ON Task.id_topic = Topic.id_topic
            INNER JOIN Class ON Topic.id_class = Class.id_class
            INNER JOIN Registrations ON Class.id_class = Registrations.id_class
            LEFT JOIN Answer ON Task.id_task = Answer.id_task AND Answer.id_student = :id_student
            WHERE Registrations.id_student = :id_student
            ORDER BY Task.timeMax ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_student' => $id_student]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
}

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<main>
    <h1>Mis Trabajos</h1>
    <table border="1" width="100%" cellpadding="10">
        <thead>
            <tr>
                <th>Clase</th>
                <th>Tarea</th>
                <th>Fecha Límite</th>
                <th>Estado</th>
                <th>Nota</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tasks)): ?>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['material']); ?></td>
                        <td><?php echo htmlspecialchars($task['task_name']); ?></td>
                        <td><?php echo $task['timeMax'] ? $task['timeMax'] : 'Sin límite'; ?></td>
                        <td>
                            <?php if ($task['id_answer']): ?>
                                <span class="success">Entregado</span>
                            <?php else: ?>
                                <span class="error">Pendiente</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $task['note'] !== null ? $task['note'] : '---'; ?></td>
                        <td>
                            <a href="submit_task.php?id_task=<?php echo $task['id_task']; ?>"><button>Ver / Entregar</button></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">No tienes tareas asignadas.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>