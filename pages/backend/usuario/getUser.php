<?php
require_once "/home/customw2/conexiones/config_reccius.php";
session_start();
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}

header('Content-Type: application/json; charset=utf-8');

$query = "SELECT * FROM usuario WHERE nombre LIKE '%" . mysqli_real_escape_string($link, $_GET['nombre']) . "%'";
$result = mysqli_query($link, $query);
$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}
echo json_encode($users);