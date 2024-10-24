<?php
require_once "/home/customw2/conexiones/config_reccius.php";
session_start();
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}
// Verificar la conexión con la base de datos
if (!$link) {
    die(json_encode(['error' => 'Error en la conexión a la base de datos.']));
}

// Consulta para obtener todos los roles
$query = "SELECT id, nombre FROM roles";
$result = mysqli_query($link, $query);

if (!$result) {
    die(json_encode(['error' => 'Error en la consulta de la base de datos.']));
}

$roles = [];

while ($row = mysqli_fetch_assoc($result)) {
    $roles[] = $row;
}

// Devolver los roles en formato JSON
header('Content-Type: application/json');
echo json_encode($roles);

mysqli_close($link);
?>
