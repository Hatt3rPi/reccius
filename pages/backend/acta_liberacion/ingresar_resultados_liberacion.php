<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Validación y saneamiento del ID del análisis externo
$id_acta = isset($_GET['id_acta']) ? intval($_GET['id_acta']) : 0;

// Preparación de la consulta
$queryAnalisisExterno = $conn->prepare("SELECT 
                            an.*,
                            prod.identificador_producto AS 'prod_identificador_producto', 
                            prod.nombre_producto AS 'prod_nombre_producto', 
                            prod.tipo_producto AS 'prod_tipo_producto', 
                            prod.tipo_concentracion AS 'prod_tipo_concentracion', 
                            prod.concentracion AS 'prod_concentracion', 
                            prod.formato AS 'prod_formato', 
                            prod.elaborado_por AS 'prod_elaborado_por',
                            es.id_especificacion AS 'es_id_especificacion', 
                            es.documento AS 'es_documento', 
                            es.version AS 'es_version', 
                            anali.id_analisis AS 'anali_id_analisis', 
                            anali.tipo_analisis AS 'anali_tipo_analisis', 
                            anali.metodologia AS 'anali_metodologia',
                            anali.descripcion_analisis AS 'anali_descripcion_analisis',
                            anali.criterios_aceptacion AS 'anali_criterios_aceptacion'
                        FROM calidad_analisis_externo AS an
                        JOIN calidad_especificacion_productos AS es ON an.id_especificacion = es.id_especificacion
                        JOIN calidad_productos AS prod ON es.id_producto = prod.id
                        JOIN calidad_analisis AS anali ON es.id_especificacion = anali.id_especificacion_producto
                        WHERE an.id = ?");


// Vinculación de parámetros
$queryAnalisisExterno->bind_param("i", $id_acta);

// Ejecución de la consulta
$queryAnalisisExterno->execute();

// Obtención de resultados
$result = $queryAnalisisExterno->get_result();

// Verificación de resultados
if ($result->num_rows > 0) {
    $analisisExterno = $result->fetch_assoc();
    // Procesar los resultados según sea necesario
    // Por ejemplo, puedes almacenarlos en una variable de sesión
    $_SESSION['analisis_externo'] = $analisisExterno;
} else {
    echo "No se encontraron resultados.";
}

// Cierre de la consulta y la conexión
$queryAnalisisExterno->close();
$conn->close();
?>
