# EduFlow

Este repositorio contiene la infraestructura y el código fuente de **EduFlow**, una plataforma de gestión para aulas virtuales desarrollada como proyecto técnico integral para el ciclo de **2º de SMR (Sistemas Microinformáticos y Redes)**. 

El proyecto representa una evolución de un modelo básico basado en almacenamiento local (LocalStorage) hacia un **ecosistema Cliente-Servidor real**, completamente securizado, persistente, contenedorizado y accesible tanto desde la intranet local como desde Internet.

---

##  Arquitectura de Red e Infraestructura

EduFlow no funciona de forma aislada; está desplegado en un servidor físico Linux orquestado mediante **Docker** y conectado en red de forma híbrida con servicios perimetrales y de entretenimiento (**Stack Odiseus**).

```text
                                  [ INTERNET ]
                                       │
                         (DuckDNS: eduflowsmr.duckdns.org)
                                       │
                                       ▼
                       ┌───────────────────────────────┐
                       │    Router Doméstico (NAT)     │
                       │     Puertos 80 y 443 Abiertos │
                       └───────────────┬───────────────┘
                                       │
                                       ▼
    ┌─────────────────────────────────────────────────────────────────────┐
    │ SERVIDOR FÍSICO (Ubuntu Server - IP: 192.168.1.254)                 │
    │                                                                     │
    │  [ Cortafuegos Perimetral ] UFW Activo (Solo SSH, HTTP, HTTPS)      │
    │                                                                     │
    │  [ Proxy Inverso Maestro ]                                          │
    │    └── Caddy Container (Escucha en 80/443, SSL Let's Encrypt Real)  │
    │          │                                                          │
    │          ├──► Host: eduflowsmr.duckdns.org ──┐                      │
    │          └──► Host Local: eduflow.smr ───────┼──┐                   │
    │                                              │  │                   │
    │  [ Red Compartida: media_media_network ]     │  │                   │
    │    ┌─────────────────────────────────────────┘  │                   │
    │    ▼                                            │                   │
    │  ┌───────────────────────────┐                  │                   │
    │  │ Contenedor: smr_web       │◄─────────────────┘                   │
    │  │ (Servidor Apache + PHP)   │                                      │
    │  └─────────────┬─────────────┘                                      │
    │                │                                                    │
    │  [ Red Interna Aislada: smr_red ]                                   │
    │                ├──► Contenedor: smr_db (MariaDB - Puerto 3306)      │
    │                └──► Contenedor: smr_dns (Bind9 - Zona eduflow.smr)  │
    └─────────────────────────────────────────────────────────────────────┘
```

### Componentes de Infraestructura:
* **Proxy Inverso (Caddy):** Centraliza el tráfico externo y local. Proporciona HTTPS automático (Let's Encrypt) para el acceso externo y certificados internos para la intranet. Oculta la topología interna protegiendo el backend Apache.
* **Servidor DNS Local (Bind9):** Configurado con reenviadores (*forwarders*) públicos. Resuelve de forma autoritativa el dominio `eduflow.smr` dentro de la red del centro educativo para garantizar el funcionamiento sin depender de la salida a Internet.
* **Base de Datos (MariaDB):** Un *fork* ligero de MySQL de código abierto, ideal para la persistencia del modelo de datos de forma eficiente bajo contenedores Docker.
* **Seguridad del Host (UFW):** Cortafuegos configurado con políticas estrictas de reenvío (`ACCEPT` controlado para Docker) que bloquea el acceso externo a bases de datos o servicios de gestión interna.

---

##  Perfiles de Usuario y Modelo Lógico

El sistema gestiona dinámicamente cuatro perfiles mediante control de sesiones en el servidor PHP y privilegios basados en el estándar **CRUD** (Create, Read, Update, Delete):

1.  **Students (Alumnos):** Permisos de lectura de clases asignadas, descarga de materiales y subida/modificación de entregas de tareas dentro del plazo establecido.
2.  **Teachers (Profesores):** Propietarios de sus aulas virtuales. Permisos para crear clases, matricular alumnos, estructurar temas, añadir contenidos y calificar entregas.
3.  **Admin (Administradores):** Control total de la infraestructura. Capacidad de realizar operaciones CRUD globales sobre usuarios, asignación de roles, reinicio de contraseñas y auditoría del sistema.
4.  **Usuarios sin Registrar:** Estado de transición. Acceso exclusivo a la Landing Page corporativa para conocer las características del software.

---

##  Diseño de la Base de Datos

El esquema relacional está diseñado para asegurar la integridad de los datos mediante claves primarias (`id_`) y relaciones lógicas consistentes:

* **Tabla Usuarios:** Almacena credenciales seguras mediante el hash de contraseñas del sistema y define el `rol`.
* **Tabla Clases:** Lista las asignaturas creadas vinculando un profesor (`id_teacher`).
* **Tabla Matriculas:** Tabla puente con relación muchos a muchos para inscribir alumnos en clases.
* **Tabla Temas:** Segmenta los módulos de aprendizaje dentro de cada clase.
* **Tabla Tareas:** Gestiona los enunciados de ejercicios, archivos adjuntos del docente y las marcas de tiempo de expiración (`timeMax`).
* **Tabla Entregas:** Relaciona al estudiante con su respuesta enviada, el archivo cargado y la calificación otorgada por el profesor.
* **Tabla Contenido:** Permite la publicación de material didáctico adicional (párrafos, enlaces, documentos).

---

##  Directrices de Seguridad del Lado del Servidor

Para mitigar las principales vulnerabilidades web indicadas en estándares como OWASP, se han implementado las siguientes defensas a nivel de código y servidor:

* **Inyección SQL:** Se emplean consultas preparadas en PHP (PDO) para desinfectar cualquier entrada proveniente de los formularios.
* **Ataques XSS (Cross-Site Scripting):** Uso sistemático de funciones de escape como `htmlspecialchars()` al renderizar variables capturadas por `$_GET` y `$_POST`.
* **Seguridad en Variables de Entorno:** Toda credencial crítica de producción (passwords de base de datos, claves de cifrado) está completamente aislada en archivos `.env` protegidos mediante directivas de servidor en el fichero `.htaccess`.
* **Políticas de Cabeceras HTTP:** El proxy Caddy inyecta cabeceras de seguridad estrictas para mitigar ataques de clickjacking y sniffing de tipos MIME:
    ```text
    Strict-Transport-Security max-age=31536000
    X-Content-Type-Options nosniff
    X-Frame-Options DENY
    ```

---

##  Estructura de Directorios del Proyecto

La estructura del código sigue un patrón modular limpio para facilitar el desarrollo colaborativo mediante Git:

```text
├── caddy/                  # Archivos de configuración del Proxy Inverso maestro
├── bind/                   # Archivos de zona y named.conf.options para Bind9
├── eduflow/                # Carpeta raíz de la aplicación web (Montada en el contenedor Apache)
│   ├── database/           # Scripts SQL y backups de inicialización automática de la base de datos
│   ├── includes/           # Componentes comunes del backend (Conexión db.php, cabeceras header.php)
│   ├── pages/              # Módulos de la aplicación divididos estrictamente por rol asignado
│   │   ├── admin/          # Paneles y vistas de administración global
│   │   ├── teacher/        # Interfaz de gestión y calificación de profesores
│   │   └── student/        # Panel de asignaturas, tareas y entregas del alumno
│   ├── css/                # Hojas de estilo estructuradas para el diseño responsive e identidad visual
│   ├── index.php           # Landing Page principal de la aplicación
│   ├── login.php           # Pasarela de autenticación segura y control de sesiones
│   └── .htaccess           # Protección del entorno y bloqueo de archivos sensibles
├── docker-compose.yml      # Orquestador del stack educativo EduFlow
└── README.md               # Documentación técnica del proyecto
```

---

##  Despliegue de la Infraestructura

### Requisitos Previos:
* Docker y Docker Compose instalados en el sistema Host.
* Una red puente externa existente (en este entorno creada por el proyecto base de media como `media_media_network`).
* Puertos 80 y 443 del router redirigidos a la IP estática local del servidor físico (`192.168.1.254`).

### Instrucciones de Arranque:
1.  Clonar el repositorio en el servidor de producción:
    ```bash
    git clone https://github.com/guAzo-pixel/eduflow.git ~/eduflow
    ```
2.  Configurar las variables correspondientes dentro del archivo `eduflow/.env`.
3.  Desplegar el stack completo de contenedores en segundo plano:
    ```bash
    cd ~/eduflow
    docker compose up -d
    ```
4.  Verificar el correcto funcionamiento de los servicios y mapeos de red:
    ```bash
    docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
    ```