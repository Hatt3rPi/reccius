<?php
//archivo pages\backend\acta_muestreo\versiona_acta.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}
$tipo_producto='';
$identificador_producto='';
$id_especificacion='';
$id_producto='';
$id_analisis_externo='';
$responsable='';
$ejecutor='';
$verificador='';
$nuevo_id='';
$numero_acta='';
$numero_registro='';
// Validación y saneamiento del ID del análisis externo
$id_analisis_externo = isset($_GET['id_analisis_externo']) ? intval($_GET['id_analisis_externo']) : 0;
$id_original = isset($_GET['id_original']) ? intval($_GET['id_original']) : 0;
$version_actaMuestreo = isset($_GET['version']) ? intval($_GET['version']) + 1 : 0;


//OBTENCIÓN DE DATOS
    // Consulta SQL para obtener los datos del análisis externo y el producto asociado
    $query = "SELECT aex.id as id_analisis_externo, aex.id_especificacion, aex.id_producto,
    pr.nombre_producto, pr.formato, pr.concentracion, pr.tipo_producto,
    aex.lote, aex.tamano_lote, ep.codigo_mastersoft, aex.condicion_almacenamiento, aex.tamano_muestra, aex.tamano_contramuestra, aex.tipo_analisis, aex.muestreado_por, aex.am_verificado_por, aex.am_ejecutado_por,
    usrRev.nombre as nombre_usrRev, usrRev.cargo as cargo_usrRev, usrRev.foto_firma as foto_firma_usrRev, usrRev.ruta_registroPrestadoresSalud as ruta_registroPrestadoresSalud_usrRev, 
    usrMuest.nombre as nombre_usrMuest, usrMuest.cargo as cargo_usrMuest, usrMuest.foto_firma as foto_firma_usrMuest, usrMuest.ruta_registroPrestadoresSalud as ruta_registroPrestadoresSalud_usrMuest,
     usrEje.nombre as nombre_usrEje, usrEje.cargo as cargo_usrEje, usrEje.foto_firma as foto_firma_usrEje, usrEje.ruta_registroPrestadoresSalud as ruta_registroPrestadoresSalud_usrEje, 
    LPAD(pr.identificador_producto, 3, '0') AS identificador_producto,
	aex.solicitado_por,
    aex.numero_solicitud, usrMuest.usuario as usuario_firma2, usrEje.usuario as usuario_firma1, aex.fecha_elaboracion, aex.fecha_vencimiento, aex.observaciones,
	(select numero_acta from calidad_acta_muestreo where id=?) as numero_acta,
    (select numero_registro from calidad_acta_muestreo where id=?) as numero_registro
    FROM `calidad_analisis_externo` as aex
    LEFT JOIN calidad_productos as pr ON aex.id_producto = pr.id
    LEFT JOIN usuarios as usrMuest ON aex.muestreado_por=usrMuest.usuario
    LEFT JOIN usuarios as usrRev ON aex.am_verificado_por=usrRev.usuario
    LEFT JOIN usuarios as usrEje ON aex.am_ejecutado_por=usrEje.usuario
    left join calidad_especificacion_productos as ep on aex.id_especificacion=ep.id_especificacion
    WHERE aex.id =?;";

    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "iii", $id_original, $id_original, $id_analisis_externo);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $analisis_externos = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $analisis_externos[] = [
            'id_analisis_externo' => $row['id_analisis_externo'],
            'id_especificacion' => $row['id_especificacion'],
            'id_producto' => $row['id_producto'],
            'nombre_producto' => $row['nombre_producto'],
            'formato' => $row['formato'],
            'concentracion' => $row['concentracion'],
            'tipo_producto' => $row['tipo_producto'],
            'lote' => $row['lote'],
            'tamano_lote' => $row['tamano_lote'],
            'codigo_mastersoft' => $row['codigo_mastersoft'],
            'condicion_almacenamiento' => $row['condicion_almacenamiento'],
            'tamano_muestra' => $row['tamano_muestra'],
            'tamano_contramuestra' => $row['tamano_contramuestra'],
            'tipo_analisis' => $row['tipo_analisis'],
            'muestreado_por' => $row['nombre_usrMuest'],
            'cargo_muestreado_por' => $row['cargo_usrMuest'],
            'foto_firma_muestreado_por' => $row['foto_firma_usrMuest'],
            'ruta_registroPrestadoresSalud_muestreado_por' => $row['ruta_registroPrestadoresSalud_usrMuest'],
            'revisado_por' => $row['nombre_usrRev'],
            'cargo_revisado_por' => $row['cargo_usrRev'],
            'foto_firma_revisado_por' => $row['foto_firma_usrRev'],
            'identificador_producto' => $row['identificador_producto'],
            'ruta_registroPrestadoresSalud_revisado_por' => $row['ruta_registroPrestadoresSalud_usrRev'],
            'nombre_usr2'=> $row['nombre_usrMuest'],
            'cargo_usr2'=> $row['cargo_usrMuest'],
            'nombre_usr3'=> $row['nombre_usrRev'],
            'cargo_usr3'=> $row['cargo_usrRev'],
            'aex_solicitado_por'=> $row['solicitado_por'],
            'aex_numero_solicitud'=> $row['numero_solicitud'],
            'usuario_firma2'=> $row['usuario_firma2'],
            'fecha_elaboracion'=> $row['fecha_elaboracion'],
            'fecha_vencimiento'=> $row['fecha_vencimiento'],
            'observaciones'=> $row['observaciones'],
        ];
        $tipo_producto=$row['tipo_producto'];
        $identificador_producto=$row['identificador_producto'];
        $id_especificacion=$row['id_especificacion'];
        $id_producto=$row['id_producto'];
        $id_analisis_externo=$row['id_analisis_externo'];
        $responsable=$row['muestreado_por'];
        $verificador=$row['am_verificado_por'];
        $ejecutor=$row['ejecutado_por'];
        $numero_acta=$row['numero_acta'];
        $numero_registro=$row['numero_registro'];
    }
    mysqli_stmt_close($stmt);



    // Insertar en la base de datos
    $insertQuery = "INSERT INTO calidad_acta_muestreo (numero_registro, version_registro, id_original,  numero_acta, version_acta, fecha_muestreo, id_especificacion, id_producto, id_analisisExterno,   responsable, verificador) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($link, $insertQuery);
    mysqli_stmt_bind_param($stmt, "ssisiiiiss", $numero_registro, $version_actaMuestreo, $id_original,  $numero_acta, $version_actaMuestreo, $id_especificacion, $id_producto, $id_analisis_externo,  $responsable, $verificador);
    
    $exito = mysqli_stmt_execute($stmt);
    $nuevo_id = mysqli_insert_id($link); // Obtiene el ID de la última fila insertada
    unset($_SESSION['nuevo_id']);
    $_SESSION['nuevo_id'] = $nuevo_id; // Almacena el nuevo ID en la sesión


    // Update posterior a la inserción para actualizar el campo id_original
        $updateQuery = "UPDATE calidad_productos_analizados SET id_actaMuestreo=? where id_analisisExterno=?;";
        $updateStmt = mysqli_prepare($link, $updateQuery);
        mysqli_stmt_bind_param($updateStmt, "ii", $nuevo_id, $id_analisis_externo); // Actualiza el campo id_original con el mismo nuevo_id
        $updateExito = mysqli_stmt_execute($updateStmt);
        mysqli_stmt_close($updateStmt); // Cierra el statement del update

        // Ejecutar la declaración
    registrarTrazabilidad(
        $_SESSION['usuario'], 
        $_SERVER['PHP_SELF'], 
        'Genera Acta de Muestreo', 
        'acta de muestreo',  
        $nuevo_id, 
        $insertQuery,  
        [$numero_registro, $version_actaMuestreo, $id_original,  $numero_acta, $version_actaMuestreo, $id_especificacion, $id_producto, $id_analisis_externo,  $responsable, $verificador], 
        $exito ? 1 : 0, 
        $exito ? null : mysqli_error($link)
    );
    if ($exito) {
        
        finalizarTarea($_SESSION['usuario'], $id_analisis_externo, 'calidad_analisis_externo', 'Generar Acta Muestreo');
        registrarTarea(7, $_SESSION['usuario'], $_SESSION['usuario'], 'Ingresar resultados de Acta de Muestreo: ' . $numero_acta , 2, 'Firma 1', $nuevo_id, 'calidad_acta_muestreo');
        //tarea anterior se cierra: finalizarTarea($_SESSION['usuario'], $nuevo_id, 'calidad_acta_muestreo', 'Firma 1');
        // Actualización de los datos con el nuevo número de acta
        unset($value);
    } else {
        // Manejo de errores en la inserción
        echo json_encode(['error' => 'Error al insertar el acta muestreada']);
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);

// Devolver los resultados en formato JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'analisis_externos' => $analisis_externos,
    'id_actaMuestreo' => $nuevo_id,
    'numero_acta' => $numero_acta,
    'numero_registro' => $numero_registro
], JSON_UNESCAPED_UNICODE);
?>
