<?php
//archivo: pages\backend\acta_liberacion\acta_liberacion_guardayfirma.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON input
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, TRUE); // Convert JSON to array

    // Validate and sanitize the input data
    $id_analisis_externo = isset($input['id_analisis_externo']) ? intval($input['id_analisis_externo']) : null;
    $id_especificacion = isset($input['id_especificacion']) ? intval($input['id_especificacion']) : null;
    $id_producto = isset($input['id_producto']) ? intval($input['id_producto']) : null;
    $id_actaMuestreo = isset($input['id_actaMuestreo']) ? intval($input['id_actaMuestreo']) : null;
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
    $year = date("y");
    $month = date("m");
    $aux_anomes = $year . $month;
    // Insert/Update data in the database
    // Adjust the query according to your table structure and field names
    $query = "INSERT INTO calidad_acta_liberacion (id_analisis_externo, id_especificacion, id_producto, id_actaMuestreo, nro_acta, nro_registro, nro_version, fecha_acta_lib, tipo_producto, estado, obs1, obs2, obs3, obs4, cant_real_liberada, parte_ingreso, revision_estados, revision_liberacion, aux_anomes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare and execute the query
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("iiiiissiisssssssssi", $id_analisis_externo, $id_especificacion, $id_producto, $id_actaMuestreo, $nro_acta, $nro_registro, $nro_version, $fecha_acta_lib, $tipo_producto, $estado, $obs1, $obs2, $obs3, $obs4, $cant_real_liberada, $parte_ingreso, $docConformeResults, $revisionResults, $aux_anomes);
        if ($stmt->execute()) {
            echo json_encode(['success' => 'Data saved successfully']);
        } else {
            echo json_encode(['error' => 'Failed to save data: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Failed to prepare statement: ' . $mysqli->error]);
    }
    $mysqli->close();
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
