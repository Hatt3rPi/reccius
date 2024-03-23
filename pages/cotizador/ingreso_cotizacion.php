<?php
//archivo: pages\cotizador\ingreso_cotizacion.php
//Elaborado por: @ratapan
// Todo:
// 1. Cotizador
// 2. Mantenedor de precios
// 3. Ingreso de recetas



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
</head>

<body>
    <div class="form-container">
        <h1>Ingreso de cotización</h1>
        <form method="POST" id="formulario_cotizacion" name="formulario_cotizacion">
            <fieldset>
                <br>
                <br>
                <h2 class="section-title">Receta:</h2>
                <div id="contenedor_cotizador">
                    <table id="cotizadorTabla" class="table table-striped table-bordered" width="100%"></table>
                </div>
                <button type="button" id="button_agrega_elemento">
                    Agregar Producto</button>
                <div class="actions-container">
                    <button type="button" id="guardarCotizacion" name="guardarCotizacion" class="action-button">Guardar Cotización</button>
                    <button type="button" id="editarCotizacion" name="editarCotizacion" class="action-button" style="background-color: red; color: white;display: none;">Editar cotización</button>
                </div>
        </form>
        <div class="modal bg-dark-opacity" id="add_contizacion_modal" tabindex="-1" role="dialog">
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
                            <label for="add_cantidad">Producto:</label>
                            <input class="form-control mx-0" id="add_cantidad" name="add_cantidad" type="number" placeholder="Cantidad de concentración">
                        </div>
                    </div>
                    <div class="alert alert-warning" role="alert" id="add_error_alert" style="display: none;">
                        Todos los campos deben llenarse
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Agregar</button>
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
    var addContizacionFormProducto = $('#add_producto') //producto modal
    var addContizacionFormProductoData = $('#datalist_product_options') //producto datalist modal
    var addContizacionFormConcentracion = $('#add_tipo_concentracion') //cantidad modal
    
    var addErrorAlert = $('#add_error_alert') //error modal
    var cotizadorTabla, cotizadorFilas = 0;

    var cotizadorLista = [];

    buttonAgregaElementoCotizacion.on('click', function() {
        addContizacionModal.show();
    });
    addContizacionModalClose.on('click', function() {
        addContizacionModal.hide();
    })


    addContizacionFormProducto.on('input', () => {
        const searchValue = addContizacionFormProducto.val().toLowerCase();
        console.log('searchValue: ', searchValue);

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

    function addContizacionFormSubmit(event) {
        addErrorAlert.hide();
        event.preventDefault();
        const formData = new FormData(this);
        var formObject = {};
        formData.forEach(function(value, key) {
            formObject[key] = value;
        });
        if(validarFormulario(formObject)) {
            var i = cotizadorLista.length
            formObject['index'] = i
            cotizadorLista.push(formObject)
            addProductoCotizador(
                {
                    index: i,
                    producto: formObject['add_cantidad'],
                    preparacion: formObject['add_preparacion'],
                    concentracion: `${formObject['add_concentracion']} : ${formObject['concentracion_form_param_1']}/${formObject["add_tipo_concentracion"].includes("/") ? formObject['concentracion_form_param_2'] : ""}`,
                    cantidad: formObject['add_cantidad'],
                }

            ) 
            return
        }

        addErrorAlert.show();

    }

    function validarFormulario(formObject) {
        let valido = true;
        let camposRequeridos = ["add_producto", "add_tipo_preparacion", "add_cantidad", "add_tipo_concentracion", "concentracion_form_param_1"];
        camposRequeridos.forEach(campo => {
            if (!formObject[campo] || formObject[campo].trim() === "") {
                console.log(`El campo ${campo} es obligatorio.`);
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

    function addProductoCotizador({
        index,
        preparacion,
        producto,
        concentracion,
        cantidad
    }) {
        var filaNueva = [
            // preparacion,
            document.createElement('p').textContent = preparacion,
            // producto
            document.createElement('p').textContent = producto,
            // concentración
            document.createElement('p').textContent = concentracion,
            // cantidad
            document.createElement('p').textContent = cantidad,
            //Action
            `
            <button type="button" data-index="${index}" class="btn-eliminar">Editar</button>
            <button type="button" data-index="${index}" class="btn-eliminar">Eliminar</button>
            `
        ];
        cotizadorTabla.row.add(filaNueva).draw(false);
        contadorFilasMB++;
    }
    $('#cotizadorTabla').on('click', '.btn-eliminar', function() {
        var index = $(this).data('index');
        cotizadorTabla.row($(this).parents('tr')).remove().draw();
    });
    $('#cotizadorTabla').on('click', '.btn-editar', function() {
        var index = $(this).data('index');
        console.log('Editar');
        //cotizadorTabla.row($(this).parents('tr')).remove().draw();
    });

    $('#guardarCotizacion').on('click', function(e) {
        if (!validarFormulario()) {
            e.preventDefault();
        }
    });

    $('#cotizadorTabla').on('click', '.btn-eliminar', function() {
        cotizadorTabla = $('#analisisMB').DataTable();
        cotizadorTabla.row($(this).parents('tr')).remove().draw();
    });



    var fakeProductos = [{
            id: 1,
            nombre: "Loratadina 231",
            concentracion: "UI",
            precio: 98.64
        },
        {
            id: 2,
            nombre: "Ketorolaco 124",
            concentracion: "g",
            precio: 32.56
        },
        {
            id: 3,
            nombre: "Atorvastatina 148",
            concentracion: "UI/ml",
            precio: 38.17
        },
        {
            id: 4,
            nombre: "Metformina 618",
            concentracion: "g",
            precio: 29.28
        },
        {
            id: 5,
            nombre: "Losartán 980",
            concentracion: "%/ml",
            precio: 56.77
        },
        {
            id: 6,
            nombre: "Amoxicilina 309",
            concentracion: "g",
            precio: 13.98
        },
        {
            id: 7,
            nombre: "Ácido Ascórbico 570",
            concentracion: "%/ml",
            precio: 54.97
        },
        {
            id: 8,
            nombre: "Esomeprazol 200",
            concentracion: "ml",
            precio: 75.32
        },
        {
            id: 9,
            nombre: "Cetirizina 870",
            concentracion: "g/ml",
            precio: 85.5
        },
        {
            id: 10,
            nombre: "Dexametasona 364",
            concentracion: "ml",
            precio: 39.54
        },
        {
            id: 11,
            nombre: "Clotrimazol 374",
            concentracion: "UI/ml",
            precio: 8.4
        },
        {
            id: 12,
            nombre: "Fluconazol 160",
            concentracion: "UI",
            precio: 7.41
        },
        {
            id: 13,
            nombre: "Hidrocortisona 909",
            concentracion: "g/ml",
            precio: 25.97
        },
        {
            id: 14,
            nombre: "Insulina 556",
            concentracion: "%/ml",
            precio: 76.88
        },
        {
            id: 15,
            nombre: "Ketorolaco 788",
            concentracion: "g",
            precio: 52.36
        },
        {
            id: 16,
            nombre: "Lidocaína 433",
            concentracion: "UI",
            precio: 64.22
        },
        {
            id: 17,
            nombre: "Mometasona 521",
            concentracion: "g/ml",
            precio: 90.3
        },
        {
            id: 18,
            nombre: "Ibuprofeno 312",
            concentracion: "ml",
            precio: 15.67
        },
        {
            id: 19,
            nombre: "Omeprazol 157",
            concentracion: "%/ml",
            precio: 68.44
        },
        {
            id: 20,
            nombre: "Paracetamol 983",
            concentracion: "UI/ml",
            precio: 55.32
        },
        {
            id: 21,
            nombre: "Ranitidina 451",
            concentracion: "g",
            precio: 22.58
        },
        {
            id: 22,
            nombre: "Sertralina 867",
            concentracion: "ml",
            precio: 33.45
        },
        {
            id: 23,
            nombre: "Ácido Ascórbico 254",
            concentracion: "g/ml",
            precio: 47.85
        },
        {
            id: 24,
            nombre: "Amoxicilina 789",
            concentracion: "UI",
            precio: 12.34
        },
        {
            id: 25,
            nombre: "Cetirizina 642",
            concentracion: "%/ml",
            precio: 19.48
        },
        {
            id: 26,
            nombre: "Dexametasona 273",
            concentracion: "g/ml",
            precio: 36.77
        },
        {
            id: 27,
            nombre: "Esomeprazol 834",
            concentracion: "ml",
            precio: 45.66
        },
        {
            id: 28,
            nombre: "Fluconazol 195",
            concentracion: "UI/ml",
            precio: 28.54
        },
        {
            id: 29,
            nombre: "Hidrocortisona 678",
            concentracion: "g",
            precio: 62.37
        },
        {
            id: 30,
            nombre: "Insulina 889",
            concentracion: "UI",
            precio: 56.78
        },
        {
            id: 31,
            nombre: "Ketorolaco 456",
            concentracion: "%/ml",
            precio: 34.89
        },
        {
            id: 32,
            nombre: "Lidocaína 321",
            concentracion: "g/ml",
            precio: 78.99
        },
        {
            id: 33,
            nombre: "Mometasona 654",
            concentracion: "ml",
            precio: 66.87
        },
        {
            id: 34,
            nombre: "Ibuprofeno 982",
            concentracion: "UI/ml",
            precio: 23.56
        },
        {
            id: 35,
            nombre: "Omeprazol 314",
            concentracion: "g",
            precio: 44.73
        },
        {
            id: 36,
            nombre: "Paracetamol 765",
            concentracion: "ml",
            precio: 51.99
        },
        {
            id: 37,
            nombre: "Ranitidina 586",
            concentracion: "%/ml",
            precio: 27.64
        },
        {
            id: 38,
            nombre: "Sertralina 423",
            concentracion: "UI/ml",
            precio: 38.95
        }
    ]
</script>