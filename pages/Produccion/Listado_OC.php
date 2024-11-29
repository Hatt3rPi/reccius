<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Órdenes de Compra</title>
    <link rel="stylesheet" href="../assets/css/Listados.css">
    <link rel="stylesheet" href="../assets/css/modal_produccion.css">
</head>

<body>
    <div class="form-container">
        <h1>Listado de Órdenes de Compra</h1>
        <h2 class="section-title">Órdenes Registradas</h2>
        <table id="listado" class="display" style="width:100%">
            <thead>
                <tr>
                    <th></th>
                    <th>Fecha Ingreso</th>
                    <th>Estado</th>
                    <th>OC</th>
                    <th>Tipo Entrega</th>
                    <th>Cliente</th>
                    <th>Fecha Programada</th>
                    <th>Zona Envío</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="details-control">+</td>
                    <td>02/10/2024</td>
                    <td>Producción</td>
                    <td>OC123</td>
                    <td>Total</td>
                    <td>Clínica Santa María</td>
                    <td>04/10/2024</td>
                    <td>Santiago</td>
                </tr>
                <tr>
                    <td class="details-control">+</td>
                    <td>02/10/2024</td>
                    <td>Pendiente</td>
                    <td>OC124</td>
                    <td>Parcial</td>
                    <td>Clínica Alemana</td>
                    <td>05/10/2024</td>
                    <td>Región</td>
                </tr>
                <tr>
                    <td class="details-control">+</td>
                    <td>03/10/2024</td>
                    <td>Pendiente</td>
                    <td>OC125</td>
                    <td>Total</td>
                    <td>Hospital del Salvador</td>
                    <td>06/10/2024</td>
                    <td>Santiago</td>
                </tr>
                <tr>
                    <td class="details-control">+</td>
                    <td>04/10/2024</td>
                    <td>Pendiente</td>
                    <td>OC126</td>
                    <td>Parcial</td>
                    <td>Clínica Dávila</td>
                    <td>07/10/2024</td>
                    <td>Región</td>
                </tr>
                <tr>
                    <td class="details-control">+</td>
                    <td>05/10/2024</td>
                    <td>Pendiente</td>
                    <td>OC127</td>
                    <td>Total</td>
                    <td>Clínica Universidad de Chile</td>
                    <td>08/10/2024</td>
                    <td>Santiago</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Script para manejar DataTables -->
    <script>
        $(document).ready(function () {
            // Formato para los detalles de cada orden
            function formatDetails(rowData, productData) {
                const maxProductsToShow = 3; // Número máximo de productos visibles inicialmente
                const totalProducts = productData.length; // Total de productos

                // Tomar los primeros N productos para mostrar
                const visibleProducts = productData.slice(0, maxProductsToShow);

                // Generar tarjetas de productos visibles
                let productCards = visibleProducts.map((product, index) => `
                    <div class="product-card">
                        <h3>Producto: ${index + 1}</h3>
                        <label>Producto:</label><span>${product.nombre}</span><br>
                        <label>Cantidad:</label><span>${product.cantidad}</span><br>
                        <label>¿Aplica Receta?:</label><span>${product.receta}</span><br>
                        <label>Tipo Preparación:</label><span>${product.tipo}</span>
                    </div>
                `).join('');

                // Agregar tarjetas vacías si faltan para completar 3 columnas
                const emptyCards = maxProductsToShow - visibleProducts.length;
                for (let i = 0; i < emptyCards; i++) {
                    productCards += `<div class="product-card empty-card"></div>`;
                }

                // Texto de productos mostrados y total
                const productsInfoText = `<div class="products-info">Mostrando ${visibleProducts.length} de ${totalProducts} productos</div>`;

                return `
                    ${productsInfoText}
                    <div class="details-container">
                        ${productCards}
                    </div>
                `;
            }

            // Inicializa DataTables
            var table = $('#listado').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json"
                },
                columnDefs: [
                    { orderable: false, className: 'details-control', targets: 0 }
                ],
                order: [[1, 'asc']]
            });

            // Datos simulados de productos para cada fila (a conectar con el backend)
            const productDataSimulated = [
                [{ nombre: "Polidocanol INY 1% 2ML", cantidad: 1000, receta: "Sí", tipo: "Inyectable" }],
                [
                    { nombre: "Producto A", cantidad: 500, receta: "No", tipo: "Tableta" },
                    { nombre: "Producto B", cantidad: 300, receta: "Sí", tipo: "Inyectable" }
                ],
                [
                    { nombre: "Producto X", cantidad: 200, receta: "Sí", tipo: "Jarabe" },
                    { nombre: "Producto Y", cantidad: 150, receta: "No", tipo: "Cápsula" },
                    { nombre: "Producto Z", cantidad: 100, receta: "Sí", tipo: "Tableta" }
                ],
                [
                    { nombre: "Producto 1", cantidad: 400, receta: "Sí", tipo: "Líquido" },
                    { nombre: "Producto 2", cantidad: 250, receta: "No", tipo: "Polvo" },
                    { nombre: "Producto 3", cantidad: 600, receta: "Sí", tipo: "Jarabe" },
                    { nombre: "Producto 4", cantidad: 350, receta: "No", tipo: "Cápsula" }
                ],
                Array.from({ length: 20 }, (_, i) => ({
                    nombre: `Producto ${i + 1}`,
                    cantidad: Math.floor(Math.random() * 1000),
                    receta: i % 2 === 0 ? "Sí" : "No",
                    tipo: i % 3 === 0 ? "Tableta" : i % 3 === 1 ? "Jarabe" : "Cápsula"
                }))
            ];

            // Control de los detalles desplegables
            $('#listado tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    // Cierra la fila de detalles
                    row.child.hide();
                    $(this).text('+');
                } else {
                    // Muestra la fila de detalles
                    const rowIndex = table.row(tr).index();
                    const productData = productDataSimulated[rowIndex];
                    row.child(formatDetails(row.data(), productData)).show();
                    $(this).text('-');
                }
            });
        });
    </script>
</body>

</html>
