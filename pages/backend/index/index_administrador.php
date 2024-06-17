<?php
// archivo: pages\backend\index\index_administrador.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Consulta para obtener los datos de productos analizados
$query = "SELECT 
            id_especificacion, 
            id_producto, 
            id_analisisExterno, 
            id_actaMuestreo, 
            estado, 
            lote, 
            tamano_lote, 
            fecha_in_cuarentena, 
            fecha_elaboracion, 
            fecha_vencimiento 
          FROM calidad_productos_analizados";

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
