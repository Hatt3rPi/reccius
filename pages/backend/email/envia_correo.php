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
    if ($e->getCode() == 0) {
        // Error de conexión al servidor SMTP (posiblemente credenciales incorrectas)
        echo "No se pudo conectar al servidor SMTP. Verifica las credenciales y configuraciones del servidor. Error de PHPMailer: {$mail->ErrorInfo}";
    } else {
        // Otros errores de PHPMailer
        echo "El mensaje no se pudo enviar. Error de PHPMailer: {$mail->ErrorInfo}";
    }
} catch (\Exception $e) {
    echo "Error general: " . $e->getMessage();
}
?>
