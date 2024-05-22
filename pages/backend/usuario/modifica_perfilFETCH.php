<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
include_once '../cloud/R2_manager.php';

$method = $_SERVER['REQUEST_METHOD'];
$usuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : null;

if (!$usuario) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => 'Acceso denegado']);
    exit;
}

switch ($method) {
    case 'GET':
        getUsuario($link, $usuario);
        break;
    case 'POST':
        updateUsuario($link, $usuario);
        break;
    default:
        header('HTTP/1.1 405 Method Not Allowed');
        exit;
}

function getUsuario($link, $usuario)
{
    $query = "SELECT id, nombre, nombre_corto, foto_perfil, ruta_registroPrestadoresSalud, cargo FROM `usuarios` WHERE usuario = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $data = [];

    if ($row = mysqli_fetch_assoc($result)) {
        $data = [
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'nombre_corto' => $row['nombre_corto'],
            'foto_perfil' => $row['foto_perfil'],
            'certificado' => $row['ruta_registroPrestadoresSalud'],
            'cargo' => $row['cargo']
        ];
    }

    mysqli_stmt_close($stmt);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['usuario' => $data], JSON_UNESCAPED_UNICODE);
}
function updateImage($link, $usuario, $file, $type)
{
    if (!$file) {
        echo json_encode(['status' => 'error', 'message' => 'No se recibió archivo.']);
        return;
    }

    $folder = 'usuarios';
    $usuarioFilename = str_replace(' ', '_', $usuario);
    $timestamp = time();
    $fileName = $usuarioFilename . '_' . $timestamp . '.webp';

    $fileBinary = file_get_contents($file['tmp_name']);

    if (!$fileBinary) {
        echo json_encode(['status' => 'error', 'message' => 'Error al leer el archivo.']);
        return;
    }

    $params = [
        'fileBinary' => $fileBinary,
        'folder' => $folder,
        'fileName' => $fileName
    ];

    $uploadStatus = setFile($params);
    $uploadResult = json_decode($uploadStatus, true);

    if (isset($uploadResult['success']) && $uploadResult['success'] !== false) {
        $fileURL = $uploadResult['success']['ObjectURL'];

        $column = $type === 'foto' ? 'foto_perfil' : 'foto_firma';
        $query = "UPDATE `usuarios` SET $column = ? WHERE usuario = ?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "ss", $fileURL, $usuario);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo json_encode(['status' => 'success', 'message' => ucfirst($type) . ' actualizada correctamente', 'url' => $fileURL]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la ' . $type]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al subir la ' . $type . ': ' . $uploadResult['error']]);
    }
}

function updateUsuario($link, $usuario)
{
    $response = [];

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        ob_start();
        updateImage($link, $usuario, $_FILES['imagen'], 'foto');
        $response['foto'] = json_decode(ob_get_clean(), true);
    } else {
        $response['foto_error'] = 'Error en imagen: ' . (isset($_FILES['imagen']['error']) ? $_FILES['imagen']['error'] : 'No se encontró el archivo de imagen.');
    }

    if (isset($_FILES['firma']) && $_FILES['firma']['error'] === UPLOAD_ERR_OK) {
        ob_start();
        updateImage($link, $usuario, $_FILES['firma'], 'firma');
        $response['firma'] = json_decode(ob_get_clean(), true);
    } else {
        $response['firma_error'] = 'Error en firma: ' . (isset($_FILES['firma']['error']) ? $_FILES['firma']['error'] : 'No se encontró el archivo de firma.');
    }

    if (!empty($response)) {
        echo json_encode(['status' => 'partial success', 'response' => $response]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se pudieron procesar los archivos.']);
    }
}
?>