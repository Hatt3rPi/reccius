<?php
require_once "/home/customw2/conexiones/config_reccius.php";
session_start();
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}


$query = "SELECT * FROM usuario WHERE nombre LIKE '%" . mysqli_real_escape_string($this->link, $_GET['nombre']) . "%'";
$result = mysqli_query($this->link, $query);
$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}
return $users;
