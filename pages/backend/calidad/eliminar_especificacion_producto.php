<?php
// archivo: pages\backend\calidad\eliminar_especificacion_producto.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Comprobar si el ID fue enviado por POST
if (!isset($_POST['id_especificacionProducto'])) {
    echo json_encode(['error' => 'No se proporcionó el ID necesario.']);
    exit;
}

// Verificar la conexión
if ($link->connect_error) {
    die("Conexión fallida: " . $link->connect_error);
}

// Inicia la transacción
$link->begin_transaction();
$id_especificacionProducto = intval($_POST['id_especificacionProducto']);
$motivo_eliminacion = $_POST['motivo_eliminacion'];
$fecha_eliminacion = $_POST['fecha_eliminacion'];

$resultados = [];

try {
    // 0. Actualiza en calidad_especificacion_productos si existe
    $stmt = $link->prepare("UPDATE calidad_especificacion_productos SET estado = 'eliminado_por_solicitud_usuario', motivo_eliminacion =?, fecha_eliminacion =? WHERE id_especificacion = ?");
    if ($stmt) {
        $stmt->bind_param("ssi", $motivo_eliminacion, $fecha_eliminacion , $id_especificacionProducto);
        $stmt->execute();
        $resultados[] = "calidad_especificacion_productos: actualización exitosa";
        $stmt->close();


        registrarTrazabilidad(
            $_SESSION['usuario'],
            $_SERVER['PHP_SELF'],
            'Eliminación Especificación',
            'CALIDAD - Especificación de Producto',
            0, // ID del registro insertado
            "UPDATE calidad_especificacion_productos SET estado = 'eliminado_por_solicitud_usuario', motivo_eliminacion =?, fecha_eliminacion =? WHERE id_especificacion = ?",
            [$motivo_eliminacion, $fecha_eliminacion , $id_especificacionProducto],
            1,
            null
        );

    } else {
        throw new Exception("Error al preparar la declaración para calidad_especificacion_productos: " . $link->error);
    }
    // 1. Actualiza en calidad_analisis_externo si existe
    $stmt = $link->prepare("UPDATE calidad_analisis_externo SET estado = 'eliminado_por_solicitud_usuario' WHERE id_especificacion = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id_especificacionProducto);
        $stmt->execute();
        $resultados[] = "calidad_especificacion_productos: actualización exitosa";
        $stmt->close();
    } else {
        throw new Exception("Error al preparar la declaración para calidad_especificacion_productos: " . $link->error);
    }
    // 2. Actualiza en calidad_acta_muestreo si existe
    $stmt = $link->prepare("UPDATE calidad_acta_muestreo SET estado = 'eliminado_por_solicitud_usuario' WHERE id_especificacion = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id_especificacionProducto);
        $stmt->execute();
        $resultados[] = "calidad_acta_muestreo: actualización exitosa";
        $stmt->close();
    } else {
        throw new Exception("Error al preparar la declaración para calidad_acta_muestreo: " . $link->error);
    }

    // 3. Actualiza en calidad_productos_analizados si existe
    $stmt = $link->prepare("UPDATE calidad_productos_analizados SET estado = 'eliminado_por_solicitud_usuario' WHERE id_especificacion = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id_especificacionProducto);
        $stmt->execute();
        $resultados[] = "calidad_productos_analizados: actualización exitosa";
        $stmt->close();
    } else {
        throw new Exception("Error al preparar la declaración para calidad_productos_analizados: " . $link->error);
    }

    // 4. Actualiza en calidad_acta_liberacion si existe
    $stmt = $link->prepare("UPDATE calidad_acta_liberacion SET estado = 'eliminado_por_solicitud_usuario' WHERE id_especificacion = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id_especificacionProducto);
        $stmt->execute();
        $resultados[] = "calidad_acta_liberacion: actualización exitosa";
        $stmt->close();
    } else {
        throw new Exception("Error al preparar la declaración para calidad_acta_liberacion: " . $link->error);
    }


    // 5.1 Actualiza en la tabla tareas relacionadas con calidad_especificacion_productos
    $stmt = $link->prepare("UPDATE tareas t
                            JOIN calidad_especificacion_productos ep ON t.id_relacion = ep.id AND t.tabla_relacion = 'calidad_especificacion_productos'
                            SET t.estado = 'eliminado_por_solicitud_usuario'
                            WHERE ep.id_especificacion = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id_especificacionProducto);
        $stmt->execute();
        $resultados[] = "tareas relacionadas con calidad_especificacion_productos: actualización exitosa";
        $stmt->close();
    } else {
        throw new Exception("Error al preparar la declaración para tareas relacionadas con calidad_especificacion_productos: " . $link->error);
    }

    // 5.2 Actualiza en la tabla tareas relacionadas con calidad_especificacion_productos
    $stmt = $link->prepare("UPDATE tareas t
                            JOIN calidad_analisis_externo aex ON t.id_relacion = aex.id AND t.tabla_relacion = 'calidad_analisis_externo'
                            SET t.estado = 'eliminado_por_solicitud_usuario'
                            WHERE aex.id_especificacion = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id_especificacionProducto);
        $stmt->execute();
        $resultados[] = "tareas relacionadas con calidad_analisis_externo: actualización exitosa";
        $stmt->close();
    } else {
        throw new Exception("Error al preparar la declaración para tareas relacionadas con calidad_especificacion_productos: " . $link->error);
    }
    // 6. Actualiza en la tabla tareas relacionadas con calidad_acta_muestreo
    $stmt = $link->prepare("UPDATE tareas t
                            JOIN calidad_acta_muestreo cam ON t.id_relacion = cam.id AND t.tabla_relacion = 'calidad_acta_muestreo'
                            SET t.estado = 'eliminado_por_solicitud_usuario'
                            WHERE cam.id_especificacion = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id_especificacionProducto);
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
                            WHERE cpa.id_especificacion = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id_especificacionProducto);
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
                            WHERE cal.id_especificacion = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id_especificacionProducto);
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
    $link->rollback();
    error_log("Error durante la eliminación de la especificación: " . $e->getMessage());
    error_log("Datos recibidos: id_especificacionProducto=" . $id_especificacionProducto . ", motivo_eliminacion=" . $motivo_eliminacion . ", fecha_eliminacion=" . $fecha_eliminacion);
    $resultados[] = "Error: " . $e->getMessage();
    echo json_encode(['resultados' => $resultados]);
} finally {
    // Cerrar la conexión
    $link->close();
}
?>
