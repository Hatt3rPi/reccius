<?php
//archivo: pages\cotizador\ingreso_cotizacion.php
//Elaborado por: @ratapan
// Todo:
// 1. Ingreso de recetas ✅
// 2. Cotizador ✅
// 3. Mantenedor de precios
// 4. Busqueda de cotizaciones

//Todo:
// redondear arriba 10 pesos ✅

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
                            <label>Nombre Cliente:</label>
                            <input class="form-control" id="data_cli_name" name="data_cli_name" placeholder="Nombre del cliente">
                        </div>
                    </div>

                    <div class="row">
                        <div class="w-100 col form-group">
                            <label>Rut Cliente:</label>
                            <input class="form-control" id="data_cli_rut" name="data_cli_rut" placeholder="Rut del cliente">
                        </div>
                        <div class="w-100 col form-group">
                            <label>Correo Cliente:</label>
                            <input type="email" class="form-control w-100" id="data_cli_mail" name="data_cli_mail" placeholder="Correo del cliente">
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
                    <h4 class="text-center">Total: $<span id="formulario_cotizacion_total"></span></h4>
                </div>
            </div>
            <br>
            <div class="actions-container">
                <button type="button" id="guardarCotizacion" name="guardarCotizacion" class="action-button">Guardar Cotización</button>
                <button type="button" id="editarCotizacion" name="editarCotizacion" class="action-button" style="background-color: red; color: white;display: none;">Editar cotización</button>
            </div>
        </form>
        <div class="modal" id="add_contizacion_modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-dialog">
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
                                <option>Selecciona preparación a utilizar:</option>
                                <option value='fraccionamiento'>fraccionamiento</option>
                                <option value='inyectables'>inyectables</option>
                                <option value='oftalmologia'>Oftalmología</option>
                                <option value='semisolidos'>semisólidos</option>
                                <option value='solidos'>sólidos</option>
                                <option value='soluciones'>soluciones</option>
                            </select>
                        </div>
                        <div class="alert alert-warning text-center" role="alert">
                            Los productos no son reales, solo una simulación.
                        </div>
                        <div class="form-group">
                            <label for="add_producto">Producto:</label>
                            <input class="form-control mx-0" list="datalist_product_options" id="add_producto" name="add_producto" placeholder="Buscar producto..">
                            <datalist id="datalist_product_options">
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label>Concentración:</label>
                            <select name="add_tipo_concentracion" id="add_tipo_concentracion" class="w-100 select-style mx-0" required>
                                <option>Selecciona estructura a utilizar:</option>
                                <option value='g/ml'>g/ml</option>
                                <option value='%/ml'>%/ml</option>
                                <option value='UI/ml'>UI/ml</option>
                                <option value='g'>g</option>
                                <option value='ml'>ml</option>
                                <option value='UI'>UI</option>
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
                            <label for="add_cantidad">Cantidad:</label>
                            <input class="form-control mx-0" id="add_cantidad" name="add_cantidad" type="number" placeholder="Cantidad de concentración">
                        </div>
                    </div>
                    <div class="alert alert-danger mx-3 text-center" style="display: none" role="alert" id="add_error_alert">
                        Todos los campos deben llenarse
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="add_contizacion_form_button" class="btn btn-primary">Agregar</button>
                    </div>
                </form>


            </div>
        </div>

    </div>
</body>

</html>
<script>
    /*
    Modal
*/
    var buttonAgregaElementoCotizacion = $('#button_agrega_elemento') //modal open
    var addContizacionModalClose = $('#add_modal_close') //modal close
    var addContizacionModal = $('#add_contizacion_modal') //modal
    var addContizacionForm = $('#add_contizacion_form') //form modal
    var addContizacionFormProducto = $('#add_producto') //producto modal
    var addContizacionFormProductoData = $('#datalist_product_options') //producto datalist modal
    var addContizacionFormConcentracion = $('#add_tipo_concentracion') //cantidad modal
    var addContizacionFormButton = $('#add_contizacion_form_button') // modal button



    var addErrorAlert = $('#add_error_alert') //error modal
    var cotizadorTabla, cotizadorFilas = 0;

    var cotizadorLista = [];
    var editing = false;
    var editingObj = null;

    buttonAgregaElementoCotizacion.on('click', function() {
        openModal()
    });
    addContizacionModalClose.on('click', function() {
        closeModal()
    })

    addContizacionFormProducto.on('input', () => {
        const searchValue = addContizacionFormProducto.val().toLowerCase();
        //API
        var fakeProductosFilter = fakeProductos.filter(option => option.nombre.toLowerCase().includes(searchValue));
        feedDataList(addContizacionFormProductoData, fakeProductosFilter.map(option => {
            return {
                name: option.nombre,
                id: option.id
            }
        }));
    });

    addContizacionFormConcentracion.change(function() {
        const concentracion = $(this).val();
        actualizarConcentracion(concentracion)
    });

    addContizacionForm.on("submit", addContizacionFormSubmit);

    function feedDataList(datalist, options) {
        datalist.empty();
        options.forEach(optionValue => {
            const optionEl = document.createElement('option');
            optionEl.value = optionValue.name;
            optionEl.textContent = optionValue.id;
            datalist.append(optionEl);
        });

    }

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

        if (['g/ml', '%/ml', 'UI/ml'].includes(select)) {
            $('input[name=concentracion_form_param_1]').val('').show();
            $('input[name=concentracion_form_param_2]').val('').show();
            $('input[name=concentracion_form_type_1]').val(select.split('/')[0]).show();
            $('input[name=concentracion_form_type_2]').val(select.split('/')[1]).show();
        } else if (['g', 'ml', 'UI'].includes(select)) {
            $('input[name=concentracion_form_param_1]').val('').show();
            $('input[name=concentracion_form_type_1]').val(select).show();
        }
    }

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
            producto: formObject.add_producto,
            preparacion: formObject.add_tipo_preparacion,
            concentracion: `${formObject.add_tipo_concentracion} : ${formObject.concentracion_form_param_1}${
                twoValues ? 
                        `/${formObject['concentracion_form_param_2']}` : ""}`,
            cantidad: formObject.add_cantidad
        })
    }

    function validarFormulario(formObject) {
        let valido = true;
        let camposRequeridos = ["add_producto", "add_tipo_preparacion", "add_cantidad", "add_tipo_concentracion", "concentracion_form_param_1"];
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
        let camposRequeridos = ["add_producto", "add_tipo_preparacion", "add_cantidad", "add_tipo_concentracion", "concentracion_form_param_1", "concentracion_form_param_2"];
        camposRequeridos.forEach((el) => {
            $(`#${el}`).val('')
        })
        editing = false;
        editingObj = null;
    }

    function setFormAddCotizador(data) {
        let camposRequeridos = ["add_producto", "add_tipo_preparacion", "add_cantidad", "add_tipo_concentracion", "concentracion_form_param_1", "concentracion_form_param_2"];
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
    var cotizacionForm = $('#formulario_cotizacion') //formulario

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
            add_producto,
            add_tipo_preparacion,
            add_tipo_concentracion,
            concentracion_form_param_1,
            concentracion_form_param_2
        }) => {
            const price = fakeProductos.find(x => x.nombre == add_producto).precio;
            var twoValues = add_tipo_preparacion.includes("/");
            const subTotal = twoValues ? (
                    roundDoubleZero((concentracion_form_param_1 / concentracion_form_param_2) *
                        price) * add_cantidad) :
                roundDoubleZero((price * concentracion_form_param_1) * add_cantidad);
            total += subTotal
            formCotizacionTbody.append(`
            <tr>
            <td>
                <p>${add_producto}</p>
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

    var fakeProductos = [{
            id: 1,
            nombre: "Loratadina 231",
            concentracion: "UI",
            precio: 964
        },
        {
            id: 2,
            nombre: "Ketorolaco 124",
            concentracion: "g",
            precio: 356
        },
        {
            id: 3,
            nombre: "Atorvastatina 148",
            concentracion: "UI/ml",
            precio: 317
        },
        {
            id: 4,
            nombre: "Metformina 618",
            concentracion: "g",
            precio: 228
        },
        {
            id: 5,
            nombre: "Losartán 980",
            concentracion: "%/ml",
            precio: 577
        },
        {
            id: 6,
            nombre: "Amoxicilina 309",
            concentracion: "g",
            precio: 198
        },
        {
            id: 7,
            nombre: "Ácido Ascórbico 570",
            concentracion: "%/ml",
            precio: 597
        },
        {
            id: 8,
            nombre: "Esomeprazol 200",
            concentracion: "ml",
            precio: 732
        },
        {
            id: 9,
            nombre: "Cetirizina 870",
            concentracion: "g/ml",
            precio: 85
        },
        {
            id: 10,
            nombre: "Dexametasona 364",
            concentracion: "ml",
            precio: 354
        },
        {
            id: 11,
            nombre: "Clotrimazol 374",
            concentracion: "UI/ml",
            precio: 4
        },
        {
            id: 12,
            nombre: "Fluconazol 160",
            concentracion: "UI",
            precio: 41
        },
        {
            id: 13,
            nombre: "Hidrocortisona 909",
            concentracion: "g/ml",
            precio: 297
        },
        {
            id: 14,
            nombre: "Insulina 556",
            concentracion: "%/ml",
            precio: 788
        },
        {
            id: 15,
            nombre: "Ketorolaco 788",
            concentracion: "g",
            precio: 536
        },
        {
            id: 16,
            nombre: "Lidocaína 433",
            concentracion: "UI",
            precio: 622
        },
        {
            id: 17,
            nombre: "Mometasona 521",
            concentracion: "g/ml",
            precio: 93
        },
        {
            id: 18,
            nombre: "Ibuprofeno 312",
            concentracion: "ml",
            precio: 167
        },
        {
            id: 19,
            nombre: "Omeprazol 157",
            concentracion: "%/ml",
            precio: 644
        },
        {
            id: 20,
            nombre: "Paracetamol 983",
            concentracion: "UI/ml",
            precio: 532
        },
        {
            id: 21,
            nombre: "Ranitidina 451",
            concentracion: "g",
            precio: 258
        },
        {
            id: 22,
            nombre: "Sertralina 867",
            concentracion: "ml",
            precio: 345
        },
        {
            id: 23,
            nombre: "Ácido Ascórbico 254",
            concentracion: "g/ml",
            precio: 485
        },
        {
            id: 24,
            nombre: "Amoxicilina 789",
            concentracion: "UI",
            precio: 134
        },
        {
            id: 25,
            nombre: "Cetirizina 642",
            concentracion: "%/ml",
            precio: 148
        },
        {
            id: 26,
            nombre: "Dexametasona 273",
            concentracion: "g/ml",
            precio: 377
        },
        {
            id: 27,
            nombre: "Esomeprazol 834",
            concentracion: "ml",
            precio: 466
        },
        {
            id: 28,
            nombre: "Fluconazol 195",
            concentracion: "UI/ml",
            precio: 254
        },
        {
            id: 29,
            nombre: "Hidrocortisona 678",
            concentracion: "g",
            precio: 637
        },
        {
            id: 30,
            nombre: "Insulina 889",
            concentracion: "UI",
            precio: 578
        },
        {
            id: 31,
            nombre: "Ketorolaco 456",
            concentracion: "%/ml",
            precio: 389
        },
        {
            id: 32,
            nombre: "Lidocaína 321",
            concentracion: "g/ml",
            precio: 799
        },
        {
            id: 33,
            nombre: "Mometasona 654",
            concentracion: "ml",
            precio: 687
        },
        {
            id: 34,
            nombre: "Ibuprofeno 982",
            concentracion: "UI/ml",
            precio: 256
        },
        {
            id: 35,
            nombre: "Omeprazol 314",
            concentracion: "g",
            precio: 473
        },
        {
            id: 36,
            nombre: "Paracetamol 765",
            concentracion: "ml",
            precio: 599
        },
        {
            id: 37,
            nombre: "Ranitidina 586",
            concentracion: "%/ml",
            precio: 264
        },
        {
            id: 38,
            nombre: "Sertralina 423",
            concentracion: "UI/ml",
            precio: 395
        }
    ]
</script>