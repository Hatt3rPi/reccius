<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Validación y saneamiento del ID del análisis externo
$id_acta = isset($_GET['id_acta']) ? intval($_GET['id_acta']) : 0;

$queryAnalisisExterno = "SELECT 
                            an.*,
                            prod.identificador_producto AS 'prod_identificador_producto', 
                            prod.nombre_producto AS 'prod_nombre_producto', 
                            prod.tipo_producto AS 'prod_tipo_producto', 
                            prod.tipo_concentracion AS 'prod_tipo_concentracion', 
                            prod.concentracion AS 'prod_concentracion', 
                            prod.formato AS 'prod_formato', 
                            prod.elaborado_por AS 'prod_elaborado_por'
                            
                        FROM calidad_analisis_externo AS an
                        JOIN calidad_productos AS prod ON an.id_producto = prod.id
                        WHERE an.id = ?";

$queryActaMuestreo = "SELECT * FROM calidad_acta_muestreo WHERE id_analisisExterno= ?";

//queryAnalisisExterno
$stmtAnali = mysqli_prepare($link, $queryAnalisisExterno);
mysqli_stmt_bind_param($stmtAnali, "i", $id_acta);
mysqli_stmt_execute($stmtAnali);

$analisis = [];

$resultAnali = mysqli_stmt_get_result($stmtAnali);
if ($rowAnali = mysqli_fetch_assoc($resultAnali)) {
    $analisis = $rowAnali;
}
mysqli_stmt_close($stmtAnali);


//queryActaMuestreo
$analisisActaMuestreo = [];

$stmtActaMuestreo = mysqli_prepare($link, $queryActaMuestreo);
mysqli_stmt_bind_param($stmtActaMuestreo, "i", $id_acta);
mysqli_stmt_execute($stmtActaMuestreo);

$resultActaMuestreo = mysqli_stmt_get_result($stmtActaMuestreo);
while ($row = mysqli_fetch_assoc($resultActaMuestreo)) {
    $analisisActaMuestreo[] = $row;

}

mysqli_stmt_close($stmtActaMuestreo);


mysqli_close($link);

// Enviar los datos en formato JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['Acta_Muestreo' => array_values($analisisActaMuestreo), 'analisis' => $analisis], JSON_UNESCAPED_UNICODE);

?>
 