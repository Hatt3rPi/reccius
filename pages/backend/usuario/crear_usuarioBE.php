<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
include "../email/envia_correoBE.php"; // Asegúrate de que esta ruta es correcta

function limpiarDato($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nombreUsuario'], $_POST['correoElectronico'], $_POST['usuario'], $_POST['rol'])) {
        
        $nombreUsuario = limpiarDato($_POST['nombreUsuario']);
        $correoElectronico = limpiarDato($_POST['correoElectronico']);
        $usuario = limpiarDato($_POST['usuario']);
        $rol = limpiarDato($_POST['rol']);
        if (!$link) {
            die("Conexión fallida: " . mysqli_connect_error());
        }

        $stmt = mysqli_prepare($link, "SELECT id FROM usuarios WHERE usuario = ?");
        mysqli_stmt_bind_param($stmt, "s", $usuario);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            echo "Error: El usuario ya existe.";
        } else {
            $query="INSERT INTO usuarios (nombre, correo, usuario, rol_id) VALUES (?, ?, ?, ?)";
            $variables=[$nombreUsuario, $correoElectronico, $usuario, $rol];
            $insert = mysqli_prepare($link, $query);
            mysqli_stmt_bind_param($insert, "ssss", $nombreUsuario, $correoElectronico, $usuario, $rol);
            
            if (mysqli_stmt_execute($insert)) {
                $last_id = mysqli_insert_id($link);
                //in trazabilidad
                    $result = mysqli_stmt_get_result($insert);
                    $resultadoArray = mysqli_fetch_assoc($result);
                    $resultado = $resultadoArray ? 1 : 0; // Suponiendo que 1 es éxito y 0 es fracaso
                    $error = mysqli_stmt_error($insert) ? "Error al ejecutar la consulta: " . mysqli_stmt_error($insert) : null;
                    try{
                    registrarTrazabilidad($_SESSION['usuario'], $_SERVER['PHP_SELF'], 'crear_usuario', 'usuarios', mysqli_insert_id($link), $query, $variables, $resultado, $error);
                    } catch (Exception $e) {
                        die("Error: " . $e->getMessage());
                    }
                // out trazabidad
                $token = bin2hex(random_bytes(32));

                $insertToken = mysqli_prepare($link, "INSERT INTO tokens_reset (usuario_id, token, fecha_expiracion) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))");
                mysqli_stmt_bind_param($insertToken, "is", $last_id, $token);
                mysqli_stmt_execute($insertToken);
                mysqli_stmt_close($insertToken);

                $enlaceReset = 'https://customware.cl/reccius/reset_password.php?token=' . $token;
                $asunto = 'Restablecer tu contraseña';
                $cuerpo = 'Por favor, haz clic en este enlace para restablecer tu contraseña: ' . $enlaceReset;

                if (enviarCorreo($correoElectronico, $nombreUsuario, $asunto, $cuerpo)) {
                    // Imprime el script de JavaScript para mostrar la alerta y redirigir
                    echo "<script type='text/javascript'>
                            alert('Usuario creado exitosamente. Se ha enviado un correo electrónico para restablecer la contraseña.');
                            window.location.href='../../index.php'; // Redirige con JavaScript
                          </script>";
                } else {
                    // Imprime el script de JavaScript para mostrar la alerta de error en el correo
                    echo "<script type='text/javascript'>
                            alert('Usuario creado, pero hubo un error al enviar el correo de restablecimiento.');
                            window.location.href='../../index.php'; // Opcional: Redirige o maneja el error como prefieras
                          </script>";
                }
            } else {
                // Imprime el script de JavaScript para mostrar la alerta de error en la creación del usuario
                echo "<script type='text/javascript'>
                        alert('Error al crear usuario: " . addslashes(mysqli_error($link)) . "');
                      </script>";
            }
        }

        mysqli_stmt_close($stmt);
        mysqli_close($link);
    } else {
        echo "Todos los campos son requeridos";
    }
} else {
    echo "Método no permitido";
}
?>
