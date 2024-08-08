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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</head>
<body>
    <h1>Imágenes desde Cloudflare R2</h1>
    <div id="content">
        <img id="qr-certificado" src="https://pub-4017b86f75d04838b6e805cbb3235b10.r2.dev/certificados_qr/qr_documento_fabarca212_1722967694.png" alt="QR Certificado" crossorigin="anonymous" />
        <img id="otra-imagen" src="https://pub-bde9ff3e851b4092bfe7076570692078.r2.dev/APROBADO.webp" alt="Otra Imagen" crossorigin="anonymous" />
    </div>

    <button id="download-pdf">Descargar PDF</button>

    <script>
        document.getElementById('download-pdf').addEventListener('click', function() {
            const content = document.getElementById('content');
            
            html2canvas(content, {
                useCORS: true,
                allowTaint: false,
                logging: true,
                scale: 2
            }).then(canvas => {
                const imgData = canvas.toDataURL('image/jpeg');
                const pdf = new jspdf.jsPDF();
                const imgWidth = 210; // Width of A4 page in mm
                const pageHeight = 297; // Height of A4 page in mm
                const imgHeight = canvas.height * imgWidth / canvas.width;
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

                pdf.save('documento.pdf');
            }).catch(error => {
                console.error('Error capturando la imagen:', error);
            });
        });
    </script>
</body>
</html>
