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
                an_externo.url_documento_adicional,
				an_externo.estado as estado_aex,
				an_externo.url_certificado_solicitud_analisis_externo,
				an_externo.numero_solicitud,
                CONCAT(
                    an_externo.numero_solicitud, '-', 
                    LPAD(an_externo.version, 2, '0') 
                ) AS numero_solicitud_version,
                an_externo.tipo_analisis,
				an_externo.muestreado_por,  
				an_externo.fecha_firma_1, 
                an_externo.fecha_firma_2, 
                an_externo.fecha_firma_revisor,
                an_externo.am_verificado_por,
				an_externo.am_ejecutado_por as 'am_generador',
				an_externo.revisado_por as 'aex_revisado_por', 
				an_externo.solicitado_por as 'aex_firma1',
				an_externo.estado as 'aex_estado',
                an_externo.url_certificado_de_analisis_externo,
                an_externo.url_certificado_acta_de_muestreo,
                an_externo.url_certificado_solicitud_analisis_externo,
                an_externo.url_certificado_solicitud_analisis_externo_con_resultados,
                an_externo.url_documento_adicional,
				d.estado as estado_amuestreo,
				d.responsable as 'am_responsable',
                d.verificador as 'am_verificador',
                d.muestreador as 'am_muestreador',
                d.fecha_firma_muestreador as 'am_fecha_firma_muestreador',
                d.fecha_firma_responsable  as 'am_fecha_firma_responsable',
                d.fecha_firma_verificador as 'am_fecha_firma_verificador',
                d.fecha_muestreo  as 'am_fecha_muestreo',
                e.aprobado_por as 'ep_aprobado_por'
            FROM calidad_productos_analizados as a
            LEFT JOIN calidad_productos as b on a.id_producto=b.id
            left join calidad_analisis_externo as an_externo on a.id_analisisExterno=an_externo.id
            left join calidad_acta_muestreo as d on a.id_actaMuestreo=d.id
            left join calidad_especificacion_productos as e on a.id_especificacion=e.id_especificacion
            where a.estado not in ('eliminado_por_solicitud_usuario');";
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
