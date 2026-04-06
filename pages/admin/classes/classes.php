<?php
session_start();
/* Si el usuario no esta logeado o no es admin le echamos */
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "admin"){
    header("Location: ../../../index.php");
    exit();
}
include '../../../includes/header.php';
include '../../../includes/db.php'; 

try {
    /*Logica de la funcion para buscar usuario */
    /* "$_GET" viaja en la url, es decir se ecribe ahi "/classs.php?search=" */
    if (isset($_GET['search']) && !empty($_GET['search'])){
        $search = $_GET['search'];
        
        /*A diferenccia de en la pagina users.php aqui necesitamos hacer una una consulta relacional con JOIN*/
        $sql = "SELECT Class.*, Users.name, Users.lastName 
                FROM Class
                INNER JOIN Users ON Class.id_teacher = Users.id_user
                WHERE Class.course LIKE :search OR Class.material LIKE :search OR Class.id_class LIKE :search 
                ORDER BY Class.time DESC";
        
        $stmt = $pdo->prepare($sql);

        /* Añadimos "%" en la petición para que busque coencidencias */
        $stmt->execute([':search' => '%' . $search . '%']);
    }
    else {
         /* Si no se busca nada trae los 10 usuarios añadidos mas recientemente */
        $sql = "SELECT Class.*, Users.name, Users.lastName 
                FROM Class 
                INNER JOIN Users ON Class.id_teacher = Users.id_user 
                ORDER BY Class.time DESC";
        $stmt = $pdo->query($sql);
    }
    
    /* Recogemos los datos de la respuesta */
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    $error = "Error al cargar las clases: " . $e->getMessage();
}
?>

<main>
    <h1>Gestion de Clases</h1>

    <div class="management-menu">
        <form method="GET" action="classes.php">
            <input type="text" name="search" placeholder="Buscar por curso, material, id...">
            <button type="submit">Buscar</button>
            <?php 
                /*Funcion para borrar la busqueda */
                if (!empty($_GET['search'])){
                    echo "<a href='classes.php'><button type='button'>Borrar busqueda</button></a>";
                }
            ?>
        </form>
        <a href="create_class.php">
            <button>+ Añadir Clase</button>
        </a>
    </div>
    <?php 
    /* Mensaje de error o exito en caso de borrar un clase */
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
                <th>ID</th>
                <th>Materia</th>
                <th>Curso</th>
                <th>Creador</th>
                <th>Fecha de Alta</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($classes) && count($classes) > 0): ?>
                <?php foreach ($classes as $class): ?>
                    <tr>
                        <td><?php echo $class['id_class']; ?></td>
                        <td><?php echo htmlspecialchars($class['material']); ?></td>
                        <td><?php echo htmlspecialchars($class['course']); ?></td>
                        <td><?php echo htmlspecialchars($class['name'] . ' ' . $class['lastName']); ?></td>
                        <td><?php echo $class['time']; ?></td>
                        <td>
                            <a href="modify_class.php?id=<?php echo $class['id_class']; ?>">
                                <button>Modificar</button>
                            </a>
                            <a href="dell_class.php?id=<?php echo $class['id_class']; ?>">
                                <button style="color: red;">Eliminar</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">No se encontraron clases.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    
</main>
<?php include '../../../includes/footer.php'; ?>