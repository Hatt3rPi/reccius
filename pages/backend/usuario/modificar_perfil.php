<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

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
    if ($fotoPerfil["size"] > 5000000) { // 5MB
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

    // Cambio de foto de perfil
    if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === UPLOAD_ERR_OK) {
        $resultadoCambioFoto = cambiarFotoPerfil($link, $usuario, $_FILES['fotoPerfil']);
        if ($resultadoCambioFoto !== "La foto de perfil ha sido actualizada con éxito.") {
            $mensajeError .= $resultadoCambioFoto;
        }
    } else {
        $mensajeError .= "Archivo de foto de perfil no proporcionado. ";
    }

    if ($mensajeError) {
        echo trim($mensajeError);
    } else {
        echo "Perfil actualizado con éxito.";
    }

    exit;
    //header("Location: ../../index.html");
}
?>