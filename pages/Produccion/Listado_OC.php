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
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }

        .product-card {
            flex: 0 0 calc(16.66% - 15px);
            min-width: calc(16.66% - 15px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px;
            background-color: #ffffff;
            border-radius: 6px;
            border: 1px solid #ced4da;
            text-align: left;
            transition: transform 0.2s, box-shadow 0.2s;
            font-size: 12px;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .product-card h3 {
            margin-top: 0;
            font-size: 14px;
            font-weight: bold;
            color: #495057;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }

        .product-card label {
            font-weight: bold;
            color: #6c757d;
            display: block;
            margin-bottom: 3px;
        }

        .product-card span {
            color: #343a40;
            display: block;
            margin-bottom: 5px;
        }

        .products-info {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 10px;
            text-align: left;
        }

        .estado-completado {
            background-color: #28a745;
            color: #fff;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            text-align: center;
        }

        .estado-pendiente {
            background-color: #ffc107;
            color: #fff;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            text-align: center;
        }

        .icono-detalles {
            font-size: 16px;
            color: #007bff;
            cursor: pointer;
        }

        .icono-detalles:hover {
            color: #0056b3;
        }

        .filtros-container {
            margin-bottom: 20px;
        }

        .filtro-boton {
            margin-right: 10px;
            padding: 5px 10px;
            font-size: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .filtro-boton.completado {
            background-color: #28a745;
            color: #fff;
        }

        .filtro-boton.pendiente {
            background-color: #ffc107;
            color: #fff;
        }

        .filtro-boton.todos {
            background-color: #6c757d;
            color: #fff;
        }
    </style>
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
                ],
                [
                    { nombre: "Producto 1", cantidad: 50, receta: "No", tipo: "Tableta" },
                    { nombre: "Producto 2", cantidad: 100, receta: "Sí", tipo: "Inyectable" },
                    { nombre: "Producto 3", cantidad: 150, receta: "No", tipo: "Jarabe" },
                    { nombre: "Producto 4", cantidad: 200, receta: "Sí", tipo: "Polvo" },
                    { nombre: "Producto 5", cantidad: 250, receta: "No", tipo: "Cápsula" },
                    { nombre: "Producto 6", cantidad: 300, receta: "Sí", tipo: "Jarabe" }
                ]
            ];


            $('#listado tbody').on('click', 'td.details-control', function () {
                const tr = $(this).closest('tr');
                const row = table.row(tr);

                if (row.child.isShown()) {
                    row.child.hide();
                    $(this).html('<i class="icono-detalles fas fa-plus-circle"></i>');
                } else {
                    const rowIndex = table.row(tr).index();
                    const productData = productDataSimulated[rowIndex];
                    row.child(formatDetails(row.data(), productData)).show();
                    $(this).html('<i class="icono-detalles fas fa-minus-circle"></i>');
                }
            });

            $('.filtro').on('click', function () {
                const estado = $(this).data('estado');
                table.rows().every(function () {
                    const tr = $(this.node());
                    tr.toggle(estado === 'Todos' || tr.data('estado') === estado);
                });
            });
        });
    </script>

</body>

</html>