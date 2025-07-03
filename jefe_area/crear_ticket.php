<?php
require_once "../includes/auth.php";
require_once "../includes/csrf.php";
require_once "../config/db.php";
if (!es_admin() && !es_jefe_area()) { header("Location: ../dashboard.php"); exit; }

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $estado = $_POST['estado'];
    $usuario = $_SESSION['id_usuario'];
    $area    = $_SESSION['id_area'];
    if (!in_array($estado, ['Pendiente', 'En proceso', 'Realizado'])) {
        $error = 'Estado inválido';
    } else {
        $prioridad = 'Media';
        $stmt = $conn->prepare(
            "INSERT INTO Ticket (titulo, descripcion, prioridad, estado, id_usuario, id_area) VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $titulo, $descripcion, $prioridad, $estado, $usuario, $area
        ]);
    }
}
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
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="text" name="titulo" placeholder="Título" required>
            <textarea name="descripcion" placeholder="Descripción" required></textarea>
            <select name="estado">
                <option value="Pendiente">Pendiente</option>
                <option value="En proceso">En proceso</option>
                <option value="Realizado">Realizado</option>
            </select>
            <button type="submit">Crear</button>
        </form>
    </main>
    <?php include "../includes/footer.php"; ?>
</body>
</html>