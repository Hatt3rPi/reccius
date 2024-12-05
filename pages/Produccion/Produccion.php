<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantalla 5 - Producción</title>
    <link rel="stylesheet" href="../assets/css/Ingreso_OC.css">
</head>
<body>
    <div id="form-container">
        <!-- Título de la categoría Producción -->
        <h2>Producción</h2>
        
        <!-- Contenedor de datos principales -->
        <div id="container-1">
            <div class="column">
                <div class="form-group">
                    <label for="fecha-revision">Fecha Revisión:</label>
                    <input type="text" id="fecha-revision" name="fecha-revision" value="(automática)" disabled>
                </div>
                <div class="form-group">
                    <label for="medio-despacho">Medio Despacho:</label>
                    <input type="text" id="medio-despacho" name="medio-despacho">
                </div>
                <div class="form-group">
                    <label for="dia-retiro">Día Retiro:</label>
                    <input type="text" id="dia-retiro" name="dia-retiro" value="(automática según regla)" disabled>
                </div>
                <div class="form-group">
                    <label for="hora-retiro">Hora Retiro:</label>
                    <input type="text" id="hora-retiro" name="hora-retiro" value="(automática según regla)" disabled>
                </div>
            </div>
            <div class="column">
                <div class="form-group">
                    <label for="mismo-retiro">¿Mismo retiro para todos los productos?:</label>
                    <input type="text" id="mismo-retiro" name="mismo-retiro" value="Sí/No" disabled>
                </div>
                <div class="form-group">
                    <label for="numero-producto"># Producto:</label>
                    <input type="text" id="numero-producto" name="numero-producto" value="1 (automática)" disabled>
                </div>
                <div class="form-group">
                    <label for="producto">Producto:</label>
                    <input type="text" id="producto" name="producto" value="(cargado desde BE)" disabled>
                </div>
                <div class="form-group">
                    <label for="area-fabricacion">Área Fabricación:</label>
                    <input type="text" id="area-fabricacion" name="area-fabricacion" value="(cargado desde BE)" disabled>
                </div>
            </div>
        </div>

        <!-- Contenedor de comentarios -->
        <div id="comments-container">
            <h2>Ingresar Comentario</h2>
            <div class="form-group">
                <input type="text" id="comment" name="comment" placeholder="Escribe tu comentario aquí">
            </div>
        </div>

        <!-- Estado -->
        <div id="status-container">
            <h2>Estado:</h2>
            <div class="form-group">
                <span style="color: white; background-color: red; padding: 10px; border-radius: 4px;">
                    3. Listo para facturar
                </span>
            </div>
        </div>
    </div>
</body>
</html>
