<?php
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
                    <p name="producto" id="producto"
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
                            <td name="nro_solicitud" id="nro_solicitud"></td>
                        </tr>
                        <tr>
                            <td>Fecha Liberación:</td>
                            <td>
                                <input type="date" id="fecha_lib" name="fecha_lib" style="border: 0px;" readonly>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
            <!-- Body -->
            <br>
            <form>
                <table>
                    <tr>
                        <td class="Subtitulos" colspan="4">I. IDENTIFICACIÓN DE LA MUESTRA</td>
                    </tr>
                    <tr>
                        <td class="titulo">1. Producto</td>
                        <td><input type="text" id="Producto" name="Producto" required></td>
                        <td class="titulo">2. N°Lote:</td>
                        <td><input type="text" id="nro_lote" name="nro_lote" required></td>

                    </tr>
                    <tr>
                        <td class="titulo">3. Tipo Producto:</td>
                        <td><input type="text" id="tipo_producto" name="tipo_producto" required></td>
                        <td class="titulo">4. Tamaño de Lote:</td>
                        <td><input type="text" id="tamaño_lote" name="tamaño_lote" required></td>

                    </tr>
                    <tr>
                        <td class="titulo">5. Código Interno:</td>
                        <td><input type="text" id="codigo_interno" name="codigo_interno" required></td>
                        <td class="titulo">6. Fecha Elaboración:</td>
                        <td><input type="text" id="fecha_elab" name="fecha_elab" required></td>

                    </tr>

                    <tr>
                        <td class="titulo">7. Cond. Almacenamiento</td>
                        <td><input type="text" id="cond_almacenamiento" name="cond_almacenamiento" required></td>
                        <td class="titulo">8. Fecha de Vencimiento:</td>
                        <td><input type="text" id="fecha_venc" name="fecha_venc" required></td>

                    </tr>

                </table>

            </form>
            <br>

            <!-- Sección II: MUESTREO -->
            <form>
                <table>
                    <tr>
                        <td class="Subtitulos" colspan="4">II. MUESTREO Y ANALISIS</td>
                    </tr>
                    <tr>
                        <td class="titulo">1. N°Acta de Muestreo:</td>
                        <td><input type="text" id="nro_acta_muestreo" name="nro_acta_muestreo" required></td>
                        <td class="titulo">2. Fecha Acta Muestreo:</td>
                        <td><input type="text" id="fecha_acta_muestreo" name="fecha_acta_muestreo" required></td>

                    </tr>
                    <tr>
                        <td class="titulo">3. N° Solicitud de Análisis</td>
                        <td><input type="text" id="nro_solicitud_analisis" name="nro_solicitud_analisis" required></td>
                        <td class="titulo">4. Fecha Solicitud Análisis</td>
                        <td><input type="text" id="fecha_solicitud_analisis" name="fecha_solicitud_analisis" required>
                        </td>

                    </tr>
                    <tr>
                        <td class="titulo">5. Laboratorio Analista</td>
                        <td><input type="text" id="laboratorio_analista" name="laboratorio_analista" required></td>
                        <td class="titulo">6. Fecha de Envío</td>
                        <td><input type="text" id="fecha_envio" name="fecha_envio" required></td>

                    </tr>

                    <tr>
                        <td class="titulo">7. N° de Análisis:</td>
                        <td><input type="text" id="nro_analisis" name="nro_analisis" required></td>
                        <td class="titulo">8. Fecha de Revisión:</td>
                        <td><input type="text" id="fecha_revision" name="fecha_revision" required></td>

                    </tr>
                </table>

            </form>
            <br>
            <form>
                <table>
                    <tr>
                        <td class="Subtitulos" colspan="4">
                            III. ANÁLISIS SOLICITADOS</td>
                    </tr>
                    <tr class="bordeAbajo">
                        <th>Análisis</th>
                        <th>Metodología</th>
                        <th>Especificación</th>
                        <th>Revisión</th>
                    </tr>
                    <tr class="bordeAbajo">
                        <td class="tituloTabla">Planilla de fabricación</td>
                        <td class="Metod" id="Metod_Pfabricacion">Metodología interna laboratorio analista</td>
                        <td class="Espec" id="Espec_Pfabricacion">Solución límpida, transparente, de color ligeramente
                            amarillo, inolora, sin
                            partículas...
                        </td>
                        <td class="revision"><input type="checkbox" class="checkmark">
                            <span class="tamañoRevision ">cumple</span>
                            <br><input type="checkbox" class="checkmark">
                            <span class="tamañoRevision ">no cumple</span>
                        </td>
                    </tr>
                    <tr class="bordeAbajo">
                        <td class="tituloTabla">Acta de Muestreo</td>
                        <td class="Metod" id="Metod_Actamuestreo">Metodología interna laboratorio analista</td>
                        <td class="Espec" id="Espec_Actamuestreo">Solución límpida, transparente, de color ligeramente
                            amarillo, inolora, sin
                            partículas...
                        </td>
                        <td class="revision"><input type="checkbox" class="checkmark">
                            <span class="tamañoRevision ">cumple</span>
                            <br><input type="checkbox" class="checkmark">
                            <span class="tamañoRevision ">no cumple</span>
                        </td>
                    </tr>
                    <tr class="bordeAbajo">
                        <td class="tituloTabla">Solicitud de Análisis</td>
                        <td class="Metod" id="Metod_Solicitud">Metodología interna laboratorio analista</td>
                        <td class="Espec" id="Espec_Solicitud">Solución límpida, transparente, de color ligeramente
                            amarillo, inolora, sin
                            partículas...
                        </td>
                        <td class="revision"><input type="checkbox" class="checkmark">
                            <span class="tamañoRevision ">cumple</span>
                            <br><input type="checkbox" class="checkmark">
                            <span class="tamañoRevision ">no cumple</span>
                        </td>
                    </tr>
                    <tr class="bordeAbajo">
                        <td class="tituloTabla">Certificado de Análisis</td>
                        <td class="Metod" id="Metod_Certificado">Metodología interna laboratorio analista</td>
                        <td class="Espec" id="Espec_Certificado">Solución límpida, transparente, de color ligeramente
                            amarillo, inolora, sin
                            partículas...
                        </td>
                        <td class="revision"><input type="checkbox" class="checkmark">
                            <span class="tamañoRevision ">cumple</span>
                            <br><input type="checkbox" class="checkmark">
                            <span class="tamañoRevision ">no cumple</span>
                        </td>
                    </tr>

                    <!-- ... -->
                </table>

            </form>
            <!-- Sección II: MUESTREO -->
            <br>
            <form>
                <table>
                    <tr>
                        <td class="Subtitulos" colspan="4">IV. MUESTREO Y ANALISIS</td>
                    </tr>
                    <tr>
                        <td class="titulo">1. N°Acta de Liberacion:</td>
                        <td><input type="text" id="nro_acta_liberacion" name="nro_acta_liberacion" required></td>
                        <td class="titulo">2. Fecha Liberacion:</td>
                        <td><input type="text" id="fecha_lib" name="fecha_lib" required></td>

                    </tr>
                    <tr>
                        <td class="titulo">3. Producto:</td>
                        <td><input type="text" id="producto" name="producto" required></td>
                        <td class="titulo">4. N° Lote:</td>
                        <td><input type="text" id="nro_lote" name="nro_lote" required></td>

                    </tr>
                    <tr>
                        <td class="titulo">5. Fecha de Elaboración:</td>
                        <td><input type="text" id="fecha_elab" name="fecha_elab" required></td>
                        <td class="titulo">6. Fecha de Vencimiento:</td>
                        <td><input type="text" id="fecha_venc" name="fecha_venc" required></td>

                    </tr>

                    <tr>
                        <td class="titulo">7. Cantidad real Liberada:</td>
                        <td><input type="text" id="cantidad_real" name="cantidad_real" required></td>
                        <td class="titulo">8. N°Parte de Ingreso/Traspaso:</td>
                        <td><input type="text" id="nro_traspaso" name="nro_traspaso" required></td>

                    </tr>
                </table>

            </form>
            <br>

            <!-- Footer -->
            <br>
            <div class="footer-container">
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
                        válido. El certificado de análisis solo debe disponer de la información vertida en esta
                        solicitud.
                        La información contenida en esta solicitud es de carácter CONFIDENCIAL y es considerada SECRETO
                        INDUSTRIAL.
                    </p>
                </footer>
            </div>


        </div>


</body>
<div class="button-container">
    <button class="botones" id="download-pdf" >Descargar PDF</button>
    <button class="botones" id="firma" >Firmar Documento</button>
    <button class="botones" id="guardar" >Guardar Documento</button>
</div>

<div id="notification" class="notification-container notify" style="display: none;">
    <p id="notification-message">Este es un mensaje de notificación.</p>
</div>

</html>
<script>
    document.getElementById('download-pdf').addEventListener('click', function () {
        document.querySelector('.button-container').style.display = 'none';
        const elementToExport = document.getElementById('form-container');


        html2canvas(elementToExport, {
            scale: 2,
            logging: true,
            useCORS: true
        }).then(canvas => {
            document.querySelector('.button-container').style.display = 'block';
            elementToExport.style.border = '1px solid #000';
            elementToExport.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.1)';

            const imgData = canvas.toDataURL('image/png');
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
            pdf.addImage(imgData, 'JPEG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;

            while (heightLeft > 0) {
                position = heightLeft - imgHeight;
                pdf.addPage();
                pdf.addImage(imgData, 'JPEG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
            }

            pdf.save('documento.pdf');
            $.notify("PDF generado con éxito", "success");
        });
    });
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
        url: './backend/acta_liberacion/ingresar_resultados_liberacion.php',
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
                $('#numero_registro').text(primerAnalisis.numero_registro);
                $('#version').text(primerAnalisis.version);
                $('#numero_solicitud').text(primerAnalisis.numero_solicitud);
                // FALTA LA FECHA DE LIBERACION

                //TABLA 1
                
                $('#lote').val(primerAnalisis.lote);
                $('#nombre_producto').text(primerAnalisis.prod_nombre_producto);
                $('#nombre_producto2').val(primerAnalisis.prod_nombre_producto);
                $('#Tipo_Producto').text(primerAnalisis.prod_tipo_producto);
                $('#tamano_lote').val(primerAnalisis.tamano_lote);
                $('#codigo_mastersoft').val(primerAnalisis.codigo_mastersoft);
                $('#fecha_elaboracion').val(primerAnalisis.fecha_elaboracion);
                $('#condicion_almacenamiento').val(primerAnalisis.condicion_almacenamiento);
                $('#fecha_vencimiento').val(primerAnalisis.fecha_vencimiento);

                //TABLA 2

                $('#laboratorio').val(primerAnalisis.laboratorio);
                //FECHA DE MUESTREO
                //NUMERO SOLICITUD ANALISIS
                $('#fecha_solicitud').val(primerAnalisis.fecha_solicitud);
                //LABORATORIO ANALISTA
                //FECHA ENVIO
                //NUMERO ANALISIS
                //FECHA REVISION

                //TABLA 3
                //NRO ACTA LIBERACION
                //FECHA LIBERACION
                $('#nombre_producto').text(primerAnalisis.prod_nombre_producto);
                $('#lote').val(primerAnalisis.lote);
                $('#fecha_elaboracion').val(primerAnalisis.fecha_elaboracion);
                $('#fecha_vencimiento').val(primerAnalisis.fecha_vencimiento);
                // CANTIDAD REAL LIBERADA
                // N°PARTE DE INGRESO/ TRASPASO
            }

            if (analisis[0].revisado_por === usuarioActual && analisis[0].fecha_firma_revisor === null && analisis[0].estado === "En proceso de firmas") {
                $(".button-container").append('<button class="botones" id="FirmaAnalisisExternoRevisor">Firmar revisión análisis externo</button>');
                $("#FirmaAnalisisExternoRevisor").click(function() {
                    firmarDocumentoSolicitudExterna(idAnalisisExterno);
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error cargando los datos: ' + error);
            console.error('AJAX error: ' + status + ' : ' + error);
            alert("Error en carga de datos. Revisa la consola para más detalles.");
        }
    });
}


</script>