<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
header('Content-Type: application/json');

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    http_response_code(403); 
    echo json_encode(['error' => 'Acceso denegado']);
    exit;
}
$id_a_buscar = isset($_GET['id']) ? mysqli_real_escape_string($link, $_GET['id']) : '';


$query = mysqli_prepare($link,"SELECT * FROM calidad_acta_muestreo WHERE id = ?");

/* 
Columnas:
  id	
  numero_registro	
  version_registro	
  numero_acta	
  version_acta	
  fecha_muestreo	
  id_especificacion	
  id_producto	
  id_analisisExterno	
  aux_autoincremental	
  aux_anomes	
  aux_tipo	
  estado	
  responsable	
  ejecutor	
  verificador	
  fecha_firma_responsable	
  fecha_firma_ejecutor	
  fecha_firma_verificador
*/

mysqli_stmt_bind_param($query, 's', $id_a_buscar);
mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);
$row = mysqli_fetch_assoc($result);

if ($row) {
    echo json_encode($row);
} else {
    // Manejar el caso de que no se encuentren resultados
    echo json_encode(['error' => 'No se encontró el registro.']);
    exit;
}

?>