<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

$response = [
    'success' => false,
    'message' => '',
    'data' => []
];

try {
    // Consulta con JOIN para obtener el tipo de producto agrupado por tipo_producto
    $query = "
        SELECT 
            cp.tipo_producto, 
            COUNT(*) as contador 
        FROM 
            calidad_productos_analizados cpa
        JOIN 
            calidad_productos cp 
        ON 
            cpa.id_producto = cp.id
        WHERE 
            cpa.estado IS NOT NULL 
        GROUP BY 
            cp.tipo_producto;
    ";

    // Preparar y ejecutar la consulta
    $stmt = mysqli_prepare($link, $query);
    if (!$stmt) {
        throw new Exception("Error en mysqli_prepare (query): " . mysqli_error($link));
    }
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $response['data'][] = [
            'tipo_producto' => $row['tipo_producto'],
            'contador' => $row['contador']
        ];
    }
    mysqli_stmt_close($stmt);

    mysqli_close($link);

    // Preparar la respuesta
    $response['success'] = true;

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Enviar los datos en formato JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
