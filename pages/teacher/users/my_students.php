<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_teacher.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';

$id_teacher = $_SESSION['user_id']; 

try {
    $sql_c = "SELECT id_class, material, course FROM Class WHERE id_teacher = :id_t";
    $stmt_c = $pdo->prepare($sql_c);
    $stmt_c->execute([':id_t' => $id_teacher]);
    $my_classes = $stmt_c->fetchAll(PDO::FETCH_ASSOC);

    $id_class_filter = $_GET['id_class_filter'] ?? '';
    $search = $_GET['search'] ?? '';

    $sql = "SELECT DISTINCT Users.* FROM Users 
            INNER JOIN Registrations ON Users.id_user = Registrations.id_student
            INNER JOIN Class ON Registrations.id_class = Class.id_class
            WHERE Class.id_teacher = :id_teacher";

    $params = [':id_teacher' => $id_teacher];

    if (!empty($id_class_filter)) {
        $sql .= " AND Class.id_class = :id_class";
        $params[':id_class'] = $id_class_filter;
    }
    if (!empty($search)) {
        $sql .= " AND (Users.name LIKE :search OR Users.email LIKE :search)";
        $params[':search'] = "%$search%";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<main>
    <h1>Gestión Global de Alumnos</h1>

    <div class="management-menu">
        <form method="GET">
            <select name="id_class_filter">
                <option value="">Todas mis asignaturas</option>
                <?php foreach ($my_classes as $c): ?>
                    <option value="<?php echo $c['id_class']; ?>" <?php if($id_class_filter == $c['id_class']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($c['material']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="search" placeholder="Nombre..." value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn-primary" type="submit">Filtrar</button>
            <a href="my_students.php"><button class="btn" type="button">Limpiar</button></a>
        </form>
    </div>

    <table border="1" width="100%" cellpadding="10">
        <thead>
            <tr>
                <th>Nombre Alumno</th>
                <th>Correo</th>
                <th>Asignaturas contigo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($students)): ?>
                <?php foreach ($students as $s): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($s['name'] . " " . $s['lastName']); ?></td>
                        <td><?php echo htmlspecialchars($s['email']); ?></td>
                        <td>
                            <?php 
                            // Buscamos las materias que este alumno cursa con ESTE profesor
                            $sql_mats = "SELECT Class.material 
                                         FROM Class 
                                         INNER JOIN Registrations ON Class.id_class = Registrations.id_class 
                                         WHERE Registrations.id_student = :id_s 
                                         AND Class.id_teacher = :id_t";
                            $stmt_mats = $pdo->prepare($sql_mats);
                            $stmt_mats->execute([':id_s' => $s['id_user'], ':id_t' => $id_teacher]);
                            $materias = $stmt_mats->fetchAll(PDO::FETCH_COLUMN); // FETCH_COLUMN nos da un array simple
                            
                            echo implode(", ", $materias); // Junta las materias con una coma
                            ?>
                        </td>
                        <td>
                            <a href="enroll_existing_student.php?id_user=<?php echo $s['id_user']; ?>">
                                <button>Modificar</button>
                            </a>
                            <a href="mailto:<?php echo $s['email']; ?>">
                                <button>Contactar</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">No se han encontrado alumnos.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>