<?php

session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

function limpiarDato($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Imprime los datos recibidos para propósitos de depuración
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

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
        echo "Todos los campos son requeridos. ".$error;
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
        $stmt = mysqli_prepare($link, "INSERT INTO calidad_productos (nombre_producto, tipo_producto, concentracion, formato, elaborado_por, documento_ingreso) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssss", $producto, $tipoProducto, $concentracion, $formato, $elaboradoPor, $numeroDocumento);
            if (mysqli_stmt_execute($stmt)) {
                $idProducto = mysqli_insert_id($link);
                $fechaEdicionDateTime = new DateTime($fechaEdicion);
                $fechaEdicionDateTime->modify("+$vigencia years");
                $fechaExpiracion = $fechaEdicionDateTime->format('Y-m-d');
                $stmt2 = mysqli_prepare($link, "INSERT INTO calidad_especificacion_productos (id_producto, documento, fecha_edicion, version, fecha_expiracion) VALUES (?, ?, ?, ?, ?)");
                if ($stmt2) {
                    mysqli_stmt_bind_param($stmt2, "issss", $idProducto, $numeroDocumento, $fechaEdicion, $version, $fechaExpiracion);
                    if (mysqli_stmt_execute($stmt2)) {
                        echo "Especificación de producto creada con éxito.";
                        $idEspecificacion = mysqli_insert_id($link); // ID de la especificación insertada
                    } else {
                        echo "Error al insertar en calidad_especificacion_productos: " . mysqli_error($link);
                    }
                    mysqli_stmt_close($stmt2);
                } else {
                    echo "Error en la preparación de la sentencia de calidad_especificacion_productos: " . mysqli_error($link);
                }
            } else {
                echo "Error al insertar en calidad_productos: " . mysqli_error($link);
            }
        } else {
            echo "Error en la preparación de la sentencia de calidad_productos: " . mysqli_error($link);
        }
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        
    };
    
    // Procesar datos de analisisFQ
    // Procesar datos de analisisFQ
    if (isset($_POST['analisisFQ']) && is_array($_POST['analisisFQ'])) {
        foreach ($_POST['analisisFQ'] as $analisis) {
            // Asegúrate de que estas claves coincidan con las de tu array
            $descripcion_analisis = limpiarDato($analisis['descripcion_analisis']);
            $metodologia = limpiarDato($analisis['metodologia']);
            $criterios_aceptacion = limpiarDato($analisis['criterio']);

            $stmtAnalisisFQ = mysqli_prepare($link, "INSERT INTO calidad_analisis (id_especificacion_producto, tipo_analisis, descripcion_analisis, metodologia, criterios_aceptacion) VALUES (?, 'analisis_FQ', ?, ?, ?)");
            mysqli_stmt_bind_param($stmtAnalisisFQ, "isss", $idEspecificacion, $descripcion_analisis, $metodologia, $criterios_aceptacion);
            if (mysqli_stmt_execute($stmtAnalisisFQ)) {
                // Éxito en la inserción
            } else {
                echo "Error al insertar en calidad_analisis para analisis_FQ: " . mysqli_error($link);
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

            $stmtAnalisisMB = mysqli_prepare($link, "INSERT INTO calidad_analisis (id_especificacion_producto, tipo_analisis, descripcion_analisis, metodologia, criterios_aceptacion) VALUES (?, 'analisis_MB', ?, ?, ?)");
            mysqli_stmt_bind_param($stmtAnalisisMB, "isss", $idEspecificacion, $descripcion_analisis, $metodologia, $criterios_aceptacion);
            if (mysqli_stmt_execute($stmtAnalisisMB)) {
                // Éxito en la inserción
            } else {
                echo "Error al insertar en calidad_analisis para analisis_MB: " . mysqli_error($link);
            }
            mysqli_stmt_close($stmtAnalisisMB);
        }
    }

    mysqli_close($link);
} else {
    echo "Todos los campos son requeridos. ".$error;
}
?>
