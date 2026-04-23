<?php 
/*para la comprobación inicial cargamos el rol, una vez que hay sesion no volvemos a crearla */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*Comprobamos si "$_SESSION" existe si no asignamos otro valor */
$rol = $_SESSION['user_rol'] ?? 'invitado'
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/main.css">
    <title>Eduflow</title>
</head>
<body>
<nav class="prin">
    <a href="/index.php" class="logo">EduFlow</a>
    <?php if ($rol === "admin"): ?>
        <ul>
            <li><a href="/index.php">Inicio</a></li>
            <li><a href="/pages/admin/users/users.php">Crear/Modificar usuarios</a></li>
            <li><a href="/pages/admin/classes/classes.php">Crear/Modificar clases</a></li>
            <li><a href="/pages/admin/registrations/registrations.php">Matricular alumnos</a></li>
            <li class="logout"><a href="/logout.php">Cerrar sesión</a></li>
        </ul>
    <?php elseif ($rol === "teacher"): ?>
        <ul>
            <li><a href="/index.php">Inicio</a></li>
            <li><a href="/pages/teacher/classes/classes.php">Mis Clases</a></li>
            <li><a href="/pages/teacher/users/my_students.php">Mis Alumnos</a></li>
            <li><a href="/pages/teacher/corrections.php">Correciones</a></li>
            <li class="logout"><a href="/logout.php">Cerrar sesión</a></li>
        </ul>
    <?php elseif ($rol === "student"): ?>
        <ul>
            <li><a href="/index.php">Inicio</a></li>
            <li><a href="/pages/student/classes/my_classes.php">Mis Clases</a></li>
            <li><a href="/pages/student/tasks/my_tasks.php">Trabajos</a></li>
            <li><a href="/pages/student/profile/my_profile.php">Area Personal</a></li>
            <li class="logout"><a href="/logout.php">Cerrar sesión</a></li>
        </ul>
    <?php else: ?>
        <ul>
            <li><a>Inicio</a></li>
            <li><a>Sobre Nosotros</a></li>
            <li><a>¿Como funciona?</a></li>
            <li><a>Nustros clientes</a></li>
            <li class="login"><a href="/login.php">Inicia Sesion</a></li>
        </ul>
    <?php endif; ?>
</nav>
