<?php
session_start();
require_once "config/db.php";
require_once "includes/csrf.php";

$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $correo = filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL);
    if ($correo) {
        $stmt = $conn->prepare("SELECT id_usuario FROM Usuario WHERE correo = ?");
        $stmt->execute([$correo]);
        $user = $stmt->fetch();
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hora
            $ins = $conn->prepare("INSERT INTO password_reset (user_id, token, expires_at) VALUES (?, ?, ?)");
            $ins->execute([$user['id_usuario'], $token, $expires]);
            $message = "Siga este enlace para restablecer su contraseña: ";
            $message .= "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset_password.php?token=$token";
        } else {
            $message = 'Si el correo existe, recibirá un enlace para restablecer la contraseña.';
        }
    } else {
        $message = 'Correo inválido.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Restablecer Contraseña</h2>
        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="email" name="correo" placeholder="Correo" required>
            <button type="submit">Enviar enlace</button>
        </form>
        <p><a href="login.php">Volver al login</a></p>
    </div>
</body>
</html>
