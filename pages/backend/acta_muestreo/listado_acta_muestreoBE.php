<?php
// archivo: pages\backend\acta_muestreo\listado_acta_muestreoBE.php
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
                CASE 
                    WHEN pr.concentracion IS NULL OR pr.concentracion = '' 
                    THEN pr.nombre_producto 
                    ELSE CONCAT(pr.nombre_producto, ' ', pr.concentracion, ' - ', pr.formato) 
                END AS producto,
                pr.tipo_producto,
                am.id as id_acta,
                am.id_analisisExterno,
                am.muestreador as user_firma1,
                am.responsable as user_firma2,
                am.verificador as user_firma3,
                (CASE WHEN am.fecha_firma_muestreador IS NOT NULL THEN 1 ELSE 0 END + 
            CASE WHEN am.fecha_firma_responsable IS NOT NULL THEN 1 ELSE 0 END + 
            CASE WHEN am.fecha_firma_verificador IS NOT NULL THEN 1 ELSE 0 END) AS cantidad_firmas,
            aex.lote
            FROM `calidad_acta_muestreo` as am
            LEFT JOIN calidad_productos as pr on am.id_producto=pr.id
            left join calidad_analisis_externo as aex on am.id_analisisExterno=aex.id;";

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
