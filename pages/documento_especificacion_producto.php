<?php
//archivo: pages\documento_especificacion_producto.php
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
    <link rel="stylesheet" href="../assets/css/DocumentoEspecs_old.css">
    <link rel="stylesheet" href="../assets/css/Botones.css">
    <link rel="stylesheet" href="../assets/css/Notificacion.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="../assets/js/notify.js"></script>

</head>

<body>
    <div id="form-container" class="ContenedorP">
        <div id="Maincontainer">
            <!-- Contenedor Principal del Header -->
            <div id="header-container" class="header-container">
                <!-- Fila principal con logo, título y tabla informativa -->
                <div id="header" class="header">
                    <!-- Logo e Información Izquierda -->
                    <div class="header-left">
                        <img src="../assets/images/logo_reccius_medicina_especializada.png" alt="Logo" />
                    </div>

                    <!-- Título Central -->
                    <div class="header-center">
                        <h1 id="Tipo_Producto" class="header-toptitle"></h1>
                        <p id="producto" style="margin: 0; font-size: 11px; font-weight: bold; color: #000;"></p>
                        <hr>
                        <div class="header-subtitle">Dirección de Calidad</div>
                    </div>

                    <!-- Información Derecha con Tabla -->
                    <div class="header-right">
                        <table id="panel_informativo">
                            <tr>
                                <td>Doc. N°:</td>
                                <td id="documento"></td>
                                <td>Elab. por:</td>
                                <td id="creadoPor"></td>
                            </tr>
                            <tr>
                                <td>Edición:</td>
                                <td id="edicion"></td>
                                <td>Rev. Por:</td>
                                <td id="revisadoPor"></td>
                            </tr>
                            <tr>
                                <td>Versión:</td>
                                <td id="version"></td>
                                <td>Aut. Por:</td>
                                <td id="aprobadoPor"></td>
                            </tr>
                            <tr>
                                <td>Vigencia:</td>
                                <td id="vigencia"></td>
                                <td>Página:</td>
                                <td id="pagina-numero"></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Fila adicional debajo del encabezado -->
                <div class="header-bottom">
                    <div class="header-bottom-left">
                        <div class="sub-info" style="text-align: left;">
                            Producto de recetario magistral <br>
                            Res. Ex. N° 2988/2018
                        </div>
                    </div>
                    <div class="header-bottom-right">
                        <div class="sub-info" style="text-align: right;">
                            RF XII 001/18: 1A, 1B, 2A, 2C, 3A, 3B, 3D, 4 y homeopático
                        </div>
                    </div>
                </div>
            </div>


            <div class="watermark" id="watermark"></div>
            <div id="contenido_main">

                <div id="content" class="contenido">
                    <!-- Resto del contenido del cuerpo igual al HTML original -->
                    <div class="table-section">
                        <div class="analysis-section"
                            style="font-size: 10px; font-weight: bold; margin-top: 5px; padding-left: 50px;">
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
                <div id="additionalContent" class="contenido" >
                    <div class="table-section">
                        <!-- Sección de Análisis Microbiológico -->
                        <div class="analysis-section"
                            style="font-size: 10px; font-weight: bold; margin-top: 20px; padding-left: 50px;">
                            II. Análisis Microbiológico
                        </div>
                        <!-- Tabla de Análisis Microbiológico -->
                        <table id="analisisMB" class="display compact table-bordered"
                            style="width:100%; font-size: 10px">
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
                            <img id="QRcreador" name="QRcreador"
                                src="https://customware.fabarca212.workers.dev/assets/firma_null.webp" alt="Firma"
                                class="firma">
                        </div>

                    </div>
                    <div class="date-container">
                        
                        <div id="fecha_Edicion" name="fecha_Edicion" class="date" style="font-size: 8px"></div>
                        <p id="mensaje_creador" name="mensaje_creador" class="firma-bottom">Firmado
                            digitalmente</p>
                    </div>
                </div>
                <!-- Sección revisada por -->
                <div class="firma-section">
                    <div class="firma-box-title">Revisado por:</div>
                    <div class="firma-boxes">
                        <p id="revisadoPor2" name="revisadoPor2" class="bold"></p>
                        <p id="cargo_revisor" name="cargo_revisor" class="bold"></p>
                        <div class="signature" id="QRrevisor" name="QRrevisor">
                            <img id="QRrevisor" name="QRrevisor"
                                src="https://customware.fabarca212.workers.dev/assets/firma_null.webp" alt="Firma"
                                class="firma">
                        </div>

                    </div>
                    <div class="date-container">
                        
                        <div id="fechaRevision" name="fechaRevision" class="date" style="font-size: 8px"></div>
                        <p id="mensaje_revisor" name="mensaje_revisor" class="firma-bottom">Firmado
                            digitalmente</p>
                    </div>
                </div>
                <!-- Sección aprobada por -->
                <div class="firma-section">
                    <div class="firma-box-title">Aprobado por:</div>
                    <div class="firma-boxes">
                        <p id="aprobadoPor2" name="aprobadoPor2" class="bold"></p>
                        <p id="cargo_aprobador" name="cargo_aprobador" class="bold"></p>
                        <div class="signature" id="QRaprobador" name="QRaprobador">
                            <img id="QRaprobador" name="QRaprobador"
                                src="https://customware.fabarca212.workers.dev/assets/firma_null.webp" alt="Firma"
                                class="firma">
                        </div>

                    </div>
                    <div class="date-container">
                        
                        <div id="fechaAprobacion" name="fechaAprobacion" class="date" style="font-size: 8px"></div>
                        <p id="mensaje_aprobador" name="mensaje_aprobador" class="firma-bottom">Firmado
                            digitalmente</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="button-container">
        <button class="botones ingControl" id="sign-document" style="display: none;">Firmar Documento</button>
        <button class="botones ingControl" id="download-pdf">Descargar PDF</button>

        <p id='id_especificacion' name='id_especificacion' style="display: none;"></p>

    </div>
    <div id="notification" class="notification-container notify" style="display: none;">
        <p id="notification-message">Este es un mensaje de notificación.</p>
    </div>
    <script>
        var usuarioNombre = "<?php echo $_SESSION['nombre']; ?>";
        var usuario = "<?php echo $_SESSION['usuario']; ?>";

        document.getElementById('download-pdf').addEventListener('click', function () {
            $.notify("Generando PDF", "warn");

            // Ocultar la sección de botones antes de capturar la pantalla
            const buttonContainer = document.querySelector('.button-container');
            buttonContainer.style.display = 'none';

            const headerElement = document.getElementById('header-container');
            const contentElement = document.getElementById('content');
            const additionalContentElement = document.getElementById('additionalContent');
            const footerElement = document.getElementById('footer');

            const pdf = new jspdf.jsPDF({
                orientation: 'p',
                unit: 'mm',
                format: 'a4'
            });

            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();
            const paddingTop = 10; // Define el padding superior

            // Función para añadir el header y footer
            function addHeaderAndFooter(pdf, headerImgData, footerImgData) {
                const headerWidth = pageWidth;
                const headerHeight = headerElement.scrollHeight * headerWidth / headerElement.scrollWidth;

                const footerWidth = pageWidth;
                const footerHeight = footerElement.scrollHeight * footerWidth / footerElement.scrollWidth;

                // Añadir el header
                pdf.addImage(headerImgData, 'JPEG', 0, paddingTop, headerWidth, headerHeight);

                // Añadir el footer
                pdf.addImage(footerImgData, 'JPEG', 0, pageHeight - footerHeight, footerWidth, footerHeight);
            }

            // Verifica cuántas filas hay en la tabla
            const analisisFQRows = document.querySelectorAll('#analisisFQ tbody tr').length;

            // Captura el header y footer para usarlos en ambas páginas
            Promise.all([
                html2canvas(headerElement, {
                    scale: 2,
                    useCORS: true
                }),
                html2canvas(footerElement, {
                    scale: 2,
                    useCORS: true
                })
            ]).then(([headerCanvas, footerCanvas]) => {
                const headerImgData = headerCanvas.toDataURL('image/jpeg');
                const footerImgData = footerCanvas.toDataURL('image/jpeg');

                // Página 1: `#content`
                html2canvas(contentElement, {
                    scale: 2,
                    useCORS: true
                }).then(contentCanvas => {
                    const contentImgData = contentCanvas.toDataURL('image/jpeg');
                    const contentWidth = pageWidth;
                    const contentHeight = contentCanvas.height * contentWidth / contentCanvas.width;

                    // Añadir header y footer a la primera página
                    addHeaderAndFooter(pdf, headerImgData, footerImgData);

                    // Añadir el contenido principal a la primera página
                    let yOffset = headerCanvas.height * contentWidth / headerCanvas.width + paddingTop;
                    pdf.addImage(contentImgData, 'JPEG', 0, yOffset, contentWidth, contentHeight);

                    // Si hay más de 6 filas, añadir una nueva página con el contenido adicional
                    if (analisisFQRows > 8) {
                        pdf.addPage();

                        // Página 2: `#additionalContent`
                        html2canvas(additionalContentElement, {
                            scale: 2,
                            useCORS: true
                        }).then(additionalContentCanvas => {
                            const additionalContentImgData = additionalContentCanvas.toDataURL('image/jpeg');
                            const additionalContentWidth = pageWidth;
                            const additionalContentHeight = additionalContentCanvas.height * additionalContentWidth / additionalContentCanvas.width;

                            // Añadir header y footer a la segunda página
                            addHeaderAndFooter(pdf, headerImgData, footerImgData);

                            // Añadir el contenido adicional a la segunda página
                            let yOffset2 = headerCanvas.height * additionalContentWidth / headerCanvas.width + paddingTop;
                            pdf.addImage(additionalContentImgData, 'JPEG', 0, yOffset2, additionalContentWidth, additionalContentHeight);

                            // Descargar el PDF
                            const nombreProducto = document.getElementById('producto').textContent.trim();
                            const nombreDocumento = document.getElementById('documento').textContent.trim();
                            pdf.save(`${nombreDocumento} ${nombreProducto}.pdf`);

                            // Restaurar visibilidad y estilos
                            buttonContainer.style.display = 'block';
                            $.notify("PDF generado con éxito", "success");
                        });
                    } else {
                        // Incluir `#additionalContent` dentro de la primera página si hay menos de 6 filas
                        html2canvas(additionalContentElement, {
                            scale: 2,
                            useCORS: true
                        }).then(additionalContentCanvas => {
                            const additionalContentImgData = additionalContentCanvas.toDataURL('image/jpeg');
                            const additionalContentWidth = pageWidth;
                            const additionalContentHeight = additionalContentCanvas.height * additionalContentWidth / additionalContentCanvas.width;

                            // Añadir el contenido adicional dentro de la misma página
                            let yOffset2 = yOffset + contentHeight + 10; // Añadir un pequeño margen entre secciones
                            if (yOffset2 + additionalContentHeight > pageHeight - 40) {
                                pdf.addPage();
                                yOffset2 = paddingTop;
                            }
                            pdf.addImage(additionalContentImgData, 'JPEG', 0, yOffset2, additionalContentWidth, additionalContentHeight);

                            // Descargar el PDF
                            const nombreProducto = document.getElementById('producto').textContent.trim();
                            const nombreDocumento = document.getElementById('documento').textContent.trim();
                            pdf.save(`${nombreDocumento} ${nombreProducto}.pdf`);

                            // Restaurar visibilidad y estilos
                            buttonContainer.style.display = 'block';
                            $.notify("PDF generado con éxito", "success");
                        });
                    }
                });
            }).catch(error => {
                $.notify("Error al generar el PDF", "error");
                console.error("Error generating PDF: ", error);
            });
        });














        function cargarDatosEspecificacion(id) {
            console.log("A1");
            $.ajax({
                url: './backend/calidad/documento_especificacion_productoBE.php',
                type: 'GET',
                data: {
                    id: id
                },
                success: function (response) {
                    // Procesar los datos recibidos
                    procesarDatosEspecificacion(response);

                    // Verificar y mostrar el botón de firma
                    try {
                        if (!response?.productos?.length || !Array.isArray(response.productos)) {
                            console.log("Respuesta no válida o sin productos");
                            return; // Si la respuesta no es válida, no hacer nada
                        }

                        let especificacion = response.productos[0].especificaciones[Object.keys(response.productos[0].especificaciones)[0]];


                        // Extraer los datos de firmas
                        let creado_por = {
                            usuario: especificacion.creado_por.usuario,
                            fecha_edicion: especificacion.fecha_edicion // Asegúrate de que esta propiedad exista
                        };
                        let revisado_por = {
                            usuario: especificacion.revisado_por.usuario,
                            fecha_revision: especificacion.revisado_por.fecha_revision
                        };
                        let aprobado_por = {
                            usuario: especificacion.aprobado_por.usuario,
                            fecha_aprobacion: especificacion.aprobado_por.fecha_aprobacion
                        };

                        // Obtener el nombre del usuario actual
                        var usuarioNombre = "<?php echo $_SESSION['usuario']; ?>";
                        // Llamar a la función para verificar y mostrar el botón de firma
                        verificarYMostrarBotonFirma2(creado_por, revisado_por, aprobado_por, usuarioNombre);

                    } catch (error) {
                        console.error("Error al procesar los datos para la firma:", error);
                    }

                    // Ajustar las secciones según la cantidad de filas
                    ajustarSeccionesPorFilas();
                },
                error: function (xhr, status, error) {
                    console.error("Error en la solicitud: ", status, error);
                }
            });
        }


        function ajustarSeccionesPorFilas() {
            const analisisFQRows = document.querySelectorAll('#analisisFQ tbody tr').length;

            // Mostrar u ocultar el contenido adicional en función de la cantidad de filas
            if (analisisFQRows > 8) {
                // Si hay más de 8 filas, el contenido adicional debe mostrarse en otra sección
                document.getElementById('additionalContent').style.display = 'block';
            } else {
                // Si hay menos de 8 filas, el contenido adicional debe mostrarse en la misma página
                document.getElementById('additionalContent').style.display = 'flex';
            }
        }

        async function procesarDatosEspecificacion(response) {
            console.log("A2")
            if (!response || !response.productos || !Array.isArray(response.productos)) {
                console.error('Los datos recibidos no son válidos:', response);
                return;
            }

            response.productos.forEach(function (producto) {
                poblarYDeshabilitarCamposProducto(producto);
                let especificaciones = Object.values(producto.especificaciones || {});
                if (especificaciones.length > 0) {
                    let especificacion = especificaciones[0];

                    let analisisFQ = especificacion.analisis.filter(a => a.tipo_analisis === 'analisis_FQ');
                    if (analisisFQ.length > 0) {
                        mostrarAnalisisFQ(analisisFQ);
                    } else {
                        $('#content').hide(); // Ocultar si no hay análisis FQ
                    }

                    let analisisMB = especificacion.analisis.filter(a => a.tipo_analisis === 'analisis_MB');
                    if (analisisMB.length > 0) {
                        mostrarAnalisisMB(analisisMB);
                    } else {
                        $('#additionalContent').hide(); // Ocultar si no hay análisis MB
                    }
                }
            });

            ajustarSeccionesPorFilas(); // Ajustar después de procesar la especificación
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
                        contenedor.innerHTML = '<span src="https://customware.fabarca212.workers.dev/assets/firma_no_proporcionada.webp" style="display: inline-block; width: 64px; height: 64px; line-height: 64px; text-align: center;"></span>';
                    });
            } else {
                var contenedor = document.getElementById(contenedorQR);
                contenedor.innerHTML = '<span  src="https://customware.fabarca212.workers.dev/assets/firma_no_proporcionada.webp" style="display: inline-block; width: 64px; height: 64px; line-height: 64px; text-align: center;"></span>';
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
            if (analisis.length > 0) {
                if ($.fn.DataTable.isDataTable('#analisisFQ')) {
                    $('#analisisFQ').DataTable().clear().rows.add(analisis).draw();
                } else {
                    $('#analisisFQ').DataTable({
                        data: analisis,
                        columns: [{
                            title: 'Análisis',
                            data: 'descripcion_analisis',
                            width: '170px'
                        },
                        {
                            title: 'Metodología',
                            data: 'metodologia',
                            width: '106px'
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
                            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                        }
                    });
                }
                $('#content').show();
            } else {
                $('#content').hide();
            }
            ajustarSeccionesPorFilas(); // Ajustar el contenido una vez se muestre
        }

        function mostrarAnalisisMB(analisis) {
            console.log("A6")
            if (analisis.length > 0) {
                if ($.fn.DataTable.isDataTable('#analisisMB')) {
                    $('#analisisMB').DataTable().clear().rows.add(analisis).draw();
                } else {
                    $('#analisisMB').DataTable({
                        data: analisis,
                        columns: [{
                            title: 'Análisis',
                            data: 'descripcion_analisis',
                            width: '170px'
                        },
                        {
                            title: 'Metodología',
                            data: 'metodologia',
                            width: '106px'
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
                            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                        }
                    });
                }
                $('#additionalContent').show();
            } else {
                $('#additionalContent').hide();
            }
            ajustarSeccionesPorFilas(); // Ajustar el contenido una vez se muestre
        }

        document.getElementById('sign-document').addEventListener('click', function () {
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
                success: function (response) {
                    // Aquí manejas la respuesta del backend
                    console.log('Firma actualizada correctamente:', response);
                    // Actualiza el estado del documento en el frontend
                    actualizarEstadoDocumento();
                    cargarDatosEspecificacion(idEspecificacion);
                    // Mostrar notificaciones de éxito y advertencia
                    $.notify("Documento firmado con éxito.", "success");
                    $.notify("Tarea terminada con éxito", "success");
                },
                error: function (xhr, status, error) {
                    console.error('Error al firmar documento:', status, error);
                    $.notify("Error al firmar documento:", "error");
                }
            });
        }

        function verificarYMostrarBotonFirma(response) {
            console.log("A10", "Respuesta recibida:", response);

            if (!response?.productos?.length || !Array.isArray(response.productos)) {
                console.log("Respuesta no válida o sin productos");
                return; // Si la respuesta no es válida, no hacer nada
            }

            let especificacion = response.productos[0].especificaciones[Object.keys(response.productos[0].especificaciones)[0]];
            let {
                nombre: nombreRevisor,
                fecha_revision
            } = especificacion.revisado_por;
            let {
                nombre: nombreAprobador,
                fecha_aprobacion
            } = especificacion.aprobado_por;

            let esAprobador = nombreAprobador === usuarioNombre;
            let esRevisor = nombreRevisor === usuarioNombre;

            let esRevisorPendiente = esRevisor && !fecha_revision;
            let esAprobadorPendiente = esAprobador && !fecha_aprobacion;
            let revisorHaFirmado = !!fecha_revision;

            // Ocultar botón si no corresponde al usuario
            let botonFirma = document.getElementById('sign-document');
            if (!botonFirma) return;

            // Si es el revisor y la firma del aprobador está pendiente, ocultar botón
            if (esRevisor && fecha_aprobacion !== null) {
                botonFirma.style.display = 'none';
                return;
            }

            // Si es el aprobador y la firma del revisor está pendiente, ocultar botón
            if (esAprobador && fecha_revision === null) {
                botonFirma.style.display = 'none';
                return;
            }

            // Mostrar botón según las condiciones establecidas
            if (esRevisorPendiente) {
                console.log("Mostrar botón de firma para Revisor (habilitado)");
                botonFirma.style.display = 'block';
                botonFirma.disabled = false;
            } else if (esAprobadorPendiente && revisorHaFirmado) {
                console.log("Mostrar botón de firma para Aprobador (habilitado)");
                botonFirma.style.display = 'block';
                botonFirma.disabled = false;
            } else if (esAprobadorPendiente && !revisorHaFirmado) {
                console.log("Mostrar botón de firma para Aprobador (deshabilitado)");
                botonFirma.style.display = 'block';
                botonFirma.disabled = true;
                botonFirma.title = "Documento debe estar firmado por revisor para poder aprobarlo";
            }
        }
        function verificarYMostrarBotonFirma2(creado_por, revisado_por, aprobado_por, usuarioNombre) {
            let botonFirma = document.getElementById('sign-document');
            if (!botonFirma) return;

            // Extraer información de los parámetros
            let creadorUsuario = creado_por.usuario;
            let fechaEdicion = creado_por.fecha_edicion;

            let revisorUsuario = revisado_por.usuario;
            let fechaRevision = revisado_por.fecha_revision;

            let aprobadorUsuario = aprobado_por.usuario;
            let fechaAprobacion = aprobado_por.fecha_aprobacion;

            // Determinar los roles del usuario actual
            let esCreador = creadorUsuario === usuarioNombre;
            let esRevisor = revisorUsuario === usuarioNombre;
            let esAprobador = aprobadorUsuario === usuarioNombre;

            // Variable para controlar si se muestra el botón
            let mostrarBoton = false;

            // Lógica para el Creador
            if (esCreador && !fechaEdicion) {
                // El creador aún no ha firmado
                mostrarBoton = true;
                console.log("Mostrar botón de firma para Creador (habilitado)");
            }

            // Lógica para el Revisor
            if (esRevisor && fechaEdicion && !fechaRevision) {
                // El revisor puede firmar después de que el creador ha firmado
                mostrarBoton = true;
                console.log("Mostrar botón de firma para Revisor (habilitado)");
            }

            // Lógica para el Aprobador
            if (esAprobador && fechaRevision && !fechaAprobacion) {
                // El aprobador puede firmar después de que el revisor ha firmado
                mostrarBoton = true;
                console.log("Mostrar botón de firma para Aprobador (habilitado)");
            }

            // Mostrar u ocultar el botón según corresponda
            if (mostrarBoton) {
                botonFirma.style.display = 'block';
                botonFirma.disabled = false;
            } else {
                botonFirma.style.display = 'none';
                console.log("No hay acciones de firma disponibles para este usuario.");
            }
        }



        function actualizarEstadoDocumento() {
            console.log("A11")
            var creadorFirmado = $('#fecha_Edicion').text() !== 'Firma Pendiente';
            var revisorFirmado = $('#fechaRevision').text() !== 'Firma Pendiente';
            var aprobadorFirmado = $('#fechaAprobacion').text() !== 'Firma Pendiente';

            var watermarks = document.querySelectorAll('.watermark');

            watermarks.forEach(function (watermark) {
                if (creadorFirmado && revisorFirmado && aprobadorFirmado) {
                    watermark.textContent = 'CONFIDENCIAL';
                    watermark.classList.add('watermark');
                } else {
                    watermark.textContent = 'PENDIENTE DE APROBACIÓN';
                    watermark.classList.add('pendiente-aprobacion');
                }
            });
        }

        window.onload = function () {
            cargarDatosEspecificacion(id);
        };
    </script>

</body>

</html>