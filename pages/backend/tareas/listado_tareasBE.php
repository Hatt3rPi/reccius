<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Consulta para obtener las tareas
$query = "SELECT 
            id,
            fecha_ingreso,
            fecha_vencimiento,
            fecha_done,
            usuario_creador,
            usuario_ejecutor,
            descripcion_tarea,
            estado,
            prioridad
          FROM tareas
          ORDER BY fecha_ingreso DESC;";

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
