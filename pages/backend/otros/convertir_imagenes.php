<?php
// Este archivo se llamará 'convertir_imagenes.php'

function convertToBase64($url) {
    $imageData = file_get_contents($url);
    if ($imageData === false) {
        return null;
    }
    return base64_encode($imageData);
}

$response = [];

$imagenFirmaUrl = 'https://www.customwares.info/assets/certificados_qr/qr_documento_fabarca212_1716860564.png';
$estadoLiberacionUrlAprobado = 'https://www.customwares.info/assets/APROBADO.webp';
$estadoLiberacionUrlRechazado = 'https://www.customwares.info/assets/RECHAZADO_WS.webp';

$response['imagen_firma'] = convertToBase64($imagenFirmaUrl);
$response['estado_liberacion_aprobado'] = convertToBase64($estadoLiberacionUrlAprobado);
$response['estado_liberacion_rechazado'] = convertToBase64($estadoLiberacionUrlRechazado);

echo json_encode($response);
