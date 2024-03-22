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
$query = "SELECT categoria, nombre_opcion FROM calidad_opciones_desplegables ORDER BY categoria, CASE WHEN nombre_opcion = 'Otro' THEN 1 ELSE 0 END, nombre_opcion";
$result = mysqli_query($link, $query);

$opciones = [];
while ($row = mysqli_fetch_assoc($result)) {
    $opciones[$row['categoria']][] = $row['nombre_opcion'];
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
        <form method="POST" id="formulario_especificacion" name="formulario_especificacion">
            <fieldset>
                <br>
                <br>
                <h2 class="section-title">Análisis Físico-Químico:</h2>
                <div id="contenedor_cotizador">
                    <table id="cotizadorTabla" class="table table-striped table-bordered" width="100%"></table>
                </div>
                <button type="button" id="boton_agrega_elemento">Agregar Elemento</button>
                <div class="actions-container">
                    <button type="button" id="guardarCotizacion" name="guardarCotizacion" class="action-button">Guardar Cotización</button>
                    <button type="button" id="editarCotizacion" name="editarCotizacion" class="action-button" style="background-color: red; color: white;display: none;">Editar cotización</button>
                </div>
        </form>
    </div>
</body>

</html>
<script>
    const opcionesDesplegables = <?php echo json_encode($opciones); ?>;
    console.log(opcionesDesplegables);
    var cotizadorTabla, cotizadorFilas = 0;

    carga_tabla_cotizacion({id:null, action:null});
    function carga_tabla_cotizacion({id = null, accion = null}) {

        cotizadorTabla = new DataTable('#cotizadorTabla', {
            "paging": false,
            "info": false,
            "searching": false,
            "lengthChange": false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            columns: [
                { title: 'Preparación' },
                { title: 'Producto' },
                { title: 'Concentración' },
                { title: 'Cantidad' }
            ]
        });

        $('#boton_agrega_elemento').on('click', function() {
           console.log("Open pop-up")
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
</script>