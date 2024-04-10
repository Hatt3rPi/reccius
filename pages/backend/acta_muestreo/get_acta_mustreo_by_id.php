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


$query = mysqli_prepare($link, "SELECT 
                        mu.id as id ,	
                        mu.numero_registro as numero_registro ,	
                        mu.version_registro as version_registro ,	
                        mu.numero_acta as numero_acta ,	
                        mu.version_acta as version_acta ,	
                        mu.fecha_muestreo as fecha_muestreo ,	
                        mu.id_especificacion as id_especificacion ,	
                        mu.id_producto as id_producto ,	
                        mu.id_analisisExterno as id_analisisExterno ,	
                        mu.aux_autoincremental as aux_autoincremental ,	
                        mu.aux_anomes as aux_anomes ,	
                        mu.aux_tipo as aux_tipo ,	
                        mu.estado as estado ,	
                        mu.responsable as responsable ,	
                        mu.ejecutor as ejecutor ,	
                        mu.verificador as verificador ,	
                        mu.fecha_firma_responsable as fecha_firma_responsable ,	
                        mu.fecha_firma_ejecutor as fecha_firma_ejecutor ,	
                        mu.fecha_firma_verificador as fecha_firma_verificador,

                        pro.identificador_producto as prod_identificador,	
                        pro.nombre_producto as prod_nombre,	
                        pro.tipo_producto as prod_tipo,	
                        pro.tipo_concentracion as prod_tipo_concentracion,	
                        pro.concentracion as prod_concentracion,	
                        pro.formato as prod_formato,	
                        pro.elaborado_por as prod_elaborado_por,	
                        pro.pais_origen as prod_pais_origen,	
                        pro.documento_ingreso as prod_documento_ingreso,	
                        pro.proveedor as prod_proveedor


                        FROM calidad_acta_muestreo as mu 
                        JOIN calidad_productos as pro 
                        ON mu.id_producto = pro.id 
                        WHERE mu.id = ?");

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
