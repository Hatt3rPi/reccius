<?php
require_once "/home/customw2/conexiones/config_reccius.php";

// Verificar si es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener el id del usuario
    if (isset($_POST['usuario_id'])) {
        $usuario_id = $_POST['usuario_id'];

        // Comprobar si el id no está vacío
        if (!empty($usuario_id)) {
            // Preparar la consulta para eliminar el usuario
            $query = "DELETE FROM usuarios WHERE id = ?";
            $stmt = mysqli_prepare($link, $query);
            mysqli_stmt_bind_param($stmt, 'i', $usuario_id);

            // Ejecutar la consulta y verificar si fue exitosa
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['status' => 'success', 'message' => 'Usuario eliminado correctamente.']);
            } else {
                // Mensaje de error si la consulta falla
                echo json_encode(['status' => 'error', 'message' => 'Error al ejecutar la consulta: ' . mysqli_error($link)]);
            }

            mysqli_stmt_close($stmt);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID de usuario vacío']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID de usuario no proporcionado']);
    }

    mysqli_close($link);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}
