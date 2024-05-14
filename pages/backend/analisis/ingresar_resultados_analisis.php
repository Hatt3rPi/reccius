<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Validación y saneamiento del ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta para obtener los productos, especificaciones y análisis asociados
$queryAnalisisExterno = "SELECT 
                            an.*,
                            prod.identificador_producto AS 'prod_identificador_producto', 
                            prod.nombre_producto AS 'prod_nombre_producto', 
                            prod.tipo_producto AS 'prod_tipo_producto', 
                            prod.tipo_concentracion AS 'prod_tipo_concentracion', 
                            prod.concentracion AS 'prod_concentracion', 
                            prod.formato AS 'prod_formato', 
                            prod.elaborado_por AS 'prod_elaborado_por',
                            cep.id_especificacion AS 'cep_id_especificacion',
                            cep.version AS 'cep_version',
                            cep.vigencia AS 'cep_vigencia',
                            cep.creado_por AS 'cep_creado_por',
                            cep.revisado_por AS 'cep_revisado_por',
                            cep.aprobado_por AS 'cep_aprobado_por'
                        FROM calidad_analisis_externo AS an
                        JOIN calidad_productos AS prod ON an.id_producto = prod.id
                        JOIN calidad_especificacion_productos AS cep ON cep.id_producto = prod.id
                        WHERE an.id = ?";

$stmt = mysqli_prepare($link, $queryAnalisisExterno);
if (!$stmt) {
    die('Error en la preparación de la consulta: ' . mysqli_error($link));
}
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$productos = [];

while ($row = mysqli_fetch_assoc($result)) {
    // Procesamiento de resultados
}

mysqli_stmt_close($stmt);
mysqli_close($link);

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['productos' => array_values($productos)], JSON_UNESCAPED_UNICODE);
?>
