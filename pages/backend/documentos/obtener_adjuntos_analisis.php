<?php
// archivo: pages/backend/documentos/obtener_adjuntos_analisis.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}
header('Content-Type: application/json');


// Verificar que se haya enviado el id_productos_analizados
$id_productos_analizados = $_GET['id_productos_analizados'] ?? null;

if (!$id_productos_analizados) {
    echo json_encode(['exito' => false, 'mensaje' => 'ID de producto analizado no proporcionado']);
    exit;
}

// Verificar que el `id_productos_analizados` exista en la tabla `calidad_productos_analizados`
$query = "SELECT id FROM calidad_productos_analizados WHERE id_productos_analizados = ?";
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

// Obtener todos los documentos relacionados con el `id_productos_analizados`
$query = "SELECT ctd.*, od.nombre_opcion as categoria
            FROM calidad_otros_documentos as ctd
            JOIN calidad_opciones_desplegables as od ON 
                id_opciones_desplegables = id
            WHERE id_productos_analizados = ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, 'i', $id_productos_analizados);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$documentos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $documentos[] = $row;
}

mysqli_stmt_close($stmt);
mysqli_close($link);

if (empty($documentos)) {
    echo json_encode(['exito' => false, 'mensaje' => 'No se encontraron documentos para este producto analizado']);
} else {
    echo json_encode(['exito' => true, 'documentos' => $documentos]);
}
?>
