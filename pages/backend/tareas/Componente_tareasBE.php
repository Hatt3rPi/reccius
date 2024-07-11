<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Verifica si el usuario está en la sesión
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['data' => []]);
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Consulta para obtener las primeras 5 tareas más antiguas con estado "Activo" y relacionadas con el usuario
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
    WHERE estado = 'Activo' AND usuario_id = ?
    ORDER BY fecha_ingreso ASC
    LIMIT 5;
";

$stmt = $link->prepare($query);
$stmt->bind_param("i", $usuario_id);
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
