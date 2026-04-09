<?php
session_start();

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "teacher"){
    header("Location: ../../../index.php");
    exit();
}

include '../../../includes/db.php'; 

/* Comprobamos que el ID venga por la URL */
if (!isset($_GET['id_class'])) {
    header("Location: classes.php");
    exit();
}

try {
    $id_class = $_GET['id_class'];

    $id = $_SESSION['user_id'];

    $sql = "SELECT * FROM Class WHERE id_teacher = :id AND id_class = :id_class"; 
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([':id' => $id, ':id_class' => $id_class]); 
    
    $classes = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$classes){
        header("Location: classes.php");
        exit();
    }
}