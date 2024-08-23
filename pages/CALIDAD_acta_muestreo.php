<?php
// archivo: pages\CALIDAD_acta_muestreo.php
// analizar eliminación
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>crear analisis</title>
    <link rel="stylesheet" href="../assets/css/calidad.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- CSS de Bootstrap 4 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Estilos CSS de DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">



    <!-- JS de DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- JS de DataTables con soporte para Bootstrap 4 -->
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://kit.fontawesome.com/7011384382.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="form-container">
        <h1>CALIDAD / Preparar Acta de Muestreo</h1>
        <BR></BR>
        <form id="formulario_analisis_externo" name="formulario_analisis_externo">
            <fieldset>
                <legend>I. Análisis:</legend>
                <br>
                <div class="form-row">
                    <div class="form-group">
                        <label>N° Registro:</label>
                        <input required id="registro" class="form-control mx-0 w-90" name="registro" type="text" placeholder="DCAL-CC-ENE-001">
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Versión:</label>
                        <input id="version" name="version" type="text" class="form-control mx-0 w-90" value="1" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>N° Solicitud:</label>
                        <input required id="numero_solicitud" class="form-control mx-0 w-90" name="numero_solicitud" type="text" placeholder="SAEPT-0101001-00">
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Fecha registro:</label>
                        <input name="fecha_registro" class="form-control mx-0 w-90" id="fecha_registro" type="date" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
            </fieldset>
            <br />
            <br />
            <fieldset>
                <legend>II. Especificaciones del producto:</legend>
                <br>
                <div class="form-row">
                    <div class="form-group">
                        <label>Tipo de Producto:</label>
                        <input class="form-control mx-0 w-90" id="tipo_producto" name="tipo_producto" type="text" placeholder="Producto Terminado">
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Código Producto:</label>
                        <input class="form-control mx-0 w-90" id="codigo_producto" name="codigo_producto" type="text" placeholder="12345">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Producto:</label>
                        <input class="form-control mx-0 w-90" id="producto" name="producto" type="text" placeholder="Ácido Ascórbico">
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Concentración:</label>
                        <input class="form-control mx-0 w-90" name="concentracion" id="concentracion" type="text" placeholder="1g / 10 ml">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Formato:</label>
                        <input class="form-control mx-0 w-90" name="formato" id="formato" type="text" placeholder="Ampolla">
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Elaborado por:</label>
                        <input class="form-control mx-0 w-90" name="elaboradoPor" id="elaboradoPor" type="text" placeholder="Reccius">
                    </div>
                </div>
            </fieldset>
            <br /><br />
            <fieldset>
                <legend>III. Identificación de la muestra:</legend>
                <br>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nro Lote:</label>
                        <input required class="form-control mx-0 w-90" name="lote" id="lote" type="text" placeholder="RM-000000/00">
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Tamaño Lote:</label>
                        <input required class="form-control mx-0 w-90" name="tamano_lote" id="tamano_lote" type="text" placeholder="20">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha Elaboración:</label>
                        <input required class="form-control mx-0 w-90" name="fecha_elaboracion" id="fecha_elaboracion" type="date" placeholder="12345">
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Fecha Vencimiento:</label>
                        <input required class="form-control mx-0 w-90" name="fecha_vence" id="fecha_vence" type="date" placeholder="20">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Tipo Analisis:</label>
                        <input required class="form-control mx-0 w-90" name="tipo_analisis" id="tipo_analisis" type="text" value="Análisis de rutina">
                    </div>
                    <div class="divider"></div>
                    <div class="form-group">
                        <label>Condiciones Almacenamiento:</label>
                        <textarea required class="form-control mx-0 w-90 border rounded-sm" style="field-sizing: content;" name="condicion_almacenamiento" id="condicion_almacenamiento" rows="2" value="T° ambiente, lugar fresco y seco, protegido de la luz"></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Cantidad Muestra:</label>
                        <input required class="form-control mx-0 w-90" name="cantidad_muestra" id="cantidad_muestra" type="text" placeholder="...">
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Cantidad Contra-muestra:</label>
                        <input required class="form-control mx-0 w-90" name="cantidad_contramuestra" id="cantidad_contramuestra" type="text" placeholder="...">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Registro ISP:</label>
                        <input required class="form-control mx-0 w-90" name="registro_isp" id="registro_isp" type="text" value="N°2988/18. RF XIII 06/18. 1A, 2B, 2C, 3A, 3D, 4">
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Muestreado según POS:</label>
                        <input required class="form-control mx-0 w-90" name="muestreado_POS" id="muestreado_POS" type="text" value="DCAL-CC-PO-007">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Muestreado por:</label>
                        <select required name="muestreado_por" id="muestreado_por" class="select-style mx-0 form__select w-90">
                            <option>Selecciona el usuario:</option>
                            <option value="mgodoy" selected>Macarena Godoy - Supervisor Calidad</option>
                            <option value="isumonte">Inger Sumonte Rodríguez - Director Calidad</option>
                            <option value="lcaques">Lynnda Caques Segovia - Coordinador Calidad</option>
                            <option value="cpereira">Catherine Pereira García - Jefe de Producción</option>
                            <option value="lsepulveda">Luis Sepúlveda Miranda - Director Técnico</option>
                            <option value="fabarca212">Felipe Abarca</option>
                            <option value="lucianoalonso2000">Luciano Abarca</option>
                            <option value="javier2000asr">Javier Sabando</option>
                        </select>
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group"></div>
                </div>
            </fieldset>
            <br />
            <div class="actions-container">
                <button type="button" id="guardar" name="guardar" class="action-button">Guardar Acta de Muestreo</button>
                <button type="button" id="editarGenerarVersion" name="editarGenerarVersion" class="action-button" style="background-color: red; color: white;display: none;">Editar y generar nueva versión</button>
                <input type="text" id="id_producto" name="id_producto" style="display: none;">
                <input type="text" id="id_especificacion" name="id_especificacion" style="display: none;">
            </div>
        </form>
    </div>
</body>

</html>
<script>
    function cargarDatosEspecificacion(id) {
        console.log({id});
        $.ajax({
            url: './backend/acta_muestreo/get_acta_mustreo_by_id.php',
            type: 'GET',
            data: {
                id: id
            },
            success: function(response) {
                procesarDatosActa(response);
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud: ", status, error);
                console.log(status, error);
            }
        });
    }

    function procesarDatosActa(response) {
        console.log(response);

        /* 
            Columnas:
                aux_anomes
                aux_autoincremental
                aux_tipo
                ejecutor
                estado
                fecha_firma_ejecutor
                fecha_firma_responsable
                fecha_firma_verificador
                fecha_muestreo
                id
                id_analisisExterno
                id_especificacion
                id_producto
                numero_acta
                numero_registro
                prod_concentracion
                prod_documento_ingreso
                prod_elaborado_por
                prod_formato
                prod_identificador
                prod_nombre
                prod_pais_origen
                prod_proveedor
                prod_tipo
                prod_tipo_concentracion
                responsable
                verificador
                version_acta
                version_registro
        */
        if (response) {
            // * Producto
            $('#id_producto').val(response.id_producto).prop('disabled', true);
            $('#Tipo_Producto').val(response.prod_tipo).prop('disabled', true);
            $('#codigo_producto').val(response.prod_identificador).prop('disabled', true);
            $('#producto').val(response.prod_nombre).prop('disabled', true);
            $('#concentracion').val(response.prod_concentracion).prop('disabled', true);
            $('#formato').val(response.prod_formato).prop('disabled', true);
            $('#elaboradoPor').val(response.prod_elaborado_por).prop('disabled', true);

            // * Identificacion de la muestra


            // * Acta de Muestreo
            $('#version').val(response.version_acta).prop('disabled', true);
            $('#numero_registro').val(response.numero_registro).prop('disabled', true);

        } else {
            console.error("No se recibieron datos válidos: ", response);
        }
    }

    function verificarOtro(selectId, inputId) {
        var select = document.getElementById(selectId);
        var input = document.getElementById(inputId);
        if (select.value === 'Otro') {
            input.style.display = 'block';
        } else {
            input.style.display = 'none';
            input.value = ''; // Limpiar el campo si "Otro" no está seleccionado
        }
    }

    $('#formulario_analisis_externo').on('submit', formSubmit);
    function validateTextRequiredInputs(formData) {
        var formObject = {};
        formData.forEach(function(value, key) {
            formObject[key] = value;
        });
    }

    function formSubmit(event) {
        event.preventDefault();
        const formData = new FormData(this);
        validateTextRequiredInputs(formData)
        var datosFormulario = $('#formulario_analisis_externo').serialize();

        console.log(datosFormulario);

        $.ajax({
            url: './backend/laboratorio/LABORATORIO_preparacion_solicitudBE.php',
            type: 'POST',
            data: datosFormulario,
            success: function(data) {
                var respuesta = JSON.parse(data);
                if (respuesta.exito) {
                    $('#dynamic-content').load('LABORATORIO_listado_solicitudes.php', function(response, status, xhr) {
                        if (status == "error") {
                            console.log("Error al cargar el formulario: " + xhr.status + " " + xhr.statusText);
                        } else {
                            console.log('Listado cargado correctamente cargado exitosamente.');
                            carga_listado();
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
</script>