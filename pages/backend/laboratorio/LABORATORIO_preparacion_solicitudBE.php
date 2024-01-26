<?php

session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

function limpiarDato($dato) {
    $datoLimpio = trim($dato);
    return htmlspecialchars(stripslashes($datoLimpio));
}

// Funciones para interactuar con la base de datos
function insertarRegistro($link, $datos) {
    // Preparar la consulta SQL para insertar un nuevo registro
    $query = "INSERT INTO calidad_analisis_externo (
                numero_registro, version, numero_solicitud, fecha_registro, tipo_producto, 
                codigo_producto, producto, concentracion, formato, elaboradoPor, lote, 
                tamano_lote, fecha_elaboracion, fecha_vencimiento, tipo_analisis, 
                condicion_almacenamiento, cantidad_muestra, cantidad_contramuestra, 
                registro_isp, muestreado_por, muestreado_POS, laboratorio, fecha_solicitud, 
                analisis_segun, fecha_cotizacion, estandar_provisto_por, adjunta_HDS, 
                fecha_entrega_estimada, numero_documento, numero_especificacion, version_especificacion, 
                id_producto, id_especificacion
              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($link, $query);
    
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . mysqli_error($link));
    }

    // Asociar los datos con la consulta preparada
    mysqli_stmt_bind_param($stmt, 'sissssssssssssssssssssssssssssss', 
                           $datos['numero_registro'], $datos['version'], $datos['numero_solicitud'], 
                           $datos['fecha_registro'], $datos['tipo_producto'], $datos['codigo_producto'], 
                           $datos['producto'], $datos['concentracion'], $datos['formato'], 
                           $datos['elaboradoPor'], $datos['lote'], $datos['tamano_lote'], 
                           $datos['fecha_elaboracion'], $datos['fecha_vencimiento'], 
                           $datos['tipo_analisis'], $datos['condicion_almacenamiento'], 
                           $datos['cantidad_muestra'], $datos['cantidad_contramuestra'], 
                           $datos['registro_isp'], $datos['muestreado_por'], $datos['muestreado_POS'], 
                           $datos['laboratorio'], $datos['fecha_solicitud'], $datos['analisis_segun'], 
                           $datos['fecha_cotizacion'], $datos['estandar_provisto_por'], 
                           $datos['adjunta_HDS'], $datos['fecha_entrega_estimada'], 
                           $datos['numero_documento'], $datos['numero_especificacion'], 
                           $datos['version_especificacion'], $datos['id_producto'], 
                           $datos['id_especificacion']);

    // Ejecutar la consulta
    $exito = mysqli_stmt_execute($stmt);
    $id = $exito ? mysqli_insert_id($link) : 0;
        mysqli_stmt_close($stmt);
        registrarTrazabilidad(
            $_SESSION['usuario'], 
            $_SERVER['PHP_SELF'], 
            'Creación registro análisis externo', 
            'tareas',  
            $id, 
            $sql,  
            $datos, 
            $exito ? 1 : 0, 
            $exito ? null : mysqli_error($link)
        );
    if (!$exito) {
        throw new Exception("Error al ejecutar la inserción: " . mysqli_error($link));
    }

    // Cerrar el statement
    mysqli_stmt_close($stmt);
}


function actualizarRegistro($link, $datos) {
    // Preparar la consulta SQL para actualizar un registro existente
    // (Aquí debes preparar tu consulta SQL usando los datos del formulario)
    // Ejemplo: $query = "UPDATE calidad_analisis_externo SET ... WHERE id=?";

    // Ejecutar la consulta y manejar errores
    // (Aquí debes ejecutar la consulta y manejar posibles errores)
}

// Procesar la solicitud
// Procesar la solicitud
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limpiar y validar datos recibidos del formulario
    $numero_registro = limpiarDato($_POST['numero_registro']);
    $version = limpiarDato($_POST['version']);
    $numero_solicitud = limpiarDato($_POST['numero_solicitud']);
    $fecha_registro = limpiarDato($_POST['fecha_registro']);
    $tipo_producto = limpiarDato($_POST['Tipo_Producto']);
    $codigo_producto = limpiarDato($_POST['codigo_producto']);
    $producto = limpiarDato($_POST['producto']);
    $concentracion = limpiarDato($_POST['concentracion']);
    $formato = limpiarDato($_POST['formato']);
    $elaboradoPor = limpiarDato($_POST['elaboradoPor']);
    $lote = limpiarDato($_POST['lote']);
    $tamano_lote = limpiarDato($_POST['tamano_lote']);
    $fecha_elaboracion = limpiarDato($_POST['fecha_elaboracion']);
    $fecha_vencimiento = limpiarDato($_POST['fecha_vencimiento']);
    $tipo_analisis = limpiarDato($_POST['tipo_analisis']);
    $condicion_almacenamiento = limpiarDato($_POST['condicion_almacenamiento']);
    $cantidad_muestra = limpiarDato($_POST['cantidad_muestra']);
    $cantidad_contramuestra = limpiarDato($_POST['cantidad_contramuestra']);
    $registro_isp = limpiarDato($_POST['registro_isp']);
    $muestreado_por = limpiarDato($_POST['muestreado_por']);
    $muestreado_POS = limpiarDato($_POST['muestreado_POS']);
    $laboratorio = limpiarDato($_POST['laboratorio']);
    $fecha_solicitud = limpiarDato($_POST['fecha_solicitud']);
    $analisis_segun = limpiarDato($_POST['analisis_segun']);
    $fecha_cotizacion = limpiarDato($_POST['fecha_cotizacion']);
    $estandar_provisto_por = limpiarDato($_POST['estandar_provisto_por']);
    $adjunta_HDS = limpiarDato($_POST['adjunta_HDS']);
    $fecha_entrega_estimada = limpiarDato($_POST['fecha_entrega_estimada']);
    $numero_documento = limpiarDato($_POST['numero_documento']);
    $numero_especificacion = limpiarDato($_POST['numero_especificacion']);
    $version_especificacion = limpiarDato($_POST['version_especificacion']);
    $id_producto = isset($_POST['id_producto']) ? limpiarDato($_POST['id_producto']) : null;
    $id_especificacion = isset($_POST['id_especificacion']) ? limpiarDato($_POST['id_especificacion']) : null;

    // Determinar si se está insertando un nuevo registro o actualizando uno existente
    $estaEditando = isset($_POST['id']) && !empty($_POST['id']);

    // Iniciar transacción
    mysqli_begin_transaction($link);

    try {
        // Crear un array con los datos limpios
        $datosLimpios = [
            'numero_registro' => $numero_registro,
            'version' => $version,
            'numero_solicitud' => $numero_solicitud,
            'fecha_registro' => $fecha_registro,
            'tipo_producto' => $tipo_producto,
            'codigo_producto' => $codigo_producto,
            'producto' => $producto,
            'concentracion' => $concentracion,
            'formato' => $formato,
            'elaboradoPor' => $elaboradoPor,
            'lote' => $lote,
            'tamano_lote' => $tamano_lote,
            'fecha_elaboracion' => $fecha_elaboracion,
            'fecha_vencimiento' => $fecha_vencimiento,
            'tipo_analisis' => $tipo_analisis,
            'condicion_almacenamiento' => $condicion_almacenamiento,
            'cantidad_muestra' => $cantidad_muestra,
            'cantidad_contramuestra' => $cantidad_contramuestra,
            'registro_isp' => $registro_isp,
            'muestreado_por' => $muestreado_por,
            'muestreado_POS' => $muestreado_POS,
            'laboratorio' => $laboratorio,
            'fecha_solicitud' => $fecha_solicitud,
            'analisis_segun' => $analisis_segun,
            'fecha_cotizacion' => $fecha_cotizacion,
            'estandar_provisto_por' => $estandar_provisto_por,
            'adjunta_HDS' => $adjunta_HDS,
            'fecha_entrega_estimada' => $fecha_entrega_estimada,
            'numero_documento' => $numero_documento,
            'numero_especificacion' => $numero_especificacion,
            'version_especificacion' => $version_especificacion,
            'id_producto' => $id_producto,
            'id_especificacion' => $id_especificacion
        ];

        if ($estaEditando) {
            actualizarRegistro($link, $datosLimpios);
        } else {
            insertarRegistro($link, $datosLimpios);
        }
        mysqli_commit($link); // Aplicar cambios
        echo json_encode(["exito" => true, "mensaje" => "Operación exitosa"]);
    } catch (Exception $e) {
        mysqli_rollback($link); // Revertir cambios en caso de error
        echo json_encode(["exito" => false, "mensaje" => "Error en la operación: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["exito" => false, "mensaje" => "Método inválido"]);
}


?>
