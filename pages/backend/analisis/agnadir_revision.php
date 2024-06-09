<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
header('Content-Type: application/json');

$idAnalisisExterno = isset($_GET['id_analisis']) ? intval($_GET['id_analisis']) : null;

function limpiarDato($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

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
    !isset($data['resultados_analisis']) ||
    !isset($data['laboratorio_nro_analisis']) ||
    !isset($data['laboratorio_fecha_analisis']) ||
    !isset($data['fecha_entrega'])
){
    echo json_encode(['error' => 'Datos inválidos o no proporcionados.']);
    exit;
}

$checkQuery = "SELECT COUNT(*) as count FROM calidad_analisis_externo WHERE id = ? AND resultados_analisis IS NOT NULL";
$stmtCheck = mysqli_prepare($link, $checkQuery);
mysqli_stmt_bind_param($stmtCheck, 'i', $idAnalisisExterno);
mysqli_stmt_execute($stmtCheck);
$resultCheck = mysqli_stmt_get_result($stmtCheck);
$row = mysqli_fetch_assoc($resultCheck);

if ($row['count'] > 0) {
    echo json_encode(['error' => 'Ya existen resultados para este análisis.']);
    exit;
}
mysqli_stmt_close($stmtCheck);


$consultaSQL = "UPDATE calidad_analisis_externo SET  
    resultados_analisis = ? 
    laboratorio_nro_analisis ?
    laboratorio_fecha_analisis ?
    fecha_entrega = ?
    WHERE id = ?";

$stmt = mysqli_prepare($link, $consultaSQL);
if (!$stmt) {
    echo json_encode(['error' => 'Error al preparar consulta.']);
    exit;
}

$resultados_analisis = limpiarDato($data['resultados_analisis']);
$laboratorio_fecha_analisis = limpiarDato($data['laboratorio_fecha_analisis']); 
$laboratorio_nro_analisis = limpiarDato($data['laboratorio_nro_analisis']); 
$fecha_entrega = limpiarDato($data['fecha_entrega']);

mysqli_stmt_bind_param($stmt, 'ssssi', 
        $resultados_analisis, 
        $laboratorio_nro_analisis, 
        $laboratorio_fecha_analisis, 
        $fecha_entrega, 
        $idAnalisisExterno
    );
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if (mysqli_error($link)) {
    echo json_encode(['error' => 'Error al ejecutar consulta.']);
    exit;
}

echo json_encode(['exito' => true]);

?>