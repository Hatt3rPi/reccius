<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

function limpiarDato($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

$error = '';
$success = '';
$mostrarFormulario = false;
$usuario_id = null;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['token']) && !empty($_GET['token'])) {
        $token = limpiarDato($_GET['token']);
        $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        if (!$link) {
            die("Conexión fallida: " . mysqli_connect_error());
        }
        $stmt = mysqli_prepare($link, "SELECT usuario_id, fecha_expiracion FROM tokens_reset WHERE token = ? AND fecha_expiracion > NOW() AND consumido = 0");
        mysqli_stmt_bind_param($stmt, "s", $token);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($fila = mysqli_fetch_assoc($resultado)) {
            $mostrarFormulario = true;
            $usuario_id = $fila['usuario_id'];
        } else {
            $error = 'El enlace de restablecimiento no es válido o ha expirado.';
        }

        mysqli_stmt_close($stmt);
        mysqli_close($link);
    } else {
        $error = 'Solicitud no válida.';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
    $nuevaContrasena = limpiarDato($_POST['nuevaContrasena']);
    $usuario_id = $_POST['usuario_id'] ?? null; // Obtener usuario_id del campo oculto

    if ($usuario_id) {
        $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if (!$link) {
            die("Conexión fallida: " . mysqli_connect_error());
        }

        $contrasenaHash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
        $update = mysqli_prepare($link, "UPDATE usuarios SET contrasena = ? WHERE id = ?");
        mysqli_stmt_bind_param($update, "si", $contrasenaHash, $usuario_id);

        if (mysqli_stmt_execute($update)) {
            // Actualización exitosa, ahora marcar el token como consumido
            $stmt_consumido = mysqli_prepare($link, "UPDATE tokens_reset SET consumido = 1 WHERE token = ?");
            mysqli_stmt_bind_param($stmt_consumido, "s", $token);
            mysqli_stmt_execute($stmt_consumido);
            mysqli_stmt_close($stmt_consumido);
        
            $success = 'Tu contraseña ha sido restablecida exitosamente.';
            header("Location: pages/login.html");
            exit();
        } else {
            $error = 'Error al restablecer tu contraseña: ' . mysqli_stmt_error($update);
        }

        mysqli_stmt_close($update);
        mysqli_close($link);
    } else {
        $error = 'Error al procesar la solicitud.';
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <style>
        /* Estilos proporcionados previamente */
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
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('./assets/images/fondo_login.png');
            background-size: cover;
            background-position: center;
            filter: blur(8px);
            opacity: var(--opacity-background);
            z-index: -1;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 1);
            padding: 60px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
        }
        @media (max-width: 600px) {
            .login-container {
                width: 90%;
                padding: 30px;
            }
        }
        .login-form {
            display: flex;
            flex-direction: column;
        }
        .login-form label {
            margin-bottom: 0.3125rem; /* 5px */
            font-weight: bold;
        }
        .login-form input {
            padding: 0.625rem; /* 10px */
            margin-bottom: 0.625rem; /* 10px */
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .login-form button {
            padding: 0.625rem; /* 10px */
            background-color: #5e4ad9;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-form button:hover {
            background-color: #473cbd;
        }
        .login-header {
            text-align: center;
        }
        .login-header .logo {
            display: block;
            margin: 0 auto;
        }
        .logo {
            max-width: 300px;
            height: auto;
        }
        .separator {
            border: none;
            height: 1px;
            background-color: #ddd;
            margin: 10px 0;
        }
        .error-message {
            color: #ff0000; /* Rojo */
            background-color: #ffecec; /* Fondo rojo claro */
            border: 1px solid #ff0000;
            border-radius: 5px;
            padding: 10px;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <?php if ($error): ?>
            <div class="error-message">Error: <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <p><?php echo htmlspecialchars($success); ?></p>
        <?php else: ?>
            <?php if ($mostrarFormulario): ?>
                <form class="login-form" action="reset_password.php" method="post">
                    <input type="hidden" name="usuario_id" value="<?php echo htmlspecialchars($usuario_id); ?>">
                    <label for="nuevaContrasena">Nueva Contraseña:</label>
                    <input type="password" id="nuevaContrasena" name="nuevaContrasena" required minlength="8">
                    <button type="submit" name="reset_password">Restablecer Contraseña</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
