<?php
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
            $mail->clearAddresses(); // Limpia las direcciones antes de añadir una nueva
            $mail->addAddress($destinatario['email'], $destinatario['nombre']);

            try {
                $mail->send();
                $mail->CharSet = 'UTF-8';
                $mail->Subject = $asunto;
                $mail->Body    = $cuerpo;
                $mail->AltBody = $altBody ?: strip_tags($cuerpo);
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
