<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Consulta para obtener las tareas
$query = "  SELECT 
                a.id,
                DATE_FORMAT(a.fecha_ingreso, '%Y-%m-%d') as fecha_ingreso,
                DATE_FORMAT(a.fecha_vencimiento, '%Y-%m-%d') as fecha_vencimiento,
                DATE_FORMAT(a.fecha_done, '%Y-%m-%d') as fecha_done,
                b.nombre as usuario_creador,
                c.nombre as usuario_ejecutor,
                a.descripcion_tarea,
                a.estado,
                CASE prioridad 
                    WHEN '1' THEN 'Alta'
                    WHEN '2' THEN 'Media'
                    WHEN '3' THEN 'Baja'
                    ELSE 'Desconocida'
                END AS prioridad,
                a.id_relacion,
                a.tipo,
                a.tabla_relacion
            FROM tareas as a
            LEFT JOIN usuarios as b ON a.usuario_creador = b.usuario
            LEFT JOIN usuarios as c ON a.usuario_ejecutor = c.usuario
            ORDER BY a.id DESC;";

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
