<?php
// archivo pages\backend\acta_muestreo\guardar_y_firmar.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Leer los datos JSON enviados desde el frontend
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); // convertir JSON a array
$usuario = $_SESSION['usuario'] ?? 'Usuario no definido'; // Usa la clave correcta para 'usuario'
$fechaActual = date("Y-m-d"); // Formato de fecha para SQL

// Validar y obtener los datos necesarios
$id_actaMuestreo = isset($input['id_actaMuestreo']) ? intval($input['id_actaMuestreo']) : null;
$etapa = isset($input['etapa']) ? intval($input['etapa']) : null;
$respuestas = isset($input['respuestas']) ? $input['respuestas'] : null;
$textareaData = isset($input['textareaData']) ? $input['textareaData'] : [];

// Validar que los datos esenciales están presentes
if (!$id_actaMuestreo || !$etapa || !$respuestas) {
    echo json_encode(['error' => 'Datos faltantes o incorrectos.']);
    exit;
}

// Dependiendo de la etapa, los datos relevantes cambiarán
switch ($etapa) {
    case 1:
        // Asumimos que sólo en la etapa 1 necesitamos guardar datos de textarea
        $estado = 'En proceso de firma';
        if (count($textareaData) === 0) {
            echo json_encode(['error' => 'Datos de textarea faltantes para la etapa 1.']);
            exit;
        }
        $query = "UPDATE calidad_acta_muestreo SET
                    estado=?,
                    resultados_muestrador=?,
                    pregunta5=?, pregunta6=?, pregunta7=?, pregunta8=?,
                    muestreador=?, fecha_firma_muestreador=?
                  WHERE id=?";
        $types = "ssssssssi";
        $params = [
            $estado,
            $respuestas,
            $textareaData['form_textarea5'],
            $textareaData['form_textarea6'],
            $textareaData['form_textarea7'],
            $textareaData['form_textarea8'],
            $usuario,
            $fechaActual,
            $id_actaMuestreo
        ];
        $flujo='Firma usuario 1 de 3';
        break;
    case 2:
        $estado = 'En proceso de firma';
        // Otras etapas podrían tener diferentes campos a guardar
        $query = "UPDATE calidad_acta_muestreo SET
                    estado=?, resultados_responsable=?,
                    responsable=?, fecha_firma_responsable=?
                  WHERE id=?";
        $types = "ssssi";
        $params = [
            $estado,
            $respuestas,
            $usuario,
            $fechaActual,
            $id_actaMuestreo
        ];
        $flujo='Firma usuario 2 de 3';
        break;
    case 3:
        
        $estado = 'Vigente'; // Define el estado del documento como vigente después de esta firma.
        $query = "  UPDATE calidad_acta_muestreo SET
                        estado=?, verificador=?, fecha_firma_verificador=? 
                    WHERE id=?;";  // Condición para asegurar la actualización correcta por ID
        $types = "sssisi";  // Tipos de los parámetros: string, string, string, integer
        $params = [
            $estado,
            $usuario,
            $fechaActual,
            $id_actaMuestreo
        ];
        $flujo='Firma usuario 3 de 3';  // Descripción del proceso actual para la trazabilidad
        break;
    default:
        echo json_encode(['error' => 'Etapa de firma no reconocida.']);
        exit;
}

// Ejecutar la consulta preparada
if ($stmt = mysqli_prepare($link, $query)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    $exito = mysqli_stmt_execute($stmt);
    // Aquí podrías agregar registro de trazabilidad...
    registrarTrazabilidad(
        $_SESSION['usuario'], 
        $_SERVER['PHP_SELF'], 
        $flujo, 
        'acta de muestreo',  
        $id_actaMuestreo, 
        $query,  
        $params, 
        $exito ? 1 : 0, 
        $exito ? null : mysqli_error($link)
    );
    if ($exito) {
        $_SESSION['nuevo_id'] = $id_actaMuestreo; // Esto puede ser útil dependiendo del flujo
        if ($etapa==3) {
            
            $id_analisis_externo = intval($input['id_analisis_externo']);
            $estado2 = 'Pendiente completar análisis'; // Define el estado del documento como vigente después de esta firma.
            $query = "  UPDATE calidad_analisis_externo SET
                            estado=? 
                        WHERE id=?;";  // Condición para asegurar la actualización correcta por ID
            $types = "si";  // Tipos de los parámetros: string, string, string, integer
            $params = [
                $estado2,
                $id_analisis_externo
            ];
            $stmt2 = mysqli_prepare($link, $query);
            mysqli_stmt_bind_param($stmt2, $types, ...$params);
            $exito = mysqli_stmt_execute($stmt2);
            // Aquí podrías agregar registro de trazabilidad...
            registrarTrazabilidad(
                $_SESSION['usuario'], 
                $_SERVER['PHP_SELF'], 
                'Cambio de estado post firma acta de muestreo', 
                'análisis externo',  
                $id_analisis_externo, 
                $query,  
                $params, 
                $exito ? 1 : 0, 
                $exito ? null : mysqli_error($link)
            );
            if ($exito){
                echo json_encode(['success' => 'Datos guardados correctamente.']);
            } else {
                echo json_encode(['error' => 'Error al guardar datos: ' . mysqli_stmt_error($stmt2)]);
            }
            mysqli_stmt_close($stmt2);
        }
    } else {
        echo json_encode(['error' => 'Error al guardar datos: ' . mysqli_stmt_error($stmt)]);
    }
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['error' => 'Error al preparar la declaración: ' . mysqli_error($link)]);
}

mysqli_close($link);
?>
