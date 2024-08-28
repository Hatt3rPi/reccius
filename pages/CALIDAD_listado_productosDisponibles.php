<?php
//archivo: pages\CALIDAD_listado_productosDisponibles.php
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
        <h1>CALIDAD / LISTADO DE PRODUCTOS ANALIZADOS</h1>
        <br>
        <br>
        <h2 class="section-title">Listado de productos analizados:</h2>
        <div class="estado-filtros">
            <label> Filtrar por:</label>
        </div>        
        <div class="estado-filtros">
            <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('En cuarentena','estado')">En cuarentena</button>
            <button class="estado-filtro badge badge-success" onclick="filtrar_listado('liberado','estado')">Liberado</button>
            <button class="estado-filtro badge badge-dark" onclick="filtrar_listado('rechazado','estado')">Rechazado</button>
        </div>
        <div class="estado-filtros">
            <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Producto Terminado', 'tipo_producto')">Producto Terminado</button>
            <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Material Envase y Empaque', 'tipo_producto')">Material Envase y Empaque</button>
            <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Materia Prima', 'tipo_producto')">Materia Prima</button>
            <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Insumo', 'tipo_producto')">Insumo</button>
            
        </div>
        <div class="estado-filtros">
            <label> </label>
            <button class="estado-filtro badge" onclick="filtrar_listado('','estado')">Limpiar Filtros</button>
            
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
    // Ahora puedes usar la sintaxis import


    function filtrar_listado(valor, filtro) {
        var table = $('#listado').DataTable();
        if(filtro=="estado"){
            if (valor === '') {
                // Eliminar todos los filtros
                table.search('').columns().search('').draw();
            } else {
                table.column(1).search(valor).draw(); // Asumiendo que la columna 1 es la de
            }
        }else if(filtro=="tipo_producto"){
            if (valor === '') {
                // Eliminar todos los filtros
                table.search('').columns().search('').draw();
            } else {
                table.column(6).search(valor).draw(); // Asumiendo que la columna 1 es la de
            }
        }
        
    }

    function carga_listado() {
        var table = $('#listado').DataTable({
            "ajax": "./backend/acta_liberacion/listado_productosDisponiblesBE.php",
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
                    "width": "80px",
                    "render": function(data, type, row) {
                        switch (data) {
                            case 'rechazado':
                                return '<span class="badge badge-dark">Rechazado</span>';
                            case 'liberado':
                                return '<span class="badge badge-success">Liberado</span>';
                            case 'En cuarentena':
                                return '<span class="badge badge-warning">En Cuarentena</span>';
                            default:
                                return '<span class="badge">' + data + '</span>';
                        }
                    }
                },
                {
                    "data": "producto",
                    "title": "Producto",
                    "width": "300px"
                },
                {
                    "data": "lote",
                    "title": "Nro Lote",
                    "width": "100px"
                },
                {
                    "data": "fecha_out_cuarentena",
                    "title": "Fecha Liberación",
                    "width": "65px",
                    "render": function(data, type, row) {
                        return data ? data : 'En proceso';
                    }
                },
                {
                    "data": "fecha_vencimiento",
                    "title": "Fecha Vencimiento",
                    "width": "65px"
                },
                {
                    "data": "tipo_producto",
                    "title": "Tipo producto",
                    "width": "100px"
                },
                {
                    "data": "id",
                    "title": "ID producto",
                    visible: false
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
            if (d.estado === "liberado" || d.estado === "rechazado" ) {
                acciones += '<button class="accion-btn" title="Revisar Especificación de producto" id="' + d.id_especificacion + '" name="generar_documento" onclick="botones(this.id, this.name, \'especificacion\')"><i class="fa fa-file-pdf-o"></i> Revisa Especificación de Producto</button><a> </a>';
                acciones += '<button class="accion-btn" title="Revisar acta de Muestreo" id="' + d.id_actaMuestreo + '" name="revisar_acta" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Revisar Acta de Muestreo</button><a> </a>';
                acciones += '<button class="accion-btn" title="Revisar Solicitud Análisis Externo" id="' + d.id_analisisExterno + '" name="generar_documento_solicitudes" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Revisar Solicitud Análisis Externo</button><a> </a>';
                if(d.url_documento_adicional !== null && d.url_documento_adicional !== ""){
                    acciones += '<button class="accion-btn" title="Revisar Documento Adicional" onclick="window.open(\'' + d.url_documento_adicional + '\', \'_blank\')"><i class="fa fa-file-pdf-o"></i> Revisar Documento Adicional</button><a> </a>';
                }
                acciones += '<button class="accion-btn" title="Revisar Acta de liberación o rechazo" id="' + d.id_actaLiberacion + '" name="revisar_acta_liberacion" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Revisa Acta de Liberación/Rechazo</button><a> </a>';
                acciones += '<button class="accion-btn" title="Revisar informe de Laboratorio" id="' + d.id_analisisExterno + '" name="revisar_informe_laboratorio" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Revisar informe de Laboratorio</button><a> </a>';
                

            }else{
                acciones += '<button class="accion-btn" title="Revisar Especificación de producto" id="' + d.id_especificacion + '" name="generar_documento" onclick="botones(this.id, this.name, \'especificacion\')"><i class="fa fa-file-pdf-o"></i> Revisa Especificación de Producto</button><a> </a>';
                if(d.id_actaMuestreo!==null || d.id_actaMuestreo!==""){
                    acciones += '<button class="accion-btn" title="Revisar acta de Muestreo" id="' + d.id_actaMuestreo + '" name="revisar_acta" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Revisar Acta de Muestreo</button><a> </a>';                    
                }
                if(d.id_analisisExterno!==null || d.id_analisisExterno!==""){
                    acciones += '<button class="accion-btn" title="Revisar Solicitud Análisis Externo" id="' + d.id_analisisExterno + '" name="generar_documento_solicitudes" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Revisar Solicitud Análisis Externo</button><a> </a>';
                }
                
                if(d.url_documento_adicional !== null && d.url_documento_adicional !== ""){
                    acciones += '<button class="accion-btn" title="Revisar Documento Adicional" onclick="window.open(\'' + d.url_documento_adicional + '\', \'_blank\')"><i class="fa fa-file-pdf-o"></i> Revisar Documento Adicional</button><a> </a>';
                }
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
            console.log('se intentará filtrar por id: ', buscar);
            table.columns(7).search(buscar).draw();
            //table.search(buscar).draw();
            <?php unset($_SESSION['buscar_por_ID']); ?>
        <?php } ?>
    }
</script>