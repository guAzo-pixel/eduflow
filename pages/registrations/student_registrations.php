<?php
session_start();
/* Si el usuario no esta logeado o no es admin le echamos */
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "admin"){
    header("Location: ../../index.php");
    exit();
}
include '../../includes/db.php'; 

/* Logica para mostrar los datos del usuario id */
if (isset($_GET['id'])){
    $id = $_GET['id'];

    try {
            /* Buscamos el nombre del alumno para el Título (H1) */
        $sql_student = "SELECT name, lastName FROM Users WHERE id_user = :id AND rol = 'student'";
        $stmt_student = $pdo->prepare($sql_student);
        $stmt_student->execute([':id' => $id]);
        $student_info = $stmt_student->fetch(PDO::FETCH_ASSOC);

        if (!$student_info) {
            header("Location: registrations.php");
            exit();
        }

        /* Buscamos TODAS sus matrículas cruzando con la tabla Class */
        $sql_classes = "SELECT Registrations.id_registrations, Registrations.time, Registrations.id_student, Class.material, Class.course
                        FROM Registrations
                        INNER JOIN Class ON Registrations.id_class = Class.id_class
                        WHERE Registrations.id_student = :id
                        ORDER BY Registrations.time DESC";

        $stmt_classes = $pdo->prepare($sql_classes);
        $stmt_classes->execute([':id' => $id]);
        $registrations = $stmt_classes->fetchAll(PDO::FETCH_ASSOC);

        } 
    catch (PDOException $e) {
       $error = "Error de base de datos: " . $e->getMessage();
    }

} else {
    header("Location: registrations.php");
    exit();
}
include '../../includes/header.php';
?>
<main>
    <h1>Gestion de Matriculas de <?php echo htmlspecialchars($student_info['name'] . ' ' . $student_info['lastName'] . '.')?></h1>

    <div class="management-menu">
        <form method="GET" action="registrations.php">
            <input type="text" name="search" placeholder="Buscar la clase...">
            <button type="submit">Buscar</button>
            <?php 
                /*Funcion para borrar la busqueda */
                if (!empty($_GET['search'])){
                    echo "<a href='student_registrations.php'><button type='button'>Borrar busqueda</button></a>";
                }
            ?>
        </form>
        <a href="create_registrations.php?id_student=<?php echo $id; ?>">
            <button>+ Añadir a una Clase</button>
        </a>
        <a href="registrations.php">
            <button>Volver a Alumnos</button>
        </a>
    </div>
    <?php 
    /* Mensaje de error o exito en caso de borrar un usuario */
    if(isset($_SESSION['success_message'])) {
        echo "<div class='success'>" . $_SESSION['success_message'] . "</div>";
        unset($_SESSION['success_message']);
    }
    if(isset($_SESSION['error_message'])) {
        echo "<div class='error'>" . $_SESSION['error_message'] . "</div>";
        unset($_SESSION['error_message']);
    }
    ?>
    <table border="1" width="100%" cellpadding="10">
        <thead>
            <tr>
                <th>Id</th>

                <th>Clase</th>
                <th>Fecha de Alta</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($registrations) && count($registrations) > 0): ?>
                <?php foreach ($registrations as $registration): ?>
                    <tr>
                        <td><?php echo $registration['id_registrations']; ?></td>
                        <td><?php echo htmlspecialchars($registration['material'] . ' ' . $registration['course']); ?></td>
                        <td><?php echo $registration['time']; ?></td>
                        <td>
                            <a href="dell_registrations.php?id_reg=<?php echo $registration['id_registrations']; ?>&id_student=<?php echo $registration['id_student']; ?>">
                                <button style="color: red;">Eliminar</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">No se encontraron matriculas.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    
</main>
<?php include '../../includes/footer.php'; ?>