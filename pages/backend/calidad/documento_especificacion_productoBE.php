<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Validación y saneamiento del ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta para obtener los productos, especificaciones y análisis asociados
$query = "SELECT cp.id as id_producto,
            CONCAT(cp.nombre_producto, ' ', cp.concentracion, ' - ', cp.formato) AS producto,
            cp.tipo_producto,
            cp.documento_ingreso as documento_producto,
            cp.elaborado_por, 
            cp.pais_origen,
            cep.version,
            concat(cep.vigencia, ' años') as vigencia,
            cep.id_especificacion,
            cep.creado_por,
            cep.revisado_por,
            cep.aprobado_por,
            usrCreado.nombre as usrCreado_nombre,
            usrCreado.nombre_corto as usrCreado_nombre_corto,
            usrCreado.ruta_registroPrestadoresSalud as usrCreado_ruta_registroPrestadoresSalud,
            usrCreado.cargo as usrCreado_cargo,
            usrRevisado.nombre as usrRevisado_nombre,
            usrRevisado.nombre_corto as usrRevisado_nombre_corto,
            usrRevisado.ruta_registroPrestadoresSalud as usrRevisado_ruta_registroPrestadoresSalud,
            usrRevisado.cargo as usrRevisado_cargo,
            usrAprobado.nombre as usrAprobado_nombre,
            usrAprobado.nombre_corto as usrAprobado_nombre_corto,
            usrAprobado.ruta_registroPrestadoresSalud as usrAprobado_ruta_registroPrestadoresSalud,
            usrAprobado.cargo as usrAprobado_cargo,
            DATE_FORMAT(cep.fecha_revision, '%d/%m/%Y') as fecha_revision,
            DATE_FORMAT(cep.fecha_aprobacion, '%d/%m/%Y') as fecha_aprobacion,
            DATE_FORMAT(cep.fecha_expiracion, '%d/%m/%Y') as fecha_expiracion,
            DATE_FORMAT(cep.fecha_edicion, '%d/%m/%Y') as fecha_edicion,
            can.id_analisis,
            can.tipo_analisis,
            can.descripcion_analisis,
            can.metodologia, 
            can.criterios_aceptacion
        FROM calidad_productos as cp 
        INNER JOIN calidad_especificacion_productos as cep 
        ON cp.id = cep.id_producto 
        LEFT JOIN calidad_analisis as can 
        ON cep.id_especificacion = can.id_especificacion_producto
        LEFT JOIN  usuarios as usrCreado
        ON usrCreado.usuario = cep.creado_por
        LEFT JOIN usuarios as usrRevisado
        ON usrRevisado.usuario = cep.revisado_por
        LEFT JOIN usuarios as usrAprobado
        ON usrAprobado.usuario = cep.aprobado_por 
        WHERE cep.id_especificacion = ?";

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
            'id' => $producto_id,
            'nombre_producto' => $row['producto'],
            'tipo_producto' => $row['tipo_producto'],
            'formato' => $row['formato'],
            'documento_producto' => $row['documento_producto'],
            'elaborado_por' => $row['elaborado_por'],
            'pais_origen' => $row['elaborado_por'],
            'especificaciones' => []
        ];
    }


    // Si la especificación no está en el producto, agregarla
    if (!isset($productos[$producto_id]['especificaciones'][$especificacion_id])) {
        $productos[$producto_id]['especificaciones'][$especificacion_id] = [
            'id' => $especificacion_id,
            'version' => $row['version'],
            'vigencia' => $row['vigencia'],
            'creado_por' => [
                'nombre' => $row['usrCreado_nombre'],
                'nombre_corto' => $row['usrCreado_nombre_corto'],
                'ruta_registro' => $row['usrCreado_ruta_registroPrestadoresSalud'],
                'cargo' => $row['usrCreado_cargo']
            ],
            'revisado_por' => [
                'fecha_revision' => $row['fecha_revision'],
                'nombre' => $row['usrRevisado_nombre'],
                'nombre_corto' => $row['usrRevisado_nombre_corto'],
                'ruta_registro' => $row['usrRevisado_ruta_registroPrestadoresSalud'],
                'cargo' => $row['usrRevisado_cargo']
            ],
            'aprobado_por' => [
                'fecha_aprobacion' => $row['fecha_aprobacion'],
                'nombre' => $row['usrAprobado_nombre'],
                'nombre_corto' => $row['usrAprobado_nombre_corto'],
                'ruta_registro' => $row['usrAprobado_ruta_registroPrestadoresSalud'],
                'cargo' => $row['usrAprobado_cargo']
            ],
            'fecha_expiracion' => $row['fecha_expiracion'],
            'fecha_edicion' => $row['fecha_edicion'],
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
