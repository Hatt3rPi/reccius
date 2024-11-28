<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Órdenes de Compra</title>

    <!-- Tu CSS personalizado -->
    <link rel="stylesheet" href="../assets/css/Listados.css">
</head>

<body>
    <div class="form-container">
        <h1>Listado de Órdenes de Compra</h1>
        <h2 class="section-title">Órdenes Registradas</h2>
        <table id="ordenes-listado" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID Orden</th>
                    <th>Proveedor</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Productos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Proveedor A</td>
                    <td>2024-11-25</td>
                    <td>Pendiente</td>
                    <td>$1,200,000</td>
                    <td>10</td>
                    <td>
                        <button class="accion-btn action-button" title="Ver">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="accion-btn action-button" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="accion-btn action-button" title="Eliminar">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Proveedor B</td>
                    <td>2024-11-20</td>
                    <td>Completada</td>
                    <td>$850,000</td>
                    <td>8</td>
                    <td>
                        <button class="accion-btn action-button" title="Ver">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="accion-btn action-button" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="accion-btn action-button" title="Eliminar">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Proveedor C</td>
                    <td>2024-11-15</td>
                    <td>Cancelada</td>
                    <td>$600,000</td>
                    <td>5</td>
                    <td>
                        <button class="accion-btn action-button" title="Ver">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="accion-btn action-button" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="accion-btn action-button" title="Eliminar">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Inicialización de DataTables -->
    <script>
        $(document).ready(function () {
            $('#ordenes-listado').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json"
                }
            });
        });
    </script>
</body>

</html>
