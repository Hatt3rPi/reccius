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

        <!-- Contenedor de Productos -->
        <div id="products-container">
            <h2>Productos</h2>
            <!-- Botones dentro del contenedor de productos -->
            <div class="button-container">
                <button class="btn-save">Guardar</button>
                <button class="btn-edit">Editar</button>
                <button class="btn-delete">Eliminar</button>
            </div>

            <div class="form-group">
                <label for="product1">Producto 1</label>
                <input type="text" id="product1" name="product1">
            </div>
            <div class="form-group">
                <label for="product2">Producto 2</label>
                <input type="text" id="product2" name="product2">
            </div>
            <div class="form-group">
                <label for="product3">Producto 3</label>
                <input type="text" id="product3" name="product3">
            </div>
            <div class="form-group">
                <label for="product4">Producto 4</label>
                <input type="text" id="product4" name="product4">
            </div>
            <div class="form-group">
                <label for="product5">Producto 5</label>
                <input type="text" id="product5" name="product5">
            </div>
            <div class="form-group">
                <label for="product6">Producto 6</label>
                <input type="text" id="product6" name="product6">
            </div>
        </div>
    </div>
</body>

</html>