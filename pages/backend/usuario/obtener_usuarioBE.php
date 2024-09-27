<?php
//archivo: pages\backend\usuario\obtener_usuarioBE.php
session_start();
header('Content-Type: application/json');

if(isset($_SESSION['usuario'])) {
    echo json_encode([
        "usuario" => $_SESSION['usuario'],
        "nombre" => $_SESSION['nombre'],
        "foto_perfil" => $_SESSION['foto_perfil'],
        "foto_firma" => $_SESSION['foto_firma'],
        "rol" => $_SESSION['rol'],
        "cargo" => $_SESSION['cargo']
    ]);
} else {
    echo json_encode(["usuario" => null, "rol" => null, "nombre" => null, "foto_perfil" => null]);
}
?>
