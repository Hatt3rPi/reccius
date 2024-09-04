<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="stylesheet" href="../assets/css/Listados.css">
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
    let rolesCache = [];

    // Función para cargar los usuarios y los roles (solo una solicitud para roles)
    async function cargarUsuarios() {
        try {
            const [usuariosResponse, rolesResponse] = await Promise.all([
                fetch('./backend/administracion_usuarios/obtener_usuariosBE.php'),
                fetch('./backend/administracion_usuarios/obtener_rolesBE.php')
            ]);

            if (!usuariosResponse.ok || !rolesResponse.ok) {
                throw new Error('Error en la respuesta del servidor');
            }

            const usuariosData = await usuariosResponse.json();
            rolesCache = await rolesResponse.json(); // Guardar los roles en el cache

            const table = $('#usuariosTable').DataTable({
                data: usuariosData.data,
                columns: [
                    { data: 'id' },
                    { data: 'usuario' },
                    { data: 'nombre' },
                    { data: 'correo' },
                    { data: 'cargo' },
                    {
                        data: 'rol',
                        render: function (data, type, row) {
                            return `<select class="rolSelect" data-id="${row.id}">
                                        ${getRolesOptions(row.rol_id)}
                                    </select>`;
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
                const data = table.row($(this).parents('tr')).data();
                const usuario_id = data.id;
                const rol_id = $(this).parents('tr').find('.rolSelect').val();

                actualizarRolUsuario(usuario_id, rol_id);
            });

        } catch (error) {
            console.error('Error al cargar los usuarios o roles:', error);
        }
    }

    // Función para obtener las opciones de los roles desde el cache
    function getRolesOptions(rolActualId) {
        let options = '';

        rolesCache.forEach(rol => {
            const selected = rol.id == rolActualId ? 'selected' : '';
            options += `<option value="${rol.id}" ${selected}>${rol.nombre}</option>`;
        });

        return options;
    }

    // Función para actualizar el rol del usuario
    async function actualizarRolUsuario(usuario_id, rol_id) {
        try {
            const response = await fetch('./backend/administracion_usuarios/asignar_permisosBE.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `usuario_id=${usuario_id}&rol_id=${rol_id}`
            });

            if (!response.ok) throw new Error('Error al actualizar el rol');
            alert('Rol actualizado correctamente');

        } catch (error) {
            console.error('Error al actualizar el rol:', error);
        }
    }

    // Cargar la tabla cuando el documento esté listo
    $(document).ready(function () {
        cargarUsuarios();
    });
</script>

</html>
