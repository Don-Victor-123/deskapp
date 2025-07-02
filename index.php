<?php
session_start();
if (isset($_SESSION['id_usuario'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bienvenido | Sistema de Tickets</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Bienvenido al Sistema de Gestión de Tickets</h2>
        <p>Por favor, <a href="login.php">inicia sesión</a> para continuar.</p>
    </div>
</body>
</html>