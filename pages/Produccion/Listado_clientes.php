<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Clientes</title>
    <!-- Enlace al archivo CSS -->
    <link rel="stylesheet" href="../assets/css/Listados.css">
    <link rel="stylesheet" href="../assets/css/modal_produccion.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Feature Flags Configuration -->
    <script src="../../assets/js/features_customware.js"></script>
</head>

<body>
    <!-- Control de acceso por feature flag -->
    <script>
    if (typeof AppConfig === 'undefined' || !AppConfig.FLAGS.experimental_produccion) {
        document.body.innerHTML = '';
        document.write(`
            <div style="font-family: Arial, sans-serif; text-align: center;background-color: #f8f8f8;">
                <h1 style="color: #333; text-align: center;">¡Estamos trabajando en ello!</h1>
                <p style="color: #555;text-align: center;">Gracias por tu interés en esta nueva función. Nuestro equipo está trabajando arduamente para ofrecerte una experiencia aún mejor. Esta característica estará disponible muy pronto.</p>
                <p style="color: #555;text-align: center;">Te pedimos un poco de paciencia mientras preparamos todo para que disfrutes de esta nueva funcionalidad al máximo. ¡Estamos emocionados por compartirla contigo!</p>
                <p style="color: #555;text-align: center;">Mientras tanto, no dudes en explorar el resto de nuestras increíbles herramientas y servicios. ¡Hay mucho más por descubrir!</p>
            </div>
        `);
    }
    </script>
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
