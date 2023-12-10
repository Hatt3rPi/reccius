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
    }
    
    
    if (isset($_POST['Tipo_Producto'], $_POST['producto'], $_POST['concentracion'], $_POST['formato'], $_POST['elaboradoPor'], $_POST['documento'], $_POST['fechaEdicion'], $_POST['version'], $_POST['vigencia'])) {

        $tipoProducto = limpiarDato($_POST['Tipo_Producto']);
        $producto = limpiarDato($_POST['producto']);
        $concentracion=limpiarDato($_POST['concentracion']);
        $formato=limpiarDato($_POST['formato']);
        $elaboradoPor=limpiarDato($_POST['elaboradoPor']);
        $numeroDocumento=limpiarDato($_POST['documento']); 
        $fechaEdicion=limpiarDato($_POST['fechaEdicion']);
        $version=limpiarDato($_POST['version']);
        $vigencia= limpiarDato($_POST['vigencia']);

        // Insertar en la tabla calidad_productos
        $stmt = mysqli_prepare($link, "INSERT INTO calidad_productos (nombre_producto, tipo_producto, concentracion, formato,  elaborado_por, documento_ingreso) VALUES ( ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssssss", $producto, $tipoProducto, $concentracion, $formato, $elaboradoPor, $numeroDocumento);

        if (mysqli_stmt_execute($stmt)) {
            $idProducto = mysqli_insert_id($link);

            // Insertar en la tabla calidad_especificacion_productos
            $stmt2 = mysqli_prepare($link, "INSERT INTO calidad_especificacion_productos (id_producto, documento, fecha_edicion, version, vigencia) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt2, "issss", $idProducto, $numeroDocumento, $fechaEdicion, $version, $vigencia);
            mysqli_stmt_execute($stmt2);

            // Aquí puedes insertar en calidad_analisis si es necesario
            // ...

            echo "Especificación de producto creada con éxito.";
        } else {
            echo "Error al crear la especificación de producto: " . mysqli_error($link);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($link);
    } else {
        echo "Todos los campos son requeridos. ".$error;
    };
    $idEspecificacion = mysqli_insert_id($link); // ID de la especificación insertada

    // Procesar datos de analisisFQ
    if (isset($_POST['analisisFQ']) && is_array($_POST['analisisFQ'])) {
        foreach ($_POST['analisisFQ'] as $analisis) {
            // Aquí debes extraer y limpiar cada dato del análisis como metodologia, descripcion_analisis, etc.
            // Por ejemplo:
            $descripcion_analisis = limpiarDato($analisis['descripcion']);
            $metodologia = limpiarDato($analisis['metodologia']);
            $criterios_aceptacion = limpiarDato($analisis['criterio']);

            $stmtAnalisisFQ = mysqli_prepare($link, "INSERT INTO calidad_analisis (id_especificacion_producto, tipo_analisis, descripcion_analisis, metodologia, criterios_aceptacion) VALUES (?, 'analisis_FQ', ?, ?, ?)");
            mysqli_stmt_bind_param($stmtAnalisisFQ, "isss", $idEspecificacion, $descripcion_analisis, $metodologia, $criterios_aceptacion);
            mysqli_stmt_execute($stmtAnalisisFQ);
            mysqli_stmt_close($stmtAnalisisFQ);
        }
    }
    // Procesar datos de analisisMB
    if (isset($_POST['analisisMB']) && is_array($_POST['analisisMB'])) {
        foreach ($_POST['analisisMB'] as $analisis) {
            // Aquí debes extraer y limpiar cada dato del análisis como metodologia, descripcion_analisis, etc.
            // Por ejemplo:
            $descripcion_analisis = limpiarDato($analisis['descripcion']);
            $metodologia = limpiarDato($analisis['metodologia']);
            $criterios_aceptacion = limpiarDato($analisis['criterio']);

            $stmtAnalisisMB = mysqli_prepare($link, "INSERT INTO calidad_analisis (id_especificacion_producto, tipo_analisis, descripcion_analisis, metodologia, criterios_aceptacion) VALUES (?, 'analisis_MB', ?, ?, ?)");
            mysqli_stmt_bind_param($stmtAnalisisMB, "isss", $idEspecificacion, $descripcion_analisis, $metodologia, $criterios_aceptacion);
            mysqli_stmt_execute($stmtAnalisisMB);
            mysqli_stmt_close($stmtAnalisisMB);
        }
    }
} else {
    echo "Método no permitido";
}
?>
