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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


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
        <!-- Notificación de Éxito -->
    <div id="notification-success" class="Message Message--green" style="display: none;">
    <div class="Message-icon">
        <i class="fa fa-check"></i>
    </div>
    <div class="Message-body">
        <p id="notification-success-message">Este es un mensaje de notificación.</p>
    </div>
    <button class="Message-close js-messageClose"><i class="fa fa-times"></i></button>
    </div>

    <!-- Notificación de Error -->
    <div id="notification-error" class="Message Message--red" style="display: none;">
    <div class="Message-icon">
        <i class="fa fa-times"></i>
    </div>
    <div class="Message-body">
        <p id="notification-error-message">Este es un mensaje de notificación.</p>
    </div>
    <button class="Message-close js-messageClose"><i class="fa fa-times"></i></button>
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
    var notificationSuccess = document.getElementById('notification-success');
    var notificationError = document.getElementById('notification-error');
    
    if (isSuccess) {
        document.getElementById('notification-success-message').textContent = message;
        notificationSuccess.style.display = 'block';
        setTimeout(function() {
            notificationSuccess.style.display = 'none';
        }, 5000);
    } else {
        document.getElementById('notification-error-message').textContent = message;
        notificationError.style.display = 'block';
        setTimeout(function() {
            notificationError.style.display = 'none';
        }, 5000);
    }
}

// Añade un evento de clic para cerrar la notificación
document.querySelectorAll('.js-messageClose').forEach(function(button) {
    button.addEventListener('click', function() {
        this.parentElement.style.display = 'none';
    });
});

</script>

</body>

</html>