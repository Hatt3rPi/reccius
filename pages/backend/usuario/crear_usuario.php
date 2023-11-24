<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
// Función para validar y limpiar datos
function limpiarDato($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Asegúrate de que todos los campos requeridos estén configurados
    if (isset($_POST['nombreUsuario']) && isset($_POST['correoElectronico']) && isset($_POST['usuario']) && isset($_POST['rol'])) {
        
        // Limpiar los datos para evitar inyecciones SQL
        $nombreUsuario = limpiarDato($_POST['nombreUsuario']);
        $correoElectronico = limpiarDato($_POST['correoElectronico']);
        $usuario = limpiarDato($_POST['usuario']);
        $rol = limpiarDato($_POST['rol']);

        try {
            $conn = new PDO("mysql:host=$servername;dbname=myDB", $username, $password);
            // Establecer el modo de error de PDO a excepción
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO usuarios (nombreUsuario, correoElectronico, usuario, rol) VALUES (:nombreUsuario, :correoElectronico, :usuario, :rol)";

            $stmt = mysqli_prepare($link, "SELECT contrasena FROM usuarios WHERE usuario = ?");
            mysqli_stmt_bind_param($stmt, "s", $usuario);
            mysqli_stmt_execute($stmt);

            echo "Usuario creado exitosamente";
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        $conn = null;
    } else {
        echo "Todos los campos son requeridos";
    }
} else {
    echo "Método no permitido";
}
?>