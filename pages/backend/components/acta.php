<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

$response = [
    'success' => false,
    'message' => '',
    'estados' => []
];

try {
    $query = "SELECT estado, COUNT(*) as contador FROM calidad_acta_muestreo WHERE estado IS NOT NULL GROUP BY estado";
    $stmt = mysqli_prepare($link, $query);
    if (!$stmt) {
        throw new Exception("Error en mysqli_prepare (query): " . mysqli_error($link));
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $response['estados'][] = $row;
    }

    $response['success'] = true;
    mysqli_stmt_close($stmt);
    mysqli_close($link);

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Enviar los datos en formato JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
