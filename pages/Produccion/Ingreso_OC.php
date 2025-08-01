<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Productos</title>
    <link rel="stylesheet" href="../assets/css/Ingreso_OC.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Feature Flags Configuration -->
    <script src="../../assets/js/features_customware.js"></script>
</head>

<body>
    <!-- Control de acceso por feature flag -->
    <script>
    if (typeof AppConfig === 'undefined' || !AppConfig.FLAGS.experimental_produccion) {
        document.body.innerHTML = '';
        document.write(`
            <div style="font-family: Arial, sans-serif; text-align: center;background-color: #f8f8f8;">
                <h1 style="color: #333; text-align: center;">¡Estamos trabajando en ello!</h1>
                <p style="color: #555;text-align: center;">Gracias por tu interés en esta nueva función. Nuestro equipo está trabajando arduamente para ofrecerte una experiencia aún mejor. Esta característica estará disponible muy pronto.</p>
                <p style="color: #555;text-align: center;">Te pedimos un poco de paciencia mientras preparamos todo para que disfrutes de esta nueva funcionalidad al máximo. ¡Estamos emocionados por compartirla contigo!</p>
                <p style="color: #555;text-align: center;">Mientras tanto, no dudes en explorar el resto de nuestras increíbles herramientas y servicios. ¡Hay mucho más por descubrir!</p>
            </div>
        `);
    }
    </script>
    <div id="form-container">
        <!-- Título de la categoría Orden de Compra -->
        <h2>Orden de Compra Institucional</h2>
        <!-- Contenedor 1 -->
        <div id="container-1">
            <div class="column">
                <div class="form-group">
                    <label for="rut">Rut:</label>
                    <input type="text" id="rut" name="rut" value="123456">
                </div>
                <div class="form-group">
                    <label for="orden-compra">Orden de Compra:</label>
                    <input type="text" id="orden-compra" name="orden-compra" value="OC45679">
                </div>
                <div class="form-group">
                    <label for="tipo-entrega">Tipo Entrega:</label>
                    <input type="text" id="tipo-entrega" name="tipo-entrega" value="Total / Parcial"
                        style="background-color: red; color: white;">
                </div>
                <div class="form-group">
                    <label for="fecha-entrega">Fecha Entrega Programada:</label>
                    <input type="text" id="fecha-entrega" name="fecha-entrega">
                </div>
            </div>
            <div class="column">
                <div class="form-group">
                    <label for="cliente">Cliente:</label>
                    <input type="text" id="cliente" name="cliente" value="Clínica Alemana">
                </div>
                <div class="form-group">
                    <label for="adjunta-receta">¿Adjunta Receta?:</label>
                    <input type="text" id="adjunta-receta" name="adjunta-receta" value="Sí / No">
                </div>
                <div class="form-group">
                    <label for="medio-despacho">Medio despacho:</label>
                    <input type="text" id="medio-despacho" name="medio-despacho">
                </div>
                <div class="form-group">
                    <label for="zona-entrega">Zona de Entrega:</label>
                    <input type="text" id="zona-entrega" name="zona-entrega" value="Santiago / Región">
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
                    <label for="producto-nombre">Producto:</label>
                    <input type="text" id="producto-nombre" name="producto-nombre" value="POLIDOCANOL INY 1% 2ML"
                        disabled>
                </div>
                <div class="form-group">
                    <label for="producto-cantidad">Cantidad:</label>
                    <input type="text" id="producto-cantidad" name="producto-cantidad" value="1000" disabled>
                </div>
                <div class="form-group">
                    <label for="producto-receta">¿Aplica Receta?:</label>
                    <input type="text" id="producto-receta" name="producto-receta" value="Sí / No" disabled>
                </div>
                <div class="form-group">
                    <label for="producto-tipo-preparacion">Tipo Preparación:</label>
                    <input type="text" id="producto-tipo-preparacion" name="producto-tipo-preparacion"
                        value="Inyectable" disabled>
                </div>
                <div class="button-container">
                    <button class="btn-save" id="guardar-producto" disabled>Guardar</button>
                    <button class="btn-edit" id="editar-producto">Editar</button>
                    <button class="btn-delete" id="eliminar-producto">Eliminar</button>
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
                <label for="producto-nombre">Producto:</label>
                <input type="text" id="producto-nombre" name="producto-nombre" disabled>
            </div>
            <div class="form-group">
                <label for="producto-cantidad">Cantidad:</label>
                <input type="text" id="producto-cantidad" name="producto-cantidad" disabled>
            </div>
            <div class="form-group">
                <label for="producto-receta">¿Aplica Receta?:</label>
                <input type="text" id="producto-receta" name="producto-receta" disabled>
            </div>
            <div class="form-group">
                <label for="producto-tipo-preparacion">Tipo Preparación:</label>
                <input type="text" id="producto-tipo-preparacion" name="producto-tipo-preparacion" disabled>
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
                    alert(`Producto ${productNumber} eliminado.`);
                });
            }

            // Inicializar botones del primer producto
            addButtonFunctionalities(document.querySelector('.products-container'), 1);

            // Agregar funcionalidad al botón "Agregar Producto"
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