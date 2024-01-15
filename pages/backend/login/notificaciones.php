<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Asumiendo que tienes el ID de usuario en la sesión
$user = $_SESSION['usuario'];

$query = "SELECT COUNT(*) AS count FROM tareas WHERE estado in ('Activo', 'Atrasado', 'Fecha de Vencimiento cercano', 'Activa', 'Atrasada') AND usuario_ejecutor = ?";
$stmt = $link->prepare($query);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode(['count' => $data['count']]);

$link->close();
?>
