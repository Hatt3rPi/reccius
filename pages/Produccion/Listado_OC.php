<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Órdenes de Compra</title>
    <link rel="stylesheet" href="../assets/css/Listados.css">
    <link rel="stylesheet" href="../assets/css/modal_produccion.css">
<<<<<<< HEAD
    <style>
        .details-container {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .product-card {
            flex: 0 0 calc(33.33% - 15px); /* Ocupa 1/3 del contenedor con espacio */
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
=======

    <style>
        .details-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 10px;
            margin: 10px 0;
        }

        .product-card {
            flex: 1 1 calc(33.333% - 10px);
            /* Mantiene siempre 1/3 del ancho */
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            min-height: 150px;
        }

        .product-card h3 {
            font-size: 16px;
            margin-bottom: 8px;
            color: #0056b3;
            text-align: center;
>>>>>>> parent of aae4c1cf (Update Listado_OC.php)
        }

        .product-card label {
            font-weight: bold;
<<<<<<< HEAD
            color: #555;
        }

        .products-info {
            font-size: 14px;
            color: #888;
            margin-bottom: 10px;
            text-align: left;
=======
            display: inline-block;
            margin-right: 5px;
            color: #333;
        }

        .product-card span {
            display: inline-block;
            margin-bottom: 8px;
            color: #555;
        }

        .empty-card {
            visibility: hidden;
            /* Oculta las tarjetas vacías pero mantiene el espacio */
>>>>>>> parent of aae4c1cf (Update Listado_OC.php)
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
<<<<<<< HEAD
        document.addEventListener('DOMContentLoaded', function () {
            function formatDetails(rowData, productData) {
                let productsHtml = productData.map((product, index) => `
                    <div class="product-card">
                        <h3>Producto: ${index + 1}</h3>
                        <label>Producto:</label> ${product.name}<br>
                        <label>Cantidad:</label> ${product.quantity}<br>
                        <label>¿Aplica Receta?:</label> ${product.prescription}<br>
                        <label>Tipo Preparación:</label> ${product.preparation}
                    </div>
                `).join('');

                return `
                    <div class="details-container">
                        <div class="products-info">
                            Mostrando ${productData.length} de ${productData.length} productos
                        </div>
                        ${productsHtml}
                    </div>
                `;
            }

=======
        $(document).ready(function () {
            function formatDetails(rowData, productData) {
                let productCards = productData.map((product, index) => `
        <div class="product-card">
            <h3>Producto: ${index + 1}</h3>
            <label>Producto:</label><span>${product.nombre}</span><br>
            <label>Cantidad:</label><span>${product.cantidad}</span><br>
            <label>¿Aplica Receta?:</label><span>${product.receta}</span><br>
            <label>Tipo Preparación:</label><span>${product.tipo}</span>
        </div>
    `).join('');

                // Agregar tarjetas vacías si faltan para completar 3 columnas
                const emptyCards = 3 - productData.length;
                for (let i = 0; i < emptyCards; i++) {
                    productCards += `<div class="product-card empty-card"></div>`;
                }

                return `<div class="details-container">${productCards}</div>`;
            }

            const productDetails = {
                0: [{ nombre: 'Polidocanol INY 1% 2ML', cantidad: '1000', receta: 'Sí', tipo: 'Inyectable' }],
                1: [
                    { nombre: 'Producto A', cantidad: '500', receta: 'No', tipo: 'Tableta' },
                    { nombre: 'Producto B', cantidad: '300', receta: 'Sí', tipo: 'Inyectable' }
                ],
                2: [
                    { nombre: 'Producto X', cantidad: '200', receta: 'Sí', tipo: 'Jarabe' },
                    { nombre: 'Producto Y', cantidad: '150', receta: 'No', tipo: 'Cápsula' },
                    { nombre: 'Producto Z', cantidad: '100', receta: 'Sí', tipo: 'Tableta' }
                ]
            };

>>>>>>> parent of aae4c1cf (Update Listado_OC.php)
            const table = $('#listado').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json"
                },
                columnDefs: [{ orderable: false, className: 'details-control', targets: 0 }],
                order: [[1, 'asc']],
            });

            $('#listado tbody').on('click', 'td.details-control', function () {
                const tr = $(this).closest('tr');
                const row = table.row(tr);
<<<<<<< HEAD

                const sampleProducts = [
                    [{ name: 'Producto A', quantity: 100, prescription: 'Sí', preparation: 'Inyectable' }],
                    [
                        { name: 'Producto B', quantity: 200, prescription: 'No', preparation: 'Tableta' },
                        { name: 'Producto C', quantity: 150, prescription: 'Sí', preparation: 'Líquido' }
                    ],
                    Array(20).fill({ name: 'Producto Genérico', quantity: 50, prescription: 'No', preparation: 'Polvo' })
                ];

                const rowIndex = row.index();
                const productData = sampleProducts[rowIndex % sampleProducts.length];
=======
                const index = table.row(tr).index();
>>>>>>> parent of aae4c1cf (Update Listado_OC.php)

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.find('.details-control').text('+');
                } else {
<<<<<<< HEAD
                    row.child(formatDetails(row.data(), productData)).show();
                    tr.find('.details-control').text('-');
=======
                    const details = formatDetails(row.data(), productDetails[index]);
                    row.child(details).show();
                    $(this).text('-');
>>>>>>> parent of aae4c1cf (Update Listado_OC.php)
                }
            });
        });
    </script>
</body>

</html>