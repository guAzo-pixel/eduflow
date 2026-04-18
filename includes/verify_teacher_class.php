<?php
/* Barrera de seguridad centralizada: Verifica que la clase exista y el profesor sea el dueño */
if (!isset($_GET['id_class'])) {
    header("Location: /pages/teacher/classes/classes.php");
    exit();
}

$id_class = $_GET['id_class'];
$id_teacher = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM Class WHERE id_teacher = :id AND id_class = :id_class");
    $stmt->execute([':id' => $id_teacher, ':id_class' => $id_class]);
    $classes = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$classes) {
        header("Location: /pages/teacher/classes/classes.php");
        exit();
    }
} catch (PDOException $e) {
    $error = "Error al verificar la clase: " . $e->getMessage();
}