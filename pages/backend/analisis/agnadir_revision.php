<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
require_once "../cloud/R2_manager.php";
header('Content-Type: application/json');

$idAnalisisExterno = isset($_GET['id_analisis']) ? intval($_GET['id_analisis']) : null;

function limpiarDato($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

if ($idAnalisisExterno === null) {
    echo json_encode(['error' => 'ID de análisis no proporcionado.']);
    exit;
}

if (!isset($_FILES['certificado_de_analisis_externo']) || $_FILES['certificado_de_analisis_externo']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'Error al subir el archivo.']);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if ($data === null) {
    echo json_encode(['error' => 'Datos inválidos o no proporcionados.', 'data'=>$data]);
    exit;
}

if (
    !isset($data['resultados_analisis']) ||
    !isset($data['laboratorio_nro_analisis']) ||
    !isset($data['laboratorio_fecha_analisis']) ||
    !isset($data['fecha_entrega'])
){
    echo json_encode(['error' => 'Datos inválidos o no proporcionados.', 'data'=>$data]);
    exit;
}

$checkQuery = "SELECT COUNT(*) as count FROM calidad_analisis_externo WHERE id = ? AND resultados_analisis IS NOT NULL";
$stmtCheck = mysqli_prepare($link, $checkQuery);
mysqli_stmt_bind_param($stmtCheck, 'i', $idAnalisisExterno);
mysqli_stmt_execute($stmtCheck);
$resultCheck = mysqli_stmt_get_result($stmtCheck);
$row = mysqli_fetch_assoc($resultCheck);

if ($row['count'] > 0) {
    echo json_encode(['error' => 'Ya existen resultados para este análisis.']);
    exit;
}
mysqli_stmt_close($stmtCheck);

// Sube el archivo a Cloudflare R2
$fileBinary = file_get_contents($_FILES['certificado_de_analisis_externo']['tmp_name']);
$fileName = 'certificados/' . $_FILES['certificado_de_analisis_externo']['name'];

$params = [
    'fileBinary' => $fileBinary,
    'folder' => 'certificado_de_analisis_externo',
    'fileName' => $fileName
];

$uploadStatus = setFile($params);
$uploadResult = json_decode($uploadStatus, true);


if (isset($uploadResult['success']) && $uploadResult['success'] !== false) {
    $fileURL = $uploadResult['success']['ObjectURL'];

    $consultaSQL = "UPDATE calidad_analisis_externo SET  
        resultados_analisis = ?, 
        laboratorio_nro_analisis = ?, 
        laboratorio_fecha_analisis = ?, 
        fecha_entrega = ?, 
        url_certificado_de_analisis_externo = ?, 
        estado = 'Pendiente liberación productos'
        WHERE id = ?";

    $stmt = mysqli_prepare($link, $consultaSQL);
    if (!$stmt) {
        echo json_encode(['error' => 'Error al preparar consulta.']);
        exit;
    }

    $resultados_analisis = limpiarDato(json_encode($data['resultados_analisis']));
    $laboratorio_nro_analisis = limpiarDato($data['laboratorio_nro_analisis']);
    $laboratorio_fecha_analisis = limpiarDato($data['laboratorio_fecha_analisis']);
    $fecha_entrega = limpiarDato($data['fecha_entrega']);

    mysqli_stmt_bind_param($stmt, 'sssssi', 
        $resultados_analisis, 
        $laboratorio_nro_analisis, 
        $laboratorio_fecha_analisis, 
        $fecha_entrega, 
        $fileURL, 
        $idAnalisisExterno
    );
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if (mysqli_error($link)) {
        echo json_encode(['error' => 'Error al ejecutar consulta.', 'data'=>$data]);
        exit;
    }

    echo json_encode(['exito' => true]);

} else {
    echo json_encode(['error' => 'Error al subir el certificado: ' . $uploadResult['error']]);
}
?>
