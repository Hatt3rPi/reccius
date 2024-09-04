<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="stylesheet" href="../assets/css/Listados.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <div class="form-container">
        <h1>Administración / Gestión de Roles de Usuarios</h1>
        <br><br>
        <h2 class="section-title">Listado de Usuarios y Roles:</h2>

        <div id="contenedor_listado">
            <table id="usuariosTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Cargo</th>
                        <th>Rol</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los datos dinámicos de la tabla se insertarán aquí -->
                </tbody>
            </table>
        </div>
    </div>
</body>

<script>
    // Función para cargar los usuarios en la tabla
    function cargarUsuarios() {
        fetch('./backend/administracion_usuarios/obtener_usuariosBE.php') // Ruta del archivo PHP que devuelve la lista de usuarios en JSON
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json(); // Convertir la respuesta en JSON
            })
            .then(data => {
                var table = $('#usuariosTable').DataTable({
                    data: data.data, // Los datos que vienen desde la respuesta del fetch
                    columns: [
                        { data: 'id' },
                        { data: 'usuario' },
                        { data: 'nombre' },
                        { data: 'correo' },
                        { data: 'cargo' },  // Añadimos la columna cargo
                        {
                            data: 'rol',  // Ahora el rol viene desde la tabla roles
                            render: function(data, type, row) {
                                return `
                                    <select class="rolSelect" data-id="${row.id}">
                                        ${getRolesOptions(data)}
                                    </select>
                                `;
                            }
                        },
                        {
                            data: null,
                            defaultContent: '<button class="btnGuardar">Guardar</button>',
                            orderable: false
                        }
                    ]
                });

                // Evento para guardar el rol seleccionado
                $('#usuariosTable tbody').on('click', '.btnGuardar', function () {
                    var data = table.row($(this).parents('tr')).data();
                    var usuario_id = data.id;
                    var rol_id = $(this).parents('tr').find('.rolSelect').val();

                    actualizarRolUsuario(usuario_id, rol_id);
                });
            })
            .catch(error => {
                console.error('Error al cargar los usuarios:', error);
            });
    }

    // Función para obtener las opciones de los roles
    function getRolesOptions(rolActual) {
        const roles = ['Administrador', 'Supervisor Calidad', 'Vendedor', 'Invitado']; // Aquí podrías hacer que se carguen dinámicamente también
        let options = '';
        roles.forEach(rol => {
            const selected = rol === rolActual ? 'selected' : '';
            options += `<option value="${rol}" ${selected}>${rol}</option>`;
        });
        return options;
    }

    // Función para actualizar el rol del usuario
    function actualizarRolUsuario(usuario_id, rol_id) {
        fetch('./backend/administracion_usuarios/asignar_permisosBE.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `usuario_id=${usuario_id}&rol_id=${rol_id}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al actualizar el rol');
            }
            return response.text();
        })
        .then(data => {
            alert('Rol actualizado correctamente');
        })
        .catch(error => {
            console.error('Error al actualizar el rol:', error);
        });
    }

    // Cargar la tabla cuando el documento esté listo
    $(document).ready(function() {
        cargarUsuarios();
    });
</script>

</html>
