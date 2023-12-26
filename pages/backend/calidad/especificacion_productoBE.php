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
function insertarOpcionSiNoExiste($link, $categoria, $nuevoValor) {
    // Limpiar y preparar el valor
    $nuevoValor = limpiarDato($nuevoValor);

    // Comprobar si ya existe
    $queryVerificar = "SELECT COUNT(*) FROM calidad_opciones_desplegables WHERE nombre_opcion = ? AND categoria = ?";
    $stmtVerificar = mysqli_prepare($link, $queryVerificar);
    mysqli_stmt_bind_param($stmtVerificar, "ss", $nuevoValor, $categoria);
    mysqli_stmt_execute($stmtVerificar);
    mysqli_stmt_bind_result($stmtVerificar, $cantidad);
    mysqli_stmt_fetch($stmtVerificar);
    mysqli_stmt_close($stmtVerificar);

    // Insertar si no existe
    if ($cantidad == 0) {
        $queryInsertar = "INSERT INTO calidad_opciones_desplegables (categoria, nombre_opcion) VALUES (?, ?)";
        $stmtInsertar = mysqli_prepare($link, $queryInsertar);
        mysqli_stmt_bind_param($stmtInsertar, "ss", $categoria, $nuevoValor);
        $ingresaOtro=mysqli_stmt_execute($stmtInsertar);
        
        //in trazabilidad
            $resultado = $ingresaOtro ? 1 : 0; // Suponiendo que 1 es éxito y 0 es fracaso
            $error = $ingresaOtro ? null: "Error al ejecutar la consulta: " . mysqli_stmt_error($stmtInsertar);
            registrarTrazabilidad($_SESSION['usuario'], $_SERVER['PHP_SELF'], 'CALIDAD - OPCIONES DESPLEGABLES - inserta nuevo '.$categoria , 'calidad_opciones_desplegables',  mysqli_insert_id($link), $queryInsertar, [$categoria, $nuevoValor], $resultado, $error);
        // out trazabidad
        mysqli_stmt_close($stmtInsertar);
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Imprime los datos recibidos para propósitos de depuración
    //echo "<pre>";
    //print_r($_POST);
    registrarTrazabilidad($_SESSION['usuario'], $_SERVER['PHP_SELF'], 'INTENTO DE CARGA', 'TEST',  1, '', $_POST, '', '');
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
        'periodosVigencia' => 'Vigencia'
    ];
    if (isset($_POST['formato']) && $_POST['formato'] == 'Otro' && empty($_POST['otroFormato'])) {
        $error .= "Otro Formato, ";
    }
    if (isset($_POST['analisisFQ']) && is_array($_POST['analisisFQ'])) {
        foreach ($_POST['analisisFQ'] as $analisis) {
            if ($analisis['descripcion_analisis'] == 'Otro' && empty($analisis['otrodescripcion_analisis'])) {
                $error .= "Otra Descripción Análisis FQ, ";
            }
            if ($analisis['metodologia'] == 'Otro' && empty($analisis['otrometodologia'])) {
                $error .= "Otra Metodología Análisis FQ, ";
            }
        }
    }
    if (isset($_POST['analisisMB']) && is_array($_POST['analisisMB'])) {
        foreach ($_POST['analisisMB'] as $analisis) {
            if ($analisis['descripcion_analisis'] == 'Otro' && empty($analisis['otrodescripcion_analisis'])) {
                $error .= "Otra Descripción Análisis MB, ";
            }
            if ($analisis['metodologia'] == 'Otro' && empty($analisis['otrometodologia'])) {
                $error .= "Otra Metodología Análisis MB, ";
            }
        }
    }
    if (isset($_POST['Tipo_Producto']) && $_POST['Tipo_Producto'] == 'Otro' && empty($_POST['otroTipo_Producto'])) {
        $error .= "Otro Tipo de Producto, ";
    }
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
        $formato = !empty($_POST['formato']) ? limpiarDato($_POST['formato']) : (isset($_POST['otroFormato']) ? limpiarDato($_POST['otroFormato']) : '');
        $elaboradoPor = limpiarDato($_POST['elaboradoPor']);
        $numeroDocumento = limpiarDato($_POST['documento']);
        $numeroProducto = limpiarDato($_POST['numeroProducto']);
        $fechaEdicion = limpiarDato($_POST['fechaEdicion']);
        $version = limpiarDato($_POST['version']);
        $vigencia = limpiarDato($_POST['periodosVigencia']);

        // Preparar sentencia para insertar en calidad_productos
        $query="INSERT INTO calidad_productos (nombre_producto, tipo_producto, concentracion, formato, elaborado_por, documento_ingreso, identificador_producto) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($link, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssssss", $producto, $tipoProducto, $concentracion, $formato, $elaboradoPor, $numeroDocumento, $numeroProducto);
            $crea_producto=mysqli_stmt_execute($stmt);
            $idProducto = mysqli_insert_id($link);
            //in trazabilidad
            $resultado = $crea_producto ? 1 : 0; // Suponiendo que 1 es éxito y 0 es fracaso
            $error = $crea_producto ? null :  "Error al ejecutar la consulta: " . mysqli_stmt_error($stmt);
            registrarTrazabilidad($_SESSION['usuario'], $_SERVER['PHP_SELF'], 'CALIDAD - crea producto', 'calidad_productos',  $idProducto, $query, [$producto, $tipoProducto, $concentracion, $formato, $elaboradoPor, $numeroDocumento, $numeroProducto], $resultado, $error);
            // out trazabidad
            if ($crea_producto) {
                
                $fechaEdicionDateTime = new DateTime($fechaEdicion);
                $fechaEdicionDateTime->modify("+$vigencia years");
                $fechaExpiracion = $fechaEdicionDateTime->format('Y-m-d');
                $tipoAbreviatura = '';
                switch ($tipoProducto) {
                    case 'Material Envase y Empaque':
                        $tipoAbreviatura = 'EME';
                        break;
                    case 'Materia Prima':
                        $tipoAbreviatura = 'EMP';
                        break;
                    case 'Producto Terminado':
                        $tipoAbreviatura = 'EPT';
                        break;
                    case 'Insumo':
                        $tipoAbreviatura = 'EIN';
                        break;
                }
                $numeroDocumentoFormateado = 'DCAL-CC-' . $tipoAbreviatura . '-' . $numeroDocumento;
                $query_especificacion="INSERT INTO calidad_especificacion_productos (id_producto, documento, fecha_edicion, version, fecha_expiracion, vigencia) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt2 = mysqli_prepare($link, $query_especificacion);
                if ($stmt2) {
                    mysqli_stmt_bind_param($stmt2, "issssi", $idProducto, $numeroDocumentoFormateado, $fechaEdicion, $version, $fechaExpiracion, $vigencia);
                    $crea_especificacion=mysqli_stmt_execute($stmt2);
                    $idEspecificacion = mysqli_insert_id($link); // ID de la especificación insertada
                    //in trazabilidad
                        $resultado = $crea_especificacion ? 1 : 0; // Suponiendo que 1 es éxito y 0 es fracaso
                        $error = $resultado ? null : "Error al ejecutar la consulta: " . mysqli_stmt_error($stmtAnalisisFQ);
                        registrarTrazabilidad($_SESSION['usuario'], $_SERVER['PHP_SELF'], 'CALIDAD - crea especificación', 'calidad_especificacion_productos',  $idEspecificacion, $query_especificacion, [$idProducto, $numeroDocumentoFormateado, $fechaEdicion, $version, $fechaExpiracion, $vigencia], $resultado, $error);
                    // out trazabidad

                    if ($crea_especificacion) {
                        //echo "Especificación de producto creada con éxito.";
                        $exito=true;
                        
                        $mensaje = "Especificación de producto creada con éxito.";
                        if (isset($_POST['analisisFQ']) && is_array($_POST['analisisFQ'])) {
                            foreach ($_POST['analisisFQ'] as $analisis) {
                                $crea_analisis='';
                                // Asegúrate de que estas claves coincidan con las de tu array
                                $descripcion_analisis = $analisis['descripcion_analisis'] === 'Otro' ? limpiarDato($analisis['otrodescripcion_analisis']) : limpiarDato($analisis['descripcion_analisis']);
                                $metodologia = $analisis['metodologia'] === 'Otro' ? limpiarDato($analisis['otrometodologia']) : limpiarDato($analisis['metodologia']);
                                $criterios_aceptacion = limpiarDato($analisis['criterio']);
                                $tipo='analisis_FQ';
                                $query_analisis="INSERT INTO calidad_analisis (id_especificacion_producto, tipo_analisis, descripcion_analisis, metodologia, criterios_aceptacion) VALUES (?, ?, ?, ?, ?)";
                                $stmtAnalisisFQ = mysqli_prepare($link, $query_analisis);
                                mysqli_stmt_bind_param($stmtAnalisisFQ, "issss", $idEspecificacion, $tipo, $descripcion_analisis, $metodologia, $criterios_aceptacion);
                                $crea_analisis=mysqli_stmt_execute($stmtAnalisisFQ);
                                $id_analisis=mysqli_insert_id($link);
                                //in trazabilidad
                                    $resultado = $crea_analisis ? 1 : 0; // Suponiendo que 1 es éxito y 0 es fracaso
                                    $error = $resultado ? null : "Error al ejecutar la consulta: " . mysqli_stmt_error($stmtAnalisisFQ);
                                    registrarTrazabilidad($_SESSION['usuario'], $_SERVER['PHP_SELF'], 'CALIDAD - crea analisis FQ', 'calidad_analisis',  $id_analisis, $query_analisis, [$idEspecificacion, $tipo, $descripcion_analisis, $metodologia, $criterios_aceptacion], $resultado, $error);
                                // out trazabidad
                                if ($crea_analisis) {
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
                                $descripcion_analisis = $analisis['descripcion_analisis'] === 'Otro' ? limpiarDato($analisis['otrootrodescripcion_analisis']) : limpiarDato($analisis['descripcion_analisis']);
                                $metodologia = $analisis['metodologia'] === 'Otro' ? limpiarDato($analisis['otrometodologia']) : limpiarDato($analisis['metodologia']);
                                $criterios_aceptacion = limpiarDato($analisis['criterio']);
                                $tipo='analisis_MB';
                                $query_analisis="INSERT INTO calidad_analisis (id_especificacion_producto, tipo_analisis, descripcion_analisis, metodologia, criterios_aceptacion) VALUES (?, ?, ?, ?, ?)";
                                $stmtAnalisisMB = mysqli_prepare($link, $query_analisis);
                                mysqli_stmt_bind_param($stmtAnalisisMB, "issss", $idEspecificacion, $tipo, $descripcion_analisis, $metodologia, $criterios_aceptacion);
                                $crea_analisis = mysqli_stmt_execute($stmtAnalisisMB);
                                $id_analisis=mysqli_insert_id($link);
                                //in trazabilidad
                                    $resultado = $crea_analisis ? 1 : 0; // Suponiendo que 1 es éxito y 0 es fracaso
                                    $error = $crea_analisis ? null: "Error al ejecutar la consulta: " . mysqli_stmt_error($stmtAnalisisMB);
                                    registrarTrazabilidad($_SESSION['usuario'], $_SERVER['PHP_SELF'], 'CALIDAD - crea analisis MB', 'calidad_analisis',  $id_analisis, $query_analisis, [$idEspecificacion, $tipo, $descripcion_analisis, $metodologia, $criterios_aceptacion], $resultado, $error);
                                // out trazabidad
                                if ($crea_analisis) {
                                    // Éxito en la inserción

                                    if ($_POST['formato'] == 'Otro' && !empty($_POST['otroFormato'])) {
                                        insertarOpcionSiNoExiste($link, 'Formato', $_POST['otroFormato']);
                                    }
                                    if ($_POST['Tipo_Producto'] == 'Otro' && !empty($_POST['otroTipo_Producto'])) {
                                        insertarOpcionSiNoExiste($link, 'Tipo_Producto', $_POST['otroTipo_Producto']);
                                    }
                                    if ($_POST['descripcion_analisis'] == 'Otro' && !empty($_POST['otrodescripcion_analisis'])) {
                                        insertarOpcionSiNoExiste($link, 'descripcion_analisis', $_POST['otrodescripcion_analisis']);
                                    }
                                    if ($_POST['metodologia'] == 'Otro' && !empty($_POST['otrometodologia'])) {
                                        insertarOpcionSiNoExiste($link, 'metodologia', $_POST['otrometodologia']);
                                    }
                                    // Similar para 'Metodología', 'Análisis FQ' y 'Análisis MB'

                                } else {
                                    $mensaje = "Error al insertar en calidad_analisis para analisis_MB: " . mysqli_error($link);
                                }
                                mysqli_stmt_close($stmtAnalisisMB);
                            }
                        }


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
