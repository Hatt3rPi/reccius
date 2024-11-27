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
    <link rel="stylesheet" href="../assets/css/Ingreso_OC.css">
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
                <div class="form-group">
                    <label for="campo1">Campo 1</label>
                    <input type="text" id="campo1" name="campo1" class="form-control">
                </div>
                <!-- Casilla 2 -->
                <div class="form-group">
                    <label for="campo2">Campo 2</label>
                    <input type="text" id="campo2" name="campo2" class="form-control">
                </div>
                <!-- Casilla 3 -->
                <div class="form-group">
                    <label for="campo3">Campo 3</label>
                    <input type="text" id="campo3" name="campo3" class="form-control">
                </div>
                <!-- Casilla 4 -->
                <div class="form-group">
                    <label for="campo4">Campo 4</label>
                    <input type="text" id="campo4" name="campo4" class="form-control">
                </div>
                <!-- Casilla 5 -->
                <div class="form-group">
                    <label for="campo5">Campo 5</label>
                    <input type="text" id="campo5" name="campo5" class="form-control">
                </div>
                <!-- Casilla 6 -->
                <div class="form-group">
                    <label for="campo6">Campo 6</label>
                    <input type="text" id="campo6" name="campo6" class="form-control">
                </div>
            </div>

            <!-- Botón de Agregar Producto -->
            <div id="add-product-container" style="text-align: center;">
                <button id="add-product">Agregar Producto</button>
            </div>

<!-- Contenedores de Productos -->
<div id="product-containers">
    <button id="add-product" class="btn-blue" style="float: right; margin-bottom: 20px;">Agregar Producto</button>

    <!-- Contenedor de Producto Ejemplo -->
    <div class="product-container">
        <!-- Botones superiores -->
        <div class="top-buttons">
            <button class="btn-save">Guardar</button>
            <button class="btn-edit">Editar</button>
            <button class="btn-delete">Eliminar</button>
        </div>
        <!-- Campos de Producto -->
        <div class="form-group">
            <label for="product1">Producto 1</label>
            <input type="text" id="product1" name="product1" class="form-control">
        </div>
        <div class="form-group">
            <label for="product2">Producto 2</label>
            <input type="text" id="product2" name="product2" class="form-control">
        </div>
        <div class="form-group">
            <label for="product3">Producto 3</label>
            <input type="text" id="product3" name="product3" class="form-control">
        </div>
        <div class="form-group">
            <label for="product4">Producto 4</label>
            <input type="text" id="product4" name="product4" class="form-control">
        </div>
        <div class="form-group">
            <label for="product5">Producto 5</label>
            <input type="text" id="product5" name="product5" class="form-control">
        </div>
        <div class="form-group">
            <label for="product6">Producto 6</label>
            <input type="text" id="product6" name="product6" class="form-control">
        </div>
    </div>
</div>
        </div>
    </div>

    <div class="button-container" style="margin-top: 20px; text-align: center;">
        <button class="ingControl" id="save-oc" style="margin-right: 10px;">Guardar OC</button>
        <button class="ingControl" id="cancel-oc">Cancelar</button>
    </div>

 
<script>
    document.getElementById('add-product').addEventListener('click', function () {
        // Crear un nuevo contenedor de productos
        const newContainer = document.createElement('div');
        newContainer.classList.add('product-container');
        newContainer.innerHTML = `
            <div class="top-buttons">
                <button class="btn-save">Guardar</button>
                <button class="btn-edit">Editar</button>
                <button class="btn-delete">Eliminar</button>
            </div>
            <div class="form-group">
                <label for="product1">Producto 1</label>
                <input type="text" id="product1" name="product1" class="form-control">
            </div>
            <div class="form-group">
                <label for="product2">Producto 2</label>
                <input type="text" id="product2" name="product2" class="form-control">
            </div>
            <div class="form-group">
                <label for="product3">Producto 3</label>
                <input type="text" id="product3" name="product3" class="form-control">
            </div>
            <div class="form-group">
                <label for="product4">Producto 4</label>
                <input type="text" id="product4" name="product4" class="form-control">
            </div>
            <div class="form-group">
                <label for="product5">Producto 5</label>
                <input type="text" id="product5" name="product5" class="form-control">
            </div>
            <div class="form-group">
                <label for="product6">Producto 6</label>
                <input type="text" id="product6" name="product6" class="form-control">
            </div>
        `;

        // Agregar el nuevo contenedor al contenedor principal
        document.getElementById('product-containers').appendChild(newContainer);
    });
</script>
</body>

</html>
