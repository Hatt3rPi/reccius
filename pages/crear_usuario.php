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
    <link rel="stylesheet" href="../assets/css/Notificacion.css">
    <script src="../assets/js/notificacion.js"></script>
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
                    <input type="text" class="form-control" id="empresa" name="empresa" style="width: 100%;">
                </div>
                <div class="form-group">
                    <label for="cargo">Cargo:</label>
                    <input type="text" class="form-control" id="cargo" name="cargo" style="width: 100%;">
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

    <script>
        $(document).ready(function() {
            $("#formCrearUsuario").submit(function(event) {
                event.preventDefault(); // Prevenir el envío estándar del formulario

                var formData = $(this).serialize(); // Obtener los datos del formulario

                $.ajax({
                    type: "POST",
                    url: "../pages/backend/usuario/crear_usuarioBE.php", // Ruta relativa correcta
                    data: formData,
                    success: function(response) {
                        var mensajesError = [
                            "Error: El usuario ya existe.",
                            "Error al ejecutar la consulta: ",
                            "Error: ",
                            "Error al crear usuario: "

                        ];
                        var mensajesAdvertencia = [
                            "Conexión fallida: ",
                            'Usuario creado, pero hubo un error al enviar el correo de restablecimiento.',
                            "Todos los campos son requeridos",
                            "Método no permitido"
                        ];
                        var mensajesExito = [
                            'Usuario creado exitosamente. Se ha enviado un correo electrónico para restablecer la contraseña.',
                        ];



                        if (mensajesAdvertencia.includes(response.trim())) {
                            mostrarNotificacion(response, "advertencia");
                        }else if (mensajesError.includes(response.trim())) {
                            mostrarNotificacion(response, "error");
                        }else {
                            // Este bloque se ejecutará si la respuesta no es una advertencia, éxito o error conocido
                            $("#formCrearUsuario")[0].reset();
                            mostrarNotificacion('Usuario creado exitosamente. Se ha enviado un correo electrónico para restablecer la contraseña.', "éxito");}
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Mostrar un mensaje de error
                        mostrarNotificacion("Error al procesar la solicitud: " + textStatus + ", " + errorThrown, "error");
                    }
                });
            });
        });



        // El otro script que ya tenías
        document.getElementById('correoElectronico').addEventListener('input', function() {
            var correo = this.value;
            var usuario = correo.split('@')[0];
            document.getElementById('usuario').value = usuario;
        });
    </script>

</body>

</html>