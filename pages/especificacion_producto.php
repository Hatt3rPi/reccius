<?php
session_start();

// Verificar si la variable de sesión "usuario" no está establecida o está vacía.
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    // Redirigir al usuario a la página de inicio de sesión.
    header("Location: login.html");
    exit;
}

$esNuevo = isset($_GET['nuevo']) && $_GET['nuevo'] == 'true';

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    

<STYle>
    .form-group input[type="date"] {
        width: 80%; /* Ajusta el ancho al de su contenedor */
        padding: 10px; /* Espaciado interno para que el texto no esté pegado a los bordes */
        margin: 5px 0; /* Espaciado exterior para separar los campos entre sí */
        display: inline-block; /* Asegura que se comporten como bloques pero en línea */
        border: 1px solid #ccc; /* Borde gris claro, puedes cambiarlo por el color que prefieras */
        border-radius: 4px; /* Bordes redondeados para suavizar la apariencia */
        box-sizing: border-box; /* Asegura que padding y border no aumenten el ancho total */
    }
    .section-title {
            font-weight: bold;  /* Negrita */
            text-decoration: underline;  /* Subrayado */
            text-align: left;  /* Alineación a la izquierda */
            margin-top: 20px;  /* Espaciado superior para separación */
        }
</STYle>
    <!-- Asegúrate de incluir el CSS para estilizar tu formulario aquí -->
    <!-- CSS personalizado específico para esta página -->
    <link rel="stylesheet" href="../assets/css/calidad.css">
</head>

<body>
    <div class="form-container">
        <h1>Calidad / Crear Especificación de Producto</h1>
        <form method="POST" action="./backend/calidad/especificacion_productoBE.php">
            <fieldset>
            <br>
            <br>
                <h2 class="section-title">Especificaciones del producto</h2>
                <br>
                <div class="form-row">
                    <div class="form-group">
                        <label>Tipo de Producto:</label>
                        <select id="Tipo_Producto" name="Tipo_Producto" class="select-style" style="width: 82.5%;">
                            <option value="Menvase">Material Envase y Empaque</option>
                            <option value="Mprima">Materia Prima</option>
                            <option value="Pterminado">Producto Terminado</option>
                            <option value="Insumo">Insumo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Producto:</label>
                        <input type="text" name="producto" placeholder="Ácido Ascórbico">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Concentración:</label>
                        <input type="text" name="concentracion" placeholder="1 g / 10 ml">
                    </div>
                    <div class="form-group">
                        <label>Formato:</label>
                        <input type="text" name="formato" placeholder="Ampolla">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Elaborado por:</label>
                        <input type="text" name="elaboradoPor" placeholder="Reccius">
                    </div>
                    <div class="form-group">
                        <label>Número de documento:</label>
                        <input type="text" name="documento" placeholder="12345678">
                    </div>
                </div>
                <br>
                <br>
                <h2 class="section-title">Detalles de la Especificación</h2>
                <br>
                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha edición:</label>
                        <input type="date" name="fechaEdicion" placeholder="dd/mm/aaaa">
                    </div>
                    <div class="form-group">
                        <label>Versión:</label>
                        <input type="text" name="version" placeholder="1">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Vigencia:</label>
                        <input type="text" name="vigencia" placeholder="5 años" style="width: 39.5%;"> <!-- Evaluar SVA. se podría mostrar las especificaciones que estén próximas a vencer -->
                    </div>
                </div>
            </fieldset>
            <br>
            <br>
            <h2 class="section-title">Análisis Físico-Químicos</h2>
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
                <button type="submit" id="guardar" class="action-button">Guardar Especificación</button>


            </div>
        </form>
    </div>
</body>

</html>
<script>
    function carga_tablaFQ() {
        var esNuevo = <?php echo json_encode($esNuevo); ?>;
        if (esNuevo) {
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
                $('#boton_agrega_analisisFQ').on('click', function() {
                    // Verificar si la tabla se cargó correctamente antes de agregar filas
                    if ($.fn.DataTable.isDataTable('#analisisFQ')) {
                        tablaFQ.row.add([
                        '<select name="c' + 
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
                        '<select name="analisisFQ[0][metodologia]">' +
                            '<option value="">Selecciona metodología</option>' +
                            '<option value="Interno">Interno</option>' +
                            '<option value="USP">USP</option>' +
                            '<option value="Otro">Otro</option>' +
                        '</select>',
                        '<textarea rows="4" cols="50" name="analisisFQ[0][criterio]"></textarea>',
                        '<button type="button" class="btn-eliminar">Eliminar</button>'
                        
                    ]).draw(false);
                    } else {
                        console.error('Error: La tabla no está inicializada.');
                        alert('Error al cargar la tabla. Por favor, intente de nuevo.');
                    }
                });
            $("#boton_agrega_analisisFQ").show();
        }
        else {
            $("#contenedor_analisisFQ").load("./backend/calidad/datatables_analisis.html", function(response, status, xhr) {
            if (status == "error") {
                console.error("Error al cargar el archivo: ", xhr.status, xhr.statusText);
                alert("Error al cargar la tabla. Revise la consola para más detalles.");
                return;
            }
            try {
                // Intentar inicializar DataTables
                var tabla = $('#analisisFQ').DataTable();
            } catch (error) {
                console.error('Error al inicializar la tabla: ', error);
                alert('Error al cargar la tabla. Por favor, revise la consola para más detalles.');
            }
        });
        }
    }
    $('#analisisFQ').on('click', '.btn-eliminar', function () {
    var tablaFQ = $('#analisisFQ').DataTable();
    tablaFQ.row($(this).parents('tr')).remove().draw();
});
function carga_tablaMB() {
        var esNuevo = <?php echo json_encode($esNuevo); ?>;
        if (esNuevo) {
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
                $('#boton_agrega_analisisMB').on('click', function() {
                    // Verificar si la tabla se cargó correctamente antes de agregar filas
                    if ($.fn.DataTable.isDataTable('#analisisMB')) {
                        tablaMB.row.add([
                        '<select name="analisisMB[0][tipo]">">' + 
                            '<option value="">Selecciona un análisis</option>' +
                            '<option value="Esterilidad">Esterilidad</option>' +
                            '<option value="Endotoxinas">Endotoxinas</option>' +
                            '<option value="Otro">Otro</option>' +
                        '</select>',
                        '<select name="analisisMB[0][metodologia]">' +
                            '<option value="">Selecciona metodología</option>' +
                            '<option value="Interno">Interno</option>' +
                            '<option value="USP">USP</option>' +
                            '<option value="Otro">Otro</option>' +
                        '</select>',
                        '<textarea rows="4" cols="50" name="analisisMB[0][criterio]"></textarea>',
                        '<button type="button" class="btn-eliminar">Eliminar</button>'
                        
                    ]).draw(false);
                    } else {
                        console.error('Error: La tabla no está inicializada.');
                        alert('Error al cargar la tabla. Por favor, intente de nuevo.');
                    }
                });
            $("#boton_agrega_analisisMB").show();
        }
        else {
            $("#contenedor_analisisMB").load("./backend/calidad/datatables_analisis.html", function(response, status, xhr) {
            if (status == "error") {
                console.error("Error al cargar el archivo: ", xhr.status, xhr.statusText);
                alert("Error al cargar la tabla. Revise la consola para más detalles.");
                return;
            }
            try {
                // Intentar inicializar DataTables
                var tabla = $('#analisisMB').DataTable();
            } catch (error) {
                console.error('Error al inicializar la tabla: ', error);
                alert('Error al cargar la tabla. Por favor, revise la consola para más detalles.');
            }
        });
        }
    }
    $('#analisisMB').on('click', '.btn-eliminar', function () {
    var tablaMB = $('#analisisMB').DataTable();
    tablaMB.row($(this).parents('tr')).remove().draw();
});
</script>
