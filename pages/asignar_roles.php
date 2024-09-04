<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
</head>
<body>
    <div class="container">
        <h2>Lista de Usuarios</h2>
        <table id="usuariosTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <!-- El contenido de la tabla se llenará dinámicamente -->
            </tbody>
        </table>
    </div>

    <script>
        // Función para cargar los usuarios en la tabla
        function cargarUsuarios() {
            fetch('./backend/administracion_usuarios/obtener_usuariosBE.php') // Llama al archivo PHP que devuelve los usuarios
                .then(response => response.json())
                .then(data => {
                    const table = $('#usuariosTable').DataTable();
                    data.forEach(usuario => {
                        // Crear las filas dinámicamente para cada usuario
                        table.row.add([
                            usuario.id,
                            usuario.usuario,
                            usuario.nombre,
                            usuario.correo,
                            `<select class="rolSelect" data-id="${usuario.id}">
                                ${getRolesOptions(usuario.rol)}
                             </select>`,
                            `<button class="btnGuardar" data-id="${usuario.id}">Guardar</button>`
                        ]).draw(false);
                    });

                    // Agregar event listeners para los botones "Guardar"
                    document.querySelectorAll('.btnGuardar').forEach(button => {
                        button.addEventListener('click', function () {
                            const usuario_id = this.getAttribute('data-id');
                            const rol_id = this.closest('tr').querySelector('.rolSelect').value;

                            // Llamar a la función para actualizar el rol del usuario
                            actualizarRolUsuario(usuario_id, rol_id);
                        });
                    });
                })
                .catch(error => console.error('Error al cargar los usuarios:', error));
        }

        // Función para generar el selector de roles dinámico
        function getRolesOptions(rolActual) {
            const roles = ['Administrador', 'Supervisor Calidad', 'Vendedor', 'Invitado']; // Puedes obtener estos roles dinámicamente si es necesario
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
            .then(response => response.text())
            .then(data => {
                alert('Rol actualizado correctamente');
            })
            .catch(error => {
                console.error('Error al actualizar el rol:', error);
            });
        }

        // Inicializar DataTable y cargar los usuarios
        $(document).ready(function() {
            $('#usuariosTable').DataTable();
            cargarUsuarios();
        });
    </script>
</body>
</html>
