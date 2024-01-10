<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

$usuario = $_SESSION['usuario'] ? $_SESSION['usuario'] : null;
$query =    "SELECT 
                id,
                nombre, 
                nombre_corto, 
                foto_perfil, 
                ruta_registroPrestadoresSalud, 
                cargo 
            FROM `usuarios` 
            where usuario = ?;";

$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "s", $usuario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$usuario = [];

while ($row = mysqli_fetch_assoc($result)) {
    $usuario = [
        'id' => $row['id'],
        'nombre' => $row['nombre'],
        'nombre_corto' => $row['nombre_corto'],
        'foto_perfil' => $row['foto_perfil'],
        'certificado' => $row['ruta_registroPrestadoresSalud'],
        'cargo' => $row['cargo']
    ];
    break;
}

mysqli_stmt_close($stmt);
mysqli_close($link);

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['usuario' => $usuario], JSON_UNESCAPED_UNICODE);

?>
