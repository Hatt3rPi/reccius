<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Validación y saneamiento del ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id_analisis_externo = isset($_GET['id_analisis_externo']) ? intval($_GET['id_analisis_externo']) : 0;


// Consulta para obtener los productos, especificaciones y análisis asociados
$query = "SELECT 
            cp.id as id_producto,
            cp.nombre_producto AS producto,
            cp.identificador_producto,
            cp.tipo_producto,
            cp.concentracion,
            cp.formato,
            cp.documento_ingreso as documento_producto,
            cp.elaborado_por, 
            cp.tipo_concentracion,
            cp.pais_origen,
            cp.proveedor,
            cep.id_especificacion,
            cep.documento,
            cep.fecha_expiracion,
            cep.fecha_edicion,
            cep.version
        FROM calidad_productos as cp 
        INNER JOIN calidad_especificacion_productos as cep ON cp.id = cep.id_producto 
        LEFT JOIN calidad_analisis as can ON cep.id_especificacion = can.id_especificacion_producto 
        WHERE cep.id_especificacion = ?";


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
                        JOIN calidad_productos AS prod 
                        ON an.id_producto = prod.id
                        WHERE an.id = ?";

$queryAnalisisMany = "SELECT COUNT(*) AS analisis_externo_count
                        FROM calidad_analisis_externo
                        WHERE id_especificacion = ?";

$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$analisis = [];
$analisis_count = [];
$productos = [];

if ($id_analisis_externo !== 0) {
    $stmtAnali = mysqli_prepare($link, $queryAnalisisExterno);
    mysqli_stmt_bind_param($stmtAnali, "i", $id_analisis_externo);
    mysqli_stmt_execute($stmtAnali);

    $resultAnali = mysqli_stmt_get_result($stmtAnali);
    if ($rowAnali = mysqli_fetch_assoc($resultAnali)) {
        $analisis = $rowAnali;
    }
    mysqli_stmt_close($stmtAnali);
}

//----------

$stmtAnaliCount = mysqli_prepare($link, $queryAnalisisMany);
mysqli_stmt_bind_param($stmtAnaliCount, "i", $id);
mysqli_stmt_execute($stmtAnaliCount);

$resultAnaliCount = mysqli_stmt_get_result($stmtAnaliCount);
if ($rowAnaliCount = mysqli_fetch_assoc($resultAnaliCount)) {
    $analisis_count = $rowAnaliCount['analisis_externo_count'];
}
mysqli_stmt_close($stmtAnaliCount);

//----------

while ($row = mysqli_fetch_assoc($result)) {
    $producto_id = $row['id_producto'];
    $especificacion_id = $row['id_especificacion'];

    // Si el producto no está en el arreglo, agregarlo
    if (!isset($productos[$producto_id])) {
        $productos[$producto_id] = [
            'id_producto' => $producto_id,
            'nombre_producto' => $row['producto'],
            'identificador_producto' => $row['identificador_producto'],
            'tipo_producto' => $row['tipo_producto'],
            'concentracion' => $row['concentracion'],
            'tipo_concentracion' => $row['tipo_concentracion'],
            'formato' => $row['formato'],
            'proveedor' => $row['proveedor'],
            'elaborado_por' => $row['elaborado_por'],
            'pais_origen' => $row['pais_origen'],
            'documento_producto' => $row['documento_producto'],
            'especificaciones' => []
        ];
    }

    // Si la especificación no está en el producto, agregarla
    if (!isset($productos[$producto_id]['especificaciones'][$especificacion_id])) {
        $productos[$producto_id]['especificaciones'][$especificacion_id] = [
            'id_especificacion' => $especificacion_id,
            'id' => $especificacion_id,
            'estado' => $row['estado'],
            'documento' => $row['documento'],
            'version' => $row['version']
        ];
    }


}

mysqli_close($link);

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['productos' => array_values($productos), 'analisis' => $analisis, 'count_analisis_externo'=> $analisis_count], JSON_UNESCAPED_UNICODE);

?>
 