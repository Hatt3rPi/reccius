<?php
//archivo: pages\backend\tareas\cambiar_usuarioBE.php
//solución podría conllevar problemas del tipo de cambio de usuarios que firman
session_start();

require_once "/home/customw2/conexiones/config_reccius.php";
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}
header('Content-Type: application/json');


$input = json_decode(file_get_contents('php://input'), true);

if (!isset($_POST['idTarea']) || !isset($_POST['usuarioNuevo'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'Datos insuficientes']);
    exit;
}

$idTarea = $_POST['idTarea'];
$usuarioNuevo = $_POST['usuarioNuevo'];

// Obtener la tarea específica
$queryTarea = "SELECT * FROM tareas WHERE id = ?";
$stmtTarea = mysqli_prepare($link, $queryTarea);

if (!$stmtTarea) {
  http_response_code(500);
  echo json_encode(['exito' => false, 'mensaje' => 'Error en la preparación de la consulta']);
  exit;
}

mysqli_stmt_bind_param($stmtTarea, "i", $idTarea);
mysqli_stmt_execute($stmtTarea);
$resultTarea = mysqli_stmt_get_result($stmtTarea);
$tarea = mysqli_fetch_assoc($resultTarea);
mysqli_stmt_close($stmtTarea);

if (!$tarea) {
  http_response_code(404);
  echo json_encode(['exito' => false, 'mensaje' => 'No se encontró la tarea especificada']);
  exit;
}

$tablaRelacion = $tarea['tabla_relacion'];
$idRelacion = $tarea['id_relacion'];
$tipoTarea = $tarea['tipo'];

// Mapeo de tabla_relacion/tipo a los campos correspondientes
$updateRelacion = null;

switch ($tablaRelacion) {
    case 'calidad_especificacion_productos':
        if ($tipoTarea == 'Firma 2') {
            $updateRelacion = "UPDATE calidad_especificacion_productos SET usuario_revisor = ? WHERE id = ?";
        } elseif ($tipoTarea == 'Firma 3') {
            $updateRelacion = "UPDATE calidad_especificacion_productos SET usuario_aprobador = ? WHERE id = ?";
        }
        break;
    case 'calidad_analisis_externo':
        if ($tipoTarea == 'Generar Acta Muestreo') {
            $updateRelacion = "UPDATE calidad_analisis_externo SET muestreado_por = ? WHERE id = ?";
        } elseif ($tipoTarea == 'Enviar a Laboratorio') {
            $updateRelacion = "UPDATE calidad_analisis_externo SET revisado_por = ? WHERE id = ?";
        } elseif ($tipoTarea == 'Ingresar resultados Laboratorio') {
            $updateRelacion = "UPDATE calidad_analisis_externo SET revisado_por = ? WHERE id = ?";
        } elseif ($tipoTarea == 'Emitir acta de liberación') {
            $updateRelacion = "UPDATE calidad_analisis_externo SET solicitado_por = ? WHERE id = ?";
        }
        break;
    case 'calidad_acta_muestreo':
        if ($tipoTarea == 'Firma 1') {
            $updateRelacion = "UPDATE calidad_acta_muestreo SET muestreado_por = ? WHERE id = ?";
        } elseif ($tipoTarea == 'Firma 2') {
            $updateRelacion = "UPDATE calidad_acta_muestreo SET muestreado_por = ? WHERE id = ?";
        } elseif ($tipoTarea == 'Firma 3') {
            $updateRelacion = "UPDATE calidad_acta_muestreo SET verificado_por = ? WHERE id = ?";
        }
        break;
}


// Si se encontró el campo correspondiente
if ($updateRelacion) {
    // Actualizar la tabla tareas
    $updateTareas = "UPDATE tareas SET usuario_ejecutor = ? WHERE id = ?";
    $stmt = mysqli_prepare($link, $updateTareas);
    mysqli_stmt_bind_param($stmt, "si", $usuarioNuevo, $idTarea);
    $exitoTarea = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($exitoTarea) {
        // Actualizar la tabla relacionada
        $stmt = mysqli_prepare($link, $updateRelacion);
        mysqli_stmt_bind_param($stmt, "si", $usuarioNuevo, $idRelacion);
        $exitoRelacion = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($exitoRelacion) {
            // Aquí debes incluir la lógica para finalizar la tarea o registrar una nueva si es necesario.
            registrarTrazabilidad(
                $_SESSION['usuario'],
                $_SERVER['PHP_SELF'],
                'Cambio de usuario ejecutor de tarea',
                'tareas',
                $idRelacion,
                $tablaRelacion,
                [$usuarioNuevo, $idRelacion],
                $exitoRelacion ? 1 : 0,
                $exitoRelacion ? null : mysqli_error($link)
            );
            echo json_encode(['exito' => true, 'mensaje' => 'Usuario ejecutor actualizado correctamente.']);
        } else {
            http_response_code(500);
            registrarTrazabilidad(
                $_SESSION['usuario'],
                $_SERVER['PHP_SELF'],
                'Cambio de usuario ejecutor de tarea',
                'tareas',
                $idRelacion,
                $tablaRelacion,
                [$usuarioNuevo, $idRelacion],
                $exitoRelacion ? 1 : 0,
                $exitoRelacion ? null : mysqli_error($link)
            );
            echo json_encode(['exito' => false, 'mensaje' => 'Error al actualizar el usuario en la tabla relacionada.']);
        }
    } else {
        http_response_code(500);
        echo json_encode(['exito' => false, 'mensaje' => 'Error al actualizar el usuario en la tabla tareas.']);
    }
} else {
    echo json_encode(['exito' => false, 'mensaje' => 'No se encontró el campo correspondiente para la tarea especificada.']);
}
?>
