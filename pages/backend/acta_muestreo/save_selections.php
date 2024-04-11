<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Verificar si hay una sesión activa
if (!isset($_SESSION['usuario'])) {
    echo "No autorizado";
    exit;
}

// Verificar si se recibieron datos
if (!isset($_POST['selections'])) {
    echo "No se recibieron datos";
    exit;
}

// Datos enviados desde el frontend
$selections = $_POST['selections'];

// Preparar la consulta para insertar las selecciones
$query = "INSERT INTO save_selections (id_documento, radio_name, radio_value, checked) VALUES (?, ?, ?, ?)";

// Preparar la conexión y la consulta
$stmt = mysqli_prepare($link, $query);
if (!$stmt) {
    echo "Error al preparar la consulta: " . mysqli_error($link);
    exit;
}

foreach ($selections as $selection) {
    $id_documento = $selection['id_documento'];
    $name = $selection['name'];
    $value = $selection['value'];
    $checked = $selection['checked'] ? 1 : 0; // Convierte true/false a 1/0

    // Vincular parámetros a la consulta
    mysqli_stmt_bind_param($stmt, "issi", $id_documento, $name, $value, $checked);

    // Ejecutar la consulta
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        echo "Error al guardar la selección: " . mysqli_stmt_error($stmt);
        exit; // Si quieres que el script continúe intentando guardar otras selecciones a pesar de los errores, quita esta línea.
    }
}

// Cerrar la consulta preparada
mysqli_stmt_close($stmt);

// Cerrar la conexión a la base de datos
mysqli_close($link);

echo "Selecciones guardadas correctamente";
?>
