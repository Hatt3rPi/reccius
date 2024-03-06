<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
include '/home/customw2/librerias/phpqrcode/qrlib.php'; // Asegúrate de ajustar la ruta según donde coloques la biblioteca

function generarQR($usuario, $rutaRegistro){
    $contenidoQR = 'https://customware.cl/reccius/documentos_publicos/' . $rutaRegistro;
    $nombreArchivoQR = '../../../documentos_publicos/qr_documento_' . $usuario . '.png'; // Ajusta la ruta según sea necesario
    QRcode::png($contenidoQR, $nombreArchivoQR, QR_ECLEVEL_L, 3, 2);
    return 'qr_documento_' . $usuario . '.png'; // Devuelve la ruta del archivo QR para su uso posterior si es necesario
}
function cambiarCertificado($link, $usuario, $certificado) {
    if ($certificado['error'] !== UPLOAD_ERR_OK) {
        return "Error al subir el archivo: " . $certificado['error'];
    }

    $directorioDestino = "../../../documentos_publicos/";
    if (!is_writable($directorioDestino) || !is_dir($directorioDestino)) {
        return "Error: El directorio de destino no es escribible o no existe.";
    }

    $nombreArchivoTemporal = $certificado['tmp_name'];
    $tipoArchivo = strtolower(pathinfo($certificado['name'], PATHINFO_EXTENSION));
    $nombreArchivo = $directorioDestino . $nombreArchivoTemporal ;
    $nombreArchivo_n = $nombreArchivoTemporal ;

    if ($certificado["size"] > 10000000) { // Tamaño máximo de 10 MB
        return "El archivo es demasiado grande.";
    }

    if ($tipoArchivo != 'pdf') {
        return "El archivo no es un PDF válido.";
    }

    if (!move_uploaded_file($nombreArchivoTemporal, $nombreArchivo)) {
        return "Hubo un error al guardar el archivo.";
    }

    $ruta_qr=generarQR($usuario, $nombreArchivo_n);

    $stmt = mysqli_prepare($link, "UPDATE usuarios SET ruta_registroPrestadoresSalud = ?, qr_documento = ? WHERE usuario = ?");
    mysqli_stmt_bind_param($stmt, "sss", $nombreArchivo_n, $ruta_qr, $usuario);
    if (!mysqli_stmt_execute($stmt)) {
        return "Error al actualizar la ruta del certificado en la base de datos.";
    }

    return "Certificado actualizado con éxito.";
}
function actualizarInformacionUsuario($link, $usuario, $nombre, $nombre_corto, $cargo) {
    $stmt = mysqli_prepare($link, "UPDATE usuarios SET nombre = ?, nombre_corto = ?, cargo = ? WHERE usuario = ?");
    mysqli_stmt_bind_param($stmt, "ssss", $nombre, $nombre_corto, $cargo, $usuario);
    $exito = mysqli_stmt_execute($stmt);
    return $exito ? "Información de usuario actualizada con éxito." : "Error al actualizar la información del usuario.";
}
function validarFortalezaPassword($password) {
    // Agrega aquí tus propias reglas de validación de contraseña
    return strlen($password) >= 8;
}

function validarFormatoPassword($password) {
    // Agrega aquí tus propias reglas de validación de formato de contraseña
    return preg_match('/[a-zA-Z].*[0-9]|[0-9].*[a-zA-Z]/', $password);
}

function cambiarPassword($link, $usuario, $passwordActual, $nuevaPassword) {
    if (!validarFortalezaPassword($nuevaPassword) || !validarFormatoPassword($nuevaPassword)) {
        return "La nueva contraseña no cumple con los requisitos de seguridad y formato.";
    }

    $stmt = mysqli_prepare($link, "SELECT contrasena FROM usuarios WHERE usuario = ?");
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $fila = mysqli_fetch_assoc($result);

    if ($fila && password_verify($passwordActual, $fila['contrasena'])) {
        $nuevaPasswordHash = password_hash($nuevaPassword, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($link, "UPDATE usuarios SET contrasena = ? WHERE usuario = ?");
        mysqli_stmt_bind_param($stmt, "ss", $nuevaPasswordHash, $usuario);
        $exito = mysqli_stmt_execute($stmt);
        return $exito ? "La contraseña ha sido actualizada con éxito." : "Error al actualizar la contraseña.";
    }

    return "La contraseña actual no es correcta o el usuario no fue encontrado.";
}

function cambiarFotoPerfil($link, $usuario, $fotoPerfil) {
    if ($fotoPerfil['error'] !== UPLOAD_ERR_OK) {
        return "Error al subir el archivo: " . $fotoPerfil['error'];
    }

    $directorioDestino = "../../../assets/uploads/perfiles/";
    if (!is_writable($directorioDestino) || !is_dir($directorioDestino)) {
        return "Error: El directorio de destino no es escribible o no existe. " . $directorioDestino;
    }

    $nombreArchivoTemporal = $fotoPerfil['tmp_name'];
    $tipoArchivo = strtolower(pathinfo($fotoPerfil['name'], PATHINFO_EXTENSION));
    $nombreArchivo = $directorioDestino . "perfil_" . $usuario . ".png";
    $nombreArchivo_n = "perfil_" . $usuario . ".png";
    if ($fotoPerfil["size"] > 10000000) { // 5MB
        return "El archivo es demasiado grande.";
    }

    $check = getimagesize($nombreArchivoTemporal);
    if (!$check || !in_array($tipoArchivo, ['jpg', 'png', 'jpeg', 'gif'])) {
        return "El archivo no es una imagen válida o no tiene un formato permitido.";
    }

    // Crear una imagen desde el archivo original
    switch ($tipoArchivo) {
        case 'jpg':
        case 'jpeg':
            $imagenOriginal = imagecreatefromjpeg($nombreArchivoTemporal);
            break;
        case 'png':
            $imagenOriginal = imagecreatefrompng($nombreArchivoTemporal);
            break;
        case 'gif':
            $imagenOriginal = imagecreatefromgif($nombreArchivoTemporal);
            break;
        default:
            return "Formato de archivo no soportado.";
    }

    if (!$imagenOriginal) {
        return "Error al procesar el archivo de imagen.";
    }

    // Redimensionar la imagen
    $imagenRedimensionada = imagecreatetruecolor(40, 40);
    imagecopyresampled($imagenRedimensionada, $imagenOriginal, 0, 0, 0, 0, 40, 40, $check[0], $check[1]);

    // Guardar la imagen redimensionada como PNG
    if (!imagepng($imagenRedimensionada, $nombreArchivo)) {
        return "Hubo un error al guardar la imagen redimensionada.";
    }

    // Liberar recursos de imagen
    imagedestroy($imagenOriginal);
    imagedestroy($imagenRedimensionada);

    // Actualizar la ruta de la imagen en la base de datos
    $stmt = mysqli_prepare($link, "UPDATE usuarios SET foto_perfil = ? WHERE usuario = ?");
    mysqli_stmt_bind_param($stmt, "ss", $nombreArchivo_n, $usuario);
    if (!mysqli_stmt_execute($stmt)) {
        return "Error al actualizar la foto de perfil en la base de datos.";
    }

    return "La foto de perfil ha sido actualizada con éxito.";
}
if (isset($_POST['modificarPerfil'])) {
    $usuario = $_SESSION['usuario'] ?? $_POST['usuario'];
    $mensajeError = "";

    // Cambio de contraseña
if (isset($_POST['editarContrasena']) && $_POST['editarContrasena'] == '1') {
    if (!empty($_POST['passwordActual']) && !empty($_POST['nuevaPassword']) && !empty($_POST['confirmarPassword'])) {
        $passwordActual = $_POST['passwordActual'];
        $nuevaPassword = $_POST['nuevaPassword'];
        $confirmarPassword = $_POST['confirmarPassword'];

        if ($nuevaPassword === $confirmarPassword) {
            $resultadoCambio = cambiarPassword($link, $usuario, $passwordActual, $nuevaPassword);
            if ($resultadoCambio !== "La contraseña ha sido actualizada con éxito.") {
                $mensajeError .= $resultadoCambio;
            }
        } else {
            $mensajeError .= "Las contraseñas no coinciden. ";
        }
    } else {
        $mensajeError .= "Información de contraseña no proporcionada. ";
    }
}
if (isset($_POST['editarInfo']) && $_POST['editarInfo'] == '1') {
    if (!empty($_POST['nombre']) && !empty($_POST['nombre_corto']) && !empty($_POST['cargo'])) {
        $resultadoActualizarInfo = actualizarInformacionUsuario($link, $usuario, $_POST['nombre'], $_POST['nombre_corto'], $_POST['cargo']);
        if ($resultadoActualizarInfo !== "Información de usuario actualizada con éxito.") {
            $mensajeError .= $resultadoActualizarInfo;
        }
    } else {
        $mensajeError .= "Información de usuario incompleta. ";
    }
}

// Cambio de foto de perfil
if (isset($_POST['editarFoto']) && $_POST['editarFoto'] == '1') {
    if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === UPLOAD_ERR_OK) {
        $resultadoCambioFoto = cambiarFotoPerfil($link, $usuario, $_FILES['fotoPerfil']);
        if ($resultadoCambioFoto !== "La foto de perfil ha sido actualizada con éxito.") {
            $mensajeError .= $resultadoCambioFoto;
        }
    } else {
        $mensajeError .= "Archivo de foto de perfil no proporcionado. ";
    }
}
if (isset($_POST['editarCertificado']) && $_POST['editarCertificado'] == '1') {
    error_log(print_r($_POST, true));
    if (isset($_FILES['certificado']) && $_FILES['certificado']['error'] === UPLOAD_ERR_OK) {
        $resultadoCambioCertificado = cambiarCertificado($link, $usuario, $_FILES['certificado']);
        if ($resultadoCambioCertificado !== "Certificado actualizado con éxito.") {
            $mensajeError .= $resultadoCambioCertificado;
        }
    } else {
        $mensajeError .= "Archivo de certificado no proporcionado. ";
    }
}
    // Agrega aquí las comprobaciones y lógica para las demás secciones (Información de usuario, Certificado, etc.)

        // Al final de tu script PHP, reemplaza el echo y header por:
        if (!empty($mensajeError)) {
            echo json_encode(["success" => false, "message" => trim($mensajeError)]);
        } else {
            echo json_encode(["success" => true, "message" => "Perfil actualizado con éxito."]);
        }
        exit();
        

}
?>