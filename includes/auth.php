<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}
function es_admin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador';
}
function es_jefe_area() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'JefeArea';
}
?>