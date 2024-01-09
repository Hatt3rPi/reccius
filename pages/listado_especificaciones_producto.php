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
        <h1>Calidad / Listado Especificaciones de Productos</h1>
            <br>
            <br>
            <h2 class="section-title">Listado Especificaciones de Productos:</h2>
            <div class="estado-filtros">
                <label>Filtrar por:</label>
                <button class="estado-filtro badge badge-success" onclick="filtrar_listado('Vigente')">Vigente</button>
                <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Pendiente de Aprobación')">Pendiente de Aprobación</button>
                <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Pendiente de Revisión')">Pendiente de Revisión</button>
                <button class="estado-filtro badge badge-dark" onclick="filtrar_listado('Especificación obsoleta')">Especificación obsoleta</button>
                <button class="estado-filtro badge badge-dark" onclick="filtrar_listado('Expirado')">Expirado</button>
                <button class="estado-filtro badge" onclick="filtrar_listado('')">Todos</button>
            </div>
            <br>
            <br>
            <div id="contenedor_listadoEspecProductos">
                <table id="listadoEspecProductos" class="table table-striped table-bordered" style="width:100%">
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
    </div>
</body>

</html>
<script>
    function filtrar_listado(estado) {
        var table = $('#listadoEspecProductos').DataTable();
        table.column(1).search(estado).draw(); // Asumiendo que la columna 1 es la de
    }
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
            {
                "data": "estado",
                "title": "Estado",
                "render": function(data, type, row) {
                    switch (data) {
                        case 'Vigente':
                            return '<span class="badge badge-success">Vigente</span>';
                        case 'Especificación obsoleta':
                            return '<span class="badge badge-dark">Expirado</span>';
                        case 'Expirado':
                            return '<span class="badge badge-dark">Expirado</span>';
                        case 'Pendiente de Aprobación':
                            return '<span class="badge badge-warning">Pendiente de Aprobación</span>';
                        case 'Pendiente de Revisión':
                            return '<span class="badge badge-warning">Pendiente de Revisión</span>';
                        default:
                            return '<span class="badge">' + data + '</span>';
                    }
                }
            },
            { "data": "documento", "title": "Documento" },
            { "data": "version", "title": "Versión" },
            { "data": "producto", "title": "Producto" },
            { "data": "tipo_producto", "title": "Tipo producto" },
            { "data": "concentracion", "title": "Concentración" },
            { "data": "formato", "title": "Formato" },
            { "data": "fecha_expiracion", "title": "Fecha expiración" },
            {
                    title: 'id_especificacion',
                    data: 'id_especificacion',
                    defaultContent: '', // Puedes cambiar esto si deseas poner contenido por defecto
                    visible: false // Esto oculta la columna
                }
        ],
        
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
                    '<button class="accion-btn" title="Revisar Especificación" type="button" id="' + d.id_especificacion + '" name="revisar" onclick="botones(this.id, this.name, \'especificacion\')"><i class="fas fa-search"></i></button><a> </a>' +
                    '<button class="accion-btn" title="Generar documento" type="button" id="' + d.id_especificacion + '" name="generar_documento" onclick="botones(this.id, this.name, \'especificacion\')"><i class="fa fa-file-pdf-o"></i></button><a> </a>' +
                    '<button class="accion-btn" title="Editar" type="button" id="' + d.id_especificacion + '" name="editar" onclick="botones(this.id, this.name, \'especificacion\')"><i class="fas fa-edit"></i></button><a> </a>' +
                '</td>' +
            '</tr>' +
        '</table>';
    }


    // Verificar si hay una alerta en la sesión y mostrarla
    <?php if (isset($_SESSION['alerta'])) { ?>
        alert('<?php echo $_SESSION['alerta']; ?>');
        <?php unset($_SESSION['alerta']); ?>
    <?php } ?>

    // Si se acaba de insertar una nueva especificación, establecer el valor del buscador de DataTables
    <?php if (isset($_SESSION['buscarEspecificacion'])) { ?>
        var buscar = '<?php echo $_SESSION['buscarEspecificacion']; ?>';
        table.columns(9).search(buscar).draw();
        //table.search(buscar).draw();
        <?php unset($_SESSION['buscarEspecificacion']); ?>
    <?php } ?>
}

</script>
