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
                    <td>05/10/2024</td>
                    <td>Entregado</td>
                    <td>OC126</td>
                    <td>Total</td>
                    <td>Clínica Dávila</td>
                    <td>10/10/2024</td>
                    <td>Región</td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
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
                    <div class="carousel-container">
                        <button class="carousel-button prev-button">&lt;</button>
                        <div class="carousel">
                            ${productCards}
                        </div>
                        <button class="carousel-button next-button">&gt;</button>
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

            const productDataSimulated = [
                [{ nombre: "Producto A", cantidad: 500, receta: "Sí", tipo: "Tableta" }],
                [
                    { nombre: "Producto B", cantidad: 300, receta: "No", tipo: "Inyectable" },
                    { nombre: "Producto C", cantidad: 200, receta: "Sí", tipo: "Jarabe" }
                ],
                [
                    { nombre: "Producto X", cantidad: 100, receta: "Sí", tipo: "Cápsula" },
                    { nombre: "Producto Y", cantidad: 150, receta: "No", tipo: "Polvo" },
                    { nombre: "Producto Z", cantidad: 50, receta: "Sí", tipo: "Inyectable" }
                ],
                [
                    { nombre: "Producto A", cantidad: 120, receta: "No", tipo: "Jarabe" },
                    { nombre: "Producto B", cantidad: 250, receta: "Sí", tipo: "Inyectable" },
                    { nombre: "Producto C", cantidad: 300, receta: "Sí", tipo: "Tableta" },
                    { nombre: "Producto D", cantidad: 100, receta: "No", tipo: "Cápsula" }
                ]
            ];

            $('#listado tbody').on('click', 'td.details-control', function () {
                const tr = $(this).closest('tr');
                const row = table.row(tr);

                if (row.child.isShown()) {
                    row.child.hide();
                    $(this).text('+');
                } else {
                    const rowIndex = table.row(tr).index();
                    const productData = productDataSimulated[rowIndex];
                    row.child(formatDetails(row.data(), productData)).show();
                    $(this).text('-');

                    const carousel = row.child().find('.carousel');
                    const prevButton = row.child().find('.prev-button');
                    const nextButton = row.child().find('.next-button');
                    let scrollPosition = 0;

                    prevButton.on('click', function () {
                        scrollPosition = Math.max(0, scrollPosition - 300);
                        carousel.scrollLeft(scrollPosition);
                    });

                    nextButton.on('click', function () {
                        scrollPosition = Math.min(carousel[0].scrollWidth - carousel[0].clientWidth, scrollPosition + 300);
                        carousel.scrollLeft(scrollPosition);
                    });
                }
            });
        });
    </script>
</body>

</html>
