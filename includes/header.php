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
<meta charset="UTF-8">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eduflow</title>
</head>
<body>
<nav>
    <?php if ($rol === "admin"): ?>
        <ul>
            <li><a href="/index.php">Inicio</a></li>
            <li><a href="/pages/admin/users/users.php">Crear/Modificar usuarios</a></li>
            <li><a href="/pages/admin/classes/classes.php">Crear/Modificar clases</a></li>
            <li><a href="/pages/admin/registrations/registrations.php">Matricular alumnos</a></li>
            <li><a href="/logout.php">Cerrar sesión</a></li>
        </ul>
    <?php elseif ($rol === "teacher"): ?>
        <ul>
            <li><a href="/index.php">Inicio</a></li>
            <li><a href="/pages/teacher/classes/classes.php">Mis Clases</a></li>
            <li><a>Mis Alumnos</a></li>
            <li><a>Correciones</a></li>
            <li><a href="/logout.php">Cerrar sesión</a></li>
        </ul>
    <?php elseif ($rol === "student"): ?>
        <ul>
            <li><a>Inicio</a></li>
            <li><a >Mis Clases</a></li>
            <li><a>Trabajos</a></li>
            <li><a>Area Personal</a></li>
            <li><a href="/logout.php">Cerrar sesión</a></li>
        </ul>
    <?php else: ?>
        <ul>
            <li><a>Inicio</a></li>
            <li><a>Sobre Nosotros</a></li>
            <li><a>¿Como funciona?</a></li>
            <li><a>Nustros clientes</a></li>
            <li><a href="/login.php">Inicia Sesion</a></li>
        </ul>
    <?php endif; ?>
</nav>

