<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Comprobar si el ID fue enviado por POST
if (!isset($_POST['id_actaMuestreo'])) {
    echo json_encode(['error' => 'No se proporcionó el ID necesario.']);
    exit;
}

$id_actaMuestreo = intval($_POST['id_actaMuestreo']);

// Conectar a la base de datos
$link = mysqli_connect($host, $usuario, $contrasena, $baseDatos);
if (!$link) {
    echo json_encode(['error' => 'Error de conexión: ' . mysqli_connect_error()]);
    exit;
}

// Preparar la consulta SQL
$query = "SELECT id, numero_registro, version_registro, numero_acta, version_acta, fecha_muestreo, muestreador, responsable, verificador, fecha_firma_muestreador, fecha_firma_responsable, fecha_firma_verificador, resultados_muestrador, resultados_responsable, pregunta5, pregunta6, pregunta7, pregunta8 FROM calidad_acta_muestreo WHERE id = ?";

if ($stmt = mysqli_prepare($link, $query)) {
    // Vincular el ID a la consulta
    mysqli_stmt_bind_param($stmt, "i", $id_actaMuestreo);
    
    // Ejecutar la consulta
    mysqli_stmt_execute($stmt);
    
    // Vincular las variables de resultado
    mysqli_stmt_bind_result($stmt, $id, $numero_registro, $version_registro, $numero_acta, $version_acta, $fecha_muestreo, $muestreador, $responsable, $verificador, $fecha_firma_muestreador, $fecha_firma_responsable, $fecha_firma_verificador, $resultados_muestrador, $resultados_responsable, $pregunta5, $pregunta6, $pregunta7, $pregunta8);
    
    // Obtener los resultados
    $resultados = [];
    while (mysqli_stmt_fetch($stmt)) {
        $resultados[] = [
            'id' => $id,
            'numero_registro' => $numero_registro,
            'version_registro' => $version_registro,
            'numero_acta' => $numero_acta,
            'version_acta' => $version_acta,
            'fecha_muestreo' => $fecha_muestreo,
            'muestreador' => $muestreador,
            'responsable' => $responsable,
            'verificador' => $verificador,
            'fecha_firma_muestreador' => $fecha_firma_muestreador,
            'fecha_firma_responsable' => $fecha_firma_responsable,
            'fecha_firma_verificador' => $fecha_firma_verificador,
            'resultados_muestrador' => $resultados_muestrador,
            'resultados_responsable' => $resultados_responsable,
            'pregunta5' => $pregunta5,
            'pregunta6' => $pregunta6,
            'pregunta7' => $pregunta7,
            'pregunta8' => $pregunta8
        ];
    }
    
    // Devolver los datos en formato JSON
    echo json_encode($resultados);
    
    // Cerrar la declaración
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['error' => 'Error al preparar la declaración: ' . mysqli_error($link)]);
}

// Cerrar la conexión
mysqli_close($link);
?>
