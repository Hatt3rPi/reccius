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
    // Asegúrate de recibir todos los campos necesarios
    if (isset($_POST['Tipo_Producto'], $_POST['Producto'], $_POST['Concentracion'], $_POST['Formato'], $_POST['ElaboradoPor'], $_POST['NumeroDocumento'], $_POST['FechaEdicion'], $_POST['Version'], $_POST['Vigencia'])) {

        $tipoProducto = limpiarDato($_POST['Tipo_Producto']);
        $producto = limpiarDato($_POST['Producto']);
        $concentracion=limpiarDato($_POST['Concentracion']);
        $formato=limpiarDato($_POST['Formato']);
        $elaboradoPor=limpiarDato($_POST['ElaboradoPor']);
        $numeroDocumento=limpiarDato($_POST['NumeroDocumento']); 
        $fechaEdicion=limpiarDato($_POST['FechaEdicion']);
        $version=limpiarDato($_POST['Version']);
        $vigencia= limpiarDato($_POST['Vigencia']);

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
        echo "Todos los campos son requeridos";
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
