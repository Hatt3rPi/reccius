<?php
// archivo: pages\backend\acta_muestreo\rechazar_acta_muestreoBE.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Comprobar si el ID fue enviado por POST
if (!isset($_POST['id'])) {
    echo json_encode(['error' => 'No se proporcionó el ID necesario.']);
    exit;
}

// Verificar la conexión
if ($link->connect_error) {
    die("Conexión fallida: " . $link->connect_error);
}

// Inicia la transacción
$link->begin_transaction();
$id_actaMuestreo = intval($_POST['id']);
$motivo_rechazo = $_POST['motivo_rechazo'];
$fecha_rechazo = $_POST['fecha_rechazo'];

$resultados = [];

try {
    // 1. Actualiza en calidad_analisis_externo si existe
    $stmt = $link->prepare("UPDATE calidad_acta_muestreo SET estado = 'rechazado', motivo_rechazo =?, fecha_rechazo =? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("ssi", $motivo_rechazo, $fecha_rechazo , $id_actaMuestreo);
        $stmt->execute();
        $resultados[] = "calidad_acta_muestreo: actualización exitosa";
        $stmt->close();

        registrarTrazabilidad(
            $_SESSION['usuario'],
            $_SERVER['PHP_SELF'],
            'Rechazo Acta Muestreo',
            'CALIDAD - Solicitud Análisis Externo',
            0, // ID del registro insertado
            "UPDATE calidad_acta_muestreo SET estado = 'rechazado', motivo_rechazo =?, fecha_rechazo =? WHERE id = ?",
            [$motivo_rechazo, $fecha_rechazo , $id_actaMuestreo],
            1,
            null
        );
    } else {
        throw new Exception("Error al preparar la declaración para calidad_acta_muestreo: " . $link->error);
    }
    // 2. Actualiza en la tabla tareas relacionadas con calidad_acta_muestreo
    $stmt = $link->prepare("UPDATE calidad_productos_analizados SET id_actaMuestreo=null where id_actaMuestreo=?;");
    if ($stmt) {
    $stmt->bind_param("i", $id_actaMuestreo);
    $stmt->execute();
    $resultados[] = "calidad_productos_analizados relacionadas con calidad_acta_muestreo: actualización exitosa";
    $stmt->close();
    } else {
    throw new Exception("Error al preparar la declaración para calidad_productos_analizados relacionadas con calidad_acta_muestreo: " . $link->error);
    }

    // 6. Actualiza en la tabla tareas relacionadas con calidad_acta_muestreo
    $stmt = $link->prepare("UPDATE tareas t
                            JOIN calidad_acta_muestreo cam ON t.id_relacion = cam.id
                            SET t.estado = 'eliminado_por_solicitud_usuario'
                            WHERE t.tabla_relacion = 'calidad_acta_muestreo' AND t.id_relacion = ?");
    if ($stmt) {
    $stmt->bind_param("i", $id_actaMuestreo);
    $stmt->execute();
    $resultados[] = "tareas relacionadas con calidad_acta_muestreo: actualización exitosa";
    $stmt->close();
    } else {
    throw new Exception("Error al preparar la declaración para tareas relacionadas con calidad_acta_muestreo: " . $link->error);
    }
    // Confirma la transacción
    $link->commit();
    echo json_encode(['resultados' => $resultados], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    // Si algo falla, realiza un rollback
    $link->rollback();
    $resultados[] = "Error: " . $e->getMessage();
    echo json_encode(['resultados' => $resultados]);
} finally {
    // Cerrar la conexión
    $link->close();
}
?>
