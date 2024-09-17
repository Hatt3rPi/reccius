<?php
// Incluir las dependencias necesarias
require '/home/customw2/librerias/PHPMailer-6.9.1/src/Exception.php';
require '/home/customw2/librerias/PHPMailer-6.9.1/src/PHPMailer.php';
require '/home/customw2/librerias/PHPMailer-6.9.1/src/SMTP.php';
require '/home/customw2/conexiones/config_correo_noreply.php';
require "/home/customw2/conexiones/config_reccius.php";
require './envia_correoBE.php'; // Asegúrate de que este archivo contiene la función enviarCorreo_transitorio

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Lista de destinatarios proporcionada
$recipientsList = [
    ["email" => "fabarca212@gmail.cl", "nombre" => "caty"],
    ["email" => "fabarca212@hotmail.com", "nombre" => "felipe1"],
    ["email" => "feabarcas@gmail.com", "nombre" => "felipe2"],
    ["email" => "javier2000asr@gmail.com", "nombre" => "Javier Sabando Ruiz"],
    ["email" => "lucianoalonso2000@gmail.com", "nombre" => "luciano"],
    ["email" => "ratapansthings@gmail.com", "nombre" => "javier"],
    ["email" => "lucianoalonso2000@gmail.com", "nombre" => "Luciano Abarca"]
];

// Número total de pruebas a realizar
$totalTests = count($recipientsList);

for ($i = 1; $i <= $totalTests; $i++) {
    // Obtener los primeros $i destinatarios
    $currentRecipients = array_slice($recipientsList, 0, $i);

    // Asunto y cuerpo del correo
    $asunto = "Test Email - $i Destinatario(s)";
    $cuerpo = "Estimados Pharma ISA,
Junto con saludar, comento que con fecha 16/09/2024, enviamos solicitud de análisis externo para el producto: CORREOS TEST2 - Materia Prima - Lote: 121212

Adjuntamos la información necesaria para ingresar a análisis:

Solicitud de Análisis Externo: Ver archivo
Solicitud Acta de Muestreo: Ver archivo
Documento adicional: Ver archivo

PD: El correo fue generado de una casilla automática. favor derivar sus respuestas a Luciano Abarca (lucianoalonso2000@gmail.com) y Macarena Alejandra Godoy Castro (mgodoy@reccius.cl).
Saluda atentamente,
Farmacéutica Reccius SPA";
    $altBody = "Este es un correo de prueba enviado a $i destinatario(s).";

    // Enviar el correo utilizando la función enviarCorreo_transitorio
    $resultado = enviarCorreo_transitorio($currentRecipients, $asunto, $cuerpo, $altBody);

    // Mostrar el resultado
    echo "Test $i: ";
    if ($resultado['status'] == 'success') {
        echo "Correo enviado con éxito a $i destinatario(s).<br>";
    } else {
        echo "Error al enviar correo a $i destinatario(s): " . $resultado['message'] . "<br>";
        if (!empty($resultado['errores'])) {
            foreach ($resultado['errores'] as $error) {
                echo "Detalle del error: $error<br>";
            }
        }
    }

    // Pausa entre envíos para evitar sobrecargar el servidor
    sleep(2);
}
?>
