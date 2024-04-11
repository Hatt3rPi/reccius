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
                        <input name="fecha_registro" class="form-control mx-0 w-90" id="fecha_registro" type="date" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
            </fieldset>
            <br><br>
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
            <br><br>
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
                        <textarea required class="form-control mx-0 w-90 border rounded-sm" style="field-sizing: content;" name="condicion_almacenamiento" id="condicion_almacenamiento" rows="2" placeholder="..."></textarea>
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
                        <input required class="form-control mx-0 w-90" name="muestreado_POS" id="muestreado_POS" type="text" placeholder="...">
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
            <div id="QA_is_editing">
                <fieldset>
                    <legend>IV. Solicitud de Análisis Externo:</legend>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Laboratorio Analista:</label>
                            <select name="laboratorio" id="laboratorio" class="select-style" onchange="verificarOtro('laboratorio', 'otro_laboratorio')" style="width: 83%" required>
                                <option value="">Selecciona un Laboratorio</option>
                                <?php foreach ($opciones['laboratorio'] as $opcion) : ?>
                                    <option value="<?php echo htmlspecialchars($opcion); ?>">
                                        <?php echo htmlspecialchars($opcion); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" name="otro_laboratorio" id="otro_laboratorio" placeholder="Especificar otro laboratorio" style="display: none" />
                        </div>
                        <div class="divider"></div>
                        <!-- Esta es la línea divisora -->
                        <div class="form-group">
                            <label>Fecha Solicitud:</label>
                            <input name="fecha_solicitud" id="fecha_solicitud" type="date" placeholder="06-07-2023" style="width: 82.75%" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Análisis según:</label>
                            <input name="analisis_segun" id="analisis_segun" type="text" placeholder="Cotización" />
                        </div>
                        <div class="divider"></div>
                        <!-- Esta es la línea divisora -->
                        <div class="form-group">
                            <label>Fecha Cotización:</label>
                            <input name="fecha_cotizacion" id="fecha_cotizacion" type="date" placeholder="06-07-2023" style="width: 82.75%" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="estandar_provisto_por">Estándar Provisto por:</label>
                            <select id="estandar_provisto_por" name="estandar_provisto_por" class="select-style" style="width: 82.5%">
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
                            <input name="adjunta_HDS" id="adjunta_HDS" type="text" placeholder="No" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Fecha Entrega Estimada <em>(10 días hábiles)</em>:</label>
                            <input name="fecha_entrega_estimada" id="fecha_entrega_estimada" type="date" value="<?php echo $fechaEntregaEstimadaFormato; ?>" style="width: 82.75%" />
                        </div>
                        <div class="divider"></div>
                        <!-- Esta es la línea divisora -->
                        <div class="form-group">
                            <label>N° Documento:</label>
                            <input name="numero_documento" id="numero_documento" type="text" placeholder="123456" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Observaciones:</label>
                            <textarea name="observaciones" id="observaciones" rows="4" placeholder="..."></textarea>
                        </div>
                        <div class="divider"></div>
                        <!-- Esta es la línea divisora -->
                        <div class="form-group"></div>
                    </div>
                </fieldset>
                <br />
                <br />
                <fieldset>
                    <legend>V. Análisis:</legend>
                    <br />
                    <div class="form-row">
                        <div class="form-group">
                            <label>Especificación de producto:</label>
                            <input name="numero_especificacion" id="numero_especificacion" type="text" placeholder="06-07-2023" style="width: 82.75%" />
                        </div>
                        <div class="divider"></div>
                        <!-- Esta es la línea divisora -->
                        <div class="form-group">
                            <label>Versión:</label>
                            <input name="version_especificacion" id="version_especificacion" type="text" placeholder="06-07-2023" style="width: 82.75%" />
                        </div>
                    </div>
                </fieldset>
                <br />
                <br />
                <fieldset>
                    <legend>VI. Flujo de Aprobación:</legend>
                    <br />
                    <br />
                    <div class="form-row">
                        <div class="form-group">
                            <label>Usuario Solicitante:</label>
                            <input type="text" id="usuario_editor" name="usuario_editor" value="<?php echo $_SESSION['nombre']; ?>" style="width: 38.5%" readonly />
                            <input type="text" id="user_editor" name="user_editor" value="<?php echo $_SESSION['usuario']; ?>" style="display: none" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Revisión a cargo de:</label>
                            <select name="usuario_revisor" id="usuario_revisor" class="select-style" style="width: 38.5%" required>
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
            <div class="actions-container">
                <button type="submit" id="guardar" name="guardar" class="action-button">GUARDAR SOLICITUD</button>
                <button type="button" id="editarGenerarVersion" name="editarGenerarVersion" class="action-button" style="background-color: red; color: white;display: none;">EDITAR</button>
                <input type="text" id="id_producto" name="id_producto" style="display: none;">
                <input type="text" id="id_especificacion" name="id_especificacion" style="display: none;">
            </div>
        </form>


    </div>


</body>

</html>
<script>
    function cargarDatosEspecificacion(id) {
        $.ajax({
            url: './backend/laboratorio/cargaEsp_solicitudBE.php',
            type: 'GET',
            data: {
                id: id
            },
            success: function(response) {
                procesarDatosActa(response);
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud: ", status, error);
            }
        });
    }

    function procesarDatosActa(response) {
        console.log(response);
        if (response && response.productos && response.productos.length > 0) {
            var producto = response.productos[0];
            $('#id_producto').val(producto.id_producto);
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
                $('#id_especificacion').val(especificacion.id_especificacion);
                $('#version_especificacion').val(especificacion.version).prop('disabled', true);
            }
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

    $('#formulario_analisis_externo').on('submit', formSubmit)
    
    console.log({
        QA_solicitud_analisis_editing
    });

    if (!QA_solicitud_analisis_editing) {
        $('#QA_is_editing').remove()
    }


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
            url: 'backend/laboratorio/LABORATORIO_preparacion_solicitudBE.php',
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