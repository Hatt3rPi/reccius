<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Órdenes de Compra</title>

    <link rel="stylesheet" href="../assets/css/Listados.css">
    <link rel="stylesheet" href="../assets/css/modal_produccion.css">
    <link rel="stylesheet" href="../assets/css/modal_OC.css">

</head>

<body>
    <div class="form-container">
        <h1>Listado de Órdenes de Compra</h1>
        <h2 class="section-title">Órdenes Registradas</h2>

        <div class="filtros-container">
            <button class="filtro-boton completado" onclick="filtrarPorEstado('Producción')">Producción</button>
            <button class="filtro-boton pendiente" onclick="filtrarPorEstado('Pendiente')">Pendiente</button>
            <button class="filtro-boton todos" onclick="filtrarPorEstado('')">Todos</button>
        </div>

        <table id="listado" class="display" style="width:100%">
            <thead>
                <tr>
                    <th><i class="icono-detalles fas fa-search"></i></th>
                    <th>Estado</th>
                    <th>OC</th>
                    <th>Fecha Ingreso</th>
                    <th>Tipo Entrega</th>
                    <th>Cliente</th>
                    <th>Fecha Programada</th>
                    <th>Zona Envío</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="details-control"><i class="icono-detalles fas fa-plus-circle"></i></td>
                    <td><span class="estado-completado">Producción</span></td>
                    <td>OC123</td>
                    <td>02/10/2024</td>
                    <td>Total</td>
                    <td>Clínica Santa María</td>
                    <td>04/10/2024</td>
                    <td>Santiago</td>
                </tr>
                <tr>
                    <td class="details-control"><i class="icono-detalles fas fa-plus-circle"></i></td>
                    <td><span class="estado-pendiente">Pendiente</span></td>
                    <td>OC124</td>
                    <td>02/10/2024</td>
                    <td>Parcial</td>
                    <td>Clínica Alemana</td>
                    <td>05/10/2024</td>
                    <td>Región</td>
                </tr>
                <tr>
                    <td class="details-control"><i class="icono-detalles fas fa-plus-circle"></i></td>
                    <td><span class="estado-pendiente">Pendiente</span></td>
                    <td>OC125</td>
                    <td>03/10/2024</td>
                    <td>Total</td>
                    <td>Hospital del Salvador</td>
                    <td>06/10/2024</td>
                    <td>Santiago</td>
                </tr>
                <tr>
                    <td class="details-control"><i class="icono-detalles fas fa-plus-circle"></i></td>
                    <td><span class="estado-pendiente">Pendiente</span></td>
                    <td>OC126</td>
                    <td>04/10/2024</td>
                    <td>Parcial</td>
                    <td>Clínica Dávila</td>
                    <td>07/10/2024</td>
                    <td>Región</td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Modal para editar producto -->
    <div id="modalEditarProducto" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h2>Editar Producto</h2>
            <form id="formEditarProducto">
                <label for="nombreProducto">Producto:</label>
                <input type="text" id="nombreProducto" name="nombreProducto" required>

                <label for="cantidadProducto">Cantidad:</label>
                <input type="number" id="cantidadProducto" name="cantidadProducto" required>

                <label for="recetaProducto">¿Aplica Receta?:</label>
                <select id="recetaProducto" name="recetaProducto">
                    <option value="Sí">Sí</option>
                    <option value="No">No</option>
                </select>

                <!-- Contenedor para el campo de archivo -->
                <div id="recetaArchivoContainer" style="display: none; margin-top: 15px;">
                    <label for="archivoReceta">Subir Receta (PDF, Word o Imagen):</label>
                    <input type="file" id="archivoReceta" name="archivoReceta" accept=".pdf, .doc, .docx, image/*">
                </div>

                <label for="tipoProducto">Tipo Preparación:</label>
                <input type="text" id="tipoProducto" name="tipoProducto" required>

                <button type="button" onclick="guardarCambios()">Guardar Cambios</button>
            </form>
        </div>
    </div>
    <!-- Botón flotante -->
    <button class="floating-button" onclick="abrirModalRecepcionar()">
        <i class="fas fa-plus"></i>
    </button>

    <!-- Modal para recepcionar orden de compra -->
    <div id="modalRecepcionarOC">
        <div class="modal-content-recepcion">
            <span class="close-recepcion" onclick="cerrarModalRecepcionar()">&times;</span>
            <h2>Recepcionar Orden de Compra</h2>

            <!-- GIF integrado en el modal -->
            <div style="text-align: center; margin-bottom: 20px;">
                <iframe src="https://giphy.com/embed/bLeARYYZSjAAz40cKU" width="100%" height="250"
                    style="border-radius: 10px; border: none; pointer-events: none;" frameborder="0" class="giphy-embed"
                    allowfullscreen>
                </iframe>
                <p style="font-size: 12px; color: #555;">
                    <a target="_blank" style="text-decoration: none; color: #007bff;">Usa tu pistola lectora de códigos
                        de barra</a>
                </p>
            </div>

            <form id="formRecepcionarOC">
                <label for="ordenCompra">Número de OC:</label>
                <input type="text" id="ordenCompra" name="ordenCompra" placeholder="Ingrese el número de OC" required>

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" placeholder="Ingrese una descripción" rows="4"
                    required></textarea>

                <label for="fechaRecepcion">Fecha de Recepción:</label>
                <input type="date" id="fechaRecepcion" name="fechaRecepcion" required>

                <label for="Comentario">Ingresar Comentario:</label>
                <input type="text" id="Comentario" name="Comentario" placeholder="tu comentario.." required>

                <button type="submit">Recepcionar</button>
            </form>
        </div>
    </div>

    <script>
    $(document).ready(function () {
        let productoSeleccionado = null;
        let indiceProductoSeleccionado = null;
        let ordenActual = null;

        function formatDetails(rowData, productData, etapasEstado) {
            const etapas = etapasEstado.map((etapa, index) => `
                <div class="etapa ${etapa.completada ? 'completada' : ''}">
                    <div class="icono-etapa"><i class="${etapa.completada ? 'fas fa-check-circle' : 'fas fa-circle'}"></i></div>
                    <p>${etapa.nombre}</p>
                </div>
            `).join('');

            const productCards = productData.map((product, index) => `
                <div class="product-card">
                    <div class="product-header">
                        <h3>Producto: ${index + 1}</h3>
                        <div class="product-actions">
                            <i class="icon-edit fas fa-pencil-alt" onclick="editarProducto(${index}, '${rowData[2]}')"></i>
                            <i class="icon-delete fas fa-times" onclick="eliminarProducto(${index}, '${rowData[2]}')"></i>
                        </div>
                    </div>
                    <label>Producto:</label><span>${product.nombre}</span>
                    <label>Cantidad:</label><span>${product.cantidad}</span>
                    <label>¿Aplica Receta?:</label><span>${product.receta}</span>
                    <label>Tipo Preparación:</label><span>${product.tipo}</span>
                </div>
            `).join('');

            return `
                <div class="details-container">
                    <div class="etapas-container">${etapas}</div>
                    <div class="productos-container">${productCards}</div>
                </div>
            `;
        }

        const table = $('#listado').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json"
            },
            columnDefs: [{ orderable: false, className: 'details-control', targets: 0 }],
            order: [[1, 'asc']],
        });

        const productDataSimulated = {
            OC123: [{ nombre: "Producto A", cantidad: 500, receta: "Sí", tipo: "Tableta" }],
            OC124: [
                { nombre: "Producto B", cantidad: 300, receta: "No", tipo: "Inyectable" },
                { nombre: "Producto C", cantidad: 200, receta: "Sí", tipo: "Jarabe" }
            ],
            OC125: [
                { nombre: "Producto X", cantidad: 100, receta: "Sí", tipo: "Cápsula" },
                { nombre: "Producto Y", cantidad: 150, receta: "No", tipo: "Polvo" },
                { nombre: "Producto Z", cantidad: 50, receta: "Sí", tipo: "Inyectable" }
            ],
            OC126: [
                { nombre: "Producto 1", cantidad: 50, receta: "No", tipo: "Tableta" },
                { nombre: "Producto 2", cantidad: 100, receta: "Sí", tipo: "Inyectable" }
            ]
        };

        const etapasPorFila = {
            OC123: [
                { nombre: "Creación", completada: true },
                { nombre: "Confirmación", completada: true },
                { nombre: "Producción", completada: false },
                { nombre: "Despacho", completada: false },
                { nombre: "Entrega", completada: false }
            ],
            OC124: [
                { nombre: "Creación", completada: true },
                { nombre: "Confirmación", completada: true },
                { nombre: "Producción", completada: true },
                { nombre: "Despacho", completada: false },
                { nombre: "Entrega", completada: false }
            ]
        };

        $('#listado tbody').on('click', 'td.details-control', function () {
            const tr = $(this).closest('tr');
            const row = table.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                $(this).html('<i class="icono-detalles fas fa-plus-circle"></i>');
            } else {
                const rowData = row.data();
                const orderNumber = rowData[2];
                const productData = productDataSimulated[orderNumber] || [];
                const etapasEstado = etapasPorFila[orderNumber] || [];
                row.child(formatDetails(rowData, productData, etapasEstado)).show();
                $(this).html('<i class="icono-detalles fas fa-minus-circle"></i>');
            }
        });

        // Funciones de edición
        window.editarProducto = function (index, orderNumber) {
            ordenActual = orderNumber;
            productoSeleccionado = productDataSimulated[orderNumber]?.[index];
            indiceProductoSeleccionado = index;

            if (productoSeleccionado) {
                $('#nombreProducto').val(productoSeleccionado.nombre);
                $('#cantidadProducto').val(productoSeleccionado.cantidad);
                $('#recetaProducto').val(productoSeleccionado.receta);
                $('#tipoProducto').val(productoSeleccionado.tipo);

                if (productoSeleccionado.receta === "Sí") {
                    $('#recetaArchivoContainer').show();
                } else {
                    $('#recetaArchivoContainer').hide();
                }

                $('#modalEditarProducto').fadeIn();
            }
        };

        // Escuchar cambios en el campo de receta
        $('#recetaProducto').on('change', function () {
            const aplicaReceta = $(this).val();
            if (aplicaReceta === "Sí") {
                $('#recetaArchivoContainer').fadeIn();
            } else {
                $('#recetaArchivoContainer').fadeOut();
            }
        });

        window.guardarCambios = function () {
            if (productoSeleccionado) {
                productoSeleccionado.nombre = $('#nombreProducto').val();
                productoSeleccionado.cantidad = $('#cantidadProducto').val();
                productoSeleccionado.receta = $('#recetaProducto').val();
                productoSeleccionado.tipo = $('#tipoProducto').val();

                const tr = $(`#listado tbody tr:contains(${ordenActual})`);
                const row = table.row(tr);
                const etapasEstado = etapasPorFila[ordenActual] || [];
                row.child(formatDetails(row.data(), productDataSimulated[ordenActual], etapasEstado)).show();
                alert('Cambios guardados correctamente.');
                $('#modalEditarProducto').fadeOut();
            }
        };

        // Eliminar producto
        window.eliminarProducto = function (index, orderNumber) {
            if (confirm(`¿Está seguro de eliminar el producto ${index + 1}?`)) {
                productDataSimulated[orderNumber].splice(index, 1);
                const tr = $(`#listado tbody tr:contains(${orderNumber})`);
                const row = table.row(tr);
                const etapasEstado = etapasPorFila[orderNumber] || [];
                row.child(formatDetails(row.data(), productDataSimulated[orderNumber], etapasEstado)).show();
                alert('Producto eliminado correctamente.');
            }
        };

        // Filtrar por estado
        window.filtrarPorEstado = function (estado) {
            table.columns(1).search(estado).draw();
        };

        // Función para abrir el modal de recepción
        function abrirModalRecepcionar() {
            document.getElementById('modalRecepcionarOC').style.display = 'block';
        }

        // Función para cerrar el modal de recepción
        function cerrarModalRecepcionar() {
            document.getElementById('modalRecepcionarOC').style.display = 'none';
        }

        // Simular el envío del formulario de recepción
        document.getElementById('formRecepcionarOC').addEventListener('submit', function (event) {
            event.preventDefault();
            alert('Orden de Compra Recepcionada Exitosamente');
            cerrarModalRecepcionar();
        });
    });
</script>


</body>


</html>