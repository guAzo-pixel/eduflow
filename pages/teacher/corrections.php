<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_teacher.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';

$id_teacher = $_SESSION['user_id'];

try {
    // Filtro seleccionado por el usuario. Por defecto: 'pending' (pendientes)
    $filter = $_GET['filter'] ?? 'pending';

    // Obtenemos todas las entregas de tareas de las clases de este profesor
    $sql = "SELECT 
                Answer.id_answer,
                Answer.note,
                Answer.time AS fecha_entrega,
                Task.id_task,
                Task.name AS task_name,
                Class.id_class,
                Class.material,
                Users.name AS student_name,
                Users.lastName AS student_lastName
            FROM Answer
            INNER JOIN Task ON Answer.id_task = Task.id_task
            INNER JOIN Topic ON Task.id_topic = Topic.id_topic
            INNER JOIN Class ON Topic.id_class = Class.id_class
            INNER JOIN Users ON Answer.id_student = Users.id_user
            WHERE Class.id_teacher = :id_teacher ";

    // Aplicamos los filtros modificando el final de la consulta SQL
    if ($filter === 'pending') {
        $sql .= " AND Answer.note IS NULL ";
    } elseif ($filter === 'graded') {
        $sql .= " AND Answer.note IS NOT NULL ";
    }

    $sql .= " ORDER BY Answer.time DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_teacher' => $id_teacher]);
    $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Error al cargar las correcciones: " . $e->getMessage();
}

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<main>
    <h1>Correcciones Globales</h1>
    <p>Aquí puedes ver todas las entregas de tus alumnos en todas tus clases.</p>

    <div class="management-menu">
        <form method="GET" action="corrections.php">
            <select name="filter" onchange="this.form.submit()">
                <option value="pending" <?php echo $filter === 'pending' ? 'selected' : ''; ?>>Pendientes de corrección</option>
                <option value="graded" <?php echo $filter === 'graded' ? 'selected' : ''; ?>>Corregidas</option>
                <option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>Todas</option>
            </select>
        </form>
    </div>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <table border="1" width="100%" cellpadding="10">
        <thead>
            <tr>
                <th>Alumno</th>
                <th>Clase</th>
                <th>Tarea</th>
                <th>Fecha Entrega</th>
                <th>Nota</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($answers) > 0): ?>
                <?php foreach ($answers as $ans): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ans['student_lastName'] . ", " . $ans['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($ans['material']); ?></td>
                        <td><?php echo htmlspecialchars($ans['task_name']); ?></td>
                        <td><?php echo $ans['fecha_entrega']; ?></td>
                        <td>
                            <?php echo ($ans['note'] !== null) ? $ans['note'] : "<span style='color:red;'>Sin nota</span>"; ?>
                        </td>
                        <td>
                            <a href="/pages/teacher/classes/topic/tasks/grade_student.php?id_answer=<?php echo $ans['id_answer']; ?>">
                                <button>
                                    <?php echo ($ans['note'] !== null) ? 'Modificar Nota' : 'Corregir'; ?>
                                </button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">No hay entregas para mostrar con el filtro actual.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>