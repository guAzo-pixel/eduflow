<?php 
include 'includes/header.php';
include 'includes/db.php'; 
?>


<main class="hero">

    <!-- ========================================================= -->
    <!-- HERO -->
    <!-- ========================================================= -->

    <section class="hero-grid">

        <div class="hero-content">

            <span class="eyebrow">
                Educational Infrastructure • Docker • Security • Networking
            </span>

            <h1>
                EduFlow
            </h1>

            <p class="hero-subtitle">
                Knowledge, orchestrated.
            </p>

            <p class="hero-description">
                EduFlow es una plataforma educativa desarrollada como proyecto académico
                orientado a la gestión integral de un centro educativo mediante tecnologías
                reales de infraestructura, virtualización y desarrollo web.
            </p>

            <p class="hero-description">
                El proyecto combina administración de sistemas, bases de datos,
                redes, seguridad informática y desarrollo backend para construir
                un ecosistema educativo moderno, seguro y escalable.
            </p>

            <div class="button-group">

                <a href="login.php" class="btn btn-primary">
                    Acceder al sistema
                </a>

                <a href="#arquitectura" class="btn btn-outline">
                    Explorar arquitectura
                </a>

            </div>

        </div>

        <div class="hero-card">

            <div class="hero-card-content">

                <span class="card-tag">
                    Infraestructura desplegada
                </span>

                <h3>
                    Stack tecnológico
                </h3>

                <p>
                    EduFlow utiliza una arquitectura híbrida basada
                    en contenedores Docker, proxy inverso, DNS interno
                    y servicios web reales.
                </p>

                <table>

                    <tbody>

                        <tr>
                            <td data-label="Tecnología">
                                PHP
                            </td>

                            <td data-label="Función">
                                Backend y lógica del sistema
                            </td>
                        </tr>

                        <tr>
                            <td data-label="Tecnología">
                                MariaDB
                            </td>

                            <td data-label="Función">
                                Persistencia relacional
                            </td>
                        </tr>

                        <tr>
                            <td data-label="Tecnología">
                                Docker
                            </td>

                            <td data-label="Función">
                                Contenerización
                            </td>
                        </tr>

                        <tr>
                            <td data-label="Tecnología">
                                Caddy
                            </td>

                            <td data-label="Función">
                                Proxy inverso + HTTPS
                            </td>
                        </tr>

                        <tr>
                            <td data-label="Tecnología">
                                Bind9
                            </td>

                            <td data-label="Función">
                                DNS interno .smr
                            </td>
                        </tr>

                        <tr>
                            <td data-label="Tecnología">
                                DuckDNS
                            </td>

                            <td data-label="Función">
                                Resolución externa
                            </td>
                        </tr>

                        <tr>
                            <td data-label="Tecnología">
                                UFW
                            </td>

                            <td data-label="Función">
                                Firewall Linux
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </section>

    <!-- ========================================================= -->
    <!-- INTRODUCCIÓN -->
    <!-- ========================================================= -->

    <section class="section-block">

        <div class="section-heading">

            <span class="eyebrow">
                Visión del proyecto
            </span>

            <h2>
                Mucho más que una página web escolar
            </h2>

            <p>
                EduFlow nace como una evolución del reto académico planteado
                para el ciclo formativo de Sistemas Microinformáticos y Redes.
                Aunque el reto inicial proponía una solución basada en LocalStorage,
                el proyecto evolucionó hacia una infraestructura cliente-servidor real.
            </p>

        </div>

        <div class="features-grid">

            <article class="feature-card">

                <h3>
                    Persistencia real
                </h3>

                <p>
                    LocalStorage fue descartado debido a sus limitaciones
                    de sincronización y persistencia entre dispositivos.
                    MariaDB permite almacenamiento centralizado y acceso multiusuario.
                </p>

            </article>

            <article class="feature-card">

                <h3>
                    Infraestructura profesional
                </h3>

                <p>
                    El sistema fue desplegado utilizando Docker,
                    proxy inverso y DNS híbrido para aproximarse
                    a una infraestructura empresarial real.
                </p>

            </article>

            <article class="feature-card">

                <h3>
                    Seguridad integrada
                </h3>

                <p>
                    EduFlow incorpora HTTPS, control de sesiones,
                    roles de usuario, firewall y protección
                    frente a vulnerabilidades comunes.
                </p>

            </article>

        </div>

    </section>

    <!-- ========================================================= -->
    <!-- FUNCIONALIDADES -->
    <!-- ========================================================= -->

    <section class="section-block">

        <div class="section-heading">

            <span class="eyebrow">
                Funcionalidades
            </span>

            <h2>
                Ecosistema académico modular
            </h2>

        </div>

        <table>

            <thead>

                <tr>
                    <th>Módulo</th>
                    <th>Descripción</th>
                    <th>Tecnología aplicada</th>
                </tr>

            </thead>

            <tbody>

                <tr>

                    <td data-label="Módulo">
                        Gestión de cursos
                    </td>

                    <td data-label="Descripción">
                        Creación y administración de clases virtuales.
                    </td>

                    <td data-label="Tecnología">
                        PHP + MariaDB
                    </td>

                </tr>

                <tr>

                    <td data-label="Módulo">
                        Matriculación
                    </td>

                    <td data-label="Descripción">
                        Asociación dinámica entre alumnos y clases.
                    </td>

                    <td data-label="Tecnología">
                        Relaciones SQL
                    </td>

                </tr>

                <tr>

                    <td data-label="Módulo">
                        Sistema de tareas
                    </td>

                    <td data-label="Descripción">
                        Publicación y entrega de actividades.
                    </td>

                    <td data-label="Tecnología">
                        Backend PHP
                    </td>

                </tr>

                <tr>

                    <td data-label="Módulo">
                        Filtrado de alumnos
                    </td>

                    <td data-label="Descripción">
                        Búsqueda dinámica mediante consultas SQL.
                    </td>

                    <td data-label="Tecnología">
                        SELECT + LIKE
                    </td>

                </tr>

                <tr>

                    <td data-label="Módulo">
                        Roles y permisos
                    </td>

                    <td data-label="Descripción">
                        Sistema jerárquico Admin / Teacher / Student.
                    </td>

                    <td data-label="Tecnología">
                        password_hash + sesiones
                    </td>

                </tr>

            </tbody>

        </table>

    </section>

    <!-- ========================================================= -->
    <!-- ARQUITECTURA -->
    <!-- ========================================================= -->

    <section class="section-block" id="arquitectura">

        <div class="section-heading">

            <span class="eyebrow">
                Arquitectura técnica
            </span>

            <h2>
                Infraestructura híbrida y segmentada
            </h2>

            <p>
                La plataforma utiliza un modelo de capas
                donde cada servicio cumple una función específica
                dentro del ecosistema.
            </p>

        </div>

        <div class="architecture-flow">

            <div class="flow-card">
                <span>1</span>
                <h3>Cliente</h3>
                <p>
                    Navegador web del alumno o profesor.
                </p>
            </div>

            <div class="flow-arrow">
                →
            </div>

            <div class="flow-card">
                <span>2</span>
                <h3>Caddy Proxy</h3>
                <p>
                    Centraliza HTTPS y enruta peticiones.
                </p>
            </div>

            <div class="flow-arrow">
                →
            </div>

            <div class="flow-card">
                <span>3</span>
                <h3>Apache + PHP</h3>
                <p>
                    Procesamiento lógico del backend.
                </p>
            </div>

            <div class="flow-arrow">
                →
            </div>

            <div class="flow-card">
                <span>4</span>
                <h3>MariaDB</h3>
                <p>
                    Persistencia y consultas relacionales.
                </p>
            </div>

        </div>

    </section>

    <!-- ========================================================= -->
    <!-- SEGURIDAD -->
    <!-- ========================================================= -->

    <section class="section-block">

        <div class="section-heading">

            <span class="eyebrow">
                Seguridad y securización
            </span>

            <h2>
                Arquitectura protegida
            </h2>

            <p>
                EduFlow fue diseñado aplicando principios básicos
                de ciberseguridad y administración segura de servicios.
            </p>

        </div>

        <div class="security-grid">

            <article class="feature-card">

                <h3>
                    Proxy inverso
                </h3>

                <p>
                    Caddy actúa como gateway central,
                    ocultando la arquitectura interna
                    y gestionando certificados SSL/TLS automáticos.
                </p>

            </article>

            <article class="feature-card">

                <h3>
                    Firewall UFW
                </h3>

                <p>
                    El tráfico entrante queda limitado
                    únicamente a los puertos necesarios,
                    reduciendo la superficie de ataque.
                </p>

            </article>

            <article class="feature-card">

                <h3>
                    Protección backend
                </h3>

                <p>
                    Sanitización de entradas,
                    control de sesiones
                    y uso de password_hash.
                </p>

            </article>

            <article class="feature-card">

                <h3>
                    DNS híbrido
                </h3>

                <p>
                    Bind9 permite resolución local .smr,
                    mientras DuckDNS facilita acceso remoto.
                </p>

            </article>

        </div>

    </section>

    <!-- ========================================================= -->
    <!-- ESTRUCTURA -->
    <!-- ========================================================= -->

    <section class="section-block">

        <div class="section-heading">

            <span class="eyebrow">
                Organización del proyecto
            </span>

            <h2>
                Estructura modular del sistema
            </h2>

        </div>

        <table>

            <thead>

                <tr>
                    <th>Directorio</th>
                    <th>Responsabilidad</th>
                </tr>

            </thead>

            <tbody>

                <tr>

                    <td data-label="Directorio">
                        /includes
                    </td>

                    <td data-label="Responsabilidad">
                        Componentes reutilizables del sistema.
                    </td>

                </tr>

                <tr>

                    <td data-label="Directorio">
                        /pages/admin
                    </td>

                    <td data-label="Responsabilidad">
                        Gestión administrativa global.
                    </td>

                </tr>

                <tr>

                    <td data-label="Directorio">
                        /pages/teacher
                    </td>

                    <td data-label="Responsabilidad">
                        Gestión académica de clases y tareas.
                    </td>

                </tr>

                <tr>

                    <td data-label="Directorio">
                        /pages/student
                    </td>

                    <td data-label="Responsabilidad">
                        Acceso del alumno a contenidos y entregas.
                    </td>

                </tr>

                <tr>

                    <td data-label="Directorio">
                        /css
                    </td>

                    <td data-label="Responsabilidad">
                        Sistema visual editorial responsive.
                    </td>

                </tr>

                <tr>

                    <td data-label="Directorio">
                        /database
                    </td>

                    <td data-label="Responsabilidad">
                        Scripts SQL y estructura relacional.
                    </td>

                </tr>

            </tbody>

        </table>

    </section>

    <!-- ========================================================= -->
    <!-- DESARROLLO -->
    <!-- ========================================================= -->

    <section class="section-block">

        <div class="section-heading">

            <span class="eyebrow">
                Desarrollo colaborativo
            </span>

            <h2>
                Metodología de trabajo
            </h2>

        </div>

        <div class="features-grid">

            <article class="feature-card">

                <h3>
                    GitHub
                </h3>

                <p>
                    El proyecto utiliza control de versiones
                    mediante ramas para facilitar
                    el trabajo colaborativo.
                </p>

            </article>

            <article class="feature-card">

                <h3>
                    Visual Studio Code
                </h3>

                <p>
                    Todo el entorno de desarrollo
                    fue centralizado mediante VSCode
                    conectado remotamente por SSH.
                </p>

            </article>

            <article class="feature-card">

                <h3>
                    Dockerización
                </h3>

                <p>
                    Cada servicio se ejecuta
                    de forma aislada para simplificar
                    mantenimiento y despliegue.
                </p>

            </article>

        </div>

    </section>

</main>



<?php include 'includes/footer.php'; ?>