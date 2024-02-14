<?php

session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

function limpiarDato($dato) {
    $datoLimpio = trim($dato);
    return htmlspecialchars(stripslashes($datoLimpio));
}

// Funciones para interactuar con la base de datos
function insertarRegistro($link, $datos) {
    $query = "INSERT INTO calidad_analisis_externo (version, id_especificacion, id_producto, estado, numero_registro, numero_solicitud, fecha_registro, solicitado_por, revisado_por) VALUES (?, ?, ?, 'Pendiente Acta de Muestreo', ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($link, $query);
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . mysqli_error($link));
    }

    // Asignar valores directos a variables
    $estado = 'Pendiente Acta de Muestreo';
    // Luego usa estas variables en la función mysqli_stmt_bind_param
    mysqli_stmt_bind_param($stmt, 'iiisssss', 
    $datos['version'], 
    $datos['id_especificacion'], 
    $datos['id_producto'], 
    $datos['registro'], 
    $datos['numero_solicitud'], 
    $datos['fecha_registro'], 
    $_SESSION['usuario'], 
    $datos['usuario_revisor']
);


    $exito = mysqli_stmt_execute($stmt);
    $id = $exito ? mysqli_insert_id($link) : 0;
    mysqli_stmt_close($stmt);

    registrarTrazabilidad(
        $_SESSION['usuario'], 
        $_SERVER['PHP_SELF'], 
        'Creación registro análisis externo', 
        'calidad_analisis_externo',  
        $id, 
        $query,  
        [$datos['version'], $datos['id_especificacion'], $datos['id_producto'], 
        $estado, $datos['registro'], $datos['numero_solicitud'], $datos['fecha_registro'], 
        $datos['laboratorio'], $datos['fecha_solicitud'], $datos['analisis_segun'], $datos['numero_documento'], 
        $datos['fecha_cotizacion'], $datos['estandar_provisto_por'],  $datos['adjunta_HDS'],  
        $datos['fecha_entrega_estimada'], $datos['lote'], 
        $datos['registro_isp'], $datos['condicion_almacenamiento'], $datos['muestreado_por'], $datos['muestreado_POS'], 
        $datos['tamano_lote'], $datos['fecha_elaboracion'], $datos['fecha_vence'], $datos['cantidad_muestra'], $datos['cantidad_contramuestra'], 
        $datos['observaciones'], $_SESSION['usuario'], $datos['usuario_revisor']], 
        $exito ? 1 : 0, 
        $exito ? null : mysqli_error($link)
    );
    $_SESSION['buscar_por_ID']=$id;

    if (!$exito) {
        throw new Exception("Error al ejecutar la inserción: " . mysqli_error($link));
    }
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
    registrarTrazabilidad($_SESSION['usuario'], $_SERVER['PHP_SELF'], 'INTENTO DE CARGA', 'LABORATORIO',  1, '', $_POST, '', '');
    // Limpiar y validar datos recibidos del formulario
    $registro = limpiarDato($_POST['registro']);
    $version = limpiarDato($_POST['version']);
    $numero_solicitud = limpiarDato($_POST['numero_solicitud']);
    $fecha_registro = limpiarDato($_POST['fecha_registro']);
    $tipo_producto = limpiarDato($_POST['tipo_producto']);
    $codigo_producto = limpiarDato($_POST['codigo_producto']);
    $producto = limpiarDato($_POST['producto']);
    $concentracion = limpiarDato($_POST['concentracion']);
    $formato = limpiarDato($_POST['formato']);
    $elaboradoPor = limpiarDato($_POST['elaboradoPor']);
    $lote = limpiarDato($_POST['lote']);
    $tamano_lote = limpiarDato($_POST['tamano_lote']);
    $fecha_elaboracion = limpiarDato($_POST['fecha_elaboracion']);
    $fecha_vence = limpiarDato($_POST['fecha_vence']);
    $tipo_analisis = limpiarDato($_POST['tipo_analisis']);
    $condicion_almacenamiento = limpiarDato($_POST['condicion_almacenamiento']);
    $cantidad_muestra = limpiarDato($_POST['cantidad_muestra']);
    $cantidad_contramuestra = limpiarDato($_POST['cantidad_contramuestra']);
    $registro_isp = limpiarDato($_POST['registro_isp']);
    $muestreado_por = limpiarDato($_POST['muestreado_por']);
    $muestreado_POS = limpiarDato($_POST['muestreado_POS']);
    $numero_especificacion = limpiarDato($_POST['numero_especificacion']);
    $version_especificacion = limpiarDato($_POST['version_especificacion']);
    $usuario_revisor = limpiarDato($_POST['usuario_revisor']);
    $id_producto = isset($_POST['id_producto']) ? limpiarDato($_POST['id_producto']) : null;
    $id_especificacion = isset($_POST['id_especificacion']) ? limpiarDato($_POST['id_especificacion']) : null;

    // Determinar si se está insertando un nuevo registro o actualizando uno existente
    $estaEditando = isset($_POST['id']) && !empty($_POST['id']);

    // Iniciar transacción
    mysqli_begin_transaction($link);

    try {
        // Crear un array con los datos limpios
        $datosLimpios = [
            'registro' => $registro,
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
            'fecha_vence' => $fecha_vence,
            'tipo_analisis' => $tipo_analisis,
            'condicion_almacenamiento' => $condicion_almacenamiento,
            'cantidad_muestra' => $cantidad_muestra,
            'cantidad_contramuestra' => $cantidad_contramuestra,
            'registro_isp' => $registro_isp,
            'muestreado_por' => $muestreado_por,
            'muestreado_POS' => $muestreado_POS,
            'numero_especificacion' => $numero_especificacion,
            'version_especificacion' => $version_especificacion,
            'usuario_revisor' => $usuario_revisor,
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
