<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}
function limpiarDato($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}


if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Acceso denegado']);
    exit;
}
// Check if data was received
if (!isset($_POST['selections'])) {
    exit('No data received');
}

$selections = $_POST['selections'];

// Prepare the insert statement
$query = "INSERT INTO save_selections (id_documento, radio_name, radio_value, checked) VALUES (?, ?, ?, ?)";

// Prepare the connection and the statement
$stmt = mysqli_prepare($link, $query);

foreach ($selections as $selection) {
    $idDocumento = limpiarDato($selection['id_documento']);
    $name = limpiarDato($selection['name']);
    $value = limpiarDato($selection['value']);
    $checked = limpiarDato($selection['checked']);

    // Bind parameters to the query
    mysqli_stmt_bind_param($stmt, "issi", $idDocumento, $name, $value, $checked);
    
    // Execute the query
    $result = mysqli_stmt_execute($stmt);
    if (!$result) {
        // Handle any errors here
        exit('Error saving selection: ' . mysqli_stmt_error($stmt));
    }
}

// Close the prepared statement
mysqli_stmt_close($stmt);

// Close the database connection
mysqli_close($link);

echo 'Selections saved successfully';
?> 
