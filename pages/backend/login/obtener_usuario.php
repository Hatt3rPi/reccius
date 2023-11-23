<?php
session_start();
header('Content-Type: application/json');

if(isset($_SESSION['usuario'])) {
    echo json_encode([
        "usuario" => $_SESSION['usuario'],
        "rol" => $_SESSION['rol']
    ]);
} else {
    echo json_encode(["usuario" => null, "rol" => null]);
}
?>
