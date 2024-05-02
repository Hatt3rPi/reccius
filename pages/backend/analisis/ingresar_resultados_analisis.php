<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Validación y saneamiento del ID del análisis externo
$id_acta = isset($_GET['id_acta']) ? intval($_GET['id_acta']) : 0;

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
                am.id as id_acta,
                cae.laboratorio,
                cae.fecha_solicitud,
                cae.analisis_segun,
                cae.fecha_cotizacion,
                cae.estandar_segun,
                cae.hds_adjunto,
                cae.fecha_entrega_estimada,
                cae.numero_documento,
                cae.estandar_otro,
                cae.hds_otro
            FROM `calidad_acta_muestreo` as am
            LEFT JOIN `calidad_productos` as pr ON am.id_producto = pr.id
            LEFT JOIN `calidad_analisis_externo` as cae ON am.id = cae.id_acta
            WHERE am.id = ?";



// Preparar y ejecutar la consulta
$stmt = $link->prepare($query);
$stmt->bind_param("i", $id_acta);
$stmt->execute();
$result = $stmt->get_result();

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
