<?php
//archivo: pages\backend\laboratorio\LABORATORIO_preparacion_solicitudBE.php

session_start();
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}
require_once "/home/customw2/conexiones/config_reccius.php";
require_once "../otros/laboratorio.php";
require_once "../cloud/R2_manager.php";
header('Content-Type: application/json');
$mensaje='GO ';
global $numero_solicitud;
function limpiarDato($dato)
{
    $datoLimpio = trim($dato);
    return htmlspecialchars(stripslashes($datoLimpio));
}

// Funciones para interactuar con la base de datos
function insertarRegistro($link, $datos)
{
    global $id_analisis_externo, $mensaje; // Hacer la variable global para que se pueda acceder fuera de esta función
    error_log("insertarRegistro - Datos recibidos: " . print_r($datos, true));
    error_log("insertarRegistro - id_producto: " . $datos['id_producto']);
    error_log("insertarRegistro - id_especificacion: " . $datos['id_especificacion']);
    //Todo: tomar las versiones anteriores y deprecarlas si les falta firmas
    $year = date("Y");
    $month = date("m");
    $aux_anomes = $year . $month;
    $fecha_ymd=date("Y-m-d");
    $query= "INSERT INTO calidad_analisis_externo (
        version,
        id_especificacion,
        id_producto,
        estado,
        numero_registro,
        numero_solicitud,
        fecha_registro,
        solicitado_por,
        lote,
        tamano_lote,
        fecha_elaboracion,
        fecha_vencimiento,
        tamano_muestra,
        tamano_contramuestra,
        registro_isp,
        condicion_almacenamiento,
        muestreado_por,
        numero_pos,
        tipo_analisis,
        am_verificado_por,
        am_ejecutado_por,
        aux_autoincremental, 
        aux_anomes, 
        aux_tipo,
        elaborado_por,
        pais_origen,
        proveedor,
        fecha_firma_1,
        observaciones,
        liberado_por
            ) 
        SELECT 
            ?, -- version
            c.id_especificacion, 
            c.id_producto, 
            'Pendiente Acta de Muestreo', 
            ?, -- numero_registro
            ?, -- numero_solicitud
            ?, -- fecha_registro
            ?, -- solicitado_por
            ?, -- lote
            ?, -- tamano_lote
            ?, -- fecha_elaboracion
            ?, -- fecha_vencimiento
            ?, -- tamano_muestra
            ?, -- tamano_contramuestra
            ?, -- registro_isp
            ?, -- condicion_almacenamiento
            ?, -- muestreado_por
            ?, -- numero_pos
            ?, -- tipo_analisis
            ?, -- am_verificado_por
            ?, -- am_ejecutado_por
            COALESCE(MAX(ae.aux_autoincremental) + 1, 1), -- aux_autoincremental
            ?, -- aux_anomes
            b.tipo_producto, -- aux_tipo
            ?,
            ?,
            ?,
            '".$fecha_ymd."',
            ?,
            ?  -- liberado_por

        FROM 
            calidad_especificacion_productos AS c
            LEFT JOIN calidad_productos AS b ON c.id_producto = b.id
            LEFT JOIN calidad_analisis_externo AS ae ON ae.aux_anomes = ? AND ae.aux_tipo = b.tipo_producto
        WHERE 
            c.id_especificacion = ?
        LIMIT 1;
        ";

    $stmt = mysqli_prepare($link, $query);
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . mysqli_error($link));
    }

    mysqli_stmt_bind_param(
        $stmt,
        'issssssssssssssssssssssssi',
        $datos['version'],
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
        $datos['am_verificado_por'],
        $datos['am_ejecutado_por'],
        $aux_anomes,        // Se utiliza en la inserción como aux_anomes
        $datos['elaboradoPor'],
        $datos['paisOrigen'],
        $datos['dealer'],
        $datos['observaciones_originales'],
        $_SESSION['usuario'],
        $aux_anomes,        // Se utiliza en la subconsulta WHERE
        $datos['id_especificacion']
    );
    $exito = mysqli_stmt_execute($stmt);
    $id = $exito ? mysqli_insert_id($link) : 0;
    $id_analisis_externo = $id; // Asignar el ID a la variable global
    mysqli_stmt_close($stmt);

    registrarTrazabilidad(
        $_SESSION['usuario'],
        $_SERVER['PHP_SELF'],
        'Creación registro análisis externo',
        'calidad_analisis_externo',
        $id,
        $query,
        [
            $datos['version'],
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
            $datos['am_verificado_por'],
            $datos['am_ejecutado_por'],
            $aux_anomes,        // Se utiliza en la inserción como aux_anomes
            $datos['elaboradoPor'],
            $datos['paisOrigen'],
            $datos['dealer'],
            $datos['observaciones_originales'],
            $_SESSION['usuario'],
            $aux_anomes,        // Se utiliza en la subconsulta WHERE
            $datos['id_especificacion']
        ],
        $exito ? 1 : 0,
        $exito ? null : mysqli_error($link)
    );
    insertarRegistroAnalisis($link, $id);
    unset($_SESSION['buscar_por_ID']);
    $_SESSION['buscar_por_ID'] = $id;
    $mensaje.= " 1. se intentará guardar SESSION['buscar_por_ID']=".$id;

    if (!$exito) {
        throw new Exception("Error al ejecutar la inserción: " . mysqli_error($link));
    }
}
function insertarRegistroAnalisis($link, $datos){
    global $id_analisis_externo, $mensaje; // Hacer la variable global para que se pueda acceder fuera de esta función
    //Todo: tomar las versiones anteriores y deprecarlas si les falta firmas

    $query= "INSERT INTO calidad_resultados_analisis (
        id_analisisExterno,
        id_analisis,
        id_especificacion_producto,
        criterios_aceptacion,
        descripcion_analisis,
        metodologia,
        tipo_analisis
            ) 
    select aex.id,
        an.id_analisis,
        an.id_especificacion_producto,
        an.criterios_aceptacion,
        an.descripcion_analisis,
        an.metodologia,
        an.tipo_analisis
        from calidad_analisis_externo as aex 
        left join calidad_analisis as an on aex.id_especificacion=an.id_especificacion_producto
        where aex.id=?;";

    $stmt = mysqli_prepare($link, $query);
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . mysqli_error($link));
    }

    mysqli_stmt_bind_param($stmt,'i',$datos);
    $exito = mysqli_stmt_execute($stmt);
    $id = $exito ? mysqli_insert_id($link) : 0;
     mysqli_stmt_close($stmt);

    registrarTrazabilidad(
        $_SESSION['usuario'],
        $_SERVER['PHP_SELF'],
        'Preparación análisis para Solicitud análisis externo',
        'calidad_resultados_analisis',
        $id,
        $query,
        [$datos],
        $exito ? 1 : 0,
        $exito ? null : mysqli_error($link)
    );
    if (!$exito) {
        throw new Exception("2 Error al ejecutar la inserción: " . mysqli_error($link));
    }
}

function enviar_aCuarentena($link, $id_especificacion, $id_producto, $id_analisis_externo, $lote, $tamano_lote, $fechaActual, $fecha_elaboracion, $fecha_vencimiento){
        // Nueva inserción en calidad_productos_analizados
        $query_productos_analizados = "INSERT INTO `calidad_productos_analizados` 
            (id_especificacion, id_producto, id_analisisExterno, estado, lote, tamano_lote, fecha_in_cuarentena, fecha_elaboracion, fecha_vencimiento) 
            VALUES (?, ?, ?, 'En cuarentena', ?, ?, ?, ?, ?)";

        $stmt_productos_analizados = mysqli_prepare($link, $query_productos_analizados);
        if (!$stmt_productos_analizados) {
            throw new Exception("Error en la preparación de la consulta de productos analizados: " . mysqli_error($link));
        }
        $fechaActual = date('Y-m-d');
        mysqli_stmt_bind_param(
            $stmt_productos_analizados,
            'iiisssss',
            $id_especificacion,
            $id_producto,
            $id_analisis_externo,
            $lote,
            $tamano_lote,
            $fechaActual,
            $fecha_elaboracion,
            $fecha_vencimiento
        );
        $exito_2 = mysqli_stmt_execute($stmt_productos_analizados);
        $id_cuarentena = $exito_2 ? mysqli_insert_id($link) : 0;
        registrarTrazabilidad(
            $_SESSION['usuario'],
            $_SERVER['PHP_SELF'],
            'Envío de lote a cuarentena',
            'calidad_productos_analizados',
            $id_cuarentena,
            $query_productos_analizados,
            [$id_especificacion, $id_producto, $id_analisis_externo, 'En cuarentena', $lote, $tamano_lote, $fechaActual, $fecha_elaboracion, $fecha_vencimiento],
            $exito_2 ? 1 : 0,
            $exito_2 ? null : mysqli_error($link)
        );
        mysqli_stmt_close($stmt_productos_analizados);
        $query_update = "UPDATE calidad_analisis_externo SET id_cuarentena = '$id_cuarentena' WHERE id = '$id_analisis_externo'";

        if (!mysqli_query($link, $query_update)) {
            throw new Exception("Error en la actualización de calidad_analisis_externo: " . mysqli_error($link));
        }
}
function agregarDatosPostFirma($link, $datos,$archivo)
{
    global $id_analisis_externo, $mensaje; // Hacer la variable global para que se pueda acceder fuera de esta función

    // Consultar el numero_solicitud asociado al id en la tabla calidad_analisis_externo
    $query_numero_solicitud = "SELECT numero_solicitud FROM calidad_analisis_externo WHERE id = ?";
    $stmt_numero_solicitud = mysqli_prepare($link, $query_numero_solicitud);
    if (!$stmt_numero_solicitud) {
        throw new Exception("Error en la preparación de la consulta: " . mysqli_error($link));
    }
    mysqli_stmt_bind_param($stmt_numero_solicitud, 'i', $datos['id']);
    mysqli_stmt_execute($stmt_numero_solicitud);
    mysqli_stmt_bind_result($stmt_numero_solicitud, $numero_solicitud);
    mysqli_stmt_fetch($stmt_numero_solicitud);
    mysqli_stmt_close($stmt_numero_solicitud);

    $laboratorio = new Laboratorio();

    if ($datos['otro_laboratorio'] !== '') {
        $laboratorio->findOrCreateByName($datos['otro_laboratorio']);
        $datos['laboratorio'] = $datos['otro_laboratorio'];
    }

    if (isset($archivo) && $archivo['error'] === UPLOAD_ERR_OK) {
        $mimeType = mime_content_type($archivo['tmp_name']);

        if ($mimeType === 'application/pdf') {
            $fileBinary = file_get_contents($archivo['tmp_name']);
            $timestamp = time();
            $newFileName = $id_analisis_externo . '_' . $_SESSION['usuario'] . '_' . $timestamp . '.pdf';

            // Subir el archivo a S3
            $params = [
                'fileBinary' => $fileBinary,
                'folder' => 'url_documento_adicional',
                'fileName' => $newFileName
            ];

            $uploadStatus = setFile($params);
            $uploadResult = json_decode($uploadStatus, true);

            if (isset($uploadResult['success']) && $uploadResult['success'] !== false) {
                $datos['url_documento_adicional'] = $uploadResult['success']['ObjectURL'];
            } else {
                echo json_encode(["exito" => false, "mensaje" => "Error al subir el documento adicional"]);
                exit;
            }
        } else {
            echo json_encode(["exito" => false, "mensaje" => "El documento adicional debe ser un archivo PDF"]);
            exit;
        }
    }

    $camposAActualizar = [
        'analisis_segun',
        'estandar_segun',
        'fecha_cotizacion',
        'fecha_entrega_estimada',
        'fecha_solicitud',
        'hds_otro',
        'laboratorio',
        'numero_documento',
        'observaciones',
        'solicitado_por',
        'liberado_por',
        'revisado_por',
        'enviado_lab_por', // Agregar el nuevo campo
    ];

    //* Añado revisado por para luego hacer que esa persona firme en tareas

    $partesConsulta = [];
    $valoresParaVincular = [];
    $tipos = '';

    foreach ($camposAActualizar as $campo) {
        if (isset($datos[$campo]) && $datos[$campo] !== '') {
            $partesConsulta[] = "$campo = ?";
            $valoresParaVincular[] = $datos[$campo] ?? null;
            $tipos .= campoTipo($campo);
        }
    } // * esto me ayuda a actualizar solo lo que le envio

    // Asegurarte de que haya algo que actualizar
    if (empty($partesConsulta)) {
        throw new Exception("No hay datos para actualizar.");
    }
    //
    if (isset($datos['url_documento_adicional'])) {
        $partesConsulta[] = "url_documento_adicional = ?";
        $valoresParaVincular[] = $datos['url_documento_adicional'];
        $tipos .= 's';
    }
    //nuevo estado 
    $partesConsulta[] = "estado = ?";
    $valoresParaVincular[] = "Pendiente envío a Laboratorio";
    $tipos .= campoTipo("estado");

    // Añadir el ID al final para la cláusula WHERE
    $valoresParaVincular[] = $datos['id'];
    $id_analisis_externo = $datos['id']; // Asignar el ID a la variable global
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
    unset($_SESSION['buscar_por_ID']);
    $_SESSION['buscar_por_ID'] = $datos['id'];
    $mensaje.= " 2. se intentará guardar SESSION['buscar_por_ID']=".$datos['id'];


    // registrar tarea
    finalizarTarea($_SESSION['usuario'], $id_analisis_externo, 'calidad_analisis_externo', 'Firma 1');
    registrarTarea(7, $_SESSION['usuario'], $datos['revisado_por'], 'Enviar Análisis externo a Laboratorio: ' . $numero_solicitud, 2, 'Enviar a Laboratorio', $datos['id'], 'calidad_analisis_externo');
    //["2024-08-06", "fabarca212", "", "Enviar Análisis externo a Laboratorio: ", 2, "Enviar a Laboratorio", "2024-07-30 21:05:15", "90", "calidad_analisis_externo"]
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
        'fecha_firma1' => 's',
        

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
        'am_ejecutado_por' => 's',
        'numero_pos' => 's',
        'codigo_mastersoft' => 's',
        'tamano_lote' => 's',
        'tamano_muestra' => 's',
        'tamano_contramuestra' => 's',
        'observaciones' => 's',
        'solicitado_por' => 's',
        'liberado_por' => 's',
        'revisado_por' => 's',
        'enviado_lab_por' => 's' // Agregar el nuevo campo
    ];

    return $tiposCampo[$campo] ?? 's';
}

// Procesar la solicitud
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //registrarTrazabilidad($_SESSION['usuario'], $_SERVER['PHP_SELF'], 'INTENTO DE CARGA', 'LABORATORIO',  1, '', $_POST, '', '');
    
    // Verificar si el anexo extra existe
    $archivo = isset($_FILES['url_documento_adicional']) ? $_FILES['url_documento_adicional'] : null;

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
    $paisOrigen = limpiarDato($_POST['paisOrigen']);
    $dealer = limpiarDato($_POST['dealer']);
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
    $otro_laboratorio = limpiarDato($_POST['otro_laboratorio']);
    $fecha_solicitud = limpiarDato($_POST['fecha_solicitud']);
    $analisis_segun = limpiarDato($_POST['analisis_segun']);
    $fecha_cotizacion = limpiarDato($_POST['fecha_cotizacion']);
    $estandar_segun = limpiarDato($_POST['estandar_segun']);
    $hds_otro = limpiarDato($_POST['hds_otro']);
    $fecha_entrega_estimada = limpiarDato($_POST['fecha_entrega_estimada']);
    $numero_documento = limpiarDato($_POST['numero_documento']);
    $observaciones = limpiarDato($_POST['observaciones']);
    $observaciones_originales = limpiarDato($_POST['form_observaciones']);
    $solicitadoPor = limpiarDato($_POST['solicitado_por']);
    $liberadoPor = limpiarDato($_POST['liberado_por']);
    $revisadoPor = limpiarDato($_POST['revisado_por']);
    $enviado_lab_por = limpiarDato($_POST['revisado_por']);
    $am_verificado_por = limpiarDato($_POST['am_verificado_por']);
    $am_ejecutado_por = limpiarDato($_POST['ejecutado_por']);
    $id_producto = isset($_POST['id_producto']) ? limpiarDato($_POST['id_producto']) : null;
    $id_especificacion = isset($_POST['id_especificacion']) ? limpiarDato($_POST['id_especificacion']) : null;

    error_log("POST - Datos recibidos raw id_producto: " . print_r($_POST['id_producto'], true));
    error_log("POST - Datos recibidos raw id_especificacion: " . print_r($_POST['id_especificacion'], true));
    error_log("POST - id_producto limpio: " . $id_producto);
    error_log("POST - id_especificacion limpio: " . $id_especificacion);

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
            'paisOrigen' => $paisOrigen,
            'dealer' => $dealer,
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
            'otro_laboratorio' => $otro_laboratorio,
            'fecha_solicitud' => $fecha_solicitud,
            'analisis_segun' => $analisis_segun,
            'fecha_cotizacion' => $fecha_cotizacion,
            'estandar_segun' => $estandar_segun,
            'hds_otro' => $hds_otro,
            'fecha_entrega_estimada' => $fecha_entrega_estimada,
            'numero_documento' => $numero_documento,
            'observaciones' => $observaciones,
            'observaciones_originales' => $observaciones_originales,
            'solicitado_por' => $solicitadoPor,
            'liberado_por' => $liberadoPor,
            'revisado_por' => $revisadoPor,
            'enviado_lab_por' => $enviado_lab_por,
            'am_verificado_por' => $am_verificado_por,
            'am_ejecutado_por' =>$am_ejecutado_por,

        ];

        error_log("POST - datosLimpios array: " . print_r($datosLimpios, true));

        if ($estaEditando) {
            $datosLimpios['id'] = limpiarDato($_POST['id']);
            $datosLimpios['numero_solicitud'] = $numero_solicitud;

            agregarDatosPostFirma($link, $datosLimpios,$archivo);
        } else {
            error_log("Iniciando inserción de nuevo registro con id_producto: " . $id_producto);
            insertarRegistro($link, $datosLimpios);
            registrarTarea(7, $_SESSION['usuario'], $am_ejecutado_por, 'Generar Acta Muestreo para análisis externo:' . $numero_solicitud , 2, 'Generar Acta Muestreo', $id_analisis_externo, 'calidad_analisis_externo');
            enviar_aCuarentena($link, $id_especificacion, $id_producto, $id_analisis_externo, $lote, $tamano_lote, $fechaActual, $fecha_elaboracion, $fecha_vencimiento);
        }
        mysqli_commit($link); // Aplicar cambios
        echo json_encode(["exito" => true, "mensaje" => $mensaje]);
        exit;

        // tarea anterior se cierra con: finalizarTarea($_SESSION['usuario'], $id_analisis_externo, 'calidad_analisis_externo', 'Generar Acta Muestreo');
    } catch (Exception $e) {
        mysqli_rollback($link); // Revertir cambios en caso de error
        error_log("Error en el procesamiento: " . $e->getMessage());
        echo json_encode(["exito" => false, "mensaje" => "Error en la operación: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["exito" => false, "mensaje" => "Método inválido"]);
}
?>
