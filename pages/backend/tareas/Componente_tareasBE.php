<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Verifica si el usuario está en la sesión
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['data' => []]);
    exit();
}

$usuario_ejecutor = $_SESSION['usuario'];

// Consulta para obtener las tareas relacionadas con el usuario en sesión
$query = "
    SELECT 
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
    WHERE a.usuario_ejecutor = ? AND a.estado = 'Activo'
    ORDER BY fecha_ingreso ASC
    LIMIT 5;
";

$stmt = $link->prepare($query);
$stmt->bind_param("s", $usuario_ejecutor);
$stmt->execute();
$result = $stmt->get_result();
$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$stmt->close();
$link->close();

header('Content-Type: application/json');
echo json_encode(['data' => $data]);
?>
