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
    <title>Envío de resultados a laboratorio</title>
    <link rel="stylesheet" href="../assets/css/DocumentoAna.css?<?php echo time(); ?>">
</head>

<body>
    <div id="form-container" class="form-container formpadding" style="margin: 0 auto;">
        <h3>CALIDAD / Envío de resultados a laboratorio</h3>
        <form id="envioCorreoForm" name="envioCorreoForm">
            <fieldset>
                <legend>I. Información general</legend>
                <br>
                <div class="form-row">
                    <div class="form-group">
                        <label for="laboratorio">Laboratorio Analista:</label>
                        <input type="text" id="laboratorio" name="laboratorio" class="form-control mx-0 w-90" readonly required>
                    </div>
                </div>
            </fieldset>
            <br>
            <fieldset>
                <legend>II. Destinatarios</legend>
                <br>
                <div id="destinatarios-container">
                    <div class="form-row destinatario-row justify-content-start align-items-center">
                        <div class="form-group" style="width: 300px;">
                            <label for="destinatario1_email">Email <span class="order_span_mail">1</span>:</label>
                            <input type="email" id="destinatario1_email" name="destinatarios[0][email]" class="form-control mx-0 w-90" placeholder="Email destinatario 1" required>
                        </div>
                        <div class="form-group" style="width: 300px;">
                            <label for="destinatario1_nombre">Nombre <span class="order_span_name">1</span>:</label>
                            <input type="text" id="destinatario1_nombre" name="destinatarios[0][nombre]" class="form-control mx-0 w-90" placeholder="Nombre destinatario 1" required>
                        </div>
                        <button type="button" class="remove-destinatario btn btn-danger">Eliminar</button>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" id="add-destinatario">Agregar destinatario</button>
            </fieldset>
            <fieldset>
                <legend>III. Cuerpo</legend>
                <br>
                <div class="form-row justify-content-start align-items-center">
                    <textarea id="editor"></textarea>
                </div>
            </fieldset>
            <br>
            <input type="hidden" id="id_analisis_externo" name="id_analisis_externo">
            <div class="button-container">
                <button type="button" class="botones" id="enviarCorreo">Enviar</button>
            </div>
        </form>
    </div>
    <script>
        var idAnalisisExterno = <?php echo json_encode($_POST['id'] ?? ''); ?>;
        var destinatarioCount = 1;

        $(document).ready(function() {
            loadData();

            $('#enviarCorreo').on('click', function() {
                enviarCorreo();
            });

            $('#add-destinatario').on('click', function() {
                addDestinatario();
            });

            $(document).on('click', '.remove-destinatario', function() {
                $(this).closest('.destinatario-row').remove();
                updateDestinatarioNames();
                updateNumberDestinatario();
            });

            // Inicializar CKEditor
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
                        plugins: [Essentials, Bold, Italic, Font, Paragraph],
                        toolbar: {
                            items: [
                                'undo', 'redo', '|', 'bold', 'italic', 'link', '|',
                                'numberedList', 'bulletedList', '|',
                                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                            ]
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });
        });

        function loadData() {
            fetch('./backend/analisis/ingresar_resultados_analisis.php?id_acta=' + idAnalisisExterno, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.analisis && data.analisis.length > 0) {
                        $('#laboratorio').val(data.analisis[0].laboratorio);
                        $('#id_analisis_externo').val(idAnalisisExterno);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function addDestinatario() {
            destinatarioCount++;
            var destinatarioHtml = `
                <div class="form-row destinatario-row justify-content-start align-items-center">
                    <div class="form-group" style="width: 300px;">
                        <label for="destinatario${destinatarioCount}_email">Email <span class="order_span_mail">${destinatarioCount}</span>:</label>
                        <input type="email" id="destinatario${destinatarioCount}_email" name="destinatarios[${destinatarioCount - 1}][email]" class="form-control mx-0 w-90" placeholder="Email destinatario ${destinatarioCount}" required>
                    </div>
                    <div class="form-group" style="width: 300px;">
                        <label for="destinatario${destinatarioCount}_nombre">Nombre <span class="order_span_name">${destinatarioCount}</span>:</label>
                        <input type="text" id="destinatario${destinatarioCount}_nombre" name="destinatarios[${destinatarioCount - 1}][nombre]" class="form-control mx-0 w-90" placeholder="Nombre destinatario ${destinatarioCount}" required>
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

        function enviarCorreo() {
            var destinatarios = [];

            $('#destinatarios-container .destinatario-row').each(function() {
                var email = $(this).find('input[type="email"]').val();
                var nombre = $(this).find('input[type="text"]').val();
                if (email && nombre) {
                    destinatarios.push({
                        email: email,
                        nombre: nombre
                    });
                }
            });

            var data = {
                id_analisis_externo: $('#id_analisis_externo').val(),
                destinatarios: destinatarios,
                mensaje: $('#editor').val()
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
                    } else {
                        alert('Error al enviar el correo: ' + data.mensaje);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
</body>

</html>