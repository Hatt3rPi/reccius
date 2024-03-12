<?php
session_start();
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}
require_once "/home/customw2/conexiones/config_reccius.php";
$query = "SELECT usuario, nombre FROM usuarios ORDER BY nombre";
$result = mysqli_query($link, $query);

$usuarios = [];
while ($row = mysqli_fetch_assoc($result)) {
    $usuarios[] = $row;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
<script src="./backend/tareas/listado_tareas.js" type="module"></script>
<link rel="stylesheet" href="../assets/css/Listados.css">
    <!-- Incluye aquí otros archivos necesarios (jQuery, DataTables CSS, FontAwesome, etc.) -->
</head>
<body>
    <div class="form-container">
        <h1>Listado de Tareas</h1>
        <div class="estado-filtros">
                <label>               Filtrar por:</label>
                <button class="estado-filtro badge resaltar" onclick="filtrar_listado_usuario('')">Mis Tareas</button>
                <button class="estado-filtro badge badge-primary" onclick="filtrar_listado_estado('Activo')">Activo</button>
                <button class="estado-filtro badge badge-warning" onclick="filtrar_listado_estado('Fecha de Vencimiento cercano')">Fecha de Vencimiento cercano</button>
                <button class="estado-filtro badge badge-danger" onclick="filtrar_listado_estado('Atrasado')">Atrasado</button>
                <button class="estado-filtro badge badge-dark" onclick="filtrar_listado_estado('Finalizado')">Finalizado</button>
                <button class="estado-filtro badge" onclick="filtrar_listado_estado('')">Todos</button>
            </div>
            <br>
            <br>
        <div id="contenedor_tareas">
            <table id="listado" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Descripción</th>
                        <th>Usuario Creador</th>
                        <th>Usuario Ejecutor</th>
                        <th>Fecha Ingreso</th>
                        <th>Fecha Vencimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los datos dinámicos de la tabla se insertarán aquí -->
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal para Cambiar Usuario -->
    <div id="modalCambiarUsuario" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Re-asignar Tarea</h2>
            <form id="formCambiarUsuario">
                <label for="">Ejecutor original:</label>
                <input id="ejecutorOriginal" name="ejecutorOriginal">
                <br>
                <label for="usuarioNuevo">Re-asignar tarea a:</label>
                <select name="usuarioNuevo" id="usuarioNuevo">
                    <option value="">Selecciona el nuevo ejecutor</option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?php echo htmlspecialchars($usuario['usuario']); ?>">
                            <?php echo htmlspecialchars($usuario['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br>
                <input type="hidden" id="idTarea" name="idTarea">
                <button type="submit">Aceptar</button>
            </form>
        </div>
    </div>

    
</body>
</html>
