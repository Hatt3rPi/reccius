<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Clientes</title>
    <!-- Enlace al archivo CSS -->
    <link rel="stylesheet" href="../assets/css/Listados.css">
    <link rel="stylesheet" href="../assets/css/modal_produccion.css">
</head>

<body>
    <div class="form-container">
        <h1>Listado de Clientes</h1>
        <h2 class="section-title">Clientes Registrados</h2>
        <table id="listado" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo Electrónico</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Juan Pérez</td>
                    <td>juan.perez@example.com</td>
                    <td>+56 9 1234 5678</td>
                    <td>Calle Falsa 123, Santiago</td>
                    <td>
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

    <!-- Botón para abrir el modal -->
    <button id="btn-add-client" class="btn-add-client">Agregar Cliente</button>

    <!-- Modal para agregar cliente -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Ingreso de nuevo Cliente</h2>
            <div class="form-group">
                <label for="rut">Rut:</label>
                <input type="text" id="rut" name="rut">
            </div>
            <div class="form-group">
                <label for="cliente">Cliente:</label>
                <input type="text" id="cliente" name="cliente">
            </div>
            <div class="form-group">
                <label for="email-contacto">Email Contacto:</label>
                <input type="email" id="email-contacto" name="email-contacto">
            </div>
            <div class="form-group">
                <label for="condicion-pago">Condición de Pago:</label>
                <input type="text" id="condicion-pago" name="condicion-pago">
            </div>
            <div class="modal-footer">
                <button id="guardar-cliente">Guardar</button>
            </div>
        </div>
    </div>

    <!-- Enlace al archivo JavaScript -->
    <script src="../assets/js/modal_produccion.js"></script>
</body>

</html>
