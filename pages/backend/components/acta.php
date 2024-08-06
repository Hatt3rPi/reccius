<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

$response = [
    'success' => false,
    'message' => '',
    'estados' => []
];

try {
    // Estados específicos que necesitas
    $estadosPermitidos = ['Vigente', 'En Proceso de Firma', 'Pendiente Muestreo', 'Especificación obsoleta', 'Expirado'];

    // Preparar consulta con los estados específicos
    $placeholders = implode(',', array_fill(0, count($estadosPermitidos), '?'));
    $queryEstados = "
        SELECT estado, COUNT(*) as contador 
        FROM calidad_acta_muestreo 
        WHERE estado IN ($placeholders)
        GROUP BY estado;
    ";

    // Preparar y ejecutar la consulta
    $stmtEstados = mysqli_prepare($link, $queryEstados);
    if (!$stmtEstados) {
        throw new Exception("Error en mysqli_prepare (queryEstados): " . mysqli_error($link));
    }
    mysqli_stmt_bind_param($stmtEstados, str_repeat('s', count($estadosPermitidos)), ...$estadosPermitidos);
    mysqli_stmt_execute($stmtEstados);

    $resultEstados = mysqli_stmt_get_result($stmtEstados);
    while ($row = mysqli_fetch_assoc($resultEstados)) {
        $response['estados'][] = [
            'estado' => $row['estado'],
            'contador' => $row['contador']
        ];
    }
    mysqli_stmt_close($stmtEstados);

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
