<?php
require_once "../includes/auth.php";
require_once "../includes/csrf.php";
require_once "../config/db.php";
if (!es_jefe_area()) { header("Location: ../dashboard.php"); exit; }

$area = $_SESSION['id_area'] ?? null;
if (!$area) { die('Área no definida'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    if ($_POST['accion'] === 'agregar') {
        $nombre = trim($_POST['nombre']);
        $correo = filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL);
        $pass   = $_POST['password'];
        if (!$correo) { die('Correo inválido'); }
        $hashed = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO Usuario (nombre, correo, password, rol, id_area) VALUES (?, ?, ?, 'Usuario', ?)");
        $stmt->execute([$nombre, $correo, $hashed, $area]);
    } elseif ($_POST['accion'] === 'eliminar') {
        $id = (int)$_POST['id_usuario'];
        $stmt = $conn->prepare("DELETE FROM Usuario WHERE id_usuario = ? AND rol = 'Usuario' AND id_area = ?");
        $stmt->execute([$id, $area]);
    } elseif ($_POST['accion'] === 'editar') {
        $id = (int)$_POST['id_usuario'];
        $nombre = trim($_POST['nombre']);
        $correo = filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL);
        $pass = $_POST['password'];
        if (!$correo) { die('Correo inválido'); }
        if ($pass !== '') {
            $hashed = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE Usuario SET nombre=?, correo=?, password=? WHERE id_usuario=? AND rol='Usuario' AND id_area=?");
            $stmt->execute([$nombre, $correo, $hashed, $id, $area]);
        } else {
            $stmt = $conn->prepare("UPDATE Usuario SET nombre=?, correo=? WHERE id_usuario=? AND rol='Usuario' AND id_area=?");
            $stmt->execute([$nombre, $correo, $id, $area]);
        }
    }
}

$stmt = $conn->prepare("SELECT * FROM Usuario WHERE rol='Usuario' AND id_area = ?");
$stmt->execute([$area]);
$usuarios = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Usuarios de mi Área</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include "../includes/header.php"; ?>
    <main class="container">
        <h2>Usuarios de mi Área</h2>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="accion" value="agregar">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Agregar</button>
        </form>
        <table>
            <tr><th>Nombre</th><th>Correo</th><th>Acciones</th></tr>
            <?php foreach ($usuarios as $row): ?>
            <tr>
                <form method="POST" style="display:inline">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="accion" value="editar">
                    <input type="hidden" name="id_usuario" value="<?= $row['id_usuario'] ?>">
                    <td><input type="text" name="nombre" value="<?= htmlspecialchars($row['nombre']) ?>"></td>
                    <td><input type="email" name="correo" value="<?= htmlspecialchars($row['correo']) ?>"></td>
                    <td>
                        <input type="password" name="password" placeholder="Nueva contraseña">
                        <button type="submit">Guardar</button>
                </form>
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

