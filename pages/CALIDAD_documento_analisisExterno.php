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
    <div id="form-container" class="form-container formpadding">
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
                    <p name="producto" id="producto" style="margin: 0; font-size: 11px; font-weight: bold; color: #000;">
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
                            <td name="nro_registro" id="nro_registro"></td>
                        </tr>
                        <tr>
                            <td>N° Versión:</td>
                            <td name="nro_version" id="nro_version"></td>
                        </tr>
                        <tr>
                            <td>N° Solicitud:</td>
                            <td name="nro_solicitud" id="nro_solicitud"></td>
                        </tr>
                        <tr>
                            <td>Fecha :</td>
                            <td>
                                <input type="date" id="fecha" name="fecha" style="border: 0px;" readonly>
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
                        <td><input type="text" id="laboratorio_analista" name="laboratorio_analista" required></td>

                    </tr>
                    <tr>
                        <td class="titulo">2. Fecha de Solicitud:(*)</td>
                        <td><input type="text" id="fecha_solicitud" name="fecha_solicitud" required></td>

                    </tr>
                    <tr>
                        <td class="titulo">3. Análisis según:(**)</td>
                        <td><input type="text" id="analisis_segun" name="analisis_segun" required></td>
                        <td class="titulo titulo-right">N° Documento:</td>
                        <td><input type="text" id="nro_documento" name="nro_documento" required></td>
                    </tr>

                    <tr>
                        <td class="titulo">4. Fecha de Cotización:(**)</td>
                        <td><input type="text" id="fecha_cotizacion" name="fecha_cotizacion" required></td>
                    </tr>
                    <tr>
                        <td class="titulo">5. Estandar Provisto por:(**)</td>
                        <td><input type="text" id="Estandar" name="Estandar" required></td>
                        <td class="titulo titulo-right">Otro:</td>
                        <td><input type="text" id="otro1" name="otro1"></td>
                    </tr>
                    <tr>
                        <td class="titulo">6. Adjunta HDS:(***)</td>
                        <td><input type="text" id="HDS" name="HDS" required></td>
                        <td class="titulo titulo-right">Otro:</td>
                        <td><input type="text" id="otro2" name="otro2"></td>
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
                        <td><input type="text" id="tamaño_lote" name="tamaño_lote" required></td>

                    </tr>
                    <tr>
                        <td class="titulo">2. Presentación:</td>
                        <td><input type="text" id="presentacion" name="presentacion" required></td>
                        <td class="titulo titulo-right">11. Fecha Elab.:</td>
                        <td><input type="text" id="fecha_elab" name="fecha_elab" required></td>

                    </tr>
                    <tr>
                        <td class="titulo">3. Serie o lote:</td>
                        <td><input type="text" id="serie" name="serie" required></td>
                        <td class="titulo titulo-right">12. Fecha Vence:</td>
                        <td><input type="text" id="fecha_venc" name="fecha_venc" required></td>

                    </tr>
                    <tr>
                        <td class="titulo">4. Registro I.S.P:</td>
                        <td><input type="text" id="registro" name="registro" required></td>
                        <td class="titulo titulo-right">13. Muestra:</td>
                        <td><input type="text" id="muestra" name="muestra" required></td>
                    </tr>
                    <tr>
                        <td class="titulo">5. Condic. almacenamiento</td>
                        <td><input type="text" id="condicion_almacenamiento" name="condicion_almacenamiento" required>
                        </td>
                        <td class="titulo titulo-right">14. Cta.muestra</td>
                        <td><input type="text" id="contra_muestra" name="contra_muestra"></td>
                    </tr>
                    <tr>
                        <td class="titulo">6. fabricante:</td>
                        <td><input type="text" id="fabricante" name="fabricante" required></td>
                        <td class="titulo titulo-right">Otro:</td>
                        <td><input type="text" id="otro3" name="otro3"></td>
                    </tr>
                    <tr>
                        <td class="titulo">7. Muestreado por:</td>
                        <td><input type="text" id="muestreado_por" name="muestreado_por" required></td>
                        <td class="titulo titulo-right">Observaciones:</td>
                        <td><input type="text" id="Observaciones" name="Observaciones"></td>

                    </tr>
                    <tr>
                        <td class="titulo">8. Muestreado según POS:</td>
                        <td><input type="text" id="POS" name="POS" required></td>


                    </tr>
                    <tr>
                        <td class="titulo">9. Código:</td>
                        <td><input type="text" id="codigo" name="codigo" required></td>


                    </tr>
                    <!-- Continúa agregando más filas según los campos requeridos -->
                </table>

            </form>
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
                        <th class="revision">Revisión</th>

                    </tr>
                    <tr class="bordeAbajo">
                        <td class="tituloTabla">Apariencia:</td>
                        <td class="Metod" id="Metod_Apariencia">Metodología interna laboratorio analista</td>
                        <td class="Espec" id="Metod_Especs">Solución límpida, transparente, de color ligeramente
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
                        <td class="tituloTabla">Identificación:</td>
                        <td class="Metod" id="Metod_Identificacion">Metodología interna laboratorio analista</td>
                        <td class="Espec" id="Espec_Identificacion">Solución límpida, transparente, de color ligeramente
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
                        <td class="tituloTabla">Valoración:</td>
                        <td class="Metod" id="Metod_Valoracion">Metodología interna laboratorio analista</td>
                        <td class="Espec" id="Espec_Valoracion">Solución límpida, transparente, de color ligeramente
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
                        <td class="tituloTabla">pH:</td>
                        <td class="Metod" id="Metod_pH">Metodología interna laboratorio analista</td>
                        <td class="Espec" id="Espec_pH">Solución límpida, transparente, de color ligeramente amarillo,
                            inolora, sin
                            partículas...
                        </td>
                        <td class="revision"><input type="checkbox" class="checkmark">
                            <span class="tamañoRevision ">cumple</span>
                            <br><input type="checkbox" class="checkmark">
                            <span class="tamañoRevision ">no cumple</span>
                        </td>
                    </tr>
                    <tr class="bordeAbajo">
                        <td class="tituloTabla">Volumen extraíble:</td>
                        <td class="Metod" id="Metod_VolumenExtraible">Metodología interna laboratorio analista</td>
                        <td class="Espec" id="Espec_VolumenExtraible">Solución límpida, transparente, de color
                            ligeramente amarillo, inolora, sin
                            partículas...
                        </td>
                        <td class="revision"><input type="checkbox" class="checkmark">
                            <span class="tamañoRevision ">cumple</span>
                            <br><input type="checkbox" class="checkmark">
                            <span class="tamañoRevision ">no cumple</span>
                        </td>
                    </tr>
                    <tr class="bordeAbajo">
                        <td class="tituloTabla">Densidad y Osmoralidad:</td>
                        <td class="Metod" id="Metod_DyO">Metodología interna laboratorio analista</td>
                        <td class="Espec" id="Espec_DyO">Solución límpida, transparente, de color ligeramente amarillo,
                            inolora, sin
                            partículas...
                        </td>
                        <td class="revision"><input type="checkbox" class="checkmark">
                            <span class="tamañoRevision ">cumple</span>
                            <br><input type="checkbox" class="checkmark">
                            <span class="tamañoRevision ">no cumple</span>
                        </td>
                    </tr>
                    <tr class="bordeAbajo">
                        <td class="tituloTabla">Límite de Oxalato:</td>
                        <td class="Metod" id="Metod_Oxalato">Metodología interna laboratorio analista</td>
                        <td class="Espec" id="Espec_Oxalato">Solución límpida, transparente, de color ligeramente
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
                        <td class="tituloTabla">Material Particulado:</td>
                        <td class="Metod" id="Metod_Mparticulado">Metodología interna laboratorio analista</td>
                        <td class="Espec" id="Espec_Mparticulado">Solución límpida, transparente, de color ligeramente
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
                        <td class="tituloTabla">Material Sub-Particulado:</td>
                        <td class="Metod" id="Metod_Msubparticulado">Metodología interna laboratorio analista</td>
                        <td class="Espec" id="Espec_Msubparticulado">Solución límpida, transparente, de color
                            ligeramente amarillo, inolora, sin
                            partículas...
                        </td>
                        <td class="revision"><input type="checkbox" class="checkmark">
                            <span class="tamañoRevision ">cumple</span>
                            <br><input type="checkbox" class="checkmark">
                            <span class="tamañoRevision ">no cumple</span>
                        </td>
                    </tr>
                    <tr class="bordeAbajo">
                        <td class="tituloTabla">Esterilidad:</td>
                        <td class="Metod" id="Metod_Esterilidad">Metodología interna laboratorio analista</td>
                        <td class="Espec" id="Espec_Esterilidad">Solución límpida, transparente, de color ligeramente
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
                        <td class="tituloTabla">Endotoxinas:</td>
                        <td class="Metod" id="Metod_Endotoxinas">Metodología interna laboratorio analista</td>
                        <td class="Espec" id="Espec_Endotoxinas">Solución límpida, transparente, de color ligeramente
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



            const imgData = canvas.toDataURL('image/png');
            const pdf = new jspdf.jsPDF({
                orientation: 'p',
                unit: 'mm',
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
            var nombreDocumento = document.getElementById('nro_registro').textContent.trim();
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
    });
</script>
<script>
    // Declarar la variable primeravez
    let primeravez = false;

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
        // Cargar datos iniciales (si es necesario)
        loadData();
    });
   

    var idAnalisisExterno = <?php echo json_encode($_POST['id'] ?? ''); ?>;
    console.log("ID Analisis Externo:", idAnalisisExterno);
    function loadData() {
    $.ajax({
        url: './backend/analisis/ingresar_resultados_analisis.php', // Asegúrate de que la URL es correcta
        type: 'GET',
        data: {
            id_acta: idAnalisisExterno
        }, // Enviar el ID como parte de la solicitud
        dataType: 'json',
        success: function(response) {
            // Asumiendo que la respuesta incluye datos bajo la clave 'data'
            const datos = response.data[0]; // Asumiendo que solo hay un resultado
            $('#Tipo_Producto').text(datos.tipo_producto);
            $('#producto').text(datos.producto);
            $('#nro_registro').text(datos.numero_acta);
            $('#nro_version').text(datos.version_acta);
            $('#nro_solicitud').text(datos.id_acta);
            $('#fecha').val(datos.fecha_muestreo);
            // Continúa llenando otros campos según necesites
        },
        error: function() {
            console.error('Error cargando los datos');
        }
    });
}

</script>