<?php
require_once "../includes/auth.php";
require_once "../includes/csrf.php";
require_once "../config/db.php";
if (!es_admin()) { header("Location: ../dashboard.php"); exit; }

$areas = $conn->query("SELECT * FROM Area")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    if ($_POST['accion'] === 'agregar') {
        $nombre = trim($_POST['nombre']);
        $correo = filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL);
        $pass   = $_POST['password'];
        $area   = (int)$_POST['area'];
        if (!$correo) {
            die('Correo inválido');
        }
        $hashed = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO Usuario (nombre, correo, password, rol, id_area) VALUES (?, ?, ?, 'JefeArea', ?)");
        $stmt->execute([$nombre, $correo, $hashed, $area]);
    } elseif ($_POST['accion'] === 'eliminar') {
        $id = (int)$_POST['id_usuario'];
        $stmt = $conn->prepare("DELETE FROM Usuario WHERE id_usuario = ? AND rol = 'JefeArea'");
        $stmt->execute([$id]);
    }
}
$stmt = $conn->query("SELECT U.*, A.nombre_area FROM Usuario U JOIN Area A ON U.id_area = A.id_area WHERE rol='JefeArea'");
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
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="accion" value="agregar">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <select name="area">
                <?php foreach ($areas as $a): ?>
                <option value="<?= $a['id_area'] ?>"><?= htmlspecialchars($a['nombre_area']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Agregar</button>
        </form>
        <table>
            <tr><th>Nombre</th><th>Correo</th><th>Área</th><th>Eliminar</th></tr>
            <?php foreach ($usuarios as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['correo']) ?></td>
                <td><?= htmlspecialchars($row['nombre_area']) ?></td>
                <td>
                    <form method="POST" style="display:inline">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
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
