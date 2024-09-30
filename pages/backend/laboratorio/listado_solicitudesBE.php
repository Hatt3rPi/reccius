<?php
// archivos: pages\backend\laboratorio\listado_solicitudesBE.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";


// Consulta para obtener las especificaciones de productos
$query = "SELECT 
    aex.id as id_analisisExterno,
    aex.estado, 
    aex.numero_registro, 
    aex.numero_solicitud,
    aex.version,
    -- Nuevo campo agregado aquí
    CONCAT(
        aex.numero_solicitud, '-', 
		LPAD(aex.version, 3, '0') 
    ) AS numero_solicitud_version,
    aex.laboratorio, 
    aex.fecha_registro,
    aex.id_especificacion,
    aex.id_producto, 
    aex.am_ejecutado_por,
    aex.muestreado_por,
    aex.am_verificado_por,
    CASE 
        WHEN pr.concentracion IS NULL OR pr.concentracion = '' 
        THEN pr.nombre_producto 
        ELSE CONCAT(pr.nombre_producto, ' ', pr.concentracion, ' - ', pr.formato) 
    END AS producto,
    aex.revisado_por,
    aex.fecha_firma_revisor,
    aex.solicitado_por,
    aex.lote,
    pr.tipo_producto,
    cam.id as id_muestreo,
    cam.version_registro,
    cam.estado as estado_muestreo,
    cam.id_original
FROM calidad_analisis_externo aex
LEFT JOIN calidad_productos pr ON aex.id_producto = pr.id
LEFT JOIN (
    SELECT
        a.id_analisisExterno,
        a.id,
        a.id_original,
        a.version_registro,
        a.estado
    FROM calidad_acta_muestreo a
    INNER JOIN (
        SELECT 
            id_analisisExterno,
            MAX(id) as max_id
        FROM (
            SELECT
                id_analisisExterno,
                id,
                estado,
                ROW_NUMBER() OVER (PARTITION BY id_analisisExterno ORDER BY 
                    CASE 
                        WHEN estado = 'vigente' THEN 1
                        WHEN estado = 'En proceso de firma' THEN 2
                        ELSE 3
                    END, 
                    id DESC) as row_num
            FROM calidad_acta_muestreo
        ) ranked
        WHERE row_num = 1
        GROUP BY id_analisisExterno
    ) b ON a.id_analisisExterno = b.id_analisisExterno
    AND a.id = b.max_id
) cam ON aex.id = cam.id_analisisExterno  
WHERE aex.estado NOT IN ('eliminado_por_solicitud_usuario')
ORDER BY `id_analisisExterno` DESC;";


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
