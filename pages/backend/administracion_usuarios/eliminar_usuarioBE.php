<?php
require_once "/home/customw2/conexiones/config_reccius.php";

// Verificar si es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener el id del usuario
    $usuario_id = $_POST['usuario_id'];

    // Eliminar el usuario
    $query = "DELETE FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, 'i', $usuario_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => 'success', 'message' => 'Usuario eliminado correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el usuario.']);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}
?>
