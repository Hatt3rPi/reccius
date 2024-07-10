<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Consulta para obtener las primeras 5 tareas más antiguas con estado "Activo"
$query = "
    SELECT 
        CASE prioridad 
            WHEN '1' THEN 'Alta'
            WHEN '2' THEN 'Media'
            WHEN '3' THEN 'Baja'
            ELSE 'Desconocida'
        END AS prioridad,
        descripcion_tarea,
        DATE_FORMAT(fecha_vencimiento, '%Y-%m-%d') as fecha_vencimiento
    FROM tareas 
    WHERE estado = 'Activo'
    ORDER BY fecha_ingreso ASC
    LIMIT 5;
";

$result = $link->query($query);
$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$link->close();

header('Content-Type: application/json');
echo json_encode(['data' => $data]);
?>
