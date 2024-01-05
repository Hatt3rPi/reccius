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
    <link rel="stylesheet" href="../assets/css/CrearUsuario.css">

</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2>Formulario para Crear Usuario</h2>
            <form id="formCrearUsuario" action="backend/usuario/crear_usuarioBE.php" method="POST">
                <div class="form-group">
                    <label for="nombreUsuario">Nombre:</label>
                    <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" required style="width: 100%;">
                </div>
                <div class="form-group">
                    <label for="correoElectronico">Correo Institucional:</label>
                    <input type="email" class="form-control" id="correoElectronico" name="correoElectronico" required style="width: 100%;">
                </div>
                <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" readonly style="width: 100%;">
                </div>
                <div class="form-group">
                    <label for="usuario">Empresa:</label>
                    <input type="text" class="form-control" id="empresa" name="empresa"  style="width: 100%;">
                </div>
                <div class="form-group">
                    <label for="cargo">Cargo:</label>
                    <input type="text" class="form-control" id="cargo" name="cargo"  style="width: 100%;">
                </div>
                <div class="form-group">
                    <label for="rol">Rol:</label>
                    <select class="select-styles" id="rol" name="rol" style="width: 100%;">
                        <!-- Las opciones se cargarán mediante JavaScript -->
                    </select>
                </div>
                <br>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Crear Usuario</button>
            </form>
        </div>
    </div>
    <div id="notification" class="notification-container" style="display: none;">
        <p id="notification-message">Este es un mensaje de notificación.</p>
    </div>

    <script>
    $(document).ready(function(){
    $("#formCrearUsuario").submit(function(event){
        event.preventDefault(); // Prevenir el envío estándar del formulario

        var formData = $(this).serialize(); // Obtener los datos del formulario
        
        $.ajax({
            type: "POST",
            url: "../pages/backend/usuario/crear_usuarioBE.php", // Ruta relativa correcta
            data: formData,
            success: function(response){
                // Mostrar la respuesta como una notificación
                showNotification(response, true);
            },
            error: function(jqXHR, textStatus, errorThrown){
                // Mostrar un mensaje de error
                showNotification("Error al procesar la solicitud: " + textStatus + ", " + errorThrown, false);
            }
        });
    });
});

// Función para mostrar la notificación
function showNotification(message, isSuccess) {
    var notification = document.getElementById('notification');
    var messageElement = document.getElementById('notification-message');
    messageElement.textContent = message;
    
    // Añadir la clase para el estilo de éxito o error
    notification.className = isSuccess ? 'notification-container success' : 'notification-container error';
    
    // Mostrar la notificación
    notification.style.display = 'block';
    
    // Ocultar la notificación después de 5 segundos
    setTimeout(function() {
        notification.style.display = 'none';
    }, 10000);
}


    // El otro script que ya tenías
    document.getElementById('correoElectronico').addEventListener('input', function () {
        var correo = this.value;
        var usuario = correo.split('@')[0];
        document.getElementById('usuario').value = usuario;
    });
</script>

</body>

</html>