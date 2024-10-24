<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}

// Consulta para obtener las especificaciones de productos
$query = "SELECT 
                lib.estado, 
                CONCAT(lib.numero_acta, '-', LPAD(lib.version_acta, 2, '0')) AS numero_acta,
                lib.fecha_muestreo, 
                lib.responsable, 
                lib.muestreador, 
                lib.verificador, 
                lib.version_acta,
                CASE 
                    WHEN pr.concentracion IS NULL OR pr.concentracion = '' 
                    THEN pr.nombre_producto 
                    ELSE CONCAT(pr.nombre_producto, ' ', pr.concentracion, ' - ', pr.formato) 
                END AS producto,
                pr.tipo_producto,
                lib.id as id_actaLiberacion
            FROM `calidad_acta_liberacion` as lib
            LEFT JOIN calidad_productos as pr 
            on lib.id_producto=pr.id
            where lib.estado not in ('eliminado_por_solicitud_usuario') ;";
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
