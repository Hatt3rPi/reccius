<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantalla 6 - Facturación</title>
    <link rel="stylesheet" href="../assets/css/Ingreso_OC.css">
</head>
<body>
    <div id="form-container">
        <!-- Título de la categoría Facturación -->
        <h2>Facturación</h2>
        
        <!-- Contenedor de datos principales -->
        <div id="container-1">
            <div class="column">
                <div class="form-group">
                    <label for="fecha-factura">Fecha Factura:</label>
                    <input type="text" id="fecha-factura" name="fecha-factura">
                </div>
                <div class="form-group">
                    <label for="numero-factura">Número Factura:</label>
                    <input type="text" id="numero-factura" name="numero-factura">
                </div>
            </div>
            <div class="column">
                <div class="form-group">
                    <label for="misma-factura">¿Misma factura para todos los productos?:</label>
                    <input type="text" id="misma-factura" name="misma-factura" value="Sí/No" class="input-disabled" disabled>
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
