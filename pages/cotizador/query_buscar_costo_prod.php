<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    http_response_code(403); 
    echo json_encode(['error' => 'Acceso denegado']);
    exit;
}

// Obtén los parámetros de la solicitud
$preparacion = isset($_GET['preparacion']) ? mysqli_real_escape_string($link, $_GET['preparacion']) : '';
$detalle = isset($_GET['detalle']) ? mysqli_real_escape_string($link, $_GET['detalle']) : '';

// Verifica que los parámetros necesarios están presentes
if (empty($preparacion) || empty($detalle)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Parámetros insuficientes']);
    exit;
}

// Define la consulta SQL
$queryCosto = "SELECT id, tipo_costo, detalle_costo, preparacion, detalle_preparacion, valor_clp, ultima_modificacion_fecha, ultima_modificacion_usuario FROM recetariomagistral_costosproduccion WHERE preparacion  = '$preparacion' AND detalle_preparacion= '$detalle'";

$result = mysqli_query($link, $queryCosto);

$costos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $costos[] = $row;
}

// Devuelve los resultados como JSON
echo json_encode($costos);
?>