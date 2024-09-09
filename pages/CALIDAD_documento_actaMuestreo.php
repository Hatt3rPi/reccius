<?php
// archivo pages\CALIDAD_documento_actaMuestreo.php
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

    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    
    <script src="https://kit.fontawesome.com/7011384382.js" crossorigin="anonymous"></script>
    JS de DataTables 
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/notify.js"></script> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/css/DocumentoActa.css?version=1">

</head>

<body>
    <div id="form-container" class="form-container">
        <div id="Maincontainer">
            <!-- Header -->
            <div id="header-container" style="width: 100%; display: flex; justify-content: space-between; align-items: center;">

                <!-- Logo a la izquierda -->
                <div class="header-left" style="flex: 1;">
                    <img src="../assets/images/logo_reccius_medicina_especializada.png" alt="Logo Reccius" style="height: 100px;">
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
                            <td><input type="date" id="fecha_muestreo" name="fecha_muestreo" class="editable resp" value="<?php echo date('Y-m-d'); ?>" required></td>

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
                        <th id="revision_actor1">Revisión Muestreador</th>
                        <th></th>
                        <th id="revision_actor2">Revisión Responsable</th>
                        <th></th>
                        <th>Rótulo General de Muestra</th>
                    </tr>
                    <tr>
                        <td class="formulario-titulo">1. Producto:</td>
                        <td class="formulario" id="form_producto" readonly>id="form_producto" </td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical " role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="verificadores btn-check " name="identResp1" id="identResp1a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identResp1a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identResp1" id="identResp1b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identResp1b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identVB1" id="identVB1a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identVB1a"><i class="fa-regular fa-circle-check"></i> Cumple</i></label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identVB1" id="identVB1b" value="0" autocomplete="off">
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
                        <td class="formulario" id="form_tipo" readonly>id="form_tipo"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identResp2" id="identResp2a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identResp2a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identResp2" id="identResp2b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identResp2b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identVB2" id="identVB2a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identVB2a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identVB2" id="identVB2b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identVB2b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo">3. Lote:</td>
                        <td class="formulario" id="form_lote" readonly>id="form_lote"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identResp3" id="identResp3a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identResp3a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identResp3" id="identResp3b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identResp3b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identVB3" id="identVB3a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identVB3a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identVB3" id="identVB3b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identVB3b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo">4. Tamaño de Lote:</td>
                        <td class="formulario" id="form_tamano_lote" readonly>id="form_tamano_lote"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identResp4" id="identResp4a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identResp4a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identResp4" id="identResp4b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identResp4b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identVB4" id="identVB4a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identVB4a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identVB4" id="identVB4b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identVB4b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo">5. Código Interno:</td>
                        <td class="formulario" id="form_codigo_mastersoft" readonly>id="form_codigo_mastersoft"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identResp5" id="identResp5a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identResp5a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identResp5" id="identResp5b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identResp5b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identVB5" id="identVB5a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identVB5a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identVB5" id="identVB5b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identVB5b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo">6. Cond. Almacenamiento:</td>

                        <td class="formulario">
                            <div id="form_condAlmacenamiento" class="editable-diva"></div>

                        </td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identResp6" id="identResp6a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identResp6a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identResp6" id="identResp6b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identResp6b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identVB6" id="identVB6a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identVB6a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identVB6" id="identVB6b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identVB6b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo">7. Cantidad Muestra:</td>
                        <td class="formulario" id="form_cant_muestra" readonly>id="form_cant_muestra"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identResp7" id="identResp7a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identResp7a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identResp7" id="identResp7b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identResp7b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identVB7" id="identVB7a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identVB7a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identVB7" id="identVB7b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identVB7b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo">8. Cantidad Contramuestra:</td>
                        <td class="formulario" id="form_cant_contramuestra" readonly>id="form_cant_contramuestra"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identResp8" id="identResp8a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identResp8a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identResp8" id="identResp8b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identResp8b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identVB8" id="identVB8a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identVB8a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identVB8" id="identVB8b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identVB8b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo">9. Tipo de Análisis:</td>
                        <td class="formulario" id="form_tipo_analisis" readonly>id="form_tipo_analisis"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identResp9" id="identResp9a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identResp9a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identResp9" id="identResp9b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identResp9b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identVB9" id="identVB9a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="identVB9a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="identVB9" id="identVB9b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="identVB9b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                            </div>
                        </td>
                    </tr>
                    <tr style="Height: 46.66666px;">
                        <td class="formulario-titulo">10. Fecha Vencimiento:</td>
                        <td class="formulario" id="form_fecha_vencimiento" name="form_fecha_vencimiento" readonly>id="form_fecha_vencimiento"</td>
                    </tr>
                    <tr style="Height: 46.66666px;">
                        <td class="formulario-titulo">11. Fecha Elaboración:</td>
                        <td class="formulario" id="form_fecha_elaboracion" name="form_fecha_elaboracion" readonly>id="form_fecha_elaboracion"</td>
                    </tr>
                </table>
            </section>
            <label style="font-size: 12px" for="form_Inusual">12. Obs. del Análisis Externo:</label>
            <div class="editable-div textarea" contenteditable="true" id="form_observaciones" name="form_observaciones">id="form_observaciones"</div>
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
                        <th id="revision_actor1">Revisión Muestreador</th>
                        <th></th>
                        <th id="revision_actor2">Revisión Responsable</th>
                        <th></th>
                        <th>Rótulo General de Muestra</th>
                    </tr>

                    <tr>
                        <td>1. La zona de esterilización se encuentra limpia y ordenada.</td>
                        <td id="form_1" style="visibility: hidden;">
                            <!-- CheckBoxes para Conforme y No Conforme -->
                            <input type="checkbox" name="estado_Pro" value="Conforme">
                            <label for="conforme_Pro">Conforme</label>
                            <input type="checkbox" name="estado_Pro" value="No Conforme">
                            <label for="noConforme_Pro">No Conforme</label>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp1" id="muestreoResp1a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoResp1a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp1" id="muestreoResp1b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoResp1b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp1" id="muestreoResp1c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="muestreoResp1c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB1" id="muestreoVB1a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoVB1a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB1" id="muestreoVB1b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoVB1b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB1" id="muestreoVB1c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="muestreoVB1c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario" rowspan="9">
                            <label>Pegar etiqueta de identificación general de la muestra</label>
                        </td>
                    </tr>

                    <tr>
                        <td>2. Verificar que la zona de muestreo se encuentre libre de otros productos.</td>
                        <td id="form_2" style="visibility: hidden;">
                            <!-- CheckBoxes para Conforme y No Conforme -->
                            <input type="checkbox" name="estado_Pro" value="Conforme">
                            <label for="conforme_Pro">Conforme</label>
                            <input type="checkbox" name="estado_Pro" value="No Conforme">
                            <label for="noConforme_Pro">No Conforme</label>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp2" id="muestreoResp2a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoResp2a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp2" id="muestreoResp2b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoResp2b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp2" id="muestreoResp2c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="muestreoResp2c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB2" id="muestreoVB2a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoVB2a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB2" id="muestreoVB2b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoVB2b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB2" id="muestreoVB2c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="muestreoVB2c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>3. Evaluar el aspecto del producto en zona de revisión.</td>
                        <td id="form_3" style="visibility: hidden;">
                            <!-- CheckBoxes para Conforme y No Conforme -->
                            <input type="checkbox" name="estado_Pro" value="Conforme">
                            <label for="conforme_Pro">Conforme</label>
                            <input type="checkbox" name="estado_Pro" value="No Conforme">
                            <label for="noConforme_Pro">No Conforme</label>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp3" id="muestreoResp3a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoResp3a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp3" id="muestreoResp3b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoResp3b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp3" id="muestreoResp3c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="muestreoResp3c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB3" id="muestreoVB3a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoVB3a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB3" id="muestreoVB3b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoVB3b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB3" id="muestreoVB3c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="muestreoVB3c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>4. Verificar correcta identificación del lote y producto.</td>
                        <td id="form_4" style="visibility: hidden;">
                            <!-- CheckBoxes para Conforme y No Conforme -->
                            <input type="checkbox" name="estado_Pro" value="Conforme">
                            <label for="conforme_Pro">Conforme</label>
                            <input type="checkbox" name="estado_Pro" value="No Conforme">
                            <label for="noConforme_Pro">No Conforme</label>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp4" id="muestreoResp4a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoResp4a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp4" id="muestreoResp4b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoResp4b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp4" id="muestreoResp4c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="muestreoResp4c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB4" id="muestreoVB4a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoVB4a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB4" id="muestreoVB4b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoVB4b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB4" id="muestreoVB4c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="muestreoVB4c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>5. Cantidad de ciclos de esterilización</td>
                        <td class="formulario">
                            <div id="form_textarea5" class="editable-div" contenteditable="true"></div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp5" id="muestreoResp5a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoResp5a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp5" id="muestreoResp5b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoResp5b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp5" id="muestreoResp5c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="muestreoResp5c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB5" id="muestreoVB5a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoVB5a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB5" id="muestreoVB5b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoVB5b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB5" id="muestreoVB5c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="muestreoVB5c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>6. Cantidad bandejas esterilizadas por ciclo</td>
                        <td class="formulario">
                            <div id="form_textarea6" class="editable-div" contenteditable="true"></div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp6" id="muestreoResp6a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoResp6a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp6" id="muestreoResp6b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoResp6b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp6" id="muestreoResp6c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="muestreoResp6c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB6" id="muestreoVB6a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoVB6a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB6" id="muestreoVB6b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoVB6b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB6" id="muestreoVB6c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="muestreoVB6c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>7. Cantidad de muestras por bandeja</td>
                        <td class="formulario">
                            <div id="form_textarea7" class="editable-div" contenteditable="true"></div>

                        </td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp7" id="muestreoResp7a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoResp7a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp7" id="muestreoResp7b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoResp7b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoResp7" id="muestreoResp7c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="muestreoResp7c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB7" id="muestreoVB7a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="muestreoVB7a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB7" id="muestreoVB7b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="muestreoVB7b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check verificadores" name="muestreoVB7" id="muestreoVB7c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="muestreoVB7c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                    </tr>
                </table>
            </section>

            <div style="margin-top: 10px; font-size: 12px;">
                <br>
                <label for="form_Inusual">8. Registrar cualquier situación inesperada o inusual durante el proceso:</label>
                <div id="form_textarea8" name="form_textarea8" class="editable-div textarea" contenteditable="true"></div>
                <br><br>

            </div>
            <!-- Sección III: Plan de Muestreo -->
            <section id="sampling-plan">
                <h2 class="Subtitulos">III. PLAN DE MUESTREO</h2>
                <br>
                <table id="seccion3" style="width:100%; border-collapse: collapse;">
                    <!-- Encabezados de la tabla -->
                    <tr>
                        <th>Tamaño de Lote</th>
                        <th>Muestra</th>
                        <th>Contramuestra</th>
                        <th>Total</th>
                        <th id="revision_actor1">Revisión Muestreador</th>
                        <th id="revision_actor2">Revisión Responsable</th>
                    </tr>
                    <!-- Fila para lotes de <= 500 unidades -->
                    <tr style="border-bottom: 1px solid #000;border-left: 1px solid;border-right: 1px solid;">
                        <td readonly>&le; 500 unidades</td>
                        <td readonly>40 unidades</td>
                        <td readonly>80 unidades</td>
                        <td readonly>120 Unidades</td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check" name="planResp1" id="planResp1a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="planResp1a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check" name="planResp1" id="planResp1b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="planResp1b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check" name="planResp1" id="planResp1c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="planResp1c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check" name="planVB1" id="planVB1a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="planVB1a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check" name="planVB1" id="planVB1b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="planVB1b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check" name="planVB1" id="planVB1c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="planVB1c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                    </tr>
                    <!-- Fila para lotes entre 501 y 4999 unidades -->
                    <tr style="border-bottom: 1px solid #000;border-left: 1px solid;border-right: 1px solid;">
                        <td contenteditable="true">501 - 999 unidades</td>
                        <td contenteditable="true">40 unidades</td>
                        <td contenteditable="true">80 unidades</td>
                        <td contenteditable="true">120 Unidades</td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check" name="planResp2" id="planResp2a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="planResp2a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check" name="planResp2" id="planResp2b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="planResp2b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check" name="planResp2" id="planResp2c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="planResp2c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check" name="planVB2" id="planVB2a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="planVB2a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check" name="planVB2" id="planVB2b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="planVB2b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check" name="planVB2" id="planVB2c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="planVB2c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                    </tr>
                    <!-- Fila para lotes de >= 1000 unidades -->
                    <tr style="border-bottom: 1px solid #000;border-left: 1px solid;border-right: 1px solid;">
                        <td contenteditable="true">&ge; 1000 unidades</td>
                        <td contenteditable="true">50 unidades</td>
                        <td contenteditable="true">100 unidades</td>
                        <td contenteditable="true">150 Unidades</td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check" name="planResp3" id="planResp3a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="planResp3a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check" name="planResp3" id="planResp3b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="planResp3b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check" name="planResp3" id="planResp3c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="planResp3c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" style="display: none;" class="btn-check" name="planVB3" id="planVB3a" value="1" autocomplete="off">
                                <label class="btn btn-outline-success verificadores" for="planVB3a"><i class="fa-regular fa-circle-check"></i> Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check" name="planVB3" id="planVB3b" value="0" autocomplete="off">
                                <label class="btn btn-outline-danger verificadores" for="planVB3b"><i class="fa-regular fa-circle-xmark"></i> No Cumple</label>
                                <input type="radio" style="display: none;" class="btn-check" name="planVB3" id="planVB3c" value="2" autocomplete="off">
                                <label class="btn btn-outline-secondary verificadores" for="planVB3c"><i class="fa-regular fa-circle-xmark"></i> No Aplica</label>
                            </div>
                        </td>
                    </tr>
                </table>
            </section>


            <!-- Footer -->
            <div id="footer-containerDIV" class="footer-containerDIV">




                <!-- Sección Muestreado por -->
                <div class="firma-section">
                    <div class="firma-box-title">Muestreado por:</div>
                    <div class="firma-boxes">
                        <p id="realizadoPor" name="realizadoPor" class="bold">

                        <p id="cargo_realizador" name="cargo_realizador" class="bold"></p>
                        </p>
                        <div class="signature">
                            <!-- Agregar la imagen aquí -->
                            <img id="firma_realizador" name="firma_realizador" src="https://pub-bde9ff3e851b4092bfe7076570692078.r2.dev/firma_null.webp" alt="Firma" class="firma">
                        </div>
                    </div>
                    <div class="date-container">
                        <div id='fecha_firma1' name='fecha_firma1' class="date" style="display: none;">Fecha: dd/mm/yyyy</div>
                        <p id='mensaje_firma1' name='mensaje_firma1' class="text-bottom" style="display: none;">Firmado digitalmente</p>
                    </div>
                </div>
                <!-- Sección Realizado por -->
                <div class="firma-section">
                    <div class="firma-box-title">Responsable:</div>
                    <div class="firma-boxes">
                        <p id='responsable' name='responsable' class="bold">
                        </p>

                        <p id="cargo_responsable" name="cargo_responsable" class="bold">
                        </p>
                        <div class="signature">
                            <!-- Agregar la imagen aquí -->
                            <img id="firma_responsable" name="firma_responsable" src="https://pub-bde9ff3e851b4092bfe7076570692078.r2.dev/firma_null.webp" alt="Firma" class="firma">

                        </div>

                    </div>
                    <div class="date-container">
                        <div id='fecha_firma2' name='fecha_firma2' class="date" style="display: none;">Fecha: dd/mm/yyyy</div>
                        <p id='mensaje_firma2' name='mensaje_firma2' class="text-bottom" style="display: none;">Firmado digitalmente</p>
                        <p id='user_firma2' name='user_firma2' style="display: none;"></p>
                    </div>
                </div>
                <!-- Sección Verificado por -->
                <div class="firma-section">
                    <div class="firma-box-title">Revisado por:</div>
                    <div class="firma-boxes">
                        <p id='verificadoPor' name='verificadoPor' class="bold">
                        </p>

                        <p id='cargo_verificador' name='cargo_verificador' class="bold">
                        </p>

                        <div class="signature">
                            <!-- Agregar la imagen aquí -->
                            <img id="firma_verificador" name="firma_verificador" src="https://pub-bde9ff3e851b4092bfe7076570692078.r2.dev/firma_null.webp" alt="firma_verificador" class="firma" />

                        </div>

                    </div>
                    <div class="date-container">
                        <div id='fecha_firma3' name='fecha_firma3' class="date" style="display: none;">Fecha: dd/mm/yyyy</div>
                        <p id='mensaje_firma3' name='mensaje_firma3' class="text-bottom" style="display: none;">Firmado digitalmente</p>
                        <p id='user_firma3' name='user_firma3' style="display: none;" style="display: none;"></p>
                    </div>
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
    <button class="botones" id="metodo_muestreo" data-bs-toggle="modal" data-bs-target="#modalMetodoMuestreo">Método
        Muestreo</button>
    <button class="botones" id="guardar" style="display: none">Guardar</button>
    <button class="botones" id="firmar" style="display: none">Ingresar Resultados</button>
    <button class="botones" id="download-pdf" style="display: none">Descargar PDF</button>
    <button class="botones" id="upload-pdf" style="display: none">Guardar PDF</button>
    <p id='etapa' name='etapa' style="display: none;"></p>
    <p id='id_actaMuestreo' name='id_actaMuestreo' style="display: none;"></p>
    <p id='id_analisis_externo' name='id_analisis_externo' style="display: none;"></p>
    <p id='numero_solicitud_analisis_externo' name='numero_solicitud_analisis_externo' style="display: none;"></p>
    <p id='solicitado_por_analisis_externo' name='solicitado_por_analisis_externo' style="display: none;"></p>
</div>
<!-- Modal -->
<div class="modal fade" id="modalMetodoMuestreo" tabindex="-1" aria-labelledby="modalMetodoMuestreoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMetodoMuestreoLabel">Seleccionar Método de Muestreo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="metodoMuestreo" id="muestreoManual" value="manual">
                    <label class="form-check-label" for="muestreoManual">
                        Acta de Muestreo Manual (en papel)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="metodoMuestreo" id="muestreoDigital" value="digital">
                    <label class="form-check-label" for="muestreoDigital">
                        Acta de Muestreo Digital (en tablet)
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="confirmarMetodo">Confirmar</button>
            </div>
        </div>
    </div>
</div>
<div id="notification" class="notification-container notify" style="display: none;">
    <p id="notification-message">Este es un mensaje de notificación.</p>
</div>

</html>
<script>
    var idAnalisisExterno_acta = null;

    document.getElementById('confirmarMetodo').addEventListener('click', function() {
        const metodoManual = document.getElementById('muestreoManual').checked;
        const metodoDigital = document.getElementById('muestreoDigital').checked;

        // Cerrar el modal después de seleccionar la opción
        $('#modalMetodoMuestreo').modal('hide');
        $("#modalMetodoMuestreo").css("z-index", "-1");
        if (metodoManual) {
            // Simula un clic en el botón de descarga de PDF si el método manual es seleccionado
            document.getElementById('download-pdf').click();
        } else if (metodoDigital) {
            $('#etapa').text('ingresa resultados y firma1');
            // Hacer visible el contenido en formulario.resp si el método digital es seleccionado
            document.querySelectorAll('.formulario.resp *').forEach(function(element) {
                element.style.visibility = 'visible';
            });
            document.getElementById('metodo_muestreo').style.display = 'none';
            document.getElementById('firmar').style.display = 'none';
            document.getElementById('guardar').style.display = 'block';
            $('.resp').css('background-color', '#f4fac2');
            var nombre_ejecutor = "<?php echo $_SESSION['nombre']; ?>";
            var cargo = "<?php echo $_SESSION['cargo']; ?>";
            var fecha_hoy = "<?php echo date('d-m-Y'); ?>";
            var fecha_yoh = "<?php echo date('Y-m-d'); ?>";
            $('#fecha_muestreo').val(fecha_yoh).prop('readonly', false);
            $('#fecha_firma1').text(fecha_hoy);
            //document.getElementById('fecha_firma1').style.display = 'block';
            //document.getElementById('mensaje_firma1').style.display = 'block';

            $('#cargo_realizador').text(cargo);
            $('#realizadoPor').text(nombre_ejecutor);

            $('#form_textarea5').prop('readonly', false).css('background-color', '#f4fac2');
            $('#form_textarea6').prop('readonly', false).css('background-color', '#f4fac2');
            $('#form_textarea7').prop('readonly', false).css('background-color', '#f4fac2');
            $('#form_textarea8').prop('readonly', false).css('background-color', '#f4fac2');
        }
    });

    function setFirmaImage(imgElement, firmaSrc) {
        const nullImage = 'https://pub-bde9ff3e851b4092bfe7076570692078.r2.dev/firma_null.webp';
        const noProvidedImage = 'https://pub-bde9ff3e851b4092bfe7076570692078.r2.dev/firma_no_proporcionada.webp';

        if (!firmaSrc) {
            imgElement.src = nullImage;
            console.log("Firma no disponible, usando imagen nula.");
        } else {
            imgElement.onerror = function() {
                imgElement.src = noProvidedImage;
                console.log("Error al cargar la firma, usando imagen de firma no proporcionada.");
            };
            imgElement.src = firmaSrc;
            console.log("Cargando firma desde:", firmaSrc);
        }
    }
    // Cambio: Actualizar la función firma1 para usar setFirmaImage
    // Función para manejar la respuesta y asignar valores a los elementos del DOM
    function firma1(response) {
        var fotoFirmaUsuario = response.foto_firma_usr1;

        console.log('Asignación de la firma del usuario:');
        console.log(fotoFirmaUsuario);
        fetch(fotoFirmaUsuario).then(resp => resp.blob()).then(blob => new Promise((resolve, _) => {
            const reader = new FileReader();
            reader.onloadend = () => resolve(reader.result);
            reader.readAsDataURL(blob);
        })).then((data) => {
            console.log(data)
            setFirmaImage(document.getElementById('firma_realizador'), data);
        })
        $('#fecha_firma1').text(response.fecha_firma_muestreador);
        document.getElementById('fecha_firma1').style.display = 'block';
        document.getElementById('mensaje_firma1').style.display = 'block';
        asignarValoresARadios(response.resultados_muestrador, '.formulario.resp');
    }

    function firma2(response) {
        var fotoFirmaReesponsable = response.foto_firma_usr2;

        console.log('Asignación de la firma del usuario:');
        console.log(fotoFirmaReesponsable);
        fetch(fotoFirmaReesponsable).then(resp => resp.blob()).then(blob => new Promise((resolve, _) => {
            const reader = new FileReader();
            reader.onloadend = () => resolve(reader.result);
            reader.readAsDataURL(blob);
        })).then((data) => {
            console.log(data)
            setFirmaImage(document.getElementById('firma_responsable'), data);
        })
        $('#fecha_firma2').text(response.fecha_firma_muestreador);
        document.getElementById('fecha_firma2').style.display = 'block';
        document.getElementById('mensaje_firma2').style.display = 'block';
        asignarValoresARadios(response.resultados_responsable, '.formulario.verif');
    }

    function firma3(response) {
        var fotoFirmaVerificador = response.foto_firma_usr3;

        console.log('Asignación de la firma del usuario:');
        console.log(fotoFirmaVerificador);
        fetch(fotoFirmaVerificador).then(resp => resp.blob()).then(blob => new Promise((resolve, _) => {
            const reader = new FileReader();
            reader.onloadend = () => resolve(reader.result);
            reader.readAsDataURL(blob);
        })).then((data) => {
            console.log(data)
            setFirmaImage(document.getElementById('firma_verificador'), data);
        })
        $('#fecha_firma3').text(response.fecha_firma_muestreador);
        document.getElementById('fecha_firma3').style.display = 'block';
        document.getElementById('mensaje_firma3').style.display = 'block';
    }

    function asignarValoresARadios(valores, selectorGrupos) {
        const grupos = document.querySelectorAll(selectorGrupos);
        console.log("Cantidad de grupos encontrados:", grupos.length);
        console.log("Longitud de valores esperados:", valores.length);

        if (valores.length !== grupos.length) {
            console.error("La cantidad de valores no coincide con la cantidad de grupos de botones.");
            return;
        }

        grupos.forEach((grupo, index) => {
            const valor = valores[index];
            let suffix;

            // Asignar el sufijo según el valor recibido
            if (valor === '1') {
                suffix = 'a';
            } else if (valor === '0') {
                suffix = 'b';
            } else if (valor === '2') { // Nuevo valor para "No Aplica"
                suffix = 'c';
            }

            const radio = grupo.querySelector(`input[type="radio"][id$="${suffix}"]`);

            const allRadios = grupo.querySelectorAll('input[type="radio"]');
            allRadios.forEach(r => {
                r.setAttribute('disabled', 'disabled');
            });

            if (radio) {
                radio.checked = true;
            } else {
                console.error(`No se encontró el botón con id terminado en '${suffix}' en el grupo ${index + 1}`);
            }
        });
    }







    document.getElementById('download-pdf').addEventListener('click', function() {
        const {
            jsPDF
        } = window.jspdf;

        // Configuración inicial de jsPDF
        const pdf = new jsPDF({
            orientation: 'p',
            unit: 'mm',
            format: 'a4'
        });

        const imgWidth = 210; // Ancho A4
        const pageHeight = 297; // Alto A4
        const marginTop = 20; // Margen superior para el contenido

        // Agregar el header manualmente
        pdf.setFontSize(16);
        pdf.text('Acta de Muestreo Control de Calidad', imgWidth / 2, marginTop, {
            align: 'center'
        });

        // Datos generales
        const registro = document.getElementById('nro_registro').textContent || '';
        const version = document.getElementById('nro_version').textContent || '';
        const acta = document.getElementById('nro_acta').textContent || '';
        const fecha = document.getElementById('fecha_muestreo').value || '';

        pdf.setFontSize(12);
        pdf.text(`N° Registro: ${registro}`, 10, marginTop + 20);
        pdf.text(`N° Versión: ${version}`, 10, marginTop + 30);
        pdf.text(`N° Acta: ${acta}`, 10, marginTop + 40);
        pdf.text(`Fecha Muestreo: ${fecha}`, 10, marginTop + 50);

        // Ejemplo de contenido del muestreo
        const producto = document.getElementById('producto').textContent || '';
        const tipoProducto = document.getElementById('Tipo_Producto').textContent || '';
        const lote = document.getElementById('form_lote').textContent || '';

        pdf.text(`Producto: ${producto}`, 10, marginTop + 70);
        pdf.text(`Tipo Producto: ${tipoProducto}`, 10, marginTop + 80);
        pdf.text(`Lote: ${lote}`, 10, marginTop + 90);

        // Puedes agregar más datos según necesites aquí.

        // Agregar el footer manualmente
        pdf.setFontSize(10);
        pdf.text('La información contenida en esta acta es de carácter CONFIDENCIAL', imgWidth / 2, pageHeight - 10, {
            align: 'center'
        });

        // Guardar el PDF
        const nombreProducto = producto || 'Acta';
        const nombreDocumento = acta || 'Documento';
        pdf.save(`${nombreDocumento}_${nombreProducto}.pdf`);
    });





    document.getElementById('upload-pdf').addEventListener('click', function() {

        const allButtonGroups = document.querySelectorAll('.btn-group-horizontal, .btn-group-vertical');

        allButtonGroups.forEach(group => {
            const buttons = group.querySelectorAll('.btn-check');
            buttons.forEach(button => {
                if (!button.checked) {
                    button.nextElementSibling.style.display = 'none';
                }
            });
        });

        document.querySelector('.button-container').style.display = 'none';
        const elementToExport = document.getElementById('form-container');
        elementToExport.style.border = 'none';
        elementToExport.style.boxShadow = 'none';

        html2canvas(elementToExport, {
            scale: 1
        }).then(canvas => {
            document.querySelector('.button-container').style.display = 'block';
            elementToExport.style.border = '1px solid #000';
            elementToExport.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.1)';

            allButtonGroups.forEach(group => {
                const buttons = group.querySelectorAll('.btn-check');
                buttons.forEach(button => {
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

            const blob = pdf.output('blob');

            const formData = new FormData();
            formData.append('certificado', blob, 'documento.pdf');
            formData.append('type', 'acta');
            formData.append('id_solicitud', idAnalisisExterno_acta);

            fetch('./backend/calidad/add_documentos.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        $.notify("PDF subido con éxito", "success");
                    } else {
                        $.notify("Error al subir el PDF: " + data.message, "error");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    $.notify("Error al subir el PDF", "error");
                });

            allButtonGroups.forEach(group => {
                const buttons = group.querySelectorAll('.btn-check');
                buttons.forEach(button => {
                    button.style.display = 'block';
                });
            });
        }).catch(error => {
            console.error('Error al generar el canvas:', error);
            $.notify("Error al generar el PDF", "error");
        });
    });


    //cargarDatosEspecificacion(id, true, '0');
    function cargarDatosEspecificacion(id, resultados, etapa) {
        console.log(id, resultados, etapa);
        var id_actaM = "<?php echo $_SESSION['nuevo_id']; ?>";
        if (resultados) {
            $.ajax({
                url: './backend/acta_muestreo/consulta_resultados.php',
                type: 'POST',
                dataType: 'json', // Asegura que la respuesta se parsea como JSON
                data: {
                    id_actaMuestreo: id
                },
                success: function(data) {
                    console.log('Datos recibidos:', data)

                    $('#id_actaMuestreo').text(id);
                    if (data.analisis_externos && data.analisis_externos.length > 0) {
                        procesarDatosActa(data.analisis_externos[0], resultados, etapa);
                    } else {
                        console.error("No se encontraron datos válidos: ", data);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud: ", status, error);
                }
            });
        } else {
            // Solicitud GET para generar una nueva acta
            $.ajax({
                url: './backend/acta_muestreo/genera_acta.php',
                type: 'GET',
                dataType: 'json', // Asegura que la respuesta se parsea como JSON
                data: {
                    id_analisis_externo: id
                },
                success: function(data) {
                    console.log('Datos recibidos para nueva acta:', data);
                    if (data.id_actaMuestreo) {
                        $('#id_actaMuestreo').text(data.id_actaMuestreo);
                    }
                    if (data.analisis_externos && data.analisis_externos.length > 0) {
                        procesarDatosActa(data.analisis_externos[0], resultados, etapa);
                    } else {
                        console.error("No se recibieron datos válidos: ", data);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud: ", status, error);
                }
            });
        }
    }

    function procesarDatosActa(response, resultados, etapa) {
        console.log(resultados, etapa);
        idAnalisisExterno_acta = response.id_analisis_externo
        var nombreProducto = response.nombre_producto || ''; // Si es null, lo reemplaza por un string vacío
        var concentracion = response.concentracion ? ' ' + response.concentracion : ''; // Añade un espacio antes solo si no es null
        var formato = response.formato ? ' ' + response.formato : ''; // Añade un espacio antes solo si no es null

        // Concatenar solo las partes que no sean nulas o vacías
        var productoTexto = nombreProducto + concentracion + formato;
        // Asumiendo que la respuesta es un objeto que contiene un array bajo la clave 'analisis_externos'
        // Aquí asignas los valores a los campos del formulario
        // Asegúrate de que los ID de los elementos HTML coincidan con estos
        $('#producto').text(productoTexto);
        $('#Tipo_Producto').text(response.tipo_producto);
        $('#form_producto').text(productoTexto);
        $('#form_tipo').text('Magistral ' + response.tipo_producto);
        $('#form_lote').text(response.lote);
        $('#form_tamano_lote').text(response.tamano_lote);
        $('#form_codigo_mastersoft').text(response.codigo_mastersoft);
        $('#form_condAlmacenamiento').text(response.condicion_almacenamiento);
        $('#form_cant_muestra').text(response.tamano_muestra);
        $('#form_cant_contramuestra').text(response.tamano_contramuestra);
        $('#form_tipo_analisis').text(response.tipo_analisis);
        $('#nro_acta').text(response.numero_acta);
        $('#realizadoPor').text(response.nombre_usr1);
        $('#cargo_realizador').text(response.cargo_usr1);
        $('#responsable').text(response.nombre_usr2);
        $('#user_firma2').text(response.usuario_firma2);
        $('#cargo_responsable').text(response.cargo_usr2);
        $('#verificadoPor').text(response.nombre_usr3);
        $('#user_firma3').text(response.verificador);
        $('#cargo_verificador').text(response.cargo_usr3);
        $('#numero_solicitud_analisis_externo').text(response.aex_numero_solicitud);
        $('#solicitado_por_analisis_externo').text(response.aex_solicitado_por);
        $('#form_fecha_vencimiento').text(response.fecha_vencimiento);
        $('#form_fecha_elaboracion').text(response.fecha_elaboracion);
        $('#form_observaciones').html(response.observaciones);
        console.log(resultados, etapa);
        if (resultados) {
            let usuario_activo = "<?php echo $_SESSION['usuario']; ?>";
            $('#nro_registro').text(response.numero_registro);
            $('#nro_version').text(response.version_registro);
            $('#id_analisis_externo').text(response.id_analisis_externo);
            $('#user_realizadoPor').text(usuario_activo);
            switch (response.cantidad_firmas) {
                case 0:
                    var nombre_ejecutor = "<?php echo $_SESSION['nombre']; ?>";
                    var cargo = "<?php echo $_SESSION['cargo']; ?>";
                    var fecha_yoh = "<?php echo date('Y-m-d'); ?>";
                    $('#etapa').text('ingresa resultados y firma1');
                    $('#realizadoPor').text(nombre_ejecutor);
                    $('#cargo_realizador').text(cargo);
                    $('#form_textarea5').text(response.pregunta5).prop('readonly', false).css('background-color', '#f4fac2');
                    $('#form_textarea6').text(response.pregunta6).prop('readonly', false).css('background-color', '#f4fac2');
                    $('#form_textarea7').text(response.pregunta7).prop('readonly', false).css('background-color', '#f4fac2');
                    $('#form_textarea8').text(response.pregunta8).prop('readonly', false).css('background-color', '#f4fac2');
                    $('#fecha_muestreo').val(fecha_yoh).prop('readonly', false);
                    document.getElementById('metodo_muestreo').style.display = 'none';
                    document.getElementById('guardar').style.display = 'block';
                    $('.resp').css('background-color', '#f4fac2');
                    document.querySelectorAll('.formulario.verif *').forEach(function(element) {
                        element.style.visibility = 'hidden'; // Hacer invisible el contenido
                    });
                    break;
                case 1:
                    //documento firmado por muestreador. queda pendiente firma de responsable
                    $('#form_textarea5').text(response.pregunta5).prop('readonly', true);
                    $('#form_textarea6').text(response.pregunta6).prop('readonly', true);
                    $('#form_textarea7').text(response.pregunta7).prop('readonly', true);
                    $('#form_textarea8').text(response.pregunta8).prop('readonly', true);
                    $('#fecha_muestreo').val(response.fecha_muestreo).prop('readonly', true);
                    firma1(response);
                    $('#etapa').text('ingresa resultados y firma2');
                    if (usuario_activo == response.responsable) {
                        document.getElementById('metodo_muestreo').style.display = 'none';
                        document.getElementById('guardar').style.display = 'block';
                        $('.verif').css('background-color', '#f4fac2');
                    }

                    break;
                case 2:
                    //documento firmado por muestreador y responsable. queda pendiente firma de revisor
                    $('#form_textarea5').text(response.pregunta5).prop('readonly', true);
                    $('#form_textarea6').text(response.pregunta6).prop('readonly', true);
                    $('#form_textarea7').text(response.pregunta7).prop('readonly', true);
                    $('#form_textarea8').text(response.pregunta8).prop('readonly', true);
                    $('#fecha_muestreo').val(response.fecha_muestreo).prop('readonly', true);
                    firma1(response);
                    firma2(response);
                    $('#etapa').text('firma3');
                    if (usuario_activo == response.verificador) {
                        document.getElementById('metodo_muestreo').style.display = 'none';
                        document.getElementById('guardar').style.display = 'block';
                    }
                    break;
                case 3:
                    $('#form_textarea5').text(response.pregunta5).prop('readonly', true);
                    $('#form_textarea6').text(response.pregunta6).prop('readonly', true);
                    $('#form_textarea7').text(response.pregunta7).prop('readonly', true);
                    $('#form_textarea8').text(response.pregunta8).prop('readonly', true);
                    $('#fecha_muestreo').val(response.fecha_muestreo).prop('readonly', true);
                    firma1(response);
                    firma2(response);
                    firma3(response);
                    document.getElementById('metodo_muestreo').style.display = 'none';
                    document.getElementById('guardar').style.display = 'none';
                    document.getElementById('download-pdf').style.display = 'block';
                    $('#upload-pdf').show();
                    break;
            }
        } else {
            switch (response.tipo_producto) {
                case 'Material Envase y Empaque':
                    $('#nro_registro').text('DCAL-CC-AMMEE-' + response.identificador_producto.toString().padStart(3, '0'));
                    break;
                case 'Materia Prima':
                    $('#nro_registro').text('DCAL-CC-AMMP-' + response.identificador_producto.toString().padStart(3, '0'));
                    break;
                case 'Producto Terminado':
                    $('#nro_registro').text('DCAL-CC-AMPT-' + response.identificador_producto.toString().padStart(3, '0'));
                    break;
                case 'Insumo':
                    $('#nro_registro').text('DCAL-CC-AMINS-' + response.identificador_producto.toString().padStart(3, '0'));
                    break;
            }
            $('#nro_version').text(1);
            $('#realizadoPor').text('Nombre:');
            document.querySelectorAll('.formulario.verif *, .formulario.resp *').forEach(function(element) {
                element.style.visibility = 'hidden'; // Hacer invisible el contenido
            });

        }
    }


    document.getElementById('firmar').addEventListener('click', function() {
        // Hacer visibles los elementos de .formulario.resp
        console.log('click firma')
        document.querySelectorAll('.formulario.resp *').forEach(function(element) {
            element.style.visibility = 'visible';
        });
        console.log('formulario resp cargado')
        document.getElementById('guardar').style.display = 'none';
        document.getElementById('firmar').style.display = 'none';
        $('.resp').css('background-color', '#f4fac2');

    });

    function consolidarRespuestas(universo) {
        let valorConsolidado = '';
        const grupos = document.querySelectorAll(universo);

        grupos.forEach(grupo => {
            const radioSeleccionado = grupo.querySelector('input[type="radio"]:checked');

            if (radioSeleccionado) {
                if (radioSeleccionado.id.endsWith('a')) {
                    valorConsolidado += '1'; // Cumple
                } else if (radioSeleccionado.id.endsWith('b')) {
                    valorConsolidado += '0'; // No Cumple
                } else if (radioSeleccionado.id.endsWith('c')) {
                    valorConsolidado += '2'; // No Aplica
                }
            } else {
                valorConsolidado += 'N'; // Ninguna opción seleccionada
            }
        });

        return valorConsolidado;
    }



    document.getElementById('guardar').addEventListener('click', function() {

        let etapa = $('#etapa').text();
        switch (etapa) {
            case 'ingresa resultados y firma1':
                guardar_firma('.formulario.resp', 1);
                break;
            case 'ingresa resultados y firma2':
                guardar_firma('.formulario.verif', 2);
                break;
            case 'firma3':
                guardar_firma3();
                break;
        }
    });

    function guardar_firma(selector, etapa) {
        let usuario = "<?php echo $_SESSION['usuario']; ?>";
        let respuestas = consolidarRespuestas(selector);
        let id_actaMuestreo = $('#id_actaMuestreo').text();
        let id_analisis_externo = $('#id_analisis_externo').html();
        let firma2 = $('#user_firma2').text();
        let firma3 = $('#user_firma3').text();
        let acta = $('#nro_acta').text();
        let observaciones = $('#form_observaciones').html(); // Esto funcionará para texto plano.
        let numero_solicitud_analisis_externo = $('#numero_solicitud_analisis_externo').text();
        let solicitado_por_analisis_externo = $('#solicitado_por_analisis_externo').text();
        let todosSeleccionados = true;
        let dataToSave = {
            id_actaMuestreo: id_actaMuestreo,
            etapa: etapa,
            usuario: usuario,
            firma2: firma2,
            firma3: firma3,
            acta: acta,
            id_analisis_externo: id_analisis_externo,
            observaciones: observaciones,
            numero_solicitud: numero_solicitud_analisis_externo,
            solicitado_por_analisis_externo: solicitado_por_analisis_externo,
            respuestas: respuestas,
            textareaData: {}
        };
        let botonesNoSeleccionados = [];

        // Verifica que todos los radio buttons en el selector especificado estén seleccionados
        document.querySelectorAll(selector).forEach(function(grupo) {
            const radioSeleccionado = grupo.querySelector('input[type="radio"]:checked');
            if (!radioSeleccionado) {
                todosSeleccionados = false;
                grupo.querySelectorAll('input[type="radio"]').forEach(function(radio) {
                    botonesNoSeleccionados.push(radio.id); // Agregar ID de los radios no seleccionados
                });
            }
        });

        if (!todosSeleccionados) {
            console.log("Botones no seleccionados:", botonesNoSeleccionados.join(', '));
            //alert("Por favor, asegúrate de que todos los campos han sido seleccionados.");
            $.notify("Existen campos incompletos.", "warn");
            return; // Detiene la función si no todos están seleccionados
        }

        // Recolecta datos de los textarea sólo si la firma es 1
        if (etapa === 1) {
            ['form_textarea5', 'form_textarea6', 'form_textarea7', 'form_textarea8'].forEach(function(id) {
                let textarea = document.getElementById(id);
                // Usa textContent o innerText en lugar de value para los divs con contenteditable
                if (textarea.textContent.trim() === '') {
                    //alert(`El campo ${id} está vacío y es obligatorio.`);
                    $.notify(`El campo ${id} está vacío y es obligatorio.`, "warn");
                    todosSeleccionados = false;
                    return;
                } else {
                    dataToSave.textareaData[id] = textarea.textContent;
                }
            });

            if (!todosSeleccionados) {
                return; // Detiene la función si algún textarea está vacío
            }
        }

        // Enviar datos al servidor usando AJAX
        console.log(dataToSave);
        $.ajax({
            url: './backend/acta_muestreo/guardar_y_firmar.php',
            type: 'POST',
            data: JSON.stringify(dataToSave),
            contentType: 'application/json; charset=utf-8',
            success: function(response) {
                console.log('Guardado exitoso: ', response);
                //alert("Datos guardados correctamente.");
                $.notify("Datos guardados correctamente.", "success");
                $('#listado_acta_muestreo').click();

            },
            error: function(xhr, status, error) {
                console.error("Error al guardar: ", status, error);
                //alert("Error al guardar los datos.");
                $.notify("Error al guardar los datos.", "error");
            }
        });
    }

    function guardar_firma3() {
        let id_actaMuestreo = $('#id_actaMuestreo').text();
        let id_analisis_externo = $('#id_analisis_externo').html();
        let firma2 = $('#user_firma2').text();
        let firma3 = $('#user_firma3').text();
        let acta = $('#nro_acta').text();
        let observaciones = $('#form_observaciones').html();
        let numero_solicitud_analisis_externo = $('#numero_solicitud_analisis_externo').text();
        let solicitado_por_analisis_externo = $('#solicitado_por_analisis_externo').text();
        let dataToSave = {
            id_analisis_externo: id_analisis_externo,
            id_actaMuestreo: id_actaMuestreo,
            firma2: firma2,
            firma3: firma3,
            acta: acta,
            observaciones: observaciones,
            numero_solicitud: numero_solicitud_analisis_externo,
            solicitado_por_analisis_externo: solicitado_por_analisis_externo,
            etapa: 3,
            respuestas: 'no aplica'
        };

        // Enviar datos al servidor usando AJAX
        console.log(dataToSave);
        $.ajax({
            url: './backend/acta_muestreo/guardar_y_firmar.php',
            type: 'POST',
            data: JSON.stringify(dataToSave),
            contentType: 'application/json; charset=utf-8',
            success: function(response) {
                console.log('Firma guardada con éxito: ', response);
                //alert("Firma guardada correctamente.");
                $.notify("Documento firmado correctamente.", "success");
                $('#listado_acta_muestreo').click();
            },
            error: function(xhr, status, error) {
                console.error("Error al guardar la firma: ", status, error);
                //alert("Error al guardar la firma.");
                $.notify("Error al firmar documento", "error");
            }
        });
    }
</script>