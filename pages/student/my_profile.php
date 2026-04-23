<?php
include $_SERVER['DOCUMENT_ROOT'] . '/includes/auth_student.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';

$id_student = $_SESSION['user_id'];

try {
    $sql = "SELECT * FROM Users WHERE id_user = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id_student]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (isset($_POST['actualizar'])) {
        $new_password = $_POST['new_password'];
        if (!empty($new_password)) {
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $sql_upd = "UPDATE Users SET password = :pwd WHERE id_user = :id";
            $stmt_upd = $pdo->prepare($sql_upd);
            $stmt_upd->execute([':pwd' => $password_hash, ':id' => $id_student]);
            $_SESSION['success_message'] = "Contraseña actualizada.";
            header("Location: my_profile.php");
            exit();
        }
    }
} catch (PDOException $e) {
    $error = $e->getMessage();
}

include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<main>
    <h1>Área Personal</h1>
    <?php if(isset($_SESSION['success_message'])) { echo "<div class='success'>" . $_SESSION['success_message'] . "</div>"; unset($_SESSION['success_message']); } ?>
    
    <form class="standard-form" method="POST">
        <label>Nombre:</label>
        <input type="text" value="<?php echo htmlspecialchars($user['name'] . ' ' . $user['lastName']); ?>" disabled>
        
        <label>Email:</label>
        <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
        
        <label>Cambiar Contraseña:</label>
        <input type="password" name="new_password" placeholder="Nueva contraseña (dejar en blanco para no cambiar)">
        <button type="submit" name="actualizar">Actualizar</button>
    </form>
</main>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>