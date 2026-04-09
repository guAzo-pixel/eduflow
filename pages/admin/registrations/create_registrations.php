<?php
    session_start();
    /* Si el usuario no esta logeado o no es admin le echamos */
    if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "admin"){
        header("Location: ../../../index.php");
        exit();
    }

    include '../../../includes/db.php'; 

    try{
        if (isset($_GET['id_student'])){
            $id = $_GET['id_student'];
            $sql = "SELECT name, lastName FROM Users WHERE id_user = :id AND rol = 'student'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        else{
            header("Location: registrations.php");
            exit();
        }
        if (isset($_GET['search']) && !empty($_GET['search'])){
            $search = $_GET['search'];
            $sql_class = "SELECT Users.name, Users.lastName, Users.id_user, Class.material, Class.course, Class.id_class, Class.time
                        FROM Class
                        INNER JOIN Users ON Class.id_teacher = Users.id_user
                        WHERE Class.material LIKE :search OR Class.course LIKE :search OR Users.name LIKE :search OR Users.lastName LIKE :search
                        ORDER BY Class.time ASC";
            
            $stmt = $pdo->prepare($sql_class);

            /* Añadimos "%" en la petición para que busque coencidencias */
            $stmt->execute([':search' => '%' . $search . '%']); 
        }
        else{
            $sql_class = "SELECT Users.name, Users.lastName, Users.id_user, Class.material, Class.course, Class.id_class, Class.time
                        FROM Class
                        INNER JOIN Users ON Class.id_teacher = Users.id_user
                        ORDER BY Class.time ASC";
            $stmt = $pdo->query($sql_class);
        }
        $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
        $error = "Error de base de datos: " . $e->getMessage();
    }


    if (isset($_POST['matricular'])) {
        $id_student = $_GET['id_student']; // Lo recogemos de la URL
        $id_class = $_POST['id_class'];    // Lo recogemos del input oculto

        try {
            $sql_check = "SELECT id_registrations FROM Registrations WHERE id_student = :id_student AND id_class = :id_class";
            
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute([':id_student' => $id_student, ':id_class' => $id_class]);
            $existe = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($existe) {
                // Si $existe tiene datos, significa que ya estaba matriculado.
                $_SESSION['error_message'] = "El alumno ya está matriculado en esta clase.";
                
                // Redirigimos para recargar la página y que salga el mensaje rojo
                header("Location: create_registrations.php?id_student=$id_student");
                exit();
                
            } else {
                $sql_insert = "INSERT INTO Registrations (id_student, id_class) VALUES (:id_student, :id_class)";
                
                $stmt_insert = $pdo->prepare($sql_insert);
                $stmt_insert->execute([':id_student' => $id_student, ':id_class' => $id_class]);

                // Mensaje verde y volvemos al perfil del alumno
                $_SESSION['success_message'] = "Matrícula realizada con éxito.";
                header("Location: student_registrations.php?id=$id_student");
                exit();
            }

        } 
        catch (PDOException $e) {
            $error = "Error al matricular: " . $e->getMessage();
        }
    }


    include '../../../includes/header.php';
?>
<main>
    <h1>Matricular a <?php echo htmlspecialchars($student['name'] . ' ' . $student['lastName'])?> a una clase.</h1>


    <div class="management-menu">
        <form method="GET" action="create_registrations.php">
            <input type="hidden" name="id_student" value="<?php echo $id; ?>">
            
            <input type="text" name="search" placeholder="Buscar materia, curso...">
            <button type="submit">Buscar</button>
            <?php 
                /*Funcion para borrar la busqueda */
                if (!empty($_GET['search'])){
                    echo "<a href='create_registrations.php?id_student=$id'><button type='button'>Borrar busqueda</button></a>";
                }
            ?>
        </form>
        <a href="student_registrations.php?id=<?php echo $id; ?>">
            <button>Volver a Clases Matriculadas</button>
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
                            <form method="POST" action="create_registrations.php?id_student=<?php echo $id; ?>">
                                <input type="hidden" name="id_class" value="<?php echo $class['id_class']; ?>">
                                <button type="submit" name="matricular">Añadir</button>
                            </form>
                            
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