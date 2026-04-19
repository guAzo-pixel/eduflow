<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_teacher.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';


try{
    if (isset($_GET['id_task'])){
        $id_task = $_GET['id_task']; 

        $sql = "SELECT 
                    Users.id_user,
                    Users.name, 
                    Users.lastName, 
                    Answer.id_answer,
                    Answer.note, 
                    Answer.time AS fecha_entrega
                FROM Task
                INNER JOIN Topic ON Task.id_topic = Topic.id_topic
                INNER JOIN Registrations ON Topic.id_class = Registrations.id_class
                INNER JOIN Users ON Registrations.id_student = Users.id_user
                LEFT JOIN Answer ON (Answer.id_student = Users.id_user AND Answer.id_task = Task.id_task)
                WHERE Task.id_task = :id_task
                ORDER BY Users.lastName ASC";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([':id_task' => $id_task]);
    
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $sql_info = "SELECT 
                        Task.name, 
                        Topic.id_topic, 
                        Topic.id_class 
                    FROM Task 
                    INNER JOIN Topic ON Task.id_topic = Topic.id_topic 
                    WHERE Task.id_task = :id_task";
        $stmt_info = $pdo->prepare($sql_info);
        $stmt_info->execute([':id_task' => $id_task]);
        $task_info = $stmt_info->fetch(PDO::FETCH_ASSOC);
    }
}
catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
}
include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>
<main>
    <h1>Entregas de: <?php echo htmlspecialchars($task_info['name']); ?></h1>
    <a href="../topic_details.php?id_topic=<?php echo $task_info['id_topic']; ?>&id_class=<?php echo $task_info['id_class']; ?>">
        <button type="button">Volver al Tema</button>
    </a>

    <table border="1" width="100%">
        <thead>
            <tr>
                <th>Alumno</th>
                <th>Estado</th>
                <th>Nota</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['lastName'] . ", " . $student['name']); ?></td>
                    
                    <td>
                        <?php if ($student['id_answer']): ?>
                            <span class="negative">Entregado</span>
                        <?php else: ?>
                            <span class="positive">No entregado</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php 
                            // Si note es NULL, mostramos un guión
                            echo ($student['note'] !== null) ? $student['note'] : "---"; 
                        ?>
                    </td>

                    <td>
                        <?php if ($student['id_answer']): ?>
                            <a href="grade_student.php?id_answer=<?php echo $student['id_answer']; ?>">
                                <button>Corregir</button>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>