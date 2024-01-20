<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";


// Consulta para obtener las especificaciones de productos
$query = "SELECT 
            cp.id as id_producto,
            cep.id_especificacion,
            am.id as id_acta_muestreo,
            cp.identificador_producto,
            cp.documento_ingreso as documento, 
            cp.nombre_producto AS producto, 
            cp.tipo_producto, 
            cp.concentracion,
            cp.formato, 
            cp.elaborado_por,
            cp.pais_origen
        FROM calidad_acta_muestreo AS am 
        INNER JOIN calidad_especificacion_productos as cep ON am.id_especificacion = cep.id
        INNER JOIN calidad_productos as cp ON cp.id = cep.id_producto;";
        

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
