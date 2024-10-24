<?php
// archivo: pages\backend\tareas\tareasBE.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}
function limpiarDato($dato) {
    $datoLimpio = trim($dato);
    if (empty($datoLimpio)) {
        return null;
    }
    return htmlspecialchars(stripslashes($datoLimpio));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    registrarTrazabilidad($_SESSION['usuario'], $_SERVER['PHP_SELF'], 'INTENTO DE CARGA', 'TAREAS',  1, '', $_POST, '', '');
    $respuesta = procesarFormulario($link);
    echo json_encode($respuesta);
} else {
    echo json_encode(["exito" => false, "mensaje" => "Método inválido", "idEspecificacion" => 0]);
}

function procesarFormulario($link) {
    $idTarea = limpiarDato($_POST['idTarea']);
    $usuarioNuevo = limpiarDato($_POST['usuarioNuevo']);

    // Realiza aquí la validación de los datos

    // Preparar la consulta SQL para actualizar la tarea
    $sql = "UPDATE tareas SET usuario_ejecutor = ? WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "si", $usuarioNuevo, $idTarea);
        $exito = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        registrarTrazabilidad(
            $_SESSION['usuario'], 
            $_SERVER['PHP_SELF'], 
            'Cambio de usuario ejecutor', 
            'tareas',  
            $idTarea, 
            $sql,  
            [$usuarioNuevo, $idTarea], 
            $exito ? 1 : 0, 
            $exito ? null : mysqli_error($link)
        );
        if ($exito) {
            return ["exito" => true, "mensaje" => "Usuario actualizado con éxito"];
        } else {
            return ["exito" => false, "mensaje" => "Error al actualizar el usuario: " . mysqli_stmt_error($stmt)];
        }

        mysqli_stmt_close($stmt);
    } else {
        return ["exito" => false, "mensaje" => "Error al preparar la sentencia: " . mysqli_error($link)];
    }
}

?>
