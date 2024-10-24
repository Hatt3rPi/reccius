<?php
require_once "/home/customw2/conexiones/config_reccius.php";
session_start();
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}
header('Content-Type: application/json');

$roles = getRoles();
echo json_encode($roles);

function getRoles() {
    global $link;
    $stmt = mysqli_prepare($link, "SELECT id, nombre FROM roles");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $roles = [];
    while($row = mysqli_fetch_assoc($result)) {
        $roles[] = $row;
    }
    return $roles;
}
?>
