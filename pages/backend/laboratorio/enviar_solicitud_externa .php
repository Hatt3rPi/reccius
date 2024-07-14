<?php
session_start();
header('Content-Type: application/json');
include "../email/envia_correoBE.php";
require_once "/home/customw2/conexiones/config_reccius.php";

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'Acceso denegado']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id_analisis_externo']) || !isset($input['destinatarios']) || !is_array($input['destinatarios'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'Datos insuficientes']);
    exit;
}

$id_analisis_externo = intval($input['id_analisis_externo']);
$destinatarios = $input['destinatarios'];

// Obtener el usuario actual
$usuario = $_SESSION['usuario'];
$id_usuario = $_SESSION['id_usuario'];

// Obtener datos del análisis externo
$queryAnalisis = "SELECT solicitado_por, revisado_por, url_certificado_acta_de_muestreo, url_certificado_solicitud_analisis_externo, laboratorio FROM calidad_analisis_externo WHERE id = ?";
$stmtAnalisis = mysqli_prepare($link, $queryAnalisis);
if (!$stmtAnalisis) {
    die(json_encode(['exito' => false, 'mensaje' => 'Error en la preparación de la consulta del análisis externo: ' . mysqli_error($link)]));
}
mysqli_stmt_bind_param($stmtAnalisis, "i", $id_analisis_externo);
mysqli_stmt_execute($stmtAnalisis);
$resultAnalisis = mysqli_stmt_get_result($stmtAnalisis);
$analisis = mysqli_fetch_assoc($resultAnalisis);
mysqli_stmt_close($stmtAnalisis);

if (!$analisis) {
    echo json_encode(['exito' => false, 'mensaje' => 'No se encontró el análisis externo']);
    exit;
}

// Obtener correos del solicitante y revisor
$solicitado_por = $analisis['solicitado_por'];
$revisado_por = $analisis['revisado_por'];
$laboratorio = $analisis['laboratorio'];

// Correos del laboratorio
$correosLaboratorio = [
    'reccius' => 'correo@reccius.cl',
    'cequc' => 'correo@cequc.cl',
    'pharmaisa' => 'correo@pharmaisa.cl',
];

// Añadir correo del laboratorio a los destinatarios
if (isset($correosLaboratorio[$laboratorio])) {
    $destinatarios[] = [
        'email' => $correosLaboratorio[$laboratorio],
        'nombre' => $laboratorio
    ];
}

// Obtener correos del solicitante y revisor
$queryUsuarios = "SELECT usuario, correo, nombre FROM usuarios WHERE usuario IN (?, ?)";
$stmtUsuarios = mysqli_prepare($link, $queryUsuarios);
if (!$stmtUsuarios) {
    die(json_encode(['exito' => false, 'mensaje' => 'Error en la preparación de la consulta de usuarios: ' . mysqli_error($link)]));
}
mysqli_stmt_bind_param($stmtUsuarios, "ss", $solicitado_por, $revisado_por);
mysqli_stmt_execute($stmtUsuarios);
$resultUsuarios = mysqli_stmt_get_result($stmtUsuarios);

while ($usuario = mysqli_fetch_assoc($resultUsuarios)) {
    $destinatarios[] = [
        'email' => $usuario['correo'],
        'nombre' => $usuario['nombre']
    ];
}
mysqli_stmt_close($stmtUsuarios);

// Verificar si las URLs de los documentos están presentes
$url_certificado_acta_de_muestreo = $analisis['url_certificado_acta_de_muestreo'];
$url_certificado_solicitud_analisis_externo = $analisis['url_certificado_solicitud_analisis_externo'];

if (empty($url_certificado_acta_de_muestreo) || empty($url_certificado_solicitud_analisis_externo)) {
    echo json_encode(['exito' => false, 'mensaje' => 'Faltan URL de documentos']);
    exit;
}

$asunto = "Solicitud de análisis externo";
$cuerpo = "<h3>Solicitud de análisis externo</h3>
<strong>Documentos:</strong>
<ul>
<li>Certificado acta de muestreo: <a href='{$url_certificado_acta_de_muestreo}'>Ver documento</a></li>
<li>Solicitud de análisis externo: <a href='{$url_certificado_solicitud_analisis_externo}'>Ver documento</a></li>
</ul>";
$altBody = "Solicitud de análisis externo\n\nDocumentos:\nCertificado acta de muestreo: {$url_certificado_acta_de_muestreo}\nSolicitud de análisis externo: {$url_certificado_solicitud_analisis_externo}";

if (enviarCorreoMultiple($destinatarios, $asunto, $cuerpo, $altBody)) {
    echo json_encode(['exito' => true, 'mensaje' => 'Correo enviado con éxito']);
} else {
    http_response_code(500);
    echo json_encode(['exito' => false, 'mensaje' => 'Error al enviar el correo']);
}

mysqli_close($link);
?>
