<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Leer los datos JSON enviados desde el frontend
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); // convert JSON into array
$usuario = $_SESSION['usuario'] ?? 'Usuario no definido'; // Cambia 'usuario' por la clave apropiada
$fechaActual = date("Y-m-d"); // Formato de fecha para SQL
// Validar y obtener los datos necesarios
$id_actaMuestreo = isset($input['id_actaMuestreo']) ? intval($input['id_actaMuestreo']) : null;
$etapa = isset($input['etapa']) ? intval($input['etapa']) : null;
$respuestas = isset($input['respuestas']) ? $input['respuestas'] : null;
$textareaData = isset($input['textareaData']) ? $input['textareaData'] : [];

// Validar que los datos esenciales están presentes
if (!$id_actaMuestreo || !$etapa || !$respuestas || count($textareaData) === 0) {
    echo json_encode(['error' => 'Datos faltantes o incorrectos.']);
    exit;
}

// Preparar la consulta SQL para insertar los datos en la base de datos
$query = "update calidad_acta_muestreo  set 
    estado=? , 
    resultados_muestrador=? , 
    pregunta5=? ,  pregunta6=? ,  pregunta7=? ,  pregunta8=? ,  muestreador=? ,  fecha_firma_muestreador=? where id =? ";

if ($stmt = mysqli_prepare($link, $query)) {
    // Vincular parámetros para marcadores
    mysqli_stmt_bind_param($stmt, "ssssssssi",
        $estado, 
        $respuestas,
        $textareaData['form_textarea5'],
        $textareaData['form_textarea6'],
        $textareaData['form_textarea7'],
        $textareaData['form_textarea8'],
        $usuario,
        $fechaActual,
        $id_actaMuestreo
    );

    // Establecer estado y otros valores necesarios
    $estado = 'En proceso de firma'; // Suponiendo un valor predeterminado

    // Ejecutar la declaración
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => 'Datos guardados correctamente.']);
    } else {
        echo json_encode(['error' => 'Error al guardar datos: ' . mysqli_stmt_error($stmt)]);
    }

    // Cerrar declaración
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['error' => 'Error al preparar la declaración: ' . mysqli_error($link)]);
}

// Cerrar la conexión
mysqli_close($link);
?>
