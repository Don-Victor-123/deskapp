<!-- header.php -->
<?php
// Header global con navegación según rol
require_once __DIR__ . '/auth.php';

// Ajustar rutas para enlaces cuando se incluye desde subcarpetas
$prefix = '';
if (strpos($_SERVER['PHP_SELF'], '/admin/') !== false ||
    strpos($_SERVER['PHP_SELF'], '/jefe_area/') !== false) {
    $prefix = '../';
}
?>
<header class="main-header">
    <div class="header-container">
        <h1 class="logo">Sistema de Tickets</h1>
        <nav class="nav-main">
            <ul class="nav-list">
                <?php if (es_admin()): ?>
                    <li><a href="<?= $prefix ?>dashboard.php">Inicio</a></li>
                    <li><a href="<?= $prefix ?>admin/usuarios.php">Usuarios</a></li>
                    <li><a href="<?= $prefix ?>admin/tickets.php">Todos los Tickets</a></li>
                <?php elseif (es_jefe_area()): ?>
                    <li><a href="<?= $prefix ?>dashboard.php">Inicio</a></li>
                    <li><a href="<?= $prefix ?>jefe_area/crear_ticket.php">Crear Ticket</a></li>
                    <li><a href="<?= $prefix ?>jefe_area/ver_tickets.php">Mis Tickets</a></li>
                    <!-- <li><a href="<?= $prefix ?>jefe_area/usuarios.php">Usuarios</a></li> -->
                    <li><a href="<?= $prefix ?>coming_soon/usuarios.php">Usuarios</a></li>
                <?php else: ?>
                    <li><a href="<?= $prefix ?>dashboard.php">Inicio</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <a href="<?= $prefix ?>logout.php" class="btn-logout">Cerrar Sesión</a>
    </div>
</header>

