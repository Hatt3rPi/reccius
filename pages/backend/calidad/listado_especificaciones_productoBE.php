<?php
// archivo: pages\backend\calidad\listado_especificaciones_productoBE.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";


// Consulta para obtener las especificaciones de productos
$query = "SELECT 
            cp.id,
            cep.id_especificacion,
            cep.estado, 
            cep.version,
            cp.documento_ingreso as documento, 
            cp.nombre_producto AS producto, 
            cp.tipo_producto, 
            cp.concentracion, 
            cp.formato, 
            cp.elaborado_por,
            cp.pais_origen,
            CASE
                WHEN cep.fecha_aprobacion IS NOT NULL THEN TRUE
                ELSE FALSE
            END as aprobacion,
            cep.fecha_expiracion 
        FROM calidad_productos as cp
        INNER JOIN calidad_especificacion_productos as cep ON cp.id = cep.id_producto where estado not in ('eliminado_por_solicitud_usuario');";

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
