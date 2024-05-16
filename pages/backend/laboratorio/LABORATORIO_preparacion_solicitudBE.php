<?php

session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

function limpiarDato($dato)
{
    $datoLimpio = trim($dato);
    return htmlspecialchars(stripslashes($datoLimpio));
}

// Funciones para interactuar con la base de datos
function insertarRegistro($link, $datos)
{
    //Todo: tomar las versiones anteriores y deprecarlas si les falta firmas
    $query = "INSERT INTO calidad_analisis_externo (
        version
        ,id_especificacion
        ,id_producto
        ,estado
        ,numero_registro
        ,numero_solicitud
        ,fecha_registro
        ,solicitado_por
        ,lote
        ,tamano_lote
        ,fecha_elaboracion
        ,fecha_vencimiento
        ,tamano_muestra
        ,tamano_contramuestra
        ,registro_isp
        ,condicion_almacenamiento
        ,muestreado_por
        ,numero_pos
        ,tipo_analisis
        ,am_verificado_por) 
    VALUES (?, ?, ?, 'Pendiente Acta de Muestreo', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

    $stmt = mysqli_prepare($link, $query);
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . mysqli_error($link));
    }

    mysqli_stmt_bind_param(
        $stmt,
        'iiissssssssssssssss',
        $datos['version'],
        $datos['id_especificacion'],
        $datos['id_producto'],
        $datos['numero_registro'],
        $datos['numero_solicitud'],
        $datos['fecha_registro'],
        $_SESSION['usuario'],
        $datos['lote'],
        $datos['tamano_lote'],
        $datos['fecha_elaboracion'],
        $datos['fecha_vencimiento'],
        $datos['tamano_muestra'],
        $datos['tamano_contramuestra'],
        $datos['registro_isp'],
        $datos['condicion_almacenamiento'],
        $datos['muestreado_por'],
        $datos['numero_pos'],
        $datos['tipo_analisis'],
        $datos['am_verificado_por']
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
        [
            $datos['version'], $datos['id_especificacion'], $datos['id_producto'],
            $datos['registro'], $datos['numero_solicitud'],
            $datos['fecha_registro'], $_SESSION['usuario'],  $datos['usuario_revisor'],
            $datos['lote'], $datos['tamano_lote'], $datos['fecha_elaboracion'],
            $datos['fecha_vencimiento'], $datos['tamano_muestra'], $datos['tamano_contramuestra'],
            $datos['registro_isp'], $datos['condicion_almacenamiento'], $datos['muestreado_por'],
            $datos['numero_pos'], $datos['tipo_analisis'],            $datos['am_verificado_por']
        ],
        $exito ? 1 : 0,
        $exito ? null : mysqli_error($link)
    );

    $_SESSION['buscar_por_ID'] = $id;

    if (!$exito) {
        throw new Exception("Error al ejecutar la inserción: " . mysqli_error($link));
    }
}

function agregarDatosPostFirma($link, $datos)
{
    $camposAActualizar = [
        'analisis_segun',
        'estandar_segun', // estandar_provisto_por
        'fecha_cotizacion',
        'fecha_entrega_estimada',
        'fecha_solicitud',
        'hds_otro',
        'laboratorio',
        'numero_documento',
        'observaciones',
        'solicitado_por',
        'revisado_por',
    ];

    //* Añado revisado por para luego hacer que esa persona firme en tareas

    $partesConsulta = [];
    $valoresParaVincular = [];
    $tipos = '';

    foreach ($camposAActualizar as $campo) {
        if (isset($datos[$campo]) && $datos[$campo] !== '') {
            $partesConsulta[] = "$campo = ?";
            $valoresParaVincular[] = $datos[$campo];
            $tipos .= campoTipo($campo);
        }
    } // * esto me ayuda a actualizar solo lo que le envio


    // Asegurarte de que haya algo que actualizar
    if (empty($partesConsulta)) {
        throw new Exception("No hay datos para actualizar.");
    }

    //nuevo estado 
    $partesConsulta[] = "estado = ?";
    $valoresParaVincular[] = "En proceso de firmas";
    $tipos .= campoTipo("estado");

    // Añadir el ID al final para la cláusula WHERE
    $valoresParaVincular[] = $datos['id'];
    $tipos .= 'i';

    $consultaSQL = "UPDATE calidad_analisis_externo SET " . join(", ", $partesConsulta) . " WHERE id = ?";
    $stmt = mysqli_prepare($link, $consultaSQL);
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . mysqli_error($link));
    }

    mysqli_stmt_bind_param($stmt, $tipos, ...$valoresParaVincular);

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error al ejecutar la actualización: " . mysqli_stmt_error($stmt));
    }
    
    // registrar tarea
    registrarTarea(7, 
            $_SESSION['usuario'], 
            $datos['usuario_revisor'], 
            'Revisar Analisis Externo: '.$datos['numero_registro']
            //Añadir documento en vez de id 
            ,
            1, 
            'Firma analisis externo: Revisor', 
            $datos['id'].';'.$datos['numero_registro'], 
            'calidad_analisis_externo'
        );
}

function campoTipo($campo)
{
    // Define los tipos de datos según el campo para la función mysqli_stmt_bind_param
    $tiposCampo = [
        // Campos de tipo INTEGER
        'id' => 'i',
        'version' => 'i',
        'id_especificacion' => 'i',
        'id_producto' => 'i',

        // Campos de tipo DATE
        'fecha_registro' => 's',  // Las fechas se manejan como strings en MySQL
        'fecha_solicitud' => 's',
        'fecha_cotizacion' => 's',
        'fecha_entrega' => 's',
        'fecha_entrega_estimada' => 's',
        'fecha_elaboracion' => 's',
        'fecha_vencimiento' => 's',
        'fecha_firma_revisor' => 's',

        // Campos de tipo VARCHAR o cualquier tipo de texto
        'estado' => 's',
        'numero_registro' => 's',
        'numero_solicitud' => 's',
        'laboratorio' => 's',
        'analisis_segun' => 's',
        'numero_documento' => 's',
        'estandar_segun' => 's',
        'estandar_otro' => 's',
        'hds_adjunto' => 's',
        'hds_otro' => 's',
        'lote' => 's',
        'registro_isp' => 's',
        'condicion_almacenamiento' => 's',
        'tipo_analisis' => 's',
        'muestreado_por' => 's',
        'am_verificado_por' => 's',
        'numero_pos' => 's',
        'codigo_mastersoft' => 's',
        'tamano_lote' => 's',
        'tamano_muestra' => 's',
        'tamano_contramuestra' => 's',
        'observaciones' => 's',
        'solicitado_por' => 's',
        'revisado_por' => 's',
    ];

    return $tiposCampo[$campo] ?? 's';
}

// Procesar la solicitud
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    registrarTrazabilidad($_SESSION['usuario'], $_SERVER['PHP_SELF'], 'INTENTO DE CARGA', 'LABORATORIO',  1, '', $_POST, '', '');
    // Limpiar y validar datos recibidos del formulario

    $numero_registro = limpiarDato($_POST['numero_registro']);
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
    $fecha_vencimiento = limpiarDato($_POST['fecha_vencimiento']);
    $tipo_analisis = limpiarDato($_POST['tipo_analisis']);
    $condicion_almacenamiento = limpiarDato($_POST['condicion_almacenamiento']);
    $tamano_muestra = limpiarDato($_POST['tamano_muestra']);
    $tamano_contramuestra = limpiarDato($_POST['tamano_contramuestra']);
    $registro_isp = limpiarDato($_POST['registro_isp']);
    $muestreado_por = limpiarDato($_POST['muestreado_por']);
    $numero_pos = limpiarDato($_POST['numero_pos']);
    $numero_especificacion = limpiarDato($_POST['numero_especificacion']);
    $version_especificacion = limpiarDato($_POST['version_especificacion']);
    $usuario_revisor = limpiarDato($_POST['usuario_revisor']);
    // datos extra
    $laboratorio = limpiarDato($_POST['laboratorio']);
    $fecha_solicitud = limpiarDato($_POST['fecha_solicitud']);
    $analisis_segun = limpiarDato($_POST['analisis_segun']);
    $fecha_cotizacion = limpiarDato($_POST['fecha_cotizacion']);
    $estandar_segun = limpiarDato($_POST['estandar_segun']);
    $hds_otro = limpiarDato($_POST['hds_otro']);
    $fecha_entrega_estimada = limpiarDato($_POST['fecha_entrega_estimada']);
    $numero_documento = limpiarDato($_POST['numero_documento']);
    $observaciones = limpiarDato($_POST['observaciones']);
    $solicitadoPor = limpiarDato($_POST['solicitado_por']);
    $revisadoPor = limpiarDato($_POST['revisado_por']);
    $am_verificado_por = limpiarDato($_POST['am_verificado_por']);

    $id_producto = isset($_POST['id_producto']) ? limpiarDato($_POST['id_producto']) : null;
    $id_especificacion = isset($_POST['id_especificacion']) ? limpiarDato($_POST['id_especificacion']) : null;

    $id_especificacion = isset($_POST['id_especificacion']) ? limpiarDato($_POST['id_especificacion']) : null;


    // Determinar si se está insertando un nuevo registro o actualizando uno existente
    $estaEditando = isset($_POST['id']) && !empty($_POST['id']);

    // Iniciar transacción
    mysqli_begin_transaction($link);

    try {
        // Crear un array con los datos limpios
        $datosLimpios = [
            'codigo_producto' => $codigo_producto,
            'concentracion' => $concentracion,
            'condicion_almacenamiento' => $condicion_almacenamiento,
            'elaboradoPor' => $elaboradoPor,
            'fecha_elaboracion' => $fecha_elaboracion,
            'fecha_registro' => $fecha_registro,
            'fecha_vencimiento' => $fecha_vencimiento,
            'formato' => $formato,
            'id_especificacion' => $id_especificacion,
            'id_producto' => $id_producto,
            'lote' => $lote,
            'muestreado_por' => $muestreado_por,
            'numero_especificacion' => $numero_especificacion,
            'numero_pos' => $numero_pos,
            'numero_registro' => $numero_registro,
            'numero_solicitud' => $numero_solicitud,
            'producto' => $producto,
            'registro_isp' => $registro_isp,
            'tamano_contramuestra' => $tamano_contramuestra,
            'tamano_lote' => $tamano_lote,
            'tamano_muestra' => $tamano_muestra,
            'tipo_analisis' => $tipo_analisis,
            'tipo_producto' => $tipo_producto,
            'usuario_revisor' => $usuario_revisor,
            'version_especificacion' => $version_especificacion,
            'version' => $version,

            'laboratorio' => $laboratorio,
            'fecha_solicitud' => $fecha_solicitud,
            'analisis_segun' => $analisis_segun,
            'fecha_cotizacion' => $fecha_cotizacion,
            'estandar_segun' => $estandar_segun,
            'hds_otro' => $hds_otro,
            'fecha_entrega_estimada' => $fecha_entrega_estimada,
            'numero_documento' => $numero_documento,
            'observaciones' => $observaciones,
            'solicitado_por' => $solicitadoPor,
            'revisado_por' => $revisadoPor

        ];

        if ($estaEditando) {
            $datosLimpios['id'] = limpiarDato($_POST['id']);
            agregarDatosPostFirma($link, $datosLimpios);
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
