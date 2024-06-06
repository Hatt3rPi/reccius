<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
header('Content-Type: application/json');

$idAnalisisExterno = isset($_GET['id_analisis']) ? intval($_GET['id_analisis']) : null;


if ($idAnalisisExterno === null) {
    echo json_encode(['error' => 'ID de análisis no proporcionado.']);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);


if ($data === null) {
    echo json_encode(['error' => 'Datos inválidos o no proporcionados.']);
    exit;
}
if(
    $data['revision'] === null ||
    $data['laboratorio_nro_analisis'] === null ||
    $data['laboratorio_fecha_analisis'] === null ||
    $data['fecha_entrega'] === null
){
    echo json_encode(['error' => 'Datos inválidos o no proporcionados.']);
    exit;
}

//análisis de laboratorio ( BE  laboratorio_nro_analisis)
//Fecha análisis (BE laboratorio_fecha_analisis)
//Fecha recepción (BE fecha_entrega)

$consultaSQL = "UPDATE calidad_analisis_externo SET  
    revision_resultados_laboratorio = ? 
    laboratorio_nro_analisis ?
    laboratorio_fecha_analisis ?
    fecha_entrega = ?
    WHERE id = ?";

$stmt = mysqli_prepare($link, $consultaSQL);
if (!$stmt) {
    echo json_encode(['error' => 'Error al preparar consulta.']);
    exit;
}
mysqli_stmt_bind_param($stmt, 'ii', $data['revision'], $idAnalisisExterno);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if (mysqli_error($link)) {
    echo json_encode(['error' => 'Error al ejecutar consulta.']);
    exit;
}

echo json_encode(['exito' => true]);

?>
