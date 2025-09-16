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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1>TRAZABILIDAD</h1>
            <button id="btn-logs-pdf"
                    class="btn btn-primary"
                    title="Ver logs de diagnóstico de subida de PDF"
                    style="background-color: #007bff; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                <i class="fas fa-chart-line"></i> Logs PDF
            </button>
        </div>
            <br>
            <br>
            <h2 class="section-title">Listado actividad reciente:</h2>
            <div id="contenedor_trazabilidad">
                <table id="listado" class="table table-striped table-bordered" style="width:100%">
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
    var table = $('#listado').DataTable({
        "ajax": "./backend/index/index_superadminBE.php",
        "paging": false,
        order: [[0, 'desc']],
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

// Función para cargar logs PDF dinámicamente
$(document).ready(function() {
    $('#btn-logs-pdf').click(function(event) {
        event.preventDefault();

        // Ocultar contenido actual y mostrar spinner
        $('#dynamic-content').hide();
        $('#loading-spinner').show();

        console.log('Cargando dashboard PDF logs...');

        // Cargar el dashboard en el contenido dinámico
        $('#dynamic-content').load('check_pdf_upload_logs.php', function(response, status, xhr) {
            if (status == "error") {
                console.log("Error al cargar logs PDF: " + xhr.status + " " + xhr.statusText);
            } else {
                console.log('Dashboard PDF logs cargado exitosamente');
            }
            $('#loading-spinner').hide();
            $('#dynamic-content').show();
        });
    });
});

</script>
