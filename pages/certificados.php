<?php

// Obtener el nombre del archivo del request, por ejemplo, a través de un parámetro GET
$nombreArchivo = $_GET['archivo'];

// Validar y sanear el nombre del archivo para evitar vulnerabilidades
$nombreArchivo = basename($nombreArchivo);

// Definir la ruta al directorio donde se almacenan los documentos
$rutaDocumentos = '/home/customw2/documentos/';

// Construir la ruta completa al archivo
$rutaCompleta = $rutaDocumentos . $nombreArchivo;

// Verificar si el archivo existe y es un archivo regular
if (!is_file($rutaCompleta)) {
    die("Archivo no encontrado.");
}

// Obtener el tipo MIME del archivo, necesario para el encabezado
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$tipoMime = finfo_file($finfo, $rutaCompleta);
finfo_close($finfo);

// Establecer los encabezados para la descarga del archivo
header('Content-Description: File Transfer');
header('Content-Type: ' . $tipoMime);
header('Content-Disposition: attachment; filename="' . basename($rutaCompleta) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($rutaCompleta));

// Limpiar el buffer de salida y leer el archivo para la descarga
ob_clean();
flush();
readfile($rutaCompleta);
exit;

?>