<?php

session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Asegúrate de que el método sea POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesa el formulario

    $idEspecificacion = isset($_POST['id_especificacion']) ? intval($_POST['id_especificacion']) : null;
    $numeroRegistro = limpiarDato($_POST['numero_registro']);
    $numeroActa = limpiarDato($_POST['numero_acta']);
    $fechaMuestreo = limpiarDato($_POST['fecha_muestreo']);
    $lote = limpiarDato($_POST['lote']);
    $tamanoLote = limpiarDato($_POST['tamano_lote']);
    $codigoMastersoft = limpiarDato($_POST['codigo_mastersoft']);
    $condicionAlmacenamiento = limpiarDato($_POST['condicion_almacenamiento']);
    $cantidadMuestra = limpiarDato($_POST['cantidad_muestra']);
    $cantidadContraMuestra = limpiarDato($_POST['cantidad_contra_muestra']);
    $tipoAnalisis = limpiarDato($_POST['tipo_analisis']);

    // Insertar un nuevo acta de muestreo
    $resultado = insertarActaMuestreo($link, $idEspecificacion, $numeroRegistro, $numeroActa, $fechaMuestreo, $lote, $tamanoLote, $codigoMastersoft, $condicionAlmacenamiento, $cantidadMuestra, $cantidadContraMuestra, $tipoAnalisis);

    // Devuelve una respuesta al FE
    echo json_encode($resultado);

} else {
    // Método no permitido
    echo json_encode(["exito" => false, "mensaje" => "Método inválido"]);
}

function limpiarDato($dato) {
    // Lógica para limpiar y sanear el dato
    return htmlspecialchars(stripslashes(trim($dato)));
}

function insertarActaMuestreo($link, $idEspecificacion, $numeroRegistro, $numeroActa, $fechaMuestreo, $lote, $tamanoLote, $codigoMastersoft, $condicionAlmacenamiento, $cantidadMuestra, $cantidadContraMuestra, $tipoAnalisis) {
    $query = "INSERT INTO calidad_acta_muestreo (id_especificacion, numero_registro, numero_acta, fecha_muestreo, lote, tamano_lote, codigo_mastersoft, condicion_almacenamiento, cantidad_muestra, cantidad_contra_muestra, tipo_analisis) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "issssssssss", $idEspecificacion, $numeroRegistro, $numeroActa, $fechaMuestreo, $lote, $tamanoLote, $codigoMastersoft, $condicionAlmacenamiento, $cantidadMuestra, $cantidadContraMuestra, $tipoAnalisis);

    $exito = mysqli_stmt_execute($stmt);
    $idActaMuestreo = $exito ? mysqli_insert_id($link) : 0;
    mysqli_stmt_close($stmt);

    registrarTrazabilidad(
        $_SESSION['usuario'], 
        $_SERVER['PHP_SELF'], 
        'Inserción de acta de muestreo', 
        'calidad_acta_muestreo', 
        $idActaMuestreo, 
        $query, 
        [$idEspecificacion, $numeroRegistro, $numeroActa, $fechaMuestreo, $lote, $tamanoLote, $codigoMastersoft, $condicionAlmacenamiento, $cantidadMuestra, $cantidadContraMuestra, $tipoAnalisis], 
        $exito ? 1 : 0, 
        $exito ? null : mysqli_error($link)
    );
    if ($exito) {
        return ["exito" => true, "mensaje" => "Acta de muestreo creada con éxito", "idActaMuestreo" => $idActaMuestreo];
    } else {
        return ["exito" => false, "mensaje" => "Error al crear acta de muestreo: " . mysqli_error($link)];
    }
}

?>
