<?php
require_once "/home/customw2/conexiones/config_reccius.php";

function obtenerFeriados() {
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://apis.digital.gob.cl/fl/feriados",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "cache-control: no-cache"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        return json_decode($response, true);
    }
}

function insertarActualizarFeriado($feriado) {
    global $conexion; // Asumiendo que $conexion es tu variable de conexión

    $query = "INSERT INTO feriados_chile (nombre, comentarios, fecha, irrenunciable, tipo) VALUES (?, ?, ?, ?, ?)
              ON DUPLICATE KEY UPDATE
              comentarios = VALUES(comentarios),
              irrenunciable = VALUES(irrenunciable),
              tipo = VALUES(tipo)";

    if ($stmt = $conexion->prepare($query)) {
        $stmt->bind_param("sssis", $feriado['nombre'], $feriado['comentarios'], $feriado['fecha'], $feriado['irrenunciable'], $feriado['tipo']);
        $resultado = $stmt->execute();
        $stmt->close();

        if ($resultado) {
            return $feriado['fecha']; // Devolver la fecha en caso de éxito
        }
    }
    return null; // Devolver nulo en caso de error
}


$feriados = obtenerFeriados();

if (!empty($feriados)) {
    $fechasProcesadas = [];
    foreach ($feriados as $feriado) {
        $fechaProcesada = insertarActualizarFeriado($feriado);
        if ($fechaProcesada !== null) {
            $fechasProcesadas[] = $fechaProcesada;
        }
    }
    if (!empty($fechasProcesadas)) {
        $error = "";
        registrarTrazabilidad('CRON', $_SERVER['PHP_SELF'], "Carga completa de feriados", 'feriados_chile', '', "Proceso de carga de feriados", $fechasProcesadas, true, $error);
    } else {
        $error = "";
        registrarTrazabilidad('CRON', $_SERVER['PHP_SELF'], "Sin feriados nuevos", 'feriados_chile', '', "Proceso de carga de feriados", $fechasProcesadas, true, $error);

    }
}
?>
