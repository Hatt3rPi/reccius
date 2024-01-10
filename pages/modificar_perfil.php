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
    <link rel="stylesheet" href="../assets/css/ModificacionPerfil.css">
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2>Modificar Perfil</h2>
            <form id="formPerfil" action="backend/usuario/modificar_perfilBE.php" method="POST"
                enctype="multipart/form-data">

                <!-- Sección de Cambio de Contraseña -->
                <div class="seccion">

                    <h3>Cambio de Contraseña</h3>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="switch_contrasena" onclick="toggleInputs('switch_contrasena')">
                        <label class="form-check-label" for="switch_contrasena">Editar</label>
                    </div>
                    <br>
                        <div>
                            <label for="passwordActual">Contraseña Actual:</label>
                            <input class="switch_contrasena" type="password" id="passwordActual" name="passwordActual" disabled >
                        </div>
                        <div>
                            <label for="nuevaPassword">Nueva Contraseña:</label>
                            <input class="switch_contrasena" type="password" id="nuevaPassword" name="nuevaPassword" disabled >
                        </div>
                        <div>
                            <label for="confirmarPassword">Confirmar Nueva Contraseña:</label>
                            <input class="switch_contrasena" type="password" id="confirmarPassword" name="confirmarPassword" disabled >
                        </div>
                        
                </div>
                <div class="seccion">
                    <h3>Cambio de Foto de Perfil</h3>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="switch_foto" onclick="toggleInputs('switch_foto')">
                            <label class="form-check-label" for="switch_foto">Editar</label>
                        </div>
                        <br>
                        <div>
                            <label for="fotoPerfil">Foto de Perfil:</label>
                            <input  class="switch_foto" type="file" id="fotoPerfil" name="fotoPerfil" accept="image/*" disabled >
                        </div>
                </div>
                <div class="seccion">

                    <h3>Editar información de Usuario</h3>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="switch_info" onclick="toggleInputs('switch_info')">
                            <label class="form-check-label" for="switch_info">Editar</label>
                        </div>
                        <br>
                        <div>
                            <label for="cargo">Cargo:</label>
                            <input class="switch_info" type="text" id="cargo" name="cargo" disabled >
                        </div>
                        <div>
                            <label for="nombre">Nombre:</label>
                            <input class="switch_info" type="text" id="nombre" name="nombre" disabled>
                        </div>
                        <div>
                            <label for="nombre_corto">Nombre y Cargo Abreviado:</label>
                            <input class="switch_info" type="text" id="nombre_corto" name="nombre_corto" disabled>
                        </div>
                </div>
                <div class="seccion">

                    <h3>Certificado Nacional de Prestadores Individuales de Salud</h3>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="switch_certificado" onclick="toggleInputs('switch_certificado')">
                            <label class="form-check-label" for="switch_certificado">Editar</label>
                        </div>
                        <br>
                        <div>
                            <label for="certificado">Cargar Documento (extraído desde https://rnpi.superdesalud.gob.cl/):</label>
                            <input class="switch_certificado" type="file" id="certificado" name="certificado" accept="application/pdf" disabled>
                        </div>
                                            </div>          
                <input type="hidden" name="usuario" value="<?php echo $_SESSION['usuario']; ?>">      
                <button type="submit" name="modificarPerfil">Modificar Perfil</button>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('formPerfil').addEventListener('submit', function (event) {
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

        function toggleInputs(switchClass) {
    // Encuentra todos los campos de entrada con la misma clase que el switch
    var inputs = document.querySelectorAll('.' + switchClass);

    // Alternar el estado de cada campo
    inputs.forEach(function(input) {
        input.disabled = !input.disabled; // Cambiar el estado de deshabilitado
    });
}

    </script>

</body>

</html>