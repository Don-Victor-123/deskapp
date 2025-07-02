<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
$usuario = $_SESSION['id_usuario'];
$stmt = $conn->prepare(
    "SELECT T.*, A.nombre_area
     FROM Ticket T
     JOIN Area A ON T.id_area = A.id_area
     WHERE id_usuario = ?
     ORDER BY fecha_creacion DESC"
);
$stmt->execute([$usuario]);
$tickets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mis Tickets</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include "../includes/header.php"; ?>
    <main class="container">
        <h2>Mis Tickets</h2>
        <table>
            <tr><th>Título</th><th>Descripción</th><th>Prioridad</th><th>Estado</th><th>Área</th><th>Fecha</th></tr>
            <?php foreach ($tickets as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['titulo']) ?></td>
                <td><?= htmlspecialchars($row['descripcion']) ?></td>
                <td><?= $row['prioridad'] ?></td>
                <td><?= $row['estado'] ?></td>
                <td><?= $row['nombre_area'] ?></td>
                <td><?= $row['fecha_creacion'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </main>
    <?php include "../includes/footer.php"; ?>
</body>
</html>