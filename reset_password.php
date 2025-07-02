<?php
session_start();
require_once "config/db.php";
require_once "includes/csrf.php";

$token = $_GET['token'] ?? ($_POST['token'] ?? '');
$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $pass = $_POST['password'];
    $stmt = $conn->prepare("SELECT user_id FROM password_reset WHERE token=? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $row = $stmt->fetch();
    if ($row) {
        $hashed = password_hash($pass, PASSWORD_DEFAULT);
        $upd = $conn->prepare("UPDATE Usuario SET password=? WHERE id_usuario=?");
        $upd->execute([$hashed, $row['user_id']]);
        $conn->prepare("DELETE FROM password_reset WHERE token=?")->execute([$token]);
        $message = 'Contraseña actualizada correctamente.';
    } else {
        $message = 'El enlace no es válido o expiró.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nuevo Password</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Establecer Nueva Contraseña</h2>
        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if (!$message || strpos($message, 'actualizada')===false): ?>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <input type="password" name="password" placeholder="Nueva contraseña" required>
            <button type="submit">Guardar</button>
        </form>
        <?php endif; ?>
        <p><a href="login.php">Volver al login</a></p>
    </div>
</body>
</html>
