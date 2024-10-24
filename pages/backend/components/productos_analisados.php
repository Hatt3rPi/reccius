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
    'data' => []
];

try {
    // Consulta para obtener el conteo de los diferentes estados en la tabla calidad_productos_analizados
    $query = "
        SELECT estado, COUNT(*) as contador 
        FROM calidad_productos_analizados 
        WHERE estado IS NOT NULL 
        GROUP BY estado;
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
            'estado' => $row['estado'],
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
