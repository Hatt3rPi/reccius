<?php
// archivo: pages\backend\index\index_administrador.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Consulta para obtener los datos de productos analizados
$query = "SELECT 
    a.id,
    a.estado, 
    a.lote, 
    a.tamano_lote, 
    a.fecha_in_cuarentena, 
    a.fecha_out_cuarentena,
    a.fecha_vencimiento,
    CONCAT(b.nombre_producto, ' ', b.concentracion, ' - ', b.formato) AS producto,
    CASE 
        WHEN a.estado = 'En cuarentena' THEN DATEDIFF(CURDATE(), a.fecha_in_cuarentena)
        ELSE DATEDIFF(a.fecha_out_cuarentena, a.fecha_in_cuarentena)
    END AS dias_en_cuarentena,
    DATE_FORMAT(a.fecha_out_cuarentena, '%Y-%m') AS mes_salida_cuarentena,
    DATE_FORMAT(a.fecha_in_cuarentena, '%Y-%m') AS mes_entrada_cuarentena
FROM calidad_productos_analizados AS a 
LEFT JOIN calidad_productos AS b ON a.id_producto = b.id;";

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
