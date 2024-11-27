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

            <!-- Botón Agregar Producto -->
            <div>
                <button id="add-product" class="btn-blue">Agregar Producto</button>
            </div>

            <!-- Contenedores de Productos -->
            <div id="product-containers"></div>
        </div>
    </div>

    <div class="button-container">
        <button id="save-oc" class="btn-blue">Guardar OC</button>
        <button id="cancel-oc" class="btn-delete">Cancelar</button>
    </div>

    <script src="../assets/js/Ingreso_OC.js"></script>
</body>
<script>
    document.addEventListener("DOMContentLoaded", () => {
    const productContainers = document.getElementById("product-containers");
    const addProductButton = document.getElementById("add-product");

    let productCount = 0;

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

        // Eventos para los botones
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
            label.textContent = `Producto ${i}`;

            const input = document.createElement("input");
            input.type = "text";
            input.name = `product${productCount}-${i}`;
            input.classList.add("form-control");

            formGroup.appendChild(label);
            formGroup.appendChild(input);
            productContainer.appendChild(formGroup);
        }

        // Agregar el nuevo contenedor al DOM
        productContainers.appendChild(productContainer);
    });
});

</script>
</html>
