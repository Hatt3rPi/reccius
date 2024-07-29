<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

$response = [
    'success' => false,
    'message' => '',
    'approvedPercentage' => 0,
    'rejectedPercentage' => 0
];

try {
    // Consulta para obtener la cantidad de productos aprobados y rechazados
    $queryAprobados = "SELECT COUNT(*) as total FROM calidad_productos_analizados WHERE estado = 'Aprobado'";
    $queryRechazados = "SELECT COUNT(*) as total FROM calidad_productos_analizados WHERE estado = 'Rechazado'";

    $stmtAprobados = mysqli_prepare($link, $queryAprobados);
    if (!$stmtAprobados) {
        throw new Exception("Error en mysqli_prepare (queryAprobados): " . mysqli_error($link));
    }
    mysqli_stmt_execute($stmtAprobados);
    $resultAprobados = mysqli_stmt_get_result($stmtAprobados);
    $rowAprobados = mysqli_fetch_assoc($resultAprobados);

    $stmtRechazados = mysqli_prepare($link, $queryRechazados);
    if (!$stmtRechazados) {
        throw new Exception("Error en mysqli_prepare (queryRechazados): " . mysqli_error($link));
    }
    mysqli_stmt_execute($stmtRechazados);
    $resultRechazados = mysqli_stmt_get_result($stmtRechazados);
    $rowRechazados = mysqli_fetch_assoc($resultRechazados);

    $totalAprobados = $rowAprobados['total'];
    $totalRechazados = $rowRechazados['total'];
    $totalProductos = $totalAprobados + $totalRechazados;

    if ($totalProductos > 0) {
        $response['approvedPercentage'] = round(($totalAprobados / $totalProductos) * 100, 2);
        $response['rejectedPercentage'] = round(($totalRechazados / $totalProductos) * 100, 2);
        $response['success'] = true;
    } else {
        $response['message'] = 'No se encontraron registros.';
    }

    mysqli_stmt_close($stmtAprobados);
    mysqli_stmt_close($stmtRechazados);
    mysqli_close($link);

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Enviar los datos en formato JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
