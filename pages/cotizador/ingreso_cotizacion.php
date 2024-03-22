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
                            <select name="add_tipo_preparacion" id="add_tipo_preparacion" class="w-100 select-style" required>
                                <option>Selecciona estructura a utilizar:</option>
                                <option value='fraccionamiento'>fraccionamiento</option>
                                <option value='inyectables'>inyectables</option>
                                <option value='oftalmologia'>Oftalmología</option>
                                <option value='semisolidos'>semisólidos</option>
                                <option value='solidos'>sólidos</option>
                                <option value='soluciones'>soluciones</option>
                            </select>
                        </div>

                        <!--
                        <div class="form-row">
                            <input type="text" name="concentracion_param1" class="col" style="display: none;width: 40%;margin-left: 100px;margin-top: 9px;">
                            <input type="text" name="concentracion_param1_lbl" class="col" disabled style="display: none;width: 43%;margin-right: 200px;margin-top: 9px;">
                        </div>
                        <br>
                        <div class="form-row">
                            <input type="text" name="concentracion_param2" class="col" style="display: none;width: 40%;margin-left: 100px;margin-top: 9px;">
                            <input type="text" name="concentracion_param2_lbl" class="col" disabled style="display: none;width: 43%;margin-right: 200px;margin-top: 9px;">
                        </div>
                        -->

                        <div class="form-group">
                            <label for="autocomplete-input">Producto:</label>
                            <input class="form-control" list="datalist_product_options" id="add_producto" name="add_producto" placeholder="Buscar producto..">
                            <datalist id="datalist_product_options">
                                <option value="Opción 1">
                                <option value="Opción 2">
                                <option value="Opción 3">
                                <option value="Opción 4">
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label>Concentración:</label>
                            <select name="add_tipo_concentracion" id="add_tipo_concentracion" class="w-100 select-style" required>
                                <option>Selecciona estructura a utilizar:</option>
                                <option value='g/ml'>g/ml</option>
                                <option value='%/ml'>%/ml</option>
                                <option value='UI/ml'>UI/ml</option>
                                <option value='g'>g</option>
                                <option value='ml'>ml</option>
                                <option value='UI'>UI</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" type="submit" class="btn btn-primary">Agregar</button>
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

    var cotizadorTabla, cotizadorFilas = 0;

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
        feedDataList(addContizacionFormProductoData, fakeProductosFilter.map(option => {return {name:option.nombre, id:option.id}}));
    });

    addContizacionForm.on("submit", addContizacionFormSubmit);

    function addContizacionFormSubmit(event) {
        event.preventDefault();
        const formData = new FormData(this);
        var formObject = {};
        formData.forEach(function(value, key) {
            formObject[key] = value;
        });
        console.log(formObject);

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
                }
            ]
        });

    }

    document.getElementById('guardarCotizacion').addEventListener('click', function(e) {
        if (!validarFormulario()) {
            e.preventDefault(); // Previene el envío del formulario si la validación falla
        }
    });

    function validarFormulario() {
        var valido = true;
        var mensaje = '';

        // Validación para el campo 'Tipo de Producto'
        if (document.forms[0]["Tipo_Producto"].value.trim() === '') {
            mensaje += 'El campo "Tipo de Producto" es obligatorio.\n';
            valido = false;
        }

        // Validación para el campo 'Producto'
        if (document.forms[0]["producto"].value.trim() === '') {
            mensaje += 'El campo "Producto" es obligatorio.\n';
            valido = false;
        }

        // Validación para el campo 'Concentración'
        if (document.forms[0]["concentracion"].value.trim() === '') {
            mensaje += 'El campo "Concentración" es obligatorio.\n';
            valido = false;
        }

        // Validación para el campo 'Formato'
        if (document.forms[0]["formato"].value.trim() === '') {
            mensaje += 'El campo "Formato" es obligatorio.\n';
            valido = false;
        }

        // Validación para el campo 'Elaborado por'
        if (document.forms[0]["elaboradoPor"].value.trim() === '') {
            mensaje += 'El campo "Elaborado por" es obligatorio.\n';
            valido = false;
        }

        // Validación para el campo 'Número de documento'
        if (document.forms[0]["numeroProducto"].value.trim() === '') {
            mensaje += 'El campo "Número de Producto es obligatorio.\n';
            valido = false;
        }

        // Validación para el campo 'Fecha edición'
        if (document.forms[0]["fechaEdicion"].value.trim() === '') {
            mensaje += 'El campo "Fecha edición" es obligatorio.\n';
            valido = false;
        }

        // Validación para el campo 'Versión'
        if (document.forms[0]["version"].value.trim() === '') {
            mensaje += 'El campo "Versión" es obligatorio.\n';
            valido = false;
        }

        // Validación para el campo 'Vigencia'
        if (document.forms[0]["periodosVigencia"].value.trim() === '') {
            mensaje += 'El campo "Vigencia" es obligatorio.\n';
            valido = false;
        }

        var valido = true;
        var mensaje = '';

        // Función para validar un conjunto de análisis
        function validarAnalisis(selector, tipoAnalisis) {
            $(selector).find('tbody tr').each(function() {
                var tipo = $(this).find('select[name*="[descripcion_analisis]"]').val();
                var metodologia = $(this).find('select[name*="[metodologia]"]').val();
                var criterio = $(this).find('textarea[name*="[criterio]"]').val();

                if (tipo === '' || metodologia === '' || criterio.trim() === '') {
                    mensaje += 'Todos los campos de Análisis ' + tipoAnalisis + ' son obligatorios en cada fila.\n';
                    valido = false;
                }
            });
        }


        return valido;
    }
    var fakeProductos = [
  {id: 1,nombre: "Loratadina 231", concentracion: "UI", precio: 98.64},
  {id: 2,nombre: "Ketorolaco 124", concentracion: "g", precio: 32.56},
  {id: 3,nombre: "Atorvastatina 148", concentracion: "UI/ml", precio: 38.17},
  {id: 4,nombre: "Metformina 618", concentracion: "g", precio: 29.28},
  {id: 5,nombre: "Losartán 980", concentracion: "%/ml", precio: 56.77},
  {id: 6,nombre: "Amoxicilina 309", concentracion: "g", precio: 13.98},
  {id: 7,nombre: "Ácido Ascórbico 570", concentracion: "%/ml", precio: 54.97},
  {id: 8,nombre: "Esomeprazol 200", concentracion: "ml", precio: 75.32},
  {id: 9,nombre: "Cetirizina 870", concentracion: "g/ml", precio: 85.5},
  {id: 10,nombre: "Dexametasona 364", concentracion: "ml", precio: 39.54},
  {id: 11,nombre: "Clotrimazol 374", concentracion: "UI/ml", precio: 8.4},
  {id: 12,nombre: "Fluconazol 160", concentracion: "UI", precio: 7.41},
  {id: 13,nombre: "Hidrocortisona 909", concentracion: "g/ml", precio: 25.97},
  {id: 14,nombre: "Insulina 556", concentracion: "%/ml", precio: 76.88},
  {id: 15,nombre: "Ketorolaco 788", concentracion: "g", precio: 52.36},
  {id: 16,nombre: "Lidocaína 433", concentracion: "UI", precio: 64.22},
  {id: 17,nombre: "Mometasona 521", concentracion: "g/ml", precio: 90.3},
  {id: 18,nombre: "Ibuprofeno 312", concentracion: "ml", precio: 15.67},
  {id: 19,nombre: "Omeprazol 157", concentracion: "%/ml", precio: 68.44},
  {id: 20,nombre: "Paracetamol 983", concentracion: "UI/ml", precio: 55.32},
  {id: 21,nombre: "Ranitidina 451", concentracion: "g", precio: 22.58},
  {id: 22,nombre: "Sertralina 867", concentracion: "ml", precio: 33.45},
  {id: 23,nombre: "Ácido Ascórbico 254", concentracion: "g/ml", precio: 47.85},
  {id: 24,nombre: "Amoxicilina 789", concentracion: "UI", precio: 12.34},
  {id: 25,nombre: "Cetirizina 642", concentracion: "%/ml", precio: 19.48},
  {id: 26,nombre: "Dexametasona 273", concentracion: "g/ml", precio: 36.77},
  {id: 27,nombre: "Esomeprazol 834", concentracion: "ml", precio: 45.66},
  {id: 28,nombre: "Fluconazol 195", concentracion: "UI/ml", precio: 28.54},
  {id: 29,nombre: "Hidrocortisona 678", concentracion: "g", precio: 62.37},
  {id: 30,nombre: "Insulina 889", concentracion: "UI", precio: 56.78},
  {id: 31,nombre: "Ketorolaco 456", concentracion: "%/ml", precio: 34.89},
  {id: 32,nombre: "Lidocaína 321", concentracion: "g/ml", precio: 78.99},
  {id: 33,nombre: "Mometasona 654", concentracion: "ml", precio: 66.87},
  {id: 34,nombre: "Ibuprofeno 982", concentracion: "UI/ml", precio: 23.56},
  {id: 35,nombre: "Omeprazol 314", concentracion: "g", precio: 44.73},
  {id: 36,nombre: "Paracetamol 765", concentracion: "ml", precio: 51.99},
  {id: 37,nombre: "Ranitidina 586", concentracion: "%/ml", precio: 27.64},
  {id: 38,nombre: "Sertralina 423", concentracion: "UI/ml", precio: 38.95}]
</script>