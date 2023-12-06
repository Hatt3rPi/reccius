<?php
session_start();

// Verificar si la variable de sesión "usuario" no está establecida o está vacía.
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    // Redirigir al usuario a la página de inicio de sesión.
    header("Location: login.html");
    exit;
}

if (isset($_GET['nuevo']) && $_GET['nuevo'] == 'true') {
    // Mostrar formulario para nueva especificación
    // ...
} else {
    // Lógica para mostrar/editar una especificación existente
    // ...
}

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
</STYle>
    <!-- Asegúrate de incluir el CSS para estilizar tu formulario aquí -->
    <!-- CSS personalizado específico para esta página -->
    <link rel="stylesheet" href="../assets/css/calidad.css">
</head>

<body>
    <div class="form-container">
        <h1>CREAR ESPECIFICACIÓN DE PRODUCTO</h1>
        <form>
            <fieldset>
                <legend>Especificaciones del producto:</legend>

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
                        <input type="text" placeholder="Ácido Ascórbico">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Concentración:</label>
                        <input type="text" placeholder="1 g / 10 ml">
                    </div>
                    <div class="form-group">
                        <label>Formato:</label>
                        <input type="text" placeholder="Ampolla">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Elaborado por:</label>
                        <input type="text" placeholder="Reccius">
                    </div>
                    <div class="form-group">
                        <label>Número de documentor:</label>
                        <input type="text" placeholder="12345678">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha edición:</label>
                        <input type="date" placeholder="dd/mm/aaaa">
                    </div>
                    <div class="form-group">
                        <label>Versión:</label>
                        <input type="text" placeholder="1">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Vigencia:</label>
                        <input type="text" placeholder="5 años" style="width: 39.5%;">
                    </div>
                </div>
            </fieldset>
            <br>
            <br>
            <h2>Análisis Físico-Químicos:</h2>
            <div id="contenedor_analisisFQ">
                <!-- Aquí se incluirá la tabla desde tablas.php -->
            </div>
            <button type="button" id="boton_agrega_analisisFQ">Agregar Análisis</button>
            <table class="analysis-table" id="tablaanalisisFQ">
                <thead>
                    <tr>
                        <th>#</th> <!-- correlativo de análisis -->
                        <th>Análisis</th>
                        <th>Metodología</th>
                        <th>Criterio de Aceptación</th>
                    </tr>
                </thead>
            </table>
            <button type="button" onclick="addAnalysis()">Agregar Análisis</button>


            <!-- Fin de la sección de Análisis Físico-Químicos -->

            <br>
            <br>
            <h2>Análisis Microbiológicos:</h2>
            <table class="analysis-table" id="analisisMB">
                <thead>
                    <tr>
                        <th>#</th> <!-- correlativo de análisis -->
                        <th>Análisis</th>
                        <th>Metodología</th>
                        <th>Criterio de Aceptación</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="editable-row">
                        <td><input type="text" value="Apariencia" class="editable" disabled></td>
                        <td><input type="text" value="interno" class="editable" disabled></td>
                        <td class="criteria-cell.editable"><textarea class="editable"
                                disabled>Lorem ipsum dolor sit amet, consectetur adipiscing...</textarea></td>
                        <td>
                            <button type="button" onclick="toggleEdit(this)">Modificar</button>
                            <button type="button" onclick="deleteRow(this)">Eliminar</button>

                        </td>
                    </tr>
                    <tr class="editable-row">
                        <td><input type="text" value="Identificación" class="editable" disabled></td>
                        <td><input type="text" value="USP" class="editable" disabled></td>
                        <td class="criteria-cell.editable"><textarea class="editable"
                                disabled>Lorem ipsum dolor sit amet, consectetur adipiscing...</textarea></td>
                        <td>
                            <button type="button" onclick="toggleEdit(this)">Modificar</button>
                            <button type="button" onclick="deleteRow(this)">Eliminar</button>

                        </td>
                    </tr>
                    <!-- Repetir filas según sea necesario -->
                </tbody>
            </table>
            <button type="button" onclick="addAnalysisM()">Agregar Análisis</button>
            <!-- Fin de la sección de Análisis Físico-Químicos -->


            <div class="actions-container">
                <button type="button" id="cancel1" class="action-button">Cancelar</button>
                <button type="button" id="continue1" class="action-button">Continuar</button>
            </div>

        </form>

    </div>


</body>

</html>
<script>
    function carga_tablaFQ() {
        $("#contenedor_analisisFQ").load("./backend/calidad/datatables_analisis.html", function(response, status, xhr) {
            if (status == "error") {
                console.error("Error al cargar el archivo: ", xhr.status, xhr.statusText);
                alert("Error al cargar la tabla. Revise la consola para más detalles.");
                return;
            }
            try {
                // Intentar inicializar DataTables
                var tabla = $('#analisisFQ').DataTable();

                $('#boton_agrega_analisisFQ').on('click', function() {
                    // Verificar si la tabla se cargó correctamente antes de agregar filas
                    if ($.fn.DataTable.isDataTable('#analisisFQ')) {
                        tabla.row.add([
                            '', 
                            '<input type="text" name="analisis[]">',
                            '<input type="text" name="metodologia[]">',
                            '<input type="text" name="criterio[]">'
                        ]).draw(false);
                    } else {
                        console.error('Error: La tabla no está inicializada.');
                        alert('Error al cargar la tabla. Por favor, intente de nuevo.');
                    }
                });
            } catch (error) {
                console.error('Error al inicializar la tabla: ', error);
                alert('Error al cargar la tabla. Por favor, revise la consola para más detalles.');
            }
        });
    }

</script>
