<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}

header('Content-Type: application/json');

if (!isset($_POST['idTarea']) || !isset($_POST['usuarioNuevo'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'Datos insuficientes']);
    exit;
}

$idTarea = $_POST['idTarea'];
$usuarioNuevo = $_POST['usuarioNuevo'];
$usuarioOriginal = $_POST['ejecutorOriginal'];

mysqli_begin_transaction($link);

try {
    // 1. Primero actualizar la tabla tareas
    $updateTareas = "UPDATE tareas SET usuario_ejecutor = ? WHERE id = ?";
    $stmt = mysqli_prepare($link, $updateTareas);
    mysqli_stmt_bind_param($stmt, "si", $usuarioNuevo, $idTarea);
    $exitoTareas = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if (!$exitoTareas) {
        throw new Exception("Error actualizando tabla tareas");
    }

    // 2. Registrar el cambio en tareas_cambio_usuarios
    $motivo = $_POST['motivo'];
    $insertCambio = "INSERT INTO tareas_cambio_usuarios (id_tarea, usuario_original, usuario_nuevo, fecha_cambio, motivo, cambiado_por) 
                     VALUES (?, ?, ?, NOW(), ?, ?)";
    $stmt = mysqli_prepare($link, $insertCambio);
    mysqli_stmt_bind_param($stmt, "issss", $idTarea, $usuarioOriginal, $usuarioNuevo, $motivo, $_SESSION['usuario']);
    $exitoCambio = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if (!$exitoCambio) {
        throw new Exception("Error registrando cambio de usuario");
    }

    // 3. Obtener información de la tarea para actualizar tabla relacionada
    $queryTarea = "SELECT tabla_relacion, id_relacion, tipo FROM tareas WHERE id = ?";
    $stmt = mysqli_prepare($link, $queryTarea);
    mysqli_stmt_bind_param($stmt, "i", $idTarea);
    mysqli_stmt_execute($stmt);
    $resultTarea = mysqli_stmt_get_result($stmt);
    $tarea = mysqli_fetch_assoc($resultTarea);
    mysqli_stmt_close($stmt);

    // 4. Actualizar tabla relacionada según el tipo
    $updateRelacion = null;
    switch ($tarea['tabla_relacion']) {
        case 'calidad_especificacion_productos':
            if ($tarea['tipo'] == 'Firma 2') {
                $updateRelacion = "UPDATE calidad_especificacion_productos SET revisado_por = ? WHERE id_especificacion = ?";
            } elseif ($tarea['tipo'] == 'Firma 3') {
                $updateRelacion = "UPDATE calidad_especificacion_productos SET aprobado_por = ? WHERE id_especificacion = ?";
            }
            break;
        case 'calidad_analisis_externo':
            switch ($tarea['tipo']) {
                case 'Generar Acta Muestreo':
                    $updateRelacion = "UPDATE calidad_analisis_externo SET muestreado_por = ? WHERE id = ?";
                    break;
                case 'Enviar a Laboratorio':
                    $updateRelacion = "UPDATE calidad_analisis_externo SET enviado_lab_por = ? WHERE id = ?";
                    break;
                case 'Ingresar resultados Laboratorio':
                    $updateRelacion = "UPDATE calidad_analisis_externo SET revisado_por = ? WHERE id = ?";
                    break;
                case 'Emitir acta de liberación':
                    $updateRelacion = "UPDATE calidad_analisis_externo SET liberado_por = ? WHERE id = ?";
                    break;
            }
            break;
        case 'calidad_acta_muestreo':
            switch ($tarea['tipo']) {
                case 'Firma 1':
                    $updateRelacion = "UPDATE calidad_acta_muestreo SET muestreador = ? WHERE id = ?";
                    break;
                case 'Firma 2':
                    $updateRelacion = "UPDATE calidad_acta_muestreo SET responsable = ? WHERE id = ?";
                    break;
                case 'Firma 3':
                    $updateRelacion = "UPDATE calidad_acta_muestreo SET verificador = ? WHERE id = ?";
                    break;
            }
            break;
    }

    if ($updateRelacion) {
        $stmt = mysqli_prepare($link, $updateRelacion);
        mysqli_stmt_bind_param($stmt, "si", $usuarioNuevo, $tarea['id_relacion']);
        $exitoRelacion = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if (!$exitoRelacion) {
            throw new Exception("Error actualizando tabla relacionada");
            http_response_code(500);
            registrarTrazabilidad(
                $_SESSION['usuario'],
                $_SERVER['PHP_SELF'],
                'Cambio de usuario ejecutor de tarea',
                'tareas',
                $idRelacion,
                $insertCambio,
                [$idTarea, $usuarioOriginal, $usuarioNuevo, $motivo, $_SESSION['usuario']],
                $exitoRelacion ? 1 : 0,
                $exitoRelacion ? null : mysqli_error($link)
            );
        } else {
            // Aquí debes incluir la lógica para finalizar la tarea o registrar una nueva si es necesario.
            registrarTrazabilidad(
                $_SESSION['usuario'],
                $_SERVER['PHP_SELF'],
                'Cambio de usuario ejecutor de tarea',
                'tareas',
                $idRelacion,
                $insertCambio,
                [$idTarea, $usuarioOriginal, $usuarioNuevo, $motivo, $_SESSION['usuario']],
                $exitoRelacion ? 1 : 0,
                $exitoRelacion ? null : mysqli_error($link)
            );
            echo json_encode(['exito' => true, 'mensaje' => 'Usuario ejecutor actualizado correctamente.']);
        } 
    }

    mysqli_commit($link);
    
    echo json_encode(['exito' => true, 'mensaje' => 'Usuario actualizado correctamente en todas las tablas']);

} catch (Exception $e) {
    mysqli_rollback($link);
    echo json_encode(['exito' => false, 'mensaje' => $e->getMessage()]);
}

mysqli_close($link);
?>
