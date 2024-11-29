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
            </tbody>
        </table>
    </div>

    <!-- Script para manejar DataTables -->
    <script>
        $(document).ready(function () {
            // Función para el formato de los detalles de cada orden
            function formatDetails(rowData) {
                return `
                    <div class="details-container">
                        <div class="product-card">
                            <h3>Producto: 1</h3>
                            <label>Producto:</label><span>Polidocanol INY 1% 2ML</span>
                            <label>Cantidad:</label><span>1000</span>
                            <label>¿Aplica Receta?:</label><span>Sí</span>
                            <label>Tipo Preparación:</label><span>Inyectable</span>
                        </div>
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

            // Manejador para las filas desplegables
            $('#listado tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    row.child.hide();
                    $(this).text('+');
                } else {
                    row.child(formatDetails(row.data())).show();
                    $(this).text('-');
                }
            });
        });
    </script>
</body>

</html>
