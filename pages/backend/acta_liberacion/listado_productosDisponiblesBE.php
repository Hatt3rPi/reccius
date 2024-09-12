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
                c.url_documento_adicional,
				c.estado as estado_aex,
				c.url_certificado_solicitud_analisis_externo,
				c.numero_solicitud,
				c.muestreado_por,  
				c.fecha_firma_1, c.fecha_firma_2, c.fecha_firma_revisor,
				d.estado as estado_amuestreo,
				d.responsable as 'am_responsable',
                d.verificador as 'am_verificador',
                c.am_verificado_por,
                d.muestreador as 'am_muestreador',
				c.am_ejecutado_por as 'am_generador',
				c.revisado_por as 'aex_revisado_por', 
				c.solicitado_por as 'aex_firma1',
				c.estado as 'aex_estado',
                d.fecha_firma_responsable  as 'am_fecha_firma_responsable',
                d.fecha_firma_verificador as 'am_fecha_firma_verificador',
                d.fecha_muestreo  as 'am_fecha_muestreo'
            FROM calidad_productos_analizados as a
            LEFT JOIN calidad_productos as b on a.id_producto=b.id
            left join calidad_analisis_externo as c on a.id_analisisExterno=c.id
            left join calidad_acta_muestreo as d on a.id_actaMuestreo=d.id
            where a.estado not in ('eliminado_por_solicitud_usuario')  
ORDER BY `a`.`id` DESC;";
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
