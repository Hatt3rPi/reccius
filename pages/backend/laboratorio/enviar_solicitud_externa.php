<?php
// archivo: pages\backend\laboratorio\enviar_solicitud_externa.php
session_start();
header('Content-Type: application/json');
include "../email/envia_correoBE.php";
require_once "../otros/laboratorio.php";
require_once "/home/customw2/conexiones/config_reccius.php";

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'Acceso denegado']);
    exit;
}

$host = $_SERVER['HTTP_HOST'];

$input = json_decode(file_get_contents('php://input'), true);

if (
    !isset($input['id_analisis_externo']) ||
    empty($input['subject']) ||
    empty($input['mensaje']) ||
    empty($input['altMesaje'])  ||
    empty($input['emailLab'])  ||
    !isset($input['destinatarios']) ||
    !is_array($input['destinatarios']) ||
    empty($input['destinatarios'])
) {
    echo json_encode(['exito' => false, 'mensaje' => 'Datos insuficientes', 'input' => $input]);
    exit;
}

$id_analisis_externo = intval($input['id_analisis_externo']);
$destinatarios = $input['destinatarios'];
$subject = $input['subject'];
$mensaje = $input['mensaje'];
$altMesaje = $input['altMesaje'];
$emailLab = $input['emailLab'];

// Obtener el usuario actual
$usuario = $_SESSION['usuario'];
$id_usuario = $_SESSION['id_usuario'];

// Obtener datos del análisis externo
$queryAnalisis = "SELECT 
        solicitado_por, 
        revisado_por, 
        url_certificado_acta_de_muestreo, 
        url_certificado_solicitud_analisis_externo, 
        laboratorio
    FROM 
        calidad_analisis_externo 
    WHERE 
        id = ?";

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
$lab = $analisis['laboratorio'];

// Verificar si las URLs de los documentos están presentes
$url_certificado_acta_de_muestreo = $analisis['url_certificado_acta_de_muestreo'];
$url_certificado_solicitud_analisis_externo = $analisis['url_certificado_solicitud_analisis_externo'];

if (empty($url_certificado_acta_de_muestreo) || empty($url_certificado_solicitud_analisis_externo)) {
    echo json_encode(['exito' => false, 'mensaje' => 'Faltan URL de documentos']);
    exit;
}

$cuerpo = $mensaje;
$altBody = $altMesaje ?: strip_tags($cuerpo);

$laboratorio = new Laboratorio();
$laboratorio->updateCorreo($lab, $emailLab);
//filtro de correo unico
$destinatarios = array_map("unserialize", array_unique(array_map("serialize", $destinatarios)));

if ($host == 'reccius.cl') {
    $destinatarios[] =
        [
            'email' => 'mgodoy@reccius.cl',
            'nombre' => 'Macarena Alejandra Godoy Castro'
        ];
}

//En caso de quere ver los correos en ambiente de desarrollo
//if($host == 'customware.cl'){
//    $destinatarios[] = 
//    [
//        'nombre' => 'Luciano Copia',
//        'email' => 'lucianoalonso2000@gmail.com'
//    ];
//}

function agregarCorreosCC($emailLab, $lab, $laboratorio)
{
    global $link,$input;
    // Filtrar destinatarios para excluir correos específicos
    $correosExcluidos = [$emailLab, 'mgodoy@reccius.cl'];
    $destinatariosFiltrados = array_filter($input['destinatarios'], function ($destinatario) use ($correosExcluidos) {
        return !in_array($destinatario['email'], $correosExcluidos);
    });

    // Añadir los correos válidos a la tabla 'laboratorio_con_copia'
    foreach ($destinatariosFiltrados as $destinatario) {
        $correo = $destinatario['email'];
        $nombre = $destinatario['nombre'];
        // Asegúrate de que el laboratorio está correctamente identificado
        $laboratorioInfo = $laboratorio->findOrCreateByName($lab);
        $laboratorioId = $laboratorioInfo['id'];

        // Añadir el correo si no existe ya para el laboratorio
        try {
            $laboratorio->addCorreoToLaboratorioWithName($laboratorioId, $nombre, $correo);
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() != 1062) { // Ignorar error de duplicado
                echo json_encode(['exito' => false, 'mensaje' => 'Error al añadir correo: ' . $e->getMessage()]);
                exit;
            }
        }
    }
}

$correosFilter = [$emailLab];
$cc = array_filter($destinatarios, function ($destinatario) use ($correosFilter) {
    return !in_array($destinatario['email'], $correosFilter);
});

$destinatario = [
    [
        'nombre' => $lab,
        'email' => $emailLab
    ]
];
$combinado = array_merge($destinatario, $cc);



$resultado = enviarCorreo_transitorio($combinado, $subject, $cuerpo, $altBody);
if ($resultado['status'] === 'success') {
    // Llamar a la función para agregar los correos como CC
    agregarCorreosCC($emailLab, $lab, $laboratorio);

    $today = date('Y-m-d');
    $stmt = mysqli_prepare($link, "UPDATE calidad_analisis_externo 
        SET estado ='Pendiente ingreso resultados', fecha_envio = ?
        WHERE id=?");
    mysqli_stmt_bind_param($stmt, "si", $today, $id_analisis_externo);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    unset($_SESSION['buscar_por_ID']);
    $_SESSION['buscar_por_ID'] = $id_analisis_externo;
    echo json_encode([
            'exito' => true, 
            'mensaje' => 'Correo enviado con éxito',
            'resultado' => [
                'destinatario' => $destinatario, 
                'subject' => $subject, 
                'cuerpo' => $cuerpo, 
                'altBody' => $altBody, 
                'cc' => $cc,
                'resultado' =>$resultado
            ]
        ]);

    $query_numero_solicitud = "SELECT numero_solicitud FROM calidad_analisis_externo WHERE id = ?";
    $stmt_numero_solicitud = mysqli_prepare($link, $query_numero_solicitud);
    if (!$stmt_numero_solicitud) {
        throw new Exception("Error en la preparación de la consulta: " . mysqli_error($link));
    }
    mysqli_stmt_bind_param($stmt_numero_solicitud, 'i', $id_analisis_externo);
    mysqli_stmt_execute($stmt_numero_solicitud);
    mysqli_stmt_bind_result($stmt_numero_solicitud, $numero_solicitud);
    mysqli_stmt_fetch($stmt_numero_solicitud);
    mysqli_stmt_close($stmt_numero_solicitud);
    unset($_SESSION['buscar_por_ID']);
    $_SESSION['buscar_por_ID'] = $id_analisis_externo;
    finalizarTarea($_SESSION['usuario'], $id_analisis_externo, 'calidad_analisis_externo', 'Enviar a Laboratorio');
    registrarTarea(7, $_SESSION['usuario'], $_SESSION['usuario'], 'Ingresar resultados Laboratorio de solicitud: ' . $numero_solicitud, 2, 'Ingresar resultados Laboratorio', $id_analisis_externo, 'calidad_analisis_externo');
} else {
    http_response_code(400);
    echo json_encode(['exito' => false, 'mensaje' => $resultado]);
}
mysqli_close($link);

?>
