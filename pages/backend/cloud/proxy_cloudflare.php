<?php
// archivo: pages\backend\cloud\proxy_cloudflare.php

// Verificar si el parámetro 'url' está presente
if (!isset($_GET['url'])) {
    http_response_code(400);
    echo 'URL no especificada.';
    exit;
}

// Obtener la URL de la imagen a cargar
$imageUrl = $_GET['url'];

// Validar que la URL tiene un formato esperado para evitar abusos
if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    echo 'URL no válida.';
    exit;
}

// Obtener el contenido de la imagen
$imageContent = @file_get_contents($imageUrl);

// Si no se pudo obtener la imagen, responder con error
if ($imageContent === FALSE) {
    http_response_code(404);
    echo 'No se pudo cargar la imagen.';
    exit;
}

// Determinar el tipo MIME de la imagen
$finfo = new finfo(FILEINFO_MIME_TYPE);
$contentType = $finfo->buffer($imageContent);

// Enviar los encabezados adecuados
header("Content-Type: $contentType");
header('Access-Control-Allow-Origin: *');

// Enviar el contenido de la imagen
echo $imageContent;
?>
