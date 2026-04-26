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
<header class="main-header">
    <a href="/index.php" class="logo">Eduflow.</a>

    <nav class="nav-overlay" id="navOverlay">
        <ul>
            <?php if ($rol === "admin"): ?>
                <li><a href="/index.php">Inicio</a></li>
                <li><a href="/pages/admin/users/users.php">Comunidad</a></li>
                <li><a href="/pages/admin/classes/classes.php">Asignaturas</a></li>
                <li><a href="/pages/admin/registrations/registrations.php">Matrículas</a></li>
                <li><a href="/logout.php" style="color: var(--accent-terracotta);">Cerrar sesión</a></li>
            <?php elseif ($rol === "teacher"): ?>
                <li><a href="/index.php">Inicio</a></li>
                <li><a href="/pages/teacher/classes/classes.php">Mis Clases</a></li>
                <li><a href="/pages/teacher/users/my_students.php">Mis Alumnos</a></li>
                <li><a href="/pages/teacher/corrections.php">Correcciones</a></li>
                <li><a href="/logout.php" style="color: var(--accent-terracotta);">Cerrar sesión</a></li>
            <?php elseif ($rol === "student"): ?>
                <li><a href="/index.php">Inicio</a></li>
                <li><a href="/pages/student/classes/my_classes.php">Mis Clases</a></li>
                <li><a href="/pages/student/tasks/my_tasks.php">Trabajos</a></li>
                <li><a href="/pages/student/profile/my_profile.php">Área Personal</a></li>
                <li><a href="/logout.php" style="color: var(--accent-terracotta);">Cerrar sesión</a></li>
            <?php else: ?>
                <li><a href="/index.php">Inicio</a></li>
                <li><a href="#">Sobre Nosotros</a></li>
                <li><a href="#">¿Cómo funciona?</a></li>
                <li><a href="/login.php" style="color: var(--accent-terracotta);">Inicia Sesión</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    
    <button class="menu-toggle" id="menuToggle">MENÚ</button>
</header>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const menuToggle = document.getElementById('menuToggle');
        const navOverlay = document.getElementById('navOverlay');
        
        if(menuToggle && navOverlay) {
            menuToggle.addEventListener('click', () => {
                navOverlay.classList.toggle('active');
                menuToggle.textContent = navOverlay.classList.contains('active') ? 'CERRAR' : 'MENÚ';
            });
        }
    });
</script>
