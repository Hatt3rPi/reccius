<?php
//archivo: pages\backend\email\envia_correoBE.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verificar si las librerías PHPMailer existen
$paths = [
    '/home/customw2/librerias/PHPMailer-6.9.1/src/Exception.php',
    '/home/customw2/librerias/PHPMailer-6.9.1/src/PHPMailer.php',
    '/home/customw2/librerias/PHPMailer-6.9.1/src/SMTP.php'
];

foreach ($paths as $path) {
    if (!file_exists($path)) {
        die("Error: La librería PHPMailer no está conectada correctamente. Ruta no encontrada: $path");
    }
}

require '/home/customw2/conexiones/config_correo_noreply.php';
require_once $paths[0];
require_once $paths[1];
require_once $paths[2];

function enviarCorreo($destinatario, $nombreDestinatario, $asunto, $cuerpo, $altBody = '') {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom(SMTP_USER, 'Reccius - No responder');
        $mail->addAddress($destinatario, $nombreDestinatario);

        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $cuerpo;
        $mail->AltBody = $altBody ?: strip_tags($cuerpo);

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Puedes optar por manejar el error como prefieras, por ejemplo, registrarlo
        error_log("Error al enviar correo: {$mail->ErrorInfo}");
        return false;
    }
}

function enviarCorreo_transitorio($destinatarios, $asunto, $cuerpo, $altBody = '', $cc = []) {
    $mail = new PHPMailer(true);
    $errores = [];
    $exitos = [];

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';
        $mail->setFrom(SMTP_USER, 'Reccius - No responder');

        // Validación de destinatarios
        if (empty($destinatarios)) {
            throw new Exception("Error: La lista de destinatarios está vacía.");
        }

        foreach ($destinatarios as $destinatario) {
            if (!filter_var($destinatario['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Error: Dirección de correo no válida: " . $destinatario['email']);
            }
            if (empty($destinatario['nombre'])) {
                throw new Exception("Error: Nombre vacío para el correo: " . $destinatario['email']);
            }
            $mail->addAddress($destinatario['email'], $destinatario['nombre']);
        }

        // Agregar CC (si existen)
        if (!empty($cc)) {
            foreach ($cc as $copiado) {
                if (!filter_var($copiado['email'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Error: Dirección de correo CC no válida: " . $copiado['email']);
                }
                $mail->addCC($copiado['email'], $copiado['nombre']);
            }
        }

        // Validación del asunto
        if (empty($asunto)) {
            throw new Exception("Error: El asunto del correo está vacío.");
        }

        // Validación del cuerpo del mensaje
        if (empty($cuerpo)) {
            throw new Exception("Error: El cuerpo del correo está vacío.");
        }

        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $cuerpo;
        $mail->AltBody = $altBody ?: strip_tags($cuerpo);

        // Intentar enviar el correo
        if (!$mail->send()) {
            $errores[] = "Error al enviar correo: " . $mail->ErrorInfo;
            error_log("Error al enviar correo: " . $mail->ErrorInfo);
            return [
                'status' => 'error',
                'message' => 'No se pudo enviar el correo.',
                'errores' => $errores
            ];
        } else {
            $exitos[] = $destinatarios;
            return [
                'status' => 'success',
                'message' => 'El correo se envió con éxito.',
                'enviados' => $exitos
            ];
        }
    } catch (Exception $e) {
        error_log("Excepción capturada: " . $e->getMessage());
        return [
            'status' => 'error',
            'message' => $e->getMessage(),
            'errores' => $errores
        ];
    }
}




function enviarCorreoMultiple($destinatarios, $asunto, $cuerpo, $altBody = '') {
    $mail = new PHPMailer(true);
    $errores = [];
    $exitos = [];

    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';
        $mail->setFrom(SMTP_USER, 'Reccius');

        foreach ($destinatarios as $destinatario) {
            $mail->clearAddresses();
            $mail->addAddress($destinatario['email'], $destinatario['nombre']);
            $mail->Subject = $asunto;
            $mail->Body    = $cuerpo;
            $mail->AltBody = $altBody ?: strip_tags($cuerpo);
            try {
                $mail->send();
                $exitos[] = $destinatario['email'];
            } catch (Exception $e) {
                $errores[] = [
                    'email' => $destinatario['email'],
                    'error' => $mail->ErrorInfo
                ];
                error_log("Error al enviar correo a {$destinatario['email']}: {$mail->ErrorInfo}");
            }
        }

        if (empty($errores)) {
            return [
                'status' => 'success',
                'message' => 'Todos los correos se enviaron con éxito.',
                'enviados' => $exitos
            ];
        } else {
            return [
                'status' => 'partial success',
                'message' => 'Algunos correos no se pudieron enviar.',
                'enviados' => $exitos,
                'errores' => $errores
            ];
        }
    } catch (Exception $e) {
        error_log("Error general al enviar correos: {$mail->ErrorInfo}");
        return [
            'status' => 'error',
            'message' => 'No se pudieron enviar los correos.',
            'errores' => $errores
        ];
    }
}


?>
