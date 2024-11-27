<?php
session_start();

// Verificar si la variable de sesión "usuario" no está establecida o está vacía.
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    // Redirigir al usuario a la página de inicio de sesión.
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso OC</title>
    <link rel="stylesheet" href="../assets/css/DocumentoEspecs.css">
    <link rel="stylesheet" href="../assets/css/Notificacion.css">
    <script src="../assets/js/notify.js"></script>
</head>

<body>
    <div id="form-container" class="ContenedorP">
        <div id="Maincontainer">
            <!-- Encabezado -->
            <div id="header-container" style="width: 100%; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1>Ingreso Orden de Compra (OC)</h1>
            </div>

            <!-- Primer Contenedor -->
            <div id="container-1" style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 30px;">
                <!-- Casilla 1 -->
                <div class="form-group" style="flex: 1 1 45%; display: flex; flex-direction: column;">
                    <label for="campo1">Campo 1</label>
                    <input type="text" id="campo1" name="campo1" class="form-control">
                </div>
                <!-- Casilla 2 -->
                <div class="form-group" style="flex: 1 1 45%; display: flex; flex-direction: column;">
                    <label for="campo2">Campo 2</label>
                    <input type="text" id="campo2" name="campo2" class="form-control">
                </div>
                <!-- Casilla 3 -->
                <div class="form-group" style="flex: 1 1 45%; display: flex; flex-direction: column;">
                    <label for="campo3">Campo 3</label>
                    <input type="text" id="campo3" name="campo3" class="form-control">
                </div>
                <!-- Casilla 4 -->
                <div class="form-group" style="flex: 1 1 45%; display: flex; flex-direction: column;">
                    <label for="campo4">Campo 4</label>
                    <input type="text" id="campo4" name="campo4" class="form-control">
                </div>
                <!-- Casilla 5 -->
                <div class="form-group" style="flex: 1 1 45%; display: flex; flex-direction: column;">
                    <label for="campo5">Campo 5</label>
                    <input type="text" id="campo5" name="campo5" class="form-control">
                </div>
                <!-- Casilla 6 -->
                <div class="form-group" style="flex: 1 1 45%; display: flex; flex-direction: column;">
                    <label for="campo6">Campo 6</label>
                    <input type="text" id="campo6" name="campo6" class="form-control">
                </div>
                <!-- Casilla 7 -->
                <div class="form-group" style="flex: 1 1 45%; display: flex; flex-direction: column;">
                    <label for="campo7">Campo 7</label>
                    <input type="text" id="campo7" name="campo7" class="form-control">
                </div>
                <!-- Casilla 8 -->
                <div class="form-group" style="flex: 1 1 45%; display: flex; flex-direction: column;">
                    <label for="campo8">Campo 8</label>
                    <input type="text" id="campo8" name="campo8" class="form-control">
                </div>
            </div>

            <!-- Segundo Contenedor -->
            <div id="container-2" style="border: 1px solid #ccc; padding: 20px; height: 200px; margin-top: 30px;">
                <p>Este contenedor está vacío por el momento.</p>
            </div>
        </div>
    </div>

    <div class="button-container" style="margin-top: 20px; text-align: center;">
        <button class="ingControl" id="save-oc" style="margin-right: 10px;">Guardar OC</button>
        <button class="ingControl" id="cancel-oc">Cancelar</button>
    </div>
</body>

</html>
