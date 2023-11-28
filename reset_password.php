<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Función para validar y limpiar datos
function limpiarDato($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

$error = '';
$success = '';
$mostrarFormulario = false;

if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = limpiarDato($_GET['token']);
    $link = mysqli_connect("localhost", "tu_usuario", "tu_contraseña", "nombre_de_tu_base_de_datos");

    if (!$link) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    $stmt = mysqli_prepare($link, "SELECT usuario_id, fecha_expiracion FROM tokens_reset WHERE token = ? AND fecha_expiracion > NOW()");
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        // Token válido y no ha expirado
        $mostrarFormulario = true;
        $usuario_id = $fila['usuario_id']; // Guardar el id de usuario para su uso posterior
    } else {
        $error = 'El enlace de restablecimiento no es válido o ha expirado.';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
} else {
    $error = 'Solicitud no válida.';
}

// Procesar el formulario de restablecimiento de contraseña
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
    $nuevaContrasena = limpiarDato($_POST['nuevaContrasena']);
    // Aquí deberías agregar validación de la contraseña (por ejemplo, longitud mínima)

    if (!$link) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // Actualizar la contraseña del usuario
    // Asegúrate de usar password_hash para almacenar la contraseña de manera segura
    $contrasenaHash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
    $update = mysqli_prepare($link, "UPDATE usuarios SET contrasena = ? WHERE id = ?");
    mysqli_stmt_bind_param($update, "si", $contrasenaHash, $usuario_id);

    if (mysqli_stmt_execute($update)) {
        $success = 'Tu contraseña ha sido restablecida exitosamente.';
    } else {
        $error = 'Error al restablecer tu contraseña.';
    }

    mysqli_stmt_close($update);
    mysqli_close($link);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <!-- Añadir CSS si es necesario -->
</head>
<body>
    <?php if ($error): ?>
        <p>Error: <?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p><?php echo $success; ?></p>
    <?php else: ?>

        <?php if ($mostrarFormulario): ?>
            <form action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" method="post">
                <label for="nuevaContrasena">Nueva Contraseña:</label>
                <input type="password" id="nuevaContrasena" name="nuevaContrasena" required>
                <button type="submit" name="reset_password">Restablecer Contraseña</button>
            </form>
        <?php endif; ?>

    <?php endif; ?>
</body>
</html>
