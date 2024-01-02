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
                        <div class="header-left" >
                            <img src="../assets/images/logo documentos.png" alt="Logo"
                                style="height: 60px;" />
                            <!-- Ajusta el tamaño según sea necesario -->
                            <br>
                        </div>
                        <!-- Título Central -->
                        <div class="header-center"
                            style="flex: 2; text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center; font-family: 'Arial', sans-serif; height: 100%;">
                            <h1 id="Tipo_Producto" name="Tipo_Producto" style="margin: 0; font-size: 11px; font-weight: normal; color: #000; line-height: 1.2;"></h1>
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
                                    <td name="elaboradoPor" id="elaboradoPor" style="border: 1px solid rgb(56, 53, 255); text-align: center">QF DCA.LSR</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid rgb(56, 53, 255);">Edición:</td>
                                    <td name="fechaEdicion" id="fechaEdicion" style="border: 1px solid rgb(56, 53, 255); text-align: center"></td>
                                    <td style="border: 1px solid rgb(56, 53, 255);">Rev.Por:</td>
                                    <td name="supervisadoPor" id="supervisadoPor" style="border: 1px solid rgb(56, 53, 255); text-align: center">QF DTS.CPG</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid rgb(56, 53, 255);">Versión:</td>
                                    <td name="version" id="version" style="border: 1px solid rgb(56, 53, 255); text-align: center"></td>
                                    <td style="border: 1px solid rgb(56, 53, 255);">Aut.Por:</td>
                                    <td name="autorizadoPor" id="autorizadoPor" style="border: 1px solid rgb(56, 53, 255); text-align: center">QF DTL.ISM</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid rgb(56, 53, 255);">Vigencia:</td>
                                    <td name="periodosVigencia" id="periodosVigencia"  style="border: 1px solid rgb(56, 53, 255); text-align: center"></td>
                                    <td style="border: 1px solid rgb(56, 53, 255);">Página:</td>
                                    <td style="border: 1px solid rgb(56, 53, 255); text-align: center">1 de 2</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- Fila adicional con dos columnas debajo del encabezado existente -->
                    <div class="header-bottom" style="display: flex; justify-content: space-between; align-items: flex-start; padding: 0 10px; box-sizing: border-box; font-family: 'Arial', sans-serif">
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
            <div id="contenido_main">
                <h1 id="Tipo_Producto2" name="Tipo_Producto2" style="margin: 0; font-size: 11px; font-weight: bold; color: #000; line-height: 1.2; text-decoration: underline; text-transform: uppercase; text-align: center;"></h1>
                <p name="producto2" id="producto2" style="margin: 0; font-size: 11px; font-weight: bold; color: #000; text-transform: uppercase; text-align: center;"></p>
                <div id="content" class="content">
                    <!-- Resto del contenido del cuerpo igual al HTML original -->
                    <div class="table-section">
                        <div class="analysis-section" style="font-size: 10px; font-weight: bold; margin-top: 5px; padding-left: 50px;">
                            I. Análisis Generales
                        </div>
                        <table id="analisisFQ" class="display compact table-bordered" style="width:100%; font-size: 10px">
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
                <div class="footer" id="footer">
                    <!-- Sección realizada por -->
                    <div class="footer-section">
                        <br>
                        <div class="footer-box-title" style="font-size: 8px">Realizado por:</div>
                        <div class="footer-box">
                            <p id='creadoPor' name='creadoPor' class="bold"></p>
                            <p class="bold">
                                Director de Calidad
                                </p>
                            <div class="signature"></div>
                            <p style='text-align: center;'>Firmado digitalmente</p>
                        </div>
                        <div id='fecha_Edicion' name='fecha_Edicion' class="date" style="font-size: 8px"></div>
                        <br>
                    </div>
                    <!-- Sección revisada por -->
                    <div class="footer-section">
                        <div class="footer-box-title" style="font-size: 8px">Revisado por:</div>
                        <div class="footer-box">
                            <p id='revisadoPor' name='revisadoPor'class="bold"></p>
                            <p class="bold">
                                Jefe de Producción
                                </p>
                            <div class="signature"></div>
                            <p style='text-align: center;' >Firmado digitalmente</p>
                        </div>
                        <div id='fechaRevision' name='fechaRevision' class="date" style="font-size: 8px"></div>
                    </div>
                    <!-- Sección aprobada por -->
                    <div class="footer-section">
                        <div class="footer-box-title" style="font-size: 8px">Aprobado por:</div>
                        <div class="footer-box">
                            <p id='aprobadoPor' name='aprobadoPor' class="bold"></p>
                            <p class="bold">
                                Director Técnico
                                </p>
                            <div class="signature"></div>
                            <p style='text-align: center;' >Firmado digitalmente</p>
                        </div>
                        <div id='fechaAprobacion' name='fechaAprobacion' class="date" style="font-size: 8px"></div>
                    </div>
                </div>
            </div>
        </div>
        <button id="download-pdf">Descargar PDF</button>
        <script>
        function calcularCantidadDePaginas() {
            const alturaMaximaPorPagina = 232; // Altura máxima permitida por página en pt
            const seccionesTabla = document.querySelectorAll('#content .table-section');
            let alturaTotalSecciones = 0;

            // Sumar la altura de todas las secciones de la tabla
            seccionesTabla.forEach(seccion => {
                alturaTotalSecciones += seccion.scrollHeight;
            });

            // Convertir la altura de px a pt
            const alturaTotalSeccionesPt = alturaTotalSecciones / 1.333;

            // Calcular la cantidad de páginas necesarias
            const cantidadDePaginas = Math.ceil(alturaTotalSeccionesPt / alturaMaximaPorPagina);
            console.log(cantidadDePaginas);
            return cantidadDePaginas;
        }

        function actualizarIds(elemento, sufijo) {
    if (elemento.id) {
        elemento.id = `${elemento.id}-${sufijo}`;
    }
    elemento.querySelectorAll('*[id]').forEach((el) => {
        el.id = `${el.id}-${sufijo}`;
    });
}

function dividirContenidoEnPaginas() {
    let cantidadDePaginas = calcularCantidadDePaginas();
    let contenedorOriginal = document.getElementById('form-container');
    let seccionesTabla = Array.from(document.querySelectorAll('#content .table-section'));
    let paginas = [contenedorOriginal];

    // Clonar la página original y preparar las páginas adicionales
    for (let i = 1; i < cantidadDePaginas; i++) {
        let nuevaPagina = contenedorOriginal.cloneNode(true);
        actualizarIds(nuevaPagina, i);
        nuevaPagina.querySelector('#content').innerHTML = '';
        document.body.appendChild(nuevaPagina);
        paginas.push(nuevaPagina);
    }

    let indicePaginaActual = 0;
    seccionesTabla.forEach((seccion) => {
        let alturaSeccion = seccion.scrollHeight / 1.333; // Convertir la altura de px a pt
        let paginaActual = paginas[indicePaginaActual];
        let alturaPaginaActual = paginaActual.scrollHeight / 1.333;

        if (alturaPaginaActual + alturaSeccion > alturaMaximaPorPagina) {
            indicePaginaActual++;
            paginaActual = paginas[indicePaginaActual];
            alturaPaginaActual = paginaActual.scrollHeight / 1.333;
        }

        let clonSeccion = seccion.cloneNode(true);
        actualizarIds(clonSeccion, indicePaginaActual);
        paginaActual.querySelector('#content').appendChild(clonSeccion);
        seccion.remove(); // Remover la sección del contenedor original
    });
}

    function clonarConEstilos(elemento) {
        let clon = elemento.cloneNode(true);
        clon.style.cssText = elemento.style.cssText; // Copia estilos en línea
        return clon;
    }
    function cargarDatosEspecificacion(id) {
        $.ajax({
            url: './backend/calidad/documento_especificacion_productoBE.php',
            type: 'GET',
            data: { id: id },
            success: function (response) {
                procesarDatosEspecificacion(response);

            },
            error: function (xhr, status, error) {
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
        response.productos.forEach(function (producto) {
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
        dividirContenidoEnPaginas();
    }
    function poblarYDeshabilitarCamposProducto(producto) {
        if (producto) {
            // Usando .text() para elementos h2, h3 y p
            $('#Tipo_Producto').text('Especificación ' + producto.tipo_producto);
            $('#producto').text(producto.nombre_producto);
            $('#Tipo_Producto2').text('Especificación ' + producto.tipo_producto);
            $('#producto2').text(producto.nombre_producto);
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
                        { 
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
        // Verifica si hay datos para el análisis microbiológico
        if (analisis.length > 0) {
            // Si hay datos, muestra la tabla y procesa los datos
            if ($.fn.DataTable.isDataTable('#analisisMB')) {
                $('#analisisMB').DataTable().clear().rows.add(analisis).draw();
            } else {
                $('#analisisMB').DataTable({
                    data: analisis,
                    columns: [
                    { 
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
    window.onload = function () {
        // Suponiendo que tengas un ID de producto para cargar
        cargarDatosEspecificacion(id);
    };
        </script>

    </body>

</html>