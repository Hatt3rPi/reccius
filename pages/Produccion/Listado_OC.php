<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Órdenes de Compra</title>

    <link rel="stylesheet" href="../assets/css/Listados.css">
    
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
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = $('#listado').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json"
                },
                columnDefs: [{ orderable: false, className: 'details-control', targets: 0 }],
                order: [[1, 'asc']],
            });

            const productDataSimulated = [
                [
                    { nombre: "Producto A", cantidad: 500, receta: "Sí", tipo: "Tableta" },
                    { nombre: "Producto B", cantidad: 300, receta: "No", tipo: "Inyectable" },
                    { nombre: "Producto C", cantidad: 200, receta: "Sí", tipo: "Jarabe" },
                    { nombre: "Producto D", cantidad: 100, receta: "Sí", tipo: "Cápsula" },
                ]
            ];

            function formatDetails(rowData, productData) {
                let productCards = productData.map((product, index) => `
                    <div class="product-card">
                        <h3>Producto: ${index + 1}</h3>
                        <label>Producto:</label><span>${product.nombre}</span>
                        <label>Cantidad:</label><span>${product.cantidad}</span>
                        <label>¿Aplica Receta?:</label><span>${product.receta}</span>
                        <label>Tipo Preparación:</label><span>${product.tipo}</span>
                    </div>
                `).join('');

                return `
                    <div class="details-container">
                        <div class="carousel-controls">
                            <div class="carousel-button prev-button">&lt;</div>
                            <div class="carousel-button next-button">&gt;</div>
                        </div>
                        ${productCards}
                    </div>
                `;
            }

            $('#listado tbody').on('click', 'td.details-control', function () {
                const tr = $(this).closest('tr');
                const row = table.row(tr);

                if (row.child.isShown()) {
                    row.child.hide();
                    $(this).text('+');
                } else {
                    const rowIndex = table.row(tr).index();
                    const productData = productDataSimulated[rowIndex];
                    const detailsHtml = formatDetails(row.data(), productData);

                    row.child(detailsHtml).show();
                    $(this).text('-');

                    const container = row.child().find('.details-container');
                    const products = container.find('.product-card');
                    let scrollPosition = 0;

                    container.find('.prev-button').on('click', function () {
                        scrollPosition = Math.max(0, scrollPosition - 300);
                        container.scrollLeft(scrollPosition);
                    });

                    container.find('.next-button').on('click', function () {
                        scrollPosition = Math.min(container[0].scrollWidth - container[0].clientWidth, scrollPosition + 300);
                        container.scrollLeft(scrollPosition);
                    });
                }
            });
        });
    </script>
</body>

</html>