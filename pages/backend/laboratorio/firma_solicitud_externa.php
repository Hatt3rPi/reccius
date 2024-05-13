<?php
session_start();
header('Content-Type: application/json');
require_once "/home/customw2/conexiones/config_reccius.php";

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
  echo json_encode(['exito' => false, 'mensaje' => 'Acceso denegado']);
  exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id_analisis_externo'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'Datos insuficientes']);
    exit;
}

$idAnalisisExterno = intval($input['id_analisis_externo']);
$usuario = $_SESSION['usuario'];
$fechaActual = date('Y-m-d');


// fecha_firma_revisor
// revisado_por

$queryAnalisisExterno = "SELECT revisado_por, fecha_firma_revisor, estado 
                          FROM  calidad_analisis_externo
                          WHERE id = ?";

$stmtAnali = mysqli_prepare($link, $queryAnalisisExterno);

if (!$stmtAnali) {
  http_response_code(500);
  echo json_encode(['exito' => false, 'mensaje' => 'Error en la preparación de la consulta']);
  exit;
}

mysqli_stmt_bind_param($stmtAnali, "i", $idAnalisisExterno);
mysqli_stmt_execute($stmtAnali);
$resultAnali = mysqli_stmt_get_result($stmtAnali);
$analisis = mysqli_fetch_assoc($resultAnali);
mysqli_stmt_close($stmtAnali);

if (!$analisis) {
  http_response_code(404);
  echo json_encode(['exito' => false, 'mensaje' => 'La solicitud de analisis externo no exite con el id: ' . $idAnalisisExterno]);
  exit;
}

$fechaFirmaRevisor = $analisis['fecha_firma_revisor'];
$revisadoPor = $analisis['revisado_por'];
$estado = $analisis['estado'];

if ($fechaFirmaRevisor !== null) {
  echo json_encode(['exito' => false, 'mensaje' => 'Este documento ya ha sido firmado']);
  exit;
}
if ($revisadoPor !== $usuario) {
  echo json_encode(['exito' => false, 'mensaje' => 'No puedes firmar esta solicitud de análisis externo, ya que no eres un revisor']);
  exit;
}

if ("En proceso de firmas" !== $estado) {
  echo json_encode(['exito' => false, 'mensaje' => 'No puedes firmar esta solicitud de análisis externo, ya que no está en "En proceso de firmas"']);
  exit;
}


$query = "UPDATE calidad_analisis_externo 
            SET fecha_revision = ?, estado='Pendiente envío a Laboratorio' 
            WHERE id = ?";

$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "si", $fechaActual, $idAnalisisExterno);
$exito = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if ($exito) {
  // finalizarTarea(
  //   $_SESSION['usuario'], 
  //   $idAnalisisExterno, 
  //   'calidad_analisis_externo'
  // );
  echo json_encode(['exito' => true, 'mensaje' => 'Documento firmado con éxito']);
} else {
  http_response_code(500);
  echo json_encode(['exito' => false, 'mensaje' => 'Error al firmar el documento']);
}
