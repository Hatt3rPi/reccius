<?php
// pages/backend/documentos/opcionales_analisis.php
session_start();
header('Content-Type: application/json');

require_once "/home/customw2/conexiones/config_reccius.php";
require_once "../cloud/R2_manager.php";

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
  echo json_encode(['exito' => false, 'mensaje' => 'Acceso denegado']);
  exit;
}

if (!isset($_FILES['documento']) || $_FILES['documento']['error'] !== UPLOAD_ERR_OK) {
  echo json_encode(['exito' => false, 'mensaje' => 'Error en la carga del archivo']);
  exit;
}
//IDEA: mi idea es que se puedan cargar pdf e imagenes, supongo que el la farmacia tambien tendran fotos de los productos o los lotes

// Validar el tipo de archivo (solo PDF o imágenes)
// el jepg tambien contempla el jpg 
$allowedMimeTypes = ['application/pdf', 'image/jpeg', 'image/png'];
$mimeType = mime_content_type($_FILES['documento']['tmp_name']);

if (!in_array($mimeType, $allowedMimeTypes)) {
  echo json_encode(['exito' => false, 'mensaje' => 'Solo se permiten archivos PDF o imágenes (JPG - PNG)']);
  exit;
}


$documento = $_FILES['documento'];
$id_productos_analizados = $_POST['id_productos_analizados'] ?? null;
$nombre_documento = $_POST['nombre_documento'] ?? $documento['name'];
$usuario_carga = $_SESSION['usuario'];
$fecha_carga = date('Y-m-d H:i:s');


// Verificar que el `id_productos_analizados` exista en la tabla `calidad_productos_analizados`
$query = "SELECT id FROM calidad_productos_analizados WHERE id = ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, 'i', $id_productos_analizados);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) === 0) {
    echo json_encode(['exito' => false, 'mensaje' => 'El ID de producto analizado no existe']);
    mysqli_stmt_close($stmt);
    exit;
}

mysqli_stmt_close($stmt);


//R2manager
$timestamp = time();
$newFileName = $id_productos_analizados . '_' . $usuario_carga . '_' . $timestamp . '.' . pathinfo($documento['name'], PATHINFO_EXTENSION);
$params = [
    'fileBinary' => file_get_contents($documento['tmp_name']),
    'folder' => 'calidad_otros_documentos',
    'fileName' => $newFileName
];
$uploadStatus = setFile($params); // Llamada a R2_manager para subir el archivo
$uploadResult = json_decode($uploadStatus, true);

if (!$uploadResult || $uploadResult['success'] === false) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error al subir el archivo a R2']);
    exit;
}

// Guardar en BD
$fileUrl = $uploadResult['success']['ObjectURL'];

$query = "INSERT INTO calidad_otros_documentos (id_productos_analizados, url, nombre_documento, usuario_carga, fecha_carga) 
          VALUES (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, 'issss', $id_productos_analizados, $fileUrl, $nombre_documento, $usuario_carga, $fecha_carga);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['exito' => true, 'mensaje' => 'Documento subido y registrado con éxito', 'url' => $fileUrl]);
} else {
    echo json_encode(['exito' => false, 'mensaje' => 'Error al registrar el documento en la base de datos']);
}

mysqli_stmt_close($stmt);
mysqli_close($link);
?>