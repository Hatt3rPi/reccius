<?php
// archivo: pages\backend\laboratorio\enviar_solicitud_externa.php
session_start();
header('Content-Type: application/json');
require_once "../otros/laboratorio.php";
require_once "/home/customw2/conexiones/config_reccius.php";

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'Acceso denegado']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (
  empty($input['correo'])
) {
    echo json_encode(['exito' => false, 'mensaje' => 'Datos insuficientes', 'input' => $input]);
    exit;
}

$correo = $input['correo'];

$laboratorio = new Laboratorio();
$resp = $laboratorio->deleteCorreosCCByCorreo($correo);

echo json_encode(['msg' => $resp]);

?>
