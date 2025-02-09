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

                </tbody>
            </table>
        </div>
    </div>
</body>

<script>
        async function cargarUsuarios() {
            const [usuariosResponse, rolesResponse] = await Promise.all([
                fetch('./backend/administracion_usuarios/obtener_usuariosBE.php'),
                fetch('./backend/administracion_usuarios/obtener_rolesBE.php'),
                fetch('./backend/paginas/pagesBe.php')
            ]);
        }
        cargarUsuarios();
</script>

</html>