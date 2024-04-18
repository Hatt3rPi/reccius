<?php

session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
// Verificar si la variable de sesión "usuario" no está establecida o está vacía.
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    // Redirigir al usuario a la página de inicio de sesión.
    header("Location: login.html");
    exit;
}
$query = "SELECT categoria, nombre_opcion FROM calidad_opciones_desplegables ORDER BY categoria, CASE WHEN nombre_opcion = 'Otro' THEN 1 ELSE 0 END, nombre_opcion";
$result = mysqli_query($link, $query);

$opciones = [];
while ($row = mysqli_fetch_assoc($result)) {
    $opciones[$row['categoria']][] = $row['nombre_opcion'];
}

date_default_timezone_set('America/Santiago');
$fechaActual = new DateTime();
$fechaLimite = new DateTime('+30 days');

// Formatear las fechas para la consulta
$fechaActualFormato = $fechaActual->format('Y-m-d');
$fechaLimiteFormato = $fechaLimite->format('Y-m-d');

// Consulta SQL para obtener feriados entre las fechas
$queryDate = "SELECT fecha FROM feriados_chile WHERE fecha BETWEEN '$fechaActualFormato' AND '$fechaLimiteFormato'";
$resultDate = mysqli_query($link, $queryDate);

// Construir el arreglo de feriados
$feriados = [];
while ($row = mysqli_fetch_assoc($resultDate)) {
    $feriados[] = $row['fecha'];
}
function agregarDiasHabiles($fecha, $diasHabiles, $feriados)
{
    $contadorDias = 0;

    while ($contadorDias < $diasHabiles) {
        $fecha->modify('+1 day'); // Agregar un día

        // Si es un fin de semana o un feriado, no contar este día
        if ($fecha->format('N') < 6 && !in_array($fecha->format('Y-m-d'), $feriados)) {
            $contadorDias++;
        }
    }

    return $fecha;
}

// Calcular 10 días hábiles desde la fecha actual
$fechaEntregaEstimada = agregarDiasHabiles($fechaActual, 10, $feriados);

// Formatear para uso en HTML
$fechaEntregaEstimadaFormato = $fechaEntregaEstimada->format('Y-m-d');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/css/calidad.css">
</head>

<body>
    <div class="form-container">
        <h1>CALIDAD / Preparación solicitud Análisis Externo</h1>
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
                        <input name="fecha_registro" class="form-control mx-0 w-90 datepicker" id="fecha_registro" placeholder="dd/mm/aaaa" type="text" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
            </fieldset>
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
            <br />
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
                        <input required class="form-control mx-0 w-90" name="tamano_lote" id="tamano_lote" type="number" placeholder="20">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha Elaboración:</label>
                        <input required class="form-control mx-0 w-90 datepicker" name="fecha_elaboracion" id="fecha_elaboracion" value="<?php echo date('Y-m-d'); ?>" placeholder="dd/mm/aaaa" type="text">
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Fecha Vencimiento:</label>
                        <input required class="form-control mx-0 w-90 datepicker" name="fecha_vencimiento" id="fecha_vencimiento" placeholder="dd/mm/aaaa" type="text">
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
                        <textarea required class="form-control mx-0 w-90 border rounded-sm" style="field-sizing: content;" name="condicion_almacenamiento" id="condicion_almacenamiento" rows="2" placeholder="..."></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Cantidad Muestra:</label>
                        <input required class="form-control mx-0 w-90" name="tamano_muestra" id="tamano_muestra" type="text" placeholder="...">
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Cantidad Contra-muestra:</label>
                        <input required class="form-control mx-0 w-90" name="tamano_contramuestra" id="tamano_contramuestra" type="text" placeholder="...">
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
                        <input required class="form-control mx-0 w-90" name="numero_pos" id="numero_pos" type="text" placeholder="...">
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
                        </select>
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group"></div>
                </div>
            </fieldset>
            <br />
            <div id="informacion_faltante">
                <fieldset>
                    <legend>IV. Solicitud de Análisis Externo:</legend>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Laboratorio Analista:</label>
                            <select name="laboratorio" id="laboratorio" class="select-style mx-0 form__select w-90" onchange="verificarOtro('laboratorio', 'otro_laboratorio')" style="width: 90%" required>
                                <option value="">Selecciona un Laboratorio</option>
                                <?php foreach ($opciones['laboratorio'] as $opcion) : ?>
                                    <option value="<?php echo htmlspecialchars($opcion); ?>">
                                        <?php echo htmlspecialchars($opcion); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" name="otro_laboratorio" id="otro_laboratorio" placeholder="Especificar otro laboratorio" class="form-control mx-0 w-90" style="display: none" />
                        </div>
                        <div class="divider"></div>
                        <!-- Esta es la línea divisora -->
                        <div class="form-group">
                            <label>Fecha Solicitud:</label>
                            <input name="fecha_solicitud" class="form-control mx-0 w-90 datepicker" id="fecha_solicitud" placeholder="dd/mm/aaaa" type="text" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Análisis según:</label>
                            <input name="analisis_segun" id="analisis_segun" class="form-control mx-0 w-90" type="text" placeholder="Cotización" />
                        </div>
                        <div class="divider"></div>
                        <!-- Esta es la línea divisora -->
                        <div class="form-group">
                            <label>Fecha Cotización:</label>
                            <input name="fecha_cotizacion" id="fecha_cotizacion" placeholder="dd/mm/aaaa" type="text" class="form-control mx-0 w-90 datepicker" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="estandar_provisto_por">Estándar Provisto por:</label>
                            <select id="estandar_provisto_por" name="estandar_provisto_por" class="select-style mx-0 form__select w-90" style="width: 82.5%">
                                <option value="reccius">Reccius</option>
                                <option value="cequc">CEQUC</option>
                                <option value="pharmaisa">Pharma ISA</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div class="divider"></div>
                        <!-- Esta es la línea divisora -->
                        <div class="form-group">
                            <label>Adjunta Hoja de seguridad</label>
                            <input name="adjunta_HDS" id="adjunta_HDS" type="text" class="form-control mx-0 w-90" placeholder="No" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Fecha Entrega Estimada <em>(10 días hábiles)</em>:</label>
                            <input name="fecha_entrega_estimada" id="fecha_entrega_estimada" placeholder="dd/mm/aaaa" type="text" value="<?php echo $fechaEntregaEstimadaFormato; ?>" class="form-control mx-0 w-90 datepicker" />
                        </div>
                        <div class="divider"></div>
                        <!-- Esta es la línea divisora -->
                        <div class="form-group">
                            <label>N° Documento:</label>
                            <input name="numero_documento" id="numero_documento" class="form-control mx-0 w-90" type="text" placeholder="123456" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Observaciones:</label>
                            <textarea name="observaciones" id="observaciones" class="form-control mx-0 w-90 border rounded-sm" rows="4" placeholder="..."></textarea>
                        </div>
                        <div class="divider"></div>
                        <!-- Esta es la línea divisora -->
                        <div class="form-group"></div>
                    </div>
                </fieldset>
                <br />
                <fieldset>
                    <legend>V. Análisis:</legend>
                    <br />
                    <div class="form-row">
                        <div class="form-group">
                            <label>Especificación de producto:</label>
                            <input name="numero_especificacion" id="numero_especificacion" type="text" placeholder="06-07-2023" class="form-control mx-0 w-90" />
                        </div>
                        <div class="divider"></div>
                        <!-- Esta es la línea divisora -->
                        <div class="form-group">
                            <label>Versión:</label>
                            <input name="version_especificacion" id="version_especificacion" type="text" placeholder="06-07-2023" class="form-control mx-0 w-90" />
                        </div>
                    </div>
                </fieldset>
                <br />
                <fieldset>
                    <legend>VI. Flujo de Aprobación:</legend>
                    <br />
                    <br />
                    <div class="form-row">
                        <div class="form-group">
                            <label>Usuario Solicitante:</label>
                            <input class="form-control mx-0 w-90" type="text" id="usuario_editor" name="usuario_editor" value="<?php echo $_SESSION['nombre']; ?>"  readonly />
                            <input type="text" id="user_editor" name="user_editor" value="<?php echo $_SESSION['usuario']; ?>" style="display: none" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Revisión a cargo de:</label>
                            <select name="usuario_revisor" id="usuario_revisor" class="select-style mx-0 form__select w-90" required>
                                <option>Selecciona el usuario supervisor:</option>
                                <option value="isumonte">
                                    Inger Sumonte Rodríguez - Director Calidad
                                </option>
                                <option value="lcaques" selected>
                                    Lynnda Caques Segovia - Coordinador Calidad
                                </option>
                                <option value="cpereira">
                                    Catherine Pereira García - Jefe de Producción
                                </option>
                                <option value="lsepulveda">
                                    Luis Sepúlveda Miranda - Director Técnico
                                </option>
                                <option value="fabarca212">Felipe Abarca</option>
                                <option value="lucianoalonso2000">Luciano Abarca</option>
                            </select>
                        </div>
                    </div>
                </fieldset>
                
                
            </div>
            <div class="alert alert-warning mx-3 text-center p-2 m-0" id="alert_warning" style="display: none;"></div>
            <div class="actions-container">
                <button type="submit" id="guardar" name="guardar" class="action-button">GUARDAR SOLICITUD</button>
                <button type="submit" id="editarGenerarVersion" name="editarGenerarVersion" class="action-button" style="background-color: red; color: white;">EDITAR</button>

                <input type="text" id="id_producto" name="id_producto" style="display: none;">
                <input type="text" id="id_especificacion" name="id_especificacion" style="display: none;">
            </div>
        </form>


    </div>


</body>

</html>
<script>
    //document.querySelectorAll('input, select, textarea').forEach((el)=>console.log({id:el.id, type: el.type}))
    function informacionFaltante() {
        if(QA_solicitud_analisis_editing){
            $("#guardar").hide();
        }else{
            $("#editarGenerarVersion").hide();
            $("#informacion_faltante").remove();
        }
    }
    informacionFaltante();
    var idFormulario = <?php echo json_encode($_POST['id'] ?? ''); ?>;

    ['fecha_registro', 'fecha_elaboracion', 'fecha_vencimiento', 'fecha_solicitud', 'fecha_cotizacion', 'fecha_entrega_estimada'].forEach(val => {
        console.log('#' + val);
        $('#' + val).datepicker({
            format: 'dd/mm/yyyy',
        });
    });


    function cargarDatosEspecificacion(id) {
        $.ajax({
            url: './backend/laboratorio/cargaEsp_solicitudBE.php',
            type: 'GET',
            data: {
                id: id,
            },
            success: function(response) {
                if (QA_solicitud_analisis_editing) {
                    procesarDatosActaUpdate(response);
                } else {
                    procesarDatosActa(response);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud: ", status, error);
            }
        });
    }

    $('#fecha_elaboracion').datepicker({
        format: 'dd/mm/yyyy', // Formato de fecha
    });

    function procesarDatosActa(response) {
        console.log({
            response
        });
        if (response && response.productos && response.productos.length > 0) {
            var producto = response.productos[0];

            $('#registro').val(producto.documento_producto); //? es esto lo que tenemos que cambiar?

            $('#id_producto').val(producto.id_producto).prop('disabled', true);;
            $('#tipo_producto').val(producto.tipo_producto).prop('disabled', true);
            $('#codigo_producto').val(producto.identificador_producto).prop('disabled', true);
            $('#producto').val(producto.nombre_producto).prop('disabled', true);
            $('#concentracion').val(producto.concentracion).prop('disabled', true);
            $('#formato').val(producto.formato).prop('disabled', true);
            $('#elaboradoPor').val(producto.elaborado_por).prop('disabled', true);
            $('#numero_especificacion').val(producto.documento_producto).prop('disabled', true);

            var especificaciones = Object.values(producto.especificaciones);
            if (especificaciones.length > 0) {
                var especificacion = especificaciones[0];
                $('#id_especificacion').val(especificacion.documento);
                $('#version_especificacion').val(especificacion.version).prop('disabled', true);
            }
        } else {
            console.error("No se recibieron datos válidos: ", response);
        }
    }

    function procesarDatosActaUpdate(response) {
        console.log({
            response
        })
        /*
            "id": 16,
            "estado": "Pendiente Acta de Muestreo",
            "numero_registro": "DCAL-CC-EMP-006",
            "version": 1,
            "numero_solicitud": "num-test",
            "fecha_registro": "2024-04-15",
            "laboratorio": null,
            "fecha_solicitud": null,
            "analisis_segun": null,
            "numero_documento": null,
            "fecha_cotizacion": null,
            "estandar_segun": null,
            "estandar_otro": null,
            "hds_adjunto": null,
            "hds_otro": null,
            "fecha_entrega": null,
            "fecha_entrega_estimada": "0000-00-00",
            "id_especificacion": 123,
            "id_producto": 2,
            "lote": "RM-000-test",
            "registro_isp": "N°2988\/18. RF XIII 06\/18. 1A, 2B, 2C, 3A, 3D, 4",
            "condicion_almacenamiento": "1",
            "tipo_analisis": "Análisis de rutina",
            "muestreado_por": "mgodoy",
            "numero_pos": "1",
            "codigo_mastersoft": null,
            "tamano_lote": "10",
            "fecha_elaboracion": "2024-04-15",
            "fecha_vencimiento": "2025-01-16",
            "tamano_muestra": "5",
            "tamano_contramuestra": "10",
            "observaciones": null,
            "solicitado_por": "javier2000asr",
            "revisado_por": "",
            "fecha_firma_revisor": null,
            "prod_identificador_producto": "2",
            "prod_nombre_producto": "test",
            "prod_tipo_producto": "Materia Prima",
            "prod_tipo_concentracion": null,
            "prod_concentracion": "concentracion",
            "prod_formato": "frmato",
            "prod_elaborado_por": "Reccius"
        */
        if (response && response.analisis) {

            var analisis = response.analisis;

            if (analisis.estado == "Pendiente Acta de Muestreo") {
                $("#informacion_faltante").remove();
                $("#editarGenerarVersion").prop('disabled', true);
                $('#alert_warning').show().append(`No se puede editar, por el estado: ${analisis.estado}.`);
            }

            //* I. Análisis:
            $('#registro').val(analisis.numero_registro).prop('disabled', true);
            $('#version').val(analisis.version).prop('disabled', true);
            $('#numero_solicitud').val(analisis.numero_solicitud).prop('disabled', true);
            $('#fecha_registro').val(analisis.fecha_registro).prop('disabled', true);
            //* II. Especificaciones
            $('#id_producto').val(analisis.prod_identificador_producto).prop('disabled', true);
            $('#tipo_producto').val(analisis.prod_tipo_producto).prop('disabled', true);
            $('#codigo_producto').val(analisis.prod_identificador_producto).prop('disabled', true);
            $('#producto').val(analisis.prod_nombre_producto).prop('disabled', true);
            $('#formato').val(analisis.prod_formato).prop('disabled', true);
            $('#concentracion').val(analisis.prod_concentracion).prop('disabled', true);
            $('#elaboradoPor').val(analisis.prod_elaborado_por).prop('disabled', true);
            //* III. Identificación
            $('#lote').val(analisis.lote).prop('disabled', true);
            $('#tamano_lote').val(analisis.tamano_lote).prop('disabled', true);
            $('#fecha_elaboracion').val(analisis.fecha_elaboracion).prop('disabled', true);
            $('#fecha_vencimiento').val(analisis.fecha_vencimiento).prop('disabled', true);
            $('#tipo_analisis').val(analisis.tipo_analisis).prop('disabled', true);
            $('#condicion_almacenamiento').val(analisis.condicion_almacenamiento).prop('disabled', true);
            $('#tamano_muestra').val(analisis.tamano_muestra).prop('disabled', true);
            $('#tamano_contramuestra').val(analisis.tamano_contramuestra).prop('disabled', true);
            $('#registro_isp').val(analisis.registro_isp).prop('disabled', true);
            $('#numero_pos').val(analisis.numero_pos).prop('disabled', true);
            $('#muestreado_por').val(analisis.muestreado_por).prop('disabled', true);

            /*
            condicion_almacenamiento


            */

            //* V. Análisis
            $('#numero_especificacion').val(analisis.documento_producto).prop('disabled', true);
            $('#id_especificacion').val(analisis.id_especificacion);
            $('#version_especificacion').val(analisis.version).prop('disabled', true);

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

    function validateTextRequiredInputs(formData) {
        var formObject = {};
        formData.forEach(function(value, key) {
            formObject[key] = value;
        });
    }

    $('#formulario_analisis_externo').on('submit', formSubmit);

    function formSubmit(event) {
        event.preventDefault();
        const formData = new FormData(this);
        validateTextRequiredInputs(formData)
        var datosFormulario = $('#formulario_analisis_externo').serialize();
        datosFormulario += '&id_producto=' + idFormulario;
        console.log({
            datosFormulario
        });
        /*
        registro=DCAL-CC-EMP-006
        version=1
        numero_solicitud=num-test
        fecha_registro=2024-04-15
        lote=RM-000-test
        tamano_lote=10
        fecha_elaboracion=2024-04-15
        fecha_vence=2025-01-16
        tipo_analisis=An%C3%A1lisis%20de%20rutina
        condicion_almacenamiento=1
        cantidad_muestra=5
        cantidad_contramuestra=10
        registro_isp=N%C2%B02988%2F18.%20RF%20XIII%2006%2F18.%201A%2C%202B%2C%202C%2C%203A%2C%203D%2C%204
        muestreado_POS=1
        muestreado_por=mgodoy
        id_especificacion=123
        id_producto=2
        */
        console.log(datosFormulario.split('&').join('\n'));

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