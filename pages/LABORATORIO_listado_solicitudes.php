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
            <button class="estado-filtro badge badge-success" onclick="filtrar_listado('completado', 'estado')">Completado</button>
            <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Pendiente Acta de Muestreo', 'estado')">Pendiente Acta de Muestreo</button>
            <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Pendiente completar análisis', 'estado')">Pendiente completar Análisis</button>
            <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Pendiente envío a Laboratorio', 'estado')">Pendiente envío a Laboratorio</button>
            <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Pendiente ingreso resultados', 'estado')">Pendiente ingreso resultados Laboratorio</button>
            <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Pendiente liberación productos', 'estado')">Pendiente liberación Productos</button>
            <button class="estado-filtro badge" onclick="filtrar_listado('', 'estado')">Todos</button>
        </div>
        <div class="estado-filtros">
            <label> Tipo de Producto </label>
            <button class="estado-filtro badge badge-producto-terminado" onclick="filtrar_listado('Producto Terminado', 'tipo_producto')">Producto Terminado</button>
            <button class="estado-filtro badge badge-material-envase" onclick="filtrar_listado('Material Envase y Empaque', 'tipo_producto')">Material Envase y Empaque</button>
            <button class="estado-filtro badge badge-materia-prima" onclick="filtrar_listado('Materia Prima', 'tipo_producto')">Materia Prima</button>
            <button class="estado-filtro badge badge-insumo" onclick="filtrar_listado('Insumo', 'tipo_producto')">Insumo</button>
            
        </div>
        <div class="estado-filtros">
            <label> </label>
            <button class="estado-filtro badge" onclick="limpiar_filtros()">Limpiar Filtros</button>
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
</body>
<!-- Modal de confirmación de eliminación -->
<div id="modalEliminar" class="modal">
  <div class="modal-content">
    <span class="close" onclick="cerrarModal()">&times;</span>
    <h2>Confirmar Eliminación</h2>
    <p>Por favor, ingresa la palabra <strong>'eliminar'</strong> para confirmar la acción:</p>
    <input type="text" id="confirmacionPalabra" placeholder="Ingrese 'eliminar'" required>
    <p>Motivo de la eliminación:</p>
    <textarea id="motivoEliminacion" placeholder="Ingrese el motivo de la eliminación" required></textarea>
    <button onclick="confirmarEliminacion()">Confirmar</button>
  </div>
</div>
</html>
<script>
    // variable de main js
    QA_solicitud_analisis_editing = true

    // Ahora puedes usar la sintaxis import
    function filtrar_listado(valor, filtro) {
        var table = $('#listado').DataTable();
        
        // Remover clase active de todos los filtros del mismo tipo
        if(filtro == "estado") {
            $('.estado-filtros').first().find('.estado-filtro').removeClass('active');
        } else if(filtro == "tipo_producto") {
            $('.estado-filtros').eq(1).find('.estado-filtro').removeClass('active');
        }
        
        if(filtro=="estado"){
            if (valor === '') {
                // Eliminar todos los filtros
                table.search('').columns().search('').draw();
            } else {
                table.column(1).search(valor).draw();
                // Agregar clase active al botón clickeado
                event.target.classList.add('active');
            }
        }else if(filtro=="tipo_producto"){
            if (valor === '') {
                // Eliminar todos los filtros
                table.search('').columns().search('').draw();
            } else {
                table.column(10).search(valor).draw();
                // Agregar clase active al botón clickeado
                event.target.classList.add('active');
            }
        }
        
        // Actualizar estado del botón "Limpiar Filtros"
        actualizarBotonLimpiarFiltros();
    }
    
    function limpiar_filtros() {
        var table = $('#listado').DataTable();
        // Eliminar todos los filtros de DataTable
        table.search('').columns().search('').draw();
        
        // Remover clase active de todos los filtros
        $('.estado-filtro').removeClass('active');
        
        // Actualizar estado del botón "Limpiar Filtros"
        actualizarBotonLimpiarFiltros();
    }
    
    function actualizarBotonLimpiarFiltros() {
        var hayFiltrosActivos = $('.estado-filtro.active').length > 0;
        var botonLimpiar = $('button[onclick*="limpiar_filtros"]');
        
        if (hayFiltrosActivos) {
            botonLimpiar.addClass('limpiar-filtros-activo');
        } else {
            botonLimpiar.removeClass('limpiar-filtros-activo');
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
                    "title": "Fecha registro"
                },
                {
                    "data": "numero_solicitud_version",
                    "title": "Nro Solicitud"
                },
                {
                    "data": "numero_registro",
                    "title": "Registro"
                },
                {
                    "data": "producto",
                    "title": "Producto"
                },
                {
                    "data": "lote",
                    "title": "Número Lote"
                },
                {
                    "data": "laboratorio",
                    "title": "Laboratorio"
                },
                {
                    title: 'id',
                    data: 'id_analisisExterno',
                    defaultContent: '',
                    visible: false
                },
                {
                    title: 'Producto_filtrado',
                    data: 'producto',
                    visible: false,
                    "render": function(data, type, row) {
                        if (data) {
                            return data.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                        } else {
                            return '';
                        }
                    }
                },
                {
                    title: 'tipo_producto',
                    data: 'tipo_producto',
                    defaultContent: '',
                    visible: false
                }
            ],
            // Definición de los anchos de las columnas utilizando columnDefs
            "columnDefs": [
                { "width": "17px", "targets": 0 },  // Ancho de la columna de detalles
                { "width": "80px", "targets": 1 },  // Ancho para la columna 'Estado'
                { "width": "60px", "targets": 2 },  // Ancho para la columna 'Fecha registro'
                { "width": "100px", "targets": 3 }, // Ancho para la columna 'Nro Solicitud'
                { "width": "90px", "targets": 4 },  // Ancho para la columna 'Registro'
                { "width": "150px", "targets": 5 }, // Ancho para la columna 'Producto'
                { "width": "70px", "targets": 6 },  // Ancho para la columna 'Número Lote'
                { "width": "70px", "targets": 7 }, // Ancho para la columna 'Laboratorio'
            ],
            "autoWidth": false, // Desactiva el ajuste automático de ancho de DataTables
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


            if (d.estado === "Pendiente Acta de Muestreo") {
                acciones += '<button class="accion-btn ingControl" title="Revisar Análisis Externo" type="button" id="' + d.id_analisisExterno + '" name="revisar" onclick="botones(this.id, this.name, \'laboratorio\', \'edicion\')"><i class="fas fa-search"></i> Revisar Solicitud</button><a> </a>';
            }
            
            if (d.estado === "Pendiente completar análisis" && d.solicitado_por === usuarioActual) {
                acciones += '<button class="accion-btn ingControl" title="WIP Revisar Análisis Externo" type="button" id="' + d.id_analisisExterno + '" name="revisar" onclick="botones(this.id, this.name, \'laboratorio\', \'poblar_info_faltante\')"><i class="fas fa-search"></i> Completar información faltante</button><a> </a>';
            }
            //revisar analisis externo
            if ( (d.estado === "Pendiente ingreso resultados laboratorio" ||
                d.estado === "Pendiente ingreso resultados" ) && d.revisado_por === usuarioActual ) {
                acciones +=  `<button class="accion-btn ingControl" title="Ingresar resultados Laboratorio" id="${d.id_analisisExterno}" name="generar_documento_solicitudes" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Ingresar resultados de Laboratorio</button><a> </a>`;
                //acciones += `<button class="accion-btn ingControl" title="Revisar Documento" id="${d.id_analisisExterno}" name="generar_documento_pdf" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Revisar solicitud</button><a> </a>`;
            }

            if (d.estado === "Finalizado") {
                
            }
            if (d.estado === "Pendiente envío a Laboratorio" ) {
                acciones += `<button class="accion-btn ingControl" title="Revisar Documento" id="${d.id_analisisExterno}" name="generar_documento_pdf" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Revisar solicitud</button><a> </a>`;
                if (d.enviado_lab_por === usuarioActual || usuarioActual === 'isumonte'){
                    acciones += `<button class="accion-btn ingControl" title="Enviar a Laboratorio" id="${d.id_analisisExterno}" name="enviarSolicitud_laboratorio" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Enviar Solicitud a Laboratorio</button><a> </a>`;
                }
            }
            if (d.am_ejecutado_por === usuarioActual && d.estado === "Pendiente Acta de Muestreo" && d.id_muestreo === null) {
                acciones += '<button class="accion-btn ingControl" title="Generar Acta de muestreo" id="' + d.id_analisisExterno + '" name="generar_acta_muestreo" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fas fa-check"></i> Generar Acta de Muestreo</button><a> </a>';
            }
            if (d.estado === "Pendiente Acta de Muestreo" && d.estado_muestreo === 'rechazado') {
                acciones += '<button class="accion-btn ingControl" title="Generar Acta de muestreo" id="' + d.id_analisisExterno + '" name="generar_acta_muestreo_versionado" onclick="botones(this.id, this.name, \'laboratorio\',' + d.id_original + ',' + d.version_registro + ')"><i class="fas fa-check"></i> Generar Acta de Muestreo</button><a> </a>';
            }
            if (d.estado === "Pendiente Acta de Muestreo" && d.id_muestreo !== null) {
                if (d.estado_muestreo === "Pendiente Muestreo") {
                    acciones += '<button class="accion-btn ingControl" title="Ingresar resultados Acta Muestreo" type="button" id="' + d.id_muestreo + '" name="resultados_actaMuestreo" onclick="botones(' + d.id_muestreo + ', this.name, \'laboratorio\')"><i class="fas fa-search"></i> Ingresar resultados Acta Muestreo</button><a> </a>';
                }
                if (d.estado_muestreo === "En proceso de firma") {
                    acciones += '<button class="accion-btn ingControl" title="Firmar Acta de Muestreo" id="' + d.id_muestreo + '" name="firmar_acta_muestreo" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-signature"></i> Ir a Firmar Acta Muestreo</button><a> </a>';
                }
            }
            // if (
            //     d.estado.toLowerCase() === "pendiente envío laboratorio" || 
            //     d.estado.toLowerCase() === "pendiente ingreso resultados" || 
            //     d.estado.toLowerCase() === "pendiente liberación producto" ||
            //     d.estado.toLowerCase() === "finalizado"
            // ) {
            //     acciones += `<button class="accion-btn ingControl" title="WIP Firmar Solicitud Análisis Externo" id="${d.id_analisisExterno}" name="generar_documento_solicitudes" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fa fa-file-pdf-o"></i> Revisar analisis externo</button><a> </a>`;
            // }
            
            if ( d.estado === "Pendiente liberación productos") {
            acciones += `<button class="accion-btn ingControl" title="Solicitud Liberacion" type="button" id="${d.id_analisisExterno}" name="Liberacion" onclick="botones(this.id, this.name, \'laboratorio\')"><i class="fas fa-search"></i> Emitir Acta de Liberación</button><a> </a>`;
            }
            // Solo mostrar botón de eliminación para isumonte o isumonte@reccius.cl
            if ('<?php echo $_SESSION['usuario']; ?>' === 'isumonte@reccius.cl' || '<?php echo $_SESSION['usuario']; ?>' === 'isumonte') {
                acciones += `<button class="accion-btn ingControl" title="Eliminar Solicitud" type="button" id="${d.id_analisisExterno}" name="eliminar_analisis_externo" style="background-color: red; color: white;" onclick="botones_interno(this.id, this.name, 'laboratorio')"> <i class="fas fa-trash"></i> Eliminar Análisis Externo</button><a> </a>`;
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
    var idAnalisisExternoAEliminar = null;

function botones_interno(id, accion, modulo) {
  if (accion === 'eliminar_analisis_externo') {
    idAnalisisExternoAEliminar = id;
    abrirModal();
  } else {
    // manejar otras acciones
  }
}

function abrirModal() {
  document.getElementById("modalEliminar").style.display = "flex";
  $.notify("Proceso de eliminacion iniciado.", "success");
}

function cerrarModal() {
  document.getElementById("modalEliminar").style.display = "none";
  $.notify("Proceso de eliminacion iniciado.", "success");
}
// Cierra el modal si el usuario hace clic fuera de él
window.onclick = function(event) {
    let modal = document.getElementById("modalEliminar");
    if (event.target === modal) {
        cerrarModal();
    }
}
function confirmarEliminacion() {
  var palabraConfirmacion = document.getElementById("confirmacionPalabra").value;
  var motivoEliminacion = document.getElementById("motivoEliminacion").value;

  if (palabraConfirmacion !== 'eliminar') {
    
    $.notify("Debe ingresar la palabra 'eliminar' para confirmar.", "warn");
    
    return;
  }

  if (motivoEliminacion.trim() === "") {
    
    $.notify("Debe ingresar un motivo de eliminación.", "warn");
    return;
  }

  var fechaEliminacion = new Date().toISOString().slice(0, 19).replace('T', ' ');

  // Enviar la solicitud POST al backend
  //archivo actual pages\LABORATORIO_listado_solicitudes.php
  //archivo destiino: pages\backend\analisis\eliminar_analisis_externoBE.php
  $.post("./backend/analisis/eliminar_analisis_externoBE.php", {
    id_analisisExterno: idAnalisisExternoAEliminar,
    motivo_eliminacion: motivoEliminacion,
    fecha_eliminacion: fechaEliminacion
}, function(response) {
    // Verificar si hubo algún error en el proceso
    if (response.error) {
        alert("Hubo un error al eliminar el análisis externo: " + response.error);
    } else {
        $.notify("El análisis externo ha sido eliminado con éxito.", "success");
        location.reload(); // Recargar la página o refrescar la tabla
    }
}, "json");

  cerrarModal();
}
</script>