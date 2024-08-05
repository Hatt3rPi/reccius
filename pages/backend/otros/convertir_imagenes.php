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

$imagenFirmaUrl = 'https://pub-4017b86f75d04838b6e805cbb3235b10.r2.dev/certificados_qr/qr_documento_fabarca212_1716860564.png';
$estadoLiberacionUrlAprobado = 'https://pub-bde9ff3e851b4092bfe7076570692078.r2.dev/APROBADO.webp';
$estadoLiberacionUrlRechazado = 'https://pub-bde9ff3e851b4092bfe7076570692078.r2.dev/RECHAZADO_WS.webp';

$response['imagen_firma'] = convertToBase64($imagenFirmaUrl);
$response['estado_liberacion_aprobado'] = convertToBase64($estadoLiberacionUrlAprobado);
$response['estado_liberacion_rechazado'] = convertToBase64($estadoLiberacionUrlRechazado);

echo json_encode($response);
