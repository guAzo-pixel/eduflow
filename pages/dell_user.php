<?php
include '../includes/db.php';

$id = $_GET['id'];

$sql = "DELETE FROM Users WHERE id_user = :id";

$stmt = $pdo->prepare($sql);

$stmt->execute([':id' => $id]);

header("Location: users.php");
    
exit();
?>