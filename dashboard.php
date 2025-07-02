<?php
require_once "includes/auth.php";
require_once "config/db.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Panel Principal | Tickets</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include "includes/header.php"; ?>
    <main class="container">
        <h2>Bienvenido, <?= htmlspecialchars($_SESSION['rol']) ?></h2>
        <p>Selecciona una opción del menú para comenzar.</p>
    </main>
    <?php include "includes/footer.php"; ?>
</body>
</html>