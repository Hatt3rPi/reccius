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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Especificación Producto Terminado</title>
    <link rel="stylesheet" href="../assets/css/DocumentoEspecs.css">
    <link rel="stylesheet" href="../assets/css/Notificacion.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="../assets/js/notify.js"></script>

</head>

<body>
    <div id="form-container" class="ContenedorP">
        <div id="Maincontainer">
            <div id="header-container" style="width: 100%;">
                <!-- Asegúrate de tener un contenedor para el header con display flex -->
                <div id="header" class="header" style="display: flex; justify-content: space-between; align-items: flex-start;">

                    <!-- Logo e Información Izquierda -->
                    <div class="header-left">
                        <img src="../assets/images/logo_reccius_medicina_especializada.png" alt="Logo" style="height: 60px;" />
                        <!-- Ajusta el tamaño según sea necesario -->
                        <br>
                    </div>
                    <!-- Título Central -->
                    <div class="header-center" style="flex: 2; text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center; font-family: 'Arial', sans-serif; height: 100%;">
                        <h1 id="Tipo_Producto" name="Tipo_Producto" style="margin: 0; font-size: 11px; font-weight: normal; color: #000; line-height: 1.2;">
                        </h1>
                        <p name="producto" id="producto" style="margin: 0; font-size: 11px; font-weight: bold; color: #000;"></p>
                        <hr style="width:75%; margin-top: 2px; margin-bottom: 1px;">
                        <div style="position: relative; font-size: 11px; font-weight: bold; color: #000; margin-top: 2px;">
                            Dirección de Calidad
                        </div>
                    </div>
                    <!-- Información Derecha con Tabla -->
                    <div class="header-right" style="font-size: 8px; font-family: 'Arial', sans-serif">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="border: 1px solid rgb(56, 53, 255);">Doc. N°:</td>
                                <td name="documento" id="documento" style="border: 1px solid rgb(56, 53, 255);text-align: center"></td>
                                <td style="border: 1px solid rgb(56, 53, 255);">Elab. por:</td>
                                <td name="creadoPor" id="creadoPor" style="border: 1px solid rgb(56, 53, 255); text-align: center"></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid rgb(56, 53, 255);">Edición:</td>
                                <td name="edicion" id="edicion" style="border: 1px solid rgb(56, 53, 255); text-align: center"></td>
                                <td style="border: 1px solid rgb(56, 53, 255);">Rev.Por:</td>
                                <td name="revisadoPor" id="revisadoPor" style="border: 1px solid rgb(56, 53, 255); text-align: center"></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid rgb(56, 53, 255);">Versión:</td>
                                <td name="version" id="version" style="border: 1px solid rgb(56, 53, 255); text-align: center"></td>
                                <td style="border: 1px solid rgb(56, 53, 255);">Aut.Por:</td>
                                <td name="aprobadoPor" id="aprobadoPor" style="border: 1px solid rgb(56, 53, 255); text-align: center"></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid rgb(56, 53, 255);">Vigencia:</td>
                                <td name="vigencia" id="vigencia" style="border: 1px solid rgb(56, 53, 255); text-align: center"></td>
                                <td style="border: 1px solid rgb(56, 53, 255);">Página:</td>
                                <td id="pagina-numero" class="pagina-numero" style="border: 1px solid rgb(56, 53, 255); text-align: center"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- Fila adicional con dos columnas debajo del encabezado existente -->
                <div class="header-bottom" style="display: flex; justify-content: space-between; align-items: flex-start; padding: 0 10px; box-sizing: border-box; font-family: 'Arial', sans-serif">
                    <div class="header-bottom-left" style="flex: 1; background-color: #ffffff; padding: 10px; box-sizing: border-box; text-align: left;">
                        <div class="sub-info" style="font-size: 10px;text-align: left;">
                            Producto de recetario magistral <br>
                            Res. Ex. N° 2988/2018
                        </div>
                    </div>
                    <div class="header-bottom-right" style="flex: 1; background-color: #ffffff; padding: 10px; box-sizing: border-box; text-align: right;">
                        <div class="sub-info" style="font-size: 10px; text-align: right;">
                            RF XII 001/18: 1A, 1B, 2A, 2C, 3A, 3B, 3D, 4 y homeopático
                        </div>
                    </div>
                </div>
            </div>

            <div class="watermark" id="watermark"></div>
            <div id="contenido_main">

                <h1 id="Tipo_Producto2" name="Tipo_Producto2" style="margin: 0; font-size: 11px; font-weight: bold; color: #000; line-height: 1.2; text-decoration: underline; text-transform: uppercase; text-align: center;">
                </h1>
                <p name="producto2" id="producto2" style="margin: 0; font-size: 11px; font-weight: bold; color: #000; text-transform: uppercase; text-align: center;">
                </p>
                <div id="content" class="content">
                    <!-- Resto del contenido del cuerpo igual al HTML original -->
                    <div class="table-section">
                        <div class="analysis-section" style="font-size: 10px; font-weight: bold; margin-top: 5px; padding-left: 50px;">
                            I. Análisis Generales
                        </div>
                        <table id="analisisFQ" class="compact table-bordered" style="width:100%; font-size: 10px">
                            <thead>
                                <tr>
                                    <th style="width: 170px; text-align: center">Análisis</th>
                                    <th style="width: 106px; text-align: center">Metodología</th>
                                    <th style="width: 404px; text-align: center">Criterio de Aceptación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Las filas de la tabla se agregarán dinámicamente con JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="additionalContent" class="content">
                    <div class="table-section">
                        <!-- Sección de Análisis Microbiológico -->
                        <div class="analysis-section" style="font-size: 10px; font-weight: bold; margin-top: 20px; padding-left: 50px;">
                            II. Análisis Microbiológico
                        </div>
                        <!-- Tabla de Análisis Microbiológico -->
                        <table id="analisisMB" class="display compact table-bordered" style="width:100%; font-size: 10px">
                            <thead>
                                <tr>
                                    <th style="width: 170px; text-align: center">Análisis</th>
                                    <th style="width: 106px; text-align: center">Metodología</th>
                                    <th style="width: 404px; text-align: center">Criterio de Aceptación</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="footer-containerDIV" id="footer">
                <!-- Sección realizada por -->
                <div class="firma-section">
                    <div class="firma-box-title">Realizado por:</div>
                    <div class="firma-boxes">
                        <p id="creadoPor2" name="creadoPor2" class="bold"></p>
                        <p id="cargo_creador" name="cargo_creador" class="bold"></p>
                        <div class="signature" id="QRcreador" name="QRcreador">
                            <img id="QRcreador" name="QRcreador" src="https://pub-bde9ff3e851b4092bfe7076570692078.r2.dev/firma_null.webp" alt="Firma" class="firma">
                        </div>

                    </div>
                    <div class="date-container">
                        <p id="mensaje_creador" name="mensaje_creador" style="display: none;">Firmado digitalmente</p>
                        <div id="fecha_Edicion" name="fecha_Edicion" class="date" style="font-size: 8px"></div>
                    </div>
                </div>
                <!-- Sección revisada por -->
                <div class="firma-section">
                    <div class="firma-box-title">Revisado por:</div>
                    <div class="firma-boxes">
                        <p id="revisadoPor2" name="revisadoPor2" class="bold"></p>
                        <p id="cargo_revisor" name="cargo_revisor" class="bold"></p>
                        <div class="signature" id="QRrevisor" name="QRrevisor">
                            <img id="QRrevisor" name="QRrevisor" src="https://pub-bde9ff3e851b4092bfe7076570692078.r2.dev/firma_null.webp" alt="Firma" class="firma">
                        </div>

                    </div>
                    <div class="date-container">
                        <p id="mensaje_revisor" name="mensaje_revisor" style="display: none;">Firmado digitalmente</p>
                        <div id="fechaRevision" name="fechaRevision" class="date" style="font-size: 8px"></div>
                    </div>
                </div>
                <!-- Sección aprobada por -->
                <div class="firma-section">
                    <div class="firma-box-title">Aprobado por:</div>
                    <div class="firma-boxes">
                        <p id="aprobadoPor2" name="aprobadoPor2" class="bold"></p>
                        <p id="cargo_aprobador" name="cargo_aprobador" class="bold"></p>
                        <div class="signature" id="QRaprobador" name="QRaprobador">
                            <img id="QRaprobador" name="QRaprobador" src="https://pub-bde9ff3e851b4092bfe7076570692078.r2.dev/firma_null.webp" alt="Firma" class="firma">
                        </div>

                    </div>
                    <div class="date-container">
                        <p id="mensaje_aprobador" name="mensaje_aprobador" style="display: none;">Firmado digitalmente</p>
                        <div id="fechaAprobacion" name="fechaAprobacion" class="date" style="font-size: 8px"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="button-container">
        <button id="sign-document" style="display: none;">Firmar Documento</button>
        <button id="download-pdf">Descargar PDF</button>

        <p id='id_especificacion' name='id_especificacion' style="display: none;"></p>

    </div>
    <div id="notification" class="notification-container notify" style="display: none;">
        <p id="notification-message">Este es un mensaje de notificación.</p>
    </div>
    <script>
        var usuarioNombre = "<?php echo $_SESSION['nombre']; ?>";
        var usuario = "<?php echo $_SESSION['usuario']; ?>";

        document.getElementById('download-pdf').addEventListener('click', function() {
            $.notify("Generando PDF", "warn");

            // Ocultar la sección de botones antes de capturar la pantalla
            const buttonContainer = document.querySelector('.button-container');
            buttonContainer.style.display = 'none';

            // Captura las tres secciones de forma separada
            const headerElement = document.getElementById('header-container');
            const contentElement = document.getElementById('contenido_main');
            const footerElement = document.getElementById('footer');

            const pdf = new jspdf.jsPDF({
                orientation: 'p',
                unit: 'mm',
                format: 'a4' // Especificar tamaño A4
            });

            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();
            const paddingTop = 10; // Define el padding superior

            // Primero, captura el header
            html2canvas(headerElement, {
                scale: 2,
                useCORS: true
            }).then(headerCanvas => {
                const headerImgData = headerCanvas.toDataURL('image/jpeg');
                const headerWidth = pageWidth;
                const headerHeight = headerCanvas.height * headerWidth / headerCanvas.width;

                // Luego, captura el contenido
                html2canvas(contentElement, {
                    scale: 2,
                    useCORS: true
                }).then(contentCanvas => {
                    const contentImgData = contentCanvas.toDataURL('image/jpeg');
                    const contentWidth = pageWidth;
                    const contentHeight = contentCanvas.height * contentWidth / contentCanvas.width;

                    // Por último, captura el footer
                    html2canvas(footerElement, {
                        scale: 2,
                        useCORS: true
                    }).then(footerCanvas => {
                        const footerImgData = footerCanvas.toDataURL('image/jpeg');
                        const footerWidth = pageWidth;
                        const footerHeight = footerCanvas.height * footerWidth / footerCanvas.width;

                        // Ahora posiciona las imágenes en el PDF
                        let yOffset = paddingTop; // Agrega el padding superior al yOffset

                        // Agrega el header en la parte superior con padding
                        pdf.addImage(headerImgData, 'JPEG', 0, yOffset, headerWidth, headerHeight);
                        yOffset += headerHeight;

                        // Agrega el contenido, asegurándose de que haya suficiente espacio para el footer
                        if (yOffset + contentHeight > pageHeight - footerHeight) {
                            // Si el contenido sobrepasa la página, lo manejamos en varias páginas
                            let heightLeft = contentHeight;

                            while (heightLeft > 0) {
                                let position = yOffset;

                                // Si no es la primera página, agregamos una nueva página
                                if (yOffset !== 0) {
                                    pdf.addPage();
                                    position = paddingTop; // reiniciamos el yOffset con padding superior
                                    yOffset = paddingTop;
                                }

                                const availableHeight = pageHeight - yOffset - footerHeight;

                                const heightToPrint = Math.min(availableHeight, heightLeft);
                                const widthToPrint = contentWidth;

                                pdf.addImage(contentImgData, 'JPEG', 0, yOffset, widthToPrint, heightToPrint);

                                heightLeft -= heightToPrint;
                                yOffset += heightToPrint;
                            }
                        } else {
                            pdf.addImage(contentImgData, 'JPEG', 0, yOffset, contentWidth, contentHeight);
                            yOffset += contentHeight;
                        }

                        // Finalmente, agrega el footer en la parte inferior
                        pdf.addImage(footerImgData, 'JPEG', 0, pageHeight - footerHeight, footerWidth, footerHeight);

                        const nombreProducto = document.getElementById('producto').textContent.trim();
                        const nombreDocumento = document.getElementById('documento').textContent.trim();
                        pdf.save(`${nombreDocumento} ${nombreProducto}.pdf`);

                        // Restaurar visibilidad y estilos
                        buttonContainer.style.display = 'block';
                        $.notify("PDF generado con éxito", "success");
                    });
                });
            }).catch(error => {
                $.notify("Error al generar el PDF", "error");
                console.error("Error generating PDF: ", error);
            });
        });










        function cargarDatosEspecificacion(id) {
            console.log("A1")
            $.ajax({
                url: './backend/calidad/documento_especificacion_productoBE.php',
                type: 'GET',
                data: {
                    id: id
                },
                success: function(response) {
                    procesarDatosEspecificacion(response);
                    verificarYMostrarBotonFirma(response);
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud: ", status, error);
                }
            });
        }

        async function procesarDatosEspecificacion(response) {
            console.log("A2")
            // Validación de la respuesta
            if (!response || !response.productos || !Array.isArray(response.productos)) {
                console.error('Los datos recibidos no son válidos:', response);
                return;
            }
            // Procesamiento de cada producto
            response.productos.forEach(function(producto) {
                poblarYDeshabilitarCamposProducto(producto);

                let especificaciones = Object.values(producto.especificaciones || {});
                if (especificaciones.length > 0) {
                    let especificacion = especificaciones[0];

                    let analisisFQ = especificacion.analisis.filter(a => a.tipo_analisis === 'analisis_FQ');
                    if (analisisFQ.length > 0) {
                        mostrarAnalisisFQ(analisisFQ);
                    } else {
                        // Si no hay datos, oculta la sección del análisis FQ
                        $('#content').hide();
                    }

                    let analisisMB = especificacion.analisis.filter(a => a.tipo_analisis === 'analisis_MB');
                    if (analisisMB.length > 0) {
                        mostrarAnalisisMB(analisisMB);
                    } else {
                        // Si no hay datos, oculta la sección del análisis MB
                        $('#additionalContent').hide();
                    }
                }
            });
        }

        function mostrarImagenFirma(usuario, contenedorQR) {
            console.log("A4")
            var firmaUrl;

            if (usuario && usuario.qr_documento) {
                firmaUrl = usuario.qr_documento;
            } else if (usuario && usuario.foto_firma) {
                firmaUrl = usuario.foto_firma;
            }

            if (firmaUrl) {
                fetch(firmaUrl)
                    .then(resp => resp.blob())
                    .then(blob => new Promise((resolve, _) => {
                        const reader = new FileReader();
                        reader.onloadend = () => resolve(reader.result);
                        reader.readAsDataURL(blob);
                    }))
                    .then((data) => {
                        var imgElement = document.getElementById(contenedorQR).querySelector('img');
                        if (!imgElement) {
                            imgElement = document.createElement('img');
                            imgElement.style.width = '64px';
                            imgElement.style.height = '64px';
                            document.getElementById(contenedorQR).appendChild(imgElement);
                        }
                        imgElement.src = data;
                    })
                    .catch(() => {
                        var contenedor = document.getElementById(contenedorQR);
                        contenedor.innerHTML = '<span src="https://pub-bde9ff3e851b4092bfe7076570692078.r2.dev/firma_no_proporcionada.webp" style="display: inline-block; width: 64px; height: 64px; line-height: 64px; text-align: center;"></span>';
                    });
            } else {
                var contenedor = document.getElementById(contenedorQR);
                contenedor.innerHTML = '<span  src="https://pub-bde9ff3e851b4092bfe7076570692078.r2.dev/firma_no_proporcionada.webp" style="display: inline-block; width: 64px; height: 64px; line-height: 64px; text-align: center;"></span>';
            }
        }

        function poblarYDeshabilitarCamposProducto(producto) {
            console.log("A3")
            if (producto) {
                $('#Tipo_Producto').text('Especificación ' + producto.tipo_producto);
                $('#producto').text(producto.nombre_producto);
                $('#Tipo_Producto2').text('Especificación ' + producto.tipo_producto);
                $('#producto2').text(producto.nombre_producto);
                $('#concentracion').text(producto.concentracion);
                $('#formato').text(producto.formato);
                $('#documento').text(producto.documento_producto);

                let especificacion = Object.values(producto.especificaciones)[0];
                if (especificacion) {
                    $('#id_especificacion').text(especificacion.id);
                    $('#cargo_creador').text(especificacion.creado_por.cargo);
                    $('#cargo_revisor').text(especificacion.revisado_por.cargo);
                    $('#cargo_aprobador').text(especificacion.aprobado_por.cargo);
                    $('#creadoPor').text(especificacion.creado_por.nombre || 'No disponible');
                    $('#creadoPor2').text(especificacion.creado_por.nombre || 'No disponible');
                    $('#revisadoPor').text(especificacion.revisado_por.nombre || 'No disponible');
                    $('#revisadoPor2').text(especificacion.revisado_por.nombre || 'No disponible');
                    $('#aprobadoPor').text(especificacion.aprobado_por.nombre || 'No disponible');
                    $('#aprobadoPor2').text(especificacion.aprobado_por.nombre || 'No disponible');
                    $('#vigencia').text(especificacion.vigencia || 'No disponible');
                    $('#version').text(especificacion.version || 'No disponible');
                    $('#edicion').text(especificacion.fecha_edicion || 'No disponible');

                    if (especificacion.fecha_edicion) {
                        document.getElementById('mensaje_creador').style.display = 'block';
                        $('#fecha_Edicion').text('Fecha: ' + especificacion.fecha_edicion);
                        mostrarImagenFirma(especificacion.creado_por, 'QRcreador');
                    } else {
                        document.getElementById('mensaje_creador').style.display = 'none';
                        $('#fecha_Edicion').text('Firma Pendiente');
                    }
                    if (especificacion.revisado_por.fecha_revision) {
                        document.getElementById('mensaje_revisor').style.display = 'block';
                        $('#fechaRevision').text('Fecha: ' + especificacion.revisado_por.fecha_revision);
                        mostrarImagenFirma(especificacion.revisado_por, 'QRrevisor');
                    } else {
                        document.getElementById('mensaje_revisor').style.display = 'none';
                        $('#fechaRevision').text('Firma Pendiente');
                    }
                    if (especificacion.aprobado_por.fecha_aprobacion) {
                        document.getElementById('mensaje_aprobador').style.display = 'block';
                        $('#fechaAprobacion').text('Fecha: ' + especificacion.aprobado_por.fecha_aprobacion);
                        mostrarImagenFirma(especificacion.aprobado_por, 'QRaprobador');
                    } else {
                        document.getElementById('mensaje_aprobador').style.display = 'none';
                        $('#fechaAprobacion').text('Firma Pendiente');
                    }
                }
                actualizarEstadoDocumento();
            }
        }

        function mostrarAnalisisFQ(analisis) {
            console.log("A5")
            // Verifica si hay datos para el análisis FQ
            console.log(analisis)
            if (analisis.length > 0) {
                // Si hay datos, muestra la tabla y procesa los datos
                if ($.fn.DataTable.isDataTable('#analisisFQ')) {
                    $('#analisisFQ').DataTable().clear().rows.add(analisis).draw();
                } else {
                    $('#analisisFQ').DataTable({
                        data: analisis,
                        columns: [{
                                title: 'Análisis',
                                data: 'descripcion_analisis',
                                width: '170px',
                                createdCell: function(td) {
                                    $(td).css('font-weight', 'bold');
                                    $(td).css('text-align', 'center');
                                    $(td).css('vertical-align', 'middle');
                                }
                            },
                            {
                                title: 'Metodología',
                                data: 'metodologia',
                                width: '106px',
                                createdCell: function(td) {
                                    $(td).css('text-align', 'center');
                                    $(td).css('vertical-align', 'middle');
                                }
                            },
                            {
                                title: 'Criterio aceptación',
                                data: 'criterios_aceptacion',
                                width: '404px'
                            }
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
            console.log("A6")
            // Verifica si hay datos para el análisis microbiológico
            if (analisis.length > 0) {
                // Si hay datos, muestra la tabla y procesa los datos
                if ($.fn.DataTable.isDataTable('#analisisMB')) {
                    $('#analisisMB').DataTable().clear().rows.add(analisis).draw();
                } else {
                    $('#analisisMB').DataTable({
                        data: analisis,
                        columns: [{
                                title: 'Análisis',
                                data: 'descripcion_analisis',
                                width: '170px',
                                createdCell: function(td) {
                                    $(td).css('font-weight', 'bold');
                                    $(td).css('text-align', 'center');
                                    $(td).css('vertical-align', 'middle');
                                }
                            },
                            {
                                title: 'Metodología',
                                data: 'metodologia',
                                width: '106px',
                                createdCell: function(td) {
                                    $(td).css('text-align', 'center');
                                    $(td).css('vertical-align', 'middle');
                                }
                            },
                            {
                                title: 'Criterio aceptación',
                                data: 'criterios_aceptacion',
                                width: '404px'
                            }
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

        document.getElementById('sign-document').addEventListener('click', function() {
            // Verifica si el documento está pendiente de firma y si el usuario es el revisor o aprobador
            var puedeFirmar = (esRevisorYFirmaPendiente() || esAprobadorYFirmaPendiente());
            if (puedeFirmar && confirm("¿Estás seguro que deseas firmar el documento?")) {
                // Aquí va el código para firmar el documento
                firmarDocumento();
            }
        });

        function esRevisorYFirmaPendiente() {
            console.log("A7")
            return $('#revisadoPor').text() === '<?php echo $_SESSION['nombre']; ?>' && $('#fechaRevision').text() === 'Firma Pendiente';
        }

        function esAprobadorYFirmaPendiente() {
            console.log("A8")
            return $('#aprobadoPor').text() === '<?php echo $_SESSION['nombre']; ?>' && $('#fechaAprobacion').text() === 'Firma Pendiente';
        }

        function firmarDocumento() {
            console.log("A9")
            // Obten los datos necesarios para la firmaid_especificacion
            var idEspecificacion = $('#id_especificacion').text().trim();
            var rolUsuario = '';
            if (esRevisorYFirmaPendiente()) {
                rolUsuario = 'revisado_por';
            } else if (esAprobadorYFirmaPendiente()) {
                rolUsuario = 'aprobado_por';
            } else {
                // En caso de que no se pueda determinar el rol, mostrar un error o tomar una acción adecuada
                console.error("No se puede determinar el rol del usuario para la firma.");
                return;
            }

            // Llamada AJAX al backend
            $.ajax({
                url: './backend/calidad/firma_documentoBE.php',
                type: 'POST',
                data: {
                    idEspecificacion: idEspecificacion,
                    rolUsuario: rolUsuario
                },
                success: function(response) {
                    // Aquí manejas la respuesta del backend
                    console.log('Firma actualizada correctamente:', response);
                    // Actualiza el estado del documento en el frontend
                    actualizarEstadoDocumento();
                    cargarDatosEspecificacion(idEspecificacion);
                    // Mostrar notificaciones de éxito y advertencia
                    $.notify("Documento firmado con éxito.", "success");
                    $.notify("Tarea terminada con éxito", "success");
                },
                error: function(xhr, status, error) {
                    console.error('Error al firmar documento:', status, error);
                    $.notify("Error al firmar documento:", "error");
                }
            });
        }

        function verificarYMostrarBotonFirma(response) {
            console.log("A10")
            console.log("Respuesta recibida:", response);

            if (!response || !response.productos || !Array.isArray(response.productos)) {
                console.log("Respuesta no válida o sin productos");
                return; // Si la respuesta no es válida, no hacer nada
            }

            // Considerando que la respuesta tiene una estructura esperada
            let especificacion = response.productos[0].especificaciones[Object.keys(response.productos[0].especificaciones)[0]];
            console.log("Especificación procesada:", especificacion);
            let esAprobador = especificacion.aprobado_por.nombre === usuarioNombre;
            let esRevisorPendiente = especificacion.revisado_por.nombre === usuarioNombre && especificacion.revisado_por.fecha_revision === null;
            let esAprobadorPendiente = especificacion.aprobado_por.nombre === usuarioNombre && especificacion.aprobado_por.fecha_aprobacion === null;
            let revisorHaFirmado = especificacion.revisado_por.fecha_revision !== null;

            console.log("Es Revisor y Firma Pendiente:", esRevisorPendiente);
            console.log("Es Aprobador y Firma Pendiente:", esAprobadorPendiente);
            console.log("Revisor ha firmado:", revisorHaFirmado);

            if (esRevisorPendiente || (esAprobadorPendiente && revisorHaFirmado)) {
                console.log("Mostrar botón de firma (habilitado)");
                document.getElementById('sign-document').style.display = 'block';
                document.getElementById('sign-document').disabled = false;
            } else if (esAprobadorPendiente && !revisorHaFirmado) {
                console.log("Mostrar botón de firma (deshabilitado)");
                document.getElementById('sign-document').style.display = 'block';
                document.getElementById('sign-document').disabled = true;
                document.getElementById('sign-document').title = "Documento debe estar firmado por revisor para poder aprobarlo";
            } else {
                console.log("No mostrar botón de firma");
                document.getElementById('sign-document').style.display = 'none';
            }
            if (esAprobador && especificacion.revisado_por.fecha_revision === null) {
                console.log("Usuario es revisor y la firma del aprobador está pendiente.");
                $.notify("Es necesario que el usuario Revisor firme antes que tú.", "warn");
            }
        }

        function actualizarEstadoDocumento() {
            console.log("A11")
            var creadorFirmado = $('#fecha_Edicion').text() !== 'Firma Pendiente';
            var revisorFirmado = $('#fechaRevision').text() !== 'Firma Pendiente';
            var aprobadorFirmado = $('#fechaAprobacion').text() !== 'Firma Pendiente';

            var watermarks = document.querySelectorAll('.watermark');

            watermarks.forEach(function(watermark) {
                if (creadorFirmado && revisorFirmado && aprobadorFirmado) {
                    watermark.textContent = 'CONFIDENCIAL';
                    watermark.classList.add('watermark');
                } else {
                    watermark.textContent = 'PENDIENTE DE APROBACIÓN';
                    watermark.classList.add('pendiente-aprobacion');
                }
            });
        }

        window.onload = function() {
            cargarDatosEspecificacion(id);
            verificarYMostrarBotonFirma();
        };
    </script>

</body>

</html>