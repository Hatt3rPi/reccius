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
                            prod.elaborado_por AS 'prod_elaborado_por',
                            es.id_especificacion AS 'es_id_especificacion', 
                            es.documento AS 'es_documento', 
                            es.version AS 'es_version', 
                            anali.id_analisis AS 'anali_id_analisis', 
                            anali.tipo_analisis AS 'anali_tipo_analisis', 
                            anali.metodologia AS 'anali_metodologia',
                            anali.descripcion_analisis AS 'anali_descripcion_analisis',
                            anali.criterios_aceptacion AS 'anali_criterios_aceptacion',
                            am.fecha_muestreo,
                            am.numero_acta
                        FROM calidad_analisis_externo AS an
                        JOIN calidad_especificacion_productos AS es ON an.id_especificacion = es.id_especificacion
                        JOIN calidad_productos AS prod ON es.id_producto = prod.id
                        JOIN calidad_analisis AS anali ON es.id_especificacion = anali.id_especificacion_producto
                        JOIN calidad_acta_muestreo as am on an.id=am.id_analisisExterno and am.estado='vigente'
                        WHERE an.id = ?;";

$queryActaMuestreo = "SELECT * FROM calidad_acta_muestreo WHERE id_analisisExterno = ? AND estado = 'Vigente'";

// queryAnalisisExterno
$stmtAnali = mysqli_prepare($link, $queryAnalisisExterno);
if (!$stmtAnali) {
    die("Error en mysqli_prepare (queryAnalisisExterno): " . mysqli_error($link));
}
mysqli_stmt_bind_param($stmtAnali, "i", $id_acta);
mysqli_stmt_execute($stmtAnali);

$analisis = [];
$analiDatos = [];
$seenAnalisis = [];
$resultAnali = mysqli_stmt_get_result($stmtAnali);
while ($rowAnali = mysqli_fetch_assoc($resultAnali)) {
    $filteredRow = [];
    $analiItem = [];

    foreach ($rowAnali as $key => $value) {
        if (strpos($key, 'anali_') === 0) {
            $analiItem[$key] = $value;
        } else {
            $filteredRow[$key] = $value;
        }
    }

    // Use the 'id' field to check for duplicates
    if (!isset($seenAnalisis[$rowAnali['id']])) {
        $analisis[] = $filteredRow;
        $seenAnalisis[$rowAnali['id']] = true;
    }

    if (!empty($analiItem)) {
        $analiDatos[] = $analiItem;
    }
}
mysqli_stmt_close($stmtAnali);

// queryActaMuestreo
$analisisActaMuestreo = [];

$stmtActaMuestreo = mysqli_prepare($link, $queryActaMuestreo);
if (!$stmtActaMuestreo) {
    die("Error en mysqli_prepare (queryActaMuestreo): " . mysqli_error($link));
}
mysqli_stmt_bind_param($stmtActaMuestreo, "i", $id_acta);
if (!mysqli_stmt_execute($stmtActaMuestreo)) {
    die("Error en mysqli_stmt_execute (queryActaMuestreo): " . mysqli_stmt_error($stmtActaMuestreo));
}
mysqli_stmt_execute($stmtActaMuestreo);

$resultActaMuestreo = mysqli_stmt_get_result($stmtActaMuestreo);
while ($row = mysqli_fetch_assoc($resultActaMuestreo)) {
    $analisisActaMuestreo[] = $row;
}

mysqli_stmt_close($stmtActaMuestreo);

mysqli_close($link);

// Enviar los datos en formato JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'Acta_Muestreo' => array_values($analisisActaMuestreo), 
    'analisis' => $analisis,
    'analiDatos' => $analiDatos,
    'id_analisis_externo' => $id_acta
], JSON_UNESCAPED_UNICODE);
?>
