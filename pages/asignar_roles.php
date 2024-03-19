<?php
session_start();

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Permisos</title>
    <link rel="stylesheet" href="../assets/css/CrearUsuario.css">
    <link rel="stylesheet" href="../assets/css/Notificacion.css">
    <script src="../assets/js/notify.js"></script>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2>Asignación de Rol y Permisos</h2>
            <form id="formAsignacionPermisos" action="backend/permisos/asignar_permisosBE.php" method="POST">
                <div class="form-group">
                    <label for="rolSelect">Rol:</label>
                    <select class="select-styles" id="rolSelect" name="rol" style="width: 100%;">
                        <option value="administrador">Administrador</option>
                        <option value="usuario">Usuario Calidad</option>
                        <option value="usuario">Usuario Supervisor</option>
                        <option value="invitado">Invitado</option>
                    </select>
                </div>
                <h3>Permisos</h3>
                <!-- Controles de permisos similares al primer código -->
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="inicio" name="permisos[inicio]" value="1">
                    <label class="form-check-label" for="inicio">Inicio</label>
                </div>
                <!-- Otros permisos -->
                <button id="btnAdvertencia" class="btn btn-primary" style="width: 100%;">Asignar Permisos</button>
            </form>
        </div>
    </div>
    
    
</body>
<script>
    document.getElementById('btnAdvertencia').addEventListener('click', function () {
        $.notify('Este modulo sigue incompleto , te invitamos a seguir explorando la pagina!', 'warn');
    });

</script>
</html>
