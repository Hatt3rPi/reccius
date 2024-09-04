<?php
// archivo: pages\backend\acta_liberacion\carga_acta_liberacion_firmada.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

$response = [
    'success' => false,
    'message' => '',
    'campos' => []
];

try {
    // Validación y saneamiento del ID del análisis externo
    $id_liberacion = isset($_GET['id_actaLiberacion']) ? intval($_GET['id_actaLiberacion']) : 0;

    if ($id_liberacion === 0) {
        throw new Exception('ID de acta no válido.');
    }

    // Consulta para obtener los datos del análisis externo
    $query_liberacion = "SELECT
                                lib.*,
                                an.lote, 
                                an.tamano_lote,
                                an.codigo_mastersoft,
                                an.fecha_elaboracion,
                                an.condicion_almacenamiento,
                                an.fecha_vencimiento,
                                am.numero_acta as 'nro_actaMuestreo',
                                am.fecha_muestreo,
                                an.laboratorio,
                                an.numero_solicitud,
                                an.fecha_solicitud,
                                an.laboratorio_nro_analisis,
                                an.fecha_envio,
                                an.laboratorio_fecha_analisis,
                                an.id_cuarentena,
                                an.fecha_liberacion,
                                prod.id AS 'id_producto',
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
                                es.codigo_mastersoft as 'codigo_mastersoft_interno',
                                am.fecha_muestreo,
                                am.id AS id_actaMuestreo,
                                usr1.nombre as nombre_usr1, usr1.cargo as cargo_usr1, 
                                CASE
                                    WHEN usr1.qr_documento IS NOT NULL THEN usr1.qr_documento
                                    WHEN usr1.foto_firma IS NOT NULL THEN usr1.foto_firma
                                    ELSE 'https://pub-bde9ff3e851b4092bfe7076570692078.r2.dev/firma_no_proporcionada.webp'
                                END as foto_firma_usr1
                            FROM
                                calidad_acta_liberacion AS lib
                            left JOIN calidad_analisis_externo AS an ON lib.id_analisisExterno = an.id
                            left JOIN calidad_especificacion_productos AS es ON lib.id_especificacion=es.id_especificacion
                            left JOIN calidad_productos AS prod ON lib.id_producto = prod.id
                            left JOIN calidad_acta_muestreo AS am ON lib.id_actaMuestreo = am.id
                            left JOIN usuarios as usr1 ON lib.usuario_firma1=usr1.usuario
                            WHERE
                                lib.id = ?";

    // Preparar y ejecutar la consulta
    $stmtAnali = mysqli_prepare($link, $query_liberacion);
    if (!$stmtAnali) {
        throw new Exception("Error en mysqli_prepare (query_liberacion): " . mysqli_error($link));
    }

    mysqli_stmt_bind_param($stmtAnali, "i", $id_liberacion);
    mysqli_stmt_execute($stmtAnali);
    $resultAnali = mysqli_stmt_get_result($stmtAnali);

    $dataAnali = mysqli_fetch_all($resultAnali, MYSQLI_ASSOC);
    
    if (empty($dataAnali)) {
        throw new Exception('No se encontraron datos para el ID proporcionado.');
    }

    $response['campos'] = $dataAnali;
    $response['success'] = true;
    $response['message'] = 'Datos obtenidos correctamente';

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Enviar la respuesta en formato JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE);

mysqli_close($link);
?>
