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
$firma2 = isset($input['firma2']) ? $input['firma2'] : null;
$firma3 = isset($input['firma3']) ? $input['firma3'] : null;
$acta = isset($input['acta']) ? $input['acta'] : null;
$numero_solicitud = isset($input['numero_solicitud']) ? $input['numero_solicitud'] : null;
$solicitado_por_analisis_externo = isset($input['solicitado_por_analisis_externo']) ? $input['solicitado_por_analisis_externo'] : null;

// Validar que los datos esenciales están presentes
if (!$id_actaMuestreo || !$etapa || !$respuestas) {
    echo json_encode(['error' => 'Datos faltantes o incorrectos.']);
    exit;
}

// Dependiendo de la etapa, los datos relevantes cambiarán
switch ($etapa) {
    case 1:
        $estado = 'En proceso de firma';
        if (count($textareaData) === 0) {
            echo json_encode(['error' => 'Datos de textarea faltantes para la etapa 1.']);
            exit;
        }
        $query = "UPDATE calidad_acta_muestreo SET
                    estado=?, resultados_muestrador=?, pregunta5=?, pregunta6=?, pregunta7=?, pregunta8=?,
                    muestreador=?, fecha_firma_muestreador=?
                  WHERE id=?";
        $types = "ssssssssi";
        $params = [
            $estado,
            $respuestas,
            $textareaData['form_textarea5'] ?? '',
            $textareaData['form_textarea6'] ?? '',
            $textareaData['form_textarea7'] ?? '',
            $textareaData['form_textarea8'] ?? '',
            $usuario,
            $fechaActual,
            $id_actaMuestreo
        ];
        $flujo = 'Firma usuario 1 de 3';
        break;
    case 2:
        $estado = 'En proceso de firma';
        $query = "UPDATE calidad_acta_muestreo SET
                    estado=?, resultados_responsable=?, responsable=?, fecha_firma_responsable=?
                  WHERE id=?";
        $types = "ssssi";
        $params = [
            $estado,
            $respuestas,
            $usuario,
            $fechaActual,
            $id_actaMuestreo
        ];
        $flujo = 'Firma usuario 2 de 3';
        break;
    case 3:
        $estado = 'Vigente';
        $query = "UPDATE calidad_acta_muestreo SET
                    estado=?, verificador=?, fecha_firma_verificador=?
                  WHERE id=?";
        $types = "sssi";
        $params = [
            $estado,
            $usuario,
            $fechaActual,
            $id_actaMuestreo
        ];
        $flujo = 'Firma usuario 3 de 3';
        break;
    default:
        echo json_encode(['error' => 'Etapa de firma no reconocida.']);
        exit;
}

// Ejecutar la consulta preparada
if ($stmt = mysqli_prepare($link, $query)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    $exito = mysqli_stmt_execute($stmt);
    registrarTrazabilidad(
        $_SESSION['usuario'], 
        $_SERVER['PHP_SELF'], 
        $flujo, 
        'CALIDAD - Acta de Muestreo',  
        $id_actaMuestreo, 
        $query,  
        $params, 
        $exito ? 1 : 0, 
        $exito ? null : mysqli_error($link)
    );
    if ($exito) {
        switch ($flujo) {
            case 'Firma usuario 1 de 3':
                // Crear tarea para la segunda firma
                registrarTarea(7, $_SESSION['usuario'], $firma2, '2da firma acta de muestreo: ' . $acta, 2, 'Firma 2', $id_actaMuestreo, 'calidad_acta_muestreo');
                break;
            case 'Firma usuario 2 de 3':
                // Finalizar tarea de la segunda firma y crear tarea para la tercera firma
                finalizarTarea($_SESSION['usuario'], $id_actaMuestreo, 'calidad_acta_muestreo', 'Firma 2');
                registrarTarea(7, $_SESSION['usuario'], $firma3, '3ra firma acta de muestreo: ' . $acta, 2, 'Firma 3', $id_actaMuestreo, 'calidad_acta_muestreo');
                break;
            case 'Firma usuario 3 de 3':
                // Finalizar tarea de la tercera firma
                finalizarTarea($_SESSION['usuario'], $id_actaMuestreo, 'calidad_acta_muestreo', 'Firma 3');
                registrarTarea(7, $_SESSION['usuario'], $solicitado_por_analisis_externo, 'Finalizar Solicitud análisis externo: ' . $numero_solicitud , 2, 'Completar análisis externo', $id_actaMuestreo, 'calidad_analisis_externo');
                break;
            default:
                // Manejo de caso por defecto si es necesario
                break;
        }
        
        unset($_SESSION['nuevo_id']);
        $_SESSION['nuevo_id'] = $id_actaMuestreo;
        if ($etapa == 3) {
            $id_analisis_externo = intval($input['id_analisis_externo']);
            $estado2 = 'Pendiente completar análisis';
            $query = "UPDATE calidad_analisis_externo SET estado=? WHERE id=?";
            $types = "si";
            $stmt2 = mysqli_prepare($link, $query);
            mysqli_stmt_bind_param($stmt2, $types, $estado2, $id_analisis_externo);
            $exito = mysqli_stmt_execute($stmt2);
            registrarTrazabilidad(
                $_SESSION['usuario'], 
                $_SERVER['PHP_SELF'], 
                'Cambio de estado post firma acta de muestreo', 
                'CALIDAD - Análisis Externo',  
                $id_analisis_externo, 
                $query,  
                [$estado2, $id_analisis_externo], 
                $exito ? 1 : 0, 
                $exito ? null : mysqli_error($link)
            );
            if ($exito) {
                $query = "UPDATE calidad_acta_muestreo AS cam
                JOIN (SELECT id_analisisExterno FROM calidad_acta_muestreo WHERE id = ?) AS sub
                ON cam.id_analisisExterno = sub.id_analisisExterno
                SET cam.estado = 'Deprecado'
                WHERE cam.estado NOT IN ('Vigente')";
                $types = "i";
                $stmt3 = mysqli_prepare($link, $query);
                mysqli_stmt_bind_param($stmt3, $types, $id_actaMuestreo);
                $exito = mysqli_stmt_execute($stmt3);
                registrarTrazabilidad(
                    $_SESSION['usuario'], 
                    $_SERVER['PHP_SELF'], 
                    'Deprecado Actas de Muestreo inconclusas', 
                    'CALIDAD - Acta de Muestreo',  
                    $id_actaMuestreo, 
                    $query,  
                    [$id_actaMuestreo], 
                    $exito ? 1 : 0, 
                    $exito ? null : mysqli_error($link)
                );
                if ($exito) {
                    echo json_encode(['success' => 'Datos guardados correctamente.']);
                } else {
                    echo json_encode(['error' => 'Error al guardar datos deprecados: ' . mysqli_stmt_error($stmt3)]);
                }
                mysqli_stmt_close($stmt3);
            } else {
                echo json_encode(['error' => 'Error al guardar datos modificación análisis externo: ' . mysqli_stmt_error($stmt2)]);
            }
            mysqli_stmt_close($stmt2);
        } else {
            echo json_encode(['success' => 'Datos guardados correctamente.']);
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
