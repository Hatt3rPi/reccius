<?php
session_start();
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="../assets/css/ListadoEspecs.css">
    <!-- Incluye aquí otros archivos necesarios (jQuery, DataTables CSS, FontAwesome, etc.) -->
</head>
<body>
    <div class="form-container">
        <h1>Listado de Tareas</h1>
        <div id="contenedor_tareas">
            <table id="listado_tareas" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Descripción</th>
                        <th>Usuario Creador</th>
                        <th>Usuario Ejecutor</th>
                        <th>Fecha Ingreso</th>
                        <th>Fecha Vencimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los datos dinámicos de la tabla se insertarán aquí -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Incluye aquí tu script de DataTables y las funciones para las acciones de las tareas
        function cargaListadoTareas() {
            var table = $('#listado_tareas').DataTable({
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
                        "width": "5%"
                    },
                    { "data": "prioridad" },
                    { "data": "estado" },
                    { "data": "descripcion_tarea" },
                    { "data": "usuario_creador" },
                    { "data": "usuario_ejecutor" },
                    { "data": "fecha_ingreso" },
                    { "data": "fecha_vencimiento" },

                    
                    {
                    title: 'id_tarea',
                    data: 'id',
                    defaultContent: '', // Puedes cambiar esto si deseas poner contenido por defecto
                    visible: false // Esto oculta la columna
                }
                ],
            });
            $('#listado_tareas tbody').on('click', 'td.details-control', function() {
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
            var acciones = '<table background-color="#F6F6F6" color="#FFF" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
            acciones += '<tr><td VALIGN="TOP">Acciones:</td><td>';

            // Agrega acciones según el estado de la tarea
            if (d.estado === 'Activa' || d.estado === 'Vencida') {
                acciones += '<button class="accion-btn" title="Recordar Tarea" id="' + d.id + '" name="recordar" onclick="botones(this.id, this.name, \'tareas\')" ><i class="fas fa-check"></i></button><a> </a>';
                acciones += '<button class="accion-btn" title="Cambiar Usuario Ejecutor" id="' + d.id + '" name="cambiar_usuario" onclick="botones(this.id, this.name, \'tareas\')" ><i class="fas fa-user-edit"></i></button><a> </a>';
            }
            
            // Si la tarea está finalizada, muestra acciones diferentes o ninguna acción
            if (d.estado === 'Finalizada') {
                acciones += 'No hay acciones disponibles para tareas finalizadas.';
            }

            acciones += '</td></tr></table>';
            return acciones;
        }
        }
    </script>
</body>
</html>
