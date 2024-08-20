<?php
// archivo: pages\backend\calidad\especificacion_productoBE.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

function limpiarDato($dato) {
    $datoLimpio = trim($dato);
    if (empty($datoLimpio)) {
        return null;
    }
    return htmlspecialchars(stripslashes($datoLimpio));
}

$numeroDocumento="";
function insertarOpcionSiNoExiste($link, $categoria, $nuevoValor) {
    $nuevoValor = limpiarDato($nuevoValor);
    $queryVerificar = "SELECT COUNT(*) FROM calidad_opciones_desplegables WHERE nombre_opcion = ? AND categoria = ?";
    $stmtVerificar = mysqli_prepare($link, $queryVerificar);
    mysqli_stmt_bind_param($stmtVerificar, "ss", $nuevoValor, $categoria);
    mysqli_stmt_execute($stmtVerificar);
    mysqli_stmt_bind_result($stmtVerificar, $cantidad);
    mysqli_stmt_fetch($stmtVerificar);
    mysqli_stmt_close($stmtVerificar);
    if ($cantidad == 0) {
        $queryInsertar = "INSERT INTO calidad_opciones_desplegables (categoria, nombre_opcion) VALUES (?, ?)";
        $stmtInsertar = mysqli_prepare($link, $queryInsertar);
        mysqli_stmt_bind_param($stmtInsertar, "ss", $categoria, $nuevoValor);
        mysqli_stmt_execute($stmtInsertar);
        mysqli_stmt_close($stmtInsertar);
    }
}
function obtenerTareasActivas($link, $idEspecificacion) {
    $tareas = [];
    $sql = "select id, tipo, usuario_ejecutor from tareas where tipo in ('Firma 2', 'Firma 3') and id_relacion=?;";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $idEspecificacion);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $tareas[] = $fila;
        }
        mysqli_stmt_close($stmt);
    }
    return $tareas;
}

function actualizarEstadoEspecificacion($link, $idEspecificacion_obsoleta) {
    $query = "UPDATE calidad_especificacion_productos SET estado = 'Especificación obsoleta' WHERE id_especificacion = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "i", $idEspecificacion_obsoleta);

    $exito = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    registrarTrazabilidad(
        $_SESSION['usuario'], 
        $_SERVER['PHP_SELF'], 
        'Inserción de especificación y análisis', 
        '4. versión anterior pasa a obsoleta',  
        $idEspecificacion_obsoleta, 
        $query,  
        [$idEspecificacion_obsoleta], 
        $exito ? 1 : 0, 
        $exito ? null : mysqli_error($link)
    );
    $tareas = obtenerTareasActivas($link, $idEspecificacion_obsoleta);
    foreach ($tareas as $tarea) {
        // update 22052024
        //function finalizarTarea($usuarioEjecutor, $id_relacion, $tabla_relacion, $tipoAccion, $esAutomatico = false)
        finalizarTarea($tarea['usuario_ejecutor'], $idEspecificacion_obsoleta, $tarea['tipo'], 'calidad_especificacion_productos', true);
    }
    if (!$exito) {
        throw new Exception("Error al actualizar el estado de la especificación: " . mysqli_error($link));
    }
}
function procesarFormulario($link) {
    mysqli_begin_transaction($link); // Inicia la transacción

    try {
        $estaEditando = isset($_POST['id_producto']) && !empty($_POST['id_producto']);
        $idProducto = $estaEditando ? limpiarDato($_POST['id_producto']) : 0;
        
        if (!$estaEditando) {
            $resultadoProducto = insertarProducto($link);
            if (!$resultadoProducto['exito']) {
                throw new Exception("Error al insertar producto: " . $resultadoProducto['error']);
            }
            $idProducto = $resultadoProducto['id'];
        }
        if ($estaEditando) {
            // * editando
            //Todo: revisar si esta es la última versión
            $idEspecificacion_editada = intval(limpiarDato($_POST['id_especificacion']));
            actualizarEstadoEspecificacion($link, $idEspecificacion_editada);
        }
        $resultadoEspecificacion = insertarEspecificacionYAnalisis($link, $idProducto);
        if (!$resultadoEspecificacion['exito']) {
            throw new Exception("Error al insertar especificación y análisis: " . $resultadoEspecificacion['error']);
        }
        $idEspecificacion = $resultadoEspecificacion['id'];

        mysqli_commit($link); // Aplica los cambios
        
        registrarTarea(7, $_SESSION['usuario'], $_POST['usuario_revisor'], 'Revisar especificación de producto: '.limpiarDato($_POST['documento']).' - versión:'.limpiarDato($_POST['version']), 1, 'Firma 2', $idEspecificacion, 'calidad_especificacion_productos');
        registrarTarea(14, $_SESSION['usuario'], $_POST['usuario_aprobador'], 'Aprobar especificación de producto: '.limpiarDato($_POST['documento']).' - versión:'.limpiarDato($_POST['version']), 1, 'Firma 3', $idEspecificacion, 'calidad_especificacion_productos');
        return ["exito" => true, "mensaje" => "Especificación y análisis creados con éxito.", "idEspecificacion" => $idEspecificacion];
    } catch (Exception $e) {
        // Aquí registramos el error en la trazabilidad antes de hacer rollback
        registrarTrazabilidad(
            $_SESSION['usuario'],
            $_SERVER['PHP_SELF'],
            'Error en la inserción',
            $e->getMessage(), // Aquí se debe colocar la información más relevante sobre el error
            0, // ID del registro, 0 si no se creó
            $resultadoProducto['query'], // Consulta que falló
            $resultadoProducto['params'], // Parámetros de la consulta
            0, // Indica que hubo un fracaso
            $e->getMessage() // Mensaje de error
        );

        mysqli_rollback($link); // Revierte los cambios
        return ["exito" => false, "mensaje" => $e->getMessage(), "idEspecificacion" => 0];
    }
}

function insertarProducto($link) {
    if ($_POST['formato'] == 'Otro' && !empty($_POST['otroFormato'])) {
        insertarOpcionSiNoExiste($link, 'Formato', $_POST['otroFormato']);
    }
    if ($_POST['Tipo_Producto'] == 'Otro' && !empty($_POST['otroTipo_Producto'])) {
        insertarOpcionSiNoExiste($link, 'Tipo_Producto', $_POST['otroTipo_Producto']);
    }
    $tipoProducto = $_POST['Tipo_Producto'] === 'Otro' ? limpiarDato($_POST['otroTipo_Producto']) : limpiarDato($_POST['Tipo_Producto']);
    //$tipoProducto = limpiarDato($_POST['Tipo_Producto']) ?? limpiarDato($_POST['otroTipo_Producto']);
    $producto = limpiarDato($_POST['producto']);
    $concentracion = limpiarDato($_POST['concentracion']);
    $tipo_concentracion = limpiarDato($_POST['tipo_concentracion']);
    $formato = $_POST['formato'] === 'Otro' ? limpiarDato($_POST['otroFormato']) : limpiarDato($_POST['formato']);
    //$formato = limpiarDato($_POST['formato']) ?? limpiarDato($_POST['otroFormato']);
    $elaboradoPor = limpiarDato($_POST['elaboradoPor']);
    $numeroDocumento = limpiarDato($_POST['documento']);
    $numeroProducto = limpiarDato($_POST['numeroProducto']);
    $paisOrigen = limpiarDato($_POST['paisOrigen']);
    $dealer = limpiarDato($_POST['dealer']);
    $query = "INSERT INTO calidad_productos (nombre_producto, tipo_producto, concentracion, formato, elaborado_por, documento_ingreso, identificador_producto, tipo_concentracion, pais_origen, proveedor) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "ssssssssss", $producto, $tipoProducto, $concentracion, $formato, $elaboradoPor, $numeroDocumento, $numeroProducto, $tipo_concentracion, $paisOrigen, $dealer);

    $exito = mysqli_stmt_execute($stmt);
    $idProducto = $exito ? mysqli_insert_id($link) : 0;
    mysqli_stmt_close($stmt);
    registrarTrazabilidad(
        $_SESSION['usuario'], 
        $_SERVER['PHP_SELF'], 
        'Inserción de producto', 
        '1. calidad_productos', 
        $idProducto, 
        $query, 
        [$producto, $tipoProducto, $concentracion, $formato, $elaboradoPor, $numeroDocumento, $numeroProducto, $tipo_concentracion, $paisOrigen, $dealer], 
        $exito ? 1 : 0, 
        $exito ? null : mysqli_error($link)
    );
    $params = [$producto, $tipoProducto, $concentracion, $formato, $elaboradoPor, $numeroDocumento, $numeroProducto, $tipo_concentracion, $paisOrigen, $dealer];
    $error = $exito ? null : mysqli_error($link);

    return ['exito' => $exito, 'id' => $idProducto, 'query' => $query, 'params' => $params, 'error' => $error];
}

function insertarEspecificacionYAnalisis($link, $idProducto) {
    
    $fechaEdicion = limpiarDato($_POST['fechaEdicion']);
    $version = limpiarDato($_POST['version']);
    $vigencia = limpiarDato($_POST['periodosVigencia']);
    $fechaExpiracion = calcularFechaExpiracion($fechaEdicion, $vigencia);
    $editor = limpiarDato($_POST['user_editor']);
    $revisor = limpiarDato($_POST['usuario_revisor']);
    $aprobador = limpiarDato($_POST['usuario_aprobador']);

    $queryEspecificacion = "INSERT INTO calidad_especificacion_productos (id_producto, documento, fecha_edicion, version, fecha_expiracion, vigencia, creado_por, revisado_por, aprobado_por) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtEspecificacion = mysqli_prepare($link, $queryEspecificacion);
    mysqli_stmt_bind_param($stmtEspecificacion, "issssisss", $idProducto, $numeroDocumento, $fechaEdicion, $version, $fechaExpiracion, $vigencia, $editor, $revisor, $aprobador);

    $exito = mysqli_stmt_execute($stmtEspecificacion);
    $idEspecificacion = $exito ? mysqli_insert_id($link) : 0;
    $_SESSION['buscarEspecificacion']=$idEspecificacion;
    mysqli_stmt_close($stmtEspecificacion);

    registrarTrazabilidad(
        $_SESSION['usuario'], 
        $_SERVER['PHP_SELF'], 
        'Inserción de especificación y análisis', 
        '2. calidad_especificacion_productos', 
        $idEspecificacion, 
        $queryEspecificacion, 
        [$idProducto, $numeroDocumento, $fechaEdicion, $version, $fechaExpiracion, $vigencia, $editor, $revisor, $aprobador], 
        $exito ? 1 : 0, 
        $exito ? null : mysqli_error($link)
    );
    $params = [$idProducto, $_POST['documento'], $fechaEdicion, $version, $fechaExpiracion, $vigencia, $editor, $revisor, $aprobador];
    $error = $exito ? null : mysqli_error($link);

    if ($exito) {
        if (!empty($_POST['analisisFQ'])) {
            insertarAnalisis($link, $idEspecificacion, 'analisis_FQ', $_POST['analisisFQ']);
        }
    
        if (!empty($_POST['analisisMB'])) {
            insertarAnalisis($link, $idEspecificacion, 'analisis_MB', $_POST['analisisMB']);
        }
    }
    

    return ['exito' => $exito, 'id' => $idEspecificacion, 'query' => $queryEspecificacion, 'params' => $params, 'error' => $error];
}



function insertarAnalisis($link, $idEspecificacion, $tipoAnalisis, $datosAnalisis) {
    foreach ($datosAnalisis as $analisis) {
        if ($analisis['descripcion_analisis'] == 'Otro' && !empty($analisis['otrodescripcion_analisis']) && $tipoAnalisis=='analisis_FQ') {
            insertarOpcionSiNoExiste($link, 'AnalisisFQ', $analisis['otrodescripcion_analisis']);
        }
        if ($analisis['descripcion_analisis'] == 'Otro' && !empty($analisis['otrodescripcion_analisis']) && $tipoAnalisis=='analisis_MB') {
            insertarOpcionSiNoExiste($link, 'AnalisisMB', $analisis['otrodescripcion_analisis']);
        }
        if ($analisis['metodologia'] == 'Otro' && !empty($analisis['otrometodologia'])) {
            insertarOpcionSiNoExiste($link, 'metodologia', $analisis['otrometodologia']);
        }
        $descripcion_analisis = $analisis['descripcion_analisis'] === 'Otro' ? limpiarDato($analisis['otrodescripcion_analisis']) : limpiarDato($analisis['descripcion_analisis']);
        $metodologia = $analisis['metodologia'] === 'Otro' ? limpiarDato($analisis['otrometodologia']) : limpiarDato($analisis['metodologia']);
        $criterios_aceptacion = limpiarDato($analisis['criterio']);

        $queryAnalisis = "INSERT INTO calidad_analisis (id_especificacion_producto, tipo_analisis, descripcion_analisis, metodologia, criterios_aceptacion) VALUES (?, ?, ?, ?, ?)";
        $stmtAnalisis = mysqli_prepare($link, $queryAnalisis);
        mysqli_stmt_bind_param($stmtAnalisis, "issss", $idEspecificacion, $tipoAnalisis, $descripcion_analisis, $metodologia, $criterios_aceptacion);

        $exito = mysqli_stmt_execute($stmtAnalisis);
        $idAnalisis = $exito ? mysqli_insert_id($link) : 0;
        mysqli_stmt_close($stmtAnalisis);

        registrarTrazabilidad(
            $_SESSION['usuario'], 
            $_SERVER['PHP_SELF'], 
            'Inserción de análisis', 
            '3. calidad_analisis', 
            $idAnalisis, 
            $queryAnalisis, 
            [$idEspecificacion, $tipoAnalisis, $descripcion_analisis, $metodologia, $criterios_aceptacion], 
            $exito ? 1 : 0, 
            $exito ? null : mysqli_error($link)
        );
        $params = [$idEspecificacion, $tipoAnalisis, $descripcion_analisis, $metodologia, $criterios_aceptacion];
        $error = $exito ? null : mysqli_error($link);
    }
}

function calcularFechaExpiracion($fechaInicio, $añosVigencia) {
    $fecha = new DateTime($fechaInicio);
    $fecha->modify("+$añosVigencia years");
    return $fecha->format('Y-m-d');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    registrarTrazabilidad($_SESSION['usuario'], $_SERVER['PHP_SELF'], 'INTENTO DE CARGA', 'ESPECIFICACIÓN',  1, '', $_POST, '', '');
    $respuesta = procesarFormulario($link);
    echo json_encode($respuesta);
} else {
    echo json_encode(["exito" => false, "mensaje" => "Método inválido", "idEspecificacion" => 0]);
}

?>
