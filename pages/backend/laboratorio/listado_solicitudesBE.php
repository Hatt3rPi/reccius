<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";


// Consulta para obtener las especificaciones de productos
$query = "SELECT 
                aex.id as id_analisisExterno,
                aex.estado, 
                aex.numero_registro, 
                aex.laboratorio, 
                aex.fecha_registro,
                aex.id_especificacion,
                aex.id_producto, 
                concat(pr.nombre_producto, ' ', pr.concentracion) as producto
            FROM `calidad_analisis_externo` as aex
            left join calidad_productos as pr 
            on aex.id_producto=pr.id;";

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
