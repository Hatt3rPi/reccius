<?php
require_once "/home/customw2/conexiones/config_reccius.php";

// Verificar la conexión con la base de datos
if (!$link) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Consulta para obtener todos los usuarios y sus roles
$query = "SELECT u.id, u.usuario, u.nombre, u.correo, r.nombre AS rol FROM usuarios u LEFT JOIN roles r ON u.rol_id = r.id";
$result = mysqli_query($link, $query);

// Crear un array para almacenar los usuarios
$usuarios = [];

while ($row = mysqli_fetch_assoc($result)) {
    $usuarios[] = $row;
}

// Devolver los usuarios en formato JSON
header('Content-Type: application/json');
echo json_encode([
    "data" => $usuarios // El formato que espera DataTables es que los datos estén en una clave "data"
]);

// Cerrar la conexión
mysqli_close($link);
?>
