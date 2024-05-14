<?php
$queryAnalisisExterno = "
SELECT 
    an.*,
    prod.identificador_producto AS 'prod_identificador_producto', 
    prod.nombre_producto AS 'prod_nombre_producto', 
    prod.tipo_producto AS 'prod_tipo_producto', 
    prod.tipo_concentracion AS 'prod_tipo_concentracion', 
    prod.concentracion AS 'prod_concentracion', 
    prod.formato AS 'prod_formato', 
    prod.elaborado_por AS 'prod_elaborado_por',
    cep.id_especificacion,
    cep.version,
    cep.vigencia,
    cep.creado_por,
    cep.revisado_por,
    cep.aprobado_por,
    can.id_analisis,
    can.tipo_analisis,
    can.descripcion_analisis,
    can.metodologia,
    can.criterios_aceptacion
FROM calidad_analisis_externo AS an
JOIN calidad_productos AS prod ON an.id_producto = prod.id
LEFT JOIN calidad_especificacion_productos AS cep ON prod.id = cep.id_producto
LEFT JOIN calidad_analisis AS can ON cep.id_especificacion = can.id_especificacion_producto
WHERE an.id = ?";

$stmt = mysqli_prepare($link, $queryAnalisisExterno);
if ($stmt === false) {
    die('Error en la preparación de la consulta: ' . mysqli_error($link));
}
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$productos = [];

while ($row = mysqli_fetch_assoc($result)) {
    $productos[] = $row;
}

mysqli_stmt_close($stmt);
mysqli_close($link);

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['productos' => $productos], JSON_UNESCAPED_UNICODE);

?>
