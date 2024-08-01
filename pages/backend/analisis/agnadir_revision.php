<?php
// archivo pages\backend\analisis\agnadir_revision.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
require_once "../cloud/R2_manager.php";
header('Content-Type: application/json');

$idAnalisisExterno = isset($_GET['id_analisis']) ? intval($_GET['id_analisis']) : null;
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'Acceso denegado']);
    exit;
}
$usuario = $_SESSION['usuario'];

function limpiarDato($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

if ($idAnalisisExterno === null) {
    echo json_encode(['error' => 'ID de análisis no proporcionado.']);
    exit;
}

if (!isset($_FILES['certificado_de_analisis_externo']) || $_FILES['certificado_de_analisis_externo']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'Error al subir el archivo.']);
    exit;
}

$data = [];
foreach ($_POST as $key => $value) {
    $data[$key] = limpiarDato($value);
}

if (
    !isset($data['resultados_analisis']) ||
    !isset($data['laboratorio_nro_analisis']) ||
    !isset($data['laboratorio_fecha_analisis']) ||
    !isset($data['fecha_entrega'])
) {
    echo json_encode(['error' => 'Datos inválidos o no proporcionados.', 'data' => $data]);
    exit;
}

// Verificar si ya existen resultados para este análisis
$checkQuery = "SELECT COUNT(*) as count FROM calidad_analisis_externo WHERE id = ? AND resultados_analisis IS NOT NULL";
$stmtCheck = mysqli_prepare($link, $checkQuery);
mysqli_stmt_bind_param($stmtCheck, 'i', $idAnalisisExterno);
mysqli_stmt_execute($stmtCheck);
$resultCheck = mysqli_stmt_get_result($stmtCheck);
$row = mysqli_fetch_assoc($resultCheck);

if ($row['count'] > 0) {
    echo json_encode(['error' => 'Ya existen resultados para este análisis.']);
    exit;
}
mysqli_stmt_close($stmtCheck);

// Verificar que el usuario es el mismo que revisado_por en calidad_analisis_externo
$revisadoPorQuery = "SELECT revisado_por, solicitado_por, numero_solicitud FROM calidad_analisis_externo WHERE id = ?";
$stmtRevisadoPor = mysqli_prepare($link, $revisadoPorQuery);
if ($stmtRevisadoPor) {
    mysqli_stmt_bind_param($stmtRevisadoPor, 'i', $idAnalisisExterno);
    mysqli_stmt_execute($stmtRevisadoPor);
    $resultRevisadoPor = mysqli_stmt_get_result($stmtRevisadoPor);
    $rowRevisadoPor = mysqli_fetch_assoc($resultRevisadoPor);
    mysqli_stmt_close($stmtRevisadoPor);

    if ($rowRevisadoPor['revisado_por'] !== $usuario) {
        echo json_encode(['error' => 'Acceso denegado: el usuario no tiene permiso para revisar este análisis.']);
        exit;
    }

    // Ahora tienes acceso a $rowRevisadoPor['solicitado_por'] y $rowRevisadoPor['numero_solicitud']
    $solicitadoPor = $rowRevisadoPor['solicitado_por'];
    $numero_solicitud = $rowRevisadoPor['numero_solicitud'];

    // Puedes usar estos valores según sea necesario
} else {
    echo json_encode(['error' => 'Error al preparar consulta para verificar el usuario.']);
    exit;
}


// Sube el archivo a Cloudflare R2
$fileBinary = file_get_contents($_FILES['certificado_de_analisis_externo']['tmp_name']);
$fileName = 'certificados/' . $_FILES['certificado_de_analisis_externo']['name'];

$params = [
    'fileBinary' => $fileBinary,
    'folder' => 'certificado_de_analisis_externo',
    'fileName' => $fileName
];

$uploadStatus = setFile($params);
$uploadResult = json_decode($uploadStatus, true);

if (isset($uploadResult['success']) && $uploadResult['success'] !== false) {
    $fileURL = $uploadResult['success']['ObjectURL'];

    $consultaSQL = "UPDATE calidad_analisis_externo SET  
        resultados_analisis = ?, 
        laboratorio_nro_analisis = ?, 
        laboratorio_fecha_analisis = ?, 
        fecha_entrega = ?, 
        url_certificado_de_analisis_externo = ?, 
        estado = 'Pendiente liberación productos'
        WHERE id = ?";

    $stmt = mysqli_prepare($link, $consultaSQL);
    if (!$stmt) {
        echo json_encode(['error' => 'Error al preparar consulta.']);
        exit;
    }

    $resultados_analisis = json_encode($data['resultados_analisis']);
    $laboratorio_nro_analisis = $data['laboratorio_nro_analisis'];
    $laboratorio_fecha_analisis = $data['laboratorio_fecha_analisis'];
    $fecha_entrega = $data['fecha_entrega'];

    mysqli_stmt_bind_param($stmt, 'sssssi', 
        $resultados_analisis, 
        $laboratorio_nro_analisis, 
        $laboratorio_fecha_analisis, 
        $fecha_entrega, 
        $fileURL, 
        $idAnalisisExterno
    );
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if (mysqli_error($link)) {
        echo json_encode(['error' => 'Error al ejecutar consulta.']);
        exit;
    }

    unset($_SESSION['buscar_por_ID']);
    $_SESSION['buscar_por_ID'] = $idAnalisisExterno;
    echo json_encode(['exito' => true]);

    finalizarTarea($_SESSION['usuario'], $idAnalisisExterno, 'calidad_analisis_externo', 'Ingresar resultados Laboratorio');
    registrarTarea(7, $_SESSION['usuario'], $solicitadoPor, 'Emitir Acta de Liberación de solicitud: ' . $numero_solicitud, 2, 'Emitir acta de liberación', $idAnalisisExterno, 'calidad_analisis_externo');

} else {
    echo json_encode(['error' => 'Error al subir el certificado: ' . $uploadResult['error']]);
}
?>