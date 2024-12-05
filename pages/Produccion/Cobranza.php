<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantalla 8 - Cobranza</title>
    <link rel="stylesheet" href="../assets/css/Ingreso_OC.css">
</head>
<body>
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
