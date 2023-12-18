<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Validación y saneamiento del ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta para obtener los productos, especificaciones y análisis asociados
$query = "SELECT 
            cp.id as id_producto,
            cep.id_especificacion,
            cep.estado, 
            cep.documento, 
            cp.nombre_producto AS producto, 
            cp.tipo_producto, 
            cp.concentracion, 
            cp.formato, 
            cp.elaborado_por, 
            cep.fecha_expiracion,
            can.id_analisis, 
            can.tipo_analisis, 
            can.descripcion_analisis, 
            can.metodologia, 
            can.criterios_aceptacion 
        FROM calidad_productos as cp
        INNER JOIN calidad_especificacion_productos as cep ON cp.id = cep.id_producto
        LEFT JOIN calidad_analisis as can ON cep.id_especificacion = can.id_especificacion_producto
        WHERE cp.id = ?";

$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data = [];
$especificaciones = [];

while ($row = mysqli_fetch_assoc($result)) {
    // Agrupar por especificación
    $especificacion_id = $row['especificacion_id'];
    if (!isset($especificaciones[$especificacion_id])) {
        $especificaciones[$especificacion_id] = [
            'especificacion' => array_intersect_key($row, array_flip(['estado', 'documento', 'fecha_expiracion'])),
            'analisis' => []
        ];
    }
    // Agregar análisis a la especificación correspondiente
    if ($row['id_analisis']) {
        $especificaciones[$especificacion_id]['analisis'][] = array_intersect_key($row, array_flip(['id_analisis', 'tipo_analisis', 'descripcion_analisis', 'metodologia', 'criterios_aceptacion']));
    }
}

// Reorganizar los datos para la salida
foreach ($especificaciones as $espec) {
    $data[] = $espec;
}

mysqli_stmt_close($stmt);
mysqli_close($link);

header('Content-Type: application/json');
echo json_encode(['data' => $data]);
?>
