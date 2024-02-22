<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Acta de Muestreo Control de Calidad</title>
    <link rel="stylesheet" href="../assets/css/DocumentoActa.css">
    <script  src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>

<body>
    <div id="form-container" class="form-container">
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
                        style="margin: 0; font-size: 11px; font-weight: bold; color: #000;">Acta de Muestreo Control
                        de
                        Calidad
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
                    <table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
                        <tr>
                            <td style="border: 1px dotted #000; padding: 2px;">N° Registro:</td>
                            <td style="border: 1px dotted #000; padding: 2px; text-align: center">DCAL-CC-AMPT-005
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px dotted #000; padding: 2px;">N° Versión:</td>
                            <td style="border: 1px dotted #000; padding: 2px; text-align: center">001</td>
                        </tr>
                        <tr>
                            <td style="border: 1px dotted #000; padding: 2px;">N° Acta:</td>
                            <td style="border: 1px dotted #000; padding: 2px; text-align: center">AMPT-2301006-00
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px dotted #000; padding: 2px;">Fecha Muestreo:</td>
                            <td style="border: 1px dotted #000; padding: 2px; text-align: center">
                                <!-- Fecha de muestreo aquí -->
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
            <!-- Body -->
            <h2 style="font-size: 14px; font-weight: bold; margin-bottom: 10px;">I. IDENTIFICACIÓN DE LA MUESTRA</h2>
            
            <!-- Sección I: Identificación de la Muestra -->
            <section id="sample-identification"
                style="display: flex; justify-content: space-between; align-items: stretch; gap: 5px;">
                <!-- Tabla de identificación de la muestra -->
                <table id="identificacion_muestra" name="identificacion_muestra">
                    <tr>
                        <th ></th>
                        <th ></th>
                        <th ></th>
                        <th>Revisión Responsable</th>
                        <th ></th>
                        <th>Revisión Verificador</th>
                        <th ></th>
                        <th >Rótulo General de Muestra</th>
                    </tr>
                    <tr>
                        <td class="formulario-titulo" >1. Producto:</td>
                        <td class="formulario" id="form_Pro">id="form_Pro"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical " role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="verificadores btn-check " name="identResp1" id="identResp1a" autocomplete="off" >
                                <label class="btn btn-outline-primary verificadores" for="identResp1a">Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identResp1" id="identResp1b" autocomplete="off">
                                <label class="btn btn-outline-primary verificadores" for="identResp1b">No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identVB1" id="identVB1a" autocomplete="off" >
                                <label class="btn btn-outline-primary verificadores" for="identVB1a">Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identVB1" id="identVB1b" autocomplete="off">
                                <label class="btn btn-outline-primary verificadores" for="identVB1b">No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario" rowspan="9">
                            <label>Pegar etiqueta de identificación general de la muestra</label>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo" >2. Tipo Producto:</td>
                        <td class="formulario" id="form_Tipo " >id="form_Tipo"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identResp2" id="identResp2a" autocomplete="off" >
                                <label class="btn btn-outline-primary verificadores" for="identResp2a">Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identResp2" id="identResp2b" autocomplete="off">
                                <label class="btn btn-outline-primary verificadores" for="identResp2b">No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identVB2" id="identVB2a" autocomplete="off" >
                                <label class="btn btn-outline-primary verificadores" for="identVB2a">Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identVB2" id="identVB2b" autocomplete="off">
                                <label class="btn btn-outline-primary verificadores" for="identVB2b">No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo" >3. Lote:</td>
                        <td class="formulario" id="form_Lote " >id="form_Lote"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identResp3" id="identResp3a" autocomplete="off" >
                                <label class="btn btn-outline-primary verificadores" for="identResp3a">Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identResp3" id="identResp3b" autocomplete="off">
                                <label class="btn btn-outline-primary verificadores" for="identResp3b">No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identVB3" id="identVB3a" autocomplete="off" >
                                <label class="btn btn-outline-primary verificadores" for="identVB3a">Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identVB3" id="identVB3b" autocomplete="off">
                                <label class="btn btn-outline-primary verificadores" for="identVB3b">No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo" >4. Tamaño de Lote:</td>
                        <td class="formulario" id="form_Talo te">id="form_Talote"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identResp4" id="identResp4a" autocomplete="off" >
                                <label class="btn btn-outline-primary verificadores" for="identResp4a">Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identResp4" id="identResp4b" autocomplete="off">
                                <label class="btn btn-outline-primary verificadores" for="identResp4b">No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identVB4" id="identVB4a" autocomplete="off" >
                                <label class="btn btn-outline-primary verificadores" for="identVB4a">Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identVB4" id="identVB4b" autocomplete="off">
                                <label class="btn btn-outline-primary verificadores" for="identVB4b">No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo" >5. Código Interno:</td>
                        <td class="formulario" id="form_Codi go">id="form_Codigo"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identResp5" id="identResp5a" autocomplete="off" >
                                <label class="btn btn-outline-primary verificadores" for="identResp5a">Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identResp5" id="identResp5b" autocomplete="off">
                                <label class="btn btn-outline-primary verificadores" for="identResp5b">No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identVB5" id="identVB5a" autocomplete="off" >
                                <label class="btn btn-outline-primary verificadores" for="identVB5a">Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identVB5" id="identVB5b" autocomplete="off">
                                <label class="btn btn-outline-primary verificadores" for="identVB5b">No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo" >6. Cond. Almacenamiento:</td>
                        <td class="formulario" id="form_Cond icion">id="form_Condicion"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identResp6" id="identResp6a" autocomplete="off" >
                                <label class="btn btn-outline-primary verificadores" for="identResp6a">Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identResp6" id="identResp6b" autocomplete="off">
                                <label class="btn btn-outline-primary verificadores" for="identResp6b">No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identVB6" id="identVB6a" autocomplete="off" >
                                <label class="btn btn-outline-primary verificadores" for="identVB6a">Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identVB6" id="identVB6b" autocomplete="off">
                                <label class="btn btn-outline-primary verificadores" for="identVB6b">No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo" >7. Cantidad Muestra:</td>
                        <td class="formulario" id="form_Cant muestra">id="form_Cantmuestra"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identResp7" id="identResp7a" autocomplete="off" >
                                <label class="btn btn-outline-primary verificadores" for="identResp7a">Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identResp7" id="identResp7b" autocomplete="off">
                                <label class="btn btn-outline-primary verificadores" for="identResp7b">No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identVB7" id="identVB7a" autocomplete="off" >
                                <label class="btn btn-outline-primary verificadores" for="identVB7a">Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identVB7" id="identVB7b" autocomplete="off">
                                <label class="btn btn-outline-primary verificadores" for="identVB7b">No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo" >8. Cantidad Contramuestra:</td>
                        <td class="formulario" id="form_Cant contramuestra">id="form_Cantcontramuestra"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identResp8" id="identResp8a" autocomplete="off" >
                                <label class="btn btn-outline-primary verificadores" for="identResp8a">Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identResp8" id="identResp8b" autocomplete="off">
                                <label class="btn btn-outline-primary verificadores" for="identResp8b">No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identVB8" id="identVB8a" autocomplete="off" >
                                <label class="btn btn-outline-primary verificadores" for="identVB8a">Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identVB8" id="identVB8b" autocomplete="off">
                                <label class="btn btn-outline-primary verificadores" for="identVB8b">No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="formulario-titulo" >9. Tipo de Análisis:</td>
                        <td class="formulario" id="form_tipo analisis" >id="form_tipoanalisis"</td>
                        <td class="spacer"></td>
                        <td class="formulario resp">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identResp9" id="identResp9a" autocomplete="off" >
                                <label class="btn btn-outline-primary verificadores" for="identResp9a">Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identResp9" id="identResp9b" autocomplete="off">
                                <label class="btn btn-outline-primary verificadores" for="identResp9b">No Cumple</label>
                            </div>
                        </td>
                        <td class="spacer"></td>
                        <td class="formulario verif">
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check verificadores" name="identVB9" id="identVB9a" autocomplete="off" >
                                <label class="btn btn-outline-primary verificadores" for="identVB9a">Cumple</label>
                                <input type="radio" class="btn-check verificadores" name="identVB9" id="identVB9b" autocomplete="off">
                                <label class="btn btn-outline-primary verificadores" for="identVB9b">No Cumple</label>
                            </div>
                        </td>
                    </tr>
                </table>
                <br>
                <br>


            </section>
            <h2 style="font-size: 14px; font-weight: bold; margin-bottom: 10px;">II. MUESTREO</h2>


            <!-- Sección II: MUESTREO -->
            <section id="sample-identification"
                style="display: flex; justify-content: space-between; align-items: stretch; gap: 5px;">
                <!-- Tabla de identificación de la muestra -->
                <table style="border-collapse: collapse; width: 70%;">
                    <tr>
                        <td style="border: none;" colspan="4"></td> <!-- Espacio antes del encabezado -->
                        <td style="font-size: 14px; text-align: center; font-weight: bold;" colspan="1">Rspn.</td>
                        <!-- Encabezado "Solos" -->
                        <td style="border: none; width: 1px;"></td> <!-- Espaciador -->
                        <td style="font-size: 14px; text-align: center; font-weight: bold;" colspan="1">V°B°</td>
                        <!-- Encabezado "Solos2" -->
                    </tr>
                    <tr>
                        <td style="font-size: 11px; width: 60%;" colspan="2">1. La zona de esterilización se encuentra
                            limpia y ordenada.</td>
                        <td class="formulario" id="form_1" colspan="1" style="border: none; width: 1px;">
                            <!-- CheckBoxes para Conforme y No Conforme -->
                            <input type="checkbox" name="estado_Pro" value="Conforme">
                            <label for="conforme_Pro">Conforme</label>
                            <input type="checkbox" name="estado_Pro" value="No Conforme">
                            <label for="noConforme_Pro">No Conforme</label>
                        </td>
                        <td style="border: none; width: 1px;"></td>
                        <!-- <td id="Rspn_Pro" class="Rspn" colspan="1">id="Rspn_Pro"</td>
                        -->
                        <td>
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="muestreoResp1" id="muestreoResp1a" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="muestreoResp1a">Cumple</label>
                                <input type="radio" class="btn-check" name="muestreoResp1" id="muestreoResp1b" autocomplete="off">
                                <label class="btn btn-outline-primary" for="muestreoResp1b">No Cumple</label>
                            </div>
                        </td>
                        <td style="border: none; width: 1px;"></td>
                        <td>
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="muestreoVB1" id="muestreoVB1a" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="muestreoVB1a">Cumple</label>
                                <input type="radio" class="btn-check" name="muestreoVB1" id="muestreoVB1b" autocomplete="off">
                                <label class="btn btn-outline-primary" for="muestreoVB1b">No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size: 11px; width: 50%;" colspan="2">2. Verificar que la zona de muestreo se
                            encuentre libre de otros productos.</td>
                        <td class="formulario" id="form_2" colspan="1" style="border: none; width: 1px;">
                            <!-- CheckBoxes para Conforme y No Conforme -->
                            <input type="checkbox" name="estado_Pro" value="Conforme">
                            <label for="conforme_Pro">Conforme</label>
                            <input type="checkbox" name="estado_Pro" value="No Conforme">
                            <label for="noConforme_Pro">No Conforme</label>
                        </td>
                        <td style="border: none; width: 1px;"></td>
                        <td>
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="muestreoResp2" id="muestreoResp2a" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="muestreoResp2a">Cumple</label>
                                <input type="radio" class="btn-check" name="muestreoResp2" id="muestreoResp2b" autocomplete="off">
                                <label class="btn btn-outline-primary" for="muestreoResp2b">No Cumple</label>
                            </div>
                        </td>
                        <td style="border: none; width: 1px;"></td>
                        <td>
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="muestreoVB2" id="muestreoVB2a" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="muestreoVB2a">Cumple</label>
                                <input type="radio" class="btn-check" name="muestreoVB2" id="muestreoVB2b" autocomplete="off">
                                <label class="btn btn-outline-primary" for="muestreoVB2b">No Cumple</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size: 11px; width: 50%;" colspan="2">3. Evaluar el aspecto del producto en zona
                            de revisión.</td>
                        <td class="formulario" id="form_3" colspan="1" style="border: none; width: 1px;">
                            <!-- CheckBoxes para Conforme y No Conforme -->
                            <input type="checkbox" name="estado_Pro" value="Conforme">
                            <label for="conforme_Pro">Conforme</label>
                            <input type="checkbox" name="estado_Pro" value="No Conforme">
                            <label for="noConforme_Pro">No Conforme</label>
                        </td>
                        <td style="border: none; width: 1px;"></td>
                        <td>
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="muestreoResp3" id="muestreoResp3a" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="muestreoResp3a">Cumple</label>
                                <input type="radio" class="btn-check" name="muestreoResp3" id="muestreoResp3b" autocomplete="off">
                                <label class="btn btn-outline-primary" for="muestreoResp3b">No Cumple</label>
                            </div>
                        </td>
                        <td style="border: none; width: 1px;"></td>
                        <td>
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="muestreoVB3" id="muestreoVB3a" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="muestreoVB3a">Cumple</label>
                                <input type="radio" class="btn-check" name="muestreoVB3" id="muestreoVB3b" autocomplete="off">
                                <label class="btn btn-outline-primary" for="muestreoVB3b">No Cumple</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 11px; width: 50%;" colspan="2">4. Verificar correcta identificación del
                            lote y producto.</td>
                        <td class="formulario" id="form_4" colspan="1" style="border: none; width: 1px;">
                            <!-- CheckBoxes para Conforme y No Conforme -->
                            <input type="checkbox" name="estado_Pro" value="Conforme">
                            <label for="conforme_Pro">Conforme</label>
                            <input type="checkbox" name="estado_Pro" value="No Conforme">
                            <label for="noConforme_Pro">No Conforme</label>
                        </td>
                        <td style="border: none; width: 1px;"></td>
                        <td>
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="muestreoResp4" id="muestreoResp4a" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="muestreoResp4a">Cumple</label>
                                <input type="radio" class="btn-check" name="muestreoResp4" id="muestreoResp4b" autocomplete="off">
                                <label class="btn btn-outline-primary" for="muestreoResp4b">No Cumple</label>
                            </div>
                        </td>
                        <td style="border: none; width: 1px;"></td>
                        <td>
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="muestreoVB4" id="muestreoVB4a" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="muestreoVB4a">Cumple</label>
                                <input type="radio" class="btn-check" name="muestreoVB4" id="muestreoVB4b" autocomplete="off">
                                <label class="btn btn-outline-primary" for="muestreoVB4b">No Cumple</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 11px;" colspan="2">5. Cantidad de ciclos de esterilización</td>
                        <td class="formulario" id="form_5" colspan="1">id="form_5"</td>
                        <td style="border: none; width: 1px;"></td>
                        <td>
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="muestreoResp5" id="muestreoResp5a" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="muestreoResp5a">Cumple</label>
                                <input type="radio" class="btn-check" name="muestreoResp5" id="muestreoResp5b" autocomplete="off">
                                <label class="btn btn-outline-primary" for="muestreoResp5b">No Cumple</label>
                            </div>
                        </td>
                        <td style="border: none; width: 1px;"></td>
                        <td>
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="muestreoVB5" id="muestreoVB5a" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="muestreoVB5a">Cumple</label>
                                <input type="radio" class="btn-check" name="muestreoVB5" id="muestreoVB5b" autocomplete="off">
                                <label class="btn btn-outline-primary" for="muestreoVB5b">No Cumple</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 11px;" colspan="2">6. Cantidad bandejas esterilizadas por ciclo</td>
                        <td class="formulario" id="form_6" colspan="1">id="form_6"</td>
                        <td style="border: none; width: 1px;"></td>
                        <td>
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="muestreoResp6" id="muestreoResp6a" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="muestreoResp6a">Cumple</label>
                                <input type="radio" class="btn-check" name="muestreoResp6" id="muestreoResp6b" autocomplete="off">
                                <label class="btn btn-outline-primary" for="muestreoResp6b">No Cumple</label>
                            </div>
                        </td>
                        <td style="border: none; width: 1px;"></td>
                        <td>
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="muestreoVB6" id="muestreoVB6a" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="muestreoVB6a">Cumple</label>
                                <input type="radio" class="btn-check" name="muestreoVB6" id="muestreoVB6b" autocomplete="off">
                                <label class="btn btn-outline-primary" for="muestreoVB6b">No Cumple</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 11px;" colspan="2">7. Cantidad de muestras por bandeja</td>
                        <td class="formulario" id="form_7" colspan="1">id="form_7"</td>
                        <td style="border: none; width: 1px;"></td>
                        <td>
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="muestreoResp7" id="muestreoResp7a" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="muestreoResp7a">Cumple</label>
                                <input type="radio" class="btn-check" name="muestreoResp7" id="muestreoResp7b" autocomplete="off">
                                <label class="btn btn-outline-primary" for="muestreoResp7b">No Cumple</label>
                            </div>
                        </td>
                        <td style="border: none; width: 1px;"></td>
                        <td>
                            <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="muestreoVB7" id="muestreoVB7a" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="muestreoVB7a">Cumple</label>
                                <input type="radio" class="btn-check" name="muestreoVB7" id="muestreoVB7b" autocomplete="off">
                                <label class="btn btn-outline-primary" for="muestreoVB7b">No Cumple</label>
                            </div>
                        </td>
                    </tr>


                </table>


                <!-- Espacio para la etiqueta de identificación general de la muestra -->
                <div
                    style="display: flex; flex-direction: column; justify-content: center; border: 1px solid #000; width: 30%; text-align: center;">
                    <p style="font-size: 14px; margin-bottom: 0; font-weight: bold;">Rótulo General de Muestra:</p>
                    <div
                        style="border-top: 1px dotted #000; flex-grow: 1; display: flex; align-items: center; justify-content: center;">
                        Pegar etiqueta de identificación general de la muestra
                    </div>
                </div>


            </section>
            <div style="margin-top: 10px;">
                <label for="form_Inusual" style="font-size: 11px;">8. Registrar cualquier situación inesperada o inusual
                    durante el proceso:</label>
                <textarea id="form_Inusual" name="form_Inusual" rows="3" style="width: 100%;"></textarea>
            </div>








        <!-- Sección III: Plan de Muestreo -->
        <section id="sampling-plan">
            <h2 style="font-size: 14px; font-weight: bold; margin-bottom: 10px;">III. PLAN DE MUESTREO</h2>
            <table id="seccion3"style="width:100%; border-collapse: collapse;">
                <!-- Encabezados de la tabla -->
                <tr>
                    <th>Tamaño de Lote</th>
                    <th>Muestra</th>
                    <th>Contramuestra</th>
                    <th>Total</th>
                    <th>Revisión Revisor</th>
                    <th>Revisión Verificador</th>
                </tr>
                <!-- Fila para lotes de <= 500 unidades -->
                <tr style=" border-bottom: 1px solid #000;border-left: 1px solid;border-right: 1px solid;">
                    <td>&le; 500 unidades</td>
                    <td>40 unidades</td>
                    <td>80 unidades</td>
                    <td>120 Unidades</td>
                    <td>
                        <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" name="planResp1" id="planResp1a" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="planResp1a">Cumple</label>
                            <input type="radio" class="btn-check" name="planResp1" id="planResp1b" autocomplete="off">
                            <label class="btn btn-outline-primary" for="planResp1b">No Cumple</label>
                        </div>
                    </td>

                    <td>
                        <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" name="planVB1" id="planVB1a" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="planVB1a">Cumple</label>
                            <input type="radio" class="btn-check" name="planVB1" id="planVB1b" autocomplete="off">
                            <label class="btn btn-outline-primary" for="planVB1b">No Cumple</label>
                        </div>
                    </td>
                </tr>
                <!-- Fila para lotes entre 501 y 4999 unidades -->
                <tr style=" border-bottom: 1px solid #000;border-left: 1px solid;border-right: 1px solid;">
                    <td>501 - 4999 unidades</td>
                    <td>40 unidades</td>
                    <td>80 unidades</td>
                    <td>420 Unidades</td>
                    <td>
                        <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" name="planResp2" id="planResp2a" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="planResp2a">Cumple</label>
                            <input type="radio" class="btn-check" name="planResp2" id="planResp2b" autocomplete="off">
                            <label class="btn btn-outline-primary" for="planResp2b">No Cumple</label>
                        </div>
                    </td>

                    <td>
                        <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" name="planVB2" id="planVB2a" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="planVB2a">Cumple</label>
                            <input type="radio" class="btn-check" name="planVB2" id="planVB2b" autocomplete="off">
                            <label class="btn btn-outline-primary" for="planVB2b">No Cumple</label>
                        </div>
                    </td>
                </tr>
                <!-- Fila para lotes de >= 1000 unidades -->
                <tr style=" border-bottom: 1px solid #000;border-left: 1px solid;border-right: 1px solid;">
                    <td>&ge; 1000 unidades</td>
                    <td>50 unidades</td>
                    <td>100 unidades</td>
                    <td>150 Unidades</td>
                    <td>
                        <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" name="planResp3" id="planResp3a" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="planResp3a">Cumple</label>
                            <input type="radio" class="btn-check" name="planResp3" id="planResp3b" autocomplete="off">
                            <label class="btn btn-outline-primary" for="planResp3b">No Cumple</label>
                        </div>
                    </td>

                    <td>
                        <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" name="planVB3" id="planVB3a" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="planVB3a">Cumple</label>
                            <input type="radio" class="btn-check" name="planVB3" id="planVB3b" autocomplete="off">
                            <label class="btn btn-outline-primary" for="planVB3b">No Cumple</label>
                        </div>
                    </td>
                </tr>
            </table>
        </section>

        <!-- Footer -->
        <footer style="width: 100%; text-align: center; margin-top: 20px;bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 0 10%;">
                <!-- Área para la firma del responsable del muestreo -->
                <div style="border: 1px dotted #000; padding: 10px; width: 40%;">
                    Firma Responsable Muestreo
                </div>
                <!-- Área para la firma del verificador del muestreo -->
                <div style="border: 1px dotted #000; padding: 10px; width: 40%;">
                    Firma Verificador Muestreo
                </div>
            </div>
            <!-- Nota de confidencialidad -->
            <p style="margin-top: 10px;font-size: 10px;padding-bottom: 10px;">
                La información contenida en esta acta es de carácter CONFIDENCIAL y es considerada SECRETO
                INDUSTRIAL.
            </p>
        </footer>



    </div>
    <div class="button-container">
        <button id="download-pdf">Descargar PDF</button>
    </div>

</body>

</html>