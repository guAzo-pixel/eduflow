<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<main>
    <div class="hero" style="padding-top: 2rem; padding-bottom: 2rem;">
        <div class="section-heading" style="margin: auto; max-width: 800px; text-align: center;">
            <span class="eyebrow">Guía de Uso</span>
            <h2>¿Cómo Funciona Eduflow?</h2>
            <p>Descubre todas las herramientas y funcionalidades diseñadas para la gestión educativa. Elige tu rol para conocer todo lo que puedes hacer en la plataforma.</p>
        </div>
    </div>
    
    <div style="width: min(1200px, 92%); margin: 0 auto 6rem auto;">
        
        <!-- Admin Section -->
        <div class="section-block" style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid var(--bg-paper);">
            <div class="section-heading">
                <span class="eyebrow" style="color: var(--text-carbon);">Administración</span>
                <h2>Panel de Administrador</h2>
                <p>Los administradores tienen control total sobre la estructura de la plataforma, los usuarios y las asignaciones a nivel centro educativo.</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <span class="card-tag">Gestión General</span>
                    <h3>Comunidad de Usuarios</h3>
                    <p>Controla quién tiene acceso a la plataforma. Puedes crear nuevos perfiles, modificar datos existentes y eliminar cuentas de cualquier rol (Administradores, Profesores o Estudiantes).</p>
                </div>
                <div class="feature-card">
                    <span class="card-tag">Estructura</span>
                    <h3>Asignaturas globales</h3>
                    <p>Gestiona el catálogo completo de asignaturas que se imparten. Tienes la capacidad de crear nuevas clases en el sistema general, editarlas o eliminarlas por completo.</p>
                </div>
                <div class="feature-card">
                    <span class="card-tag">Organización</span>
                    <h3>Matrículas de estudiantes</h3>
                    <p>Administra las inscripciones de forma centralizada. Puedes asignar qué estudiantes pertenecen a qué asignaturas, tramitando tanto altas como bajas en el sistema.</p>
                </div>
            </div>
        </div>

        <!-- Teacher Section -->
        <div class="section-block" style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid var(--bg-paper);">
            <div class="section-heading">
                <span class="eyebrow" style="color: var(--accent-terracotta);">Docencia</span>
                <h2>Panel de Profesor</h2>
                <p>Herramientas completas para la gestión del aula, impartición de temario y evaluación continua de los alumnos.</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <span class="card-tag">Aulas virtuales</span>
                    <h3>Clases, Temas y Contenido</h3>
                    <p>Administra tus propias asignaturas. Estructura tu clase creando "Temas" (Topics) y dentro de cada uno añade contenido didáctico como teoría, recursos o apuntes para tus alumnos.</p>
                </div>
                <div class="feature-card">
                    <span class="card-tag">Evaluación</span>
                    <h3>Trabajos y Correcciones</h3>
                    <p>Crea tareas y trabajos dentro de tus temas. Accede a un panel dedicado de correcciones para revisar las entregas que envían los estudiantes, leer sus respuestas y asignarles una nota.</p>
                </div>
                <div class="feature-card">
                    <span class="card-tag">Gestión del aula</span>
                    <h3>Mis Alumnos</h3>
                    <p>Revisa la lista de estudiantes inscritos en tus clases. Tienes autonomía para enrolar a estudiantes existentes en la plataforma dentro de tus clases o expulsarlos si es necesario.</p>
                </div>
            </div>
        </div>

        <!-- Student Section -->
        <div class="section-block" style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid var(--bg-paper);">
            <div class="section-heading">
                <span class="eyebrow" style="color: var(--accent-sage);">Aprendizaje</span>
                <h2>Panel de Estudiante</h2>
                <p>El entorno del alumno, centrado en facilitar el acceso al material de estudio y el envío de sus tareas.</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <span class="card-tag">Estudio</span>
                    <h3>Mis Clases (Dashboard)</h3>
                    <p>Accede al panel de cada una de las asignaturas en las que estás matriculado. Aquí podrás consultar todos los temas estructurados y leer el contenido o teoría subido por tus profesores.</p>
                </div>
                <div class="feature-card">
                    <span class="card-tag">Entregas</span>
                    <h3>Mis Trabajos</h3>
                    <p>Visualiza de un vistazo todas las tareas y proyectos que te han asignado. A través de este panel, podrás enviar (submit) tus respuestas o archivos para que sean evaluados por el profesor.</p>
                </div>
                <div class="feature-card">
                    <span class="card-tag">Personalización</span>
                    <h3>Área Personal</h3>
                    <p>Accede a tu perfil de estudiante para visualizar tus datos, mantener tu información personal actualizada y llevar un control de tu cuenta dentro de Eduflow.</p>
                </div>
            </div>
        </div>
        
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
