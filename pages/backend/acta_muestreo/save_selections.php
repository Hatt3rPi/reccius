<?php
// Start the session and require database configuration
session_start();
require_once "path_to_your_database_configuration.php"; // Replace with your actual path

// Check for active session
if (!isset($_SESSION['usuario'])) {
    exit('Not Authorized');
}

// Check if data was received
if (!isset($_POST['selections'])) {
    exit('No data received');
}

$selections = $_POST['selections'];

// Database connection
$link = mysqli_connect("your_host", "your_username", "your_password", "your_database"); // Replace with your database connection details

// Prepare the insert statement
$query = "INSERT INTO save_selections (id_documento, radio_name, radio_value, checked) VALUES (?, ?, ?, ?)";

// Prepare the connection and the statement
$stmt = mysqli_prepare($link, $query);

foreach ($selections as $selection) {
    // Bind parameters to the query
    mysqli_stmt_bind_param($stmt, "issi", $selection['id_documento'], $selection['name'], $selection['value'], $selection['checked']);
    
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
