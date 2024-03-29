<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
include "../email/envia_correoBE.php";

function limpiarDato($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = limpiarDato($_POST['email']);
    if (!$link) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // Verificar si el correo electrónico existe en la base de datos
    $stmt = mysqli_prepare($link, "SELECT id FROM usuarios WHERE correo = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        // Usuario encontrado
        $usuario_id = $fila['id'];
        $token = bin2hex(random_bytes(32));

        // Insertar el token en la base de datos
        $insertToken = mysqli_prepare($link, "INSERT INTO tokens_reset (usuario_id, token, fecha_expiracion) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))");
        mysqli_stmt_bind_param($insertToken, "is", $usuario_id, $token);
        mysqli_stmt_execute($insertToken);
        mysqli_stmt_close($insertToken);

        // Enviar el correo electrónico
        $enlaceReset = 'https://customware.cl/reccius/reset_password.php?token=' . $token;
        $asunto = 'Restablecer tu contraseña';
        $cuerpo = 'Por favor, haz clic en este enlace para restablecer tu contraseña: ' . $enlaceReset;

        if (enviarCorreo($email, $nombreUsuario, $asunto, $cuerpo)) {
            $mensaje = 'Se ha enviado un enlace de restablecimiento a tu correo electrónico.';
        } else {
            $mensaje = 'Hubo un error al enviar el correo electrónico de restablecimiento.';
        }
    } else {
        // Correo electrónico no encontrado
        $mensaje = 'No se encontró una cuenta con ese correo electrónico.';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
}
header('Content-Type: application/json');
echo json_encode(['data' => $mensaje]);
?>
