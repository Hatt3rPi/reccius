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
$plan_muestreo = isset($input['plan_muestreo']) ? json_encode($input['plan_muestreo']) : null;
$observaciones = isset($input['observaciones']) ? $input['observaciones'] : null;
$numero_solicitud = isset($input['numero_solicitud']) ? $input['numero_solicitud'] : null;
$solicitado_por_analisis_externo = isset($input['solicitado_por_analisis_externo']) ? $input['solicitado_por_analisis_externo'] : null;
$plan_muestreo_json = null;
if ($plan_muestreo !== null) {
    $plan_muestreo_json = json_encode($plan_muestreo);
}
// Validar que los datos esenciales están presentes
if (!$id_actaMuestreo || !$etapa || !$respuestas) {
    echo json_encode(['error' => 'Datos faltantes o incorrectos.']);
    exit;
}
// Verificar que se haya recibido el ID y las observaciones
$id_analisis_externo_updt = intval($input['id_analisis_externo']);
if (isset($observaciones) && $id_analisis_externo_updt) {
    // Prepara la consulta de actualización
    $query_update_observaciones = "UPDATE calidad_analisis_externo SET observaciones=? WHERE id=?";
    $types_observaciones = "si"; // 's' para observaciones (string), 'i' para id (integer)

    // Ejecutar la consulta preparada
    if ($stmt_observaciones = mysqli_prepare($link, $query_update_observaciones)) {
        mysqli_stmt_bind_param($stmt_observaciones, $types_observaciones, $observaciones, $id_analisis_externo_updt);
        $exito_observaciones = mysqli_stmt_execute($stmt_observaciones);

        // Registrar trazabilidad de la acción
        registrarTrazabilidad(
            $_SESSION['usuario'], 
            $_SERVER['PHP_SELF'], 
            'Actualización de observaciones en análisis externo', 
            'CALIDAD - Análisis Externo',  
            $id_analisis_externo_updt, 
            $query_update_observaciones,  
            [$observaciones, $id_analisis_externo_updt], 
            $exito_observaciones ? 1 : 0, 
            $exito_observaciones ? null : mysqli_error($link)
        );

        // Verificar si la consulta se ejecutó correctamente
        if ($exito_observaciones) {
            echo json_encode(['success' => 'Observaciones actualizadas correctamente.']);
        } else {
            echo json_encode(['error' => 'Error al actualizar observaciones: ' . mysqli_stmt_error($stmt_observaciones)]);
        }

        mysqli_stmt_close($stmt_observaciones);
    } else {
        echo json_encode(['error' => 'Error al preparar la declaración para observaciones: ' . mysqli_error($link)]);
    }
} else {
    echo json_encode(['error' => 'ID de análisis externo o observaciones no definidas.']);
}

// Dependiendo de la etapa, los datos relevantes cambiarán
switch ($etapa) {
    case 1:
        $estado = 'En proceso de firma';
        if (count($textareaData) === 0) {
            echo json_encode(['error' => 'Datos de textarea faltantes para la etapa 1.']);
            exit;
        }
        if ($plan_muestreo_json === null) {
            echo json_encode(['error' => 'Datos del plan de muestreo faltantes para la etapa 1.']);
            exit;
        }
        $query = "UPDATE calidad_acta_muestreo SET
                    estado=?, resultados_muestrador=?, pregunta5=?, pregunta6=?, pregunta7=?, pregunta8=?,
                    plan_muestreo=?, muestreador=?, fecha_firma_muestreador=?
                  WHERE id=?";
        $types = "sssssssssi";
        $params = [
            $estado,
            $respuestas,
            $textareaData['form_textarea5'] ?? '',
            $textareaData['form_textarea6'] ?? '',
            $textareaData['form_textarea7'] ?? '',
            $textareaData['form_textarea8'] ?? '',
            $plan_muestreo_json,
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
                finalizarTarea($_SESSION['usuario'], $id_actaMuestreo, 'calidad_acta_muestreo', 'Firma 1');
                registrarTarea(7, $_SESSION['usuario'], $firma2, '2da firma acta de muestreo: ' . $acta, 2, 'Firma 2', $id_actaMuestreo, 'calidad_acta_muestreo');
                break;
            case 'Firma usuario 2 de 3':
                // Finalizar tarea de la segunda firma y crear tarea para la tercera firma
                finalizarTarea($_SESSION['usuario'], $id_actaMuestreo, 'calidad_acta_muestreo', 'Firma 2');
                registrarTarea(7, $_SESSION['usuario'], $firma3, '3ra firma acta de muestreo: ' . $acta, 2, 'Firma 3', $id_actaMuestreo, 'calidad_acta_muestreo');
                break;
            case 'Firma usuario 3 de 3':
                // Finalizar tarea de la tercera firma
                $id_analisis_externo = intval($input['id_analisis_externo']);
                finalizarTarea($_SESSION['usuario'], $id_actaMuestreo, 'calidad_acta_muestreo', 'Firma 3');
                registrarTarea(7, $_SESSION['usuario'], $solicitado_por_analisis_externo, 'Finalizar Solicitud análisis externo: ' . $numero_solicitud , 2, 'Firma 1', $id_analisis_externo, 'calidad_analisis_externo');
                // tarea anterior se cierra con: finalizarTarea($_SESSION['usuario'], $id_analisis_externo, 'calidad_analisis_externo', 'Firma 1');
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
