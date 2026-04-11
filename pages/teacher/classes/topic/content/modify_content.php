<?php
session_start();
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "teacher"){
    header("Location: ../../../../../index.php");
    exit();
}
include '../../../../../includes/db.php'; 

$id_content = $_GET['id'];
$id_class = $_GET['id_class'];
$id_teacher = $_SESSION['user_id'];

try {
    $sql_topic = "SELECT id_topic FROM Content WHERE id_content = :id_c";
    $stmt_id = $pdo->prepare($sql_topic);
    $stmt_id->execute([':id_c' => $id_content]);
    $res_id = $stmt_id->fetch();
    $id_topic = $res_id['id_topic'];

    $sql = "SELECT C.* FROM Content C 
            INNER JOIN Topic T ON C.id_topic = T.id_topic 
            INNER JOIN Class CL ON T.id_class = CL.id_class 
            WHERE C.id_content = :id_c AND CL.id_teacher = :id_t";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_c' => $id_content, ':id_t' => $id_teacher]);
    $content = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$content) {
        header("Location: ../../classes.php");
        exit();
    }

    if (isset($_POST['modificar'])) {
        $name = trim($_POST['name']);
        $subtitle = trim($_POST['subtitle']);
        
        $sql_upd = "UPDATE Content SET name = :nom, subtitle = :sub WHERE id_content = :id_c";
        $stmt_upd = $pdo->prepare($sql_upd);
        $stmt_upd->execute([':nom' => $name, ':sub' => $subtitle, ':id_c' => $id_content]);
        
        $_SESSION['success_message'] = "Contenido actualizado.";
        header("Location: ../topic_details.php?id_class=$id_class&id_topic=$id_topic");
        exit();
    }
} catch (PDOException $e) { $error = $e->getMessage(); }

include '../../../../../includes/header.php';
?>
<main>
    <h1>Modificar Material</h1>
    <form method="POST" action="modify_content.php?id=<?php echo $id_content; ?>&id_class=<?php echo $id_class; ?>">
        <label>Nombre:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($content['name']); ?>" required>
        <label>Descripción:</label>
        <textarea name="subtitle"><?php echo htmlspecialchars($content['subtitle']); ?></textarea>
        <button type="submit" name="modificar">Guardar</button>
        <a href="../topic_details.php?id_class=<?php echo $id_class; ?>&id_topic=<?php echo $id_topic; ?>">Cancelar</a>
    </form>
</main>
<?php include '../../../../../includes/footer.php'; ?>