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
    if (isset($_POST['nombreUsuario'], $_POST['correoElectronico'], $_POST['usuario'], $_POST['rol'])) {
        
        $nombreUsuario = limpiarDato($_POST['nombreUsuario']);
        $correoElectronico = limpiarDato($_POST['correoElectronico']);
        $usuario = limpiarDato($_POST['usuario']);
        $rol = limpiarDato($_POST['rol']);

        // Crear conexión
        $link = mysqli_connect("localhost", "tu_usuario", "tu_contraseña", "nombre_de_tu_base_de_datos");

        // Verificar la conexión
        if (!$link) {
            die("Conexión fallida: " . mysqli_connect_error());
        }

        // Verificar si el usuario ya existe
        $stmt = mysqli_prepare($link, "SELECT id FROM usuarios WHERE usuario = ?");
        mysqli_stmt_bind_param($stmt, "s", $usuario);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            echo "Error: El usuario ya existe.";
        } else {
            // Insertar el nuevo usuario
            $insert = mysqli_prepare($link, "INSERT INTO usuarios (nombreUsuario, correoElectronico, usuario, rol) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($insert, "ssss", $nombreUsuario, $correoElectronico, $usuario, $rol);
            if (mysqli_stmt_execute($insert)) {
                echo "Usuario creado exitosamente";
            } else {
                echo "Error al crear usuario: " . mysqli_error($link);
            }
        }

        mysqli_stmt_close($stmt);
        mysqli_close($link);
    } else {
        echo "Todos los campos son requeridos";
    }
} else {
    echo "Método no permitido";
}
?>
