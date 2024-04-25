<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";


// Consulta para obtener las especificaciones de productos
$query = "SELECT 
                am.estado, 
                CONCAT(am.numero_acta, '-', LPAD(am.version_acta, 2, '0')) AS numero_acta,
                am.fecha_muestreo, 
                am.responsable, 
                am.muestreador, 
                am.verificador, 
                am.version_acta,
                concat(pr.nombre_producto, ' ', pr.concentracion, ' - ', pr.formato) as producto, 
                pr.tipo_producto,
                am.id as id_acta
            FROM `calidad_acta_muestreo` as am
            LEFT JOIN calidad_productos as pr 
            on am.id_producto=pr.id;";

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
