<?php
// Este archivo se llamará 'convertir_imagenes.php'
session_start();
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}
function convertToBase64($url) {
    $imageData = file_get_contents($url);
    if ($imageData === false) {
        return null;
    }
    return base64_encode($imageData);
}

$response = [];

$imagenFirmaUrl = 'https://customware.fabarca212.workers.dev/assets/certificados_qr/qr_documento_fabarca212_1716860564.png';
$estadoLiberacionUrlAprobado = 'https://customware.fabarca212.workers.dev/assets/APROBADO.webp';
$estadoLiberacionUrlRechazado = 'https://customware.fabarca212.workers.dev/assets/RECHAZADO_WS.webp';

$response['imagen_firma'] = convertToBase64($imagenFirmaUrl);
$response['estado_liberacion_aprobado'] = convertToBase64($estadoLiberacionUrlAprobado);
$response['estado_liberacion_rechazado'] = convertToBase64($estadoLiberacionUrlRechazado);

echo json_encode($response);
