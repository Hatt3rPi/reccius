<?php
//archivo pages\backend\usuario\modifica_perfilFETCH.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
include '/home/customw2/librerias/phpqrcode/qrlib.php';
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
function limpiarDato($dato)
{
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

function updateSession($usuario)
{ 
    global $link;
    $query = "SELECT 
            usr.id, usr.nombre, usr.foto_perfil, 
            usr.correo, usr.cargo, rol.nombre as rol,
            CASE
                WHEN usr.qr_documento IS NOT NULL THEN usr.qr_documento
                WHEN usr.foto_firma IS NOT NULL THEN usr.foto_firma
                ELSE 'https://customware.fabarca212.workers.dev/assets/firma_no_proporcionada.webp'
            END as foto_firma
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
        $_SESSION['certificado_qr'] = $row['qr_documento'];
        $_SESSION['cargo'] = $row['cargo'];
        $_SESSION['rol'] = $row['rol'];
    }

    mysqli_stmt_close($stmt);
}
function getUsuario()
{
    global $link, $usuario;
    $query = "SELECT id, nombre, nombre_corto, foto_perfil,foto_firma, ruta_registroPrestadoresSalud, 	qr_documento, cargo FROM `usuarios` WHERE usuario = ?";
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
            'certificado_qr' => $row['qr_documento'],
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

    if (isset($uploadResult['success']) && $uploadResult['success'] !== false) {
        $fileURL = $uploadResult['success']['ObjectURL'];
        $response['fileURL'] = $fileURL;

        // Generar el QR Code
        ob_start();
        QRcode::png($fileURL);
        $imageString = ob_get_contents();
        ob_end_clean();

        // Subir el QR Code a Cloudflare R2
        $qrFileName = 'qr_documento_' . $usuarioFilename . '_' . $timestamp . '.png';
        $params = [
            'fileBinary' => $imageString,
            'folder' => 'certificados_qr',
            'fileName' => $qrFileName
        ];

        $qrUploadStatus = setFile($params);
        $qrUploadResult = json_decode($qrUploadStatus, true);

        if (isset($qrUploadResult['success']) && $qrUploadResult['success'] !== false) {
            $qrFileURL = $qrUploadResult['success']['ObjectURL'];
            $response['qrFileURL'] = $qrFileURL;

            // Actualizar la base de datos con la URL del certificado y del QR
            $query = "UPDATE `usuarios` SET ruta_registroPrestadoresSalud = ?, qr_documento = ? WHERE usuario = ?";
            $stmt = mysqli_prepare($link, $query);
            mysqli_stmt_bind_param($stmt, "sss", $fileURL, $qrFileURL, $usuario);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $response['status'] = 'success';
                $response['message'] = 'Certificado y QR actualizados correctamente';
                $response['url'] = $fileURL;
                $response['qr_url'] = $qrFileURL;
            } else {
                $response['status'] = 'error';
                $response['message'] = 'No se pudo actualizar la base de datos con el certificado y el QR';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error al subir el QR: ' . $qrUploadResult['error'];
            $response['url'] = $fileURL;
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error al subir el certificado: ' . $uploadResult['error'];
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
    mysqli_stmt_close($stmt);

    if ($usuario && password_verify($pass, $userFetch['contrasena'])) {
        $contrasenaHash = password_hash($newPass, PASSWORD_DEFAULT);
        $update = mysqli_prepare($link, "UPDATE usuarios SET contrasena = ? WHERE id = ?");
        mysqli_stmt_bind_param($update, "si", $contrasenaHash, $userFetch['id']);
        $update_result = mysqli_stmt_execute($update);
        mysqli_stmt_close($update);

        return $update_result
            ? json_encode(['status' => 'success', 'message' => 'Contraseña actualizada correctamente.'])
            : json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la contraseña.']);
    } else {
        return json_encode(['status' => 'error', 'message' => 'Contraseña incorrecta.']);
    }
}

function updateDatosPersonal($cargo, $nombre, $nombre_corto) {
    global $link, $usuario;

    // Preparar la consulta para actualizar los datos personales del usuario
    $query = "UPDATE `usuarios` SET nombre = ?, nombre_corto = ?, cargo = ? WHERE usuario = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $nombre, $nombre_corto, $cargo, $usuario);

    // Ejecutar la consulta y verificar si fue exitosa
    if (mysqli_stmt_execute($stmt)) {
        $response = [
            'status' => 'success',
            'message' => 'Datos personales actualizados correctamente.'
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'No se pudieron actualizar los datos personales.'
        ];
    }

    // Cerrar la consulta preparada
    mysqli_stmt_close($stmt);

    // Devolver la respuesta como JSON
    return json_encode($response);
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

    // Procesar actualización de datos personales
    if (!empty($_POST['cargo']) && !empty($_POST['nombre']) && !empty($_POST['nombre_corto'])) {
        $cargo = limpiarDato($_POST['cargo']);
        $nombre = limpiarDato($_POST['nombre']);
        $nombre_corto = limpiarDato($_POST['nombre_corto']);
        $response['datos_personales'] = updateDatosPersonal($cargo, $nombre, $nombre_corto);
    }

    // Verificar y actualizar sesión si hay respuestas
    if (!empty($response)) {
        updateSession($_SESSION['usuario']);
        unset($_SESSION['go_to']);
        $_SESSION['go_to'] = 'modifica_perfil.php';
        echo json_encode(['status' => 'success', 'response' => $response]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se pudieron procesar los archivos.']);
    }
}
 