<?php

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
        <form>
            <fieldset>
                <legend>I. Especificaciones del producto:</legend>
                <br>
                <div class="form-row">
                    <div class="form-group">
                        <label>Tipo de Producto:</label>
                        <input id="Tipo_Producto" name="Tipo_Producto" type="text" placeholder="Producto Terminado">
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Código Producto::</label>
                        <input id="codigo_producto" name="codigo_producto" type="text" placeholder="12345">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Producto:</label>
                        <input id="producto" name="producto" type="text" placeholder="Ácido Ascórbico">
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Concentración:</label>
                        <input name="concentracion" id="concentracion" type="text" placeholder="1g / 10 ml">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Formato:</label>
                        <input name="formato" id="formato" type="text" placeholder="Ampolla">
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Elaborado por:</label>
                        <input name="elaboradoPor" id="elaboradoPor" type="text" placeholder="Reccius">
                    </div>
                </div>
            </fieldset>
            <br><br>
            <fieldset>
                <legend>II. Identificación de la muestra:</legend>
                <br>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nro Lote:</label>
                        <input name="lote" id="lote" type="text" placeholder="12345">
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Tamaño Lote:</label>
                        <input name="tamano_lote" id="tamano_lote" type="text" placeholder="20">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Tipo Analisis:</label>
                        <input name="tipo_analisis" id="tipo_analisis" type="text" placeholder="...">
                    </div>
                    <div class="divider"></div>
                    <div class="form-group">
                        <label>Condiciones Almacenamiento:</label>
                        <textarea name="condicion_almacenamiento" id="condicion_almacenamiento" rows="4" placeholder="..."></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Cantidad Muestra:</label>
                        <input name="cantidad_muestra" id="cantidad_muestra" type="text" placeholder="...">
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Cantidad Contra-muestra:</label>
                        <input name="cantidad_contramuestra" id="cantidad_contramuestra" type="text" placeholder="...">
                    </div>
                </div>
            </fieldset>
            <br><br>
            <fieldset>
                <legend>III. Acta de Muestreo:</legend>
                <br>
                <div class="form-row">
                    <div class="form-group">
                        <label>Número de registro:</label>
                        <input name="numero_registro" id="numero_registro" type="text" placeholder="...">
                    </div>
                    <div class="divider"></div> <!-- Esta es la línea divisora -->
                    <div class="form-group">
                        <label>Versión:</label>
                        <input name="version" id="version" type="text" value="1" readonly>
                    </div>
                </div>
            </fieldset>
            <div class="actions-container">
                <button type="button" id="guardar" name="guardar" class="action-button">Guardar Acta de Muestreo</button>
                <button type="button" id="editarGenerarVersion" name="editarGenerarVersion" class="action-button" style="background-color: red; color: white;display: none;">Editar y generar nueva versión</button>
                <input type="text" id="id_producto" name="id_producto" style="display: none;">
                <input type="text" id="id_especificacion" name="id_especificacion" style="display: none;">
            </div>
    </div>
</body>

</html>
<script>
    function cargarDatosEspecificacion(id) {
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
            

            //var especificaciones = Object.values(producto.especificaciones);
            //if (especificaciones.length > 0) {
            //    var especificacion = especificaciones[0];
            //    $('#id_especificacion').val(especificacion.id_especificacion);
            //}

        } else {
            console.error("No se recibieron datos válidos: ", response);
        }
    }
</script>