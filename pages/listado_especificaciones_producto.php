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
<link rel="stylesheet" href="../assets/css/Listados.css">

</head>

<body>
    <div class="form-container">
        <h1>Calidad / Listado Especificaciones de Productos</h1>
            <br>
            <br>
            <h2 class="section-title">Listado Especificaciones de Productos:</h2>
            <div class="estado-filtros">
                <label>               Filtrar por:</label>
                <button class="estado-filtro badge badge-success" onclick="filtrar_listado('Vigente')">Vigente</button>
                <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Pendiente de Revisión')">Pendiente de Revisión</button>
                <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Pendiente de Aprobación')">Pendiente de Aprobación</button>
                <button class="estado-filtro badge badge-dark" onclick="filtrar_listado('Especificación obsoleta')">Especificación obsoleta</button>
                <button class="estado-filtro badge badge-dark" onclick="filtrar_listado('Expirado')">Expirado</button>
                <button class="estado-filtro badge" onclick="filtrar_listado('')">Todos</button>
            </div>
            <br>
            <br>
            <div id="contenedor_listado">
                <table id="listado" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th></th> <!-- Columna vacía para botones o checkboxes -->
                            <th>Estado</th>
                            <th>Documento</th>
                            <th>Versión</th>
                            <th>Producto</th>
                            <th>Tipo producto</th>
                            <th>Concentración</th>
                            <th>Formato</th>
                            <th>Fecha expiración</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los datos dinámicos de la tabla se insertarán aquí -->
                    </tbody>
                </table>
            </div>
            <div id="phpData" 
                data-alerta="<?php echo isset($_SESSION['alerta']) ? $_SESSION['alerta'] : ''; ?>" 
                data-buscarEspecificacion="<?php echo isset($_SESSION['buscarEspecificacion']) ? $_SESSION['buscarEspecificacion'] : ''; ?>">
            </div>
    </div>
</body>

</html>

<script>
    
</script>
