document.addEventListener("DOMContentLoaded", function () {
    const sidebarList = document.getElementById("sidebarList");

    sidebarList.addEventListener("click", function (event) {
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
function inicializarFormularioCrearUsuario() {
    // Cargar los roles disponibles
    fetch('backend/roles/roles.php')
        .then(response => response.json())
        .then(data => {
            const selectRol = document.getElementById('rol');
            data.forEach(rol => {
                let opcion = new Option(rol.nombre, rol.id);
                selectRol.add(opcion);
            });
        })
        .catch(error => {
            console.error('Error al cargar los roles:', error);
        });
}
$(document).ready(function () {
    $('#crear-usuario').click(function (event) {
        event.preventDefault();
        $('#dynamic-content').load('crear_usuario.html', function () {
            // Llamar a la función de inicialización después de cargar el formulario
            inicializarFormularioCrearUsuario();
        });
    });
});

$(document).ready(function () {
    $('#asignar-roles').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        // Cargar el formulario de asignación de roles dentro del div #dynamic-content
        $('#dynamic-content').load('asignar_roles.html');
    });
});
$(document).ready(function () {
    $('#configuracion').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('modificar_perfil.html');
    });
});
$(document).ready(function () {
    $('#crear_especificaciones_producto').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('crear_especificaciones_producto.html');
    });
});
$(document).ready(function () {
    $('#preparacion_solicitud').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        console.log('El enlace de solicitud de análisis fue clickeado.'); // Confirmar que el evento click funciona

        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('preparacion_solicitud.html', function (response, status, xhr) {
            if (status == "error") {
                console.log("Error al cargar el formulario: " + xhr.status + " " + xhr.statusText); // Mostrar errores de carga
            } else {
                console.log('Formulario cargado exitosamente.'); // Confirmar que la carga fue exitosa
            }
        });
    });
});

$(document).ready(function () {
    $('#preparacion_solicitud').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#testing').load('testing.html');
    });
});

