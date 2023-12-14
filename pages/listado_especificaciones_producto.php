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
<link rel="stylesheet" href="../assets/css/DocumentoEspecs.css">
</head>

<body>
    <div class="form-container">
        <h1>Calidad / Listado Especificaciones de Productos</h1>
            <br>
            <br>
            <h2 class="section-title">Listado Especificaciones de Productos:</h2>
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

function carga_listadoEspecificacionesProductos() {
    var table = $('#listadoEspecProductos').DataTable({
        "ajax": "./backend/calidad/listado_especificaciones_productoBE.php",
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
    $('#listadoEspecProductos tbody').on('click', 'td.details-control', function() {
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            // Esta fila ya está abierta - ciérrala
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Abre esta filaQ
            row.child(format(row.data())).show(); // Aquí llamas a la función que formatea el contenido expandido
            tr.addClass('shown');
        }
    });

    // Función para formatear el contenido expandido
    function format(d) {
        // `d` es el objeto de datos original para la fila
        // Construye aquí tu contenido HTML para las acciones y secciones de análisis
        return '<table background-color:#F6F6F6; color:#FFF; cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
            '<tr><td VALIGN=TOP>Acciones</td>' +
                '<td>' +
                    '<button title="Revisar Especificación" type="button" id="' + d.id + '" name="revisar" onclick="botones(this.id, this.name, \'especificacion\')"><i class="fas fa-search"></i></button><a> </a>' +
                    '<button title="Generar documento" type="button" id="' + d.id + '" name="generar_documento" onclick="botones(this.id, this.name, \'especificacion\')"><i class="fa fa-file-pdf-o"></i></button><a> </a>' +
                    '<button title="Editar" type="button" id="' + d.id + '" name="editar" onclick="botones(this.id, this.name, \'especificacion\')"><i class="fas fa-edit"></i></button><a> </a>' +
                '</td>' +
            '</tr>' +
        '</table>';
    }
}

</script>
