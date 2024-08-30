<?php
//archivo: pages\LABORATORIO_listado_solicitudes.php
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
        <h1>Calidad / Análisis Externos / Listado solicitudes de análisis</h1>
        <br>
        <br>
        <h2 class="section-title">Listado solicitudes de análisis:</h2>
        <div class="estado-filtros">
            <label> Filtrar por:</label>
            <button class="estado-filtro badge badge-success" onclick="filtrar_listado('completado')">Completado</button>
            <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Pendiente Acta de Muestreo')">Pendiente Acta de Muestreo</button>
            <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Pendiente completar análisis')">Pendiente completar Análisis</button>
            <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Pendiente envío a Laboratorio')">Pendiente envío a Laboratorio</button>
            <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Pendiente ingreso resultados')">Pendiente ingreso resultados Laboratorio</button>
            <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Pendiente liberación productos')">Pendiente liberación Productos</button>
            <button class="estado-filtro badge" onclick="filtrar_listado('')">Todos</button>
        </div>
        <br>
        <br>
        <div id="contenedor_listado">
            <table id="listado" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th></th> <!-- Columna vacía para botones o checkboxes -->
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
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
    // variable de main js
    QA_solicitud_analisis_editing = true

    // Ahora puedes usar la sintaxis import
    function filtrar_listado(estado) {
        var table = $('#listado').DataTable();
        if (estado === '') {
            // Eliminar todos los filtros
            table.search('').columns().search('').draw();
        } else {
            // Aplicar filtro a la columna correspondiente
            table.column(1).search(estado).draw(); // Asumiendo que la columna 1 es la de estado
        }
    }
    function normalizeText(text) {
        return text.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    }

    // Sobrescribir la función de búsqueda global de DataTables
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var searchTerm = normalizeText($('#listado_filter input').val().toLowerCase());
            var rowContent = normalizeText(data.join(' ').toLowerCase());

            return rowContent.includes(searchTerm);
        }
    );
    var usuarioActual = "<?php echo $_SESSION['usuario']; ?>";

    function carga_listado() {
        var table = $('#listado').DataTable({
            "ajax": "./backend/laboratorio/listado_solicitudesBE.php",
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
            },
            "columns": [{
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": '<i class="fas fa-search-plus"></i>',
                    "width": "20px"
                },
                {
                    "data": "estado",
                    "title": "Estado",
                    "width": "35px",
                    "render": function(data, type, row) {
                        switch (data) {
                            case 'completado':
                                return '<span class="badge badge-success">Completado</span>';
                            case 'Especificación obsoleta':
                                return '<span class="badge badge-dark">Expirado</span>';
                            case 'Expirado':
                                return '<span class="badge badge-dark">Expirado</span>';
                            case 'Pendiente liberación productos':
                                return '<span class="badge badge-warning">Pendiente liberación productos</span>';
                            case 'Pendiente completar análisis':
                                return '<span class="badge badge-warning">Pendiente completar análisis</span>';
                            case 'Pendiente envío a Laboratorio':
                                return '<span class="badge badge-warning">Pendiente envío a Laboratorio</span>';
                            case 'En proceso de firmas':
                                return '<span class="badge badge-warning">En proceso de firmas</span>';
                            case 'Pendiente Acta de Muestreo':
                                return '<span class="badge badge-warning">Pendiente Acta de Muestreo</span>';
                            case 'Pendiente de Revisión':
                                return '<span class="badge badge-warning">Pendiente de Revisión</span>';
                            case 'Pendiente ingreso resultados':
                                return '<span class="badge badge-warning">Pendiente ingreso resultados</span>';
                            default:
                                return '<span class="badge">' + data + '</span>';
                        }
                    }
                },
                {
                    "data": "fecha_registro",
                    "title": "Fecha registro",
                    "width": "65px",
                    "render": function(data, type, row) {
                        return data ? data : '';
                    }
                },
                {
                    "data": "numero_solicitud",
                    "title": "Nro Solicitud",
                    "width": "90px"
                },
                {
                    "data": "numero_registro",
                    "title": "Registro",
                    "width": "90px"
                },
                {
                    "data": "producto",
                    "title": "Producto",
                    "width": "170px"
                },
                {
                    "data": "lote",
                    "title": "Número Lote",
                    "width": "70px"
                },
                {
                    "data": "laboratorio",
                    "title": "Laboratorio",
                    "width": "70px"
                },

                {
                    title: 'id',
                    data: 'id_analisisExterno',
                    defaultContent: '', // Puedes cambiar esto si deseas poner contenido por defecto
                    visible: false // Esto oculta la columna
                }
                ,
                {
                    "data": "producto",
                    "title": "Producto_filtrado",
                    visible: false,
                    "render": function(data, type, row) {
                        if (data) {
                            // Si data no es null ni undefined, realiza la normalización
                            return data.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                        } else {
                            // Si data es null o undefined, retorna una cadena vacía o un valor por defecto
                            return '';
                        }
                    }
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
            
            if (d.estado === "Pendiente completar análisis" && d.solicitado_por === usuarioActual) {
                acciones += '<button class="accion-btn" title="WIP Revisar Análisis Externo" type="button" id="' + d.id_analisisExterno + '" name="revisar" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fas fa-search"></i> Completar información faltante</button><a> </a>';
            }
            //revisar analisis externo
            if ( (d.estado === "Pendiente ingreso resultados laboratorio" ||
                d.estado === "Pendiente ingreso resultados" ) && d.revisado_por === usuarioActual ) {
                acciones +=  `<button class="accion-btn" title="Ingresar resultados Laboratorio" id="${d.id_analisisExterno}" name="generar_documento_solicitudes" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Ingresar resultados de Laboratorio</button><a> </a>`;
            }

            if (d.estado === "Finalizado") {
                
            }
            if (d.estado === "Pendiente envío a Laboratorio" && d.revisado_por === usuarioActual) {
                acciones += `<button class="accion-btn" title="WIP Enviar a Laboratorio" id="${d.id_analisisExterno}" name="enviarSolicitud_laboratorio" onclick="botones(this.id, this.name, \'laboratorio\')">
                    <i class="fa fa-file-pdf-o"></i> 
                    Enviar Solicitud a Laboratorio
                </button><a></a>`;
            }
            if (d.am_ejecutado_por === usuarioActual && d.estado === "Pendiente Acta de Muestreo" && d.id_muestreo === null) {
                acciones += '<button class="accion-btn" title="Generar Acta de muestreo" id="' + d.id_analisisExterno + '" name="generar_acta_muestreo" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fas fa-check"></i> Generar Acta de Muestreo</button><a> </a>';
            }

            if (d.estado === "Pendiente Acta de Muestreo" && d.id_muestreo !== null) {
                if (d.estado_muestreo === "Pendiente Muestreo") {
                    acciones += '<button class="accion-btn" title="Ingresar resultados Acta Muestreo" type="button" id="' + d.id_muestreo + '" name="resultados_actaMuestreo" onclick="botones(' + d.id_muestreo + ', this.name, \'laboratorio\')"><i class="fas fa-search"></i> Ingresar resultados Acta Muestreo</button><a></a>';
                }
                if (d.estado_muestreo === "En proceso de firma") {
                    acciones += '<button class="accion-btn" title="Firmar Acta de Muestreo" id="' + d.id_muestreo + '" name="firmar_acta_muestreo" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-signature"></i> Ir a Firmar Acta Muestreo</button><a> </a>';
                }
            }
            // if (
            //     d.estado.toLowerCase() === "pendiente envío laboratorio" || 
            //     d.estado.toLowerCase() === "pendiente ingreso resultados" || 
            //     d.estado.toLowerCase() === "pendiente liberación producto" ||
            //     d.estado.toLowerCase() === "finalizado"
            // ) {
            //     acciones += `<button class="accion-btn" title="WIP Firmar Solicitud Análisis Externo" id="${d.id_analisisExterno}" name="generar_documento_solicitudes" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Revisar analisis externo</button><a> </a>`;
            // }
            
            if ( d.estado === "Pendiente liberación productos") {
            acciones += `<button class="accion-btn" title="Solicitud Liberacion" type="button" id="${d.id_analisisExterno}" name="Liberacion" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fas fa-search"></i> Emitir Acta de Liberación</button><a></a>`;
            }

            acciones += '</td></tr></table>';

            return acciones;
        }




        // Verificar si hay una alerta en la sesión y mostrarla
        <?php if (isset($_SESSION['alerta'])) { ?>
            alert('<?php echo $_SESSION['alerta']; ?>');
            <?php unset($_SESSION['alerta']); ?>
        <?php } ?>

        // Si se acaba de insertar una nueva especificación, establecer el valor del buscador de DataTables
        <?php if (isset($_SESSION['buscar_por_ID'])) { ?>
            var buscar = '<?php echo $_SESSION['buscar_por_ID']; ?>';
            table.columns(8).search(buscar).draw();
            //table.search(buscar).draw();
            <?php unset($_SESSION['buscar_por_ID']); ?>
        <?php } ?>
    }
</script>