    // Ahora puedes usar la sintaxis import
    //import { botones } from '../../../assets/js/scripts_index.js';


    function filtrar_listado(estado) {
        var table = $('#listado').DataTable();
        table.column(1).search(estado).draw(); // Asumiendo que la columna 1 es la de
    }
    export function carga_listado_especificacionProducto() {
    var table = $('#listado').DataTable({
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
                "width": "20px"
            },
            {
                "data": "estado",
                "title": "Estado",
                "width": "160px",
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
            { "data": "documento", "title": "Documento", "width": "170px" },
            { "data": "version", "title": "Versión", "width": "65px" },
            { "data": "producto", "title": "Producto" },
            { "data": "tipo_producto", "title": "Tipo producto" },
            { "data": "concentracion", "title": "Concentración" },
            { "data": "formato", "title": "Formato" },
            { "data": "fecha_expiracion", "title": "Fecha expiración"  },
            {
                    title: 'id_especificacion',
                    data: 'id_especificacion',
                    defaultContent: '', // Puedes cambiar esto si deseas poner contenido por defecto
                    visible: false // Esto oculta la columna
                }
        ],
        
    });

    // Event listener para el botón de detalles
    $('#listado tbody').on('click', 'td.details-control', function() {
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
    var acciones = '<table background-color="#F6F6F6" color="#FFF" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
    acciones += '<tr><td VALIGN="TOP">Acciones:</td><td>';

    // Botón para revisar siempre presente
    acciones += '<button class="accion-btn" title="Revisar Especificación" type="button" id="' + d.id_especificacion + '" name="revisar" onclick="botones(this.id, this.name, \'especificacion\')"><i class="fas fa-search"></i></button><a> </a>';
    acciones += '<button class="accion-btn" title="Generar Documento" id="' + d.id_especificacion + '" name="generar_documento" onclick="botones(this.id, this.name, \'especificacion\')"><i class="fa fa-file-pdf-o"></i></button><a> </a>';
    // Botón para generar acta de muestreo, visible solo si el estado es 'Vigente'
    if (d.estado === 'Vigente') {
        acciones += '<button class="accion-btn" title="Generar Solicitud de Análisis externo" id="' + d.id_especificacion + '" name="prepararSolicitud" onclick="botones(this.id, this.name, \'especificacion\')"><i class="fas fa-vial"></i></button><a> </a>';
    }

    acciones += '</td></tr></table>';
    return acciones;
}


    const phpData = document.getElementById('phpData');
    const alerta = phpData.getAttribute('data-alerta');
    const buscarEspecificacion = phpData.getAttribute('data-buscarEspecificacion');

    if (alerta) {
        alert(alerta);
        // Asumiendo que quieres eliminar la alerta después de mostrarla
        phpData.removeAttribute('data-alerta');
    }

    if (buscarEspecificacion) {
        // Asume que carga_listado_especificacionProducto() ya ha sido definido y configurado para usar esta búsqueda
        carga_listado_especificacionProducto(buscarEspecificacion);
    }
}
