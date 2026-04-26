<?php
session_start();

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== "teacher"){
    header("Location: ../../../index.php");
    exit();
}

include '../../../includes/db.php'; 

try {
    $id = $_SESSION['user_id'];

    $sql = "SELECT * FROM Class WHERE id_teacher = :id"; 
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([':id' => $id]); 
    
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Error al cargar tus clases: " . $e->getMessage();
}

include '../../../includes/header.php';
?>

<main>
    <h1>Mis Clases</h1>
    <div class="management-menu">
        <a href="create_class.php">
            <button class="btn-primary" href="create_class.php">+ Crear Clase</button>
        </a>
</div>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
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
                <th>Descripción</th>
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
                        <td><?php echo htmlspecialchars($class['subtitle'] ?? ''); ?></td>
                        <td>
                            <a href="class_dashboard.php?id_class=<?php echo $class['id_class']; ?>">
                                <button>Entrar a la Clase</button>
                            </a>
                            <a href="modify_class.php?id=<?php echo $class['id_class']; ?>">
                                <button>Personalizar</button>
                            </a>
                            <a href="dell_class.php?id_class=<?php echo $class['id_class']; ?>">
                                <button style="color: red;">Eliminar</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">Aún no tienes clases asignadas.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php include '../../../includes/footer.php'; ?>