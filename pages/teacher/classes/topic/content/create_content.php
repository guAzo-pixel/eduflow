<?php
session_start();

/* 1. BARRERA DE SEGURIDAD */
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "teacher"){
    header("Location: ../../../../../index.php"); // 5 niveles hasta la raíz
    exit();
}

include '../../../../../includes/db.php'; 

if (!isset($_GET['id_class']) || !isset($_GET['id_topic'])) {
    header("Location: ../../classes.php"); // Volver a la lista de clases
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


    if (isset($_POST['subir'])) {
        $name = trim($_POST['name']);
        $subtitle = trim($_POST['subtitle']);


        
        $sql_ins = "INSERT INTO Content (id_topic, name, subtitle) 
                    VALUES (:id_t, :nom, :sub)";
        $stmt_ins = $pdo->prepare($sql_ins);
        $stmt_ins->execute([
            ':id_t' => $id_topic,
            ':nom' => $name,
            ':sub' => $subtitle,
        ]);

        $_SESSION['success_message'] = "Material subido correctamente.";
        header("Location: ../topic_details.php?id_class=$id_class&id_topic=$id_topic");
        exit();
    }
} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}

include '../../../../../includes/header.php';
?>

<main>
    <h1>Añadir Contenido a la UT</h1>
    
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" enctype="multipart/form-data" action="create_content.php?id_topic=<?php echo $id_topic; ?>&id_class=<?php echo $id_class; ?>">
        
        <label>Nombre del material:</label>
        <input type="text" name="name" placeholder="Ej: Guía de sintaxis PHP" required>

        <label>Descripción:</label>
        <textarea name="subtitle" placeholder="Breve explicación del contenido..."></textarea>

        <div class="buttons">
            <button type="submit" name="subir">Guardar Material</button>
            <a href="../topic_details.php?id_class=<?php echo $id_class; ?>&id_topic=<?php echo $id_topic; ?>">
                <button type="button" style="background:gray;">Cancelar</button>
            </a>
        </div>
    </form>
</main>

<?php include '../../../../../includes/footer.php'; ?>