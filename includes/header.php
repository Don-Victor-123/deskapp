<!-- header.php -->
<?php
// Header global con navegación según rol
require_once __DIR__ . '/auth.php';
?>
<header class="main-header">
    <div class="header-container">
        <h1 class="logo">Sistema de Tickets</h1>
        <nav class="nav-main">
            <ul class="nav-list">
                <?php if (es_admin()): ?>
                    <li><a href="dashboard.php">Inicio</a></li>
                    <li><a href="admin/usuarios.php">Jefes de Área</a></li>
                    <li><a href="admin/tickets.php">Todos los Tickets</a></li>
                <?php elseif (es_jefe_area()): ?>
                    <li><a href="dashboard.php">Inicio</a></li>
                    <li><a href="jefe_area/crear_ticket.php">Crear Ticket</a></li>
                    <li><a href="jefe_area/ver_tickets.php">Mis Tickets</a></li>
                <?php else: ?>
                    <li><a href="dashboard.php">Inicio</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
    </div>
</header>