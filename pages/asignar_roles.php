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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Permisos</title>
    <!-- Incluir Bootstrap para estilos -->
    <link rel="stylesheet" href="../assets/css/calidad.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>

<body>
    <div class="container">
        <div class="form-container mt-5">
            <h2>Asignación de Rol y Permisos</h2>
            <form>
                <div class="form-group">
                    <label for="rolSelect">Rol:</label>
                    <select class="form-control" id="rolSelect" name="rol">
                        <option value="administrador">Administrador</option>
                        <option value="usuario">Usuario Calidad</option>
                        <option value="usuario">Usuario Supervisor</option>
                        <option value="usuario">Usuario </option>
                        <option value="invitado">Invitado</option>
                        <!-- Agrega más roles según sea necesario -->
                    </select>
                </div>
                <h3>Permisos</h3>
                <div class="form-group form-check">
                    <!-- Repetir esto para cada página con una casilla de verificación -->
                    <input type="checkbox" class="form-check-input" id="inicio" name="permisos[inicio]" value="1">
                    <label class="form-check-label" for="inicio">Inicio</label>
                </div>
                <!-- Continuar con el resto de páginas -->
                <!-- Ejemplo con algunas páginas -->
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="pagina_venta" name="permisos[pagina_venta]"
                        value="1">
                    <label class="form-check-label" for="pagina_venta">Página de Venta</label>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="pagina_entrega" name="permisos[pagina_entrega]"
                        value="1">
                    <label class="form-check-label" for="pagina_entrega">Página de Entrega</label>
                </div>
                <!-- Añadir más controles para otras páginas siguiendo el mismo patrón -->

                <!-- Botón de envío -->
                <button type="submit" class="btn btn-primary">Asignar Permisos</button>
            </form>
            <hr style="height: 100px;color: brown;">
        </div> 
    </div>
   
    <!-- Opcional: Incluir jQuery y Bootstrap JS para funcionalidad de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>