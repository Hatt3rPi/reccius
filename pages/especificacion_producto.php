<?php
//archivo: pages\especificacion_producto.php
//Mejoras: Formato debería seleccionarse de lista desplegable
//Elaborato por
//versión por defecto debería ser 1
//que es el número de documento? 
//cuando se selecciona Otros, se debe desplegar un input
//validacion de campos antes de continuar
// formato: Ampolla, Frasco Ampolla, Vial, Papelillo, Cápsula, Colirio, Ungüento, Jarabe, Crema, etc...

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
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <!-- Asegúrate de incluir el CSS para estilizar tu formulario aquí -->
    <!-- CSS personalizado específico para esta página -->
    <link rel="stylesheet" href="../assets/css/calidad.css">
</head>

<body>
    <div class="form-container">
        <h1>Calidad / Crear Especificación de Producto</h1>
        <form method="POST" id="formulario_especificacion" name="formulario_especificacion" >
            <fieldset>
            <br>
            <br>
                <h2 class="section-title">Especificaciones del producto:</h2>
                <br>
                <div class="form-row">
                    <div class="form-group">
                        <label>Tipo de Producto:</label>
                        <select id="Tipo_Producto" name="Tipo_Producto" class="select-style" style="width: 82.5%;" required>
                            <option value="">Selecciona el tipo de producto</option>    
                            <option value="Material Envase y Empaque">Material Envase y Empaque</option>
                            <option value="Materia Prima">Materia Prima</option>
                            <option value="Producto Terminado">Producto Terminado</option>
                            <option value="Insumo">Insumo</option>
                        </select>
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Producto:</label>
                        <input type="text" name="producto" placeholder="Ácido Ascórbico" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Concentración:</label>
                        <input type="text" name="concentracion" placeholder="1 g / 10 ml" required>
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Formato:</label>
                        <select name="formato" id="formato" class="select-style" required>
                            <option value="">Selecciona un formato</option>
                            <option value="Ampolla">Ampolla</option>
                            <option value="Frasco Ampolla">Frasco Ampolla</option>
                            <option value="Vial">Vial</option>
                            <option value="Papelillo">Papelillo</option>
                            <option value="Cápsula">Cápsula</option>
                            <option value="Colirio">Colirio</option>
                            <option value="Ungüento">Ungüento</option>
                            <option value="Jarabe">Jarabe</option>
                            <option value="Crema">Crema</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Elaborado por:</label>
                        <input type="text" name="elaboradoPor" Value="Reccius" required>
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>País de origen:</label>
                        <input type="text" name="paisOrigen" Value="Chile" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Documento:</label>
                        <div class="form-row">
                            <!-- Campo para el prefijo del documento -->
                            <input type="text" name="prefijoDocumento" id="prefijoDocumento" disabled style="col-2">
                            
                            <!-- Campo para el número de producto -->
                            <input pattern="\d{1,3}" type="text" name="numeroProducto" placeholder="001" required style="col">
                        </div>                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">

                    </div>
                </div>
                <br>
                <br>
                <h2 class="section-title">Detalles de la Especificación:</h2>
                <br>
                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha edición:</label>
                        <input type="date" id="fechaEdicion" name="fechaEdicion" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Versión:</label>
                        <input type="text" name="version" value="1" disabled>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Vigencia:</label>
                        <select name="periodosVigencia" id="periodosVigencia" class="select-style"  style="width: 38.5%" required>
                            <option>Selecciona la vigencia de esta especificación:</option>
                            <option value=1>1 año</option>
                            <option value=2>2 años</option>
                            <option value=3>3 años</option>
                            <option value=4>4 años</option>
                            <option value=5>5 años</option>
                        </select>
                    </div>

                    <div class="form-group form-group-hidden">
                        <label>Próxima renovación:</label>
                        <input type="date" name="proximaRenovacion" readonly>
                    </div>

                </div>
            </fieldset>
            <br>
            <br>
            <h2 class="section-title">Análisis Físico-Químicos:</h2>
            <div id="contenedor_analisisFQ">
                <table id="analisisFQ" class="table table-striped table-bordered" width="100%"></table>
                <!-- Aquí se incluirá la tabla desde carga_tablaFQ()-->
            </div>
            <button type="button" id="boton_agrega_analisisFQ">Agregar Análisis</button>
            <br>
            <br>
            <br>
            <h2 class="section-title">Análisis Microbiológicos:</h2>
            <div id="contenedor_analisisMB">
                <table id="analisisMB" class="table table-striped table-bordered" width="100%"></table>
                <!-- Aquí se incluirá la tabla desde carga_tablaMB()-->
            </div>
            <button type="button" id="boton_agrega_analisisMB">Agregar Análisis</button>
            <div class="actions-container">
                <button type="button" id="guardar" name="guardar" class="action-button">Guardar Especificación</button>


            </div>
        </form>
    </div>
</body>

</html>
<script>
function carga_tablaFQ(id = null, accion = null) {
    var tablaFQ;
        if (id===null) {
            var tablaFQ = new DataTable('#analisisFQ', {
                    "paging": false,  // Desactiva la paginación
                    "info": false,    // Oculta el texto "Showing 1 to X of X entries"
                    "searching": false,  // Desactiva la búsqueda
                    "lengthChange": false, // Oculta el selector "Show X entries"
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                    },
                    columns: [
                        { title: 'Análisis' },
                        { title: 'Metodología' },
                        { title: 'Criterio aceptación' }, 
                        { title: 'Acciones' }
                    ]
                });
                var contadorFilasFQ = 0;
                $('#boton_agrega_analisisFQ').on('click', function() {
                    // Verificar si la tabla se cargó correctamente antes de agregar filas
                    if ($.fn.DataTable.isDataTable('#analisisFQ')) {
                        tablaFQ.row.add([
                    '<select name="analisisFQ[' + contadorFilasFQ + '][descripcion_analisis]" class="select-style" required>' +
                        '<option value="">Selecciona un análisis</option>' +
                        '<option value="Apariencia">Apariencia</option>' +
                        '<option value="Identificación">Identificación</option>' +
                        '<option value="Valoración">Valoración</option>' +
                        '<option value="Contenido">Contenido</option>' +
                        '<option value="pH">pH</option>' +
                        '<option value="Densidad">Densidad</option>' +
                        '<option value="Osmolaridad">Osmolaridad</option>' +
                        '<option value="Límite de Oxalato">Límite de Oxalato</option>' +
                        '<option value="Volumen extraíble">Volumen extraíble</option>' +
                        '<option value="Material Sub particulado">Material Sub particulado</option>' +
                        '<option value="Material Particulado">Material Particulado</option>' +
                        '<option value="Otro">Otro</option>' +
                    '</select>',
                    '<select name="analisisFQ[' + contadorFilasFQ + '][metodologia]" class="select-style" required>' +
                        '<option value="">Selecciona metodología</option>' +
                        '<option value="Interno">Interno</option>' +
                        '<option value="USP">USP</option>' +
                        '<option value="Otro">Otro</option>' +
                    '</select>',
                    '<textarea rows="4" cols="50" name="analisisFQ[' + contadorFilasFQ + '][criterio]" required></textarea>',
                    '<button type="button" class="btn-eliminar">Eliminar</button>'
                ]).draw(false);
                contadorFilasFQ++;
                } else {
                    console.error('Error: La tabla no está inicializada.');
                    alert('Error al cargar la tabla. Por favor, intente de nuevo.');
                }
            });
        $("#boton_agrega_analisisFQ").show();
        } else if (accion === 'editar') {
        // Cargar la tabla con datos para la edición
        tablaFQ = new DataTable('#analisisFQ', {
            "ajax": './backend/calidad/listado_analisis_por_especificacion.php?id=' + id + '&analisis=analisis_FQ',
            "columns": [
                { "data": "descripcion_analisis", "title": "Análisis" },
                { "data": "metodologia", "title": "Metodología" },
                { "data": "criterios_aceptacion", "title": "Criterio aceptación" }
            //,
               // {
               //    "data": null,
               //     "defaultContent": '<button type="button" class="btn-eliminar">Eliminar</button>',
               //     "title": "Acciones"
               // }
            ],
            "paging": false,
            "info": false,
            "searching": false,
            "lengthChange": false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
            }
        });

        // Evento para el botón eliminar en la tabla de edición
        $('#analisisFQ').on('click', '.btn-eliminar', function () {
            tablaFQ.row($(this).parents('tr')).remove().draw();
        });

        // Ocultar el botón de agregar análisis, si es necesario
        $("#boton_agrega_analisisFQ").hide();
    }
}
$('#analisisFQ').on('click', '.btn-eliminar', function () {
    var tablaFQ = $('#analisisFQ').DataTable();
    tablaFQ.row($(this).parents('tr')).remove().draw();
});
function carga_tablaMB(id = null, accion = null) {
    var tablaMB;
        if (id===null) {
            var tablaMB = new DataTable('#analisisMB', {
                    "paging": false,  // Desactiva la paginación
                    "info": false,    // Oculta el texto "Showing 1 to X of X entries"
                    "searching": false,  // Desactiva la búsqueda
                    "lengthChange": false, // Oculta el selector "Show X entries"
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                    },
                    columns: [
                        { title: 'Análisis' },
                        { title: 'Metodología' },
                        { title: 'Criterio aceptación' }, 
                        { title: 'Acciones' }
                    ]
                });
                var contadorFilasMB = 0;
                $('#boton_agrega_analisisMB').on('click', function() {
                    // Verificar si la tabla se cargó correctamente antes de agregar filas
                    if ($.fn.DataTable.isDataTable('#analisisMB')) {
                        tablaMB.row.add([
                        '<select name="analisisMB[' + contadorFilasMB + '][descripcion_analisis]" class="select-style" required>' + 
                            '<option value="">Selecciona un análisis</option>' +
                            '<option value="Esterilidad">Esterilidad</option>' +
                            '<option value="Endotoxinas">Endotoxinas</option>' +
                            '<option value="Otro">Otro</option>' +
                        '</select>',
                        '<select name="analisisMB[' + contadorFilasMB + '][metodologia]" class="select-style" required>' +
                            '<option value="">Selecciona metodología</option>' +
                            '<option value="Interno">Interno</option>' +
                            '<option value="USP">USP</option>' +
                            '<option value="Otro">Otro</option>' +
                        '</select>',
                        '<textarea rows="4" cols="50" name="analisisMB[' + contadorFilasMB + '][criterio]" required></textarea>',
                        '<button type="button" class="btn-eliminar">Eliminar</button>'
                        
                    ]).draw(false);
                    contadorFilasMB++;
                    } else {
                        console.error('Error: La tabla no está inicializada.');
                        alert('Error al cargar la tabla. Por favor, intente de nuevo.');
                    }
                });
            $("#boton_agrega_analisisMB").show();
        } else if (accion === 'editar') {
        // Cargar la tabla con datos para la edición
        tablaMB = new DataTable('#analisisMB', {
            "ajax": './backend/calidad/listado_analisis_por_especificacion.php?id=' + id + '&analisis=analisis_MB',
            "columns": [
                { "data": "descripcion_analisis", "title": "Análisis" },
                { "data": "metodologia", "title": "Metodología" },
                { "data": "criterios_aceptacion", "title": "Criterio aceptación" }
            //,
               // {
               //    "data": null,
               //     "defaultContent": '<button type="button" class="btn-eliminar">Eliminar</button>',
               //     "title": "Acciones"
               // }
            ],
            "paging": false,
            "info": false,
            "searching": false,
            "lengthChange": false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
            }
        });

        // Evento para el botón eliminar en la tabla de edición
        $('#analisisMB').on('click', '.btn-eliminar', function () {
            tablaMB.row($(this).parents('tr')).remove().draw();
        });

        // Ocultar el botón de agregar análisis, si es necesario
        $("#boton_agrega_analisisMB").hide();
    }
}

// Asegúrate de llamar a carga_tablaMB con los parámetros adecuados donde corresponda.

    $('#analisisMB').on('click', '.btn-eliminar', function () {
    var tablaMB = $('#analisisMB').DataTable();
    tablaMB.row($(this).parents('tr')).remove().draw();
});
function calcularProximaRenovacion() {
        var fechaEdicion = $('#fechaEdicion').val();
        var añosVigencia = $('#periodosVigencia').val();
        var proximaRenovacionContainer = $('#proximaRenovacion').closest('.form-group');

        if (fechaEdicion && añosVigencia) {
            var fechaEdicionDate = new Date(fechaEdicion);
            fechaEdicionDate.setFullYear(fechaEdicionDate.getFullYear() + parseInt(añosVigencia));
            $('#proximaRenovacion').val(fechaEdicionDate.toISOString().split('T')[0]);
            proximaRenovacionContainer.show();
        } else {
            proximaRenovacionContainer.hide();
        }
    }

    $('#periodosVigencia').on('change', calcularProximaRenovacion);

document.getElementById('guardar').addEventListener('click', function(e) {
    if (!validarFormulario()) {
        e.preventDefault(); // Previene el envío del formulario si la validación falla
    }
});

function validarFormulario() {
    var valido = true;
    var mensaje = '';

    // Validación para el campo 'Tipo de Producto'
    if (document.forms[0]["Tipo_Producto"].value.trim() === '') {
        mensaje += 'El campo "Tipo de Producto" es obligatorio.\n';
        valido = false;
    }

    // Validación para el campo 'Producto'
    if (document.forms[0]["producto"].value.trim() === '') {
        mensaje += 'El campo "Producto" es obligatorio.\n';
        valido = false;
    }

    // Validación para el campo 'Concentración'
    if (document.forms[0]["concentracion"].value.trim() === '') {
        mensaje += 'El campo "Concentración" es obligatorio.\n';
        valido = false;
    }

    // Validación para el campo 'Formato'
    if (document.forms[0]["formato"].value.trim() === '') {
        mensaje += 'El campo "Formato" es obligatorio.\n';
        valido = false;
    }

    // Validación para el campo 'Elaborado por'
    if (document.forms[0]["elaboradoPor"].value.trim() === '') {
        mensaje += 'El campo "Elaborado por" es obligatorio.\n';
        valido = false;
    }

    // Validación para el campo 'Número de documento'
    if (document.forms[0]["documento"].value.trim() === '') {
        mensaje += 'El campo "Número de documento" es obligatorio.\n';
        valido = false;
    }

    // Validación para el campo 'Fecha edición'
    if (document.forms[0]["fechaEdicion"].value.trim() === '') {
        mensaje += 'El campo "Fecha edición" es obligatorio.\n';
        valido = false;
    }

    // Validación para el campo 'Versión'
    if (document.forms[0]["version"].value.trim() === '') {
        mensaje += 'El campo "Versión" es obligatorio.\n';
        valido = false;
    }

    // Validación para el campo 'Vigencia'
    if (document.forms[0]["periodosVigencia"].value.trim() === '') {
        mensaje += 'El campo "Vigencia" es obligatorio.\n';
        valido = false;
    }

    var valido = true;
    var mensaje = '';

    // Función para validar un conjunto de análisis
    function validarAnalisis(selector, tipoAnalisis) {
        $(selector).find('tbody tr').each(function() {
            var tipo = $(this).find('select[name*="[descripcion_analisis]"]').val();
            var metodologia = $(this).find('select[name*="[metodologia]"]').val();
            var criterio = $(this).find('textarea[name*="[criterio]"]').val();

            if (tipo === '' || metodologia === '' || criterio.trim() === '') {
                mensaje += 'Todos los campos de Análisis ' + tipoAnalisis + ' son obligatorios en cada fila.\n';
                valido = false;
            }
        });
    }

    // Validar análisis Físico-Químicos si existen
    if ($('#analisisFQ').find('tbody tr').length > 0) {
        validarAnalisis('#analisisFQ', 'Físico-Químicos');
    }

    // Validar análisis Microbiológicos si existen
    if ($('#analisisMB').find('tbody tr').length > 0) {
        validarAnalisis('#analisisMB', 'Microbiológicos');
    }

    // Procesar la validación
    if (!valido) {
        alert(mensaje);
    }

    return valido;
}

function cargarDatosEspecificacion(id) {
    $.ajax({
        url: './backend/calidad/listado_analisis_por_especificacion.php',
        type: 'GET',
        data: { id: id },
        success: function(response) {
            procesarDatosEspecificacion(response);

            // Ocultar los botones "Agregar análisis"
            $('#boton_agrega_analisisFQ').hide();
            $('#boton_agrega_analisisMB').hide();

            // Ocultar el botón "Guardar especificación"
            $('#guardar').hide();

            // Agregar un nuevo botón para "Editar y generar nueva versión"
            var nuevoBoton = $('<button/>', {
                text: 'Editar y generar nueva versión',
                id: 'editarGenerarVersion',
                class: 'action-button',
                style: 'background-color: red; color: white;',
                click: function() {
                    // Aquí puedes agregar la lógica que se ejecutará cuando se haga clic en este botón
                    console.log('Editar y generar nueva versión');
                }
            });
            $('.actions-container').append(nuevoBoton);
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud: ", status, error);
        }
    });
}


function procesarDatosEspecificacion(response) {
    // Asegúrate de que 'response' contiene la propiedad 'productos'
    if (!response || !response.productos || !Array.isArray(response.productos)) {
        console.error('Los datos recibidos no son válidos:', response);
        return;
    }

    // Procesar cada producto
    response.productos.forEach(function(producto) {
        poblarYDeshabilitarCamposProducto(producto);

        // Procesar la primera especificación para cada producto
        let especificaciones = Object.values(producto.especificaciones || {});
        if (especificaciones.length > 0) {
            let especificacion = especificaciones[0];
            let analisisFQ = especificacion.analisis.filter(a => a.tipo_analisis === 'analisis_FQ');
            let analisisMB = especificacion.analisis.filter(a => a.tipo_analisis === 'analisis_MB');

            mostrarAnalisisFQ(analisisFQ);
            mostrarAnalisisMB(analisisMB);
        }
    });
}

function poblarYDeshabilitarCamposProducto(producto) {
    $('#Tipo_Producto').val(producto.tipo_producto).prop('disabled', true);
    $('input[name="producto"]').val(producto.nombre_producto).prop('disabled', true);
    $('input[name="concentracion"]').val(producto.concentracion).prop('disabled', true);
    $('#formato').val(producto.formato).prop('disabled', true);
    $('input[name="elaboradoPor"]').val(producto.elaborado_por).prop('disabled', true);
    $('input[name="paisOrigen"]').val(producto.pais_origen).prop('disabled', true);
    $('input[name="documento"]').val(producto.documento_producto).prop('disabled', true);
    let especificacion = Object.values(producto.especificaciones)[0];
    if (especificacion) {
        // Suponiendo que 'fecha_expiracion', 'version', y 'vigencia' están en la especificación
        $('#fechaEdicion').val(especificacion.fecha_edicion).prop('disabled', true);
        $('input[name="version"]').val(especificacion.version).prop('disabled', true); // Asegúrate de que 'version' exista en tus datos
        $('#periodosVigencia').val(especificacion.vigencia).prop('disabled', true); // Asegúrate de que 'vigencia' exista en tus datos
    }
    console.log(producto.tipo_producto);
}

function mostrarAnalisisFQ(analisis) {
    if ($.fn.DataTable.isDataTable('#analisisFQ')) {
        $('#analisisFQ').DataTable().clear().rows.add(analisis).draw();
    } else {
        $('#analisisFQ').DataTable({
            data: analisis,
            columns: [
                { title: 'Análisis', data: 'descripcion_analisis' },
                { title: 'Metodología', data: 'metodologia' },
                { title: 'Criterio aceptación', data: 'criterios_aceptacion' },
                {
                    title: 'Acciones',
                    data: null,
                    defaultContent: '', // Puedes cambiar esto si deseas poner contenido por defecto
                    visible: false // Esto oculta la columna
                }
                // Agrega aquí más columnas si es necesario
            ],
            paging: false,
            info: false,
            searching: false,
            lengthChange: false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
            }
        });
    }
}

function mostrarAnalisisMB(analisis) {
    if ($.fn.DataTable.isDataTable('#analisisMB')) {
        $('#analisisMB').DataTable().clear().rows.add(analisis).draw();
    } else {
        $('#analisisMB').DataTable({
            data: analisis,
            columns: [
                { title: 'Análisis', data: 'descripcion_analisis' },
                { title: 'Metodología', data: 'metodologia' },
                { title: 'Criterio aceptación', data: 'criterios_aceptacion' },
                {
                    title: 'Acciones',
                    data: null,
                    defaultContent: '', // Puedes cambiar esto si deseas poner contenido por defecto
                    visible: false // Esto oculta la columna
                }
            ],
            paging: false,
            info: false,
            searching: false,
            lengthChange: false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
            }
        });
    }
}

$('#editarGenerarVersion').click(function() {
    // Resto del código para habilitar edición del formulario...
    $('#guardar').show();
    $('#Tipo_Producto').prop('disabled', false);
    $('input[name="producto"]').prop('disabled', false);
    $('input[name="concentracion"]').prop('disabled', false);
    $('input[name="formato"]').prop('disabled', false);
    $('input[name="elaboradoPor"]').prop('disabled', false);
    $('input[name="documento"]').prop('disabled', false);
    $('input[name="fechaEdicion"]').prop('disabled', false).val(new Date().toISOString().split('T')[0]);
    
    // Incrementar la versión en 1 y mantenerla no editable
    var versionActual = parseInt($('input[name="version"]').val()) || 0;
    $('input[name="version"]').val(versionActual + 1);

    // Hacer visible la columna de acciones en ambas tablas
    var tablaFQ = $('#analisisFQ').DataTable();
    var tablaMB = $('#analisisMB').DataTable();

    tablaFQ.column('.acciones').visible(true);
    tablaMB.column('.acciones').visible(true);

    // Habilitar la adición y eliminación de análisis
    habilitarEdicionAnalisis(tablaFQ);
    habilitarEdicionAnalisis(tablaMB);
});

function habilitarEdicionAnalisis(tabla) {
    // Ejemplo de cómo habilitar la adición de nuevos análisis
    $('#boton_agrega_analisis' + tabla.node().id).show();

    // Habilitar la eliminación de análisis existentes
    // Aquí puedes agregar el código que permite eliminar filas en la tabla
    tabla.on('click', '.btn-eliminar', function() {
        tabla.row($(this).parents('tr')).remove().draw();
    });
}

function guardar(){
    
    var datosFormulario = $('#formulario_especificacion').serialize();
    console.log(datosFormulario);
    $.ajax({
        url: 'backend/calidad/especificacion_productoBE.php',
        type: 'POST',
        data: datosFormulario,
        success: function(data) {
            var respuesta = JSON.parse(data);
            if (respuesta.exito) {
                $('#dynamic-content').load('listado_especificaciones_producto.php', function (response, status, xhr) {
                    if (status == "error") {
                        console.log("Error al cargar el formulario: " + xhr.status + " " + xhr.statusText);
                    } else {
                        console.log('Listado cargado correctamente cargado exitosamente.');
                        carga_listadoEspecificacionesProductos();
                        console.log(respuesta.mensaje); // Manejar el error
                        //table.columns(9).search(buscarId).draw();
                        
                    }
                });
            } else {
                console.log(respuesta.mensaje); // Manejar el error
            }
        },
        error: function(xhr, status, error) {
            console.log("Error AJAX: " + error);
        }
    });
}
$('#guardar').click(function() {
        guardar();
    });

    $('#Tipo_Producto').on('change', function() {
    var tipoProducto = $(this).val();
    var prefijo = '';

    switch (tipoProducto) {
        case 'Material Envase y Empaque':
            prefijo = 'DCAL-CC-EME-';
            break;
        case 'Materia Prima':
            prefijo = 'DCAL-CC-EMP-';
            break;
        case 'Producto Terminado':
            prefijo = 'DCAL-CC-EPT-';
            break;
        case 'Insumo':
            prefijo = 'DCAL-CC-EIN-';
            break;
    }

    $('#prefijoDocumento').val(prefijo);
});
</script>
