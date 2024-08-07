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
    <title>Acta de Muestreo Control de Calidad</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</head>
<body>
    <h1>Imágenes desde Cloudflare R2</h1>
    <img id="qr-certificado" src="https://pub-4017b86f75d04838b6e805cbb3235b10.r2.dev/certificados_qr/qr_documento_fabarca212_1722967694.png" alt="QR Certificado" />
    <img id="otra-imagen" src="https://pub-4017b86f75d04838b6e805cbb3235b10.r2.dev/otra_imagen.png" alt="Otra Imagen" />

    <button id="download-pdf">Descargar PDF</button>

    <script>
        document.getElementById('download-pdf').addEventListener('click', function() {
            const { jsPDF } = window.jspdf;

            // Crear una nueva instancia de jsPDF
            const pdf = new jsPDF();

            // Cargar las imágenes
            const img1 = new Image();
            img1.crossOrigin = 'Anonymous'; // Permitir CORS
            img1.src = document.getElementById('qr-certificado').src;

            const img2 = new Image();
            img2.crossOrigin = 'Anonymous'; // Permitir CORS
            img2.src = document.getElementById('otra-imagen').src;

            img1.onload = function() {
                // Agregar la primera imagen al PDF
                pdf.addImage(img1, 'PNG', 10, 10, 180, 160);

                img2.onload = function() {
                    // Agregar la segunda imagen al PDF
                    pdf.addImage(img2, 'PNG', 10, 180, 180, 160);

                    // Guardar el PDF
                    pdf.save("documento.pdf");
                };

                img2.onerror = function() {
                    console.error('Error al cargar la segunda imagen.');
                };
            };

            img1.onerror = function() {
                console.error('Error al cargar la primera imagen.');
            };
        });
    </script>
</body>
</html>
