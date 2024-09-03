<?php
// archivo: pages\backend\analisis\eliminar_analisis_externoBE.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Comprobar si el ID fue enviado por POST
if (!isset($_POST['id_analisisExterno'])) {
    echo json_encode(['error' => 'No se proporcionó el ID necesario.']);
    exit;
}

// Verificar la conexión
if ($link->connect_error) {
    die("Conexión fallida: " . $link->connect_error);
}

// Inicia la transacción
$link->begin_transaction();
$id_analisisExterno = intval($_POST['id_analisisExterno']);
$motivo_eliminacion = $_POST['motivo_eliminacion'];
$fecha_eliminacion = $_POST['fecha_eliminacion'];

$resultados = [];

try {
    // 1. Actualiza en calidad_analisis_externo si existe
    $stmt = $link->prepare("UPDATE calidad_analisis_externo SET estado = 'eliminado_por_solicitud_usuario', motivo_eliminacion =?, fecha_eliminacion =? WHERE id = ? AND EXISTS (SELECT 1 FROM calidad_analisis_externo WHERE id = ?)");
    if ($stmt) {
        $stmt->bind_param("ssii", $motivo_eliminacion, $fecha_eliminacion , $id_analisisExterno, $id_analisisExterno);
        $stmt->execute();
        $resultados[] = "calidad_analisis_externo: actualización exitosa";
        $stmt->close();
    } else {
        throw new Exception("Error al preparar la declaración para calidad_analisis_externo: " . $link->error);
    }

    // 2. Actualiza en calidad_acta_muestreo si existe
    $stmt = $link->prepare("UPDATE calidad_acta_muestreo SET estado = 'eliminado_por_solicitud_usuario' WHERE id_analisisExterno = ? AND EXISTS (SELECT 1 FROM calidad_acta_muestreo WHERE id_analisisExterno = ?)");
    if ($stmt) {
        $stmt->bind_param("ii", $id_analisisExterno, $id_analisisExterno);
        $stmt->execute();
        $resultados[] = "calidad_acta_muestreo: actualización exitosa";
        $stmt->close();
    } else {
        throw new Exception("Error al preparar la declaración para calidad_acta_muestreo: " . $link->error);
    }

    // 3. Actualiza en calidad_productos_analizados si existe
    $stmt = $link->prepare("UPDATE calidad_productos_analizados SET estado = 'eliminado_por_solicitud_usuario' WHERE id_analisisExterno = ? AND EXISTS (SELECT 1 FROM calidad_productos_analizados WHERE id_analisisExterno = ?)");
    if ($stmt) {
        $stmt->bind_param("ii", $id_analisisExterno, $id_analisisExterno);
        $stmt->execute();
        $resultados[] = "calidad_productos_analizados: actualización exitosa";
        $stmt->close();
    } else {
        throw new Exception("Error al preparar la declaración para calidad_productos_analizados: " . $link->error);
    }

    // 4. Actualiza en calidad_acta_liberacion si existe
    $stmt = $link->prepare("UPDATE calidad_acta_liberacion SET estado = 'eliminado_por_solicitud_usuario' WHERE id_analisisExterno = ? AND EXISTS (SELECT 1 FROM calidad_acta_liberacion WHERE id_analisisExterno = ?)");
    if ($stmt) {
        $stmt->bind_param("ii", $id_analisisExterno, $id_analisisExterno);
        $stmt->execute();
        $resultados[] = "calidad_acta_liberacion: actualización exitosa";
        $stmt->close();
    } else {
        throw new Exception("Error al preparar la declaración para calidad_acta_liberacion: " . $link->error);
    }

    // 5. Actualiza en la tabla tareas relacionadas con calidad_analisis_externo
    $stmt = $link->prepare("UPDATE tareas t
                            JOIN calidad_analisis_externo aex ON t.id_relacion = aex.id AND t.tabla_relacion = 'calidad_analisis_externo'
                            SET t.estado = 'eliminado_por_solicitud_usuario'
                            WHERE aex.id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id_analisisExterno);
        $stmt->execute();
        $resultados[] = "tareas relacionadas con calidad_analisis_externo: actualización exitosa";
        $stmt->close();
    } else {
        throw new Exception("Error al preparar la declaración para tareas relacionadas con calidad_analisis_externo: " . $link->error);
    }

    // 6. Actualiza en la tabla tareas relacionadas con calidad_acta_muestreo
    $stmt = $link->prepare("UPDATE tareas t
                            JOIN calidad_acta_muestreo cam ON t.id_relacion = cam.id AND t.tabla_relacion = 'calidad_acta_muestreo'
                            SET t.estado = 'eliminado_por_solicitud_usuario'
                            WHERE cam.id_analisisExterno = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id_analisisExterno);
        $stmt->execute();
        $resultados[] = "tareas relacionadas con calidad_acta_muestreo: actualización exitosa";
        $stmt->close();
    } else {
        throw new Exception("Error al preparar la declaración para tareas relacionadas con calidad_acta_muestreo: " . $link->error);
    }

    // 7. Actualiza en la tabla tareas relacionadas con calidad_productos_analizados
    $stmt = $link->prepare("UPDATE tareas t
                            JOIN calidad_productos_analizados cpa ON t.id_relacion = cpa.id AND t.tabla_relacion = 'calidad_productos_analizados'
                            SET t.estado = 'eliminado_por_solicitud_usuario'
                            WHERE cpa.id_analisisExterno = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id_analisisExterno);
        $stmt->execute();
        $resultados[] = "tareas relacionadas con calidad_productos_analizados: actualización exitosa";
        $stmt->close();
    } else {
        throw new Exception("Error al preparar la declaración para tareas relacionadas con calidad_productos_analizados: " . $link->error);
    }

    // 8. Actualiza en la tabla tareas relacionadas con calidad_acta_liberacion
    $stmt = $link->prepare("UPDATE tareas t
                            JOIN calidad_acta_liberacion cal ON t.id_relacion = cal.id AND t.tabla_relacion = 'calidad_acta_liberacion'
                            SET t.estado = 'eliminado_por_solicitud_usuario'
                            WHERE cal.id_analisisExterno = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id_analisisExterno);
        $stmt->execute();
        $resultados[] = "tareas relacionadas con calidad_acta_liberacion: actualización exitosa";
        $stmt->close();
    } else {
        throw new Exception("Error al preparar la declaración para tareas relacionadas con calidad_acta_liberacion: " . $link->error);
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
