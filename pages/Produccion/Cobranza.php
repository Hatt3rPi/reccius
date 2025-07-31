<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantalla 8 - Cobranza</title>
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
        <!-- Título de la categoría Cobranza -->
        <h2>Cobranza</h2>
        
        <!-- Contenedor de datos principales -->
        <div id="container-1">
            <div class="column">
                <div class="form-group">
                    <label for="fecha-recepcion">Fecha Recepción Productos:</label>
                    <input type="text" id="fecha-recepcion" name="fecha-recepcion">
                </div>
            </div>
            <div class="column">
                <div class="form-group">
                    <label for="misma-recepcion">¿Misma recepción para todos los productos?:</label>
                    <input type="text" id="misma-recepcion" name="misma-recepcion" value="Sí/No" class="input-disabled" disabled>
                </div>
            </div>
        </div>

        <!-- Contenedor de comentarios -->
        <div id="comments-container">
            <h2>Ingresar Comentario</h2>
            <div class="form-group">
                <textarea id="comment" name="comment" placeholder="Escribe tu comentario aquí" rows="3"></textarea>
            </div>
        </div>
    </div>
</body>
</html>
