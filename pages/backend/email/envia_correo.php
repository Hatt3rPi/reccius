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
?>
