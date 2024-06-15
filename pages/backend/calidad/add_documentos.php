<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
include '/home/customw2/librerias/phpqrcode/qrlib.php';
include_once '../cloud/R2_manager.php';

$method = $_SERVER['REQUEST_METHOD'];
$usuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : null;
$type = $_POST['type'] ?? null;
$idSolicitud = $_POST['id_solicitud'] ?? null;

$types= [
    'acta' => 'url_certificado_acta_de_muestreo',
    'analisis_externo' => 'url_certificado_de_analisis_externo',
    'solicitud' => 'url_certificado_solicitud_analisis_externo',
];


if (!$type || !isset($types[$type])) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => 'Acceso denegado']);
    exit;
}

if (!$usuario) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => 'Acceso denegado']);
    exit;
}

if ($method !== 'POST') {
        header('HTTP/1.1 405 Method Not Allowed');
        exit;
}

// Validar el archivo recibido
if (!isset($_FILES['certificado']) || $_FILES['certificado']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'Error al subir el archivo']);
    exit;
}

$certificado = $_FILES['certificado'];
$mimeType = mime_content_type($certificado['tmp_name']);
//es un pdf?
if ($mimeType !== 'application/pdf') {
    echo json_encode(['error' => 'El archivo no es un PDF válido']);
    exit;
}

// Leer el archivo y prepararlo para subir
$fileBinary = file_get_contents($certificado['tmp_name']);
$fileName =  $idSolicitud . '_' . $usuario . '_' . $timestamp . '.pdf';

$params = [
    'fileBinary' => $fileBinary,
    'folder' => $types[$type],
    'fileName' => $fileName
];

$uploadStatus = setFile($params);
$uploadResult = json_decode($uploadStatus, true);


if (isset($uploadResult['success']) && $uploadResult['success'] !== false) {
    $fileURL = $uploadResult['success']['ObjectURL'];
    $response['fileURL'] = $fileURL;

    $urlColumn = $types[$type];
    $query = "UPDATE calidad_analisis_externo SET $urlColumn = ? WHERE id = ?";

    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "si", $fileURL, $idSolicitud);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Archivo subido y base de datos actualizada correctamente', 'url' => $fileURL]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la base de datos']);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al subir el archivo: ' . $uploadResult['error']]);
}

?>