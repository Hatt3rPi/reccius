<?php
session_start();

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Envío de solicitud a laboratorio</title>
    <link rel="stylesheet" href="../assets/css/DocumentoAna.css?<?php echo time(); ?>">
</head>

<body>
    <div id="form-container-mail" class="form-container formpadding" style="margin: 0 auto;">
        <h3>Envío de solicitud a laboratorio externo</h3>
        <form id="envioCorreoForm" name="envioCorreoForm">
            <fieldset>
                <legend>I. Información general</legend>
                <br>
                <div class="form-row destinatario-row justify-content-start align-items-center gap-2">
                    <div class="form-group" style="width: 300px;">
                        <label for="laboratorio">Laboratorio de Analista:</label>
                        <input type="text" id="laboratorio" name="laboratorio" class="form-control mx-0 w-90" readonly required>
                    </div>
                    <div class="form-group" style="width: 300px;">
                        <label for="fecha_registro">Fecha de solicitud:</label>
                        <input type="text" id="fecha_registro" name="fecha_registro" class="form-control mx-0 w-90" readonly required>
                    </div>
                </div>
                <div class="form-row destinatario-row justify-content-start align-items-center gap-2">
                    <div class="form-group" style="width: 300px;">
                        <label for="numero_registro">N° de registro:</label>
                        <input type="text" id="numero_registro" name="numero_registro" class="form-control mx-0 w-90" readonly required>
                    </div>
                    <div class="form-group" style="width: 300px;">
                        <label for="numero_solicitud">N° de Solicitud:</label>
                        <input type="text" id="numero_solicitud" name="numero_solicitud" class="form-control mx-0 w-90" readonly required>
                    </div>
                </div>
            </fieldset>
            <br>
            <fieldset>
                <legend>II. Destinatarios</legend>
                <br>
                <div id="destinatarios-container-lab">
                    <div class="form-row destinatario-row justify-content-start align-items-center gap-2">
                        <div class="form-group" style="width: 300px;">
                            <label for="name_lab">Laboratorio:</label>
                            <input type="text" id="name_lab" name="name_lab" class="form-control mx-0 w-90" placeholder="Nombre laboratorio" readonly required>
                        </div>
                        <div class="form-group" style="width: 300px;">
                            <label for="mail_lab">Correo laboratorio:</label>
                            <input type="text" id="mail_lab" name="mail_lab" class="form-control mx-0 w-90" placeholder="Correo laboratorio" required>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>III. Destinatarios con copia</legend>
                <br>
                <div id="destinatarios-container">
                    <div class="form-row destinatario-row justify-content-start align-items-center gap-2">
                        <div class="form-group" style="width: 300px;">
                            <label for="mail_solicitante">Solicitante:</label>
                            <input type="text" id="mail_solicitante" name="mail_solicitante" class="form-control mx-0 w-90" readonly required>
                        </div>
                        <div class="form-group" style="width: 300px;">
                            <label for="mail_revisor">Revisor:</label>
                            <input type="text" id="mail_revisor" name="mail_revisor" class="form-control mx-0 w-90" readonly required>
                        </div>
                    </div>
                    <div class="form-row destinatario-row justify-content-start align-items-center gap-2">
                        <div class="form-group" style="width: 300px;">
                            <label for="name_lab">Con copia a:</label>
                            <input type="text" value="mgodoy@reccius.cl" class="form-control mx-0 w-90" placeholder="Nombre laboratorio" readonly>
                        </div>
                        <div class="form-group" style="width: 300px;">
                        </div>
                    </div>
                    <div class="form-row destinatario-row justify-content-start align-items-center gap-2">
                        <div class="form-group" style="width: 300px;">
                            <label for="destinatario1_nombre">Nombre <span class="order_span_name">1</span>:</label>
                            <input type="text" id="destinatario1_nombre" name="destinatarios[0][nombre]" class="form-control mx-0 w-90" placeholder="Nombre destinatario 1" required>
                        </div>
                        <div class="form-group" style="width: 300px;">
                            <label for="destinatario1_email">Email <span class="order_span_mail">1</span>:</label>
                            <input type="email" id="destinatario1_email" name="destinatarios[0][email]" class="form-control mx-0 w-90" placeholder="Email destinatario 1" required>
                        </div>
                        <button type="button" class="remove-destinatario btn btn-danger">Eliminar</button>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" id="add-destinatario">Agregar destinatario</button>
            </fieldset>
            <br>
            <fieldset>
                <legend>IV. Asunto</legend>
                <br>
                <div class="form-row justify-content-start align-items-center">
                    <input id="subject" style="width: 600px;" name="subject" class="form-control"></input>
                    <style>
                        .ck.ck-editor {
                            min-width: 600px;
                        }

                        .ck ck-editor__main {
                            padding: auto !important;
                        }
                    </style>
                </div>
                <br>
            </fieldset>
            <fieldset>
                <legend>V. Cuerpo</legend>
                <br>
                <div class="form-row justify-content-start align-items-center">
                    <textarea id="editor" style="width: 600px; min-height: 300px;" name="cuerpo"></textarea>
                    <style>
                        .ck.ck-editor {
                            min-width: 600px;
                        }

                        .ck ck-editor__main {
                            padding: auto !important;
                        }
                    </style>
                </div>
            </fieldset>
            <br>
            <input type="hidden" id="id_analisis_externo" name="id_analisis_externo">
            <div id="buttonContainer" class="button-container">
            </div>
        </form>
        <div class="modal" id="modalInfo">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" id="modalContent">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Verificación de documentos</h5>
                        <button type="button" class="close" id="closeModal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Solicitud de análisis externo:<span id="analisisExternoExiste">✅⛔</span> </p>
                        <p>Solicitud acta de muestreo: <span id="actaMuestreoExiste">✅⛔</span> </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var idAnalisisExterno = <?php echo json_encode($_POST['id'] ?? ''); ?>;
        var destinatarioCount = 1;
        var editorInstance;
        var destinatarios = [];
        var destinatariosSolicitanteRevision = [];

        $(document).ready(function() {

            $('#add-destinatario').on('click', function() {
                addDestinatario();
            });

            $(document).on('click', '.remove-destinatario', function() {
                $(this).closest('.destinatario-row').remove();
                updateDestinatarioNames();
                updateNumberDestinatario();
            });

            import('ckeditor5').then(({
                ClassicEditor,
                Essentials,
                Bold,
                Italic,
                Font,
                Paragraph,
                List,
                Link
            }) => {
                ClassicEditor
                    .create(document.querySelector('#editor'), {
                        plugins: [Essentials, Bold, Italic, Font, Paragraph, List, Link],
                        toolbar: {
                            items: [
                                'undo', 'redo', '|',
                                'bold', 'italic', 'link', 'fontSize', '|',
                                'numberedList', 'bulletedList', '|'
                            ]
                        }
                    })
                    .then(editor => {
                        editorInstance = editor;
                        loadData();
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });
            $('#envioCorreoForm').on('submit', function(e) {
                e.preventDefault();
                enviarCorreo();
            })
        });

        function loadData() {
            fetch('./backend/laboratorio/enviar_solicitud_carga.php?id_acta=' + idAnalisisExterno, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    var {
                        acta,
                        analisis,
                        usuarios,
                        cc
                    } = data;

                    if (analisis) {
                        $('#laboratorio').val(analisis.laboratorio);
                        $('#numero_registro').val(analisis.numero_registro);
                        $('#numero_solicitud').val(analisis.numero_solicitud);
                        $('#fecha_registro').val(analisis.fecha_registro);
                        $('#id_analisis_externo').val(idAnalisisExterno);

                        $('#name_lab').val(analisis.laboratorio ?? '');
                        $('#mail_lab').val(analisis.correoLab ?? '');

                        $('#subject')
                            .val(`RECCIUS | Solicitud de análisis externo ${analisis.numero_documento} - ${analisis.nombre_producto}`)

                        if (analisis.url_certificado_solicitud_analisis_externo && analisis.url_certificado_acta_de_muestreo) {
                            $('#modalInfo').hide();
                            $('#buttonContainer')
                                .append('<button type="submit" class="botones" id="enviarCorreo">Enviar</button>');
                        } else {
                            $('#modalInfo').show();

                            $('#analisisExternoExiste').text(analisis.url_certificado_solicitud_analisis_externo ? '✅' : '⛔');
                            $('#actaMuestreoExiste').text(analisis.url_certificado_acta_de_muestreo ? '✅' : '⛔');

                            var acciones = '';
                            var buttonContainer = '';
                            if (!analisis.url_certificado_solicitud_analisis_externo) {
                                acciones += `<button class="btn btn-primary col-5" 
                                    type="button" 
                                    title="Análisis Externo" 
                                    name="generar_documento_pdf" 
                                    onclick="botones(${idAnalisisExterno}, this.name, 'laboratorio')"
                                    ><i class="fa-solid fa-file-pdf"></i> Análisis Externo
                                </button>`;
                                buttonContainer += `<button 
                                    type="button" 
                                    class="botones" 
                                    name="generar_documento_pdf"
                                    onclick="botones(${idAnalisisExterno}, this.name, 'laboratorio')"
                                    >Ir a guardar análisis Externo</button>`
                            }
                            if (!analisis.url_certificado_acta_de_muestreo) {
                                acciones += `<button class="btn btn-primary col-5" 
                                    type="button" 
                                    title="Acta de muestreo" 
                                    name="revisar_acta" 
                                    onclick="botones(${ acta ? acta[0].id : '' }, this.name, 'laboratorio')"
                                    ><i class="fa-solid fa-file-pdf"></i> Acta de muestreo
                                </button>`;
                                buttonContainer += `<button 
                                    type="button" 
                                    class="botones" 
                                    name="revisar_acta"
                                    onclick="botones(${ acta ? acta[0].id : '' }, this.name, 'laboratorio')"
                                    >Ir a guardar Acta de muestreo</button>`
                            }
                            $('#modalContent')
                                .append(' <div class="modal-footer gap-2 px-0 w-100 justify-content-center" id="modalFooter">' + acciones + '</div>');
                            $('#buttonContainer')
                                .append(buttonContainer);
                        }
                    }

                    if (usuarios) {
                        usuarios.forEach(element => {
                            destinatariosSolicitanteRevision.push({
                                email: element.correo,
                                nombre: element.nombre
                            })
                        });
                        $('#mail_solicitante').val(usuarios[0].correo);

                        usuarios.length > 1 ?
                            $('#mail_revisor').val(usuarios[1].correo) :
                            $('#mail_revisor').val(usuarios[0].correo);
                    }
                    // ${analisis.url_documento_adicional}
                    if (analisis && usuarios) {
                        var hoy = moment(new Date()).format('DD/MM/YYYY');
                        var bodyMail = `
                            Estimados ${analisis.laboratorio},<br/>
                            Junto con saludar, comento que con fecha ${hoy}, enviamos solicitud de análisis externo para el producto:  ${analisis.nombre_producto} - ${analisis.tipo_producto} - Lote: ${analisis.lote}<br/><br/>
                            Adjuntamos la información necesaria para ingresar a análisis:
                            <ul>
                                <li>
                                Solicitud de Análisis Externo: <a href="${analisis.url_certificado_solicitud_analisis_externo}" target="_blank" >Ver archivo</a> 
                                </li>
                                <li>
                                Solicitud Acta de Muestreo: <a href="${analisis.url_certificado_acta_de_muestreo}" target="_blank" >Ver archivo</a> 
                                </li>
                                ${analisis.url_documento_adicional ? `
                                <li>
                                Documento adicional: <a href="${analisis.url_documento_adicional}" target="_blank" >Ver archivo</a> 
                                </li>
                                ` : ''}
                            </ul>
                            <br/>
                            PD: El correo fue generado de una casilla automática. favor derivar sus respuestas a ${
                                usuarios.length > 1 ?
                                    `${usuarios[0].nombre} (${usuarios[0].correo}), ${usuarios[1].nombre} (${usuarios[1].correo})` :
                                    `${usuarios[0].nombre} (${usuarios[0].correo})`} y Macarena Alejandra Godoy Castro (mgodoy@reccius.cl).<br/>
                            Saluda atentamente,<br/>
                            Farmacéutica Reccius SPA`

                        editorInstance.setData(bodyMail);
                    }
                    if (cc && cc.length > 0) {
                        $('#destinatarios-container .destinatario-row').last().remove();
                        cc.forEach(({
                            correo,
                            id,
                            laboratorio_id,
                            name
                        }) => {
                            addDestinatario(name, correo);
                        });
                    }



                })
                .catch(error => {
                    console.error('Error:', error);
                }).finally(() => {
                    $('#closeModal').on('click', function() {
                        $('#modalInfo').remove();
                    })

                });
        }


        function addDestinatario(name = '', email = '') {
            destinatarioCount++;
            var destinatarioHtml = `
                <div class="form-row destinatario-row justify-content-start align-items-center gap-2">
                    <div class="form-group" style="width: 300px;">
                        <label for="destinatario${destinatarioCount}_nombre">Nombre <span class="order_span_name">${destinatarioCount}</span>:</label>
                        <input type="text" id="destinatario${destinatarioCount}_nombre" ${ name!=='' ? `value="${name}"` : '' } name="destinatarios[${destinatarioCount - 1}][nombre]" class="form-control mx-0 w-90" placeholder="Nombre destinatario ${destinatarioCount}" required>
                    </div>    
                    <div class="form-group" style="width: 300px;">
                        <label for="destinatario${destinatarioCount}_email">Email <span class="order_span_mail">${destinatarioCount}</span>:</label>
                        <input type="email" id="destinatario${destinatarioCount}_email" ${ email!=='' ? `value="${email}"` : '' } name="destinatarios[${destinatarioCount - 1}][email]" class="form-control mx-0 w-90" placeholder="Email destinatario ${destinatarioCount}" required>
                    </div>
                    <button type="button" class="remove-destinatario btn btn-danger">Eliminar</button>
                </div>
            `;
            $('#destinatarios-container').append(destinatarioHtml);
            updateNumberDestinatario();
        }

        function updateNumberDestinatario() {
            $('.order_span_mail').each(function(index) {
                $(this).text(index + 1);
            });
            $('.order_span_name').each(function(index) {
                $(this).text(index + 1);
            });
        }

        function updateDestinatarioNames() {
            $('#destinatarios-container .destinatario-row').each(function(index) {
                $(this).find('input').each(function() {
                    var name = $(this).attr('name');
                    var newName = name.replace(/\d+/, index);
                    $(this).attr('name', newName);
                    var id = $(this).attr('id');
                    var newId = id.replace(/\d+/, index + 1);
                    $(this).attr('id', newId);
                });
                $(this).find('label').each(function() {
                    var forAttr = $(this).attr('for');
                    var newFor = forAttr.replace(/\d+/, index + 1);
                    $(this).attr('for', newFor);
                });
            });
            destinatarioCount = $('#destinatarios-container .destinatario-row').length;
            updateNumberDestinatario();
        }

        function htmlToPlainText(html) {
            let tempDiv = document.createElement("div");
            tempDiv.innerHTML = html;

            tempDiv.querySelectorAll("a").forEach(anchor => {
                anchor.textContent = `${anchor.textContent} (${anchor.href})`;
            });

            tempDiv.querySelectorAll("ul").forEach(ul => {
                ul.outerHTML = ul.innerHTML.replace(/<li>/g, "\n&bull ").replace(/<\/li>/g, "");
            });

            tempDiv.querySelectorAll("p").forEach(p => {
                p.outerHTML = `${p.textContent}\n\n`;
            });

            return tempDiv.textContent || tempDiv.innerText || "";
        }

        function validateEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(String(email).toLowerCase());
        }

        function enviarCorreo() {
            destinatarios = [];

            $('#destinatarios-container .destinatario-row').each(function() {
                var email = $(this).find('input[type="email"]').val();
                var nombre = $(this).find('input[type="text"]').val();
                if (email && nombre && validateEmail(email)) {
                    destinatarios.push({
                        email: email,
                        nombre: nombre
                    });
                } else {
                    alert('Por favor, ingresa un correo válido y un nombre para todos los destinatarios.');
                    return false;
                }
            });
            destinatarios.push({
                email: $('#mail_lab').val(),
                nombre: $('#name_lab').val()
            });

            var htmlMessage = editorInstance.getData();
            var plainTextMessage = htmlToPlainText(htmlMessage);
            var subject = $('#subject').val();
            var data = {
                id_analisis_externo: $('#id_analisis_externo').val(),
                destinatarios: [...destinatarios, ...destinatariosSolicitanteRevision],
                subject: subject,
                mensaje: htmlMessage,
                altMesaje: plainTextMessage,
                emailLab: $('#mail_lab').val(),
            };

            fetch('./backend/laboratorio/enviar_solicitud_externa.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exito) {
                        alert('Correo enviado con éxito');
                        $('#listado_solicitudes_analisis').click();
                    } else {
                        alert('Error al enviar el correo: ' + data.mensaje);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un problema al procesar tu solicitud. Por favor, intenta de nuevo.');
                });

        }
    </script>
</body>

</html>