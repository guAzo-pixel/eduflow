<?php
session_start();
/* Si el usuario no esta logeado o no es admin le echamos */
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "admin"){
    header("Location: ../../index.php");
    exit();
}
include '../../includes/header.php';
include '../../includes/db.php'; 