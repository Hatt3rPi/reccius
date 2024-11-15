<?php
// pages/backend/documentos/opcionales_analisis.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
require_once "../cloud/R2_manager.php"; // Asegúrate de que esta librería esté configurada correctamente.

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}

header('Content-Type: application/json');

// Validar si se subió el archivo correctamente
if (!isset($_FILES['documento']) || $_FILES['documento']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error en la carga del archivo']);
    exit;
}

// Asignar el archivo cargado a la variable $documento
$documento = $_FILES['documento'];
$usuario_carga = $_SESSION['usuario'];
$fecha_carga = date('Y-m-d H:i:s');
$id_productos_analizados = $_POST['id_productos_analizados'];
$nuevo_tipo_adjunto = isset($_POST['nuevo_tipo_adjunto']) ? trim($_POST['nuevo_tipo_adjunto']) : null;
$id_tipo_adjunto = isset($_POST['tipo_adjunto']) ? (int)$_POST['tipo_adjunto'] : null;
$nombre_documento = $_POST['nombre_documento'] ?? pathinfo($documento['name'], PATHINFO_FILENAME);
$debug='';

// Validar el tipo de archivo (solo PDF o imágenes)
$allowedMimeTypes = ['application/pdf', 'image/jpeg', 'image/png'];
$mimeType = mime_content_type($documento['tmp_name']);
if (!in_array($mimeType, $allowedMimeTypes)) {
    echo json_encode(['exito' => false, 'mensaje' => 'Solo se permiten archivos PDF o imágenes (JPG - PNG)']);
    exit;
}

// Verificar si es necesario crear un nuevo tipo de adjunto
if ($nuevo_tipo_adjunto) {
    // Verificar si el tipo ya existe
    $query = "SELECT id FROM calidad_opciones_desplegables WHERE categoria = 'tipo_documento_adjunto' AND nombre_opcion = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, 's', $nuevo_tipo_adjunto);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id_tipo_adjunto);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    $debug= $query + "(" + $nuevo_tipo_adjunto + " - )";
    // Si no existe, crearlo
    if (!$id_tipo_adjunto) {
        $query = "INSERT INTO calidad_opciones_desplegables (categoria, nombre_opcion) VALUES ('tipo_documento_adjunto', ?)";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, 's', $nuevo_tipo_adjunto);
        mysqli_stmt_execute($stmt);
        $id_tipo_adjunto = mysqli_insert_id($link); // Obtener el ID del nuevo tipo
        mysqli_stmt_close($stmt);
    }
}

// Verificar que el `id_productos_analizados` exista en la tabla `calidad_productos_analizados`
$query = "SELECT id FROM calidad_productos_analizados WHERE id = ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, 'i', $id_productos_analizados);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) === 0) {
    echo json_encode(['exito' => false, 'mensaje' => 'El ID ' . $id_productos_analizados . ' de producto analizado no existe']);
    mysqli_stmt_close($stmt);
    exit;
}
mysqli_stmt_close($stmt);

// Preparar el nombre del archivo para la carga en R2
$timestamp = time();
$newFileName = $id_productos_analizados . '_' . $usuario_carga . '_' . $timestamp . '.' . pathinfo($documento['name'], PATHINFO_EXTENSION);
$params = [
    'fileBinary' => file_get_contents($documento['tmp_name']),
    'folder' => 'calidad_otros_documentos',
    'fileName' => $newFileName
];

// Subir el archivo al servidor R2
$uploadStatus = setFile($params); // Llamada a R2_manager para subir el archivo
$uploadResult = json_decode($uploadStatus, true);

if (!$uploadResult || !$uploadResult['success']) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error al subir el archivo a R2']);
    exit;
}

$fileUrl = $uploadResult['success']['ObjectURL']; // URL del archivo cargado en R2

// Guardar el registro del documento en la base de datos
$query = "INSERT INTO calidad_otros_documentos 
          (id_productos_analizados, url, nombre_documento, usuario_carga, fecha_carga, tipo) 
          VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, 'issssi', $id_productos_analizados, $fileUrl, $nombre_documento, $usuario_carga, $fecha_carga, $id_tipo_adjunto);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['exito' => true, 'mensaje' => 'Documento subido y registrado con éxito', 'url' => $fileUrl, 'debug' => $debug]);
} else {
    echo json_encode(['exito' => false, 'mensaje' => 'Error al registrar el documento en la base de datos']);
}

mysqli_stmt_close($stmt);
mysqli_close($link);
?>
