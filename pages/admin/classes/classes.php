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
                WHERE Class.course LIKE :search 
                OR Class.material LIKE :search 
                OR Class.id_class LIKE :search
                OR Users.name LIKE :search 
                OR Users.lastName LIKE :search
                ORDER BY Class.time DESC";
        
        $stmt = $pdo->prepare($sql);

        /* Añadimos "%" en la petición para que busque coencidencias */
        $stmt->execute([':search' => '%' . $search . '%']);
    }
    else {
         /* Si no se busca nada trae los usuarios añadidos mas recientemente */
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
            <button type="submit" class="btn">Buscar</button>
            <?php 
                /*Funcion para borrar la busqueda */
                if (!empty($_GET['search'])){
                    echo "<a href='classes.php'><button type='button'>Borrar busqueda</button></a>";
                }
            ?>
        </form>
        <a href="create_class.php">
            <button class="btn-primary">+ Añadir Clase</button>
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
                        <td data-label="ID"><?php echo $class['id_class']; ?></td>
                        <td data-label="Materia"><?php echo htmlspecialchars($class['material']); ?></td>
                        <td data-label="Curso"><?php echo htmlspecialchars($class['course']); ?></td>
                        <td data-label="Creador"><?php echo htmlspecialchars($class['name'] . ' ' . $class['lastName']); ?></td>
                        <td data-label="Fecha de Alta"><?php echo $class['time']; ?></td>
                        <td data-label="Acciones">
                            <a href="modify_class.php?id=<?php echo $class['id_class']; ?>">
                                <button class="btn-primary">Modificar</button>
                            </a>
                            <a href="dell_class.php?id=<?php echo $class['id_class']; ?>">
                                <button class="btn btn-danger">Eliminar</button>
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