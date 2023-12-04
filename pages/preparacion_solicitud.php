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
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <!-- Asegúrate de incluir el CSS para estilizar tu formulario aquí -->
    <link rel="stylesheet" href="../assets/css/calidad.css">
</head>

<body>
    <div class="form-container">
        <h1>SOLICITAR ANÁLISIS (1/2)  - Preparación solicitud</h1>
        <form>
            <fieldset>
                <legend>I. Especificaciones del producto:</legend>

                <div class="form-row">
                    <div class="form-group">
                        <label>Tipo de Producto:</label>
                        <input type="text" placeholder="Producto Terminado" >
                    </div>
                    <div class="form-group">
                        <label>Código Producto:</label>
                        <input type="text" placeholder="12345" >
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Producto:</label>
                        <input type="text" placeholder="Ácido Ascórbico" >
                    </div>
                    <div class="form-group">
                        <label>Concentración:</label>
                        <input type="text" placeholder="1g / 10 ml" >
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Formato:</label>
                        <input type="text" placeholder="Ampolla" >
                    </div>
                    <div class="form-group">
                        <label>Elaborado por:</label>
                        <input type="text" placeholder="Reccius" >
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Serie o Lote:</label>
                        <input type="text" placeholder="100" >
                    </div>
                    <div class="form-group">
                        <label>Fecha Elaboracion:</label>
                        <input type="date" placeholder="06-07-2023" >
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Tamaño Lote:</label>
                        <input type="text" placeholder="100" >
                    </div>
                    <div class="form-group">
                        <label>Fecha Vencimiento:</label>
                        <input type="date" placeholder="06-07-2023" >
                    </div>
                </div>
            </fieldset>
            <br>
            <br>
            <fieldset>
                <legend>II. Información General:</legend>

                <div class="form-row">
                    <div class="form-group">
                        <label>Laboratorio Analista:</label>
                        <input type="text" placeholder="CEQUC">
                    </div>
                    <div class="form-group">
                        <label>Fecha Solicitud:</label>
                        <input type="date" placeholder="06-07-2023">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Análisis según:</label>
                        <input type="text" placeholder="Cotización">
                    </div>
                    <div class="form-group">
                        <label>Fecha Cotización:</label>
                        <input type="date" placeholder="06-07-2023">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="standard-provider">Estándar Provisto por:</label>
                        <select id="standard-provider" name="standard-provider" class="select-style" style="width: 82.5%;">
                            <option value="reccius">Reccius</option>
                            <option value="cequc">CEQUC</option>
                            <option value="pharmaisa">Pharmaisa</option>
                        </select>

                    </div>
                    <div class="form-group">
                        <label>Adjunta Hoja de seguridad</label>
                        <input type="text" placeholder="No">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha Entrega Estimada:</label>
                        <input type="date" placeholder="06-07-2023">
                    </div>
                    <div class="form-group">
                        <label>N* Documento:</label>
                        <input type="text" placeholder="123456">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Otro:</label>
                        <input type="text" placeholder="">
                    </div>
                    <div class="form-group">
                        <label>Otro:</label>
                        <input type="text" placeholder="">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Version:</label>
                        <input type="text" placeholder="001" style="width: 39.5%;">
                    </div>

                </div>
            </fieldset>
            <br>
            <br>
            <fieldset>
                <legend>III. Información Producto Terminado</legend>

                <div class="form-row">
                    <div class="form-group">
                        <label>Condiciones Almacenamiento:</label>
                        <input type="text" placeholder="Lugar fresco y seco">
                    </div>
                    <div class="form-group">
                        <label>Presentacion:</label>
                        <input type="text" placeholder="Ampolla Vidrio Ámbar Tipo I 10ml" >
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Tipo Análisis:</label>
                        <input type="text" placeholder="Análisis de rutina">
                    </div>
                    <div class="form-group">
                        <label>Registro ISP:</label>
                        <input type="text" placeholder="RF XIII 06/18" >
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Cantidad Muestra:</label>
                        <input type="text" placeholder="50 Ampolla">
                    </div>
                    <div class="form-group">
                        <label>Muestreado por:</label>
                        <input type="text" placeholder="Macarena Godoy" >
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Cantidad Contra-muestra:</label>
                        <input type="text" placeholder="100 Ampolla">
                    </div>
                    <div class="form-group">
                        <label>Muestreado según POS:</label>
                        <input type="text" placeholder="DCAL-CC-PO-007" >
                    </div>
                </div>

            </fieldset>
            <br>
            <br>
            <legend>IV. Análisis Físico-Químicos:</legend>
            <table class="analysis-table" id="analysis-table4">
                <thead>
                    <tr>
                        <th>Análisis</th>
                        <th>Metodología</th>
                        <th>Criterio de Aceptación</th>
                        <th>Acciones</th>
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
                    <tr class="editable-row">
                        <td><input type="text" value="Valoración" class="editable" disabled></td>
                        <td><input type="text" value="USP" class="editable" disabled></td>
                        <td class="criteria-cell.editable"><textarea class="editable"
                                disabled>Lorem ipsum dolor sit amet, consectetur adipiscing...</textarea></td>
                        <td>
                            <button type="button" onclick="toggleEdit(this)">Modificar</button>
                            <button type="button" onclick="deleteRow(this)">Eliminar</button>

                        </td>
                    </tr>
                    <tr class="editable-row">
                        <td><input type="text" value="pH" class="editable" disabled></td>
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
            <button type="button" onclick="addAnalysis()">Agregar Análisis</button>


            <!-- Fin de la sección de Análisis Físico-Químicos -->

            <br>
            <br>
            <legend>IV. Análisis Físico-Químicos:</legend>
            <table class="analysis-table" id="analysis-table3">
                <thead>
                    <tr>
                        <th>Análisis</th>
                        <th>Metodología</th>
                        <th>Criterio de Aceptación</th>
                        <th>Acciones</th>
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
                <button type="button" id="cancel" class="action-button">Cancelar</button>
                <button type="button" id="continue" class="action-button">Continuar</button>
            </div>

        </form>

    </div>


</body>

</html>
<script>
    function toggleEdit(button) {
        // Encuentra la fila más cercana al botón que fue clickeado
        var row = button.closest('.editable-row');

        // Encuentra todos los inputs dentro de esta fila
        var inputs = row.querySelectorAll('.editable');

        // Cambia el estado de cada input
        inputs.forEach(function (input) {
            input.disabled = !input.disabled;
        });

        // Cambia el texto del botón dependiendo del estado del input
        button.textContent = inputs[0].disabled ? 'Modificar' : 'Guardar';
    }
</script>
<script>
    function addAnalysisM() {
        var table = document.getElementById('analysis-table3'); // Asegúrate de que tu tabla tenga este id

        // Crea una nueva fila
        var newRow = table.insertRow(-1);
        newRow.className = 'editable-row';

        // Agrega las celdas necesarias
        var cell1 = newRow.insertCell(0);
        var cell2 = newRow.insertCell(1);
        var cell3 = newRow.insertCell(2);
        var cell4 = newRow.insertCell(3);

        // Celda 1 - Tipo de Análisis
        var newTypeInput = document.createElement('input');
        newTypeInput.type = 'text';
        newTypeInput.className = 'editable';
        newTypeInput.value = 'Nuevo Análisis'; // Valor por defecto
        newTypeInput.disabled = true;
        cell1.appendChild(newTypeInput);

        // Celda 2 - Metodología
        var newMethodInput = document.createElement('input');
        newMethodInput.type = 'text';
        newMethodInput.className = 'editable';
        newMethodInput.value = 'Metodología'; // Valor por defecto
        newMethodInput.disabled = true;
        cell2.appendChild(newMethodInput);

        // Celda 3 - Criterio de Aceptación
        var newTextarea = document.createElement('textarea');
        newTextarea.className = 'editable';
        newTextarea.value = 'Lorem ipsum...'; // Valor por defecto
        newTextarea.disabled = true;
        cell3.appendChild(newTextarea);

        // Celda 4 - Acciones
        var editButton = document.createElement('button');
        editButton.textContent = 'Modificar';
        editButton.onclick = function () { toggleEdit(this); };
        cell4.appendChild(editButton);

        var deleteButton = document.createElement('button');
        deleteButton.textContent = 'Eliminar';
        deleteButton.onclick = function () { deleteRow(this); };
        cell4.appendChild(deleteButton);
    }


</script>
<script>
    function addAnalysis() {
        var table = document.getElementById('analysis-table4'); // Asegúrate de que tu tabla tenga este id

        // Crea una nueva fila
        var newRow = table.insertRow(-1);
        newRow.className = 'editable-row';

        // Agrega las celdas necesarias
        var cell1 = newRow.insertCell(0);
        var cell2 = newRow.insertCell(1);
        var cell3 = newRow.insertCell(2);
        var cell4 = newRow.insertCell(3);

        // Celda 1 - Tipo de Análisis
        var newTypeInput = document.createElement('input');
        newTypeInput.type = 'text';
        newTypeInput.className = 'editable';
        newTypeInput.value = 'Nuevo Análisis'; // Valor por defecto
        newTypeInput.disabled = true;
        cell1.appendChild(newTypeInput);

        // Celda 2 - Metodología
        var newMethodInput = document.createElement('input');
        newMethodInput.type = 'text';
        newMethodInput.className = 'editable';
        newMethodInput.value = 'Metodología'; // Valor por defecto
        newMethodInput.disabled = true;
        cell2.appendChild(newMethodInput);

        // Celda 3 - Criterio de Aceptación
        var newTextarea = document.createElement('textarea');
        newTextarea.className = 'editable';
        newTextarea.value = 'Lorem ipsum...'; // Valor por defecto
        newTextarea.disabled = true;
        cell3.appendChild(newTextarea);

        // Celda 4 - Acciones
        var editButton = document.createElement('button');
        editButton.textContent = 'Modificar';
        editButton.onclick = function () { toggleEdit(this); };
        cell4.appendChild(editButton);

        var deleteButton = document.createElement('button');
        deleteButton.textContent = 'Eliminar';
        deleteButton.onclick = function () { deleteRow(this); };
        cell4.appendChild(deleteButton);
    }


</script>


<script>
    // ... (función toggleEdit existente) ...

    function deleteRow(button) {
        // Encuentra la fila más cercana al botón que fue clickeado y la elimina
        var row = button.closest('tr');
        row.remove();
    }
</script>

<script>
    function toggleEdit(button) {
        // Encuentra el textarea más cercano
        var textarea = button.closest('td').previousElementSibling.querySelector('.editable');

        // Comprueba si el textarea está deshabilitado
        if (textarea.disabled) {
            textarea.disabled = false;
            textarea.focus(); // Poner foco en el textarea
        } else {
            textarea.disabled = true;
        }
    }

    // Asegúrate de que el resto de tu JavaScript para eliminar filas esté funcionando como se espera

</script>