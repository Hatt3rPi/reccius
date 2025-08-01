<?php
// archivo: pages/backend/documentos/eliminar_documento.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
require_once "../cloud/R2_manager.php"; // Asegúrate de que R2_manager.php contiene deleteFileFromUrl()

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}

// Verificar que solo isumonte o isumonte@reccius.cl pueda ejecutar esta operación
if ($_SESSION['usuario'] !== 'isumonte@reccius.cl' && $_SESSION['usuario'] !== 'isumonte') {
    echo json_encode(['exito' => false, 'mensaje' => 'No tienes permisos para realizar esta operación.']);
    exit;
}

header('Content-Type: application/json');

// Validar que se haya proporcionado un ID de documento
$id_documento = $_POST['id_documento'] ?? null;

if (!$id_documento) {
    echo json_encode(['exito' => false, 'mensaje' => 'ID de documento no proporcionado']);
    exit;
}

// Verificar que el documento exista en la base de datos y obtener su URL
$query = "SELECT url FROM calidad_otros_documentos WHERE id = ? AND (estado IS NULL OR estado != 'D')";
$stmt = mysqli_prepare($link, $query);

if ($stmt === false) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error en la preparación de la consulta.']);
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $id_documento);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $url);
mysqli_stmt_fetch($stmt);

if (!$url) {
    echo json_encode(['exito' => false, 'mensaje' => 'El documento no existe o ya ha sido eliminado.']);
    mysqli_stmt_close($stmt);
    exit;
}

mysqli_stmt_close($stmt);

// Eliminar el archivo del almacenamiento R2 usando la URL
$deleteStatus = deleteFileFromUrl($url);

if (!$deleteStatus['success']) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error al eliminar el archivo del almacenamiento: ' . $deleteStatus['error']]);
    exit;
}

// Actualizar el estado del documento en la base de datos a 'D' (eliminado)
$query = "UPDATE calidad_otros_documentos SET estado = 'D' WHERE id = ?";
$stmt = mysqli_prepare($link, $query);

if ($stmt === false) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error en la preparación de la consulta de actualización.']);
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $id_documento);
if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['exito' => true, 'mensaje' => 'Documento marcado como eliminado']);
} else {
    echo json_encode(['exito' => false, 'mensaje' => 'Error al actualizar el estado del documento en la base de datos']);
}

mysqli_stmt_close($stmt);
mysqli_close($link);
?>
