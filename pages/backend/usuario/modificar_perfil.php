<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

function cambiarPassword($link, $usuario, $passwordActual, $nuevaPassword) {
    // Primero, verifica la contraseña actual del usuario

    // Prepara la consulta para obtener la contraseña actual del usuario
    $stmt = mysqli_prepare($link, "SELECT contrasena FROM usuarios WHERE usuario = ?");
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $fila = mysqli_fetch_assoc($result);

    if ($fila) {
        $contrasenaHash = $fila['contrasena'];
        if (password_verify($passwordActual, $contrasenaHash)) {
            // Si la contraseña actual es correcta, procede a cambiarla por la nueva

            // Asegúrate de que la nueva contraseña se almacene de manera segura
            $nuevaPasswordHash = password_hash($nuevaPassword, PASSWORD_DEFAULT);

            // Prepara la consulta para actualizar la contraseña
            $stmt = mysqli_prepare($link, "UPDATE usuarios SET contrasena = ? WHERE usuario = ?");
            mysqli_stmt_bind_param($stmt, "ss", $nuevaPasswordHash, $usuario);
            $exito = mysqli_stmt_execute($stmt);

            if ($exito) {
                return "La contraseña ha sido actualizada con éxito.";
            } else {
                return "Error al actualizar la contraseña.";
            }
        } else {
            return "La contraseña actual no es correcta.";
        }
    } else {
        return "Usuario no encontrado.";
    }
}

reccius/pages\backend\usuario\modificar_perfil.php
function cambiarFotoPerfil($link, $usuario, $fotoPerfil) {
    $directorioDestino = "../../../../uploads/perfiles/"; // Ajusta esta ruta
    $nombreArchivo = $directorioDestino . basename($fotoPerfil['name']);
    $tipoArchivo = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

    // Verifica si el archivo es realmente una imagen
    $check = getimagesize($fotoPerfil["tmp_name"]);
    if($check === false) {
        return "El archivo no es una imagen.";
    }

    // Verifica el tamaño del archivo
    if ($fotoPerfil["size"] > 500000) { // 500KB, ajusta según sea necesario
        return "El archivo es demasiado grande.";
    }

    // Permite ciertos formatos de archivo
    if($tipoArchivo != "jpg" && $tipoArchivo != "png" && $tipoArchivo != "jpeg" && $tipoArchivo != "gif" ) {
        return "Solo se permiten archivos JPG, JPEG, PNG y GIF.";
    }

    // Intenta guardar el archivo
    if (move_uploaded_file($fotoPerfil["tmp_name"], $nombreArchivo)) {
        // Aquí actualiza la referencia en la base de datos
        $stmt = mysqli_prepare($link, "UPDATE usuarios SET foto_perfil = ? WHERE usuario = ?");
        mysqli_stmt_bind_param($stmt, "ss", $nombreArchivo, $usuario);
        $exito = mysqli_stmt_execute($stmt);

        if ($exito) {
            return "La foto de perfil ha sido actualizada con éxito.";
        } else {
            return "Error al actualizar la foto de perfil en la base de datos.";
        }
    } else {
        return "Hubo un error al subir el archivo.";
    }
}

if (isset($_POST['modificarPerfil'])) {
    $usuario = $_SESSION['usuario']; // Asumiendo que el nombre de usuario está almacenado en la sesión
    $passwordActual = $_POST['passwordActual'];
    $nuevaPassword = $_POST['nuevaPassword'];
    $confirmarPassword = $_POST['confirmarPassword'];

    // Cambio de contraseña
    if ($nuevaPassword === $confirmarPassword) {
        echo cambiarPassword($link, $usuario, $passwordActual, $nuevaPassword);
    } else {
        echo "Las contraseñas no coinciden.";
    }

    // Cambio de foto de perfil
    if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === UPLOAD_ERR_OK) {
        echo cambiarFotoPerfil($link, $usuario, $_FILES['fotoPerfil']);
    }

    // Redirige o maneja la respuesta como necesites
    header("Location: /path/to/success_page.php"); // Ajusta la ruta según sea necesario
    exit();
}
?>
