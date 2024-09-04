<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_POST['usuario_id'];
    $rol_id = $_POST['rol_id'];

    // Consulta para actualizar el rol del usuario en la base de datos
    $query = "UPDATE usuarios SET rol_id = ? WHERE id = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $rol_id, $usuario_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo 'Rol actualizado correctamente';
    } else {
        echo 'Error al actualizar el rol: ' . mysqli_error($link);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
}
?>
