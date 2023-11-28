<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '/home/customw2/conexiones/config_correo_noreply.php'
require '/home/librerias/PHPMailer-6.9.1/src/Exception.php';
require '/home/librerias/PHPMailer-6.9.1/src/PHPMailer.php';
require '/home/librerias/PHPMailer-6.9.1/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP usando las constantes del archivo de configuración
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = SMTP_SECURE;
    $mail->Port = SMTP_PORT;

    // Destinatarios
    $mail->setFrom(SMTP_USER, 'Reccius - No responder');
    $mail->addAddress('fabarca212@gmail.com', 'Felipe Abarca');

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'test';
    $mail->Body    = 'Este es el contenido del correo';

    $mail->send();
    echo 'El mensaje ha sido enviado';
} catch (Exception $e) {
    echo "El mensaje no se pudo enviar. Error de PHPMailer: {$mail->ErrorInfo}";
} catch (\Exception $e) {
    echo "Error general: " . $e->getMessage();
}