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
<link rel="stylesheet" href="../assets/css/ListadoEspecs.css">
</head>

<body>
    <div class="form-container">
        <h1>TRAZABILIDAD</h1>
            <br>
            <br>
            <h2 class="section-title">Listado actividad reciente:</h2>
            <div id="contenedor_trazabilidad">
                <table id="trazabilidad" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>Base</th>
                            <th>ID</th>
                            <th>Query</th>
                            <th>Parámetros</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los datos dinámicos de la tabla se insertarán aquí -->
                    </tbody>
                </table>
            </div>
    </div>
</body>

</html>
<script>

function cargaTrazabilidad() {
    var table = $('#trazabilidad').DataTable({
        "ajax": "./backend/index/index_superadminBE.php",
        language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                    },
        "columns": [
            { "data": "fecha", "title": "Fecha" }, // Asegúrate de que 'estado' es un campo en tu base de datos.
            { "data": "usuario", "title": "Usuario" },
            { "data": "accion", "title": "Acción" },
            { "data": "base", "title": "Base" },
            { "data": "identificador_base", "title": "ID" },
            { "data": "query", "title": "Query" },
            { "data": "parametros", "title": "Parámetros" }
        ],
        
    });
}

</script>
