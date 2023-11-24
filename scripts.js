$(document).ready(function() {
    $('#crear-usuario').click(function(event) {
        event.preventDefault(); // Esto previene la navegación estándar
        $('#dynamic-content').load('crear_usuario.html'); // Esto carga el contenido de crear_usuario.html en el div
    });
});

$(document).ready(function() {
    $('#asignar-roles').click(function(event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        // Cargar el formulario de asignación de roles dentro del div #dynamic-content
        $('#dynamic-content').load('asignar_roles.html');
    });
});
$(document).ready(function() {
    $('#configuracion').click(function(event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('configuracion.html');
    });
});
