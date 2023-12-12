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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/datetime-moment.js"></script>
<head>
    <meta charset="UTF-8">
    <title>Listado Especificaciones de Productos</title>
    

</head>

<body>
    <div class="form-container">
        <h1>Calidad / Listado Especificaciones de Productos</h1>
            <br>
            <br>
            <h2 class="section-title">Listado Especificaciones de Productosv:</h2>
            <div id="contenedor_listadoEspecProductos">
                <table id="listadoEspecProductos" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th></th> <!-- Columna vacía para botones o checkboxes -->
                            <th>Estado</th>
                            <th>Documento</th>
                            <th>Producto</th>
                            <th>Tipo producto</th>
                            <th>Concentración</th>
                            <th>Formato</th>
                            <th>Producido por</th>
                            <th>Fecha expiración</th>
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

$(document).ready(function() {
    var table = $('#tablaEspecificaciones').DataTable({
        "ajax": "../backend/calidad/listado_especificaciones_producto.php",
        language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                    },
        "columns": [
            {
                "className": 'details-control',
                "orderable": false,
                "data": null,
                "defaultContent": '<i class="fas fa-search-plus"></i>',
                "width": "5%"
            },
            { "data": "estado", "title": "Estado" }, // Asegúrate de que 'estado' es un campo en tu base de datos.
            { "data": "documento", "title": "Documento" },
            { "data": "producto", "title": "Producto" },
            { "data": "tipo_producto", "title": "Tipo producto" },
            { "data": "concentracion", "title": "Concentración" },
            { "data": "formato", "title": "Formato" },
            { "data": "elaborado_por", "title": "Producido por" },
            { "data": "fecha_expiracion", "title": "Fecha expiración" }
        ],
        // ... otras opciones de DataTables ...
    });

    // Event listener para el botón de detalles
    $('#tablaEspecificaciones tbody').on('click', 'td.details-control', function() {
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            // Esta fila ya está abierta - ciérrala
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Abre esta fila
            row.child(format(row.data())).show(); // Aquí llamas a la función que formatea el contenido expandido
            tr.addClass('shown');
        }
    });

    // Función para formatear el contenido expandido
    function format(d) {
        // `d` es el objeto de datos original para la fila
        // Construye aquí tu contenido HTML para las acciones y secciones de análisis
        return '<div>'+
        '<p><strong>Acciones:</strong> Crear nueva versión; Eliminar; Renovar</p>'+
        '<p><strong>Análisis Físico-Químicos:</strong></p>'+ // Añade aquí la tabla de Análisis Físico-Químicos
        '<p><strong>Análisis Microbiológicos:</strong></p>'+ // Añade aquí la tabla de Análisis Microbiológicos
        '<p><strong>Análisis de Laboratorio Asociados:</strong></p>'+ // Añade aquí la tabla de Análisis de Laboratorio
        '</div>';
    }
});

</script>
