<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";


// Consulta para obtener las especificaciones de productos
$query = "SELECT 
                lib.estado, 
                CONCAT(lib.numero_acta, '-', LPAD(lib.version_acta, 2, '0')) AS numero_acta,
                lib.fecha_muestreo, 
                lib.responsable, 
                lib.muestreador, 
                lib.verificador, 
                lib.version_acta,
                concat(pr.nombre_producto, ' ', pr.concentracion, ' - ', pr.formato) as producto, 
                pr.tipo_producto,
                lib.id as id_actaLiberacion
            FROM `calidad_acta_liberacion` as lib
            LEFT JOIN calidad_productos as pr 
            on lib.id_producto=pr.id;";
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
