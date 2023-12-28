<!DOCTYPE html>
<html lang="es">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Especificación Producto Terminado</title>
    <link rel="stylesheet" href="../test/testings.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
</head>

<body>
    <div id="form-container">
        <div id="Maincontainer">
            <div id="header-container" style="width: 100%;">
                <!-- Asegúrate de tener un contenedor para el header con display flex -->
                <div id="header" class="header"
                    style="display: flex; justify-content: space-between; align-items: flex-start;">

                    <!-- Logo e Información Izquierda -->
                    <div class="header-left" style="flex: 1;">
                    <br>
                            <br>
                        <img src="../assets/images/logo documentos.png" alt="Logo"
                            style="height: 90px;" />
                        <!-- Ajusta el tamaño según sea necesario -->
                        <br>
                    </div>
                    <!-- Título Central -->
                    <div class="header-center"
                        style="flex: 2; text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center; font-family: 'Verdana', sans-serif; height: 100%;">
                        <br>
                        <br>
                        <h1 id="Tipo_Producto" name="Tipo_Producto" style="margin: 0; font-size: 14px; font-weight: normla; color: #000; line-height: 1.2;"></h1>
                        <p name="producto" id="producto" style="margin: 0; font-size: 18px; font-weight: bold; color: #000; margin-top: 5px;"></p>
                        <hr style="width:75%;">
                        <div style="position: relative; font-size: 16px; font-weight: bold; color: #000; margin-top: 2px;">
                            Dirección de Calidad

                        </div>
                    </div>





                    <!-- Información Derecha con Tabla -->
                    <div class="header-right" style="flex: 1; font-size: 0.75em;">
                    <br>
                    <br>
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="border: 1px solid rgb(56, 53, 255); padding: 2px;">Doc No:</td>
                                <td name="documento" id="documento" style="border: 1px solid rgb(56, 53, 255); padding: 2px;"></td>
                                <td style="border: 1px solid rgb(56, 53, 255); padding: 2px;">Elab. por:</td>
                                <td name="elaboradoPor" id="elaboradoPor" style="border: 1px solid rgb(56, 53, 255); padding: 2px;">QF DCA.LSR</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid rgb(56, 53, 255); padding: 2px;">Edición:</td>
                                <td name="fechaEdicion" id="fechaEdicion" style="border: 1px solid rgb(56, 53, 255); padding: 2px;"></td>
                                <td style="border: 1px solid rgb(56, 53, 255); padding: 2px;">Rev.Por:</td>
                                <td name="supervisadoPor" id="supervisadoPor" style="border: 1px solid rgb(56, 53, 255); padding: 2px;">QF DTS.CPG</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid rgb(56, 53, 255); padding: 2px;">Versión:</td>
                                <td name="version" id="version" style="border: 1px solid rgb(56, 53, 255); padding: 2px;"></td>
                                <td style="border: 1px solid rgb(56, 53, 255); padding: 2px;">Aut.Por:</td>
                                <td name="autorizadoPor" id="autorizadoPor" style="border: 1px solid rgb(56, 53, 255); padding: 2px;">QF DTL.ISM</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid rgb(56, 53, 255); padding: 2px;">Vigencia:</td>
                                <td name="periodosVigencia" id="periodosVigencia"  style="border: 1px solid rgb(56, 53, 255); padding: 2px;"></td>
                                <td style="border: 1px solid rgb(56, 53, 255); padding: 2px;">Página:</td>
                                <td style="border: 1px solid rgb(56, 53, 255); padding: 2px;">1 de 2</td>
                            </tr>
                        </table>
                    </div>



                </div>
                <!-- Fila adicional con dos columnas debajo del encabezado existente -->
                <div class="header-bottom" style="display: flex; justify-content: space-between; align-items: flex-start; padding: 0 10px; box-sizing: border-box;">
                        <div class="header-bottom-left" style="flex: 1; background-color: #ffffff; padding: 10px; box-sizing: border-box; text-align: left;">
                            <div class="sub-info" style="font-size: 14px;text-align: left;">

                            Producto de recetario magistral   <br>
                                Res. Ex. N° 2988/2018  
                            </div>
                        </div>
                        <div class="header-bottom-right" style="flex: 1; background-color: #ffffff; padding: 10px; box-sizing: border-box; text-align: right;">
                            <div class="sub-info" style="font-size: 14px; text-align: right;">
                                RF XII 001/18: 1A, 1B, 2A, 2C, 3A, 3B, 3D, 4 y homeopático
                            </div>
                        </div>
                    </div>
            </div>
            
            
            
            
                <div id="content" class="content">
                <!-- Resto del contenido del cuerpo igual al HTML original -->
                <div class="table-section">
                    <div class="analysis-section" style="font-size: 18px; font-weight: bold; margin-top: 20px;">
                        I. Análisis Generales
                    </div>
                    <table id="analisisFQ" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Análisis</th>
                                <th>Metodología</th>
                                <th>Criterio de Aceptación</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>



            <div id="additionalContent" class="content">
                <div class="table-section">
                <!-- Sección de Análisis Microbiológico -->
                    <div class="analysis-section" style="font-size: 18px; font-weight: bold; margin-top: 20px;">
                        II. Análisis Microbiológico
                    </div>

                    <!-- Tabla de Análisis Microbiológico -->
                    <table id="analisisMB" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Análisis</th>
                                <th>Metodología</th>
                                <th>Criterio de Aceptación</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="footer" id="additionalfooter">
                <!-- Sección realizada por -->
                <div class="footer-section">
                    <br>
                    <div class="footer-box-title">Realizado por:</div>
                    <div class="footer-box">
                        <p id='creadoPor' name='creadoPor' class="bold"></p>
                        <p class="bold">
                            Director de Calidad
                            </p>
                        <div class="signature">[Espacio para la firma]</div>
                        <p>Firmado digitalmente</p>
                    </div>
                    <div id='fecha_Edicion' name='fecha_Edicion' class="date"></div>
                    <br>
                </div>

                <!-- Sección revisada por -->
                <div class="footer-section">
                    <div class="footer-box-title">Revisado por:</div>
                    <div class="footer-box">
                        <p id='revisadoPor' name='revisadoPor'class="bold"></p>
                        <p class="bold">
                            Jefe de Producción
                            </p>
                        <div class="signature">[Espacio para la firma]</div>
                        <p>Firmado digitalmente</p>
                    </div>
                    <div id='fechaRevision' name='fechaRevision' class="date"></div>
                </div>

                <!-- Sección aprobada por -->
                <div class="footer-section">
                    <div class="footer-box-title">Aprobado por:</div>
                    <div class="footer-box">
                        <p id='aprobadoPor' name='aprobadoPor' class="bold"></p>
                        <p class="bold">
                            Director Técnico
                            </p>
                        <div class="signature">[Espacio para la firma]</div>
                        <p>Firmado digitalmente</p>
                    </div>
                    <div id='fechaAprobacion' name='fechaAprobacion' class="date"></div>
                </div>
            </div>

        </div>
    </div>

    <button id="download-pdf">Descargar PDF</button>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
        var button = document.getElementById('download-pdf');
        button.addEventListener('click', function () {
            downloadPDF();
        });
    });

    function downloadPDF() {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF({
            orientation: 'p',
            unit: 'pt',
            format: 'a4'
        });

        // Altura total de la página A4 en puntos
        const pageHeight = 842;

        // Calcula las alturas según la proporción 1.5:6:2
        const headerHeight = (1.25 / 9.5) * pageHeight;
        const contentHeight = (6.5 / 9.5) * pageHeight;
        const footerHeight = (1.75 / 9.5) * pageHeight;
        const marginFromBottom = 20; // Margen desde la parte inferior que quieres para el footer

        // Función para añadir el header y el footer con margen
        const addHeaderFooter = (pdf, canvasHeader, canvasFooter) => {
            const imgDataHeader = canvasHeader.toDataURL('image/png');
            const imgDataFooter = canvasFooter.toDataURL('image/png');
            pdf.addImage(imgDataHeader, 'PNG', 0, 0, 595, headerHeight);
            // Ajusta la posición del footer para incluir el margen
            pdf.addImage(imgDataFooter, 'PNG', 0, pageHeight - footerHeight - marginFromBottom, 595, footerHeight);
        };

        // Captura el header y el footer una sola vez
        Promise.all([
            html2canvas(document.getElementById('header-container'), { scale: 2 }),
            html2canvas(document.getElementById('additionalfooter'), { scale: 2 })
        ]).then(([canvasHeader, canvasFooter]) => {
            // Añade el contenido de la primera página
            html2canvas(document.getElementById('content'), { scale: 2 }).then(canvasContent => {
                const imgDataContent = canvasContent.toDataURL('image/png');
                addHeaderFooter(pdf, canvasHeader, canvasFooter);
                pdf.addImage(imgDataContent, 'PNG', 0, headerHeight, 595, contentHeight);

                // Añade una nueva página para el additionalContent
                pdf.addPage();

                // Añade el contenido de la segunda página con un escalado menor
                html2canvas(document.getElementById('additionalContent'), { scale: 2 }).then(canvasAdditionalContent => {
                    const imgDataAdditionalContent = canvasAdditionalContent.toDataURL('image/png');
                    addHeaderFooter(pdf, canvasHeader, canvasFooter);
                    const scaledContentHeight = contentHeight * (canvasAdditionalContent.height / canvasContent.height);
                    pdf.addImage(imgDataAdditionalContent, 'PNG', 0, headerHeight, 595, scaledContentHeight);

                    // Guardar el PDF
                    pdf.save('Especificacion_Producto_Terminado.pdf');
                });
            });
        });
    }
        function cargarDatosEspecificacion(id) {
    $.ajax({
        url: './backend/calidad/documento_especificacion_productoBE.php',
        type: 'GET',
        data: { id: id },
        success: function(response) {
            procesarDatosEspecificacion(response);

        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud: ", status, error);
        }
    });
}
function procesarDatosEspecificacion(response) {
    // Asegúrate de que 'response' contiene la propiedad 'productos'
    if (!response || !response.productos || !Array.isArray(response.productos)) {
        console.error('Los datos recibidos no son válidos:', response);
        return;
    }

    // Procesar cada producto
    response.productos.forEach(function(producto) {
        poblarYDeshabilitarCamposProducto(producto);

        // Procesar la primera especificación para cada producto
        let especificaciones = Object.values(producto.especificaciones || {});
        if (especificaciones.length > 0) {
            let especificacion = especificaciones[0];
            let analisisFQ = especificacion.analisis.filter(a => a.tipo_analisis === 'analisis_FQ');
            let analisisMB = especificacion.analisis.filter(a => a.tipo_analisis === 'analisis_MB');

            mostrarAnalisisFQ(analisisFQ);
            mostrarAnalisisMB(analisisMB);
        }
    });
}
function mostrarAnalisisFQ(analisis) {
    // Verifica si hay datos para el análisis FQ
    if (analisis.length > 0) {
        // Si hay datos, muestra la tabla y procesa los datos
        if ($.fn.DataTable.isDataTable('#analisisFQ')) {
            $('#analisisFQ').DataTable().clear().rows.add(analisis).draw();
        } else {
            $('#analisisFQ').DataTable({
                data: analisis,
                columns: [
                    { title: 'Análisis', data: 'descripcion_analisis' },
                    { title: 'Metodología', data: 'metodologia' },
                    { title: 'Criterio aceptación', data: 'criterios_aceptacion' }
                ],
                paging: false,
                info: false,
                searching: false,
                lengthChange: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                }
            });
        }
        // Muestra la sección del análisis FQ
        $('#content').show();
    } else {
        // Si no hay datos, oculta la sección del análisis FQ
        $('#content').hide();
    }
}

function mostrarAnalisisMB(analisis) {
    // Verifica si hay datos para el análisis microbiológico
    if (analisis.length > 0) {
        // Si hay datos, muestra la tabla y procesa los datos
        if ($.fn.DataTable.isDataTable('#analisisMB')) {
            $('#analisisMB').DataTable().clear().rows.add(analisis).draw();
        } else {
            $('#analisisMB').DataTable({
                data: analisis,
                columns: [
                    { title: 'Análisis', data: 'descripcion_analisis' },
                    { title: 'Metodología', data: 'metodologia' },
                    { title: 'Criterio aceptación', data: 'criterios_aceptacion' }
                ],
                paging: false,
                info: false,
                searching: false,
                lengthChange: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                }
            });
        }
        // Muestra la sección del análisis microbiológico
        $('#additionalContent').show();
    } else {
        // Si no hay datos, oculta la sección del análisis microbiológico
        $('#additionalContent').hide();
    }
}

function poblarYDeshabilitarCamposProducto(producto) {
    if (producto) {
        // Usando .text() para elementos h2, h3 y p
        $('#Tipo_Producto').text('Especificación ' + producto.tipo_producto);
        $('#producto').text(producto.nombre_producto);
        $('#concentracion').text(producto.concentracion);
        $('#formato').text(producto.formato);
        $('#documento').text(producto.documento_producto);
        $('#elaboradoPor').text(producto.elaborado_por);
        
        let especificacion = Object.values(producto.especificaciones)[0];
        if (especificacion) {
            $('#fechaEdicion').text(especificacion.fecha_edicion);
            $('#version').text(especificacion.version);
            $('#periodosVigencia').text(especificacion.vigencia);
            $('#creadoPor').text(especificacion.creado_por || 'No disponible');
            $('#revisadoPor').text(especificacion.revisado_por || 'No disponible');
            $('#aprobadoPor').text(especificacion.aprobado_por || 'No disponible');
            
            // Actualizar fechas de revisión y aprobación
            $('#fecha_Edicion').text('Fecha: ' + (especificacion.fecha_revision || 'No disponible'));
            $('#fechaRevision').text('Fecha: ' + (especificacion.fecha_revision || 'No disponible'));
            $('#fechaAprobacion').text('Fecha: ' + (especificacion.fecha_aprobacion || 'No disponible'));

        }
    }
}


    </script>

</body>

</html>