<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}
$response = [
    'success' => false,
    'message' => '',
    'estados' => []
];

try {
    // Consulta para obtener el conteo de todos los estados, excluyendo los valores null
    $queryEstados = "
        SELECT estado, COUNT(*) as contador 
        FROM calidad_especificacion_productos 
        WHERE estado IS NOT NULL 
        GROUP BY estado;
    ";

    // Preparar y ejecutar la consulta
    $stmtEstados = mysqli_prepare($link, $queryEstados);
    if (!$stmtEstados) {
        throw new Exception("Error en mysqli_prepare (queryEstados): " . mysqli_error($link));
    }
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
