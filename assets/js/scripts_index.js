document.addEventListener("DOMContentLoaded", function() {
    const sidebarList = document.getElementById("sidebarList");

    sidebarList.addEventListener("click", function(event) {
        const targetElement = event.target;

        if (targetElement.classList.contains("btn_lateral")) {
            const smenu = targetElement.nextElementSibling;

            if (smenu && smenu.classList.contains("smenu")) {
                if (smenu.style.maxHeight && smenu.style.maxHeight !== "0px") {
                    smenu.style.maxHeight = "0px";
                } else {
                    smenu.style.maxHeight = smenu.scrollHeight + "px";
                }
            }
        }
    });
});
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
        $('#dynamic-content').load('modificar_perfil.html');
    });
});
