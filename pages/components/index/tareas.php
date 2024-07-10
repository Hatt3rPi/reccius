<!-- Link a la hoja de estilos -->
<link rel="stylesheet" href="../../assets/css/components/Index_components/Component_Tarea.css">

<!-- Contenedor del componente -->
<div class="form-container">
    <h1>Listado de Tareas Activas</h1>
    <div id="contenedor_tareas">
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

<!-- Script para cargar los datos -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('../../pages/backend/tareas/Componente_tareasBE.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector('#listado tbody');
                tbody.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(tarea => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${tarea.prioridad}</td>
                            <td>${tarea.descripcion_tarea}</td>
                            <td>${tarea.fecha_vencimiento}</td>
                        `;
                        tbody.appendChild(row);
                    });
                } else {
                    const row = document.createElement('tr');
                    row.innerHTML = '<td colspan="3">No hay tareas activas</td>';
                    tbody.appendChild(row);
                }
            })
            .catch(error => {
                alert('Error al obtener los datos');
                console.error('Error:', error);
            });
    });
</script>
