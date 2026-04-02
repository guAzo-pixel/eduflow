<?php 
session_start(); 

/*Comprobamos si "$_SESSION" existe si no asignamos otro valor */
$rol = $_SESSION['user_rol'] ?? 'invitado'
?>
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eduflow</title>
</head>
<body>
<nav>
    <?php if ($rol === "admin"): ?>
        <ul>
            <li><a>Inicio</a></li>
            <li><a>Crear/Modificar clases</a></li>
            <li><a>Matricular alumnos</a></li>
            <li><a>Crear/Modificar base de datos Usuarios</a></li>
        </ul>
    <?php elseif ($rol === "teacher"): ?>
        <ul>
            <li><a>Inicio</a></li>
            <li><a>Mis Clases</a></li>
            <li><a>Mis Alumnos</a></li>
            <li><a>Correciones</a></li>
        </ul>
    <?php elseif ($rol === "student"): ?>
        <ul>
            <li><a>Inicio</a></li>
            <li><a>Mis Clases</a></li>
            <li><a>Trabajos</a></li>
            <li><a>Area Personal</a></li>
        </ul>
    <?php else: ?>
        <ul>
            <li><a>Inicio</a></li>
            <li><a>Sobre Nosotros</a></li>
            <li><a>¿Como funciona?</a></li>
            <li><a>Nustros clientes</a></li>
            <li><a>Inicia Sesion</a></li>
        </ul>
    <?php endif; ?>
</nav>

