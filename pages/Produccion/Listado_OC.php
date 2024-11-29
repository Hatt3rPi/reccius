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
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 10px;
            margin: 10px 0;
        }

        .product-card {
            flex: 1 1 calc(33.333% - 10px); /* Mantiene siempre 1/3 del ancho */
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
        }

        .product-card label {
            font-weight: bold;
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
            visibility: hidden; /* Oculta las tarjetas vacías pero mantiene el espacio */
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
        $(document).ready(function () {
            function formatDetails(rowData, productData) {
                const productCards = productData.map((product, index) => `
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

            const table = $('#listado').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json"
                },
                columnDefs: [
                    { orderable: false, className: 'details-control', targets: 0 }
                ],
                order: [[1, 'asc']]
            });

            $('#listado tbody').on('click', 'td.details-control', function () {
                const tr = $(this).closest('tr');
                const row = table.row(tr);
                const index = table.row(tr).index();

                if (row.child.isShown()) {
                    row.child.hide();
                    $(this).text('+');
                } else {
                    const details = formatDetails(row.data(), productDetails[index]);
                    row.child(details).show();
                    $(this).text('-');
                }
            });
        });
    </script>
</body>

</html>
