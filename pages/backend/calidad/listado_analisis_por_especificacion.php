<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Validación y saneamiento del ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$analisis = isset($_GET['analisis']) ? intval($_GET['analisis']) : 0;
// Preparar la consulta
$stmt = mysqli_prepare($link, "SELECT 
can.id_analisis, 
can.id_especificacion_producto, 
cp.id as id_producto, 
can.tipo_analisis, 
can.descripcion_analisis, 
can.metodologia, 
can.criterios_aceptacion 
FROM calidad_productos as cp 
INNER JOIN calidad_especificacion_productos as cep ON cp.id = cep.id_producto 
INNER JOIN calidad_analisis as can on cep.id_especificacion=can.id_especificacion_producto
WHERE cp.id = ? and tipo_analisis= ? ;");

// Enlazar parámetro
mysqli_stmt_bind_param($stmt, "is", $id, $analisis);

// Ejecutar la consulta
mysqli_stmt_execute($stmt);

// Obtener resultados
$result = mysqli_stmt_get_result($stmt);

$data = [];

// Verificar si hay resultados
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

// Cerrar el statement y la conexión
mysqli_stmt_close($stmt);
mysqli_close($link);

// Enviar los datos en formato JSON
header('Content-Type: application/json');
echo json_encode(['data' => $data]);
?>
