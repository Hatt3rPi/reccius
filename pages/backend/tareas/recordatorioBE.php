<?php
require_once "/home/customw2/conexiones/config_reccius.php";
include "../email/envia_correoBE.php";

// Asegúrate de incluir la configuración de la base de datos o cualquier otra librería necesaria

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['idTarea'])) {
    $idTarea = $_POST['idTarea'];

    // Obtener detalles de la tarea y del usuario ejecutor de la base de datos
    // Por ejemplo, algo como esto (ajusta la consulta SQL según tu esquema de base de datos):
    $query = "SELECT t.descripcion_tarea, t.fecha_vencimiento, u.nombre, u.correo 
                FROM tareas t 
                JOIN usuarios u ON t.usuario_ejecutor = u.usuario  
              WHERE t.id = ?";
    if ($stmt = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $idTarea);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $tarea = mysqli_fetch_assoc($resultado);

        // Preparar el mensaje de correo electrónico
        $asunto = "Recordatorio tarea: " . $tarea['descripcion_tarea'];
        $cuerpo = "Estimado " . $tarea['nombre'] . ",<br><br>" .
                  "Tienes la siguiente tarea con fecha de vencimiento " . $tarea['fecha_vencimiento'] . ":<br>" .
                  "<strong>" . $tarea['descripcion_tarea'] . "</strong><br><br>" .
                  "Te agradecemos tu esfuerzo y dedicación.<br><br>" .
                  "Saludos cordiales,<br>" .
                  "Reccius";

        // Enviar el correo
        if (enviarCorreo($tarea['correo'], $tarea['nombre'], $asunto, $cuerpo)) {
            echo "Correo enviado con éxito";
        } else {
            echo "Error al enviar el correo";
        }
    } else {
        echo "Error al preparar la consulta SQL";
    }
}
?>
