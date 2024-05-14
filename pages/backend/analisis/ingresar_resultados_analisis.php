<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Validación y saneamiento del ID del análisis externo
$id_acta = isset($_GET['id_acta']) ? intval($_GET['id_acta']) : 0;

$queryAnalisisExterno = "SELECT 
                            an.*,
                            prod.id AS prod_id,
                            prod.identificador_producto AS prod_identificador_producto, 
                            prod.nombre_producto AS prod_nombre_producto, 
                            prod.tipo_producto AS prod_tipo_producto, 
                            prod.tipo_concentracion AS prod_tipo_concentracion, 
                            prod.concentracion AS prod_concentracion, 
                            prod.formato AS prod_formato, 
                            prod.elaborado_por AS prod_elaborado_por,
                            es.id_especificacion AS es_id_especificacion, 
                            es.documento AS es_documento, 
                            es.version AS es_version, 
                            es.estado AS es_estado,
                            anali.id_analisis AS anali_id_analisis, 
                            anali.tipo_analisis AS anali_tipo_analisis, 
                            anali.metodologia AS anali_metodologia,
                            anali.descripcion_analisis AS anali_descripcion_analisis,
                            anali.criterios_aceptacion AS anali_criterios_aceptacion
                        FROM calidad_analisis_externo AS an
                        JOIN calidad_especificacion_productos AS es ON an.id_especificacion = es.id_especificacion
                        JOIN calidad_productos AS prod ON es.id_producto = prod.id
                        JOIN calidad_analisis AS anali ON es.id_especificacion = anali.id_especificacion_producto
                        WHERE an.id = ?";

$queryActaMuestreo = "SELECT * FROM calidad_acta_muestreo WHERE id_analisisExterno= ?";

// queryAnalisisExterno
$stmtAnali = mysqli_prepare($link, $queryAnalisisExterno);
if (!$stmtAnali) {
    die("Error en mysqli_prepare (queryAnalisisExterno): " . mysqli_error($link));
}
mysqli_stmt_bind_param($stmtAnali, "i", $id_acta);
mysqli_stmt_execute($stmtAnali);

$productos = [];
$resultAnali = mysqli_stmt_get_result($stmtAnali);
while ($row = mysqli_fetch_assoc($resultAnali)) {
    $producto_id = $row['prod_id'];
    $especificacion_id = $row['es_id_especificacion'];

    // Si el producto no está en el arreglo, agregarlo
    if (!isset($productos[$producto_id])) {
        $productos[$producto_id] = [
            'id_producto' => $producto_id,
            'identificador_producto' => $row['prod_identificador_producto'],
            'nombre_producto' => $row['prod_nombre_producto'],
            'tipo_producto' => $row['prod_tipo_producto'],
            'tipo_concentracion' => $row['prod_tipo_concentracion'],
            'concentracion' => $row['prod_concentracion'],
            'formato' => $row['prod_formato'],
            'elaborado_por' => $row['prod_elaborado_por'],
            'especificaciones' => []
        ];
    }

    // Si la especificación no está en el producto, agregarla
    if (!isset($productos[$producto_id]['especificaciones'][$especificacion_id])) {
        $productos[$producto_id]['especificaciones'][$especificacion_id] = [
            'id_especificacion' => $especificacion_id,
            'documento' => $row['es_documento'],
            'version' => $row['es_version'],
            'estado' => $row['es_estado'],
            'analisis' => []
        ];
    }

    // Agregar el análisis a la especificación
    $productos[$producto_id]['especificaciones'][$especificacion_id]['analisis'][] = [
        'id_analisis' => $row['anali_id_analisis'],
        'tipo_analisis' => $row['anali_tipo_analisis'],
        'metodologia' => $row['anali_metodologia'],
        'descripcion_analisis' => $row['anali_descripcion_analisis'],
        'criterios_aceptacion' => $row['anali_criterios_aceptacion']
    ];
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
$resultActaMuestreo = mysqli_stmt_get_result($stmtActaMuestreo);
while ($row = mysqli_fetch_assoc($resultActaMuestreo)) {
    $analisisActaMuestreo[] = $row;
}
mysqli_stmt_close($stmtActaMuestreo);

mysqli_close($link);

// Enviar los datos en formato JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['Acta_Muestreo' => $analisisActaMuestreo, 'productos' => array_values($productos)], JSON_UNESCAPED_UNICODE);
?>
