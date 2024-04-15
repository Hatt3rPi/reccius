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
    <link rel="stylesheet" href="../assets/css/DocumentoActa.css">

</head>

<body>
    <div id="form-container" class="form-container">
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
                    <p name="pretitulo" id="pretitulo" style="margin: 0; font-size: 11px; font-weight: bold; color: #000;">Acta de Muestreo Control
                        de
                        Calidad
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
                            <td>N° Acta:</td>
                            <td name="nro_acta" id="nro_acta"></td>
                        </tr>
                        <tr>
                            <td>Fecha Muestreo:</td>
                            <td>
                                <input type="date" id="fecha_muestreo" name="fecha_muestreo" style="border: 0px;" readonly>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
            <!-- Body -->
            <br>
            <h2 class="Subtitulos">I. IDENTIFICACIÓN DE LA MUESTRA</h2>

            <!-- Sección I: Identificación de la Muestra -->
            <section id="sample-identification" style="display: flex; justify-content: space-between; align-items: stretch; gap: 5px;">
                <!-- Tabla de identificación de la muestra -->
                <table id="identificacion_muestra" name="identificacion_muestra">
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th id="revision_actor1">Revisión Responsable</th>
                        <th></th>
                        <th id="revision_actor2">Revisión Verificador</th>
                        <th></th>
                        <th>Rótulo General de Muestra</th>
                    </tr>
                    <tr>
                        <td class="formulario-titulo">1. Producto:</td>
                        <td class="formulario" id="form_producto" contenteditable="true">id="form_producto" </td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-horizontal " role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="verificadores btn-check " name="identResp1" id="identResp1a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identResp1a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identResp1" id="identResp1b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identResp1b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-horizontal" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identVB1" id="identVB1a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identVB1a"><i class="fa-regular fa-circle-check"></i> Cumple</i></label>
                                <input type="radio" class="btn-check verificadores" name="identVB1" id="identVB1b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identVB1b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</i></label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario" rowspan="9">
                            <label>Pegar etiqueta de identificación general de la muestra</label>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo">2. Tipo Producto:</td>
                        <td class="formulario" id="form_tipo" contenteditable="true">id="form_tipo"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identResp2" id="identResp2a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identResp2a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identResp2" id="identResp2b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identResp2b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identVB2" id="identVB2a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identVB2a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identVB2" id="identVB2b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identVB2b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo">3. Lote:</td>
                        <td class="formulario" id="form_lote" contenteditable="true">id="form_lote"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identResp3" id="identResp3a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identResp3a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identResp3" id="identResp3b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identResp3b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identVB3" id="identVB3a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identVB3a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identVB3" id="identVB3b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identVB3b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo">4. Tamaño de Lote:</td>
                        <td class="formulario" id="form_tamano_lote" contenteditable="true">id="form_tamano_lote"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identResp4" id="identResp4a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identResp4a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identResp4" id="identResp4b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identResp4b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identVB4" id="identVB4a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identVB4a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identVB4" id="identVB4b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identVB4b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo">5. Código Interno:</td>
                        <td class="formulario" id="form_codigo_mastersoft" contenteditable="true">id="form_codigo_mastersoft"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identResp5" id="identResp5a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identResp5a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identResp5" id="identResp5b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identResp5b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identVB5" id="identVB5a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identVB5a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identVB5" id="identVB5b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identVB5b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo">6. Cond. Almacenamiento:</td>
                        <td class="formulario" id="form_condAlmacenamiento" contenteditable="true">id="form_condAlmacenamiento"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identResp6" id="identResp6a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identResp6a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identResp6" id="identResp6b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identResp6b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identVB6" id="identVB6a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identVB6a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identVB6" id="identVB6b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identVB6b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo">7. Cantidad Muestra:</td>
                        <td class="formulario" id="form_cant_muestra" contenteditable="true">id="form_cant_muestra"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identResp7" id="identResp7a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identResp7a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identResp7" id="identResp7b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identResp7b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identVB7" id="identVB7a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identVB7a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identVB7" id="identVB7b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identVB7b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo">8. Cantidad Contramuestra:</td>
                        <td class="formulario" id="form_cant_contramuestra" contenteditable="true">id="form_cant_contramuestra"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identResp8" id="identResp8a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identResp8a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identResp8" id="identResp8b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identResp8b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identVB8" id="identVB8a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identVB8a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identVB8" id="identVB8b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identVB8b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo">9. Tipo de Análisis:</td>
                        <td class="formulario" id="form_tipo_analisis" contenteditable="true">id="form_tipo_analisis"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identResp9" id="identResp9a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identResp9a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identResp9" id="identResp9b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identResp9b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identVB9" id="identVB9a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identVB9a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identVB9" id="identVB9b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identVB9b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>
                </table>
            </section>
            <br>
            <br>
            <h2 class="Subtitulos">II. MUESTREO</h2>
            <!-- Sección II: MUESTREO -->
            <section id="sample-identification" style="display: flex; justify-content: space-between; align-items: stretch; gap: 5px;">
                <!-- Tabla de identificación de la muestra -->
                <table id="muestreo" name="muestreo">
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th id="revision_actor1">Revisión Responsable</th>
                        <th></th>
                        <th id="revision_actor2">Revisión Verificador</th>
                        <th></th>
                        <th>Rótulo General de Muestra</th>
                    </tr>

                    <tr>
                        <td>1. La zona de esterilización se encuentra
                            limpia y ordenada.</td>
                        <td id="form_1">
                            <!-- CheckBoxes para Conforme y No Conforme -->
                            <input type="checkbox" name="estado_Pro" value="Conforme">
                            <label for="conforme_Pro">Conforme</label>
                            <input type="checkbox" name="estado_Pro" value="No Conforme">
                            <label for="noConforme_Pro">No Conforme</label>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="muestreoResp1" id="muestreoResp1a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoResp1a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="muestreoResp1" id="muestreoResp1b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoResp1b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="muestreoVB1" id="muestreoVB1a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoVB1a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="muestreoVB1" id="muestreoVB1b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoVB1b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario" rowspan="9">
                            <label>Pegar etiqueta de identificación general de la muestra</label>
                        </td>
                    </tr>

                    <tr>
                        <td>2. Verificar que la zona de muestreo se
                            encuentre libre de otros productos.</td>
                        <td id="form_2">
                            <!-- CheckBoxes para Conforme y No Conforme -->
                            <input type="checkbox" name="estado_Pro" value="Conforme">
                            <label for="conforme_Pro">Conforme</label>
                            <input type="checkbox" name="estado_Pro" value="No Conforme">
                            <label for="noConforme_Pro">No Conforme</label>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="muestreoResp2" id="muestreoResp2a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoResp2a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="muestreoResp2" id="muestreoResp2b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoResp2b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="muestreoVB2" id="muestreoVB2a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoVB2a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="muestreoVB2" id="muestreoVB2b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoVB2b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>3. Evaluar el aspecto del producto en zona
                            de revisión.</td>
                        <td id="form_3">
                            <!-- CheckBoxes para Conforme y No Conforme -->
                            <input type="checkbox" name="estado_Pro" value="Conforme">
                            <label for="conforme_Pro">Conforme</label>
                            <input type="checkbox" name="estado_Pro" value="No Conforme">
                            <label for="noConforme_Pro">No Conforme</label>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="muestreoResp3" id="muestreoResp3a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoResp3a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="muestreoResp3" id="muestreoResp3b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoResp3b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="muestreoVB3" id="muestreoVB3a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoVB3a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="muestreoVB3" id="muestreoVB3b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoVB3b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>4. Verificar correcta identificación del lote y producto.</td>
                        <td id="form_4">
                            <!-- CheckBoxes para Conforme y No Conforme -->
                            <input type="checkbox" name="estado_Pro" value="Conforme">
                            <label for="conforme_Pro">Conforme</label>
                            <input type="checkbox" name="estado_Pro" value="No Conforme">
                            <label for="noConforme_Pro">No Conforme</label>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="muestreoResp4" id="muestreoResp4a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoResp4a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="muestreoResp4" id="muestreoResp4b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoResp4b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="muestreoVB4" id="muestreoVB4a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoVB4a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="muestreoVB4" id="muestreoVB4b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoVB4b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>5. Cantidad de ciclos de esterilización</td>
                        <td class="formulario">
                            <textarea id="form_textarea5"></textarea>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="muestreoResp5" id="muestreoResp5a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoResp5a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="muestreoResp5" id="muestreoResp5b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoResp5b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="muestreoVB5" id="muestreoVB5a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoVB5a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="muestreoVB5" id="muestreoVB5b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoVB5b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>6. Cantidad bandejas esterilizadas por ciclo</td>
                        <td class="formulario">
                            <textarea id="form_textarea6"></textarea>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="muestreoResp6" id="muestreoResp6a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoResp6a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="muestreoResp6" id="muestreoResp6b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoResp6b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="muestreoVB6" id="muestreoVB6a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoVB6a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="muestreoVB6" id="muestreoVB6b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoVB6b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>7. Cantidad de muestras por bandeja</td>
                        <td class="formulario">
                            <textarea id="form_textarea7"></textarea>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="muestreoResp7" id="muestreoResp7a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoResp7a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="muestreoResp7" id="muestreoResp7b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoResp7b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="muestreoVB7" id="muestreoVB7a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoVB7a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="muestreoVB7" id="muestreoVB7b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoVB7b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>
                </table>
            </section>
            <div style="margin-top: 10px; font-size: 12px;">
                <br>
                <label for="form_Inusual">8. Registrar cualquier situación inesperada o inusual
                    durante el proceso:</label>
                <textarea id="form_textarea8" name="form_textarea8" rows="3" style="width: 99%;margin-left: 1%;"></textarea>


            </div>
            <!-- Sección III: Plan de Muestreo -->
            <br>
            <section id="sampling-plan">
                <h2 class="Subtitulos">III. PLAN DE MUESTREO</h2>

                <table id="seccion3" style="width:100%; border-collapse: collapse;">
                    <!-- Encabezados de la tabla -->
                    <tr>
                        <th>Tamaño de Lote</th>
                        <th>Muestra</th>
                        <th>Contramuestra</th>
                        <th>Total</th>
                        <th id="revision_actor1">Revisión Responsable</th>
                        <th id="revision_actor2">Revisión Verificador</th>
                    </tr>
                    <!-- Fila para lotes de <= 500 unidades -->
                    <tr style=" border-bottom: 1px solid #000;border-left: 1px solid;border-right: 1px solid;">
                        <td contenteditable="true">&le; 500 unidades</td>
                        <td contenteditable="true">40 unidades</td>
                        <td contenteditable="true">80 unidades</td>
                        <td contenteditable="true">120 Unidades</td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="planResp1" id="planResp1a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="planResp1a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check" name="planResp1" id="planResp1b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="planResp1b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>

                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="planVB1" id="planVB1a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="planVB1a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check" name="planVB1" id="planVB1b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="planVB1b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>
                    <!-- Fila para lotes entre 501 y 4999 unidades -->
                    <tr style=" border-bottom: 1px solid #000;border-left: 1px solid;border-right: 1px solid;">
                        <td contenteditable="true">501 - 4999 unidades</td>
                        <td contenteditable="true">40 unidades</td>
                        <td contenteditable="true">80 unidades</td>
                        <td contenteditable="true">420 Unidades</td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="planResp2" id="planResp2a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="planResp2a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check" name="planResp2" id="planResp2b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="planResp2b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>

                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="planVB2" id="planVB2a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="planVB2a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check" name="planVB2" id="planVB2b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="planVB2b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>
                    <!-- Fila para lotes de >= 1000 unidades -->
                    <tr style=" border-bottom: 1px solid #000;border-left: 1px solid;border-right: 1px solid;">
                        <td contenteditable="true">&ge; 1000 unidades</td>
                        <td contenteditable="true">50 unidades</td>
                        <td contenteditable="true">100 unidades</td>
                        <td contenteditable="true">150 Unidades</td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="planResp3" id="planResp3a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="planResp3a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check" name="planResp3" id="planResp3b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="planResp3b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>

                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="planVB3" id="planVB3a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="planVB3a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" class="btn-check" name="planVB3" id="planVB3b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="planVB3b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>
                </table>
            </section>

            <!-- Footer -->
            <br>
            <div class="form-row" id="firma">
                <!-- Sección realizada por -->
                <div class="firma-section">

                    <div class="firma-box-title" style="font-size: 10px; text-align: left;">Realizado por:</div>
                    <div class="firma-box">
                        <p id='realizadoPor' name='realizadoPor' class="bold"></p>
                        <p id='cargo_realizador' name='cargo_realizador' class="bold">
                        <div class="signature" id="firma_realizador" name="firma_realizador">
                            <!-- acá debe ir el QR -->
                        </div>
                        <p id='mensaje_realizador' name='mensaje_realizador' style='text-align: center;display: none'>Firmado
                            digitalmente</p>
                    </div>
                    <div id='fecha_Edicion' name='fecha_Edicion' class="date" style="font-size: 8px"></div>
                    <br>
                </div>
                <!-- Sección realizada por -->
                <div class="firma-section">

                    <div class="firma-box-title" style="font-size: 10px; text-align: left;">Responsable:</div>
                    <div class="firma-box">
                        <p id='responsable' name='responsable' class="bold"></p>
                        <p id='cargo_responsable' name='cargo_responsable' class="bold">
                        <div class="signature" id="firma_responsable" name="firma_responsable">
                            <!-- acá debe ir el QR -->
                        </div>
                        <p id='mensaje_realizador' name='mensaje_realizador' style='text-align: center;display: none'>Firmado
                            digitalmente</p>
                    </div>
                    <div id='fecha_firma_responsable' name='fecha_firma_responsable' class="date" style="font-size: 8px"></div>
                    <br>
                </div>
                <!-- Sección aprobada por -->
                <div class="firma-section">
                    <div class="firma-box-title" style="font-size: 10px; text-align: left;">Verificado por:</div>
                    <div class="firma-box">
                        <p id='verificadoPor' name='verificadoPor' class="bold"></p>
                        <p id='cargo_verificador' name='cargo_verificador' class="bold">
                        <div class="signature" id="firma_verificador" name="firma_verificador">
                            <!-- acá debe ir el QR -->
                        </div>
                        <p id='mensaje_verificador' name='mensaje_verificador' style='text-align: center;display: none'>
                            Firmado digitalmente</p>
                    </div>
                    <div id='fecha_firma_verificador' name='fecha_firma_verificador' class="date" style="font-size: 8px"></div>
                </div>
            </div>
            <footer style="width: 100%; text-align: center; margin-top: 20px;bottom: 0;">
                <!-- Nota de confidencialidad -->
                <p style="margin-top: 10px;font-size: 10px;padding-bottom: 10px;">
                    La información contenida en esta acta es de carácter CONFIDENCIAL y es considerada SECRETO
                    INDUSTRIAL.
                </p>
            </footer>



        </div>


</body>
<div class="button-container">
    <button class="botones" id="guardar">Guardar</button>
    <button class="botones" id="firmar">Firmar</button>
    <button class="botones" id="download-pdf">Descargar PDF</button>
</div>
<div id="notification" class="notification-container notify" style="display: none;">
    <p id="notification-message">Este es un mensaje de notificación.</p>
</div>

</html>
<script>
    document.getElementById('download-pdf').addEventListener('click', function() {


        // Ocultar botones no seleccionados en todos los grupos, tanto horizontales como verticales
        const allButtonGroups = document.querySelectorAll('.btn-group-horizontal, .btn-group-vertical');

        allButtonGroups.forEach(group => {
            const buttons = group.querySelectorAll('.btn-check');
            buttons.forEach(button => {
                // Si el botón no está chequeado, ocultar el label asociado
                if (!button.checked) {
                    button.nextElementSibling.style.display = 'none';
                }
            });
        });

        // Continúa con el proceso de descarga del PDF como antes
        document.querySelector('.button-container').style.display = 'none';
        const elementToExport = document.getElementById('form-container');
        elementToExport.style.border = 'none'; // Establecer el borde a none
        elementToExport.style.boxShadow = 'none'; // Establecer el borde a none
        // Guardar los estilos actuales para restablecerlos después
        const oldBorderStyle = elementToExport.style.border;
        const oldBoxShadowStyle = elementToExport.style.boxShadow;

        html2canvas(elementToExport, {
            scale: 2
        }).then(canvas => {
            // Mostrar botones después de la captura
            document.querySelector('.button-container').style.display = 'block';
            // Restablecer los estilos originales después de generar el PDF
            elementToExport.style.border = oldBorderStyle;
            elementToExport.style.boxShadow = oldBoxShadowStyle;

            // Restablecer la visibilidad de todos los botones después de generar el PDF
            allButtonGroups.forEach(group => {
                const buttons = group.querySelectorAll('.btn-check');
                buttons.forEach(button => {
                    // Solo mostrar los labels de los botones seleccionados
                    if (button.checked) {
                        button.style.display = 'block';
                    }
                });
            });

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
    document.getElementById('guardar').addEventListener('click', function() {
        const idDocumento = document.getElementById('nro_registro').textContent; // Assuming the ID is in the textContent of an element
        const radioButtons = document.querySelectorAll("input[type='radio']");
        let dataToSave = [];

        radioButtons.forEach(radio => {
            if (radio.checked) { // Only add to the array if the radio button is selected
                let radioInfo = {
                    id_documento: idDocumento,
                    name: radio.name,
                    value: radio.value,
                    checked: radio.checked
                };
                dataToSave.push(radioInfo);
            }
        });

        // // Now `dataToSave` is defined and we can make the AJAX call
        $.ajax({
            url: './backend/acta_muestreo/save_selections.php', // Ensure this endpoint is configured on your server
            type: 'POST',
            data: {
                selections: dataToSave
            },
            success: function(response) {
                // Handle the server response here
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error("Error saving: ", status, error);
            }
        });
    });
</script>
<script>
    var TESTMODO = 3;


    // Definir la función verificarBotonesSeleccionados
    function verificarBotonesSeleccionados() {
        let todosSeleccionados = true;
        const formRespElements = document.querySelectorAll("td.formulario.resp");
        const formVerifElements = document.querySelectorAll("td.formulario.verif");

        // Combina los dos NodeList en un solo array
        const allElements = [...formRespElements, ...formVerifElements];

        allElements.forEach(element => {
            const radioButtons = element.querySelectorAll("input[type='radio']");
            const algunoSeleccionado = Array.from(radioButtons).some(radio => radio.checked);

            if (!algunoSeleccionado) {
                todosSeleccionados = false;
            }
        });

        // Ocultar o mostrar el botón de descargar PDF según si todos los botones están seleccionados
        const botonDescargarPDF = document.getElementById('download-pdf');
        botonDescargarPDF.style.display = todosSeleccionados ? 'block' : 'none';
    }

    // Ejecutar la verificación inicial y luego cada vez que cambie el estado de un botón de radio
    document.querySelectorAll("input[type='radio']").forEach(radio => {
        radio.addEventListener('change', verificarBotonesSeleccionados);
    });

    // Verificación inicial
    verificarBotonesSeleccionados();

    //ofuncion para ocultar contenido de botones al no ser de la id 



    if (TESTMODO === 1) {
        // Deshabilitar solo los botones dentro de la columna de revisión verificador
        const verificadores = document.querySelectorAll('.formulario.verif .btn-check');

        verificadores.forEach(function(button) {
            button.disabled = true;
        });
    } else if (TESTMODO === 2) {
        // Deshabilitar solo los botones dentro de la columna de revisión verificador
        const verificadores = document.querySelectorAll('.formulario.resp .btn-check');

        verificadores.forEach(function(button) {
            button.disabled = true;
        });

    }

    function cargarDatosEspecificacion(id, resultados, etapa) {
        console.log(id, resultados, etapa);
        if (resultados) {
            $.ajax({
                url: './backend/acta_muestreo/ingresa_resultados.php',
                type: 'GET',
                data: {
                    id_acta: id
                },
                success: function(response) {
                    procesarDatosActa(response, resultados, etapa);
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud: ", status, error);
                }
            });
        } else {
            $.ajax({
                url: './backend/acta_muestreo/genera_acta.php',
                type: 'GET',
                data: {
                    id_analisis_externo: id
                },
                success: function(response) {
                    procesarDatosActa(response, resultados, '0');
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud: ", status, error);
                }
            });
        }
    }

    function procesarDatosActa(response, resultados, etapa) {
        console.log(resultados, etapa);
        // Asumiendo que la respuesta es un objeto que contiene un array bajo la clave 'analisis_externos'
        if (response && response.analisis_externos && response.analisis_externos.length > 0) {
            const acta = response.analisis_externos[0]; // Tomamos el primer elemento, como ejemplo

            // Aquí asignas los valores a los campos del formulario
            // Asegúrate de que los ID de los elementos HTML coincidan con estos
            $('#producto').text(acta.nombre_producto + ' ' + acta.concentracion + ' ' + acta.formato);
            $('#Tipo_Producto').text(acta.tipo_producto);
            $('#form_producto').text(acta.nombre_producto + ' ' + acta.concentracion + ' ' + acta.formato);
            $('#form_tipo').text('Magistral ' + acta.tipo_producto);
            $('#form_lote').text(acta.lote);
            $('#form_tamano_lote').text(acta.tamano_lote);
            $('#form_codigo_mastersoft').text(acta.codigo_mastersoft);
            $('#form_condAlmacenamiento').text(acta.condicion_almacenamiento);
            $('#form_cant_muestra').text(acta.tamano_muestra);
            $('#form_cant_contramuestra').text(acta.tamano_contramuestra);
            $('#form_tipo_analisis').text(acta.tipo_analisis);
            $('#nro_acta').text(acta.numero_acta);

            $('#responsable').text(acta.muestreado_por);
            $('#cargo_responsable').text(acta.cargo_muestreado_por);
            // Puedes incluso cargar la imagen de la firma si tienes un elemento img para ello
            //$('#firma_responsable').attr('src', acta.foto_firma_muestreado_por);

            // Datos del usuario que revisó
            $('#verificadoPor').text(acta.revisado_por);
            $('#cargo_verificador').text(acta.cargo_revisado_por);
            // Y también para la firma
            //$('#firma_verificador').attr('src', acta.foto_firma_revisado_por);
            console.log(resultados, etapa);
            if (resultados) {
                switch (etapa) {
                    case '1':
                        var nombre_ejecutor = "<?php echo $_SESSION['nombre']; ?>";
                        var cargo = "<?php echo $_SESSION['cargo']; ?>";
                        var fecha_hoy = "<?php echo date('d-m-Y'); ?>";
                        var fecha_yoh = "<?php echo date('Y-m-d'); ?>";
                        $('#nro_registro').text(acta.numero_registro);
                        $('#nro_version').text(acta.version_registro);
                        $('#realizadoPor').text(nombre_ejecutor);
                        $('#cargo_realizador').text(cargo);
                        $('#fecha_muestreo').val(fecha_yoh).prop('readonly', false);
                        $('#fecha_Edicion').text(fecha_hoy);
                        $('.resp').css('background-color', '#f4fac2');
                        $('.verif input').prop('readonly', true);
                        $('.verif').css('background-color', '#e0e0e0'); // Asume que esto "atenuará" visualmente el elemento.
                        //revision_actor1
                        console.log(nombre_ejecutor, acta.muestreado_por);
                        if (nombre_ejecutor !== acta.muestreado_por) {
                            $('#revision_actor1').text('Revisión Ejecutor');
                            $('#revision_actor2').text('Revisión Responsable');
                        }
                        break;
                    case '2':
                        break;
                }
            } else {
                switch (acta.tipo_producto) {
                    case 'Material Envase y Empaque':
                        $('#nro_registro').text('DCAL-CC-AMMEE-' + acta.identificador_producto.toString().padStart(3, '0'));
                        break;
                    case 'Materia Prima':
                        $('#nro_registro').text('DCAL-CC-AMMP-' + acta.identificador_producto.toString().padStart(3, '0'));
                        break;
                    case 'Producto Terminado':
                        $('#nro_registro').text('DCAL-CC-AMPT-' + acta.identificador_producto.toString().padStart(3, '0'));
                        break;
                    case 'Insumo':
                        $('#nro_registro').text('DCAL-CC-AMINS-' + acta.identificador_producto.toString().padStart(3, '0'));
                        break;
                }
                $('#nro_version').text(1);
                $('#realizadoPor').text('Nombre:');
            }



        } else {
            console.error("No se recibieron datos válidos: ", response);
        }
    }
</script>