<?php

session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

function limpiarDato($dato) {
    $datoLimpio = trim($dato);
    if (empty($datoLimpio)) {
        return null;
    }
    return htmlspecialchars(stripslashes($datoLimpio));
}


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
            $idEspecificacion = limpiarDato($_POST['id_especificacion']);
            actualizarEstadoEspecificacion($link, $idEspecificacion);
        }
        $resultadoEspecificacion = insertarEspecificacionYAnalisis($link, $idProducto);
        if (!$resultadoEspecificacion['exito']) {
            throw new Exception("Error al insertar especificación y análisis: " . $resultadoEspecificacion['error']);
        }
        $idEspecificacion = $resultadoEspecificacion['id'];

        mysqli_commit($link); // Aplica los cambios
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
    $tipoProducto = limpiarDato($_POST['Tipo_Producto']) ?? limpiarDato($_POST['otroTipo_Producto']);
    $producto = limpiarDato($_POST['producto']);
    $concentracion = limpiarDato($_POST['concentracion']);
    $tipo_concentracion = limpiarDato($_POST['tipo_concentracion']);
    $formato = limpiarDato($_POST['formato']) ?? limpiarDato($_POST['otroFormato']);
    $elaboradoPor = limpiarDato($_POST['elaboradoPor']);
    $numeroDocumento = limpiarDato($_POST['documento']);
    $numeroProducto = limpiarDato($_POST['numeroProducto']);
    $paisOrigen = limpiarDato($_POST['paisOrigen']);

    $query = "INSERT INTO calidad_productos (nombre_producto, tipo_producto, concentracion, formato, elaborado_por, documento_ingreso, identificador_producto, tipo_concentracion, pais_origen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "sssssssss", $producto, $tipoProducto, $concentracion, $formato, $elaboradoPor, $numeroDocumento, $numeroProducto, $tipo_concentracion, $paisOrigen);

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
        [$producto, $tipoProducto, $concentracion, $formato, $elaboradoPor, $numeroDocumento, $numeroProducto, $tipo_concentracion, $paisOrigen], 
        $exito ? 1 : 0, 
        $exito ? null : mysqli_error($link)
    );
    $params = [$producto, $tipoProducto, $concentracion, $formato, $elaboradoPor, $numeroDocumento, $numeroProducto, $tipo_concentracion, $paisOrigen];
    $error = $exito ? null : mysqli_error($link);

    return ['exito' => $exito, 'id' => $idProducto, 'query' => $query, 'params' => $params, 'error' => $error];
}

function insertarEspecificacionYAnalisis($link, $idProducto) {
    $fechaEdicion = limpiarDato($_POST['fechaEdicion']);
    $version = limpiarDato($_POST['version']);
    $vigencia = limpiarDato($_POST['periodosVigencia']);
    $fechaExpiracion = calcularFechaExpiracion($fechaEdicion, $vigencia);
    $editor = limpiarDato($_POST['usuario_editor']);
    $revisor = limpiarDato($_POST['usuario_revisor']);
    $aprobador = limpiarDato($_POST['usuario_aprobador']);

    $queryEspecificacion = "INSERT INTO calidad_especificacion_productos (id_producto, documento, fecha_edicion, version, fecha_expiracion, vigencia, creado_por, revisado_por, aprobado_por) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtEspecificacion = mysqli_prepare($link, $queryEspecificacion);
    mysqli_stmt_bind_param($stmtEspecificacion, "issssisss", $idProducto, $_POST['documento'], $fechaEdicion, $version, $fechaExpiracion, $vigencia, $editor, $revisor, $aprobador);

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
        insertarAnalisis($link, $idEspecificacion, 'analisis_FQ', $_POST['analisisFQ']);
        insertarAnalisis($link, $idEspecificacion, 'analisis_MB', $_POST['analisisMB']);
    }

    return ['exito' => $exito, 'id' => $idEspecificacion, 'query' => $queryEspecificacion, 'params' => $params, 'error' => $error];
}



function insertarAnalisis($link, $idEspecificacion, $tipoAnalisis, $datosAnalisis) {
    foreach ($datosAnalisis as $analisis) {
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
function actualizarEstadoEspecificacion($link, $idEspecificacion) {
    $nuevoEstado = 'Especificación obsoleta';
    $query = "UPDATE calidad_especificacion_productos SET estado = ? WHERE id = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "si", $nuevoEstado, $idEspecificacion);

    $exito = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    registrarTrazabilidad(
        $_SESSION['usuario'], 
        $_SERVER['PHP_SELF'], 
        'Inserción de especificación y análisis', 
        '4. versión anterior pasa a obsoleta', 
        $idEspecificacion, 
        $query, 
        [$nuevoEstado, $idEspecificacion], 
        $exito ? 1 : 0, 
        $exito ? null : mysqli_error($link)
    );
    if (!$exito) {
        throw new Exception("Error al actualizar el estado de la especificación: " . mysqli_error($link));
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    registrarTrazabilidad($_SESSION['usuario'], $_SERVER['PHP_SELF'], 'INTENTO DE CARGA', 'TEST',  1, '', $_POST, '', '');
    $respuesta = procesarFormulario($link);
    echo json_encode($respuesta);
} else {
    echo json_encode(["exito" => false, "mensaje" => "Método inválido", "idEspecificacion" => 0]);
}

?>
