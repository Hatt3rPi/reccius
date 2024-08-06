<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

$response = [
    'success' => false,
    'message' => '',
    'totalProductosAnalizados' => 0
];

try {
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    $query = "SELECT COUNT(*) as total FROM calidad_productos_analizados WHERE fecha_in_cuarentena IS NOT NULL AND fecha_out_cuarentena IS NOT NULL";

    if ($filter === 'month') {
        $query .= " AND fecha_out_cuarentena >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
    } elseif ($filter === 'week') {
        $query .= " AND fecha_out_cuarentena >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)";
    }

    $stmt = mysqli_prepare($link, $query);
    if (!$stmt) {
        throw new Exception("Error en mysqli_prepare (query): " . mysqli_error($link));
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $response['totalProductosAnalizados'] = $row['total'];
        $response['success'] = true;
    } else {
        $response['message'] = 'No se encontraron registros.';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Enviar los datos en formato JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
