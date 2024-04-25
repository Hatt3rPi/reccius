<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

$method = $_SERVER['REQUEST_METHOD'];
$usuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : null;

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
function updateUsuario($link, $usuario)
{
    // $nombre = $_POST['nombre'] ?? null;
    // $nombre_corto = $_POST['nombre_corto'] ?? null;
    // $cargo = $_POST['cargo'] ?? null;
    $foto_perfil = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

        include_once '../cloud/R2_manager.php';

        $imagen = $_FILES['imagen'];
        $folder = 'usuarios';
        $usuarioFilename = str_replace(' ', '_', $usuario);
        $timestamp = time();
        $fileName = $usuarioFilename . '_' . $timestamp . '.webp';

        $fileBinary = file_get_contents($imagen['tmp_name']);
        $params = [
            'fileBinary' => $fileBinary,
            'folder' => $folder,
            'fileName' => $fileName
        ];

        $uploadStatus = setFile($params);
        $uploadResult = json_decode($uploadStatus, true);

        if (isset($uploadResult['success'])) {
            $foto_perfil = $uploadResult['success']['ObjectURL'];
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al subir la imagen: ' . $uploadResult['error']]);
            return;
        }
    }

    // if (isset($_POST['nuevaPassword'], $_POST['passwordActual'])) {

    //     $passwordActual = $_POST['passwordActual'];
    //     $nuevaPassword = password_hash($_POST['nuevaPassword'], PASSWORD_DEFAULT);

    //     // Actualizar la contraseña en la base de datos
    //     $queryPassword = "UPDATE `usuarios` SET password = ? WHERE usuario = ? AND password = ?";
    //     $stmtPassword = mysqli_prepare($link, $queryPassword);
    //     mysqli_stmt_bind_param($stmtPassword, "sss", $nuevaPassword, $usuario, $passwordActual);
    //     mysqli_stmt_execute($stmtPassword);

    //     if (mysqli_stmt_affected_rows($stmtPassword) > 0) {
    //         echo json_encode(['status' => 'success', 'message' => 'Contraseña actualizada correctamente']);
    //     } else {
    //         echo json_encode(['status' => 'error', 'message' => 'Contraseña actual no coincide o no se pudo actualizar']);
    //     }

    //     mysqli_stmt_close($stmtPassword);
    // }


    // Actualizar la base de datos con todos los campos nuevos
    if ($foto_perfil) {
        
        $query = "UPDATE `usuarios` SET foto_perfil = ? WHERE usuario = ?";
        $stmt = mysqli_prepare($link, $query);
        // mysqli_stmt_bind_param($stmt, "sssss", $nombre, $nombre_corto, $foto_perfil, $cargo, $usuario);
        mysqli_stmt_bind_param($stmt, "ss", $foto_perfil, $usuario);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Perfil actualizado correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el perfil']);
        }
    }
}
