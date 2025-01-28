<?php
//archivo: pages\listado_tareas.php
session_start();
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}
require_once "/home/customw2/conexiones/config_reccius.php";
$query = "SELECT usuario, nombre FROM usuarios ORDER BY nombre";
$result = mysqli_query($link, $query);

$usuarios = [];
while ($row = mysqli_fetch_assoc($result)) {
    $usuarios[] = $row;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>

    <link rel="stylesheet" href="../assets/css/Listados.css">
    <link rel="stylesheet" href="../assets/css/Modal_tarea.css">
    <!-- Incluye aquí otros archivos necesarios (jQuery, DataTables CSS, FontAwesome, etc.) -->
</head>

<body>
    <div class="form-container">
        <h1>Listado de Tareas</h1>
        <div class="estado-filtros">
            <label> Filtrar por:</label>
            <button class="estado-filtro badge resaltar" onclick="filtrar_listado_usuario('')">Mis Tareas</button>
            <button class="estado-filtro badge badge-primary" onclick="filtrar_listado_estado('Activo')">Activo</button>
            <button class="estado-filtro badge badge-warning"
                onclick="filtrar_listado_estado('Fecha de Vencimiento cercano')">Fecha de Vencimiento cercano</button>
            <button class="estado-filtro badge badge-danger"
                onclick="filtrar_listado_estado('Atrasado')">Atrasado</button>
            <button class="estado-filtro badge badge-dark"
                onclick="filtrar_listado_estado('Finalizado')">Finalizado</button>
            <button class="estado-filtro badge" onclick="filtrar_listado_estado('')">Todos</button>
        </div>
        <br>
        <br>
        <div id="contenedor_tareas">
            <table id="listado" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>Nro Tarea</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Descripción</th>
                        <th>Usuario Ejecutor</th>
                        <th>Fecha Vencimiento </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los datos dinámicos de la tabla se insertarán aquí -->
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal para Cambiar Usuario -->
    <div id="modalCambiarUsuario" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="modal-title">Re-asignar Tarea</h2>
            <form id="formCambiarUsuario">
                <!-- Campo oculto para el ID de la tarea -->
                <input type="hidden" id="idTarea" name="idTarea">
                
                <!-- Ejecutor original -->
                <div class="form-group">
                    <label for="ejecutorOriginal">Ejecutor original:</label>
                    <input id="ejecutorOriginal" name="ejecutorOriginal" type="text" readonly
                        style="background-color: #e9ecef">
                </div>

                <!-- Reasignar tarea -->
                <div class="form-group">
                    <label for="usuarioNuevo">Re-asignar tarea a:</label>
                    <select name="usuarioNuevo" id="usuarioNuevo">
                        <option value="">Selecciona el nuevo ejecutor</option>
                        <!-- Opciones generadas dinámicamente -->
                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?php echo htmlspecialchars($usuario['usuario']); ?>">
                                <?php echo htmlspecialchars($usuario['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Motivo del cambio -->
                <div class="form-group">
                    <label for="motivo">Motivo del cambio:</label>
                    <textarea id="motivo" name="motivo" rows="3" required></textarea>
                </div>

                <!-- Botones de acción -->
                <div class="form-actions">
                    <button type="submit" id="btnAceptar">Aceptar</button>
                    <button type="button" id="btnCancelar">Cancelar</button>
                </div>
            </form>
        </div>
    </div>





</body>

</html>
<script>
    // Incluye aquí tu script de DataTables y las funciones para las acciones de las tareas
    var usuarioActual = "<?php echo $_SESSION['nombre']; ?>";
    var usuarioEjecutorOriginal = "";

    function cargaListadoTareas() {
        var table = $('#listado').DataTable({
            "ajax": "./backend/tareas/listado_tareasBE.php",
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
            },
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": '<i class="fas fa-search-plus"></i>',
                    "width": "20px"
                },
                {
                    title: 'Nro Tarea',
                    data: 'id',
                    defaultContent: '', // Puedes cambiar esto si deseas poner contenido por defecto
                    visible: true // Esto oculta la columna
                },
                { "data": "prioridad", "width": "50px" },
                {
                    "data": "estado",
                    "title": "Estado",
                    "width": "70px",
                    "render": function (data, type, row) {
                        switch (data) {
                            case 'Activo':
                                return '<span class="badge badge-primary">Activo</span>';
                            case 'Finalizado':
                                return '<span class="badge badge-dark">Finalizado</span>';
                            case 'Fecha de Vencimiento cercano':
                                return '<span class="badge badge-warning">Fecha de Vencimiento cercano</span>';
                            case 'Atrasado':
                                return '<span class="badge badge-danger">Atrasado</span>';
                            default:
                                return '<span class="badge">' + data + '</span>';
                        }
                    }
                },
                { "data": "descripcion_tarea" },
                {
                    "data": "usuario_ejecutor",
                    "render": function (data, type, row) {
                        return data === usuarioActual ? '<span class="resaltar">' + data + '</span>' : data;
                    }
                },
                { "data": "fecha_vencimiento", "width": "100px" }
            ],
        });
        $('#listado tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                // Esta fila ya está abierta - ciérrala
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Abre esta filaQ
                row.child(format(row.data())).show(); // Aquí llamas a la función que formatea el contenido expandido
                tr.addClass('shown');
            }
        });

        function format(d) {
            // `d` es el objeto de datos original para la fila
            var acciones = '<table style="background-color:#F6F6F6; color:#000; padding-left:50px;" cellpadding="5" cellspacing="0" border="1">';
            acciones += '<tr><td>Usuario Creador:</td><td>' + d.usuario_creador + '</td></tr>';
            acciones += '<tr><td>Fecha Ingreso:</td><td>' + d.fecha_ingreso + '</td></tr>';
            acciones += '<tr><td VALIGN="TOP">Acciones:</td><td>';

            // Agrega acciones según el estado de la tarea
            if (d.estado === 'Activo' || d.estado === 'Atrasado' || d.estado === 'Fecha de Vencimiento cercano') {




                acciones += '<button class="accion-btn ingControl" title="Recordar Tarea" id="' + d.id + '" name="recordar" onclick="botones(this.id, this.name, \'tareas\')" ><i class="fas fa-envelope"></i> Recordar tarea vía e-mail</button><a> </a>';
                //el siguiente botón debería ser visible solo para el usuario creador de la tarea o para el administrador
                acciones += '<button class="accion-btn" title="Cambiar Usuario Ejecutor" id="' + d.id + '" name="cambiar_usuario"  data-usuario_ejecutor="' + d.usuario_ejecutor_usuario + '" ><i class="fas fa-user-edit"></i> Cambiar usuario ejecutor</button><a> </a>';

                if (d.usuario_ejecutor === usuarioActual) {
                    acciones += '<button class="accion-btn ingControl" title="Finalizar Tarea" id="' + d.id_relacion + '" name="finalizar_tarea" onclick="botones(this.id, this.name, \'tareas\', \'' + d.tabla_relacion + '\', \'' + d.tipo + '\')"><i class="fas fa-check"></i>  Ir a finalizar tarea</button>';
                }
            }

            // Si la tarea está finalizada, muestra acciones diferentes o ninguna acción
            if (d.estado === 'Finalizada') {
                acciones += 'No hay acciones disponibles para tareas finalizadas.';
            }

            acciones += '</td></tr></table>';
            return acciones;
        }
    }
    function filtrar_listado_estado(estado) {
        var table = $('#listado').DataTable();
        table.column(3).search(estado).draw(); // Asumiendo que la columna 1 es la de
        if (estado == "") {
            table.column(5).search("").draw();
        }
    }
    function filtrar_listado_usuario() {
        var table = $('#listado').DataTable();
        table.column(5).search(usuarioActual).draw(); // Asumiendo que la columna 1 es la de
    }
    $(document).on('click', 'button[name="cambiar_usuario"]', function () {
        var tareaId = $(this).attr('id');
        var usuarioEjecutorOriginal = $(this).data('usuario_ejecutor');
        
        // Establecer los valores en el formulario
        $('#ejecutorOriginal').val(usuarioEjecutorOriginal);
        $('#idTarea').val(tareaId);
        
        console.log('ID Tarea:', tareaId); // Debug
        $('#modalCambiarUsuario').show();
    });

    // Cerrar el modal
    $('.close').click(function () {
        $('#modalCambiarUsuario').hide();
    });


    // Cerrar el modal al hacer clic en el botón Cancelar
    $('#btnCancelar').click(function () {
        $('#modalCambiarUsuario').hide();
    });


    // Reemplazar el manejador del formulario con esta versión:
    $('#formCambiarUsuario').on('submit', function (e) {
        e.preventDefault();
        
        // Verificar que el ID de la tarea esté presente
        var idTarea = $('#idTarea').val();
        if (!idTarea) {
            console.error('Error: ID de tarea no encontrado');
            alert('Error: ID de tarea no encontrado');
            return;
        }
        
        var datosFormulario = $(this).serialize();
        var usuarioNuevo = document.getElementById('usuarioNuevo').value;
        var usuarioOriginal = document.getElementById('ejecutorOriginal').value;
        
        console.log('Datos a enviar:', datosFormulario); // Debug

        if (!usuarioNuevo || usuarioNuevo === usuarioOriginal) {
            alert("Debes seleccionar un usuario diferente del actual.");
            return;
        }

        var confirmacion = confirm("¿Estás seguro de que deseas realizar este cambio?");
        if (confirmacion) {
            $.ajax({
                url: './backend/tareas/cambiar_usuarioBE.php',
                type: 'POST',
                data: datosFormulario,
                dataType: 'json', // Especificar que esperamos JSON
                success: function (response) {
                    if (response.exito) {
                        alert(response.mensaje);
                        $('#modalCambiarUsuario').hide();
                        $('#listado').DataTable().ajax.reload();
                    } else {
                        alert('Error: ' + response.mensaje);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', xhr.responseText);
                    alert('Error al cambiar el usuario. Por favor, intente nuevamente.');
                }
            });
        }
    });

</script>