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


const routes = [
    { id: "configuracion", toLoad: "modificar_perfil.html" },
    { id: "crear_especificaciones_producto", toLoad: "crear_especificaciones_producto.html" },
    { id: "asignar-roles", toLoad: "asignar_roles.html" },
    { id: "preparacion_solicitud", toLoad: "preparacion_solicitud.html" }]

for (i in routes) {
    const { id, toLoad } = routes[i]
    $(document).ready(function () {
        $(`#${id}`).click(function (event) {
            event.preventDefault(); // Prevenir la navegación predeterminada
            // Cargar el formulario de configuración dentro del div #dynamic-content
            $('#dynamic-content').load(toLoad);
        });
    });
};