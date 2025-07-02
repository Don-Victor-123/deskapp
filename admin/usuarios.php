<?php
require_once "../includes/auth.php";
require_once "../includes/csrf.php";
require_once "../config/db.php";
if (!es_admin()) { header("Location: ../dashboard.php"); exit; }

$areas = $conn->query("SELECT * FROM Area")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $accion = $_POST['accion'] ?? '';
    if ($accion === 'agregar') {
        $nombre = trim($_POST['nombre']);
        $correo = filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL);
        $rol    = $_POST['rol'];
        $pass   = $_POST['password'];
        $area   = $_POST['area'] !== '' ? (int)$_POST['area'] : null;
        if (!$correo) { die('Correo inválido'); }
        if (!in_array($rol, ['Administrador','JefeArea','Usuario'])) { die('Rol inválido'); }
        $hashed = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO Usuario (nombre, correo, password, rol, id_area) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $correo, $hashed, $rol, $area]);
    } elseif ($accion === 'eliminar') {
        $id = (int)$_POST['id_usuario'];
        $stmt = $conn->prepare("DELETE FROM Usuario WHERE id_usuario = ?");
        $stmt->execute([$id]);
    } elseif ($accion === 'editar') {
        $id     = (int)$_POST['id_usuario'];
        $nombre = trim($_POST['nombre']);
        $correo = filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL);
        $rol    = $_POST['rol'];
        $area   = $_POST['area'] !== '' ? (int)$_POST['area'] : null;
        if (!$correo) { die('Correo inválido'); }
        if (!in_array($rol, ['Administrador','JefeArea','Usuario'])) { die('Rol inválido'); }
        $pass = $_POST['password'];
        if ($pass !== '') {
            $hashed = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE Usuario SET nombre=?, correo=?, password=?, rol=?, id_area=? WHERE id_usuario=?");
            $stmt->execute([$nombre, $correo, $hashed, $rol, $area, $id]);
        } else {
            $stmt = $conn->prepare("UPDATE Usuario SET nombre=?, correo=?, rol=?, id_area=? WHERE id_usuario=?");
            $stmt->execute([$nombre, $correo, $rol, $area, $id]);
        }
    }
}

$filtro = isset($_GET['filtro_area']) ? (int)$_GET['filtro_area'] : 0;
if ($filtro > 0) {
    $stmt = $conn->prepare("SELECT U.*, A.nombre_area FROM Usuario U LEFT JOIN Area A ON U.id_area = A.id_area WHERE U.id_area = ?");
    $stmt->execute([$filtro]);
} else {
    $stmt = $conn->query("SELECT U.*, A.nombre_area FROM Usuario U LEFT JOIN Area A ON U.id_area = A.id_area");
}
$usuarios = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include "../includes/header.php"; ?>
    <main class="container">
        <h2>Gestión de Usuarios</h2>
        <form method="GET" style="margin-bottom:1em;">
            <label>Filtrar por área:</label>
            <select name="filtro_area">
                <option value="0">Todas</option>
                <?php foreach ($areas as $a): ?>
                <option value="<?= $a['id_area'] ?>" <?= $filtro==$a['id_area']?'selected':'' ?>><?= htmlspecialchars($a['nombre_area']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filtrar</button>
            <a href="usuarios.php">Limpiar</a>
        </form>
        <h3>Agregar nuevo usuario</h3>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="accion" value="agregar">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <select name="rol">
                <option value="Administrador">Administrador</option>
                <option value="JefeArea">JefeArea</option>
                <option value="Usuario">Usuario</option>
            </select>
            <select name="area">
                <option value="">Sin área</option>
                <?php foreach ($areas as $a): ?>
                <option value="<?= $a['id_area'] ?>"><?= htmlspecialchars($a['nombre_area']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Agregar</button>
        </form>
        <table>
            <tr><th>Nombre</th><th>Correo</th><th>Rol</th><th>Área</th><th>Acciones</th></tr>
            <?php foreach ($usuarios as $row): ?>
            <tr>
                <form method="POST" style="display:inline">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="accion" value="editar">
                    <input type="hidden" name="id_usuario" value="<?= $row['id_usuario'] ?>">
                    <td><input type="text" name="nombre" value="<?= htmlspecialchars($row['nombre']) ?>"></td>
                    <td><input type="email" name="correo" value="<?= htmlspecialchars($row['correo']) ?>"></td>
                    <td>
                        <select name="rol">
                            <option value="Administrador" <?= $row['rol']=='Administrador'?'selected':'' ?>>Administrador</option>
                            <option value="JefeArea" <?= $row['rol']=='JefeArea'?'selected':'' ?>>JefeArea</option>
                            <option value="Usuario" <?= $row['rol']=='Usuario'?'selected':'' ?>>Usuario</option>
                        </select>
                    </td>
                    <td>
                        <select name="area">
                            <option value="" <?= $row['id_area']===null?'selected':'' ?>>Sin área</option>
                            <?php foreach ($areas as $a): ?>
                            <option value="<?= $a['id_area'] ?>" <?= $row['id_area']==$a['id_area']?'selected':'' ?>><?= htmlspecialchars($a['nombre_area']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
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
