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
            $error = mysqli_stmt_error($stmt) ? "Error al ejecutar la consulta: " . mysqli_stmt_error($stmt) : null;
            try{
                registrarTrazabilidad($_SESSION['usuario'], $_SERVER['PHP_SELF'], 'crea producto', 'calidad_productos',  mysqli_insert_id($link), $query, [$producto, $tipoProducto, $concentracion, $formato, $elaboradoPor, $numeroDocumento], $resultado, $error);
            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
            }
        // out trazabidad
            if ($crea_producto) {
                $idProducto = mysqli_insert_id($link);
                $fechaEdicionDateTime = new DateTime($fechaEdicion);
                $fechaEdicionDateTime->modify("+$vigencia years");
                $fechaExpiracion = $fechaEdicionDateTime->format('Y-m-d');
                $numeroDocumentoFormateado = 'DCAL-CC-EPT-' . sprintf('%03d', $version);
                $stmt2 = mysqli_prepare($link, "INSERT INTO calidad_especificacion_productos (id_producto, documento, fecha_edicion, version, fecha_expiracion, vigencia) VALUES (?, ?, ?, ?, ?, ?)");
                if ($stmt2) {
                    mysqli_stmt_bind_param($stmt2, "issssi", $idProducto, $numeroDocumentoFormateado, $fechaEdicion, $version, $fechaExpiracion, $vigencia);
                    if (mysqli_stmt_execute($stmt2)) {
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
                $mensaje = "Error al insertar en calidad_productos: " . mysqli_error($link);
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
            // Asegúrate de que estas claves coincidan con las de tu array
            $descripcion_analisis = limpiarDato($analisis['descripcion_analisis']);
            $metodologia = limpiarDato($analisis['metodologia']);
            $criterios_aceptacion = limpiarDato($analisis['criterio']);
            $tipo='analisis_FQ';

            $stmtAnalisisFQ = mysqli_prepare($link, "INSERT INTO calidad_analisis (id_especificacion_producto, tipo_analisis, descripcion_analisis, metodologia, criterios_aceptacion) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmtAnalisisFQ, "issss", $idEspecificacion, $tipo, $descripcion_analisis, $metodologia, $criterios_aceptacion);
            if (mysqli_stmt_execute($stmtAnalisisFQ)) {
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
            $descripcion_analisis = limpiarDato($analisis['descripcion_analisis']);
            $metodologia = limpiarDato($analisis['metodologia']);
            $criterios_aceptacion = limpiarDato($analisis['criterio']);
            $tipo='analisis_MB';
            $stmtAnalisisMB = mysqli_prepare($link, "INSERT INTO calidad_analisis (id_especificacion_producto, tipo_analisis, descripcion_analisis, metodologia, criterios_aceptacion) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmtAnalisisMB, "issss", $idEspecificacion, $tipo, $descripcion_analisis, $metodologia, $criterios_aceptacion);
            if (mysqli_stmt_execute($stmtAnalisisMB)) {
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
