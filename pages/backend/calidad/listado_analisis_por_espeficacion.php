<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";


// Consulta para obtener las especificaciones de productos
$query = "SELECT 
can.id_analisis, 
can.id_especificacion_producto, 
cp.id as id_producto, 
can.tipo_analisis, 
can.descripcion_analisis, 
can.metodologia, 
can.criterios_aceptacion 
FROM calidad_productos as cp 
INNER JOIN calidad_especificacion_productos as cep ON cp.id = cep.id_producto 
INNER JOIN calidad_analisis as can on cep.id_especificacion=can.id_especificacion_producto;";

$result = $link->query($query);

$data = [];

if ($result && $result->num_rows > 0) {
    // Recorrer los resultados y añadirlos al array de datos
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Cerrar la conexión a la base de datos
$link->close();

// Enviar los datos en formato JSON
header('Content-Type: application/json');
echo json_encode(['data' => $data]);
?>
