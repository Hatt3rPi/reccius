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
        <h1>Calidad / Listado Especificaciones de Productos</h1>
            <br>
            <br>
            <h2 class="section-title">Listado Especificaciones de Productos:</h2>
            <div class="estado-filtros">
                <label>               Filtrar por:</label>
                <button class="estado-filtro badge badge-success" onclick="filtrar_listado('Vigente', 'estado')">Vigente</button>
                <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Pendiente de Revisión', 'estado')">Pendiente de Revisión</button>
                <button class="estado-filtro badge badge-warning" onclick="filtrar_listado('Pendiente de Aprobación', 'estado')">Pendiente de Aprobación</button>
                <button class="estado-filtro badge badge-dark" onclick="filtrar_listado('Especificación obsoleta', 'estado')">Especificación obsoleta</button>
                <button class="estado-filtro badge badge-dark" onclick="filtrar_listado('Expirado', 'estado')">Expirado</button>
                <button class="estado-filtro badge" onclick="filtrar_listado('', 'estado')">Todos</button>
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
<div id="notification" class="notification-container notify" style="display: none;">
    <p id="notification-message">Este es un mensaje de notificación.</p>
</div>
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
    QA_solicitud_analisis_editing = false

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
                table.column(5).search(valor).draw(); // Asumiendo que la columna 1 es la de
            }
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
    function carga_listado_especificacionProducto() {
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
    acciones += '<button class="accion-btn ingControl" title="Revisar Especificación" type="button" id="' + d.id_especificacion + '" name="revisar" onclick="botones(this.id, this.name, \'especificacion\')"><i class="fas fa-search"></i> Ver Formulario de Especificación</button><a> </a>';
    acciones += '<button class="accion-btn ingControl" title="Generar Documento" id="' + d.id_especificacion + '" name="generar_documento" onclick="botones(this.id, this.name, \'especificacion\')"><i class="fa fa-file-pdf-o"></i> Ver documento</button><a> </a>';
    // Botón para generar acta de muestreo, visible solo si el estado es 'Vigente'
    if (d.estado === 'Vigente') {
        acciones += '<button class="accion-btn ingControl" title="Generar Solicitud de Análisis externo" id="' + d.id_especificacion + '" name="prepararSolicitud" onclick="botones({analisisExterno:0, especificacion:this.id}, this.name, \'especificacion\')"><i class="fas fa-vial"></i> Generar Solicitud de análisis externo</button><a> </a>';
    }
    acciones += '<button class="accion-btn ingControl" title="Eliminar Especificación de Producto" id="' + d.id_especificacion + '" name="eliminar_especificacion_producto" style="background-color: red; color: white;" onclick="botones_interno(this.id, this.name, \'laboratorio\')"><i class="fas fa-trash"></i> Eliminar Especificación de Producto</button><a> </a>';
    acciones += '</td></tr></table>';
    return acciones;
}


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
var id_especificacionProducto = null;

function botones_interno(id, accion, modulo) {
  if (accion === 'eliminar_especificacion_producto') {
    console.log(id, accion, modulo);
    id_especificacionProducto = id;
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
  $.notify("Proceso de eliminacion cerrado .", "success");
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
  $.post("./backend/calidad/eliminar_especificacion_producto.php", {
    id_especificacionProducto: id_especificacionProducto,
    motivo_eliminacion: motivoEliminacion,
    fecha_eliminacion: fechaEliminacion
}, function(response) {
    console.log(response);
    // Verificar si hubo algún error en el proceso
    if (response.error) {
        $.notify("Hubo un error al eliminar el análisis externo: " + response.error);
    } else {
        $.notify("Documento eliminado correctamente.", "success");
        location.reload(); // Recargar la página o refrescar la tabla
    }
}, "json");

  cerrarModal();
}

</script>
