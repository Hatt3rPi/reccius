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
                        <img src="../assets/images/logo documentos.png" alt="Logo" style="height: 60px;" />
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
                                <td name="elaboradoPor" id="elaboradoPor" style="border: 1px solid rgb(56, 53, 255); text-align: center"></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid rgb(56, 53, 255);">Edición:</td>
                                <td name="fechaEdicion" id="fechaEdicion" style="border: 1px solid rgb(56, 53, 255); text-align: center"></td>
                                <td style="border: 1px solid rgb(56, 53, 255);">Rev.Por:</td>
                                <td name="supervisadoPor" id="supervisadoPor" style="border: 1px solid rgb(56, 53, 255); text-align: center"></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid rgb(56, 53, 255);">Versión:</td>
                                <td name="version" id="version" style="border: 1px solid rgb(56, 53, 255); text-align: center"></td>
                                <td style="border: 1px solid rgb(56, 53, 255);">Aut.Por:</td>
                                <td name="autorizadoPor" id="autorizadoPor" style="border: 1px solid rgb(56, 53, 255); text-align: center"></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid rgb(56, 53, 255);">Vigencia:</td>
                                <td name="periodosVigencia" id="periodosVigencia" style="border: 1px solid rgb(56, 53, 255); text-align: center"></td>
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

            <div class="watermark">TESTEO TESTESO</div>
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
                        <p id="creadoPor" name="creadoPor" class="bold"></p>
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
                        <p id="revisadoPor" name="revisadoPor" class="bold"></p>
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
                        <p id="aprobadoPor" name="aprobadoPor" class="bold"></p>
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
        var Wcontent = 2;
        var usuarioNombre = "<?php echo $_SESSION['nombre']; ?>";;
        var usuario = "<?php echo $_SESSION['usuario']; ?>";
        document.getElementById('download-pdf').addEventListener('click', async function() {
            obtenerAlturaElementosYCalcularEspacioDisponible();
            ocultarContenedorPrincipal();
            actualizarContadorPaginas();
            $.notify("Creando PDF", "warn");

            // Introducir un pequeño retraso para asegurar que el DOM se ha actualizado completamente
            await new Promise(resolve => setTimeout(resolve, 500)); // Espera 500 milisegundos

            var pdf = new jspdf.jsPDF({
                orientation: 'portrait',
                unit: 'pt',
                format: 'letter'
            });

            // Seleccionar todos los contenedores que deben incluirse en el PDF
            var containers = document.querySelectorAll('.document-cloned-container');

            for (let i = 0; i < containers.length; i++) {
                if (i > 0) {
                    pdf.addPage();
                }

                await html2canvas(containers[i], {
                    scale: 1,
                    backgroundColor: 'white' // Asegurar que el fondo sea blanco
                }).then(canvas => {
                    var imgData = canvas.toDataURL('image/png');
                    var pdfWidth = pdf.internal.pageSize.getWidth();
                    var pdfHeight = pdf.internal.pageSize.getHeight();

                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                });
            }

            var nombreProducto = document.getElementById('producto').textContent.trim();
            var nombreDocumento = document.getElementById('documento').textContent.trim();
            pdf.save(`${nombreDocumento} ${nombreProducto}.pdf`);
            $.notify("PDF generado con éxito", "success");
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
                        // Si no hay datos, oculta la sección del análisis FQ
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
                    $('#revisadoPor').text(especificacion.revisado_por.nombre || 'No disponible');
                    $('#aprobadoPor').text(especificacion.aprobado_por.nombre || 'No disponible');

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
                Wcontent = 2;
            } else if (esAprobadorPendiente && !revisorHaFirmado) {
                console.log("Mostrar botón de firma (deshabilitado)");
                document.getElementById('sign-document').style.display = 'block';
                document.getElementById('sign-document').disabled = true;
                document.getElementById('sign-document').title = "Documento debe estar firmado por revisor para poder aprobarlo";
                Wcontent = 2;
            } else {
                console.log("No mostrar botón de firma");
                document.getElementById('sign-document').style.display = 'none';

            }
            if (esAprobador && especificacion.revisado_por.fecha_revision === null) {
                console.log("Usuario es revisor y la firma del aprobador está pendiente.");
                $.notify("Es necesario que el usuario Revisor firme antes que tú.", "warn");
                // Aquí puedes realizar acciones adicionales basadas en esta condición.
            }
        }




        function actualizarEstadoDocumento() {
            console.log("A11")
            var creadorFirmado = $('#fecha_Edicion').text() !== 'Firma Pendiente';
            var revisorFirmado = $('#fechaRevision').text() !== 'Firma Pendiente';
            var aprobadorFirmado = $('#fechaAprobacion').text() !== 'Firma Pendiente';

            var watermarks = document.querySelectorAll('.watermark'); // Cambio aquí: usar clase en lugar de id

            watermarks.forEach(function(watermark) { // Aplicar a todos los elementos encontrados
                if (creadorFirmado && revisorFirmado && aprobadorFirmado) {
                    watermark.textContent = 'CONFIDENCIAL';
                    watermark.classList.add('watermark'); // Asegúrate de que la clase 'pendiente-aprobacion' exista en tus estilos CSSrmar
                    Wcontent = 1;
                } else {
                    watermark.textContent = 'PENDIENTE DE APROBACIÓN';
                    watermark.classList.add('pendiente-aprobacion'); // Asegúrate de que la clase 'pendiente-aprobacion' exista en tus estilos CSS

                }
            });
        }


        function obtenerAlturaElementosYCalcularEspacioDisponible() {
            console.log("A12")
            const alturaTotal = 792; // Altura total de la página en puntos
            const alturaHeader = 123; // Altura del encabezado
            const alturaFooter = 224; // Altura del pie de página

            // Funciones auxiliares dentro de la función
            const $ = (selector) => document.querySelector(selector);
            const $$ = (selector) => document.querySelectorAll(selector);

            // Contenedores
            const contentContainer = $('#content');
            const additionalContentContainer = $('#additionalContent');

            // Aquí puedes incluir cualquier lógica adicional de cálculo de altura si es necesario

            // Seleccionar los elementos tr dentro de la tabla específica de #content y #additionalContent
            const trsDeContent = Array.from(document.querySelectorAll("#content table tbody tr"));
            const trsDeAdditionalContent = Array.from(document.querySelectorAll("#additionalContent table tbody tr"));
            newTabla("new-table", trsDeContent, trsDeAdditionalContent);
        }


        async function newTabla(id, trsContent, trsAdditionalContent) {
            console.log("A13")
            let tableContainer = createTableContainer();
            let alturaTotalDisponible = 700; // Altura disponible antes de necesitar un nuevo contenedor.

            // Crea contenedores iniciales para 'content' y 'additionalContent'.
            let newTbodyContent = createTableBody(id + "-content", tableContainer, "content");
            let alturaActualTabla = 0; // Altura acumulada actual en el contenedor.

            // Procesa trs de 'content'.
            for (let tr of trsContent) {
                [alturaActualTabla, tableContainer, newTbodyContent] = await procesarTr(
                    tr, alturaTotalDisponible, newTbodyContent, tableContainer, alturaActualTabla, id, "content"
                );
            }

            // Asegura que 'additionalContent' comience en el contenedor actual si hay espacio, o en un nuevo contenedor si es necesario.
            let newTbodyAdditionalContent = createTableBody(id + "-additionalContent", tableContainer, "additionalContent");
            for (let tr of trsAdditionalContent) {
                [alturaActualTabla, tableContainer, newTbodyAdditionalContent] = await procesarTr(
                    tr, alturaTotalDisponible, newTbodyAdditionalContent, tableContainer, alturaActualTabla, id, "additionalContent"
                );
            }

            // Asegura agregar el último contenedor al documento.
            document.querySelector("#form-container").appendChild(tableContainer);
        }






        async function procesarTr(tr, alturaTotalDisponible, newTbody, tableContainer, alturaActualTabla, id, sectionId) {
            console.log("A14")
            tr.querySelectorAll("td").forEach(td => {
                td.style.fontSize = "10px"; // Ajusta el tamaño de la fuente
                td.style.borderLeft = "none"; // Elimina el borde izquierdo
                td.style.borderRight = "none"; // Elimina el borde derecho
            });
            let alturaTr = tr.getBoundingClientRect().height; // Asume que esta altura se puede determinar así, lo cual puede no ser siempre preciso en un entorno no renderizado.

            if (alturaActualTabla + alturaTr > alturaTotalDisponible) {
                // Si agregar esta fila excede el espacio disponible, primero agregar el contenedor actual al DOM.
                document.querySelector("#form-container").appendChild(tableContainer);

                // Crea un nuevo contenedor y tbody para continuar.
                tableContainer = createTableContainer();
                newTbody = createTableBody(id + "-" + sectionId, tableContainer, sectionId);
                alturaActualTabla = 0; // Restablece la altura acumulada para el nuevo contenedor.
            }

            // Añade la fila al tbody actual y actualiza la altura acumulada.
            newTbody.appendChild(tr);
            alturaActualTabla += alturaTr;

            return [alturaActualTabla, tableContainer, newTbody];
        }


        function createTableContainer() {
            console.log("A15")
            // Incrementar el contador de páginas totales cada vez que se crea un nuevo contenedor

            const container = document.createElement("div");
            container.className = "document-cloned-container"; // Asigna la clase común a cada contenedor

            // Estilos y configuraciones para el contenedor
            container.style.height = "792pt";
            container.style.width = "722pt"
            container.style.padding = "10pt";
            container.style.boxSizing = "border-box";
            container.style.border = "1px solid rgb(0, 0, 0)";
            container.style.boxShadow = "0 4px 8px rgba(0, 0, 0, 0.1)";
            container.style.marginLeft = "-520px";
            container.style.marginRight = "-50px";
            container.style.position = "relative"; // Asegura que los elementos absolutamente posicionados dentro de este contenedor se posicionen respecto a este contenedor
            container.style.marginTop = "30px";
            container.style.fontFamily = "Verdana, Geneva, Tahoma, sans-serif";
            container.style.paddingBottom = "300px"; // Ajusta este valor según la altura de tu footer-containerDIV para asegurarte de que haya suficiente espacio


            // Clonar y añadir elementos de encabezado y pie de página
            const headerClone = document.querySelector("#header-container").cloneNode(true);
            container.appendChild(headerClone);


            // Clonar y añadir elementos específicos antes de añadir el contenedor de tablas
            const h1Clone = document.querySelector("#Tipo_Producto2").cloneNode(true);
            const pClone = document.querySelector("#producto2").cloneNode(true);

            // Asegúrate de actualizar los IDs si es necesario para evitar IDs duplicados en el DOM
            h1Clone.id = "Tipo_Producto2_" + new Date().getTime(); // Ejemplo para generar un ID único
            pClone.id = "producto2_" + new Date().getTime(); // Ejemplo para generar un ID único

            // Añadir los elementos clonados al contenedor
            container.appendChild(h1Clone);
            container.appendChild(pClone);


            // Añadir la marca de agua
            const watermark = document.createElement("div");
            watermark.setAttribute("class", "watermark");
            console.log(Wcontent);
            if (Wcontent === 1) {
                watermark.textContent = 'CONFIDENCIAL';
                watermark.classList.add('watermark'); // Asegúrate de que la clase 'pendiente-aprobacion' exista en tus estilos CSSrmar
            } else {
                watermark.textContent = 'PENDIENTE DE APROBACIÓN';
                watermark.classList.add('pendiente-aprobacion'); // Asegúrate de que la clase 'pendiente-aprobacion' exista en tus estilos CSSrmar
            }
            container.appendChild(watermark);




            const footerClone = document.querySelector("#footer").cloneNode(true);
            footerClone.style.marginTop = "5px"; // Reduce el margen superior
            container.appendChild(footerClone); // El pie de página se añade al final después de 'maintablas'



            return container; // Devuelve el contenedor principal con todo dentro
        }

        // Esta función actualiza el contador de páginas para el contenedor actual.
        function actualizarContadorPaginas() {
            console.log("A16")
            // Seleccionar todos los contenedores clonados y actualizar sus contadores
            const contenedores = document.querySelectorAll(".pagina-numero");
            const cantidadPaginas = contenedores.length

            contenedores.forEach((contenedor, index) => {
                // Encuentra el elemento del número de página dentro de cada contenedor
                contenedor.innerHTML = `${index} de ${cantidadPaginas - 1}`;
            });

            console.log("documento clonado ");
        }

        function createAnalysisSection(title) {
            console.log("A17")
            const analysisSection = document.createElement("div");
            analysisSection.className = "analysis-section";
            analysisSection.style.cssText = "font-size: 10px; font-weight: bold; margin-top: 5px; padding-left: 50px;";
            analysisSection.textContent = title;
            return analysisSection;
        }

        function createTableBody(id, container, sectionId) {
            console.log("A18")
            const newTable = document.createElement("table");
            newTable.setAttribute("id", id);
            newTable.classList.add("table", "table-bordered");

            // Crear un div con texto basado en la sección antes del thead
            const analysisTitleDiv = document.createElement("div");
            analysisTitleDiv.className = "analysis-title"; // Asegúrate de definir este estilo en tu CSS
            analysisTitleDiv.style.cssText = "font-size: 10px; font-weight: bold; margin-top: 5px; padding-left: 50px;";
            if (sectionId === "content") {
                analysisTitleDiv.textContent = "I. Análisis Generales";
            } else if (sectionId === "additionalContent") {
                analysisTitleDiv.textContent = "II. Análisis Microbiológico";
            }
            // Inserta el div antes de la tabla en el contenedor
            container.appendChild(analysisTitleDiv);

            // Continúa con la creación del thead y tbody como antes
            const newThead = document.createElement("thead");
            newThead.style.fontSize = "10px";
            newThead.style.borderLeft = "none";
            newThead.style.borderRight = "none";

            newTable.appendChild(newThead);

            const tr = document.createElement("tr");
            tr.style.fontSize = "10px";
            newThead.style.borderLeft = "none";
            newThead.style.borderRight = "none";
            newThead.appendChild(tr);

            if (sectionId === "content") {
                // Define los encabezados específicos para la tabla de 'content'
                const headers = ["Análisis", "Metodología", "Criterio de Aceptación"];
                headers.forEach(text => {
                    const th = document.createElement("th");
                    th.textContent = text;
                    tr.appendChild(th);
                });
            } else if (sectionId === "additionalContent") {
                // Define los encabezados específicos para la tabla de 'additionalContent'
                const headers = ["Análisis", "Metodología", "Resultado"];
                headers.forEach(text => {
                    const th = document.createElement("th");
                    th.textContent = text;
                    tr.appendChild(th);
                });
            }

            // Crear tbody y aplicar estilo a los <td> cuando se agreguen
            const newTbody = document.createElement("tbody");
            newTbody.style.fontSize = "10px";
            newTable.appendChild(newTbody);

            // Ubicar el newTable en el div correspondiente después del div del título
            container.appendChild(newTable);

            return newTbody;
        }




        function ocultarContenedorPrincipal() {
            console.log("A19")
            var contenedorPrincipal = document.getElementById('Maincontainer');
            var contenedorForm = document.getElementById('form-container');
            contenedorPrincipal.style.display = 'none';
            contenedorForm.style.border = 'none';
            contenedorForm.style.boxShadow = 'none';
        }

        window.onload = function() {
            cargarDatosEspecificacion(id);
            verificarYMostrarBotonFirma();

        };
    </script>
</body>

</html>