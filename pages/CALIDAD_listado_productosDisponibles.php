<?php
//archivo: pages\CALIDAD_listado_productosDisponibles.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Verificar si la variable de sesión "usuario" no está establecida o está vacía.
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    // Redirigir al usuario a la página de inicio de sesión.
    header("Location: login.html");
    exit;
}
$query = "SELECT id, nombre_opcion 
          FROM calidad_opciones_desplegables 
          WHERE categoria = 'tipo_documento_adjunto'
          ORDER BY CASE WHEN nombre_opcion = 'Otro' THEN 1 ELSE 0 END, nombre_opcion ASC";
$result = mysqli_query($link, $query);
$opciones = [];

while ($row = mysqli_fetch_assoc($result)) {
    $opciones[] = $row;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="stylesheet" href="../assets/css/Listados.css">
    <link rel="stylesheet" href="../assets/css/barra_progreso.css">
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
            <label> Estado </label>
            <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('En cuarentena','estado')">En cuarentena</button>
            <button class="estado-filtro badge badge-success" onclick="filtrar_listado('liberado','estado')">Liberado</button>
            <button class="estado-filtro badge badge-dark" onclick="filtrar_listado('rechazado','estado')">Rechazado</button>
        </div>
        <div class="estado-filtros">
            <label> Tipo de Producto </label>
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
    <div class="modal fade" id="modalAdjuntarArchivo" tabindex="-1" role="dialog" aria-labelledby="modalAdjuntarArchivoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdjuntarArchivoLabel">Adjuntar Documento Opcional</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAdjuntarArchivo" enctype="multipart/form-data">
                        <input type="hidden" id="id_productos_analizados" name="id_productos_analizados" value="">

                        <!-- Selección del archivo -->
                        <div class="form-group">
                            <label for="documento">Seleccionar Archivo (PDF o Imagen)</label>
                            <input type="file" class="form-control-file" id="documento" name="documento" accept=".pdf, image/jpeg, image/png" required>
                        </div>

                        <!-- Nombre del documento -->
                        <div class="form-group">
                            <label for="nombre_documento">Nombre del Documento</label>
                            <input type="text" class="form-control" id="nombre_documento" name="nombre_documento" required>
                        </div>

                        <!-- Tipo de adjunto y campo "Otro" -->
                        <div class="form-group">
                            <label for="tipo_adjunto">Tipo de Documento</label>
                            <select name="tipo_adjunto" class="form-control" id="tipo_adjunto">
                                <?php foreach ($opciones as $opcion): ?>
                                    <option value="<?php echo htmlspecialchars($opcion['id']); ?>">
                                        <?php echo htmlspecialchars($opcion['nombre_opcion']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Input adicional para "Otro" -->
                        <div id="otro_tipo_adjunto_container" style="display: none;" class="form-group">
                            <label for="otro_tipo_adjunto">Especificar otro tipo de documento:</label>
                            <input type="text" name="otro_tipo_adjunto" id="otro_tipo_adjunto" class="form-control" placeholder="Especificar tipo de documento">
                        </div>

                        <!-- Alerta -->
                        <div id="alertaArchivo" class="alert alert-danger" style="display: none;"></div>

                        <!-- Botón de enviar -->
                        <button type="submit" class="btn btn-primary">Subir Documento</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
<script>
    function filtrar_listado(valor, filtro) {
        var table = $('#listado').DataTable();
        if (filtro == "estado") {
            if (valor === '') {
                // Eliminar todos los filtros
                table.search('').columns().search('').draw();
            } else {
                table.column(1).search(valor).draw(); // Asumiendo que la columna 1 es la de
            }
        } else if (filtro == "tipo_producto") {
            if (valor === '') {
                // Eliminar todos los filtros
                table.search('').columns().search('').draw();
            } else {
                table.column(7).search(valor).draw(); // Asumiendo que la columna 1 es la de
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
                    "defaultContent": '<i class="fas fa-search-plus"></i>',
                    "width": "20px",
                    "data": "id",
                    "render": function(data, type, row) {
                        return `<i class="fas fa-search-plus" data-id-producto="${ data }" ></i>`;
                    }
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
                    "data": "numero_solicitud_version",
                    "title": "Nro Solicitud",
                    "width": "100px"
                },
                {
                    "data": "lote",
                    "title": "Nro Lote",
                    "width": "100px"
                },
                {
                    "data": "fecha_out_cuarentena",
                    "title": "Fecha Liberación",
                    "width": "45px",
                    "render": function(data, type, row) {
                        return data ? data : 'En proceso';
                    }
                },
                {
                    "data": "fecha_vencimiento",
                    "title": "Fecha Vencimiento",
                    "width": "45px"
                },
                {
                    "data": "tipo_producto",
                    "title": "Tipo producto",
                    "width": "100px"
                },
                {
                    "data": "tipo_analisis",
                    "title": "Tipo Análisis"
                },
                {
                    "data": "id",
                    "title": "ID producto",
                    visible: false
                },
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
                var rowData = row.data();
                // Abre esta filaQ
                row.child(format(rowData)).show();
                // Aquí llamas a la función que formatea el contenido expandido
                tr.addClass('shown');
                setAttachedDocuments(rowData.id)
                makeDocuments(rowData)
            }
        });

        // Función para formatear el contenido expandido
        function format(d) {
            // `d` es el objeto de datos original para la fila
            var botones_acta_muestreo = '';
            var botones_analisis_externo = '';
            var botones_otros_documentos = '';
            var porcentaje_externo = 0;
            switch (d.aex_estado) {
                case 'Pendiente Acta de Muestreo': {
                    porcentaje_externo = 0;
                    break;
                }
                case 'Pendiente completar análisis': {
                    porcentaje_externo = 20;
                    break;
                }
                case 'Pendiente envío a Laboratorio': {
                    porcentaje_externo = 40;
                    break;
                }
                case 'Pendiente ingreso resultados': {
                    porcentaje_externo = 60;
                    break;
                }
                case 'Pendiente liberación productos': {
                    porcentaje_externo = 80;
                    break;
                }
                case 'completado': {
                    porcentaje_externo = 100;
                    break;
                }

            }

            function determinarClase(porcentaje_externo, porcentaje_etapa) {
                if (porcentaje_externo === porcentaje_etapa) {
                    return 'pg_estado_actual';
                } else if (porcentaje_externo > porcentaje_etapa) {
                    return 'pg_completado';
                } else {
                    return '';
                }
            }
            if (d.estado === "liberado" || d.estado === "rechazado") {

                botones_otros_documentos +=
                    '<button class="accion-btn ingControl" title="Revisar Especificación de producto" id="' + d.id_especificacion + '" name="generar_documento" onclick="botones(this.id, this.name, \'especificacion\')"><i class="fa fa-file-pdf-o"></i> Revisa Especificación de Producto</button><a> </a>';


                botones_otros_documentos += '<button class="accion-btn ingControl" title="Añadir Documento Opcional" data-toggle="modal" data-target="#modalAdjuntarArchivo" data-id-producto="' + d.id + '"><i class="fa fa-plus"></i> Añadir Documento</button>';

                botones_acta_muestreo += '<button class="accion-btn ingControl" title="Revisar acta de Muestreo" id="' + d.id_actaMuestreo + '" name="revisar_acta" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Revisar Acta de Muestreo</button><a> </a>';
                botones_analisis_externo += '<button class="accion-btn ingControl" title="Revisar Solicitud Análisis Externo" id="' + d.id_analisisExterno + '" name="generar_documento_solicitudes" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Revisar Solicitud Análisis Externo</button><a> </a>';

                if (d.url_documento_adicional !== null && d.url_documento_adicional !== "") {
                    botones_analisis_externo += '<button class="accion-btn ingControl" title="Revisar Documento Adicional" onclick="window.open(\'' + d.url_documento_adicional + '\', \'_blank\')"><i class="fa fa-file-pdf-o"></i> Revisar Documento Adicional</button><a> </a>';
                }

                botones_analisis_externo += '<button class="accion-btn ingControl" title="Revisar Acta de liberación o rechazo" id="' + d.id_actaLiberacion + '" name="revisar_acta_liberacion" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Revisa Acta de Liberación/Rechazo</button><a> </a>';
                botones_analisis_externo += '<button class="accion-btn ingControl" title="Revisar informe de Laboratorio" id="' + d.id_analisisExterno + '" name="revisar_informe_laboratorio" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Revisar informe de Laboratorio</button><a> </a>';


            } else {
                botones_otros_documentos += '<button class="accion-btn ingControl" title="Revisar Especificación de producto" id="' + d.id_especificacion + '" name="generar_documento" onclick="botones(this.id, this.name, \'especificacion\')"><i class="fa fa-file-pdf-o"></i> Revisa Especificación de Producto</button><a> </a>';
                if (d.id_actaMuestreo !== null && d.id_actaMuestreo !== "" && d.estado_amuestreo === "Vigente") {
                    botones_acta_muestreo += '<button class="accion-btn ingControl" title="Revisar acta de Muestreo" id="' + d.id_actaMuestreo + '" name="revisar_acta" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Revisar Acta de Muestreo</button><a> </a>';
                }
                if (d.id_analisisExterno !== null && d.id_analisisExterno !== "" && d.estado_aex === "Pendiente ingreso resultados") {
                    botones_analisis_externo += '<button class="accion-btn ingControl" title="Revisar Solicitud Análisis Externo" id="' + d.id_analisisExterno + '"onclick="window.open(\'' + d.url_certificado_solicitud_analisis_externo + '\', \'_blank\')"><i class="fa fa-file-pdf-o"></i> Revisar Solicitud Análisis Externo</button><a> </a>';
                }
                if (d.id_analisisExterno !== null && d.id_analisisExterno !== "" && (d.estado_aex === "Completado" || d.estado_aex === "Pendiente liberación productos")) {
                    botones_analisis_externo += '<button class="accion-btn ingControl" title="Revisar Solicitud Análisis Externo" id="' + d.id_analisisExterno + '" name="generar_documento_solicitudes" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Revisar Solicitud Análisis Externo</button><a> </a>';
                }
                if (d.id_analisisExterno !== null && d.id_analisisExterno !== "" && (d.estado_aex === "Pendiente ingreso resultados laboratorio" || d.estado_aex === "Pendiente envío a Laboratorio")) {
                    botones_analisis_externo += `<button class="accion-btn ingControl" title="Revisar Documento" id="${d.id_analisisExterno}" name="generar_documento_pdf" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Revisar solicitud</button><a> </a>`;
                }
                if (d.url_documento_adicional !== null && d.url_documento_adicional !== "") {
                    botones_otros_documentos += '<button class="accion-btn ingControl" title="Revisar Documento Adicional" onclick="window.open(\'' + d.url_documento_adicional + '\', \'_blank\')"><i class="fa fa-file-pdf-o"></i> Revisar Documento Adicional</button><a> </a>';
                }

            }
            var progreso_acta_muestreo = ` 
                <div class="custom-barra_progreso">
                    <ul class="barra_progreso">
                        <li class="section ${d.id_actaMuestreo ? 'pg_completado' : 'pg_estado_actual'}">
                            <div class="circle">1</div>
                            <div class="label">Creación Acta de Muestreo</div>
                            <div class="user_done">${d.am_generador ? d.am_generador : d.am_muestreador}</div>
                        </li>
                        <li class="section ${(d.am_fecha_firma_muestreador) ? 'pg_completado' : (!d.am_fecha_firma_muestreador && d.id_actaMuestreo) ? 'pg_estado_actual' : ''}">
                            <div class="circle">2</div>
                            <div class="label">Muestreo finalizado</div>
                            <div class="user_done">${d.am_muestreador ? d.am_muestreador : 'Por definir'}</div>
                        </li>
                        <li class="section ${(d.am_fecha_firma_responsable && d.am_fecha_firma_verificador) ? 'pg_completado' : (!d.am_fecha_firma_responsable && d.am_fecha_firma_muestreador) ? 'pg_estado_actual' : ''}">
                            <div class="circle">3</div>
                            <div class="label">Firma responsable</div>
                            <div class="user_done">${d.am_responsable ? d.am_responsable : 'Por definir'}</div>
                        </li>
                        <li class="section ${d.am_fecha_firma_verificador ? 'pg_completado' : (!d.am_fecha_firma_verificador && d.am_fecha_firma_responsable) ? 'pg_estado_actual' : ''}">
                            <div class="circle">4</div>
                            <div class="label">Firma revisor</div>
                            <div class="user_done">${d.am_verificador ? d.am_verificador : 'Por definir'}</div>
                        </li>
                        <li class="section ${d.am_fecha_firma_verificador ? 'pg_completado' : ''}">
                            <div class="circle">5</div>
                            <div class="label">Completado</div>
                            <div class="user_done"></div>
                        </li>
                        <div class="status-bar"></div>
                        <div class="current-status" style="width: 60%;"></div> <!-- Ajusta el % según el progreso -->
                    </ul>
                </div>
            `;
            var progreso_analisis_externo = `
                <div class="custom-barra_progreso">
                    <ul class="barra_progreso">
                        <li class="section ${determinarClase(porcentaje_externo, 0)}">
                            <div class="circle">1</div>
                            <div class="label">Creación Análisis Externo</div>
                            <div class="user_done">${d.aex_firma1 ? d.aex_firma1 : 'Por definir'}</div>
                        </li>
                        <li class="section ${determinarClase(porcentaje_externo, 20)}">
                            <div class="circle">2</div>
                            <div class="label">Pendiente completar análisis</div>
                            <div class="user_done">${d.aex_firma1 ? d.aex_firma1 : 'Por definir'}</div>
                        </li>
                        <li class="section ${determinarClase(porcentaje_externo, 40)}">
                            <div class="circle">3</div>
                            <div class="label">Pendiente envío a Laboratorio</div>
                            <div class="user_done">${d.aex_revisado_por ? d.aex_revisado_por : 'Por definir'}</div>
                        </li>
                        <li class="section ${determinarClase(porcentaje_externo, 60)}">
                            <div class="circle">4</div>
                            <div class="label">Pendiente ingreso resultados</div>
                            <div class="user_done">${d.aex_revisado_por ? d.aex_revisado_por : 'Por definir'}</div>
                        </li>
                        <li class="section ${determinarClase(porcentaje_externo, 80)}">
                            <div class="circle">5</div>
                            <div class="label">Pendiente Liberación productos</div>
                            <div class="user_done">${d.aex_firma1 ? d.aex_firma1 : 'Por definir'}</div>
                        </li>
                        <li class="section ${porcentaje_externo === 100 ? 'pg_completado' : ''}">
                            <div class="circle">6</div>
                            <div class="label">Completado</div>
                            <div class="user_done"></div>
                        </li>
                    </ul>
                    <div class="status-bar"></div>
                    <div class="current-status" style="width: ${porcentaje_externo}%;"></div>
                </div>
            `;
            var cuadro_informativo = `
                <table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">
                    <tr>
                        <td>Acta de Muestreo:</td>
                        <td style="width: 60%">` + progreso_acta_muestreo + `</td>
                        <td>
                            <div class="button-container">
                                ` + botones_acta_muestreo + `
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Análisis Externo:</td>
                        <td style="width: 60%">` + progreso_analisis_externo + `</td>
                        <td>
                            <div class="button-container">
                                ` + botones_analisis_externo + `
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Documentos:</td>
                        <td class="otros-documentos-container" >
                            <section id="normal-documentos-container-${d.id}">
                                <p>Cargando documentos opcionales...</p>
                            </section>
                            <section id="otros-documentos-container-${d.id}">
                                <p>Cargando documentos opcionales...</p>
                            </section>
                        </td>
                        <td>
                            <div class="button-container">
                                ` + botones_otros_documentos + `
                            </div>
                        </td>
                    </tr>
                </table>
            `;
            return cuadro_informativo;

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
            table.columns(8).search(buscar).draw();
            //table.search(buscar).draw();
            <?php unset($_SESSION['buscar_por_ID']); ?>
        <?php } ?>
    }

    function makeDocuments(d) {
        var certificados = [
            {
                name: 'Revisar Especificación de Producto',
                url: d.url_revisar_especificacion,
                form: `<button class="accion-btn ingControl p-0" title="Revisar Especificación de producto" 
                        onclick="botones('${d.id_especificacion}', 'generar_documento', 'especificacion')">
                            Ver
                       </button>`
            },
            {
                name: 'Analisis externo',
                url: d.url_certificado_de_analisis_externo,
                form: ''
            },
            {
                name: 'Acta de Muestreo',
                url: d.url_certificado_acta_de_muestreo,
                form: `<button class="accion-btn ingControl p-0" title="Revisar acta de Muestreo" 
                        onclick="botones('${d.id_actaMuestreo}', 'revisar_acta', 'laboratorio')"> 
                            Ver
                        </button>`
            },
            {
                name: 'Analisis externo sin resultados',
                url: d.url_certificado_solicitud_analisis_externo,
                form: `<button class="accion-btn ingControl p-0" title="Revisar Solicitud Análisis Externo" 
                        onclick="botones('${d.id_analisisExterno}', 'generar_documento_solicitudes', 'laboratorio')">
                            Ver
                        </button>`
            },
            {
                name: 'Analisis externo con resultados',
                url: d.url_certificado_solicitud_analisis_externo_con_resultados,
                form: ''
            },
            {
                name: 'Acta de liberación o rechazo',
                url: '',
                form: `<button class="accion-btn ingControl p-0" title="Revisar Acta de liberación o rechazo" 
                        onclick="botones('${d.id_actaLiberacion}', 'revisar_acta_liberacion', 'laboratorio')">
                            Ver
                       </button>`
            },
        ];

        /*
        botones_acta_muestreo += ;

        botones_analisis_externo += 
        '<button class="accion-btn ingControl" title="Revisar Solicitud Análisis Externo" id="' + d.id_analisisExterno + '" name="generar_documento_solicitudes" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Revisar Solicitud Análisis Externo</button><a> </a>';
        */

        var norDocsContainer = $(`#normal-documentos-container-${d.id}`);
        norDocsContainer.empty();
        norDocsContainer.append('<p>Cargando documentos...</p>');
        norDocsContainer.empty();

        var bodyTableNorm = '';
        var headerTableNorm = `
            <tr>
                <th>Nombre</th>
                <th>Documento</th>
                <th>Formulario Web</th>
            </tr>`
        certificados.forEach(({
            name,
            url,
            form
        }, i) => {
            bodyTableNorm += `
                <tr id="row-document-${i}-${d.id}">
                    <td>${name}</td>
                    <td>
                        ${
                            url !== '' && url !== null && url !== undefined?
                            `<a href="${url}" target="_blank">Ver</a>`: 'Sin Documento'
                        } 
                    </td>
                    <td>
                        ${
                            form !== '' && form !== null && form !== undefined ?
                            form: ''
                        }
                    </td>
                </tr>`
        });





        norDocsContainer.append(`
            <table class="table table-striped table-bordered" style="width:100%">
                <thead>
                    ${headerTableNorm}
                </thead>
                <tbody>
                    ${bodyTableNorm}
                </tbody>
            </table>
        `);


    }
    function obtenerTipoArchivo(url) {
    const tiposMime = {
        'jpg': 'image/jpeg',
        'jpeg': 'image/jpeg',
        'png': 'image/png',
        'gif': 'image/gif',
        'bmp': 'image/bmp',
        'mp4': 'video/mp4',
        'mov': 'video/quicktime',
        'avi': 'video/x-msvideo',
        'mp3': 'audio/mpeg',
        'wav': 'audio/wav',
        'pdf': 'application/pdf',
        'doc': 'application/msword',
        'docx': 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls': 'application/vnd.ms-excel',
        'xlsx': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'zip': 'application/zip',
        'rar': 'application/x-rar-compressed'
    };
    const extension = url.split('.').pop().toLowerCase();
    if (tiposMime[extension]) {
        return tiposMime[extension];
    } else {
        return 'application/octet-stream';
    }
}
    function setAttachedDocuments(id) {
        var otrosDocumentosContainer = $(`#otros-documentos-container-${id}`);
        otrosDocumentosContainer.empty();
        otrosDocumentosContainer.append('<p>Cargando documentos adicionales...</p>');

        fetch(`./backend/documentos/obtener_adjuntos_analisis.php?id_productos_analizados=${id}`).then(response => response.json()).then(data => {
            otrosDocumentosContainer.empty();
            if (data.exito) {
                var bodyTable = '';
                var headerTable = `
                    <tr>
                        <th>Categoria</th>
                        <th>Tipo</th>
                        <th>Nombre</th>
                        <th>Cargado por</th>
                        <th>Fecha</th>
                        <th>Archivo</th>
                    </tr>`
                data.documentos.forEach(({
                    id,
                    url,
                    nombre_documento,
                    categoria,
                    usuario_carga,
                    fecha_carga
                }) => {
                    console.log('documento:', documento);
                    bodyTable += `
                        <tr id="row-document-${id}">
                            <td>${categoria}</td>
                            <td>${obtenerTipoArchivo(url)}</td>
                            <td>${nombre_documento}</td>
                            <td>${usuario_carga}</td>
                            <td>${fecha_carga.split('-').reverse().join('/')}</td>
                            <td>
                                <a href="${url}" target="_blank">Ver</a>
                            </td>
                        </tr>`
                });
                otrosDocumentosContainer.append(`
                    <table class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            ${headerTable}
                        </thead>
                        <tbody>
                            ${bodyTable}
                        </tbody>
                    </table>
                `);
            } else {
                otrosDocumentosContainer.append('<p>No hay archivos adjuntos extra</p>');
            }
        }).catch(error => {
            otrosDocumentosContainer.append('<p>Error al cargar los documentos</p>');
        });
    }
    $('#modalAdjuntarArchivo').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var idProducto = button.data('id-producto');
        var modal = $(this);
        modal.find('#id_productos_analizados').val(idProducto);
        setTimeout(function() {
            console.log('Cerrando modal');
            $('.modal-backdrop').remove();
        }, 200);
    });
    $(document).ready(function() {
        // Mostrar o esconder el campo "Otro" en función de la selección
        $('#tipo_adjunto').on('change', function() {
            var selectedOption = $(this).find('option:selected').text();
            if (selectedOption.trim() === 'Otro') {
                $('#otro_tipo_adjunto_container').show();
                $('#otro_tipo_adjunto').prop('required', true);
            } else {
                $('#otro_tipo_adjunto_container').val('');
                $('#otro_tipo_adjunto_container').hide();
                $('#otro_tipo_adjunto').prop('required', false);
            }
        });

        $('#documento').on('change', function() {
            var archivo = $(this)[0].files[0]; // Acceder al archivo seleccionado
            if (archivo) {
                var nombreArchivo = archivo.name;
                $('#nombre_documento').val(nombreArchivo);
            }
        })

        // Manejo del envío del formulario
        $('#formAdjuntarArchivo').on('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            var submitButton = $(this).find('button[type="submit"]');
            submitButton.prop('disabled', true); // Deshabilitar el botón

            fetch('./backend/documentos/opcionales_analisis.php', {
                    method: 'POST',
                    body: formData,
                    //TODO: Add seccion de otro_tipo_adjunto en el backend
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exito) {
                        setAttachedDocuments(formData.get('id_productos_analizados'));
                        alert('Documento subido con éxito');
                        $('#modalAdjuntarArchivo').modal('hide');
                        $('#formAdjuntarArchivo')[0].reset(); // Limpiar el formulario
                    } else {
                        $('#alertaArchivo').text(data.mensaje).show();
                    }
                })
                .catch(error => {
                    $('#alertaArchivo').text('Error al subir el documento: ' + error).show();
                })
                .finally(() => {
                    submitButton.prop('disabled', false); // Habilitar el botón
                });
        });
    });
    $('#formAdjuntarArchivo').on('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        var submitButton = $(this).find('button[type="submit"]');
        submitButton.prop('disabled', true);

        fetch('./backend/documentos/opcionales_analisis.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.exito) {
                    setAttachedDocuments(formData.get('id_productos_analizados'));
                    alert('Documento subido con éxito');
                    $('#modalAdjuntarArchivo').modal('hide');
                } else {
                    $('#alertaArchivo').text(data.mensaje).show();
                }
            })
            .catch(error => {
                $('#alertaArchivo').text('Error al subir el documento: ' + error).show();
            }).finally(() => {
                submitButton.prop('disabled', false);
            });
    });
</script>