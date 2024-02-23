<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Validación y saneamiento del ID del análisis externo
$id_analisis_externo = isset($_GET['id_analisis_externo']) ? intval($_GET['id_analisis_externo']) : 0;

// Consulta SQL para obtener los datos del análisis externo y el producto asociado
$query = "SELECT aex.id as id_analisis_externo, aex.id_especificacion, aex.id_producto,
pr.nombre_producto, pr.formato, pr.concentracion, pr.tipo_producto,
aex.lote, aex.tamano_lote, aex.codigo_mastersoft, aex.condicion_almacenamiento, aex.tamano_muestra, aex.tamano_contramuestra, aex.tipo_analisis, aex.muestreado_por
FROM `calidad_analisis_externo` as aex
LEFT JOIN calidad_productos as pr ON aex.id_producto = pr.id
WHERE aex.id = ?";

$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "i", $id_analisis_externo);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$analisis_externos = [];

while ($row = mysqli_fetch_assoc($result)) {
    $analisis_externos[] = [
        'id_analisis_externo' => $row['id_analisis_externo'],
        'id_especificacion' => $row['id_especificacion'],
        'id_producto' => $row['id_producto'],
        'nombre_producto' => $row['nombre_producto'],
        'formato' => $row['formato'],
        'concentracion' => $row['concentracion'],
        'tipo_producto' => $row['tipo_producto'],
        'lote' => $row['lote'],
        'tamano_lote' => $row['tamano_lote'],
        'codigo_mastersoft' => $row['codigo_mastersoft'],
        'condicion_almacenamiento' => $row['condicion_almacenamiento'],
        'tamano_muestra' => $row['tamano_muestra'],
        'tamano_contramuestra' => $row['tamano_contramuestra'],
        'tipo_analisis' => $row['tipo_analisis'],
        'muestreado_por' => $row['muestreado_por'],
    ];
}

mysqli_stmt_close($stmt);
mysqli_close($link);

// Devolver los resultados en formato JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['analisis_externos' => $analisis_externos], JSON_UNESCAPED_UNICODE);
?>
