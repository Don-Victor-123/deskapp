<?php
require_once __DIR__ . '/../config/db.php';

try {
    // Remove duplicate areas (same nombre_area) keeping the lowest id
    $sqlArea = "DELETE a1 FROM Area a1
                INNER JOIN Area a2
                WHERE a1.nombre_area = a2.nombre_area
                AND a1.id_area > a2.id_area";
    $deletedArea = $conn->exec($sqlArea);

    // Remove duplicate users with same email (precaution)
    $sqlUser = "DELETE u1 FROM Usuario u1
                INNER JOIN Usuario u2
                WHERE u1.correo = u2.correo
                AND u1.id_usuario > u2.id_usuario";
    $deletedUser = $conn->exec($sqlUser);

    echo "Areas duplicadas eliminadas: {$deletedArea}\n";
    echo "Usuarios duplicados eliminados: {$deletedUser}\n";
} catch (Exception $e) {
    fwrite(STDERR, "Error: " . $e->getMessage() . "\n");
    exit(1);
}
