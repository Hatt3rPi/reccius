<?php
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

try {
    // 1. Actualiza en calidad_analisis_externo si existe
    $stmt = $link->prepare("UPDATE calidad_analisis_externo SET estado = 'eliminado_por_solicitud_usuario' WHERE id = ? AND EXISTS (SELECT 1 FROM calidad_analisis_externo WHERE id = ?)");
    if ($stmt) {
        $stmt->bind_param("ii", $id_analisisExterno, $id_analisisExterno);
        $stmt->execute();
        $stmt->close();
    } else {
        throw new Exception("Error al preparar la declaración: " . $link->error);
    }

    // 2. Actualiza en calidad_acta_muestreo si existe
    $stmt = $link->prepare("UPDATE calidad_acta_muestreo SET estado = 'eliminado_por_solicitud_usuario' WHERE id_analisisExterno = ? AND EXISTS (SELECT 1 FROM calidad_acta_muestreo WHERE id_analisisExterno = ?)");
    if ($stmt) {
        $stmt->bind_param("ii", $id_analisisExterno, $id_analisisExterno);
        $stmt->execute();
        $stmt->close();
    } else {
        throw new Exception("Error al preparar la declaración: " . $link->error);
    }

    // 3. Actualiza en calidad_productos_analizados si existe
    $stmt = $link->prepare("UPDATE calidad_productos_analizados SET estado = 'eliminado_por_solicitud_usuario' WHERE id_analisisExterno = ? AND EXISTS (SELECT 1 FROM calidad_productos_analizados WHERE id_analisisExterno = ?)");
    if ($stmt) {
        $stmt->bind_param("ii", $id_analisisExterno, $id_analisisExterno);
        $stmt->execute();
        $stmt->close();
    } else {
        throw new Exception("Error al preparar la declaración: " . $link->error);
    }

    // 4. Actualiza en calidad_acta_liberacion si existe
    $stmt = $link->prepare("UPDATE calidad_acta_liberacion SET estado = 'eliminado_por_solicitud_usuario' WHERE id_analisisExterno = ? AND EXISTS (SELECT 1 FROM calidad_acta_liberacion WHERE id_analisisExterno = ?)");
    if ($stmt) {
        $stmt->bind_param("ii", $id_analisisExterno, $id_analisisExterno);
        $stmt->execute();
        $stmt->close();
    } else {
        throw new Exception("Error al preparar la declaración: " . $link->error);
    }

    // Confirma la transacción
    $link->commit();
    echo json_encode(['success' => 'Estados actualizados correctamente'], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    // Si algo falla, realiza un rollback
    $link->rollback();
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    // Cerrar la conexión
    $link->close();
}
?>
