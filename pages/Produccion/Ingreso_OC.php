<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Productos</title>
    <link rel="stylesheet" href="../assets/css/Ingreso_OC.css">
</head>

<body>
    <div id="form-container">
        <!-- Contenedor 1 -->
        <div id="container-1">
            <div class="column">
                <div class="form-group">
                    <label for="form1">Formulario 1</label>
                    <input type="text" id="form1" name="form1">
                </div>
                <div class="form-group">
                    <label for="form2">Formulario 2</label>
                    <input type="text" id="form2" name="form2">
                </div>
                <div class="form-group">
                    <label for="form3">Formulario 3</label>
                    <input type="text" id="form3" name="form3">
                </div>
                <div class="form-group">
                    <label for="form4">Formulario 4</label>
                    <input type="text" id="form4" name="form4">
                </div>
            </div>
            <div class="column">
                <div class="form-group">
                    <label for="form5">Formulario 5</label>
                    <input type="text" id="form5" name="form5">
                </div>
                <div class="form-group">
                    <label for="form6">Formulario 6</label>
                    <input type="text" id="form6" name="form6">
                </div>
                <div class="form-group">
                    <label for="form7">Formulario 7</label>
                    <input type="text" id="form7" name="form7">
                </div>
                <div class="form-group">
                    <label for="form8">Formulario 8</label>
                    <input type="text" id="form8" name="form8">
                </div>
            </div>
        </div>
        <!-- Botón para Agregar Producto -->
        <div class="button-container">
            <button id="add-product" class="btn-add-product">Agregar Producto</button>
        </div>

        <!-- Contenedores de Productos -->
        <div id="products-container-wrapper">
            <div class="products-container">
                <h2>Productos</h2>
                <div class="form-group">
                    <label for="product1">Producto 1</label>
                    <input type="text" name="product1">
                </div>
                <div class="form-group">
                    <label for="product2">Producto 2</label>
                    <input type="text" name="product2">
                </div>
                <div class="form-group">
                    <label for="product3">Producto 3</label>
                    <input type="text" name="product3">
                </div>
                <div class="form-group">
                    <label for="product4">Producto 4</label>
                    <input type="text" name="product4">
                </div>
                <div class="form-group">
                    <label for="product5">Producto 5</label>
                    <input type="text" name="product5">
                </div>
                <div class="form-group">
                    <label for="product6">Producto 6</label>
                    <input type="text" name="product6">
                </div>
                <div class="button-container">
                    <button class="btn-save">Guardar</button>
                    <button class="btn-edit">Editar</button>
                    <button class="btn-delete">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Plantilla para nuevos contenedores -->
    <template id="product-template">
        <div class="products-container">
            <h2>Productos</h2>
            <div class="form-group">
                <label for="product1">Producto 1</label>
                <input type="text" name="product1">
            </div>
            <div class="form-group">
                <label for="product2">Producto 2</label>
                <input type="text" name="product2">
            </div>
            <div class="form-group">
                <label for="product3">Producto 3</label>
                <input type="text" name="product3">
            </div>
            <div class="form-group">
                <label for="product4">Producto 4</label>
                <input type="text" name="product4">
            </div>
            <div class="form-group">
                <label for="product5">Producto 5</label>
                <input type="text" name="product5">
            </div>
            <div class="form-group">
                <label for="product6">Producto 6</label>
                <input type="text" name="product6">
            </div>
            <div class="button-container">
                <button class="btn-save">Guardar</button>
                <button class="btn-edit">Editar</button>
                <button class="btn-delete">Eliminar</button>
            </div>
        </div>
    </template>

    <!-- Script para manejar la lógica -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const button = document.getElementById('add-product');
            button.style.pointerEvents = 'auto';
            button.style.visibility = 'visible';
            button.style.zIndex = '1000';
            console.log("Botón encontrado:", button);

            if (button) {
                button.addEventListener('click', function () {
                    console.log("Evento 'click' activado en el botón.");
                    alert("El botón funciona correctamente.");
                });
            } else {
                console.error("No se encontró el botón.");
            }
        });
    </script>
</body>

</html>