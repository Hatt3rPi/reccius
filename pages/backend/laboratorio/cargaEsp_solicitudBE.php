<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
require_once "../otros/laboratorio.php";

// Validación y saneamiento del ID
$id_analisis_externo = isset($_GET['id_analisis_externo']) ? intval($_GET['id_analisis_externo']) : 0;
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';
$valor_buscado = 0;
$query = '';
$QA = [];

// Registro del paso de validación
$QA[] = "1 ".$accion;

if ($accion === 'prepararSolicitud') {
    $idEspecificacion = isset($_GET['idEspecificacion']) ? intval($_GET['idEspecificacion']) : 0;
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
    $valor_buscado = $idEspecificacion;
    $QA[] = "2 - $idEspecificacion";
} else if ($accion === 'revisar') {
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
        WHERE cep.id_especificacion = (SELECT id_especificacion FROM calidad_analisis_externo WHERE id = ?)";
    $valor_buscado = $id_analisis_externo;
    $QA[] = "3 - $id_analisis_externo";
}

if ($query !== '') {
    $stmt = mysqli_prepare($link, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $valor_buscado);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $QA[] = "4";
    } else {
        $QA[] = "5 " . mysqli_error($link);
        die("Error en la preparación de la consulta: " . mysqli_error($link));
    }
} else {
    $QA[] = "6";
    die("Acción no reconocida.");
}

$queryAnalisisExterno = "SELECT 
                            an.*,
                            prod.identificador_producto AS 'prod_identificador_producto', 
                            prod.nombre_producto AS 'prod_nombre_producto', 
                            prod.tipo_producto AS 'prod_tipo_producto', 
                            prod.tipo_concentracion AS 'prod_tipo_concentracion', 
                            prod.concentracion AS 'prod_concentracion', 
                            prod.formato AS 'prod_formato', 
                            prod.elaborado_por AS 'prod_elaborado_por',
                            ep.version AS 'version_especificacion',
                            ep.documento
                        FROM calidad_analisis_externo AS an
                        JOIN calidad_productos AS prod ON an.id_producto = prod.id
                        JOIN calidad_especificacion_productos AS ep ON an.id_especificacion = ep.id_especificacion
                        WHERE an.id = ?";

$queryAnalisisMany = "SELECT COUNT(*) AS analisis_externo_count FROM calidad_analisis_externo WHERE id_especificacion = (SELECT id_especificacion FROM calidad_analisis_externo WHERE id = ?)";


$total_analisis = 0;
if ($accion === 'prepararSolicitud' && $idEspecificacion !== 0) {
    $queryTotalAnalisis = "SELECT COUNT(*) AS total_analisis 
                       FROM calidad_analisis_externo 
                       WHERE fecha_solicitud IS NOT NULL 
                       AND fecha_solicitud >= DATE_FORMAT(CURDATE(), '%Y-%m-01')"; 
    $stmtTotalAnalisis = mysqli_prepare($link, $queryTotalAnalisis);
    if ($stmtTotalAnalisis) {
        mysqli_stmt_execute($stmtTotalAnalisis);
        $resultTotalAnalisis = mysqli_stmt_get_result($stmtTotalAnalisis);
        if ($rowTotalAnalisis = mysqli_fetch_assoc($resultTotalAnalisis)) {
            $total_analisis = $rowTotalAnalisis['total_analisis'];
        }
        mysqli_stmt_close($stmtTotalAnalisis);
        $QA[] = "13";
    } else {
        $QA[] = "14: " . mysqli_error($link);
        die("Error en la preparación de la consulta del total de análisis: " . mysqli_error($link));
    }
}

$total_analisis_producto = 0;
if ($accion === 'prepararSolicitud' && $idEspecificacion !== 0) {

    $queryTotalAnalisisProd = "SELECT COUNT(cae.id) AS total_analisis
            FROM calidad_analisis_externo cae
            JOIN calidad_especificacion_productos cep 
            ON cae.id_especificacion = cep.id_especificacion
            WHERE cep.id_especificacion = ?"; //todo
    $stmtTotalAnalisisProd = mysqli_prepare($link, $queryTotalAnalisisProd);
    mysqli_stmt_bind_param($stmtTotalAnalisisProd, "i", $idEspecificacion);
    if ($stmtTotalAnalisisProd) {
        mysqli_stmt_execute($stmtTotalAnalisisProd);
        $resultTotalAnalisisProd = mysqli_stmt_get_result($stmtTotalAnalisisProd);
        if ($rowTotalAnalisisProd = mysqli_fetch_assoc($resultTotalAnalisisProd)) {
            $total_analisis_producto = $rowTotalAnalisisProd['total_analisis'];
        }
        mysqli_stmt_close($stmtTotalAnalisisProd);
        $QA[] = "13";
    } else {
        $QA[] = "14: " . mysqli_error($link);
        die("Error en la preparación de la consulta del total de análisis: " . mysqli_error($link));
    }
}

$analisis = [];
$analisis_count = 0;
$productos = [];

if ($id_analisis_externo !== 0) {
    $stmtAnali = mysqli_prepare($link, $queryAnalisisExterno);
    if ($stmtAnali) {
        mysqli_stmt_bind_param($stmtAnali, "i", $id_analisis_externo);
        mysqli_stmt_execute($stmtAnali);
        $resultAnali = mysqli_stmt_get_result($stmtAnali);
        if ($rowAnali = mysqli_fetch_assoc($resultAnali)) {
            $analisis = $rowAnali;
        }
        mysqli_stmt_close($stmtAnali);
        $QA[] = "7";
    } else {
        $QA[] = "8: " . mysqli_error($link);
        die("Error en la preparación de la consulta de análisis externo: " . mysqli_error($link));
    }
}

$stmtAnaliCount = mysqli_prepare($link, $queryAnalisisMany);
if ($stmtAnaliCount) {
    mysqli_stmt_bind_param($stmtAnaliCount, "i", $id_analisis_externo);
    mysqli_stmt_execute($stmtAnaliCount);
    $resultAnaliCount = mysqli_stmt_get_result($stmtAnaliCount);
    if ($rowAnaliCount = mysqli_fetch_assoc($resultAnaliCount)) {
        $analisis_count = $rowAnaliCount['analisis_externo_count'];
    }
    mysqli_stmt_close($stmtAnaliCount);
    $QA[] = "9";
} else {
    $QA[] = "10: " . mysqli_error($link);
    die("Error en la preparación de la consulta de conteo de análisis: " . mysqli_error($link));
}

while ($row = mysqli_fetch_assoc($result)) {
    $producto_id = $row['id_producto'];
    $especificacion_id = $row['id_especificacion'];

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
        $QA[] = "11 - $producto_id agregado";
    }

    if (!isset($productos[$producto_id]['especificaciones'][$especificacion_id])) {
        $productos[$producto_id]['especificaciones'][$especificacion_id] = [
            'id_especificacion' => $especificacion_id,
            'documento' => $row['documento'],
            'version' => $row['version']
        ];
        $QA[] = "12 - ID $especificacion_id agregada al producto ID $producto_id";
    }
}

$laboratorio = new Laboratorio();
$labList = $laboratorio->findAll();

mysqli_stmt_close($stmt);
mysqli_close($link);

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'productos' => array_values($productos), 
    'analisis' => $analisis, 
    'count_analisis_externo' => $analisis_count,  
    'total_analisis' => $total_analisis, 
    'total_analisis_producto' => $total_analisis_producto,
    'laboratorios' => $labList,
    'pasos' => $QA
    ], JSON_UNESCAPED_UNICODE);

?>
