<?php
session_start();

// Verificar si la variable de sesión "usuario" no está establecida o está vacía.
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    // Redirigir al usuario a la página de inicio de sesión.
    header("Location: login.html");
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <!-- Asegúrate de incluir el CSS para estilizar tu formulario aquí -->
</head>
<body>
<!-- Contenido de configuracion.php -->
<div class="container mt-4">
    <h2>Configuración del Usuario</h2>
    <form id="formConfiguracion">
        <!-- Agrega los campos de configuración que necesitas -->
        <div class="form-group">
            <label for="email">Correo Electrónico:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña Nueva:</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <!-- Más campos según se requieran -->
        <button type="submit" class="btn btn-primary">Actualizar Configuración</button>
    </form>
</div>


</body>
</html>
