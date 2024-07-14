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
        <div id="Maincontainer">
            <!-- Header -->
            <div>
                <h1>Envío de resultados a laboratorio</h1>
            </div>
            <!-- Body -->
            <form id="envioCorreoForm">
                <table>
                    <tr>
                        <td class="Subtitulos" colspan="4">Información general</td>
                    </tr>
                    <tr>
                        <td class="titulo">1. Laboratorio Analista:(*)</td>
                        <td><input type="text" id="laboratorio" readonly name="laboratorio" required></td>
                    </tr>
                    <tr>
                        <td class="titulo">2. Destinatarios:</td>
                        <td>
                            <input type="text" id="destinatario1_email" placeholder="Email destinatario 1">
                            <input type="text" id="destinatario1_nombre" placeholder="Nombre destinatario 1">
                            <br>
                            <input type="text" id="destinatario2_email" placeholder="Email destinatario 2">
                            <input type="text" id="destinatario2_nombre" placeholder="Nombre destinatario 2">
                            <!-- Puedes añadir más destinatarios según sea necesario -->
                        </td>
                    </tr>
                </table>
                <input type="hidden" id="id_analisis_externo" name="id_analisis_externo">
                <button type="button" class="botones" id="enviarCorreo">Enviar</button>
            </form>
        </div>

        <div class="button-container">
            <button class="botones" id="enviar" style="display: none;">Enviar</button>
        </div>
        <script>
            var idAnalisisExterno = <?php echo json_encode($_GET['id'] ?? ''); ?>;

            $(document).ready(function() {
                loadData();

                $('#enviarCorreo').on('click', function() {
                    enviarCorreo();
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
