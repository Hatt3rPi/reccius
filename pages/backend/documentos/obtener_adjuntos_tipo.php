<?php
// archivo: pages/backend/documentos/obtener_adjuntos_tipo.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}
header('Content-Type: application/json');

$query = "SELECT id, nombre_opcion 
          FROM calidad_opciones_desplegables 
          WHERE categoria = 'tipo_documento_adjunto'
          ORDER BY CASE WHEN nombre_opcion = 'Otro' THEN 1 ELSE 0 END, nombre_opcion ASC";

$result = mysqli_query($link, $query);
$opciones = [];

while ($row = mysqli_fetch_assoc($result)) {
    $opciones[] = $row;
}

if (empty($opciones)) {
    echo json_encode(['exito' => false, 'mensaje' => 'No se encontraron documentos para este producto analizado']);
} else {
    echo json_encode(['exito' => true, 'documentos' => $opciones]);
}
?>
