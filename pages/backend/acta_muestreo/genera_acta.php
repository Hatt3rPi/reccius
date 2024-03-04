<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
$tipo_producto='';
$identificador_producto='';
$id_especificacion='';
$id_producto='';
$id_analisis_externo='';
$responsable='';
$verificador='';
// Validación y saneamiento del ID del análisis externo
$id_analisis_externo = isset($_GET['id_analisis_externo']) ? intval($_GET['id_analisis_externo']) : 0;

//OBTENCIÓN DE DATOS
    // Consulta SQL para obtener los datos del análisis externo y el producto asociado
    $query = "SELECT aex.id as id_analisis_externo, aex.id_especificacion, aex.id_producto,
    pr.nombre_producto, pr.formato, pr.concentracion, pr.tipo_producto,
    aex.lote, aex.tamano_lote, aex.codigo_mastersoft, aex.condicion_almacenamiento, aex.tamano_muestra, aex.tamano_contramuestra, aex.tipo_analisis, aex.muestreado_por, aex.revisado_por, 
    usrRev.nombre as nombre_usrRev, usrRev.cargo as cargo_usrRev, usrRev.foto_firma as foto_firma_usrRev, usrRev.ruta_registroPrestadoresSalud as ruta_registroPrestadoresSalud_usrRev, 
    usrMuest.nombre as nombre_usrMuest, usrMuest.cargo as cargo_usrMuest, usrMuest.foto_firma as foto_firma_usrMuest, usrMuest.ruta_registroPrestadoresSalud as ruta_registroPrestadoresSalud_usrMuest,
    LPAD(pr.identificador_producto, 3, '0') AS identificador_producto
    FROM `calidad_analisis_externo` as aex
    LEFT JOIN calidad_productos as pr ON aex.id_producto = pr.id
    LEFT JOIN usuarios as usrMuest ON aex.muestreado_por=usrMuest.usuario
    LEFT JOIN usuarios as usrRev ON aex.revisado_por=usrRev.usuario
    WHERE aex.id = ?";

    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_analisis_externo);
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
        ];
        $tipo_producto=$row['tipo_producto'];
        $identificador_producto=$row['identificador_producto'];
        $id_especificacion=$row['id_especificacion'];
        $id_producto=$row['id_producto'];
        $id_analisis_externo=$row['id_analisis_externo'];
        $responsable=$row['muestreado_por'];
        $verificador=$row['revisado_por'];
    }
    mysqli_stmt_close($stmt);


//INGRESO DE ACTA
    // Obtener el año y mes actuales
    $year = date("y");
    $month = date("m");

    // Consulta para obtener el mayor aux_autoincremental para el año y mes actual
    $query = "SELECT MAX(aux_autoincremental) AS max_correlativo FROM calidad_acta_muestreo WHERE aux_anomes = ? and aux_tipo=?";

    $aux_anomes = $year . $month; // Concatenación de año y mes para la búsqueda

    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "ss", $aux_anomes, $tipo_producto);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    $correlativo = isset($row['max_correlativo']) ? $row['max_correlativo'] + 1 : 1;
    $correlativoStr = str_pad($correlativo, 3, '0', STR_PAD_LEFT); // Asegura que el correlativo tenga 3 dígitos
    
    // Inserta el nuevo acta con el número de acta generado
    switch ($tipo_producto) {
        case 'Material Envase y Empaque':
            $numero_registro = 'DCAL-CC-AMMEE-' . str_pad($identificador_producto, 3, '0', STR_PAD_LEFT);
            $numero_acta = "AMMEE-" . $year . $month . $correlativoStr ;
            break;
        case 'Materia Prima':
            $numero_registro = 'DCAL-CC-AMMP-' . str_pad($identificador_producto, 3, '0', STR_PAD_LEFT);
            $numero_acta = "AMMP-" . $year . $month . $correlativoStr ;
            break;
        case 'Producto Terminado':
            $numero_registro = 'DCAL-CC-AMPT-' . str_pad($identificador_producto, 3, '0', STR_PAD_LEFT);
            $numero_acta = "AMPT-" . $year . $month . $correlativoStr ;
            break;
        case 'Insumo':
            $numero_registro = 'DCAL-CC-AMINS-' . str_pad($identificador_producto, 3, '0', STR_PAD_LEFT);
            $numero_acta = "AMINS-" . $year . $month . $correlativoStr ;
            break;
        default:
            $numero_registro = 'Desconocido';
            $numero_acta= 'Desconocido';
    }
    
    // Insertar en la base de datos
    $insertQuery = "INSERT INTO calidad_acta_muestreo (numero_registro, version_registro, numero_acta, version_acta, fecha_muestreo, id_especificacion, id_producto, id_analisisExterno, aux_autoincremental, aux_anomes, responsable, verificador, aux_tipo) VALUES (?, 1, ?, '01', NOW(), ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($link, $insertQuery);
    mysqli_stmt_bind_param($stmt, "ssiiiiisss", $numero_registro, $numero_acta, $id_especificacion, $id_producto, $id_analisis_externo, $correlativo, $aux_anomes, $responsable, $verificador, $tipo_producto);
    $result = mysqli_stmt_execute($stmt);
    foreach ($analisis_externos as $key => &$value) {
        $value['numero_acta'] = $numero_acta. "-01";
    }
    unset($value); 

    mysqli_stmt_close($stmt);
 mysqli_close($link);

// Devolver los resultados en formato JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['analisis_externos' => $analisis_externos], JSON_UNESCAPED_UNICODE);
?>
