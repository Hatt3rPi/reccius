<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantalla 9 - Vista General</title>
    <link rel="stylesheet" href="../assets/css/Ingreso_OC.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Feature Flags Configuration -->
    <script src="../../assets/js/features_customware.js"></script>
</head>
<body>
    <!-- Control de acceso por feature flag -->
    <script>
    if (typeof AppConfig === 'undefined' || !AppConfig.FLAGS.experimental_produccion) {
        document.body.innerHTML = '';
        document.write(`
            <div style="font-family: Arial, sans-serif; text-align: center;background-color: #f8f8f8;">
                <h1 style="color: #333; text-align: center;">¡Estamos trabajando en ello!</h1>
                <p style="color: #555;text-align: center;">Gracias por tu interés en esta nueva función. Nuestro equipo está trabajando arduamente para ofrecerte una experiencia aún mejor. Esta característica estará disponible muy pronto.</p>
                <p style="color: #555;text-align: center;">Te pedimos un poco de paciencia mientras preparamos todo para que disfrutes de esta nueva funcionalidad al máximo. ¡Estamos emocionados por compartirla contigo!</p>
                <p style="color: #555;text-align: center;">Mientras tanto, no dudes en explorar el resto de nuestras increíbles herramientas y servicios. ¡Hay mucho más por descubrir!</p>
            </div>
        `);
    }
    </script>
    <div id="form-container">
        <!-- Título de la categoría Vista General -->
        <h2>Vista General</h2>
        
        <!-- Contenedor de datos principales -->
        <div id="container-1">
            <div class="column">
                <div class="form-group">
                    <label for="resumen-general">Resumen General:</label>
                    <input type="text" id="resumen-general" name="resumen-general" value="(cargado desde BE)" class="input-disabled" disabled>
                </div>
            </div>
        </div>
    </div>
</body>
</html>