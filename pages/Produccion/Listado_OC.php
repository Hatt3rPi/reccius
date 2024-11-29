<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Órdenes de Compra</title>
    <link rel="stylesheet" href="../assets/css/Listados.css">
    <link rel="stylesheet" href="../assets/css/modal_produccion.css">
    <style>
        .details-container {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            padding: 10px;
            gap: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .product-card {
            flex: 0 0 calc(33.33% - 15px);
            min-width: calc(33.33% - 15px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 15px;
            background-color: #ffffff;
            border-radius: 5px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .empty-card {
            background-color: transparent;
            border: none;
            box-shadow: none;
        }

        .product-card h3 {
            margin-top: 0;
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .product-card label {
            font-weight: bold;
            color: #555;
        }

        .product-card span {
            color: #777;
            display: block;
            margin-bottom: 5px;
        }

        .products-info {
            font-size: 14px;
            color: #888;
            margin-bottom: 10px;
            text-align: left;
        }

        .details-control {
            cursor: pointer;
            text-align: center;
            font-size: 16px;
            color: #0056b3;
        }

        .details-control:hover {
            color: #003366;
        }
    </style>
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
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function formatDetails(rowData, productData) {
                const maxProductsToShow = 3;
                const totalProducts = productData.length;

                let productCards = productData.slice(0, maxProductsToShow).map((product, index) => `
                    <div class="product-card">
                        <h3>Producto: ${index + 1}</h3>
                        <label>Producto:</label><span>${product.nombre}</span>
                        <label>Cantidad:</label><span>${product.cantidad}</span>
                        <label>¿Aplica Receta?:</label><span>${product.receta}</span>
                        <label>Tipo Preparación:</label><span>${product.tipo}</span>
                    </div>
                `).join('');

                const emptyCards = maxProductsToShow - productData.slice(0, maxProductsToShow).length;
                for (let i = 0; i < emptyCards; i++) {
                    productCards += `<div class="product-card empty-card"></div>`;
                }

                const productsInfoText = `<div class="products-info">Mostrando ${Math.min(maxProductsToShow, totalProducts)} de ${totalProducts} productos</div>`;

                return `
                    ${productsInfoText}
                    <div class="details-container">
                        ${productCards}
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
                }
            });
        });
    </script>
</body>

</html>
