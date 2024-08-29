<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Validación y saneamiento del ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta para obtener los productos, especificaciones y análisis asociados
$query = "SELECT cp.id as id_producto,
            cp.nombre_producto AS producto,
            cp.identificador_producto,
            cp.tipo_producto,
            cp.concentracion,
            cp.formato,
            cp.documento_ingreso  as documento_producto,
            cp.tipo_concentracion,
            cep.id_especificacion,
            cep.estado, 
            cep.documento,
            cep.fecha_expiracion,
            cep.fecha_edicion,
            cep.version,
            cep.vigencia,
            cep.codigo_mastersoft,
            usrCreado.usuario,
            usrCreado.nombre,
            cep.revisado_por,
            cep.aprobado_por,
            can.id_analisis,
            can.tipo_analisis,
            can.descripcion_analisis,
            can.metodologia, 
            can.criterios_aceptacion
        FROM calidad_productos as cp INNER JOIN calidad_especificacion_productos as cep 
        ON cp.id = cep.id_producto 
        LEFT JOIN calidad_analisis as can 
        ON cep.id_especificacion = can.id_especificacion_producto 
        LEFT JOIN  usuarios as usrCreado
        ON usrCreado.usuario = cep.creado_por
        WHERE cep.id_especificacion= ?";

$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$productos = [];

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
            'fecha_expiracion' => $row['fecha_expiracion'],
            'fecha_edicion' => $row['fecha_edicion'],
            'codigo_mastersoft' => $row['codigo_mastersoft'],
            'version' => $row['version'],
            'vigencia' => $row['vigencia'],
            'usuario' => $row['usuario'],
            'nombre' => $row['nombre'],
            'revisado_por' => $row['revisado_por'],
            'aprobado_por' => $row['aprobado_por'],
            'analisis' => []
        ];
    }

    // Agregar análisis a la especificación
    if ($row['id_analisis']) {
        $productos[$producto_id]['especificaciones'][$especificacion_id]['analisis'][] = [
            'id_analisis' => $row['id_analisis'],
            'tipo_analisis' => $row['tipo_analisis'],
            'descripcion_analisis' => $row['descripcion_analisis'],
            'metodologia' => $row['metodologia'],
            'criterios_aceptacion' => nl2br(html_entity_decode($row['criterios_aceptacion'], ENT_QUOTES, 'UTF-8'))
        ];
    }
}

mysqli_stmt_close($stmt);
mysqli_close($link);

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['productos' => array_values($productos)], JSON_UNESCAPED_UNICODE);

?>
