<?php
session_start();
require_once "config/db.php";
require_once "includes/csrf.php";
$error = null;
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf();
        $correo = filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];
        if (!$correo) {
            throw new Exception('Correo inválido');
        }
        $stmt = $conn->prepare("SELECT * FROM Usuario WHERE correo = ?");
        $stmt->execute([$correo]);
        $row = $stmt->fetch();
        if ($row) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['id_usuario'] = $row['id_usuario'];
                $_SESSION['nombre'] = $row['nombre'];
                $_SESSION['rol'] = $row['rol'];
                $_SESSION['id_area'] = $row['id_area'];
                header("Location: dashboard.php");
                exit;
            }
            // Compatibilidad con contraseñas almacenadas en texto plano
            if ($password === $row['password']) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $upd = $conn->prepare("UPDATE Usuario SET password = ? WHERE id_usuario = ?");
                $upd->execute([$newHash, $row['id_usuario']]);
                $_SESSION['id_usuario'] = $row['id_usuario'];
                $_SESSION['nombre'] = $row['nombre'];
                $_SESSION['rol'] = $row['rol'];
                $_SESSION['id_area'] = $row['id_area'];
                header("Location: dashboard.php");
                exit;
            }
        }
        $error = 'Credenciales incorrectas.';
    }
} catch (Exception $e) {
    if (!is_dir('logs')) {
        mkdir('logs', 0777, true);
    }
    error_log($e->getMessage() . "\n", 3, 'logs/error.log');
    $error = 'Ocurrió un error, intente más tarde.';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login | Tickets</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <h2>Iniciar Sesión</h2>
        <form method="POST" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="password" name="password" placeholder="Contraseña" required autocomplete="current-password">
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
