<link rel="stylesheet" href="../assets/css/components/Index_components/Component_Tarea.css">

<div class="form-container">
    <h1>Listado de Tareas Activas</h1>
    <div id="contenedor_tareas">
        <a href="../../pages/listado_tareas.php" id="notificaciones" class="btn-link">Ver todas las tareas</a>
        <table id="listado" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Prioridad</th>
                    <th>Descripción</th>
                    <th>Fecha Vencimiento</th>
                </tr>
            </thead>
            <tbody>
                <!-- Los datos dinámicos de la tabla se insertarán aquí -->
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        $.ajax({
            url: '../pages/backend/tareas/Componente_tareasBE.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var data = response.data;
                var tbody = $('#listado tbody');
                tbody.empty();

                if (data.length > 0) {
                    $.each(data, function(index, tarea) {
                        var row = '<tr>' +
                            '<td>' + tarea.prioridad + '</td>' +
                            '<td>' + tarea.descripcion_tarea + '</td>' +
                            '<td>' + tarea.fecha_vencimiento + '</td>' +
                            '</tr>';
                        tbody.append(row);
                    });
                } else {
                    var row = '<tr><td colspan="3">No hay tareas activas</td></tr>';
                    tbody.append(row);
                }
            },
            error: function() {
                alert('Error al obtener los datos');
            }
        });
    });
</script>
