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
                        <br>
                        <h1 id="Tipo_Producto" name="Tipo_Producto" style="margin: 0; font-size: 11px; font-weight: normla; color: #000; line-height: 1.2;"></h1>
                        <p name="producto" id="producto" style="margin: 0; font-size: 11px; font-weight: bold; color: #000; margin-top: 5px;"></p>
                        <hr style="width:75%;">
                        <div style="position: relative; font-size: 11px; font-weight: bold; color: #000; margin-top: 2px;">
                            Dirección de Calidad

                        </div>
                    </div>





                    <!-- Información Derecha con Tabla -->
                    <div class="header-right" style="flex: 1; font-size: 0.75em;">
                    <br>
                    <br>
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="border: 1px solid rgb(56, 53, 255); padding: 2px;">Doc. N°:</td>
                                <td name="documento" id="documento" style="border: 1px solid rgb(56, 53, 255); padding: 2px; text-align: center"></td>
                                <td style="border: 1px solid rgb(56, 53, 255); padding: 2px;">Elab. por:</td>
                                <td name="elaboradoPor" id="elaboradoPor" style="border: 1px solid rgb(56, 53, 255); padding: 2px; text-align: center">QF DCA.LSR</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid rgb(56, 53, 255); padding: 2px;">Edición:</td>
                                <td name="fechaEdicion" id="fechaEdicion" style="border: 1px solid rgb(56, 53, 255); padding: 2px; text-align: center"></td>
                                <td style="border: 1px solid rgb(56, 53, 255); padding: 2px;">Rev.Por:</td>
                                <td name="supervisadoPor" id="supervisadoPor" style="border: 1px solid rgb(56, 53, 255); padding: 2px; text-align: center">QF DTS.CPG</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid rgb(56, 53, 255); padding: 2px;">Versión:</td>
                                <td name="version" id="version" style="border: 1px solid rgb(56, 53, 255); padding: 2px; text-align: center"></td>
                                <td style="border: 1px solid rgb(56, 53, 255); padding: 2px;">Aut.Por:</td>
                                <td name="autorizadoPor" id="autorizadoPor" style="border: 1px solid rgb(56, 53, 255); padding: 2px; text-align: center">QF DTL.ISM</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid rgb(56, 53, 255); padding: 2px;">Vigencia:</td>
                                <td name="periodosVigencia" id="periodosVigencia"  style="border: 1px solid rgb(56, 53, 255); padding: 2px; text-align: center"></td>
                                <td style="border: 1px solid rgb(56, 53, 255); padding: 2px;">Página:</td>
                                <td style="border: 1px solid rgb(56, 53, 255); padding: 2px; text-align: center">1 de 2</td>
                            </tr>
                        </table>
                    </div>



                </div>
                <!-- Fila adicional con dos columnas debajo del encabezado existente -->
                <div class="header-bottom" style="display: flex; justify-content: space-between; align-items: flex-start; padding: 0 10px; box-sizing: border-box;">
                        <div class="header-bottom-left" style="flex: 1; background-color: #ffffff; padding: 10px; box-sizing: border-box; text-align: left;">
                            <div class="sub-info" style="font-size: 10px;text-align: left;">

                            Producto de recetario magistral   <br>
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
            
            
            
            
                <div id="content" class="content">
                <!-- Resto del contenido del cuerpo igual al HTML original -->
                <div class="table-section">
                    <div class="analysis-section" style="font-size: 10px; font-weight: bold; margin-top: 20px;">
                        I. Análisis Generales
                    </div>
                    <table id="analisisFQ" class="table table-striped table-bordered" style="width:100%; font-size: 10px">
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
                    <div class="analysis-section" style="font-size: 10px; font-weight: bold; margin-top: 20px;">
                        II. Análisis Microbiológico
                    </div>

                    <!-- Tabla de Análisis Microbiológico -->
                    <table id="analisisMB" class="table table-striped table-bordered" style="width:100%; font-size: 10px">
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
            }

            let analisisMB = especificacion.analisis.filter(a => a.tipo_analisis === 'analisis_MB');
            if (analisisMB.length > 0) {
                mostrarAnalisisMB(analisisMB);
            }
        }
    });

    // Llamada a checkAndSplitContent
    checkAndSplitContent();
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

        // Espera a que la tabla se haya renderizado completamente
        setTimeout(function() {
            verificarYDividirContenidoSiEsNecesario();
        }, 0);
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
                    {   title: 'Análisis', 
                        data: 'descripcion_analisis', 
                        render: function(data, type, row) {
                            return '<strong>' + data + '</strong>';
                        } 
                    },
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

function ajustarContenidoParaEncabezadosYPiesDePagina() {
    // Alturas aproximadas de los encabezados y pies de página en puntos
    const alturaHeader = document.getElementById('header-container').offsetHeight;
    const alturaFooter = document.getElementById('additionalfooter').offsetHeight;

    // Altura máxima disponible para el contenido en una página de formato carta
    const alturaMaximaPagina = 792; // Formato carta en puntos
    const alturaDisponibleParaContenido = alturaMaximaPagina - alturaHeader - alturaFooter;

    // Verifica si el contenido actual excede la altura disponible
    const alturaContenido = document.getElementById('content').offsetHeight;
    if (alturaContenido > alturaDisponibleParaContenido) {
        // Si el contenido excede, divide y crea una nueva página
        dividirContenidoParaNuevaPagina();
    }
}

function dividirContenidoParaNuevaPagina() {
    // Crear una nueva sección para la continuación del contenido
    const nuevaSeccion = document.createElement('div');
    nuevaSeccion.id = 'content_continuation';
    nuevaSeccion.style.pageBreakBefore = 'always'; // Asegura una nueva página en la impresión

    // Aquí debes agregar la lógica para dividir y mover parte del contenido a la nueva sección
    // Por ejemplo, mover filas de una tabla, o dividir el contenido de un elemento grande

    document.body.appendChild(nuevaSeccion);
}



        function checkAndSplitContent() {
    // Alturas para el header, el footer y el margen inferior
    const headerHeight = document.getElementById('header-container').offsetHeight;
    const footerHeight = document.getElementById('additionalfooter').offsetHeight;
    const marginFromBottom = 20; // Margen desde la parte inferior que quieres para el footer
    
    // Altura disponible para el contenido en una página
    const availableHeight = window.innerHeight - headerHeight - footerHeight - marginFromBottom;

    // Elementos de contenido
    const content = document.getElementById('content');
    const additionalContent = document.getElementById('additionalContent');

    // Mide la altura del contenido
    const contentHeight = content.offsetHeight;

    // Comprueba si el contenido cabe en una sola página
    if (contentHeight > availableHeight) {
        // El contenido principal es demasiado grande, prepara para mover a una nueva página
        // Asegúrate de que additionalContent esté oculto
        additionalContent.style.display = 'none';

        // Crear un botón o algún control para permitir al usuario moverse a la página siguiente
        const nextPageButton = document.createElement('button');
        nextPageButton.innerText = 'Siguiente Página';
        nextPageButton.onclick = function () {
            // Al hacer clic, mostrar additionalContent y ocultar el contenido actual
            content.style.display = 'none';
            additionalContent.style.display = 'block';
            nextPageButton.style.display = 'none'; // Ocultar el botón de la siguiente página
        };

        // Añadir el botón al DOM
        document.body.appendChild(nextPageButton);
    } else {
        // Si el contenido cabe en una sola página, asegúrate de que ambos estén visibles
        additionalContent.style.display = 'block';
    }
        }

       




    </script>

</body>

</html>