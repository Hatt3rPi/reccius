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
                            anali.criterios_aceptacion AS 'anali_criterios_aceptacion'
                        FROM calidad_analisis_externo AS an
                        JOIN calidad_especificacion_productos AS es ON an.id_especificacion = es.id_especificacion
                        JOIN calidad_productos AS prod ON es.id_producto = prod.id
                        JOIN calidad_analisis AS anali ON es.id_especificacion = anali.id_especificacion_producto
                        WHERE an.id = ?";

$queryUltimaActaMuestreo = "SELECT * FROM calidad_acta_muestreo 
                            WHERE id_analisisExterno = ? 
                            ORDER BY fecha_muestreo DESC 
                            LIMIT 1";

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

// queryUltimaActaMuestreo
$ultimaActaMuestreo = [];

$stmtUltimaActaMuestreo = mysqli_prepare($link, $queryUltimaActaMuestreo);
if (!$stmtUltimaActaMuestreo) {
    die("Error en mysqli_prepare (queryUltimaActaMuestreo): " . mysqli_error($link));
}
mysqli_stmt_bind_param($stmtUltimaActaMuestreo, "i", $id_acta);
if (!mysqli_stmt_execute($stmtUltimaActaMuestreo)) {
    die("Error en mysqli_stmt_execute (queryUltimaActaMuestreo): " . mysqli_stmt_error($stmtUltimaActaMuestreo));
}
mysqli_stmt_execute($stmtUltimaActaMuestreo);

$resultUltimaActaMuestreo = mysqli_stmt_get_result($stmtUltimaActaMuestreo);
while ($row = mysqli_fetch_assoc($resultUltimaActaMuestreo)) {
    $ultimaActaMuestreo[] = $row;
}

mysqli_stmt_close($stmtUltimaActaMuestreo);

mysqli_close($link);

// Enviar los datos en formato JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'Acta_Muestreo' => array_values($ultimaActaMuestreo), 
    'analisis' => $analisis,
    'analiDatos' => $analiDatos
], JSON_UNESCAPED_UNICODE);
?>
