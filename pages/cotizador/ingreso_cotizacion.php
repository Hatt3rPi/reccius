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
                </div>
                <div class="col-sm-12 mt-4">
                    <button type="button" id="button_agrega_elemento">
                        Agregar Producto
                    </button>
                </div>
            </fieldset>
            <br>
            <h2 class="section-title">Resumen de cotización:</h2>
            <div class="container">
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
            <div class="modal-dialog  modal-dialog-centered modal-xl modal__dialog">
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
                            <select name="add_tipo_preparacion" id="add_tipo_preparacion" class="w-100 select-style mx-0 form__select" required>
                                <option disabled selected value="">Selecciona preparación a utilizar:</option>
                                <?php foreach ($opcionesCategorias as $op) : ?>
                                    <option value="<?php echo htmlspecialchars($op); ?>">
                                        <?php echo htmlspecialchars($op); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Presentación:</label>
                            <select name="add_tipo_presentacion" id="add_tipo_presentacion" class="w-100 select-style mx-0 form__select" required>
                                <option selected disabled value="">Selecciona presentación a utilizar</option>
                            </select>
                        </div>
                        <fieldset class="form-group border-left pl-2 ">
                            <legend class="h6 font-weight-normal">Tabla de materias primas</legend>
                            <div id="contenedor_table_new_materia" class="container pl-0">
                                <table id="table_new_materia" class="table table-striped table-bordered" width="100%"></table>
                            </div>
                            <div class="form-group">
                                <label for="add_materia_prima">Materia prima:</label>
                                <input class="form-control mx-0" list="datalist_materia_prima_options" id="add_materia_prima" name="add_materia_prima" placeholder="Buscar materia prima...">
                                <datalist id="datalist_materia_prima_options">
                                </datalist>
                            </div>
                            <div class="form-group">
                                <label>Concentración:</label>
                                
                                <select name="add_tipo_concentracion" id="add_tipo_concentracion" class="w-100 select-style mx-0 form__select">
                                    <option disabled selected value="">Selecciona estructura a utilizar</option>
                                </select>
                          
                                <div class="form-row">
                                    <div class="col form-row mx-0">
                                        <input type="text" name="concentracion_form_param_1" id="concentracion_form_param_1" class="col m-0 form-control" style="display: none;margin-top: 9px;">
                                        <input type="text" name="concentracion_form_type_1" class="col m-0 form-control" disabled style="display: none;width: 50px;margin-top: 9px;">
                                    </div>
                                    <div class="col form-row mx-0">
                                        <input type="text" name="concentracion_form_param_2" id="concentracion_form_param_2" class="col m-0 form-control" style="display: none;margin-top: 9px;">
                                        <input type="text" name="concentracion_form_type_2" class="col m-0 form-control" disabled style="display: none;width: 50px;margin-top: 9px;">
                                    </div>
                                </div>
                                </div>
                                <div class="form-group">
                                    <div class="alert alert-danger mx-3 text-center p-2 m-0" style="display: none" role="alert" id="add_materia_prima_error_alert"></div>
                            </div>
                            <div class="form-group">
                                <button type="button" id="add_materia_prima_btn" class="btn btn-primary">Añadir materia prima</button>
                            </div>
                        </fieldset>
                        <fieldset class="form-group border-left pl-2 ">
                            <legend class="h6 font-weight-normal">Base y Exipientes</legend>
                            <div class="form-group">
                                <input class="form-control mx-0" list="datalist_materia_base_options" id="add_materia_base" name="add_materia_base" placeholder="Buscar materia base...">
                                <datalist id="datalist_materia_base_options">
                                </datalist>
                            </div>
                        </fieldset>
                        <fieldset class="form-group border-left pl-2 ">
                            <legend class="h6 font-weight-normal">Venta</legend>
                            <div class="form-group" style="margin-right: 10px;">
                                <label>Unidad de venta:</label>
                                <div class="form-row">
                                    <div class="col">
                                        <input class="form-control mx-0" id="add_unidad_venta" required name="add_unidad_venta" type="number" placeholder="Unidad de venta" required>

                                    </div>
                                    <div class="col-auto">
                                        <select required name="add_unidad_venta_medida" id="add_unidad_venta_medida" class="w-100 select-style mx-0 form__select">
                                            <option disabled selected value="">UM</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="margin-right: 10px;">
                                <label for="add_cantidad">Cantidad (unidad/es):</label>
                                <input class="form-control mx-0" id="add_cantidad" name="add_cantidad" type="number" placeholder="Cantida" value="1" required>
                            </div>

                        </fieldset>

                    </div>
                    <div class="alert alert-danger mx-3 text-center" style="display: none" role="alert" id="add_error_alert"></div>
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
    var addContizacionFormButton = $('#add_contizacion_form_button') // modal button
    var addTipoPreparacion = $('#add_tipo_preparacion') //tipo preparacion
    var addTipoPresentacion = $('#add_tipo_presentacion')
    //input materias primas
    var addContizacionFormProducto = $('#add_materia_prima') //materias primas
    var addContizacionFormProductoData = $('#datalist_materia_prima_options') //materias primas datalist
    //input materias base
    var addContizacionFormProductoBase = $('#add_materia_base') //materias base
    var addContizacionFormProductoBaseData = $('#datalist_materia_base_options') //materias base datalist
    //
    var addMateriaPrimaBtn = $('#add_materia_prima_btn')
    var addMateriaPrimaErrorAlert = $('#add_materia_prima_error_alert')
    //seleccion de medidas
    var addConcentracionMateriaPrima = $('#add_tipo_concentracion') // concentracion
    var addUnidadVentaTipo = $('#add_unidad_venta_medida') // Unidad de venta {ejemplo peso de una pastilla} (numero/unidad de medida)

    var contenedorCotizador = $('#contenedor_cotizador');



    /*
        Funciones Iniciales
    */
    // opciones desde PHP querys
    var opciones = <?php echo json_encode($opciones); ?>;
    var opcionesCategorias = <?php echo json_encode($opcionesCategorias); ?>;
    var opcionesConversion = <?php echo json_encode($opcionesConversion); ?>;

    // Selects de medidas
    function initMedidas() {
        addConcentracionMateriaPrima.empty();
        addUnidadVentaTipo.empty();
        addConcentracionMateriaPrima.append('<option selected disabled value="">Selecciona estructura a utilizar</option>');
        addUnidadVentaTipo.append('<option selected disabled value="">UM</option>');
        opcionesConversion.forEach(opcion => {
            addConcentracionMateriaPrima
                .append('<option value="' + opcion['unidad'] + '">' + opcion['unidad'] + '</option>');
            addUnidadVentaTipo
                .append('<option value="' + opcion['unidad'] + '">' + opcion['unidad'] + '</option>');
        })
        addConcentracionMateriaPrima.val('');
        addUnidadVentaTipo.val('')
    }
    initMedidas()



    /*
        Modal
    */

    var addErrorAlert = $('#add_error_alert') //error modal
    var newProductoTabla, cotizadorFilas = 0;

    var editing = false;
    var editingObj = null;

    /*
    MODAl RECETA
    */
    var materiasList = [];
    var materiasListBase = [];
    var cotizadorLista = [];


    // abrir modal
    buttonAgregaElementoCotizacion.on('click', function() {
        openModal()
    });

    function openModal() {
        addContizacionFormButton.text(editing ? 'Actualizar' : 'Agregar');
        addContizacionModal.show();
        cargaTablaMateriasPrimas({
            id: null,
            action: null
        });
    }
    // cerrar modal
    addContizacionModalClose.on('click', function() {
        closeModal()
    })

    function closeModal() {
        addContizacionFormButton.text('Agregar');
        addContizacionModal.hide();
        //updateResume()
    }

    // CARGA TABLA MATERIAS PRIMAS
    function cargaTablaMateriasPrimas({
        id = null,
        accion = null
    }) {
        var contenedorTableNewMateria = $('#contenedor_table_new_materia');
        contenedorTableNewMateria.empty();
        contenedorTableNewMateria.append('<table id="table_new_materia" class="table table-striped table-bordered" width="100%"></table>');

        materiasAddedList = [];

        newProductoTabla = new DataTable('#table_new_materia', {
            "paging": false,
            "info": false,
            "searching": false,
            "lengthChange": false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            columns: [{
                    title: 'Materia Prima'
                },
                {
                    title: 'Concentración'
                },
                {
                    title: 'Actividad'
                }
            ]
        });

        //Eliminar row de tabla Materia Prima
        $('#table_new_materia').on('click', '.btn-eliminar-materia', function() {
            console.log('Eliminar row de tabla Materia Prima');
            newProductoTabla = $('#table_new_materia').DataTable();
            var index = $(this).data('index');
            materiasAddedList.splice(materiasAddedList.findIndex(x => x.index == index), 1)
            newProductoTabla.row($(this).parents('tr')).remove().draw();
            console.log('Eliminar row de tabla Materia Prima => ', materiasAddedList);
        });

    }

    // Seleccion de tipo preparacion
    addTipoPreparacion.on("change", function() {
        const presentacion = $(this).val();
        actualizarPresentacion(presentacion)
    })
    // Actusalizo los opciones de presentacion segun la preparacion
    function actualizarPresentacion(select) {
        addTipoPresentacion.empty();
        addTipoPresentacion.val('');
        addTipoPresentacion.append('<option selected disabled value="">Selecciona presentación a utilizar</option>');
        opciones[select].forEach(opcion => {
            addTipoPresentacion.append('<option value="' + opcion['nombre_opcion'] + '">' + opcion['nombre_opcion'] + '</option>');
        })
    }
    /*
        TABLA MATERIA PRIMA 
    */
    // Añadir materia prima
    addMateriaPrimaBtn.on('click', function() {
        addMateriaPrimaErrorAlert.empty().hide();
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
            addMateriaPrimaErrorAlert.append('<p class="text-left m-0">La materia prima no existe</p>');
        }
        var concentracion = addConcentracionMateriaPrima.val() || "";
        let concentracion_1 = $("#concentracion_form_param_1").val() || "";
        let concentracion_2 = $("#concentracion_form_param_2").val() || "";

        if (concentracion == "") {
            valido = false;
            addMateriaPrimaErrorAlert.show();
            addMateriaPrimaErrorAlert.append('<p class="text-left m-0">La concentración es requerida</p>');
        } else {
            if (concentracion_1.trim() == "") {
                addMateriaPrimaErrorAlert.show();
                addMateriaPrimaErrorAlert.append('<p class="text-left m-0">El campo 1 de concentración es requerido</p>');
                valido = false;
            }
            if (concentracion.includes("/")) {
                if (concentracion_2.trim() == "") {
                    addMateriaPrimaErrorAlert.show();
                    addMateriaPrimaErrorAlert.append('<p class="text-left m-0">El campo 2 de concentración es requerido</p>');
                    valido = false;
                }
            }

        }

        return {
            valido,
            materia: selectedMateriaFind,
            concentracion,
            concentracion_1,
            concentracion_2
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
        var twoValues = concentracion.includes("/")
        addMaterialTable({
            index,
            materia: materia.nombre,
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
        cleanAddMateriaPrima()
    }
    //Limpiar formulario add materia prima
    function cleanAddMateriaPrima() {
        let camposRequeridos = ["add_materia_prima", "add_tipo_concentracion", "concentracion_form_param_1", "concentracion_form_param_2"];
        camposRequeridos.forEach((el) => {
            $(`#${el}`).val('')
        })
    }
    //Buscar materia Prima
    [
        {
            input: addContizacionFormProducto, data: addContizacionFormProductoData, 
            setListFn: (data)=>{materiasList = data }}, 
        {
            input: addContizacionFormProductoBase, data: addContizacionFormProductoBaseData,  
            setListFn: (data)=>{materiasList = data }}
    ].forEach(({input, data, setListFn}) => 
    { 
        input.on('input', () => {
        const searchValue = input.val().toLowerCase();
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
                setListFn(productos);
                feedDataList(data, productos.map(function(option) {
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
    })

    function feedDataList(datalist, options) {
        datalist.empty();
        options.forEach(optionValue => {
            const optionEl = document.createElement('option');
            optionEl.value = optionValue.name;
            optionEl.textContent = optionValue.id;
            datalist.append(optionEl);
        });
    }

    

    // Conscentración Seleccion
    addConcentracionMateriaPrima.change(function() {
        const concentracion = $(this).val();
        actualizarConcentracion(concentracion)
    });

    function actualizarConcentracion(select) {
        var campos = [
            'concentracion_form_param_1',
            'concentracion_form_type_1',
            'concentracion_form_param_2',
            'concentracion_form_type_2'
        ];

        campos.forEach(function(campo) {
            $('input[name=' + campo + ']').val('').hide();
        });

        $('#concentracion_form_params_div_1').hide()
        $('#concentracion_form_params_div_2').hide()

        if (select.includes('/')) {
            $('#concentracion_form_params_div_1').show();
            $('input[name=concentracion_form_param_1]').show();
            $('input[name=concentracion_form_type_1]').val(select.split('/')[0]).show();
            $('#concentracion_form_params_div_2').show();
            $('input[name=concentracion_form_param_2]').show();
            $('input[name=concentracion_form_type_2]').val(select.split('/')[1]).show();
            return
        }
        $('#concentracion_form_params_div_1').show();
        $('input[name=concentracion_form_param_1]').show();
        $('input[name=concentracion_form_type_1]').val(select).show();
    }
    /*
        TABLA MATERIA PRIMA FIN 
    */

    // Submit Nuevo producto
    addContizacionForm.on("submit", addContizacionFormSubmit);
    function addContizacionFormSubmit(event) {
        addErrorAlert.hide();
        event.preventDefault();
        const formData = new FormData(this);
        var formObject = {};
        formData.forEach(function(value, key) {
            formObject[key] = value;
        });
        var {
            add_cantidad: cantidadReceta,
            add_tipo_preparacion: tipoPreparacionReceta,
            add_tipo_presentacion: tipoPresentacionReceta,
            add_unidad_venta: unidadVenta,
            add_unidad_venta_medida: unidadVentaMedida,
            add_materia_base: materiaBase
        } = formObject;

        if (validarFormulario({
                cantidadReceta,
                tipoPreparacionReceta,
                tipoPresentacionReceta,
                unidadVenta,
                unidadVentaMedida,
                materiaBase
            })) {
            if (editing) {
                cotizadorLista.splice(cotizadorLista.findIndex(x => x.index == editingObj.index), 1)
                console.log('prod:\n->', {
                    ...formObject,
                    index: editingObj.index
                });

                editing = false;
                editingObj = null;
            } else {
                var index = Date.now()
                var selectedMateriaFind = materiasListBase.find(x => x.nombre == materiaBase)
                setToList({
                    cantidadReceta,
                    tipoPreparacionReceta,
                    tipoPresentacionReceta,
                    materiasList: materiasAddedList,
                    unidadVenta,
                    unidadVentaMedida,
                    materiaBase:selectedMateriaFind,
                    index
                })
            }
            return
        }
        addErrorAlert.show();
        //updateResume()
    }

    function validarFormulario({
        cantidadReceta,
        tipoPreparacionReceta,
        tipoPresentacionReceta,
        unidadVenta,
        unidadVentaMedida,
        materiaBase
    }) {
        let valido = true;
        addErrorAlert.empty();
        if (!tipoPreparacionReceta || tipoPreparacionReceta.trim() === "") {
            valido = false;
            addErrorAlert.append('<p class="text-left m-0">El campo Tipo de preparación es requerido </p>');
        }
        if (materiasAddedList.length == 0) {
            valido = false;
            addErrorAlert.append('<p class="text-left m-0">Debe agregar al menos una materia prima </p>');
        }
        if (!cantidadReceta || cantidadReceta.trim() === "") {
            valido = false;
            addErrorAlert.append('<p class="text-left m-0">El campo Cantidad es requerido</p>');
        }
        if (!tipoPresentacionReceta || tipoPresentacionReceta.trim() === "") {
            valido = false;
            addErrorAlert.append('<p class="text-left m-0">El campo Tipo de presentación es requerido </p>');
        }
        if (!unidadVenta > 0) {
            valido = false;
            addErrorAlert.append('<p class="text-left m-0">El campo Unidad de venta es requerido</p>');
        }
        if (unidadVentaMedida.trim() === '') {
            valido = false;
            addErrorAlert.append('<p class="text-left m-0"> El campo Unidad de venta medida es requerido </p>');
            
        }

        if(materiaBase){
            var selectedMateriaFind = materiasListBase.find(x => x.nombre == materiaBase)
            if (selectedMateriaFind == undefined) {
                valido = false;
                addMateriaPrimaErrorAlert.show();
                addMateriaPrimaErrorAlert.append('<p class="text-left m-0">La materia prima no existe</p>');
            }
        }
        if(!materiaBase){
            valido = false;
            addMateriaPrimaErrorAlert.show();
            addMateriaPrimaErrorAlert.append('<p class="text-left m-0">Debe seleccionar una materia base</p>');
        }

        return valido;
    }

    /*
          MODAl RECETA FIN 
    */

    function obtenerCostosProduccion(preparacion, presentacion) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '../pages/cotizador/query_buscar_costo_prod.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    preparacion: preparacion,
                    detalle: presentacion
                },
                success: function(data) {
                    resolve(data);
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        });
    }

    /*
        Cotizador
    */

    async function setToList(formObject) {
        var {
            tipoPreparacionReceta: prepara,
            tipoPresentacionReceta: present,
        } = formObject
        try {
            const datos = await obtenerCostosProduccion(prepara, present);
            formObject['constosPreparacion'] = datos;

            cotizadorLista.push(formObject)
            console.log('cotizadorLista: \n->', cotizadorLista);
            updateCotizador()
        } catch (error) {
            formObject['constosPreparacion'] = []
            cotizadorLista.push(formObject)
            console.log('cotizadorLista Error: \n->', cotizadorLista);
            updateCotizador()
        }
        updateResume()
    }
    function updateCotizador() {
        cotizadorLista.sort((a, b) => a.index - b.index)
        contenedorCotizador.empty();
        cotizadorLista.forEach(({
            tipoPreparacionReceta,
            materiasList,
            tipoPresentacionReceta,
            cantidadReceta,
            constosPreparacion,

            //Todo: unidadVenta
            //Todo: unidadVentaMedida
            // unidadVenta
            // unidadVentaMedida
            // materiaBase

            index,
        }, i) => {
            var article = `
            <article class="container mt-2 border rounded p-2">
                    <h5 class="text-center h5">Producto N° ${i + 1}</h5>
                <main>
                    <p class="pb-2 mb-1"><strong>Preparacion:</strong>  ${tipoPreparacionReceta}</p>
                    <dl class="pb-1 mb-1">
                        <dt class="pb-2 mb-1">Materiales:</dt>
                        ${
                            materiasList.map(({materia,concentracion,concentracion_1,concentracion_2,index}) => 
                            `<dd class="pl-2 pb-1 mb-1">• ${materia.nombre} (${concentracion}) : ${concentracion.includes("/") ? `${concentracion_1}/${concentracion_2}` : `${concentracion_1}`}</dd>`).join('')
                        }
                    </dl>
                    <p class="pb-2 mb-1"><strong>Presentacion:</strong> ${ tipoPresentacionReceta} </p> 
                    <p class="pb-2 mb-1"><strong>Cantidad:</strong> ${cantidadReceta} </p> 
                    
                    ${constosPreparacion.map(({detalle_costo, valor_clp}) =>
                        ` <p class="pb-2 mb-1"><strong>${detalle_costo}:</strong> ${valor_clp}</p> `).join('')}                    
                </main > 
                <footer class = "d-flex justify-content-end border-top pt-2" style = "gap: 8px;">
                    <button type="button" data-index="${index}" class="btn-editar">Editar</button>
                    <button type = "button" data-index="${index}" class="btn-eliminar btn-danger">Eliminar</button> 
                </footer > 
            </article>
            `
            contenedorCotizador.append(article)
    })

    closeModal()
    }

    function setFormAddCotizador(data) {
        let camposRequeridos = ["add_materia_prima", "add_tipo_preparacion", "add_cantidad", "add_tipo_concentracion", "concentracion_form_param_1", "concentracion_form_param_2"];
        camposRequeridos.forEach((el) => {
            $(`#${el}`).val(data[el])
        })
    }

    contenedorCotizador.on('click', '.btn-eliminar', function() {
        var index = $(this).data('index');
        cotizadorLista.splice(cotizadorLista.findIndex(x => x.index == index), 1)
        $(this).parents('article').remove()
        //updateResume()
    });

    contenedorCotizador.on('click', '.btn-editar', function() {
        var index = $(this).data('index');
        editing = true;
        editingIndex = index;
        dataIndex = cotizadorLista.findIndex(x => x.index == index)
        editingObj = cotizadorLista[dataIndex];
        openModal()
        //updateResume()
        setFormAddCotizador(cotizadorLista[dataIndex])
    });

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
            tipoPreparacionReceta,
            materiasList,
            tipoPresentacionReceta,
            cantidadReceta,
            constosPreparacion,
            index
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