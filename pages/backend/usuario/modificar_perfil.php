<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Asumiendo que la conexión a la base de datos está en $link

function validarFortalezaPassword($password) {
    // Aquí puedes agregar las reglas que consideres para validar la contraseña
    return strlen($password) >= 8; // Ejemplo: longitud mínima de 8 caracteres
}

function validarFormatoPassword($password) {
    // Validar formato de la contraseña
    return preg_match('/[a-zA-Z].*[0-9]|[0-9].*[a-zA-Z]/', $password) &&
           preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password);
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

    return $fila ? "La contraseña actual no es correcta." : "Usuario no encontrado.";
}

function cambiarFotoPerfil($link, $usuario, $fotoPerfil) {
    if ($fotoPerfil['error'] === UPLOAD_ERR_OK) {
        $directorioDestino = "../../../uploads/perfiles/";
        $nombreArchivo = $directorioDestino . basename($fotoPerfil['name']);
        $tipoArchivo = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
        $check = getimagesize($fotoPerfil['tmp_name']);

        if (!$check || !in_array($tipoArchivo, ['jpg', 'png', 'jpeg', 'gif'])) {
            return "El archivo no es una imagen válida o no tiene un formato permitido.";
        }

        if ($fotoPerfil["size"] > 5000000) { // 5MB
            return "El archivo es demasiado grande.";
        }

        if (move_uploaded_file($fotoPerfil['tmp_name'], $nombreArchivo)) {
            $stmt = mysqli_prepare($link, "UPDATE usuarios SET foto_perfil = ? WHERE usuario = ?");
            mysqli_stmt_bind_param($stmt, "ss", $nombreArchivo, $usuario);
            $exito = mysqli_stmt_execute($stmt);
            return $exito ? "La foto de perfil ha sido actualizada con éxito." : "Error al actualizar la foto de perfil en la base de datos.";
        }

        return "Hubo un error al subir el archivo.";
    }

    return "Error al subir el archivo: " . $fotoPerfil['error'];
}

if (isset($_POST['modificarPerfil'])) {
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);

    // Variables para verificar si se realizará alguna operación
    $cambiarContrasena = !empty($_POST['passwordActual']) && !empty($_POST['nuevaPassword']) && !empty($_POST['confirmarPassword']);
    $cambiarFoto = isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === UPLOAD_ERR_OK;

    // Procesar cambio de contraseña si se proporcionaron los campos necesarios
    if ($cambiarContrasena) {
        $passwordActual = filter_input(INPUT_POST, 'passwordActual', FILTER_SANITIZE_STRING);
        $nuevaPassword = filter_input(INPUT_POST, 'nuevaPassword', FILTER_SANITIZE_STRING);
        $confirmarPassword = filter_input(INPUT_POST, 'confirmarPassword', FILTER_SANITIZE_STRING);

        if ($nuevaPassword === $confirmarPassword) {
            echo cambiarPassword($link, $usuario, $passwordActual, $nuevaPassword);
        } else {
            echo "Las contraseñas no coinciden.";
            exit;
        }
    }

    // Procesar cambio de foto de perfil si se proporcionó un archivo
    if ($cambiarFoto) {
        echo cambiarFotoPerfil($link, $usuario, $_FILES['fotoPerfil']);
    }

    // Verificar si se realizó alguna operación
    if (!$cambiarContrasena && !$cambiarFoto) {
        echo "No se ha proporcionado información para actualizar.";
        exit;
    }

    // Redirigir o manejar la respuesta como necesites
    // header("Location: /path/to/success_page.php");
    exit();
}

?>
