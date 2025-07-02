<?php
require_once "../includes/auth.php";
require_once "../config/db.php";
if (!es_admin() && !es_jefe_area()) { header("Location: ../dashboard.php"); exit; }

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $prioridad =$_POST['prioridad'];
    $area = $_POST['area'];
    $usuario = $_SESSION['id_usuario'];
    $stmt = $conn->prepare(
        "INSERT INTO Ticket (titulo, descripcion, prioridad, estado, id_usuario, id_area)
         VALUES (?, ?, ?, 'Pendiente', ?, ?)"
    );
    $stmt->execute([
        $titulo, $descripcion, $prioridad, $usuario, $area
    ]);
}
$areas = $conn->query("SELECT * FROM Area")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Crear Ticket</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include "../includes/header.php"; ?>
    <main class="container">
        <h2>Crear Ticket</h2>
        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="POST">
            <input type="text" name="titulo" placeholder="Título" required>
            <textarea name="descripcion" placeholder="Descripción" required></textarea>
            <select name="prioridad">
                <option value="Baja">Baja</option>
                <option value="Media">Media</option>
                <option value="Alta">Alta</option>
            </select>
            <select name="area">
                <?php foreach ($areas as $a): if ($a['nombre_area'] === 'Usuarios') continue; ?>
                <option value="<?= $a['id_area'] ?>"><?= htmlspecialchars($a['nombre_area']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Crear</button>
        </form>
    </main>
    <?php include "../includes/footer.php"; ?>
</body>
</html>