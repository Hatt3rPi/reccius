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

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = limpiarDato($_POST['email']);
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

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
            $success = 'Se ha enviado un enlace de restablecimiento a tu correo electrónico.';
        } else {
            $error = 'Hubo un error al enviar el correo electrónico de restablecimiento.';
        }
    } else {
        // Correo electrónico no encontrado
        $error = 'No se encontró una cuenta con ese correo electrónico.';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <style>
        :root {
            --opacity-background: 0.5;
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            height: 100vh;
            overflow: hidden;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('../assets/images/fondo_login.png');
            background-size: cover;
            background-position: center;
            filter: blur(8px);
            opacity: var(--opacity-background);
            z-index: -1;
        }
        .message-container {
            background-color: rgba(255, 255, 255, 1);
            padding: 60px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            z-index: 1;
        }
        .error-message {
            color: #ff0000; /* Rojo */
            background-color: #ffecec; /* Fondo rojo claro */
            border: 1px solid #ff0000;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
        }
        .success-message {
            color: #28a745; /* Verde */
            background-color: #e6ffe6; /* Fondo verde claro */
            border: 1px solid #28a745;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <?php if ($error): ?>
            <div class="error-message">Error: <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success-message">Restablecer Contraseña<?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>

