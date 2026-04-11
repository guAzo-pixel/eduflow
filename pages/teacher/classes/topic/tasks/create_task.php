<?php
session_start();

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "teacher"){
    header("Location: ../../../../../index.php");
    exit();
}

include '../../../../../includes/db.php'; 

if (!isset($_GET['id_class']) || !isset($_GET['id_topic'])) {
    header("Location: ../../classes.php");
    exit();
}

$id_class = $_GET['id_class'];
$id_topic = $_GET['id_topic'];
$id_teacher = $_SESSION['user_id'];

try {
    $sql_check = "SELECT T.id_topic FROM Topic T 
                  INNER JOIN Class C ON T.id_class = C.id_class 
                  WHERE T.id_topic = :id_t AND C.id_teacher = :id_profe";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':id_t' => $id_topic, ':id_profe' => $id_teacher]);

    if (!$stmt_check->fetch()) {
        header("Location: ../../classes.php");
        exit();
    }

    if (isset($_POST['crear'])) {
        $name = trim($_POST['name']);
        $subtitle = trim($_POST['subtitle']);
        $timeMax = !empty($_POST['timeMax']) ? $_POST['timeMax'] : null;

        $sql_ins = "INSERT INTO Task (id_topic, name, subtitle, timeMax, archive) 
                    VALUES (:id_t, :nom, :sub, :tmax, NULL)";
        $stmt_ins = $pdo->prepare($sql_ins);
        $stmt_ins->execute([
            ':id_t' => $id_topic,
            ':nom' => $name,
            ':sub' => $subtitle,
            ':tmax' => $timeMax
        ]);

        $_SESSION['success_message'] = "Tarea creada con éxito.";
        header("Location: ../topic_details.php?id_class=$id_class&id_topic=$id_topic");
        exit();
    }
} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}

include '../../../../../includes/header.php';
?>

<main>
    <h1>Nueva Actividad / Tarea</h1>
    
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" action="create_task.php?id_topic=<?php echo $id_topic; ?>&id_class=<?php echo $id_class; ?>">
        
        <label>Nombre de la tarea:</label>
        <input type="text" name="name" placeholder="Ej: Ejercicios de Funciones" required>

        <label>Instrucciones o descripción:</label>
        <textarea name="subtitle" placeholder="Detalla qué deben hacer los alumnos..."></textarea>

        <label>Fecha y hora límite de entrega:</label>
        <input type="datetime-local" name="timeMax">

        <div class="buttons">
            <button type="submit" name="crear">Publicar Tarea</button>
            <a href="../topic_details.php?id_class=<?php echo $id_class; ?>&id_topic=<?php echo $id_topic; ?>">
                <button type="button" style="background:gray;">Cancelar</button>
            </a>
        </div>
    </form>
</main>

<?php include '../../../../../includes/footer.php'; ?>