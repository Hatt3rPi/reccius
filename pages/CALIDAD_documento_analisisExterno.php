<?php
session_start();

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
    <title>Acta de Muestreo Control de Calidad</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/7011384382.js" crossorigin="anonymous"></script>
    <!-- JS de DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/notify.js"></script>
    <link rel="stylesheet" href="../assets/css/DocumentoAna.css">


</head>

<body>
    <div id="form-container" class="form-container formpadding" style="margin: 0 auto;">
        <div id="Maincontainer">
            <!-- Header -->
            <div id="header-container" style="width: 100%; display: flex; justify-content: space-between; align-items: center;">

                <!-- Logo a la izquierda -->
                <div class="header-left" style="flex: 1;">
                    <img src="../assets/images/logo documentos.png" alt="Logo Reccius" style="height: 100px;">
                    <!-- Ajusta la altura según sea necesario -->
                </div>
                <!-- Título Central -->
                <div class="header-center" style="flex: 2; text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center; font-family: 'Arial', sans-serif; height: 100%;">
                    <p name="pretitulo" id="pretitulo" style="margin: 0; font-size: 11px; font-weight: bold; color: #000;">Solicitud de Análisis
                        Externo
                        Control de Calidad
                        <!-- Pretitulo -->
                    </p>
                    <h1 id="Tipo_Producto" name="Tipo_Producto" style="margin: 0; font-size: 11px; font-weight: normal; color: #000; line-height: 1.2;">
                        <!-- Título del documento -->
                    </h1>
                    <p name="nombre_producto" id="nombre_producto" style="margin: 0; font-size: 11px; font-weight: bold; color: #000;">
                        <!-- Descripción del producto -->
                    </p>
                    <hr style="width:75%; margin-top: 2px; margin-bottom: 1px;">
                    <div style="position: relative; font-size: 11px; font-weight: bold; color: #000; margin-top: 2px;">
                        Dirección de Calidad
                    </div>
                </div>
                <!-- Información Derecha con Tabla -->
                <div class="header-right" style="font-size: 10px; font-family: 'Arial', sans-serif;flex: 2; text-align: right">
                    <table id="panel_informativo" name="panel_informativo" style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
                        <tr>
                            <td>N° Registro:</td>
                            <td name="numero_registro" id="numero_registro"></td>
                        </tr>
                        <tr>
                            <td>N° Versión:</td>
                            <td name="version" id="version"></td>
                        </tr>
                        <tr>
                            <td>N° Solicitud:</td>
                            <td name="numero_solicitud" id="numero_solicitud"></td>
                        </tr>
                        <tr>
                            <td>Fecha :</td>
                            <td>
                                <input type="date" id="fecha_registro" name="fecha_registro" style="border: 0px;" readonly>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
            <!-- Body -->
            <form>
                <table>
                    <tr>
                        <td class="Subtitulos" colspan="4">I. INFORMACIÓN GENERAL</td>
                    </tr>
                    <tr>
                        <td class="titulo">1. Laboratorio Analista:(*)</td>
                        <td><input type="text" id="laboratorio" name="laboratorio" required></td>

                    </tr>
                    <tr>
                        <td class="titulo">2. Fecha de Solicitud:(*)</td>
                        <td><input type="text" id="fecha_solicitud" name="fecha_solicitud" required></td>

                    </tr>
                    <tr>
                        <td class="titulo">3. Análisis según:(**)</td>
                        <td><input type="text" id="analisis_segun" name="analisis_segun" required></td>
                        <td class="titulo titulo-right">N° Documento:</td>
                        <td><input type="text" id="numero_documento" name="numero_documento" required></td>
                    </tr>

                    <tr>
                        <td class="titulo">4. Fecha de Cotización:(**)</td>
                        <td><input type="text" id="fecha_cotizacion" name="fecha_cotizacion" required></td>
                    </tr>
                    <tr>
                        <td class="titulo">5. Estandar Provisto por:(**)</td>
                        <td><input type="text" id="estandar_segun" name="estandar_segun" required></td>
                        <td class="titulo titulo-right">Otro:</td>
                        <td><input type="text" id="estandar_otro" name="estandar_otro"></td>
                    </tr>
                    <tr>
                        <td class="titulo">6. Adjunta HDS:(***)</td>
                        <td><input type="text" id="hds_adjunto" name="hds_adjunto" required></td>
                        <td class="titulo titulo-right">Otro:</td>
                        <td><input type="text" id="hds_otro" name="hds_otro"></td>
                    </tr>
                    <tr>
                        <td class="titulo">7. Fecha de Entrega estimada:</td>
                        <td><input type="text" id="fecha_entrega_estimada" name="fecha_entrega_estimada" required></td>
                    </tr>
                    <!-- Continúa agregando más filas según los campos requeridos -->
                </table>

            </form>


            <!-- Sección II: MUESTREO -->
            <form>
                <table>
                    <tr>
                        <td class="Subtitulos" colspan="4">II. INFORMACIÓN PRODUCTO TERMINADO</td>
                    </tr>
                    <tr>
                        <td class="titulo">1. Nombre producto:</td>
                        <td><input type="text" id="nombre_producto" name="nombre_producto" required></td>
                        <td class="titulo titulo-right">10. T. de lote:</td>
                        <td><input type="text" id="tamano_lote" name="tamano_lote" required></td>

                    </tr>
                    <tr>
                        <td class="titulo">2. Presentación:</td>
                        <td><input type="text" id="formato" name="formato" required></td>
                        <td class="titulo titulo-right">11. Fecha Elab.:</td>
                        <td><input type="text" id="fecha_elaboracion" name="fecha_elaboracion" required></td>

                    </tr>
                    <tr>
                        <td class="titulo">3. Serie o lote:</td>
                        <td><input type="text" id="lote" name="lote" required></td>
                        <td class="titulo titulo-right">12. Fecha Vence:</td>
                        <td><input type="text" id="fecha_vencimiento" name="fecha_vencimiento" required></td>

                    </tr>
                    <tr>
                        <td class="titulo">4. Código:</td>
                        <td><input type="text" id="codigo_mastersoft" name="codigo_mastersoft" required></td>

                        <td class="titulo titulo-right">13. Muestra:</td>
                        <td><input type="text" id="tamano_muestra" name="tamano_muestra" required></td>
                    </tr>
                    <tr>
                        <td class="titulo">5. Muestreado según POS:</td>
                        <td><input type="text" id="numero_pos" name="numero_pos" required></td>
                        <td class="titulo titulo-right">14. Cta.muestra</td>
                        <td><input type="text" id="tamano_contramuestra" name="tamano_contramuestra"></td>
                    </tr>
                    <tr>
                        <td class="titulo">6. fabricante:</td>
                        <td><input type="text" id="elaborado_por" name="elaborado_por" required></td>
                        <td class="titulo titulo-right">Otro:</td>
                        <td><input type="text" id="otro3" name="otro3"></td>
                    </tr>
                    <tr>
                        <td class="titulo">7. Muestreado por:</td>
                        <td><input type="text" id="muestreado_por" name="muestreado_por" required></td>


                    </tr>
                    <tr>

                        <td class="titulo">8. Condic. almacenamiento</td>
                        <td><textarea id="condicion_almacenamiento" name="condicion_almacenamiento" required></textarea>
                        </td>
                        <td class="titulo titulo-right">Observaciones:</td>
                        <td><textarea id="observaciones" name="observaciones" required></textarea></td>

                    </tr>
                    <tr>

                        <td class="titulo">9. Registro I.S.P:</td>
                        <td><textarea id="registro_isp" name="registro_isp" required></textarea></td>


                    </tr>
                    <!-- Continúa agregando más filas según los campos requeridos -->
                </table>

            </form>
            <form>
            <table id="analisis-solicitados">
                    <tr>
                        <td class="Subtitulos" colspan="4">III. ANÁLISIS SOLICITADOS</td>
                    </tr>
                    <tr class="bordeAbajo">
                        <th>Análisis</th>
                        <th>Metodología</th>
                        <th>Especificación</th>
                        <th class="revision">Revisión</th>
                    </tr>
                </table>

            </form>

            <!-- Footer -->
            <br>
            <div class="form-row" id="firma">
                <!-- Sección Firma Solicitante de Análisis -->
                <div class="firma-section">

                    <div class="firma-box">

                        <!-- Espacio para la firma o datos del solicitante -->
                        <div class="signature" id="firma_solicitante" name="firma_solicitante">
                            <!-- acá puede ir la firma o un espacio en blanco -->

                        </div>
                        <!-- Información del solicitante -->
                        <div class="firma-box-title">Firma Solicitante de
                            Análisis</div>
                    </div>
                </div>

                <!-- Sección Firma Revisor de Liberación -->
                <div class="firma-section">

                    <div class="firma-box">

                        <!-- Espacio para la firma o datos del revisor -->
                        <div class="signature" id="firma_revisor" name="firma_revisor">
                            <!-- acá puede ir la firma o un espacio en blanco -->
                        </div>
                        <div class="firma-box-title">Firma Revisor de Liberación
                        </div>
                        <!-- Información del revisor -->
                    </div>
                </div>
            </div>

            <footer class="TextoBajo">
                <p class="ParrafoBajo">
                    (*) Campo Obligatorio<br>
                    (**) Llenar según lista desplegable<br>
                    (***) Hoja de Seguridad<br>
                    La información contenida en esta solicitud de análisis se considerará como respaldo sanitario
                    válido. El certificado de análisis solo debe disponer de la información vertida en esta solicitud.
                    La información contenida en esta solicitud es de carácter CONFIDENCIAL y es considerada SECRETO
                    INDUSTRIAL.
                </p>
            </footer>



        </div>


</body>
<div class="button-container">

    <button class="botones" id="Cambiante">cambio</button>
    <button class="botones" id="download-pdf">Descargar PDF</button>



</div>

<div id="notification" class="notification-container notify" style="display: none;">
    <p id="notification-message">Este es un mensaje de notificación.</p>
</div>

</html>
<script>
    document.getElementById('download-pdf').addEventListener('click', function() {


        // Continúa con el proceso de descarga del PDF como antes
        document.querySelector('.button-container').style.display = 'none';
        const elementToExport = document.getElementById('form-container');
        elementToExport.style.border = 'none'; // Establecer el borde a none
        elementToExport.style.boxShadow = 'none'; // Establecer el borde a none


        html2canvas(elementToExport, {
            scale: 2
        }).then(canvas => {
            // Mostrar botones después de la captura
            document.querySelector('.button-container').style.display = 'block';
            // Establecer los estilos originales después de generar el PDF
            elementToExport.style.border = '1px solid #000';
            elementToExport.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.1)';



            const imgData = canvas.toDataURL('image/png');merge
                format: 'a4'
            });

            const imgWidth = 210;
            const pageHeight = 297;
            let imgHeight = canvas.height * imgWidth / canvas.width;
            let heightLeft = imgHeight;

            let position = 0;
            pdf.addImage(imgData, 'JPEG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;

            while (heightLeft >= 0) {
                position = heightLeft - imgHeight;
                pdf.addPage();
                pdf.addImage(imgData, 'JPEG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
            }


            var nombreProducto = document.getElementById('producto').textContent.trim();
            var nombreDocumento = document.getElementById('numero_registro').textContent.trim();
            pdf.save(`${nombreDocumento} ${nombreProducto}.pdf`);
            $.notify("PDF generado con éxito", "success");

            // Restaurar la visibilidad de los botones después de iniciar la descarga del PDF
            allButtonGroups.forEach(group => {
                const buttons = group.querySelectorAll('.btn-check');
                buttons.forEach(button => {
                    // Mostrar todos los botones nuevamente
                    button.style.display = 'block';
                });
            });


        });
    
</script>
<script>
    // Declarar la variable primeravez
    var primeravez = false;

    // Función para cambiar el estado de primeravez y actualizar la visibilidad de la columna
    function cambio() {
        primeravez = !primeravez; // Invierte el estado de primeravez
        toggleRevisionColumn();
        console.log(primeravez); // Imprime el nuevo estado de primeravez
    }

    // Agregar el evento click al botón con id 'Cambiante'
    document.getElementById('Cambiante').addEventListener('click', function() {
        cambio();
    });

    // Función para mostrar u ocultar la columna de revisión
    function toggleRevisionColumn() {
        // Seleccionar todas las celdas y el encabezado de la columna Revisión
        const revisionCells = document.querySelectorAll('.revision');
        const revisionHeader = document.querySelector('th.revision');

        if (primeravez) {
            // Ocultar las celdas y el encabezado si primeravez es true
            revisionCells.forEach(cell => {
                cell.style.display = 'none';
            });
            if (revisionHeader) {
                revisionHeader.style.display = 'none';
            }
        } else {
            // Mostrar las celdas y el encabezado si primeravez es false
            revisionCells.forEach(cell => {
                cell.style.display = '';
            });
            if (revisionHeader) {
                revisionHeader.style.display = '';
            }
        }
    }
</script>
<script>
    $(document).ready(function() {
        // Cargar datos iniciales
        loadData();
    });
    var usuarioActual = "<?php echo $_SESSION['usuario']; ?>";
    var idAnalisisExterno = <?php echo json_encode($_POST['id'] ?? ''); ?>;

    console.log("ID Analisis Externo:", idAnalisisExterno);

    function loadData() {
        $.ajax({
            url: './backend/analisis/ingresar_resultados_analisis.php',
            type: 'GET',
            data: {
                id_acta: idAnalisisExterno
            },
            dataType: 'json', // Asegúrate de que la respuesta esperada es JSON
            success: function(response) {
                // Suponiendo que la respuesta tiene dos partes principales
                const analisis = response.analisis; // Datos del análisis externo
                if (analisis.length > 0) {
                    const primerAnalisis = analisis[0];

                    // Actualizar los inputs con los datos del análisis
                    //TITULO TABLA
                    $('#nombre_producto').val(primerAnalisis.prod_nombre_producto
);
                    $('#Tipo_Producto').val(primerAnalisis.tipo_producto);
                    //TABLA 1
                    $('#laboratorio').val(primerAnalisis.laboratorio);
                    $('#tamano_lote').val(primerAnalisis.tamano_lote);
                    $('#analisis_segun').val(primerAnalisis.analisis_segun);
                    $('#numero_documento').val(primerAnalisis.numero_documento);

                    //TABLA 2
                    $('#formato').val(primerAnalisis.formato);
                    $('#lote').val(primerAnalisis.lote);
                    $('#fecha_elaboracion').val(primerAnalisis.fecha_elaboracion);
                    $('#fecha_vencimiento').val(primerAnalisis.fecha_vencimiento);
                    $('#registro_isp').val(primerAnalisis.registro_isp);
                    $('#tamano_muestra').val(primerAnalisis.tamano_muestra);
                    $('#condicion_almacenamiento').val(primerAnalisis.condicion_almacenamiento);
                    $('#tamano_contramuestra').val(primerAnalisis.tamano_contramuestra);
                    $('#elaborado_por').val(primerAnalisis.elaborado_por);
                    $('#muestreado_por').val(primerAnalisis.muestreado_por);
                    $('#observaciones').val(primerAnalisis.observaciones);
                    $('#numero_pos').val(primerAnalisis.numero_pos);
                    $('#codigo_mastersoft').val(primerAnalisis.codigo_mastersoft);



                    $('#estado').val(primerAnalisis.estado);
                    $('#numero_registro').val(primerAnalisis.numero_registro);
                    $('#version').val(primerAnalisis.version);
                    $('#numero_solicitud').val(primerAnalisis.numero_solicitud);
                    $('#fecha_registro').val(primerAnalisis.fecha_registro);
                    $('#fecha_solicitud').val(primerAnalisis.fecha_solicitud);



                    $('#fecha_cotizacion').val(primerAnalisis.fecha_cotizacion);
                    $('#estandar_segun').val(primerAnalisis.estandar_segun);
                    $('#estandar_otro').val(primerAnalisis.estandar_otro);
                    $('#hds_adjunto').val(primerAnalisis.hds_adjunto);
                    $('#hds_otro').val(primerAnalisis.hds_otro);
                    $('#fecha_entrega').val(primerAnalisis.fecha_entrega);
                    $('#fecha_entrega_estimada').val(primerAnalisis.fecha_entrega_estimada);

                    $('#tipo_analisis').val(primerAnalisis.tipo_analisis);

                }
                if (analisis[0].revisado_por === usuarioActual && analisis[0].fecha_firma_revisor === null && analisis[0].estado === "En proceso de firmas") {
                    $(".button-container").append('<button class="botones" id="FirmaAnalisisExternoRevisor">Firmar revisión análisis externo</button>');
                    $("#FirmaAnalisisExternoRevisor").click(function() {
                        firmarDocumentoSolicitudExterna(idAnalisisExterno);
                    });
                }
                
                // Poblar la tabla III. ANÁLISIS SOLICITADOS
                const analisisSolicitados = response.analiDatos;
                const table = $('#analisis-solicitados');

                analisisSolicitados.forEach(function(analisis) {
                    const row = `<tr class="bordeAbajo">
                        <td class="tituloTabla">${analisis.anali_descripcion_analisis}:</td>
                        <td class="Metod">${analisis.anali_metodologia}</td>
                        <td class="Espec">${analisis.anali_criterios_aceptacion}</td>
                        <td class="revision">
                            <input type="checkbox" class="checkmark">
                            <span class="tamañoRevision">cumple</span>
                            <br>
                            <input type="checkbox" class="checkmark">
                            <span class="tamañoRevision">no cumple</span>
                        </td>
                    </tr>`;
                    table.append(row);
                });



                // etc., continúa para otros campos según sea necesario

                // Opcional: Si también necesitas poblar datos desde Acta Muestreo
                if (response.Acta_Muestreo && response.Acta_Muestreo.length > 0) {
                    // Puedes poblar datos adicionales o manejar múltiples actas de muestreo
                }
            },
            error: function(xhr, status, error) {
                console.error('Error cargando los datos: ' + error);
                console.error('AJAX error: ' + textStatus + ' : ' + errorThrown);
                alert("Error en carga de datos. Revisa la consola para más detalles.");
            }
        });
    }
</script>