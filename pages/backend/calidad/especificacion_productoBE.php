<?php

session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
$exito=false;
$mensaje='';
function limpiarDato($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Imprime los datos recibidos para propósitos de depuración
    //echo "<pre>";
    //print_r($_POST);
    //echo "</pre>";
    $crea_producto=false;
    $crea_especificacion=false;
    $crea_analisis=false;

    $error = 'Campos faltantes: ';
    $campos = [
        'Tipo_Producto' => 'Tipo producto',
        'producto' => 'Producto',
        'concentracion' => 'Concentración',
        'formato' => 'Formato',
        'elaboradoPor' => 'Elaborado por',
        'documento' => 'Documento',
        'fechaEdicion' => 'Fecha de edición',
        'version' => 'Versión',
        'vigencia' => 'Vigencia'
    ];
    
    foreach ($campos as $campo => $nombre) {
        if (empty($_POST[$campo])) {
            $error .= "$nombre, ";
        }
    }
    
    if ($error != 'Campos faltantes: ') {
        $error = rtrim($error, ', ');
        //echo "Todos los campos son requeridos. ".$error;
    } else {
        // Proceso de inserción si todos los campos están presentes
        $tipoProducto = limpiarDato($_POST['Tipo_Producto']);
        $producto = limpiarDato($_POST['producto']);
        $concentracion = limpiarDato($_POST['concentracion']);
        $formato = limpiarDato($_POST['formato']);
        $elaboradoPor = limpiarDato($_POST['elaboradoPor']);
        $numeroDocumento = limpiarDato($_POST['documento']);
        $fechaEdicion = limpiarDato($_POST['fechaEdicion']);
        $version = limpiarDato($_POST['version']);
        $vigencia = limpiarDato($_POST['vigencia']);

        // Preparar sentencia para insertar en calidad_productos
        $query="INSERT INTO calidad_productos (nombre_producto, tipo_producto, concentracion, formato, elaborado_por, documento_ingreso) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($link, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssss", $producto, $tipoProducto, $concentracion, $formato, $elaboradoPor, $numeroDocumento);
            $crea_producto=mysqli_stmt_execute($stmt);
            
            //in trazabilidad
            $resultado = $crea_producto ? 1 : 0; // Suponiendo que 1 es éxito y 0 es fracaso
            $error = $crea_producto ? null :  "Error al ejecutar la consulta: " . mysqli_stmt_error($stmt);
            registrarTrazabilidad($_SESSION['usuario'], $_SERVER['PHP_SELF'], 'CALIDAD - crea producto', 'calidad_productos',  mysqli_insert_id($link), $query, [$producto, $tipoProducto, $concentracion, $formato, $elaboradoPor, $numeroDocumento], $resultado, $error);
            // out trazabidad
            if ($crea_producto) {
                $idProducto = mysqli_insert_id($link);
                $fechaEdicionDateTime = new DateTime($fechaEdicion);
                $fechaEdicionDateTime->modify("+$vigencia years");
                $fechaExpiracion = $fechaEdicionDateTime->format('Y-m-d');
                $numeroDocumentoFormateado = 'DCAL-CC-EPT-' . sprintf('%03d', $version);
                $query_especificacion="INSERT INTO calidad_especificacion_productos (id_producto, documento, fecha_edicion, version, fecha_expiracion, vigencia) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt2 = mysqli_prepare($link, $query_especificacion);
                if ($stmt2) {
                    mysqli_stmt_bind_param($stmt2, "issssi", $idProducto, $numeroDocumentoFormateado, $fechaEdicion, $version, $fechaExpiracion, $vigencia);
                    $crea_especificacion=mysqli_stmt_execute($stmt2)
                    //in trazabilidad
                        $resultado = $crea_especificacion ? 1 : 0; // Suponiendo que 1 es éxito y 0 es fracaso
                        $error = $resultado ? null : "Error al ejecutar la consulta: " . mysqli_stmt_error($stmtAnalisisFQ);
                        registrarTrazabilidad($_SESSION['usuario'], $_SERVER['PHP_SELF'], 'CALIDAD - crea especificación', 'calidad_calidad_especificacion_productosnalisis',  mysqli_insert_id($link), $query_analisis, [$idProducto, $numeroDocumentoFormateado, $fechaEdicion, $version, $fechaExpiracion, $vigencia], $resultado, $error);
                    // out trazabidad

                    if ($crea_especificacion) {
                        //echo "Especificación de producto creada con éxito.";
                        $exito=true;
                        $idEspecificacion = mysqli_insert_id($link); // ID de la especificación insertada
                        $mensaje = "Especificación de producto creada con éxito.";
                    } else {
                        $mensaje = "Error al insertar en calidad_especificacion_productos: " . mysqli_error($link);
                    }
                    mysqli_stmt_close($stmt2);
                } else {
                    $mensaje = "Error en la preparación de la sentencia de calidad_especificacion_productos: " . mysqli_error($link);
                }
            } else {
                $mensaje = "Error al insertar en calidad_productos: " .$crea_producto."trazabilidad: ".mysqli_insert_id($link). mysqli_error($link);
            }
        } else {
            $mensaje = "Error en la preparación de la sentencia de calidad_productos: " . mysqli_error($link);
        }
        mysqli_stmt_close($stmt);
        
        
    };
    
    // Procesar datos de analisisFQ
    // Procesar datos de analisisFQ
    if (isset($_POST['analisisFQ']) && is_array($_POST['analisisFQ'])) {
        foreach ($_POST['analisisFQ'] as $analisis) {
            $crea_analisis='';
            // Asegúrate de que estas claves coincidan con las de tu array
            $descripcion_analisis = limpiarDato($analisis['descripcion_analisis']);
            $metodologia = limpiarDato($analisis['metodologia']);
            $criterios_aceptacion = limpiarDato($analisis['criterio']);
            $tipo='analisis_FQ';
            $query_analisis="INSERT INTO calidad_analisis (id_especificacion_producto, tipo_analisis, descripcion_analisis, metodologia, criterios_aceptacion) VALUES (?, ?, ?, ?, ?)";
            $stmtAnalisisFQ = mysqli_prepare($link, $query_analisis);
            mysqli_stmt_bind_param($stmtAnalisisFQ, "issss", $idEspecificacion, $tipo, $descripcion_analisis, $metodologia, $criterios_aceptacion);
            $crea_analisis=mysqli_stmt_execute($stmtAnalisisFQ);
            //in trazabilidad
                $resultado = $crea_analisis ? 1 : 0; // Suponiendo que 1 es éxito y 0 es fracaso
                $error = $resultado ? null : "Error al ejecutar la consulta: " . mysqli_stmt_error($stmtAnalisisFQ);
                registrarTrazabilidad($_SESSION['usuario'], $_SERVER['PHP_SELF'], 'CALIDAD - crea analisis FQ', 'calidad_analisis',  mysqli_insert_id($link), $query_analisis, [$idEspecificacion, $tipo, $descripcion_analisis, $metodologia, $criterios_aceptacion], $resultado, $error);
            // out trazabidad
            if ($crea_analisis='';) {
                // Éxito en la inserción
            } else {
                $mensaje = "Error al insertar en calidad_analisis para analisis_FQ: " . mysqli_error($link);
            }
            mysqli_stmt_close($stmtAnalisisFQ);
        }
    }

    // Procesar datos de analisisMB
    if (isset($_POST['analisisMB']) && is_array($_POST['analisisMB'])) {
        foreach ($_POST['analisisMB'] as $analisis) {
            // Asegúrate de que estas claves coincidan con las de tu array
            $crea_analisis='';
            $descripcion_analisis = limpiarDato($analisis['descripcion_analisis']);
            $metodologia = limpiarDato($analisis['metodologia']);
            $criterios_aceptacion = limpiarDato($analisis['criterio']);
            $tipo='analisis_MB';
            $query_analisis="INSERT INTO calidad_analisis (id_especificacion_producto, tipo_analisis, descripcion_analisis, metodologia, criterios_aceptacion) VALUES (?, ?, ?, ?, ?)";
            $stmtAnalisisMB = mysqli_prepare($link, $query_analisis);
            mysqli_stmt_bind_param($stmtAnalisisMB, "issss", $idEspecificacion, $tipo, $descripcion_analisis, $metodologia, $criterios_aceptacion);
            $crea_analisis = mysqli_stmt_execute($stmtAnalisisMB);
            //in trazabilidad
                $resultado = $crea_analisis ? 1 : 0; // Suponiendo que 1 es éxito y 0 es fracaso
                $error = $crea_analisis ? null: "Error al ejecutar la consulta: " . mysqli_stmt_error($stmtAnalisisMB);
                registrarTrazabilidad($_SESSION['usuario'], $_SERVER['PHP_SELF'], 'crea analisis MB', 'calidad_analisis',  mysqli_insert_id($link), $query_analisis, [$idEspecificacion, $tipo, $descripcion_analisis, $metodologia, $criterios_aceptacion], $resultado, $error);
            // out trazabidad
            if ($crea_analisis) {
                // Éxito en la inserción
            } else {
                $mensaje = "Error al insertar en calidad_analisis para analisis_MB: " . mysqli_error($link);
            }
            mysqli_stmt_close($stmtAnalisisMB);
        }
    }

    mysqli_close($link);
} else {
    //echo "Todos los campos son requeridos. ".$error;
}
$_SESSION['buscarEspecificacion']=$idEspecificacion;
$respuesta = [
    "exito" => $exito,
    "mensaje" => $mensaje,
    "idEspecificacion" => $idEspecificacion
];
echo json_encode($respuesta);
exit;
?>
