<?php
require_once "/home/customw2/conexiones/config_reccius.php";
session_start();
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}

header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['nombre'])) {
    echo json_encode(['error' => 'Parameter "nombre" is required']);
    exit;
}

$query = "SELECT id, usuario, nombre, rol_id, cargo, correo FROM usuarios WHERE nombre LIKE '%" . mysqli_real_escape_string($link, $_GET['nombre']) . "%'";
$result = mysqli_query($link, $query);

if ($result === false) {
    echo json_encode(['error' => 'Error: ' . mysqli_error($link)]);
    exit;
}

$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

mysqli_free_result($result);
mysqli_close($link);

echo json_encode($users);