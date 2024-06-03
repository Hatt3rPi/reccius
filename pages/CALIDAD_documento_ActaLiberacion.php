<?php
// archivo: pages\CALIDAD_documento_ActaLiberacion.php
session_start();

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/7011384382.js" crossorigin="anonymous"></script>
    <!-- JS de DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/notify.js"></script>
    <link rel="stylesheet" href="../assets/css/DocumentoLiberacion.css">
    <style>

    </style>



</head>

<body>
    <div id="form-container" class="form-container formpadding">
        <div id="Maincontainer">
            <!-- Header -->
            <div id="header-container"
                style="width: 100%; display: flex; justify-content: space-between; align-items: center;">

                <!-- Logo a la izquierda -->
                <div class="header-left" style="flex: 1;">
                    <img src="../assets/images/logo documentos.png" alt="Logo Reccius" style="height: 100px;">
                    <!-- Ajusta la altura según sea necesario -->
                </div>
                <!-- Título Central -->
                <div class="header-center"
                    style="flex: 2; text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center; font-family: 'Arial', sans-serif; height: 100%;">
                    <p name="pretitulo" id="pretitulo"
                        style="margin: 0; font-size: 11px; font-weight: bold; color: #000;">Acta de Liberación / Rechazo
                    </p>
                    </p>
                    <!-- Pretitulo -->
                    </p>
                    <h1 id="Tipo_Producto" name="Tipo_Producto"
                        style="margin: 0; font-size: 11px; font-weight: normal; color: #000; line-height: 1.2;">
                        <!-- Título del documento -->
                    </h1>
                    <p name="producto_completo" id="producto_completo"
                        style="margin: 0; font-size: 11px; font-weight: bold; color: #000;">
                        <!-- Descripción del producto -->
                    </p>
                    <hr style="width:75%; margin-top: 2px; margin-bottom: 1px;">
                    <div style="position: relative; font-size: 11px; font-weight: bold; color: #000; margin-top: 2px;">
                        Dirección de Calidad
                    </div>
                </div>
                <!-- Información Derecha con Tabla -->
                <div class="header-right"
                    style="font-size: 10px; font-family: 'Arial', sans-serif;flex: 2; text-align: right">
                    <table id="panel_informativo" name="panel_informativo"
                        style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
                        <tr>
                            <td>N° Registro:</td>
                            <td name="nro_registro" id="nro_registro"></td>
                        </tr>
                        <tr>
                            <td>N° Versión:</td>
                            <td name="nro_version" id="nro_version"></td>
                        </tr>
                        <tr>
                            <td>N° Acta:</td>
                            <td name="nro_acta" id="nro_acta"></td>
                        </tr>
                        <tr>
                            <td>Fecha Liberación:</td>
                            <td>
                                <input type="date" id="fecha_acta_lib" name="fecha_acta_lib" style="border: 0px;" readonly>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
            <!-- Body -->
            <br>
            <form id="section1">
                <table id="seccion1" >
                    <tr>
                        <td class="Subtitulos" colspan="4">I. IDENTIFICACIÓN DE LA MUESTRA</td>
                    </tr>
                    <tr>
                        <td class="titulo">1. Producto</td>
                        <td>
                            <textarea id="producto_completoT1" name="producto_completoT1" required></textarea>
                            <span> </span>
                        </td>
                        <td class="titulo"> </td>
                        <td class="titulo">2. Cond. Almacenamiento</td>
                        <td><textarea id="cond_almacenamiento" name="cond_almacenamiento" required></textarea>
                        </td>


                    </tr>
                    <tr>
                        <td class="titulo">3. Tipo Producto:</td>
                        <td><input type="text" id="tipo_producto" name="tipo_producto" required></td>
                        <td class="titulo"> </td>
                        <td class="titulo">4. Tamaño de Lote:</td>
                        <td><input type="text" id="tamaño_lote" name="tamaño_lote" required></td>

                    </tr>
                    <tr>
                        <td class="titulo">5. Código Interno:</td>
                        <td><input type="text" id="codigo_interno" name="codigo_interno" required></td>
                        <td class="titulo"> </td>
                        <td class="titulo">6. Fecha Elaboración:</td>
                        <td><input type="text" id="fecha_elaboracion" name="fecha_elaboracion" required></td>

                    </tr>

                    <tr>
                        <td class="titulo">7. N° Lote:</td>
                        <td><input type="text" id="nro_lote" name="nro_lote" required></td>
                        <td class="titulo"></td>
                        <td class="titulo">8. Fecha de Vencimiento:</td>
                        <td><input type="text" id="fecha_vencimiento" name="fecha_vencimiento" required></td>

                    </tr>

                </table>

            </form>
            <br>

            <!-- Sección II: MUESTREO -->
            <form id="section2">
                <table id="seccion2">
                    <tr>
                        <td class="Subtitulos" colspan="4">II. MUESTREO Y ANALISIS</td>
                    </tr>
                    <tr>
                        <td class="titulo">1. N° Acta de Muestreo:</td>
                        <td><input type="text" id="nro_acta_muestreo" name="nro_acta_muestreo" required></td>
                        <td class="titulo"> </td>
                        <td class="titulo">2. Fecha Acta Muestreo:</td>
                        <td><input type="text" id="fecha_acta_muestreo" name="fecha_acta_muestreo" required></td>

                    </tr>
                    <tr>
                        <td class="titulo">3. N° Solicitud de Análisis</td>
                        <td><input type="text" id="nro_solicitud_analisis" name="nro_solicitud_analisis" required></td>
                        <td class="titulo"> </td>
                        <td class="titulo">4. Fecha Solicitud Análisis</td>
                        <td><input type="text" id="fecha_solicitud_analisis" name="fecha_solicitud_analisis" required>
                        </td>

                    </tr>
                    <tr>
                        <td class="titulo">5. Laboratorio Analista</td>
                        <td><input type="text" id="laboratorio_analista" name="laboratorio_analista" required></td>
                        <td class="titulo"> </td>
                        <td class="titulo">6. Fecha de Envío</td>
                        <td><input type="text" id="fecha_envio" name="fecha_envio" required></td>

                    </tr>

                    <tr>
                        <td class="titulo">7. N° de Análisis:</td>
                        <td><input type="text" id="nro_analisis" name="nro_analisis" required></td>
                        <td class="titulo"> </td>
                        <td class="titulo">8. Fecha de Revisión:</td>
                        <td><input type="text" id="fecha_revision" name="fecha_revision" required></td>

                    </tr>
                </table>

            </form>
            <br>
            <form id="section3">
                <table id="seccion3">
                    <tr>
                        <td class="Subtitulos" style="text-align: start;" colspan="4">
                            III. ANÁLISIS SOLICITADOS</td>
                    </tr>
                    <tr class="bordeAbajo">
                        <th>Documento</th>
                        <th>Estado</th>
                        <th>Observaciones</th>
                        <th>Revisión</th>
                    </tr>
                    <tr class="bordeAbajo">
                        <td class="tituloTabla">Planilla de fabricación</td>
                        <td class="centrado">
                            <div class="btn-group d-flex flex-column flex-md-row" role="group" aria-label="Basic radio toggle button group">
                                <div class="flex-fill">
                                    <input type="radio" style="display: none;" class="btn-check" name="estado1" id="estado1a" value="1" autocomplete="off">
                                    <label class="btn btn-outline-secondary verificadores w-100" for="estado1a">
                                        <i class="fa-regular fa-circle-check"></i> Conforme
                                    </label>
                                </div>
                                <div class="divider"></div>
                                <div class="flex-fill">
                                    <input type="radio" style="display: none;" class="btn-check" name="estado1" id="estado1b" value="0" autocomplete="off">
                                    <label class="btn btn-outline-secondary verificadores w-100" for="estado1b">
                                        <i class="fa-regular fa-circle-xmark"></i> No Conforme
                                    </label>
                                </div>
                            </div>
                        </td>
                        <td class="Espec centrado">
                            <textarea id="form_textarea1"></textarea>
                        </td>
                        <td class="revision centrado">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check" name="revision_liberacion1" id="revision_liberacion1a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="revision_liberacion1a"><i class="fa-regular fa-circle-check"></i> Aprobado</label>
                                <input type="radio" style="display: none;" class="btn-check" name="revision_liberacion1" id="revision_liberacion1b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="revision_liberacion1b"><i class="fa-regular fa-circle-xmark"></i> Rechazado</label>
                            </div>
                        </td>
                    </tr>
                    <tr class="bordeAbajo">
                        <td class="tituloTabla">Acta de Muestreo</td>
                        <td class="centrado">
                            <div class="btn-group d-flex flex-column flex-md-row" role="group" aria-label="Basic radio toggle button group">
                                <div class="flex-fill">
                                    <input type="radio" style="display: none;" class="btn-check" name="estado2" id="estado2a" value="1" autocomplete="off">
                                    <label class="btn btn-outline-secondary verificadores w-100" for="estado2a">
                                        <i class="fa-regular fa-circle-check"></i> Conforme
                                    </label>
                                </div>
                                <div class="divider"></div>
                                <div class="flex-fill">
                                    <input type="radio" style="display: none;" class="btn-check" name="estado2" id="estado2b" value="0" autocomplete="off">
                                    <label class="btn btn-outline-secondary verificadores w-100" for="estado2b">
                                        <i class="fa-regular fa-circle-xmark"></i> No Conforme
                                    </label>
                                </div>
                            </div>
                        </td>
                        <td class="Espec centrado">
                            <textarea id="form_textarea2"></textarea>
                        </td>
                        <td class="revision centrado">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check" name="revision_liberacion2" id="revision_liberacion2a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="revision_liberacion2a"><i class="fa-regular fa-circle-check"></i> Aprobado</label>
                                <input type="radio" style="display: none;" class="btn-check" name="revision_liberacion2" id="revision_liberacion2b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="revision_liberacion2b"><i class="fa-regular fa-circle-xmark"></i> Rechazado</label>
                            </div>
                        </td>
                    </tr>
                    <tr class="bordeAbajo">
                        <td class="tituloTabla">Solicitud de Análisis</td>
                        <td class="centrado">
                            <div class="btn-group d-flex flex-column flex-md-row" role="group" aria-label="Basic radio toggle button group">
                                <div class="flex-fill">
                                    <input type="radio" style="display: none;" class="btn-check" name="estado3" id="estado3a" value="1" autocomplete="off">
                                    <label class="btn btn-outline-secondary verificadores w-100" for="estado3a">
                                        <i class="fa-regular fa-circle-check"></i> Conforme
                                    </label>
                                </div>
                                <div class="divider"></div>
                                <div class="flex-fill">
                                    <input type="radio" style="display: none;" class="btn-check" name="estado3" id="estado3b" value="0" autocomplete="off">
                                    <label class="btn btn-outline-secondary verificadores w-100" for="estado3b">
                                        <i class="fa-regular fa-circle-xmark"></i> No Conforme
                                    </label>
                                </div>
                            </div>
                        </td>
                        <td class="Espec centrado">
                            <textarea id="form_textarea3"></textarea>
                        </td>
                        <td class="revision centrado">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check" name="revision_liberacion3" id="revision_liberacion3a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="revision_liberacion3a"><i class="fa-regular fa-circle-check"></i> Aprobado</label>
                                <input type="radio" style="display: none;" class="btn-check" name="revision_liberacion3" id="revision_liberacion3b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="revision_liberacion3b"><i class="fa-regular fa-circle-xmark"></i> Rechazado</label>
                            </div>
                        </td>
                    </tr>
                    <tr class="bordeAbajo">
                        <td class="tituloTabla">Certificado de Análisis</td>
                        <td class="centrado">
                            <div class="btn-group d-flex flex-column flex-md-row" role="group" aria-label="Basic radio toggle button group">
                                <div class="flex-fill">
                                    <input type="radio" style="display: none;" class="btn-check" name="estado4" id="estado4a" value="1" autocomplete="off">
                                    <label class="btn btn-outline-secondary verificadores w-100" for="estado4a">
                                        <i class="fa-regular fa-circle-check"></i> Conforme
                                    </label>
                                </div>
                                <div class="divider"></div>
                                <div class="flex-fill">
                                    <input type="radio" style="display: none;" class="btn-check" name="estado4" id="estado4b" value="0" autocomplete="off">
                                    <label class="btn btn-outline-secondary verificadores w-100" for="estado4b">
                                        <i class="fa-regular fa-circle-xmark"></i> No Conforme
                                    </label>
                                </div>
                            </div>
                        </td>
                        <td class="Espec centrado">
                            <textarea id="form_textarea4"></textarea>
                        </td>
                        <td class="revision centrado">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check" name="revision_liberacion4" id="revision_liberacion4a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="revision_liberacion4a"><i class="fa-regular fa-circle-check"></i> Aprobado</label>
                                <input type="radio" style="display: none;" class="btn-check" name="revision_liberacion4" id="revision_liberacion4b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="revision_liberacion4b"><i class="fa-regular fa-circle-xmark"></i> Rechazado</label>
                            </div>
                        </td>
                    </tr>

                    <!-- ... -->
                </table>

            </form>
            <!-- Sección II: MUESTREO -->
            <br>
            <form id="section4">
                <table id="seccion4">
                    <tr>
                        <td class="Subtitulos" colspan="4">IV. MUESTREO Y ANALISIS</td>
                    </tr>
                    <tr>
                        <td class="titulo">1. N° Acta de Liberacion:</td>
                        <td><input type="text" id="nro_acta_liberacion" name="nro_acta_liberacion" required></td>
                        <td class="titulo"> </td>
                        <td class="titulo">2. Fecha Liberacion:</td>
                        <td><input type="text" id="fecha_lib" name="fecha_lib" required></td>

                    </tr>
                    <tr>
                        <td class="titulo">3. Producto:</td>
                        <td><input type="text" id="producto_completoT3" name="producto_completoT3" required></td>
                        <td class="titulo"> </td>
                        <td class="titulo">4. N° Lote:</td>
                        <td><input type="text" id="nro_loteT3" name="nro_loteT3" required></td>

                    </tr>
                    <tr>
                        <td class="titulo">5. Fecha de Elaboración:</td>
                        <td><input type="text" id="fecha_elabT3" name="fecha_elabT3" required></td>
                        <td class="titulo"> </td>
                        <td class="titulo">6. Fecha de Vencimiento:</td>
                        <td><input type="text" id="fecha_vencT3" name="fecha_vencT3" required></td>

                    </tr>

                    <tr>
                        <td class="titulo">7. Cantidad real Liberada:</td>
                        <td><input type="text" id="cantidad_real" name="cantidad_real" required></td>
                        <td class="titulo"> </td>
                        <td class="titulo">8. N° Parte de Ingreso/Traspaso:</td>
                        <td><input type="text" id="nro_traspaso" name="nro_traspaso" required></td>

                    </tr>
                </table>

            </form>
           
            <!-- Footer -->
            
            <form id="footer-container">
                <div class="footer-containerDIV">


                    

                    <!-- Sección Realizado por -->
                    <div class="firma-section">
                        <div class="firma-box-title">Estado Final:</div>
                        <div class="firma-boxes">
                            <p id='realizado_por' name='realizado_por' class="bold" style="visibility: hidden;"></p>
                            <p id='realizado_por' name='realizado_por' class="bold" style="visibility: hidden;"></p>

                            <div class="signature">
                                <!-- Agregar la imagen aquí -->
                                <img src="..\assets\images\TEST.png" alt="Firma" class="firma">

                            </div>

                        </div>
                        <div class="date-container">
                            <div id='fecha_realizacion' name='fecha_realizacion' class="date"></div>
                            <p id='mensaje_realizador' name='mensaje_realizador' class="text-bottom" style="visibility: hidden;">Firmado digitalmente</p>
                        </div>
                    </div>
                     <!-- Sección Realizado por -->
                     <div class="firma-section">
                        <div class="firma-box-title">Responsable:</div>
                        <div class="firma-boxes">
                            <p id='realizado_por' name='realizado_por' class="bold"></p>
                            <p id='realizado_por' name='realizado_por' class="bold"></p>

                            <div class="signature">
                                <!-- Agregar la imagen aquí -->
                                <img src="..\assets\images\TEST.png" alt="Firma" class="firma">

                            </div>

                        </div>
                        <div class="date-container">
                            <div id='fecha_realizacion' name='fecha_realizacion' class="date"></div>
                            <p id='mensaje_realizador' name='mensaje_realizador' class="text-bottom">Firmado digitalmente</p>
                        </div>
                    </div>
                    
                </div>

                <footer class="TextoBajo">
                    <p class="ParrafoBajo">
                        La información contenida en esta solicitud es de carácter CONFIDENCIAL y es considerada SECRETO
                        INDUSTRIAL.
                    </p>
                </footer>
            </form>
        </div>


    </div>


</body>
<div class="button-container">
    <button class="botones" id="download-pdf">Descargar PDF</button>
    <button class="botones" id="firma" onclick="firmayguarda()">Firmar Documento</button>
    <button class="botones" id="guardar">Guardar Documento</button>
    <p id='id_actaMuestreo' name='id_actaMuestreo' style="display: none;"></p>
    <p id='id_analisis_externo' name='id_analisis_externo' style="display: none;"></p>
    <p id='numero_solicitud_analisis_externo' name='id_analisis_externo' style="display: none;"></p>
    <p id='solicitado_por_analisis_externo' name='id_analisis_externo' style="display: none;"></p>
</div>

<div id="notification" class="notification-container notify" style="display: none;">
    <p id="notification-message">Este es un mensaje de notificación.</p>
</div>

</html>
<script>
    document.getElementById('download-pdf').addEventListener('click', function () {
        const buttonContainer = document.querySelector('.button-container');
        const elementToExport = document.getElementById('form-container');

        // Guardar los estilos originales
        const originalBorder = elementToExport.style.border;
        const originalBoxShadow = elementToExport.style.boxShadow;

        // Ocultar borde y sombra
        elementToExport.style.border = 'none';
        elementToExport.style.boxShadow = 'none';

        buttonContainer.style.display = 'none';

        html2canvas(elementToExport, {
            scale: 2,
            logging: true,
            useCORS: true
        }).then(canvas => {
            // Restaurar los estilos originales
            elementToExport.style.border = originalBorder;
            elementToExport.style.boxShadow = originalBoxShadow;

            buttonContainer.style.display = 'block';

            // Ajusta la calidad de la imagen
            const imgData = canvas.toDataURL('image/jpeg', 0.75); // 0.75 es la calidad de la imagen (puedes ajustar este valor)

            const pdf = new jspdf.jsPDF({
                orientation: 'p',
                unit: 'mm',
                format: 'a4'
            });

            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();
            const imgWidth = pageWidth;
            let imgHeight = canvas.height * imgWidth / canvas.width;
            let heightLeft = imgHeight;

            let position = 0;
            pdf.addImage(imgData, 'JPEG', 0, position, imgWidth, imgHeight); // Cambia 'image/png' a 'JPEG'
            heightLeft -= pageHeight;

            while (heightLeft > 0) {
                position = heightLeft - imgHeight;
                pdf.addPage();
                pdf.addImage(imgData, 'JPEG', 0, position, imgWidth, imgHeight); // Cambia 'image/png' a 'JPEG'
                heightLeft -= pageHeight;
            }

            pdf.save('documento.pdf');
            $.notify("PDF generado con éxito", "success");
        });
    });


    var usuarioActual = "<?php echo $_SESSION['usuario']; ?>";
    var idAnalisisExterno = <?php echo json_encode($_POST['id'] ?? ''); ?>;

    console.log("ID Analisis Externo:", idAnalisisExterno);

function loadData() {
    console.log(idAnalisisExterno);
    $.ajax({
        url: './backend/acta_liberacion/carga_acta_liberacion.php',
        type: 'GET',
        data: {
            id_acta: idAnalisisExterno
        },
        dataType: 'json', // Asegúrate de que la respuesta esperada es JSON
        success: function (response) {
            if (response.success) {
                if (response.analisis && response.analisis.length > 0) {
                    const analisis = response.analisis; // Datos del análisis externo
                    const primerAnalisis = analisis[0];
                    const acta_muestreo= response.Acta_Muestreo[0];

                    // Sumar los resultados de producto en un solo texto
                    var productoCompleto = primerAnalisis.prod_nombre_producto + ' ' + primerAnalisis.prod_concentracion + ' ' + primerAnalisis.prod_formato;
                    var fecha_yoh = "<?php echo date('Y-m-d'); ?>";
                    // Actualizar el elemento con el texto combinado
                    $('#producto_completo').text(productoCompleto);
                    $('#producto_completoT1').val(productoCompleto);

                    // Actualizar los inputs con los datos del análisis
                    $('#nro_registro').text(response.numero_registro);
                    $('#nro_version').text(1);
                    $('#nro_acta').text(response.numero_acta);
                    $('#fecha_acta_lib').val(fecha_yoh);
                    $('#fecha_lib').val(fecha_yoh);
                    $('#nro_acta_liberacion').text(response.numero_acta);
                    
                    
                    $('#nro_lote').val(primerAnalisis.lote);
                    $('#tipo_producto').val(primerAnalisis.prod_tipo_producto);
                    $('#tamaño_lote').val(primerAnalisis.tamano_lote);
                    $('#codigo_interno').val(primerAnalisis.codigo_interno);
                    $('#fecha_elaboracion').val(primerAnalisis.fecha_elaboracion);
                    $('#cond_almacenamiento').val(primerAnalisis.condicion_almacenamiento);
                    $('#fecha_vencimiento').val(primerAnalisis.fecha_vencimiento);

                    // TABLA 2
                    $('#nro_acta_muestreo').val(acta_muestreo.numero_acta);
                    $('#fecha_acta_muestreo').val(acta_muestreo.fecha_muestreo);
                    $('#laboratorio_analista').val(primerAnalisis.laboratorio);
                    $('#nro_solicitud_analisis').val(primerAnalisis.numero_solicitud);
                    $('#fecha_solicitud_analisis').val(primerAnalisis.fecha_solicitud);
                    $('#nro_analisis').val(primerAnalisis.laboratorio_nro_analisis);
                    $('#fecha_envio').val(primerAnalisis.fecha_envio);
                    $('#fecha_revision').val(primerAnalisis.laboratorio_fecha_analisis);

                    // TABLA 3
                    $('#nombre_producto').text(primerAnalisis.prod_nombre_producto);
                    $('#nro_loteT3').val(primerAnalisis.lote);
                    $('#fecha_elabT3').val(primerAnalisis.fecha_elaboracion);
                    $('#fecha_vencT3').val(primerAnalisis.fecha_vencimiento);
                    $('#producto_completoT3').val(productoCompleto);


                } else {
                    console.error('Estructura de la respuesta no es la esperada:', response);
                    alert("Error en carga de datos. Revisa la consola para más detalles.");
                }
            } else {
                console.error('Error en la respuesta del servidor:', response.message);
                alert("Error en carga de datos. Revisa la consola para más detalles.");
            }
        },
        error: function (xhr, status, error) {
            console.error('Error cargando los datos: ' + error);
            console.error('AJAX error: ' + status + ' : ' + error);
            alert("Error en carga de datos. Revisa la consola para más detalles.");
        }
    });
}


    function firmayguarda(){
        // Hacer visibles los elementos de .formulario.resp
        console.log('click firma')
        
        document.getElementById('firmar').style.display = 'none';
        
    };
</script>