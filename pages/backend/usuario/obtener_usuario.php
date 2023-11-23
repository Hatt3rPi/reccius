<?php
session_start();
header('Content-Type: application/json');

if(isset($_SESSION['usuario'])) {
    echo json_encode([
        "usuario" => $_SESSION['usuario'],
        "nombre" => $_SESSION['nombre'],
        "foto_perfil" => $_SESSION['foto_perfil'],
        "rol" => $_SESSION['rol']
    ]);
} else {
    echo json_encode(["usuario" => null, "rol" => null, "nombre" => null, "foto_perfil" => null]);
}
?>
