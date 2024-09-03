<?php
// archivo: pages\backend\acta_liberacion\acta_liberacion_guardayfirma.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
include "../email/envia_correoBE.php";

// Check the connection
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

function informativo_liberacion($fecha_liberacion, $estado, $id_analisis_externo) {
    global $link;

    // Ejecutar la query para obtener los detalles del producto
    $query_producto = "SELECT b.nombre_producto, b.concentracion, b.tipo_producto, a.lote 
                       FROM calidad_analisis_externo AS a 
                       LEFT JOIN calidad_productos AS b ON a.id_producto = b.id 
                       WHERE a.id = ?";
    $stmt_producto = $link->prepare($query_producto);
    $stmt_producto->bind_param("i", $id_analisis_externo);
    $stmt_producto->execute();
    $result_producto = $stmt_producto->get_result();
    $producto_data = $result_producto->fetch_assoc();
    $stmt_producto->close();

    if ($producto_data) {
        $producto = strtoupper($producto_data['nombre_producto'] . " " . $producto_data['concentracion']);
        $tipo_producto = strtoupper($producto_data['tipo_producto']);
        $lote = strtoupper($producto_data['lote']);
    } else {
        $producto = "DESCONOCIDO";
        $tipo_producto = "DESCONOCIDO";
        $lote = "DESCONOCIDO";
    }

    $asunto = "RECCIUS informa | Salida de Cuarentena de {$tipo_producto} - {$producto} - lote: {$lote}";
    $estado = strtoupper($estado);
    $fecha_liberacion = strtoupper($fecha_liberacion);

    // Generar el cuerpo del correo
    $cuerpo = "
        <p>Estimados,</p>
        <p>Se les informa que con fecha <strong>{$fecha_liberacion}</strong>, el producto <strong>{$producto}</strong> con lote <strong>{$lote}</strong> sale de cuarentena con el siguiente estado: <strong>{$estado}</strong>.</p>
        <p>Favor realizar las acciones necesarias para continuar con el proceso.</p>
        <p>Saludos,</p>
        <p>Equipo Reccius</p>
    ";

    // Obtener el hostname actual
    $hostname = $_SERVER['SERVER_NAME'];
    
    // Seleccionar la lista de destinatarios según el hostname
    if ($hostname === 'customware.cl') {
        $destinatarios = [
            ['email' => 'fabarca212@gmail.cl', 'nombre' => 'Felipe Abarca'],
            ['email' => 'javier2000asr@gmail.com', 'nombre' => 'Javier Sabando'],
            ['email' => 'lucianoalonso2000@gmail.cl', 'nombre' => 'Luciano Abarca']
        ];
    } elseif ($hostname === 'reccius.cl') {
        $destinatarios = [
            ['email' => 'mgodoy@reccius.cl', 'nombre' => 'Macarena Godoy'],
            ['email' => 'isumonte@reccius.cl', 'nombre' => 'Inger Sumonte'],
            ['email' => 'clpereir@reccius.cl', 'nombre' => 'Catherine Pereira']
        ];
    } else {
        die("Error: Hostname no reconocido. No se puede enviar el correo.");
    }

    // Enviar el correo
    enviarCorreoMultiple($destinatarios, $asunto, $cuerpo);
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, TRUE);

    $id_analisis_externo = isset($input['id_analisis_externo']) ? intval($input['id_analisis_externo']) : null;
    $id_especificacion = isset($input['id_especificacion']) ? intval($input['id_especificacion']) : null;
    $id_producto = isset($input['id_producto']) ? intval($input['id_producto']) : null;
    $id_actaMuestreo = isset($input['id_actaMuestreo']) ? intval($input['id_actaMuestreo']) : null;
    $id_cuarentena = isset($input['id_cuarentena']) ? intval($input['id_cuarentena']) : null;
    $nro_acta = isset($input['nro_acta']) ? $input['nro_acta'] : null;
    $nro_registro = isset($input['nro_registro']) ? $input['nro_registro'] : null;
    $nro_version = isset($input['nro_version']) ? intval($input['nro_version']) : null;
    $fecha_acta_lib = isset($input['fecha_acta_lib']) ? $input['fecha_acta_lib'] : null;
    $tipo_producto = isset($input['tipo_producto']) ? $input['tipo_producto'] : null;
    $estado = isset($input['estado']) ? $input['estado'] : null;
    $obs1 = isset($input['obs1']) ? $input['obs1'] : null;
    $obs2 = isset($input['obs2']) ? $input['obs2'] : null;
    $obs3 = isset($input['obs3']) ? $input['obs3'] : null;
    $obs4 = isset($input['obs4']) ? $input['obs4'] : null;
    $cant_real_liberada = isset($input['cant_real_liberada']) ? $input['cant_real_liberada'] : null;
    $parte_ingreso = isset($input['parte_ingreso']) ? $input['parte_ingreso'] : null;
    $docConformeResults = isset($input['docConformeResults']) ? $input['docConformeResults'] : null;
    $revisionResults = isset($input['revisionResults']) ? $input['revisionResults'] : null;
    $flujo= isset($input['fase']) ? $input['fase'] : null;
    $year = date("y");
    $month = date("m");
    $aux_anomes = $year . $month;
    $usuario_firma1=$_SESSION['usuario'];
    $fecha_firma1= date("Y-m-d");
    $query1 = "SELECT MAX(aux_autoincremental) AS max_correlativo FROM calidad_acta_liberacion WHERE aux_anomes = ? and aux_tipo=?";
    $stmt1 = mysqli_prepare($link, $query1);
    mysqli_stmt_bind_param($stmt1, "ss", $aux_anomes, $tipo_producto);
    mysqli_stmt_execute($stmt1);
    $result = mysqli_stmt_get_result($stmt1);
    $row = mysqli_fetch_assoc($result);
    $correlativo = isset($row['max_correlativo']) ? $row['max_correlativo'] + 1 : 1;
    $correlativoStr = str_pad($correlativo, 3, '0', STR_PAD_LEFT); // Asegura que el correlativo tenga 3 dígitos
    mysqli_stmt_close($stmt1);
    // Insert/Update data in the database
    // Adjust the query according to your table structure and field names
    $query = "INSERT INTO calidad_acta_liberacion (id_cuarentena, id_analisisExterno, id_especificacion, id_producto, id_actaMuestreo, numero_acta, numero_registro, version_registro, fecha_acta, aux_tipo, estado, obs1, obs2, obs3, obs4, cantidad_real_liberada, nro_parte_ingreso, 
    revision_estados, revision_liberacion, 
    aux_anomes, aux_autoincremental, usuario_firma1, fecha_firma1) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $link->prepare($query)) {
        $stmt->bind_param("iiiiissssssssssssssiiss", $id_cuarentena, $id_analisis_externo, $id_especificacion, $id_producto, $id_actaMuestreo, $nro_acta, $nro_registro, $nro_version, $fecha_acta_lib, $tipo_producto, $estado, $obs1, $obs2, $obs3, $obs4, $cant_real_liberada, $parte_ingreso, 
        $docConformeResults, $revisionResults, 
        $aux_anomes, $correlativo, $usuario_firma1, $fecha_firma1);

        if ($stmt->execute()) {
            $id_actaLiberacion = $stmt->insert_id;
            registrarTrazabilidad(
                $_SESSION['usuario'],
                $_SERVER['PHP_SELF'],
                $flujo,
                'CALIDAD - Acta de Liberación',
                $stmt->insert_id, // ID del registro insertado
                $query,
                [$id_cuarentena, $id_analisis_externo, $id_especificacion, $id_producto, $id_actaMuestreo, $nro_acta, $nro_registro, $nro_version, $fecha_acta_lib, $tipo_producto, $estado, $obs1, $obs2, $obs3, $obs4, $cant_real_liberada, $parte_ingreso, $docConformeResults, $revisionResults, $aux_anomes, $correlativo, $usuario_firma1, $fecha_firma1],
                1,
                null
            );

            $stmt3 = mysqli_prepare($link, "UPDATE calidad_analisis_externo SET estado='completado', fecha_liberacion=? WHERE id=?");
            mysqli_stmt_bind_param($stmt3, "si", $fecha_acta_lib, $id_analisis_externo);
            mysqli_stmt_execute($stmt3);
            mysqli_stmt_close($stmt3);

            $estado_producto = ($estado === 'aprobado') ? 'liberado' : 'rechazado';
            $query_update = "UPDATE calidad_productos_analizados 
                             SET estado = ?, cantidad_real_liberada = ?, nro_parte_ingreso = ?, id_actaMuestreo = ?, id_actaLiberacion = ?, fecha_out_cuarentena = ?
                             WHERE id = ?";

            $stmt4 = mysqli_prepare($link, $query_update);
            if (!$stmt4) {
                throw new Exception("Error en la preparación de la consulta de actualización: " . mysqli_error($link));
            }

            mysqli_stmt_bind_param($stmt4, "sssiisi", $estado_producto, $cant_real_liberada, $parte_ingreso, $id_actaMuestreo, $id_actaLiberacion, $fecha_firma1, $id_cuarentena);

            $exito_4 = mysqli_stmt_execute($stmt4);
            registrarTrazabilidad(
                $_SESSION['usuario'], 
                $_SERVER['PHP_SELF'], 
                'Acta de liberación o rechazo', 
                'calidad_productos_analizados',  
                $id_cuarentena, 
                $query_update,  
                [$estado_producto, $cant_real_liberada, $parte_ingreso, $id_actaMuestreo, $id_actaLiberacion, $fecha_firma1, $id_cuarentena], 
                $exito_4 ? 1 : 0, 
                $exito_4 ? null : mysqli_error($link)
            );
            if (!$exito_4) {
                throw new Exception("Error al ejecutar la actualización: " . mysqli_stmt_error($stmt4));
            }

            mysqli_stmt_close($stmt4);
            unset($_SESSION['buscar_por_ID']);
            if ($exito) {
                $_SESSION['buscar_por_ID'] = $id_cuarentena;
            }
            echo json_encode(['success' => 'Data saved successfully', 'id_actaLiberacion' => $id_actaLiberacion, 'id_productoAnalizado' => $id_cuarentena]);
            finalizarTarea($_SESSION['usuario'], $id_analisis_externo, 'calidad_analisis_externo', 'Emitir acta de liberación');
            informativo_liberacion($fecha_firma1, $estado_producto, $id_analisis_externo);
        } else {
            registrarTrazabilidad(
                $_SESSION['usuario'],
                $_SERVER['PHP_SELF'],
                $flujo,
                'CALIDAD - Acta de Liberación',
                null,
                $query,
                [$id_analisis_externo, $id_especificacion, $id_producto, $id_actaMuestreo, $nro_acta, $nro_registro, $nro_version, $fecha_acta_lib, $tipo_producto, $estado, $obs1, $obs2, $obs3, $obs4, $cant_real_liberada, $parte_ingreso, $docConformeResults, $revisionResults, $aux_anomes, $correlativo, $usuario_firma1, $fecha_firma1],
                0,
                $stmt->error
            );
            echo json_encode(['error' => 'Failed to save data: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Failed to prepare statement: ' . $link->error]);
    }
    $link->close();
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
