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
            <div id="header-container" class="header-container">
                <h1>Ingreso Orden de Compra (OC)</h1>
            </div>

            <!-- Primer Contenedor -->
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

            <!-- Botón de Agregar Producto -->
            <div id="add-product-container" class="button-container">
                <button id="add-product" class="btn-blue">Agregar Producto</button>
            </div>

            <!-- Contenedores de Productos -->
            <div id="product-containers">
                <!-- Contenedor por defecto siempre visible -->
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

    <div class="button-container">
        <button class="ingControl" id="save-oc">Guardar OC</button>
        <button class="ingControl" id="cancel-oc">Cancelar</button>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const productContainers = document.getElementById("product-containers");
            const addProductButton = document.getElementById("add-product");

            let productCount = 1; // Contador inicial para productos

            addProductButton.addEventListener("click", () => {
                productCount++;

                // Crear nuevo contenedor de producto
                const productContainer = document.createElement("div");
                productContainer.classList.add("product-container");

                // Agregar botones superiores
                const topButtons = document.createElement("div");
                topButtons.classList.add("top-buttons");

                const saveButton = document.createElement("button");
                saveButton.classList.add("btn-save");
                saveButton.textContent = "Guardar";

                const editButton = document.createElement("button");
                editButton.classList.add("btn-edit");
                editButton.textContent = "Editar";

                const deleteButton = document.createElement("button");
                deleteButton.classList.add("btn-delete");
                deleteButton.textContent = "Eliminar";

                // Agregar eventos a los botones
                saveButton.addEventListener("click", () => {
                    alert(`Producto ${productCount} guardado`);
                });

                editButton.addEventListener("click", () => {
                    alert(`Editar Producto ${productCount}`);
                });

                deleteButton.addEventListener("click", () => {
                    if (confirm(`¿Estás seguro de eliminar el Producto ${productCount}?`)) {
                        productContainers.removeChild(productContainer);
                    }
                });

                topButtons.appendChild(saveButton);
                topButtons.appendChild(editButton);
                topButtons.appendChild(deleteButton);
                productContainer.appendChild(topButtons);

                // Agregar campos de producto
                for (let i = 1; i <= 6; i++) {
                    const formGroup = document.createElement("div");
                    formGroup.classList.add("form-group");

                    const label = document.createElement("label");
                    label.textContent = `Producto ${productCount} - Campo ${i}`;

                    const input = document.createElement("input");
                    input.type = "text";
                    input.name = `product${productCount}-${i}`;
                    input.classList.add("form-control");

                    formGroup.appendChild(label);
                    formGroup.appendChild(input);
                    productContainer.appendChild(formGroup);
                }

                // Añadir el nuevo contenedor al DOM
                productContainers.appendChild(productContainer);
            });
        });
    </script>
</body>

</html>
