<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Asumiendo que la conexión a la base de datos está en $link

function cambiarPassword($link, $usuario, $passwordActual, $nuevaPassword) {
    // Validar la fortaleza de la nueva contraseña
    if (!validarFortalezaPassword($nuevaPassword)) {
        return "La nueva contraseña no cumple con los requisitos de seguridad.";
    }

    // Verificar la contraseña actual
    $stmt = mysqli_prepare($link, "SELECT contrasena FROM usuarios WHERE usuario = ?");
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $fila = mysqli_fetch_assoc($result);

    if ($fila) {
        $contrasenaHash = $fila['contrasena'];
        if (password_verify($passwordActual, $contrasenaHash)) {
            // Cambiar la contraseña
            $nuevaPasswordHash = password_hash($nuevaPassword, PASSWORD_DEFAULT);

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

function validarFortalezaPassword($password) {
    // Aquí puedes agregar las reglas que consideres para validar la contraseña
    return strlen($password) >= 8; // Ejemplo: longitud mínima de 8 caracteres
}

function cambiarFotoPerfil($link, $usuario, $fotoPerfil) {
    // Verificar y guardar la foto de perfil
    if ($fotoPerfil['error'] === UPLOAD_ERR_OK) {
        $directorioDestino = "../../../uploads/perfiles/"; // Ajusta esta ruta
        $nombreArchivo = $directorioDestino . basename($fotoPerfil['name']);
        $tipoArchivo = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

        // Verificar si el archivo es realmente una imagen
        if (getimagesize($fotoPerfil['tmp_name']) === false) {
            return "El archivo no es una imagen.";
        }

        // Verificar el tamaño del archivo
        if ($fotoPerfil["size"] > 5000000) { // 5MB
            return "El archivo es demasiado grande.";
        }

        // Permitir ciertos formatos de archivo
        if (!in_array($tipoArchivo, ['jpg', 'png', 'jpeg', 'gif'])) {
            return "Solo se permiten archivos JPG, JPEG, PNG y GIF.";
        }

        if (move_uploaded_file($fotoPerfil['tmp_name'], $nombreArchivo)) {
            // Actualizar la referencia en la base de datos
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
    } else {
        return "Error al subir el archivo: " . $fotoPerfil['error'];
    }
}

if (isset($_POST['modificarPerfil'])) {
    $usuario = $_SESSION['usuario']; // Asumiendo que el nombre de usuario está almacenado en la sesión
    $passwordActual = $_POST['passwordActual'];
    $nuevaPassword = $_POST['nuevaPassword'];
    $confirmarPassword = $_POST['confirmarPassword'];

    if ($nuevaPassword === $confirmarPassword) {
        echo cambiarPassword($link, $usuario, $passwordActual, $nuevaPassword);
    } else {
        echo "Las contraseñas no coinciden.";
    }

    if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === UPLOAD_ERR_OK) {
        echo cambiarFotoPerfil($link, $usuario, $_FILES['fotoPerfil']);
    }

    // Redirigir o manejar la respuesta como necesites
    // header("Location: /path/to/success_page.php");
    exit();
}
?>
