<?php
session_start();
require_once "config/db.php";
$error = null;
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $correo = $_POST['correo'];
        $password = $_POST['password'];
        $stmt = $conn->prepare("SELECT * FROM Usuario WHERE correo = ?");
        $stmt->execute([$correo]);
        $row = $stmt->fetch();
        if ($row) {
            if ($password === $row['password']) {  // texto plano para pruebas
                $_SESSION['id_usuario'] = $row['id_usuario'];
                $_SESSION['rol'] = $row['rol'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado.";
        }
    }
} catch (Exception $e) {
    $error = "Error en el login: " . $e->getMessage();
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
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="password" name="password" placeholder="Contraseña" required autocomplete="current-password">
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>