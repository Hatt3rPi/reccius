<?php
//archivo: pages\backend\acta_liberacion\extrae_informe.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Validación y saneamiento del ID
$id_analisisExterno = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Verificación de que el ID es válido
if ($id_analisisExterno <= 0) {
    http_response_code(400); // Código de estado 400 Bad Request
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

// Consulta para obtener las especificaciones de productos
$query = "SELECT id, url_certificado_de_analisis_externo FROM `calidad_analisis_externo` WHERE id=?";

$stmt = mysqli_prepare($link, $query);
if (!$stmt) {
    http_response_code(500); // Código de estado 500 Internal Server Error
    echo json_encode(['error' => 'Error en la preparación de la consulta']);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $id_analisisExterno);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$analisis_externo = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);
mysqli_close($link);

if (!$analisis_externo) {
    http_response_code(404); // Código de estado 404 Not Found
    echo json_encode(['error' => 'Análisis externo no encontrado']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($analisis_externo, JSON_UNESCAPED_UNICODE);
?>
