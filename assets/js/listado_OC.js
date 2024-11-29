$(document).ready(function () {
    // Formatear los productos para mostrar al hacer clic
    function format(rowData) {
        return `
            <table class="table table-bordered" style="margin-left: 50px;">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>¿Aplica Receta?</th>
                        <th>Tipo Preparación</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>POLIDOCANOL INY 1% 2ML</td>
                        <td>1000</td>
                        <td>Sí</td>
                        <td>Inyectable</td>
                    </tr>
                    <tr>
                        <td>Otro Producto</td>
                        <td>500</td>
                        <td>No</td>
                        <td>Oral</td>
                    </tr>
                </tbody>
            </table>
        `;
    }

    // Inicializar DataTable
    const table = $('#listado').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json"
        },
        columnDefs: [
            {
                targets: 0,
                orderable: false,
                className: 'details-control',
                defaultContent: '<i class="fas fa-plus-circle"></i>'
            }
        ]
    });

    // Evento al hacer clic en el ícono de expansión
    $('#listado tbody').on('click', 'td.details-control', function () {
        const tr = $(this).closest('tr');
        const row = table.row(tr);

        if (row.child.isShown()) {
            // Cerrar el contenido
            row.child.hide();
            $(this).html('<i class="fas fa-plus-circle"></i>');
        } else {
            // Mostrar el contenido
            row.child(format(row.data())).show();
            $(this).html('<i class="fas fa-minus-circle"></i>');
        }
    });
});
