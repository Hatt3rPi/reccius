<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

$response = [
    'success' => false,
    'message' => '',
    'data' => []
];

try {
    // Consulta para obtener los días entre fecha_in_cuarentena y fecha_out_cuarentena
    $query = "
        SELECT DATEDIFF(fecha_out_cuarentena, fecha_in_cuarentena) as dias
        FROM calidad_productos_analizados
        WHERE fecha_in_cuarentena IS NOT NULL AND fecha_out_cuarentena IS NOT NULL;
    ";

    $stmt = mysqli_prepare($link, $query);
    if (!$stmt) {
        throw new Exception("Error en mysqli_prepare (query): " . mysqli_error($link));
    }
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $totalDias = 0;
    $count = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        $totalDias += $row['dias'];
        $count++;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);

    if ($count > 0) {
        $promedioDias = $totalDias / $count;
        $response['data'] = [
            'promedioDias' => round($promedioDias, 2)
        ];
        $response['success'] = true;
    } else {
        $response['message'] = 'No se encontraron registros válidos para calcular el promedio.';
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Enviar los datos en formato JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
