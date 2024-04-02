<?php
//archivo: pages\cotizador\ingreso_cotizacion.php
//Elaborado por: @ratapan
// Todo:
// 1. Ingreso de recetas 
// 2. Cotizador 
// 3. Mantenedor de precios
// 4. Busqueda de cotizaciones

session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
// Verificar si la variable de sesión "usuario" no está establecida o está vacía.
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    // Redirigir al usuario a la página de inicio de sesión.
    header("Location: login.html");
    exit;
}

$query = "SELECT id, categoria, nombre_opcion FROM recetariomagistral_opcionesdeplegables ORDER BY categoria, CASE WHEN nombre_opcion = 'Otro' THEN 1 ELSE 0 END, nombre_opcion";

$queryConversion = "SELECT unidad, unidad_minima, conversion_a_unidadminima FROM recetariomagistral_tablaconversion";

$result = mysqli_query($link, $query);
$resultConversion = mysqli_query($link, $queryConversion);

$opciones = [];
$opcionesCategorias = [];
$opcionesConversion = [];
while ($row = mysqli_fetch_assoc($result)) {
    $opciones[$row['categoria']][] = ['id' => $row['id'], 'nombre_opcion' => $row['nombre_opcion']];
    $opcionesCategorias[$row['categoria']] = true;
}
while ($row = mysqli_fetch_assoc($resultConversion)) {
    $opcionesConversion[] = $row;
}
$opcionesCategorias = array_keys($opcionesCategorias);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ingreso de cotización</title>
    <link rel="stylesheet" href="../assets/css/calidad.css">
    <link rel="stylesheet" href="../assets/css/cotizador.css">
</head>

<body>
    <div class="form-container">
        <h1>Ingreso de cotización</h1>
        <form id="formulario_cotizacion" name="formulario_cotizacion">
            <fieldset>
                <br>
                <br>
                <h2 class="section-title">Datos cotización:</h2>
                <div class="container">
                    <div class="row">
                        <div class="w-100 col form-group">
                            <label>Realizada por:</label>
                            <input class="form-control" value="<?php echo $_SESSION['nombre']; ?>" readonly>
                        </div>
                        <div class="w-100 col form-group">
                            <label>Rut Cliente:</label>
                            <input class="form-control" id="data_cli_rut" list="datalist_rut" name="data_cli_rut" placeholder="Rut del cliente">
                            <datalist id="datalist_rut">
                                <option value="471722-8">471722-8</option>
                                <option value="46576394-9">46576394-9</option>
                                <option value="40381677-9">40381677-9</option>
                                <option value="161287-5">161287-5</option>
                                <option value="1232807-9">1232807-9</option>
                                <option value="7655534-6">7655534-6</option>
                                <option value="40692441-6">40692441-6</option>
                                <option value="49998376-K">49998376-K</option>
                                <option value="1076639-7">1076639-7</option>
                                <option value="4438868-5">4438868-5</option>
                                <option value="45663714-0">45663714-0</option>
                                <option value="15977415-5">15977415-5</option>
                                <option value="5950396-0">5950396-0</option>
                                <option value="698660-9">698660-9</option>
                                <option value="1890659-7">1890659-7</option>
                                <option value="7347772-7">7347772-7</option>
                                <option value="46681940-9">46681940-9</option>
                                <option value="33825362-1">33825362-1</option>
                                <option value="2974968-K">2974968-K</option>
                                <option value="21266210-0">21266210-0</option>
                                <option value="50506607-3">50506607-3</option>
                                <option value="44475667-5">44475667-5</option>
                                <option value="12767803-0">12767803-0</option>
                                <option value="12768443-K">12768443-K</option>
                                <option value="40440243-9">40440243-9</option>
                                <option value="22575724-0">22575724-0</option>
                                <option value="42953327-9">42953327-9</option>
                                <option value="98307-1">98307-1</option>
                                <option value="42858688-3">42858688-3</option>
                                <option value="1081727-7">1081727-7</option>
                                <option value="26492140-6">26492140-6</option>
                                <option value="8794257-0">8794257-0</option>
                                <option value="647469-1">647469-1</option>
                            </datalist>
                        </div>
                    </div>

                    <div class="row">
                        <div class="w-100 col form-group">
                            <label>Nombre Cliente:</label>
                            <input class="form-control" id="data_cli_name" name="data_cli_name" placeholder="Nombre del cliente">
                        </div>
                        <div class="w-100 col form-group">
                            <label>Correo Cliente:</label>
                            <input type="email" class="form-control w-100" id="data_cli_mail" name="data_cli_mail" placeholder="Correo del cliente">
                        </div>
                    </div>

                    <div class="row">
                        <div class="w-100 col form-group">
                            <label>Telefono Cliente:</label>
                            <input class="form-control" id="data_cli_tel" name="data_cli_tel" placeholder="Telefono del cliente">
                        </div>
                        <div class="w-100 col form-group">
                            <label>Nombre del Medico:</label>
                            <input class="form-control w-100" id="data_cli_medico" name="data_cli_medico" placeholder="Medico de la receta">
                        </div>
                    </div>
                </div>
                <br>
                <h2 class="section-title">Receta:</h2>
                <div id="contenedor_cotizador" class="container">
                    <table id="cotizadorTabla" class="table table-striped table-bordered" width="100%"></table>
                </div>
                <div class="col-sm-12">
                    <button type="button" id="button_agrega_elemento">
                        Agregar Producto
                    </button>
                </div>
            </fieldset>
            <br>
            <h2 class="section-title">Resumen de cotización:</h2>
            <div class="container">
                <div class="alert alert-warning text-center" role="alert">
                    Los precios no son reales, solo una simulación!
                </div>
                <div class="container resume_cotizador" id="resume_cotizador">

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="row">Producto</th>
                                <th scope="col">Precio</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="formulario_cotizacion_tbody">

                        </tbody>
                    </table>
                    <h5 class="text-right text-">Total: $<span id="formulario_cotizacion_total"></span></h5>
                </div>
            </div>
            <br>
            <div class="actions-container">
                <button type="button" id="guardarCotizacion" name="guardarCotizacion" class="action-button">Guardar Cotización</button>
                <button type="button" id="editarCotizacion" name="editarCotizacion" class="action-button" style="background-color: red; color: white;display: none;">Editar cotización</button>
            </div>
        </form>
        <div class="modal" style="background-color: #00000080 !important;" id="add_contizacion_modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <form id="add_contizacion_form" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Producto</h5>
                        <button type="button" class="close" id="add_modal_close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Preparación:</label>
                            <select name="add_tipo_preparacion" id="add_tipo_preparacion" class="w-100 select-style mx-0" required>
                                <option disabled selected value="">Selecciona preparación a utilizar:</option>
                                <?php foreach ($opcionesCategorias as $op) : ?>
                                    <option value="<?php echo htmlspecialchars($op); ?>">
                                        <?php echo htmlspecialchars($op); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>Tabla de materias primas:</label>
                        </div>
                        <div id="contenedor_table_new_prod" class="container">
                            <table id="table_new_prod" class="table table-striped table-bordered" width="100%"></table>
                        </div>
                        <div class="form-group">
                            <label for="add_materia_prima">Materia prima:</label>
                            <input class="form-control mx-0" list="datalist_materia_prima_options" id="add_materia_prima" name="add_materia_prima" placeholder="Buscar materia prima...">
                            <datalist id="datalist_materia_prima_options">
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label>Concentración:</label>
                            <select name="add_tipo_concentracion" id="add_tipo_concentracion" class="w-100 select-style mx-0" required>
                                <option disabled selected value="">Selecciona estructura a utilizar</option>
                            </select>
                            <div class="form-row mx-0">
                                <input type="text" required name="concentracion_form_param_1" class="col" style="display: none;margin-top: 9px;">
                                <input type="text" name="concentracion_form_type_1" class="col" disabled style="display: none;width: 50px;margin-top: 9px;">
                            </div>
                            <div class="form-row mx-0">
                                <input type="text" name="concentracion_form_param_2" class="col" style="display: none;margin-top: 9px;">
                                <input type="text" name="concentracion_form_type_2" class="col" disabled style="display: none;width: 50px;margin-top: 9px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="alert alert-danger mx-3 text-center p-2 m-0" style="display: none" role="alert" id="add_materia_prima_error_alert"></div>
                        </div>
                        <div class="form-group">
                            <button type="button" id="add_materia_prima_btn" class="btn btn-primary">Añadir materia prima</button>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>Presentación:</label>
                            <select name="add_tipo_presentacion" id="add_tipo_presentacion" class="w-100 select-style mx-0" required>
                                <option selected disabled value="">Selecciona presentación a utilizar</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="add_cantidad">Cantidad:</label>
                            <input class="form-control mx-0" id="add_cantidad" name="add_cantidad" type="number" placeholder="Cantidad de concentración">
                        </div>
                    </div>
                    <div class="alert alert-danger mx-3 text-center" style="display: none" role="alert" id="add_error_alert">
                        Todos los campos deben llenarse
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="add_contizacion_form_button" class="btn btn-primary">Agregar producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    var buttonAgregaElementoCotizacion = $('#button_agrega_elemento') //modal open
    var addContizacionModalClose = $('#add_modal_close') //modal close
    var addContizacionModal = $('#add_contizacion_modal') //modal
    var addContizacionForm = $('#add_contizacion_form') //form modal    
    var addConcentracionMateriaPrima = $('#add_tipo_concentracion') // modal
    var addContizacionFormButton = $('#add_contizacion_form_button') // modal button
    var addTipoPreparacion = $('#add_tipo_preparacion') //tipo preparacion
    var addTipoPresentacion = $('#add_tipo_presentacion')
    //input materias primas
    var addContizacionFormProducto = $('#add_materia_prima') //materias primas
    var addContizacionFormProductoData = $('#datalist_materia_prima_options') //materias primas datalist
    var addMateriaPrimaBtn = $('#add_materia_prima_btn')
    var addMateriaPrimaErrorAlert = $('#add_materia_prima_error_alert')
    // opciones desde PHP querys
    var opciones = <?php echo json_encode($opciones); ?>;
    var opcionesCategorias = <?php echo json_encode($opcionesCategorias); ?>;
    var opcionesConversion = <?php echo json_encode($opcionesConversion); ?>;

    /*
    Modal
*/

    var addErrorAlert = $('#add_error_alert') //error modal
    var cotizadorTabla, newProductoTabla, cotizadorFilas = 0;

    var cotizadorLista = [];
    var materiasList = [];
    var materiasAddedList = [];

    var editing = false;
    var editingObj = null;

    buttonAgregaElementoCotizacion.on('click', function() {
        openModal()
    });
    addContizacionModalClose.on('click', function() {
        closeModal()
    })

    // Añadir materia prima
    addMateriaPrimaBtn.on('click', function() {
        addMateriaPrimaErrorAlert.hide();
        addMateriaPrimaErrorAlert.empty();
        var {
            valido,
            materia,
            concentracion,
            concentracion_1,
            concentracion_2
        } = validarFormularioMateriaPrima();

        if (valido) {
            var index = materiasAddedList.length
            setToMateriasList({
                materia,
                concentracion,
                concentracion_1,
                concentracion_2,
                index
            })
        }
    })
    //Validar formulario Materia Prima
    function validarFormularioMateriaPrima() {
        let valido = true;
        var selectedMateria = addContizacionFormProducto.val();
        var selectedMateriaFind = materiasList.find(x => x.nombre == selectedMateria)
        if (selectedMateriaFind == undefined) {
            valido = false;
            addMateriaPrimaErrorAlert.show();
            addMateriaPrimaErrorAlert.append('<p class="text-left m-0">La materia prima no existe</class=>');
        }
        var concentracion = addConcentracionMateriaPrima.val() || "";


        if (concentracion == "") {
            valido = false;
            addMateriaPrimaErrorAlert.append('<p class="text-left m-0">La concentración es requerida</p>');
        } else {
            let campoRequerido_param_1 = $("#concentracion_form_param_1");
            if (!campoRequerido_param_1.val() || campoRequerido_param_1.val().trim() === "") {
                addMateriaPrimaErrorAlert.append('<p class="text-left m-0">El campo 1 de concentración es requerido</p>');
                valido = false;
            }
            if (concentracion.includes("/")) {
                let campoRequerido_param_2 = $("#concentracion_form_param_2");
                if (!campoRequerido_param_2.val() || campoRequerido_param_2.val().trim() === "") {
                    addMateriaPrimaErrorAlert.append('<p class="text-left m-0">El campo 2 de concentración es requerido</p>');
                    valido = false;
                }
            }

        }

        return {
            valido,
            materia: selectedMateriaFind,
            concentracion,
            concentracion_1: $("#concentracion_form_param_1").val(),
            concentracion_2: $("#concentracion_form_param_2").val()
        };
    }
    //Set a la lista y tabla de Materia Prima
    function setToMateriasList({
        materia,
        concentracion,
        concentracion_1,
        concentracion_2,
        index
    }) {
        materiasAddedList.push({
            materia,
            concentracion,
            concentracion_1,
            concentracion_2,
            index
        })
        console.log('setToMateriasList', {
            materia,
            concentracion,
            concentracion_1,
            concentracion_2,
            index
        });
        var twoValues = concentracion.includes("/")
        addMaterialTable({
            index,
            materia,
            concentracion: `${concentracion} : ${concentracion_1}${
                twoValues ? `/${concentracion_2}` : ""}`,
        })


    }
    //Set tabla de Materia Prima
    function addMaterialTable({
        index,
        materia,
        concentracion
    }) {
        var filaNueva = [
            // materia
            `<p>${materia}</p>`,
            // concentración
            `<p>${concentracion}</p>`,
            //Action
            `
            <button type="button" data-index="${index}" class="btn-eliminar-materia">Eliminar</button>
            `
        ];
        newProductoTabla.row.add(filaNueva);
        newProductoTabla.draw();
        closeModal()
    }
    //Eliminar row de tabla Materia Prima
    $('#table_new_prod').on('click', '.btn-eliminar-materia', function() {
        newProductoTabla = $('#table_new_prod').DataTable();
        var index = $(this).data('index');
        materiasAddedList.splice(materiasAddedList.findIndex(x => x.index == index), 1)
        newProductoTabla.row($(this).parents('tr')).remove().draw();
        updateResume()
    });


    addContizacionFormProducto.on('input', () => {
        const searchValue = addContizacionFormProducto.val().toLowerCase();
        //API
        if (searchValue.length < 3) return
        $.ajax({
            url: '../pages/cotizador/query_buscar_productos.php',
            type: 'GET',
            dataType: 'json',
            data: {
                texto: searchValue
            },
            success: function(productos) {
                materiasList = productos;
                console.log(productos);
                feedDataList(addContizacionFormProductoData, productos.map(function(option) {
                    return {
                        name: option.nombre,
                        id: option.id
                    };
                }));
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error);
            }
        });
    });

    function feedDataList(datalist, options) {
        datalist.empty();
        options.forEach(optionValue => {
            const optionEl = document.createElement('option');
            optionEl.value = optionValue.name;
            optionEl.textContent = optionValue.id;
            datalist.append(optionEl);
        });
    }


    function obtenerCostosProduccion(preparacion, detalle) {
        $.ajax({
            url: '../pages/cotizador/query_buscar_costo_prod.php',
            type: 'GET',
            dataType: 'json',
            data: {
                preparacion: preparacion,
                detalle: detalle
            },
            success: function(data) {
                console.log(data);
            },
            error: function(xhr, status, error) {
                console.error("Error en la petición AJAX: " + error);
            }
        });
    }

    addConcentracionMateriaPrima.change(function() {
        const concentracion = $(this).val();
        actualizarConcentracion(concentracion)
    });

    addTipoPreparacion.on("change", function() {
        const presentacion = $(this).val();
        actualizarPresentacion(presentacion)
    })

    addContizacionForm.on("submit", addContizacionFormSubmit);



    function actualizarConcentracion(select) {
        var campos = [
            'concentracion_form_param_1',
            'concentracion_form_type_1',
            'concentracion_form_param_2',
            'concentracion_form_type_2'
        ];

        campos.forEach(function(campo) {
            $('input[name=' + campo + ']').hide();
        });

        if (select.includes('/')) {
            $('input[name=concentracion_form_param_1]').val('').show();
            $('input[name=concentracion_form_param_2]').val('').show();
            $('input[name=concentracion_form_type_1]').val(select.split('/')[0]).show();
            $('input[name=concentracion_form_type_2]').val(select.split('/')[1]).show();
            return
        }
        $('input[name=concentracion_form_param_1]').val('').show();
        $('input[name=concentracion_form_type_1]').val(select).show();
    }

    function actualizarPresentacion(select) {
        addTipoPresentacion.empty();
        addTipoPresentacion.val('');
        addTipoPresentacion.append('<option selected disabled value="">Selecciona presentación a utilizar</option>');
        opciones[select].forEach(opcion => {
            addTipoPresentacion.append('<option value="' + opcion['id'] + '">' + opcion['nombre_opcion'] + '</option>');
        })
    }

    function initConcentracion() {
        addConcentracionMateriaPrima.empty();
        addConcentracionMateriaPrima.append('<option selected disabled value="">Selecciona estructura a utilizar</option>');
        opcionesConversion.forEach(opcion => {
            addConcentracionMateriaPrima
                .append('<option value="' + opcion['unidad'] + '">' + opcion['unidad'] + '</option>');
        })
        addConcentracionMateriaPrima.val('');
    }
    initConcentracion()

    cargaTablaCotizacion({
        id: null,
        action: null
    });

    function cargaTablaCotizacion({
        id = null,
        accion = null
    }) {
        cotizadorTabla = new DataTable('#cotizadorTabla', {
            "paging": false,
            "info": false,
            "searching": false,
            "lengthChange": false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            columns: [{
                    title: 'Preparación'
                },
                {
                    title: 'Producto'
                },
                {
                    title: 'Concentración'
                },
                {
                    title: 'Cantidad'
                },
                {
                    title: 'Actividad'
                }
            ]
        });

    }

    cargaTablaNewProducto({
        id: null,
        action: null
    });

    function cargaTablaNewProducto({
        id = null,
        accion = null
    }) {
        var contenedor_table_new_prod = $('#contenedor_table_new_prod');
        contenedor_table_new_prod.empty();
        contenedor_table_new_prod.append('<table id="table_new_prod" class="table table-striped table-bordered" width="100%"></table>');

        newProductoTabla = new DataTable('#table_new_prod', {
            "paging": false,
            "info": false,
            "searching": false,
            "lengthChange": false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            columns: [{
                    title: 'Producto'
                },
                {
                    title: 'Concentración'
                },
                {
                    title: 'Actividad'
                }
            ]
        });

    }

    function addContizacionFormSubmit(event) {
        addErrorAlert.hide();
        event.preventDefault();
        const formData = new FormData(this);
        var formObject = {};
        formData.forEach(function(value, key) {
            formObject[key] = value;
        });
        if (validarFormulario(formObject)) {
            if (editing) {
                cotizadorTabla = $('#cotizadorTabla').DataTable();
                cotizadorTabla.row($(`.btn-eliminar[data-index="${editingObj.index}"]`)
                    .parents('tr')).remove();
                cotizadorTabla.draw()
                cotizadorLista.splice(cotizadorLista.findIndex(x => x.index == editingObj.index), 1)
                console.log('prod:\n->', {
                    ...formObject,
                    index: editingObj.index
                });
                setToList({
                    ...formObject,
                    index: editingObj.index
                });
                editing = false;
                editingObj = null;
            } else {
                var index = cotizadorLista.length
                setToList({
                    ...formObject,
                    index
                })
            }
            return
        }
        addErrorAlert.show();
        updateResume()
    }

    function setToList(formObject) {
        cotizadorLista.push(formObject)
        var twoValues = formObject.add_tipo_concentracion.includes("/")
        addProductoCotizador({
            index: formObject.index,
            producto: formObject.add_materia_prima,
            preparacion: formObject.add_tipo_preparacion,
            concentracion: `${formObject.add_tipo_concentracion} : ${formObject.concentracion_form_param_1}${
                twoValues ? 
                        `/${formObject['concentracion_form_param_2']}` : ""}`,
            cantidad: formObject.add_cantidad
        })
    }

    function validarFormulario(formObject) {
        let valido = true;
        let camposRequeridos = ["add_materia_prima", "add_tipo_preparacion", "add_cantidad", "add_tipo_concentracion", "concentracion_form_param_1"];
        camposRequeridos.forEach(campo => {
            if (!formObject[campo] || formObject[campo].trim() === "") {
                valido = false;
            }
        });

        // Si add_tipo_concentracion contiene "/", 
        // validar que concentracion_form_param_2 esté lleno
        if (formObject["add_tipo_concentracion"].includes("/")) {
            if (!formObject["concentracion_form_param_2"] || formObject["concentracion_form_param_2"].trim() === "") {
                valido = false;
            }
        }
        return valido;
    }

    function cleanFormAddCotizador() {
        let camposRequeridos = ["add_materia_prima", "add_tipo_preparacion", "add_cantidad", "add_tipo_concentracion", "concentracion_form_param_1", "concentracion_form_param_2"];
        camposRequeridos.forEach((el) => {
            $(`#${el}`).val('')
        })
        editing = false;
        editingObj = null;
    }

    function setFormAddCotizador(data) {
        let camposRequeridos = ["add_materia_prima", "add_tipo_preparacion", "add_cantidad", "add_tipo_concentracion", "concentracion_form_param_1", "concentracion_form_param_2"];
        camposRequeridos.forEach((el) => {
            $(`#${el}`).val(data[el])
        })
    }

    function addProductoCotizador({
        index,
        preparacion,
        producto,
        concentracion,
        cantidad
    }) {
        var filaNueva = [
            `<p>${preparacion}</p>`,
            // producto
            `<p>${producto}</p>`,
            // concentración
            `<p>${concentracion}</p>`,
            // cantidad
            `<p>${cantidad}</p>`,
            //Action
            `
            <button type="button" data-index="${index}" class="btn-editar">Editar</button>
            <button type="button" data-index="${index}" class="btn-eliminar">Eliminar</button>
            `
        ];
        cotizadorTabla.row.add(filaNueva);
        cotizadorTabla.draw();
        closeModal()
    }
    $('#cotizadorTabla').on('click', '.btn-eliminar', function() {
        cotizadorTabla = $('#cotizadorTabla').DataTable();
        var index = $(this).data('index');
        cotizadorLista.splice(cotizadorLista.findIndex(x => x.index == index), 1)
        cotizadorTabla.row($(this).parents('tr')).remove().draw();
        updateResume()
    });
    $('#cotizadorTabla').on('click', '.btn-editar', function() {
        cotizadorTabla = $('#cotizadorTabla').DataTable();
        var index = $(this).data('index');
        editing = true;
        editingIndex = index;
        dataIndex = cotizadorLista.findIndex(x => x.index == index)
        editingObj = cotizadorLista[dataIndex];
        setFormAddCotizador(cotizadorLista[dataIndex])
        openModal()
        updateResume()
    });

    function openModal() {
        addContizacionFormButton.text(editing ? 'Actualizar' : 'Agregar');
        addContizacionModal.show();
    }

    function closeModal() {
        cleanFormAddCotizador()
        addContizacionFormButton.text('Agregar');
        addContizacionModal.hide();
        updateResume()
    }
    /* 
            formulario
*/
    var cotizacionForm = $('#formulario_cotizacion')
    cotizacionForm.on("submit", contizacionFormSubmit);

    function contizacionFormSubmit() {
        event.preventDefault();
        const formData = new FormData(this);
        var formObject = {};
        formData.forEach(function(value, key) {
            formObject[key] = value;
        });
        console.log('contizacionFormSubmit:\n\n=>', formObject, cotizadorLista)
    }
    /*
    Resume
*/
    var formCotizacion = $('#formulario_cotizacion')
    var formCotizacionTbody = $('#formulario_cotizacion_tbody')
    var formCotizacionTotal = $('#formulario_cotizacion_total')
    var roundDoubleZero = (num) => Math.round(num * 100) / 100;

    function updateResume() {
        formCotizacionTbody.empty();
        formCotizacionTotal.empty();

        let total = 0
        cotizadorLista.forEach(({
            add_cantidad,
            add_materia_prima,
            add_tipo_preparacion,
            add_tipo_concentracion,
            concentracion_form_param_1,
            concentracion_form_param_2
        }) => {
            const price = fakeProductos.find(x => x.nombre == add_materia_prima).precio;
            var twoValues = add_tipo_preparacion.includes("/");
            const subTotal = twoValues ? (
                    roundDoubleZero((concentracion_form_param_1 / concentracion_form_param_2) *
                        price) * add_cantidad) :
                roundDoubleZero((price * concentracion_form_param_1) * add_cantidad);
            total += subTotal
            formCotizacionTbody.append(`
            <tr>
            <td>
                <p>${add_materia_prima}</p>
            </td>
            <td>
                <p>${price}</p>
            </td>
            <td>
                <p>
                ${add_tipo_concentracion} | ${twoValues?'(':''}${concentracion_form_param_1}${
                    twoValues ? 
                        `/${concentracion_form_param_2}` : ""}${twoValues?')':''} * ${add_cantidad}
                </p>
            </td>
            <td>
                <p>${subTotal}</p>
            </td>
            </tr>
            `)

        });
        formCotizacionTotal.append(roundDoubleZero(total))
    }
</script>