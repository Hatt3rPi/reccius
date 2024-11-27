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
    <title>Ingreso Orden de Compra (OC)</title>
    <link rel="stylesheet" href="../assets/css/Ingreso_OC.css">
    <link rel="stylesheet" href="../assets/css/Notificacion.css">
    <script src="../assets/js/notify.js"></script>
</head>

<body>
    <div id="form-container" class="ContenedorP">
        <div id="Maincontainer">
            <!-- Encabezado -->
            <div id="header-container" class="header-container">
                <h1>Ingreso Orden de Compra (OC)</h1>
            </div>

            <!-- Campos del Formulario Principal -->
            <div id="container-1" class="input-container">
                <div class="form-group">
                    <label for="campo1">Campo 1</label>
                    <input type="text" id="campo1" name="campo1" class="form-control">
                </div>
                <div class="form-group">
                    <label for="campo2">Campo 2</label>
                    <input type="text" id="campo2" name="campo2" class="form-control">
                </div>
                <div class="form-group">
                    <label for="campo3">Campo 3</label>
                    <input type="text" id="campo3" name="campo3" class="form-control">
                </div>
                <div class="form-group">
                    <label for="campo4">Campo 4</label>
                    <input type="text" id="campo4" name="campo4" class="form-control">
                </div>
                <div class="form-group">
                    <label for="campo5">Campo 5</label>
                    <input type="text" id="campo5" name="campo5" class="form-control">
                </div>
                <div class="form-group">
                    <label for="campo6">Campo 6</label>
                    <input type="text" id="campo6" name="campo6" class="form-control">
                </div>
            </div>

            <!-- Botón para Agregar Productos -->
            <div id="add-product-container" class="button-container">
                <button id="add-product" class="btn-blue">Agregar Producto</button>
            </div>

            <!-- Contenedores de Productos -->
            <div id="product-containers">
                <!-- Contenedor de Producto Predeterminado -->
                <div class="product-container">
                    <div class="top-buttons">
                        <button class="btn-save">Guardar</button>
                        <button class="btn-edit">Editar</button>
                        <button class="btn-delete">Eliminar</button>
                    </div>
                    <div class="form-group">
                        <label for="default-product1">Producto 1</label>
                        <input type="text" id="default-product1" name="default-product1" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="default-product2">Producto 2</label>
                        <input type="text" id="default-product2" name="default-product2" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="default-product3">Producto 3</label>
                        <input type="text" id="default-product3" name="default-product3" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="default-product4">Producto 4</label>
                        <input type="text" id="default-product4" name="default-product4" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="default-product5">Producto 5</label>
                        <input type="text" id="default-product5" name="default-product5" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="default-product6">Producto 6</label>
                        <input type="text" id="default-product6" name="default-product6" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones Finales -->
    <div class="button-container">
        <button class="ingControl" id="save-oc">Guardar OC</button>
        <button class="ingControl" id="cancel-oc">Cancelar</button>
    </div>

    <!-- Script para el Comportamiento Dinámico -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const productContainers = document.getElementById("product-containers");
            const addProductButton = document.getElementById("add-product");

            let productCount = 1; // Iniciar con 1 debido al contenedor inicial

            addProductButton.onclick = function () {
                productCount++;

                // Crear nuevo contenedor de producto
                const productContainer = `
                    <div class="product-container">
                        <div class="top-buttons">
                            <button class="btn-save">Guardar</button>
                            <button class="btn-edit">Editar</button>
                            <button class="btn-delete">Eliminar</button>
                        </div>
                        ${[...Array(6)].map((_, i) => `
                            <div class="form-group">
                                <label>Producto ${productCount} - Campo ${i + 1}</label>
                                <input type="text" name="product${productCount}-${i + 1}" class="form-control">
                            </div>
                        `).join("")}
                    </div>
                `;

                // Insertar el nuevo contenedor
                productContainers.insertAdjacentHTML('beforeend', productContainer);

                // Añadir eventos a los nuevos botones
                productContainers.lastElementChild.querySelector('.btn-delete').onclick = function () {
                    productContainers.removeChild(this.parentNode.parentNode);
                };
            };
        });
    </script>
</body>

</html>
