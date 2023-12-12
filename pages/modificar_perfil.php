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
    <title>Modificar Perfil</title>
    <!-- Añade aquí tus estilos o referencias a CSS -->
    <link rel="stylesheet" href="../assets/css/calidad.css">
</head>
<body>
    <h2>Modificar Perfil</h2>
    <form id="formPerfil" action="backend/usuario/modificar_perfilBE.php" method="POST" enctype="multipart/form-data">
        
        <!-- Sección de Cambio de Contraseña -->
        <fieldset>
            <legend>Cambio de Contraseña</legend>
            <div>
                <label for="passwordActual">Contraseña Actual:</label>
                <input type="password" id="passwordActual" name="passwordActual">
            </div>
            <div>
                <label for="nuevaPassword">Nueva Contraseña:</label>
                <input type="password" id="nuevaPassword" name="nuevaPassword">
            </div>
            <div>
                <label for="confirmarPassword">Confirmar Nueva Contraseña:</label>
                <input type="password" id="confirmarPassword" name="confirmarPassword">
            </div>
            <input type="hidden" name="usuario" value="<?php echo $_SESSION['usuario']; ?>">

        </fieldset>

        <!-- Sección de Cambio de Foto de Perfil -->
        <fieldset>
            <legend>Cambio de Foto de Perfil</legend>
            <div>
                <label for="fotoPerfil">Foto de Perfil:</label>
                <input type="file" id="fotoPerfil" name="fotoPerfil" accept="image/*">
            </div>
        </fieldset>

        <button type="submit" name="modificarPerfil">Modificar Perfil</button>
    </form>

    <script>
        document.getElementById('formPerfil').addEventListener('submit', function(event) {
            var password = document.getElementById('nuevaPassword').value;
            var confirmPassword = document.getElementById('confirmarPassword').value;

            // Validar que las contraseñas coincidan
            if (password !== confirmPassword) {
                alert('Las contraseñas no coinciden.');
                event.preventDefault(); // Prevenir el envío del formulario
                return false;
            }

            // Validación adicional aquí si es necesario
        });
    </script>
</body>
</html>
