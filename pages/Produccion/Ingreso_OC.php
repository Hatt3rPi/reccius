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
        <!-- Título de la categoría Orden de Compra -->
        <h2>Orden de Compra</h2>
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

        <!-- Título de la categoría Productos -->
        <h2>Productos</h2>
        <!-- Botón para Agregar Producto -->
        <div class="button-container">
            <button id="add-product" class="btn-add-product">Agregar Producto</button>
        </div>

        <!-- Contenedores de Productos -->
        <div id="products-container-wrapper">
            <div class="products-container">
                <h2>Producto: 1</h2>
                <div class="form-group">
                    <label for="product1">Producto 1</label>
                    <input type="text" name="product1" disabled>
                </div>
                <div class="form-group">
                    <label for="product2">Producto 2</label>
                    <input type="text" name="product2" disabled>
                </div>
                <div class="form-group">
                    <label for="product3">Producto 3</label>
                    <input type="text" name="product3" disabled>
                </div>
                <div class="form-group">
                    <label for="product4">Producto 4</label>
                    <input type="text" name="product4" disabled>
                </div>
                <div class="button-container">
                    <button class="btn-save" disabled>Guardar</button>
                    <button class="btn-edit">Editar</button>
                    <button class="btn-delete">Eliminar</button>
                </div>
            </div>
        </div>

        <!-- Contenedor de Comentarios -->
        <div id="comments-container">
            <h2>Deja un comentario</h2>
            <div class="form-group">
                <input type="text" id="comment" name="comment" placeholder="Escribe tu comentario aquí">
            </div>
        </div>
    </div>

    <!-- Plantilla para nuevos contenedores -->
    <template id="product-template">
        <div class="products-container">
            <h2>Producto: </h2>
            <div class="form-group">
                <label for="product1">Producto 1</label>
                <input type="text" name="product1" disabled>
            </div>
            <div class="form-group">
                <label for="product2">Producto 2</label>
                <input type="text" name="product2" disabled>
            </div>
            <div class="form-group">
                <label for="product3">Producto 3</label>
                <input type="text" name="product3" disabled>
            </div>
            <div class="form-group">
                <label for="product4">Producto 4</label>
                <input type="text" name="product4" disabled>
            </div>
            <div class="button-container">
                <button class="btn-save" disabled>Guardar</button>
                <button class="btn-edit">Editar</button>
                <button class="btn-delete">Eliminar</button>
            </div>
        </div>
    </template>

    <!-- Script para manejar la lógica -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addButton = document.getElementById('add-product');
            const productTemplate = document.getElementById('product-template');
            const productsContainerWrapper = document.getElementById('products-container-wrapper');
            let productCount = 1; // Inicia el contador de productos

            // Función para agregar funcionalidades a los botones
            function addButtonFunctionalities(productContainer, productNumber) {
                const saveButton = productContainer.querySelector('.btn-save');
                const editButton = productContainer.querySelector('.btn-edit');
                const deleteButton = productContainer.querySelector('.btn-delete');
                const inputs = productContainer.querySelectorAll('input');

                // Editar
                editButton.addEventListener('click', () => {
                    inputs.forEach(input => input.removeAttribute('disabled'));
                    saveButton.removeAttribute('disabled');
                    console.log(`Producto ${productNumber} editable.`);
                });

                // Guardar
                saveButton.addEventListener('click', () => {
                    inputs.forEach(input => input.setAttribute('disabled', ''));
                    saveButton.setAttribute('disabled', '');
                    console.log(`Producto ${productNumber} guardado.`);
                    alert(`Producto ${productNumber} guardado con éxito.`);
                });

                // Eliminar
                deleteButton.addEventListener('click', () => {
                    productContainer.remove();
                    console.log(`Producto ${productNumber} eliminado.`);
                });
            }

            // Inicializar botones del primer producto
            addButtonFunctionalities(document.querySelector('.products-container'), 1);

            if (addButton) {
                addButton.addEventListener('click', function () {
                    console.log("Evento 'click' activado en 'Agregar Producto'.");

                    // Incrementa el contador de productos
                    productCount++;

                    // Clonar el contenido de la plantilla
                    const newProductContainer = productTemplate.content.cloneNode(true).querySelector('.products-container');

                    // Actualizar el número del producto en el <h2>
                    const newProductHeading = newProductContainer.querySelector('h2');
                    newProductHeading.textContent = `Producto: ${productCount}`;

                    // Agregar funcionalidades a los botones
                    addButtonFunctionalities(newProductContainer, productCount);

                    // Agregar al final del contenedor de productos
                    productsContainerWrapper.appendChild(newProductContainer);
                });
            } else {
                console.error("No se encontró el botón 'Agregar Producto'.");
            }
        });
    </script>
</body>

</html>
