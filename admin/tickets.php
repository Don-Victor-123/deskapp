<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
if (!es_admin()) { header("Location: ../dashboard.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = $_POST['id_ticket'];
    $estado = $_POST['estado'];
    $stmt = $conn->prepare("UPDATE Ticket SET estado = ? WHERE id_ticket = ?");
    $stmt->execute([$estado, $id]);
}

$stmt = $conn->query(
    "SELECT T.*, A.nombre_area, U.nombre AS creador
     FROM Ticket T
     JOIN Area A ON T.id_area = A.id_area
     JOIN Usuario U ON T.id_usuario = U.id_usuario
     ORDER BY fecha_creacion DESC"
);
$tickets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Todos los Tickets</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include "../includes/header.php"; ?>
    <main class="container">
        <h2>Todos los Tickets</h2>
        <table>
            <tr>
                <th>Título</th><th>Descripción</th><th>Prioridad</th><th>Estado</th>
                <th>Área</th><th>Creador</th><th>Fecha</th><th>Cambiar Estado</th>
            </tr>
            <?php foreach ($tickets as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['titulo']) ?></td>
                <td><?= htmlspecialchars($row['descripcion']) ?></td>
                <td><?= $row['prioridad'] ?></td>
                <td><?= $row['estado'] ?></td>
                <td><?= $row['nombre_area'] ?></td>
                <td><?= $row['creador'] ?></td>
                <td><?= $row['fecha_creacion'] ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id_ticket" value="<?= $row['id_ticket'] ?>">
                        <select name="estado">
                            <option <?= $row['estado']=='Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option <?= $row['estado']=='En proceso' ? 'selected' : '' ?>>En proceso</option>
                            <option <?= $row['estado']=='Realizado' ? 'selected' : '' ?>>Realizado</option>
                        </select>
                        <button type="submit">Actualizar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </main>
    <?php include "../includes/footer.php"; ?>
</body>
</html>