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
        getUsuario();
        break;
    case 'POST':
        updateUsuario();
        break;
    default:
        header('HTTP/1.1 405 Method Not Allowed');
        exit;
}
function limpiarDato($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

function updateSession($usuario)
{
    global $link;
    $query = "SELECT 
            usr.id, usr.nombre, usr.foto_firma, usr.foto_perfil, 
            usr.correo, usr.cargo, rol.nombre as rol
            FROM `usuarios` as usr 
            LEFT JOIN roles as rol 
            ON usr.rol_id=rol.id WHERE usuario = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['id_usuario'] = $row['id'];
        $_SESSION['nombre'] = $row['nombre'];
        $_SESSION['foto_firma'] = $row['foto_firma'];
        $_SESSION['foto_perfil'] = $row['foto_perfil'];
        $_SESSION['correo'] = $row['correo'];
        $_SESSION['certificado'] = $row['ruta_registroPrestadoresSalud'];
        $_SESSION['cargo'] = $row['cargo'];
        $_SESSION['rol'] = $row['rol'];
    }

    mysqli_stmt_close($stmt);
}
function getUsuario()
{

    global $link, $usuario;
    $query = "SELECT id, nombre, nombre_corto, foto_perfil,foto_firma, ruta_registroPrestadoresSalud, cargo FROM `usuarios` WHERE usuario = ?";
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
            'foto_firma' => $row['foto_firma'],
            'certificado' => $row['ruta_registroPrestadoresSalud'],
            'cargo' => $row['cargo']
        ];
    }

    mysqli_stmt_close($stmt);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['usuario' => $data], JSON_UNESCAPED_UNICODE);
}

function updateImage($file, $type)
{
    global $link, $usuario;
    $response = [];

    if (!$file) {
        return json_encode(['status' => 'error', 'message' => 'No se recibió archivo.']);
    }
    // Validar el tipo MIME del archivo

    $usuarioFilename = str_replace(' ', '_', $usuario);
    $timestamp = time();
    $fileName = $usuarioFilename . '_' . $timestamp . '.webp';

    $fileBinary = file_get_contents($file['tmp_name']);

    if (!$fileBinary) {
        return json_encode(['status' => 'error', 'message' => 'Error al leer el archivo.']);
    }

    $params = [
        'fileBinary' => $fileBinary,
        'folder' => $type,
        'fileName' => $fileName
    ];

    $uploadStatus = setFile($params);
    $uploadResult = json_decode($uploadStatus, true);

    $response['uploadResult'] = $uploadResult;

    if (isset($uploadResult['success']) && $uploadResult['success'] !== false) {
        $fileURL = $uploadResult['success']['ObjectURL'];
        $response['fileURL'] = $fileURL;

        $query = "UPDATE `usuarios` SET $type = ? WHERE usuario = ?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "ss", $fileURL, $usuario);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $response['status'] = 'success';
            $response['message'] = ucfirst($type) . ' actualizada correctamente';
            $response['url'] = $fileURL;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No se pudo actualizar la ' . $type;
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error al subir la ' . $type . ': ' . $uploadResult['error'];
    }

    return json_encode($response);
}

function updateCertificado($file)
{
    global $link, $usuario;
    //ruta_registroPrestadoresSalud
    if (!$file) {
        return json_encode(['status' => 'error', 'message' => 'No se recibió archivo.']);
    }
    // Validar el tipo MIME del archivo
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if ($mimeType !== 'application/pdf') {
        return json_encode(['status' => 'error', 'message' => 'El archivo no es un PDF.']);
    }

    $usuarioFilename = str_replace(' ', '_', $usuario);
    $timestamp = time();
    $fileName = 'registroPrestadoresSalud_' . $usuarioFilename . '_' . $timestamp . '.pdf';

    $fileBinary = file_get_contents($file['tmp_name']);

    if (!$fileBinary) {
        return json_encode(['status' => 'error', 'message' => 'Error al leer el archivo.']);
    }

    $params = [
        'fileBinary' => $fileBinary,
        'folder' => 'certificados',
        'fileName' => $fileName
    ];

    $uploadStatus = setFile($params);
    $uploadResult = json_decode($uploadStatus, true);

    $response['uploadResult'] = $uploadResult;

    if (isset($uploadResult['success']) && $uploadResult['success'] !== false) {
        $fileURL = $uploadResult['success']['ObjectURL'];
        $response['fileURL'] = $fileURL;

        $query = "UPDATE `usuarios` SET ruta_registroPrestadoresSalud = ? WHERE usuario = ?";

        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "ss", $fileURL, $usuario);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $response['status'] = 'success';
            $response['message'] = 'certificado  actualizado correctamente';
            $response['url'] = $fileURL;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No se pudo actualizar el certificado';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error al subir el ' . 'certificado' . ': ' . $uploadResult['error'];
    }

    return json_encode($response);
}

function updatePassword($pass, $newPass)
{
    global $link, $usuario;

    if ($newPass == '' || $newPass == null) {
        return json_encode(['status' => 'error', 'message' => 'Las contraseñas no coinciden.']);
    }

    $query = "SELECT id, contrasena FROM `usuarios`  WHERE usuario = ?";

    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $userFetch = mysqli_fetch_assoc($result);

    if ($usuario && password_verify($pass, $userFetch['contrasena'])) {
        $contrasenaHash = password_hash($newPass, PASSWORD_DEFAULT);
        $update = mysqli_prepare($link, "UPDATE usuarios SET contrasena = ? WHERE id = ?");
        mysqli_stmt_bind_param($update, "si", $contrasenaHash, $userFetch['id']);
        $update_result = mysqli_stmt_execute($update);
        mysqli_stmt_close($update);

        return $update_result
            ? json_encode(['status' => 'success', 'message' => 'Contraseña actualizada correctamente.'])
            : json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la contraseña.']);

    }else{
        return json_encode(['status' => 'error', 'message' => 'Contraseña incorrecta.']);
    }
}

function updateUsuario()
{
    $response = [];

    // Procesar imagen de perfil
    if (isset($_FILES['imagen'])) {
        if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $response['foto'] = json_decode(updateImage($_FILES['imagen'], 'foto_perfil'), true);
        } else {
            $response['foto_error'] = 'Error en imagen: ' . $_FILES['imagen']['error'];
        }
    }

    // Procesar firma
    if (isset($_FILES['firma'])) {
        if ($_FILES['firma']['error'] === UPLOAD_ERR_OK) {
            $response['firma'] = json_decode(updateImage($_FILES['firma'], 'foto_firma'), true);
        } else {
            $response['firma_error'] = 'Error en firma: ' . $_FILES['firma']['error'];
        }
    }

    // Procesar certificado
    if (isset($_FILES['certificado'])) {
        if ($_FILES['certificado']['error'] === UPLOAD_ERR_OK) {
            $response['certificado'] = json_decode(updateCertificado($_FILES['certificado']), true);
        } else {
            $response['certificado_error'] = 'Error en certificado: ' . $_FILES['certificado']['error'];
        }
    }

    // Procesar cambio de contraseña
    if (!empty($_POST['password']) && !empty($_POST['newPassword'])) {
        $oldPassword = limpiarDato($_POST['password']);
        $newPassword = limpiarDato($_POST['newPassword']);
        $response['password'] = updatePassword($oldPassword, $newPassword);
    }

    // Verificar y actualizar sesión si hay respuestas
    if (!empty($response)) {
        updateSession($_SESSION['usuario']);
        echo json_encode(['status' => 'partial success', 'response' => $response]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se pudieron procesar los archivos.']);
    }
}

