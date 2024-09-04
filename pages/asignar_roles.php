<?php
session_start();

// Verificar si la variable de sesión "usuario" no está establecida o está vacía.
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    // Redirigir al usuario a la página de inicio de sesión.
    header("Location: login.html");
    exit;
}
?>
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
        var table = $('#usuariosTable').DataTable({
            "ajax": "./backend/obtener_usuarios.php", // Ruta del archivo PHP que devuelve la lista de usuarios en JSON
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json",
            },
            "columns": [
                { "data": "id", "title": "ID" },
                { "data": "usuario", "title": "Usuario" },
                { "data": "nombre", "title": "Nombre" },
                { "data": "correo", "title": "Correo" },
                { 
                    "data": "rol", 
                    "title": "Rol",
                    "render": function(data, type, row) {
                        return `
                            <select class="rolSelect" data-id="${row.id}">
                                ${getRolesOptions(data)}
                            </select>
                        `;
                    }
                },
                {
                    "data": null,
                    "defaultContent": '<button class="btnGuardar">Guardar</button>',
                    "orderable": false
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
        fetch('./backend/asignar_permisosBE.php', {
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

    // Cargar la tabla cuando el documento esté listo
    $(document).ready(function() {
        cargarUsuarios();
    });
</script>

</html>
