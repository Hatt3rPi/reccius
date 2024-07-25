<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

$response = [
    'success' => false,
    'message' => '',
    'laboratorios' => []
];

try {
    // Consulta para obtener los laboratorios y el contador de cada uno
    $queryLaboratorios = "
        SELECT 
            laboratorio, 
            COUNT(*) as contador 
        FROM 
            calidad_analisis_externo 
        GROUP BY 
            laboratorio;
    ";

    // Preparar y ejecutar la consulta
    $stmtLaboratorios = mysqli_prepare($link, $queryLaboratorios);
    if (!$stmtLaboratorios) {
        throw new Exception("Error en mysqli_prepare (queryLaboratorios): " . mysqli_error($link));
    }
    mysqli_stmt_execute($stmtLaboratorios);

    $resultLaboratorios = mysqli_stmt_get_result($stmtLaboratorios);
    while ($row = mysqli_fetch_assoc($resultLaboratorios)) {
        $response['laboratorios'][] = [
            'laboratorio' => $row['laboratorio'],
            'contador' => $row['contador']
        ];
    }
    mysqli_stmt_close($stmtLaboratorios);

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
