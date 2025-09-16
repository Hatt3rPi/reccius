<?php
//documento: pages\backend\calidad\add_documentos.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}
include '/home/customw2/librerias/phpqrcode/qrlib.php';
include_once '../cloud/R2_manager.php';

// Logging para diagnosticar errores intermitentes
$log_file = '/tmp/pdf_upload_debug.log';
$start_time = microtime(true);
$usuario = $_SESSION['usuario'] ?? 'unknown';
$log_prefix = date('Y-m-d H:i:s') . " | Usuario: {$usuario} | ID: " . ($_POST['id_solicitud'] ?? 'N/A') . " | ";

function write_debug_log($message) {
    global $log_file, $log_prefix;
    file_put_contents($log_file, $log_prefix . $message . PHP_EOL, FILE_APPEND | LOCK_EX);
}

write_debug_log("INICIO - Recibida solicitud de subida PDF");

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

// Quick fix: aumentar tiempo límite y memoria para este script específico
ini_set('max_execution_time', 60); // 60 segundos
ini_set('memory_limit', '256M'); // 256MB
ini_set('upload_max_filesize', '50M'); // 50MB
ini_set('post_max_size', '55M'); // 55MB

// Validar el archivo recibido
if (!isset($_FILES['certificado']) || $_FILES['certificado']['error'] !== UPLOAD_ERR_OK) {
    $error_code = $_FILES['certificado']['error'] ?? 'FILE_NOT_SET';
    write_debug_log("ERROR - Validación archivo fallida. Error code: " . $error_code);

    // Registrar error de validación en tabla de monitoreo
    log_upload_attempt($link, $usuario, $idSolicitud, 0, false, "Error de validación: " . $error_code, 'validation_error', round((microtime(true) - $start_time) * 1000, 2));

    echo json_encode(['error' => 'Error al subir el archivo', 'error_code' => $error_code]);
    exit;
}

$certificado = $_FILES['certificado'];
$file_size = $certificado['size'];
write_debug_log("VALIDACION - Archivo recibido. Tamaño: " . $file_size . " bytes");

$mimeType = mime_content_type($certificado['tmp_name']);

// Es un pdf?
if ($mimeType !== 'application/pdf') {
    write_debug_log("ERROR - Tipo de archivo inválido: " . $mimeType);
    echo json_encode(['error' => 'El archivo no es un PDF válido', 'mime_type' => $mimeType]);
    exit;
}

write_debug_log("VALIDACION - PDF válido confirmado");

// Obtener la URL existente
$urlColumn = $types[$type];
$query = "SELECT $urlColumn FROM calidad_analisis_externo WHERE id = ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "i", $idSolicitud);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $existingUrl);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if ($existingUrl) {
    // Eliminar el archivo anterior de R2
    $fileName = basename($existingUrl);
    $folder = $urlColumn;
    $deleteStatus = deleteFile($folder, $fileName);
    $deleteResult = json_decode($deleteStatus, true);

    if (isset($deleteResult['success']) && $deleteResult['success'] === false) {
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el archivo anterior: ' . $deleteResult['error']]);
        exit;
    }
}

// Leer el archivo y prepararlo para subir
$fileBinary = file_get_contents($certificado['tmp_name']);
$timestamp = time(); // Asegúrate de tener este timestamp definido
$newFileName = $idSolicitud . '_' . $usuario . '_' . $timestamp . '.pdf';

$params = [
    'fileBinary' => $fileBinary,
    'folder' => $urlColumn,
    'fileName' => $newFileName
];

write_debug_log("R2_UPLOAD - Iniciando subida a R2. Archivo: " . $newFileName);
$uploadStatus = setFile($params);
$uploadResult = json_decode($uploadStatus, true);

if (isset($uploadResult['success']) && $uploadResult['success'] !== false) {
    write_debug_log("R2_UPLOAD - Subida exitosa a R2");
    $fileURL = $uploadResult['success']['ObjectURL'];
    $response['fileURL'] = $fileURL;

    $query = "UPDATE calidad_analisis_externo SET $urlColumn = ? WHERE id = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "si", $fileURL, $idSolicitud);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $total_time = round((microtime(true) - $start_time) * 1000, 2);
        write_debug_log("EXITO - Proceso completado en {$total_time}ms");

        // Registrar en tabla de monitoreo
        log_upload_attempt($link, $usuario, $idSolicitud, $file_size, true, null, null, $total_time);

        echo json_encode(['status' => 'success', 'message' => 'Archivo subido y base de datos actualizada correctamente', 'url' => $fileURL]);
    } else {
        write_debug_log("ERROR - Fallo al actualizar BD");

        // Registrar error en tabla de monitoreo
        log_upload_attempt($link, $usuario, $idSolicitud, $file_size, false, 'Fallo al actualizar BD', 'database_error', round((microtime(true) - $start_time) * 1000, 2));

        echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la base de datos']);
    }

    mysqli_stmt_close($stmt);
} else {
    $error_msg = $uploadResult['error'] ?? 'Error desconocido en R2';
    $error_type = $uploadResult['error_type'] ?? 'unknown';
    write_debug_log("ERROR - Fallo en R2: " . $error_msg);

    // Registrar error en tabla de monitoreo
    log_upload_attempt($link, $usuario, $idSolicitud, $file_size, false, $error_msg, $error_type, round((microtime(true) - $start_time) * 1000, 2));

    echo json_encode(['status' => 'error', 'message' => 'Error al subir el archivo: ' . $error_msg, 'error_type' => $error_type]);
}

// Función para registrar intentos de subida en BD
function log_upload_attempt($link, $usuario, $id_solicitud, $tamaño_archivo, $exito, $error_msg, $error_type, $tiempo_respuesta) {
    // Verificar si la tabla existe antes de intentar insertar
    $check_table = "SHOW TABLES LIKE 'pdf_upload_log'";
    $result = mysqli_query($link, $check_table);

    if (mysqli_num_rows($result) > 0) {
        $query = "INSERT INTO pdf_upload_log (usuario, id_solicitud, tamaño_archivo, exito, error_msg, error_type, tiempo_respuesta_ms) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($link, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "siibssi", $usuario, $id_solicitud, $tamaño_archivo, $exito, $error_msg, $error_type, $tiempo_respuesta);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}
?>
