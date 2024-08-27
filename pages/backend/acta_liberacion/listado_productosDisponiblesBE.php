<?php
//archivo: pages\backend\acta_liberacion\listado_productosDisponiblesBE.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";


// Consulta para obtener las especificaciones de productos
$query = "SELECT 
                a.*,
                CASE 
                    WHEN b.concentracion IS NULL OR b.concentracion = '' 
                    THEN b.nombre_producto 
                    ELSE CONCAT(b.nombre_producto, ' ', b.concentracion, ' - ', b.formato) 
                END AS producto,
                b.tipo_producto,
                c.url_documento_adicional
            FROM `calidad_productos_analizados` as a
            LEFT JOIN calidad_productos as b 
            on a.id_producto=b.id
            left join calidad_analisis_externo as c on a.id_analisisExterno=c.id;";
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
