<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
require_once "../otros/laboratorio.php";

// Validación y saneamiento del ID del análisis externo
$id_acta = isset($_GET['id_acta']) ? intval($_GET['id_acta']) : 0;

if ($id_acta === 0) {
    die(json_encode(['error' => 'ID de acta no válido']));
}

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'Acceso denegado']);
    exit;
}

function ejecutarConsulta($link, $query, $params, $param_types)
{
    $stmt = mysqli_prepare($link, $query);
    if (!$stmt) {
        return ['error' => 'Error en la preparación de la consulta: ' . mysqli_error($link)];
    }
    mysqli_stmt_bind_param($stmt, $param_types, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        return ['error' => 'Error en la ejecución de la consulta: ' . mysqli_stmt_error($stmt)];
    }
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $data;
}

// Obtener datos del análisis externo
$queryAnalisis = "SELECT 
                    numero_registro, numero_solicitud, fecha_registro, 
                    solicitado_por, revisado_por, laboratorio,
                    url_certificado_acta_de_muestreo, url_certificado_solicitud_analisis_externo
                    FROM calidad_analisis_externo 
                    WHERE id = ?";
$analisis = ejecutarConsulta($link, $queryAnalisis, [$id_acta], 'i');
// Obtener datos del acta de muestreo
$queryActa = "SELECT 
                    id
                    FROM calidad_acta_muestreo 
                    WHERE id_analisisExterno  = ? 
                    AND estado = 'Vigente'";
$acta = ejecutarConsulta($link, $queryActa, [$id_acta], 'i');

if (isset($analisis['error'])) {
    die(json_encode(['exito' => false, 'mensaje' => $analisis['error']]));
}

if (empty($analisis)) {
    echo json_encode(['exito' => false, 'mensaje' => 'No se encontró el análisis externo']);
    exit;
}
$analisis = $analisis[0];

$solicitado_por = $analisis['solicitado_por'];
$revisado_por = $analisis['revisado_por'];

// Obtener correos del solicitante y revisor
$queryUsuarios = "SELECT usuario, correo, nombre FROM usuarios WHERE usuario IN (?, ?)";
$usuarios = ejecutarConsulta($link, $queryUsuarios, [$solicitado_por, $revisado_por], 'ss');

if (isset($usuarios['error'])) {
    die(json_encode(['exito' => false, 'mensaje' => $usuarios['error']]));
}

$laboratorio = new Laboratorio();
$lab = $laboratorio->findByName($analisis['laboratorio']);

$analisis['correoLab'] = $lab['correo'];

// Enviar los datos en formato JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'analisis' => $analisis,
    'usuarios' => $usuarios,
    'acta' => $acta
], JSON_UNESCAPED_UNICODE);

mysqli_close($link);
