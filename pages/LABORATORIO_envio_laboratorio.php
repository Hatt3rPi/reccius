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
    <title>Ingreso de resultados</title>
    <link rel="stylesheet" href="../assets/css/DocumentoAna.css?<?php echo time(); ?>">
</head>

<body>
    <div id="form-container" class="form-container formpadding" style="margin: 0 auto;">
    <h1>CALIDAD / Envío de resultados a laboratorio</h1>
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
                <div class="form-row">
                    <div class="form-group">
                        <label for="destinatario1_email">Email destinatario 1:</label>
                        <input type="email" id="destinatario1_email" name="destinatarios[0][email]" class="form-control mx-0 w-90" placeholder="Email destinatario 1" required>
                    </div>
                    <div class="form-group">
                        <label for="destinatario1_nombre">Nombre destinatario 1:</label>
                        <input type="text" id="destinatario1_nombre" name="destinatarios[0][nombre]" class="form-control mx-0 w-90" placeholder="Nombre destinatario 1" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="destinatario2_email">Email destinatario 2:</label>
                        <input type="email" id="destinatario2_email" name="destinatarios[1][email]" class="form-control mx-0 w-90" placeholder="Email destinatario 2">
                    </div>
                    <div class="form-group">
                        <label for="destinatario2_nombre">Nombre destinatario 2:</label>
                        <input type="text" id="destinatario2_nombre" name="destinatarios[1][nombre]" class="form-control mx-0 w-90" placeholder="Nombre destinatario 2">
                    </div>
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

        $(document).ready(function() {
            loadData();

            $('#enviarCorreo').on('click', function() {
                enviarCorreo();
            });
        });

        function loadData() {
            fetch('./backend/analisis/ingresar_resultados_analisis.php?id_acta=<?php echo json_encode($_POST['id'] ?? ''); ?>', {
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

        function enviarCorreo() {
            var destinatarios = [];

            if ($('#destinatario1_email').val() && $('#destinatario1_nombre').val()) {
                destinatarios.push({
                    email: $('#destinatario1_email').val(),
                    nombre: $('#destinatario1_nombre').val()
                });
            }

            if ($('#destinatario2_email').val() && $('#destinatario2_nombre').val()) {
                destinatarios.push({
                    email: $('#destinatario2_email').val(),
                    nombre: $('#destinatario2_nombre').val()
                });
            }

            var data = {
                id_analisis_externo: $('#id_analisis_externo').val(),
                destinatarios: destinatarios
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