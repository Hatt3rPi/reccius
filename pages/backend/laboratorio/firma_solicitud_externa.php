<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Verificación básica para asegurarse de que el usuario está autenticado y los datos necesarios están presentes
if (!isset($_SESSION['usuario']) || 
    empty($_SESSION['usuario']) || 
    !isset($_POST['id_analisis_externo'])) 
{
    exit('Acceso denegado o datos insuficientes');
}
$idAnalisisExterno = intval($_POST['id_analisis_externo']);
$usuario = $_SESSION['usuario'];
$fechaActual = date('Y-m-d');


// fecha_firma_revisor
// revisado_por

$queryAnalisisExterno = "SELECT revisado_por, fecha_firma_revisor, estado 
                          FROM  calidad_solicitudes_externas
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

if ($fechaFirmaRevisor !== null){
  echo json_encode(['exito' => false, 'mensaje' => 'Este documento ya ha sido firmado']);
  exit;
}
if ($revisadoPor !== $usuario){
  echo json_encode(['exito' => false, 'mensaje' => 'No puedes firmar esta solicitud de analisis externo']);
  exit;
}

if ("" !== $estado){
  echo json_encode(['exito' => false, 'mensaje' => 'No puedes firmar esta solicitud de analisis externo']);
  exit;
}


$query = "UPDATE calidad_solicitudes_externas 
            SET fecha_revision = ?, estado='Pendiente de Aprobación' 
            WHERE id_especificacion = ?";

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
  echo json_encode(['exito' => false, 'mensaje' => 'Error al firmar el documento']);
}
?>
