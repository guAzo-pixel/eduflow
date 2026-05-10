<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php'; 

try {
    $sql = "SELECT * FROM Users ORDER BY time DESC";
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    $error = "Error al cargar los usuarios: " . $e->getMessage();
}
?>

<main>
    <div class="hero" style="padding-top: 2rem; padding-bottom: 2rem;">
        <div class="section-heading" style="margin: auto; max-width: 800px; text-align: center;">
            <span class="eyebrow">Comunidad</span>
            <h2>Sobre Nosotros</h2>
            <p>Conoce a los miembros de nuestra comunidad educativa, incluyendo profesores, estudiantes y administradores que forman parte de Eduflow.</p>
        </div>
    </div>
    
    <div style="width: min(1200px, 92%); margin: 0 auto 6rem auto;">
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Fecha de Alta</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($users) && count($users) > 0): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td data-label="Nombre"><?php echo htmlspecialchars($user['name'] . ' ' . $user['lastName']); ?></td>
                            <td data-label="Email"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td data-label="Rol"><?php echo htmlspecialchars(ucfirst($user['rol'])); ?></td>
                            <td data-label="Fecha de Alta"><?php echo htmlspecialchars($user['time']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">No se encontraron usuarios registrados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>
