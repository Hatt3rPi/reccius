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
    // Función para cargar los usuarios en la tabla
    async function cargarUsuarios() {
        try {
            const response = await fetch('./backend/administracion_usuarios/obtener_usuariosBE.php');
            if (!response.ok) throw new Error('Error en la respuesta del servidor');

            const data = await response.json();

            const table = $('#usuariosTable').DataTable({
                data: data.data,
                columns: [
                    { data: 'id' },
                    { data: 'usuario' },
                    { data: 'nombre' },
                    { data: 'correo' },
                    { data: 'cargo' }, 
                    {
                        data: 'rol',
                        render: function(data, type, row) {
                            return `<select class="rolSelect" data-id="${row.id}">
                                        ${getRolesOptions(row.rol_id, data)}
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
            $('#usuariosTable tbody').on('click', '.btnGuardar', function() {
                const data = table.row($(this).parents('tr')).data();
                const usuario_id = data.id;
                const rol_id = $(this).parents('tr').find('.rolSelect').val();

                actualizarRolUsuario(usuario_id, rol_id);
            });

        } catch (error) {
            console.error('Error al cargar los usuarios:', error);
        }
    }

    // Función para obtener las opciones de los roles
    async function getRolesOptions(rolActualId, rolActualNombre) {
        try {
            const response = await fetch('./backend/administracion_usuarios/obtener_rolesBE.php'); // Ruta que devuelve los roles de la base de datos
            if (!response.ok) throw new Error('Error al cargar los roles');

            const roles = await response.json();
            let options = '';

            roles.forEach(rol => {
                const selected = rol.id === rolActualId ? 'selected' : '';
                options += `<option value="${rol.id}" ${selected}>${rol.nombre}</option>`;
            });

            return options;

        } catch (error) {
            console.error('Error al obtener los roles:', error);
            // Retornar una opción vacía si hay un error
            return `<option value="${rolActualId}" selected>${rolActualNombre}</option>`;
        }
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
    $(document).ready(function() {
        cargarUsuarios();
    });
</script>

</html>
