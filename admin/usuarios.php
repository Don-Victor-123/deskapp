<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
if (!es_admin()) { header("Location: ../dashboard.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['accion'] === 'agregar') {
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $pass   = $_POST['password'];
        $stmt = $conn->prepare("INSERT INTO Usuario (nombre, correo, password, rol) VALUES (?, ?, ?, 'JefeArea')");
        $stmt->execute([$nombre, $correo, $pass]);
    } elseif ($_POST['accion'] === 'eliminar') {
        $id = $_POST['id_usuario'];
        $stmt = $conn->prepare("DELETE FROM Usuario WHERE id_usuario = ? AND rol = 'JefeArea'");
        $stmt->execute([$id]);
    }
}
$stmt = $conn->query("SELECT * FROM Usuario WHERE rol='JefeArea'");
$usuarios = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gestión de Jefes de Área</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include "../includes/header.php"; ?>
    <main class="container">
        <h2>Jefes de Área</h2>
        <form method="POST">
            <input type="hidden" name="accion" value="agregar">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Agregar</button>
        </form>
        <table>
            <tr><th>Nombre</th><th>Correo</th><th>Eliminar</th></tr>
            <?php foreach ($usuarios as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['correo']) ?></td>
                <td>
                    <form method="POST" style="display:inline">
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="id_usuario" value="<?= $row['id_usuario'] ?>">
                        <button type="submit" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </main>
    <?php include "../includes/footer.php"; ?>
</body>
</html>