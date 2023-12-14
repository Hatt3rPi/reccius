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
    fetch('../pages/backend/roles/rolesBE.php') // Asegúrate de que la ruta es correcta
        .then(response => {
            if (!response.ok) {
                // Si la respuesta no es exitosa, lanza un error
                throw new Error('La solicitud a rolesBE.php falló: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            const selectRol = document.getElementById('rol');
            if (!selectRol) {
                // Si no se encuentra el elemento select, lanza un error
                throw new Error('Elemento select con ID "rol" no encontrado.');
            }
            data.forEach(rol => {
                let opcion = new Option(rol.nombre, rol.id);
                selectRol.add(opcion);
            });
        })
        .catch(error => {
            // Maneja cualquier error que ocurra en la solicitud o procesamiento de la respuesta
            console.error('Error al cargar los roles:', error);
        });
}

$(document).ready(function () {
    $('#crear-usuario').click(function (event) {
        event.preventDefault();
        $('#dynamic-content').load('crear_usuario.php', function () {
            // Llamar a la función de inicialización después de cargar el formulario
            inicializarFormularioCrearUsuario();
        });
    });
});

$(document).ready(function () {
    $('#asignar-roles').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        // Cargar el formulario de asignación de roles dentro del div #dynamic-content
        $('#dynamic-content').load('asignar_roles.php');
    });
});
$(document).ready(function () {
    $('#configuracion').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('modificar_perfil.php');
    });
});

$(document).ready(function () {
    $('#especificacion_producto').click(function (event) {
        event.preventDefault();
        console.log('El enlace de solicitud de análisis fue clickeado.');
        
        $('#dynamic-content').load('especificacion_producto.php?nuevo=true', function () {
            // Llamar a la función de inicialización después de cargar el formulario
            carga_tablaFQ();
            carga_tablaMB();
        });
        
        console.log('Proceso finalizado');
    });
});

$(document).ready(function () {
    $('#preparacion_solicitud').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        console.log('El enlace de solicitud de análisis fue clickeado.'); // Confirmar que el evento click funciona

        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('preparacion_solicitud.php', function (response, status, xhr) {
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
        $('#testing').load('preparacion_solicitud.php');
    });
});


$(document).ready(function () {
    $('#preparacion_analisis').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        console.log('El enlace de solicitud de análisis fue clickeado.'); // Confirmar que el evento click funciona

        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('preparacion_analisis.html', function (response, status, xhr) {
            if (status == "error") {
                console.log("Error al cargar el formulario: " + xhr.status + " " + xhr.statusText); // Mostrar errores de carga
            } else {
                console.log('Formulario cargado exitosamente.'); // Confirmar que la carga fue exitosa
            }
        });
    });
});

$(document).ready(function () {
    $('#acta_liberacion').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        console.log('El enlace de solicitud de análisis fue clickeado.'); // Confirmar que el evento click funciona

        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('acta_liberacion.html', function (response, status, xhr) {
            if (status == "error") {
                console.log("Error al cargar el formulario: " + xhr.status + " " + xhr.statusText); // Mostrar errores de carga
            } else {
                console.log('Formulario cargado exitosamente.'); // Confirmar que la carga fue exitosa
            }
        });
    });
});

$(document).ready(function () {
    $('#resultados_laboratorio').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        console.log('El enlace de solicitud de análisis fue clickeado.'); // Confirmar que el evento click funciona

        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('ingreso_resultados_laboratorio.html', function (response, status, xhr) {
            if (status == "error") {
                console.log("Error al cargar el formulario: " + xhr.status + " " + xhr.statusText); // Mostrar errores de carga
            } else {
                console.log('Formulario cargado exitosamente.'); // Confirmar que la carga fue exitosa
            }
        });
    });
});

$(document).ready(function () {
    $('#listado_especificacion_producto').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        console.log('El enlace de solicitud de análisis fue clickeado.'); // Confirmar que el evento click funciona

        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('listado_especificaciones_producto.php', function (response, status, xhr) {
            if (status == "error") {
                console.log("Error al cargar el formulario: " + xhr.status + " " + xhr.statusText); // Mostrar errores de carga
            } else {
                console.log('Listado cargado correctamente cargado exitosamente.'); // Confirmar que la carga fue exitosa
                carga_listadoEspecificacionesProductos();
            }
        });
    });
});

function botones(id, accion, base) {
    switch (base){
        case "especificacion":{
            switch (accion) {
                case "editar": {
                    console.log('El enlace de solicitud de análisis fue clickeado desde listado.');
                    
                    $.ajax({
                        url: 'especificacion_producto.php', // URL del script PHP
                        type: 'POST', // Tipo de solicitud
                        data: { 
                            'id': id,
                            'accion': accion
                             }, // Datos que se enviarán con la solicitud
                        success: function(response) {
                            // Esta función se ejecuta cuando la solicitud es exitosa
                            $('#dynamic-content').html(response); // Inserta el contenido en el elemento del DOM
                            carga_tablaFQ(); // Llama a tus funciones adicionales
                            carga_tablaMB();
                        },
                        error: function(xhr, status, error) {
                            // Esta función se ejecuta en caso de error en la solicitud
                            console.error("Error en la solicitud: ", status, error);
                        }
                    });
                    
                    console.log('Proceso finalizado');
                }
                case "generar_documento": {
                        $.redirect('DocumentsEspecs2.html', {
                            'id': id
                        }, 'post');
                    break;
                }
                case "revisar": {
                    $.redirect('especificacion_producto.php', {
                        'id': id,
                        'base': base
                    }, 'post');
                    break;
                }
            }
        }
    }

}

$(document).ready(function () {
    $('#testeo').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        console.log('El enlace de solicitud de análisis fue clickeado.'); // Confirmar que el evento click funciona

        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('test2.html', function (response, status, xhr) {
            if (status == "error") {
                console.log("Error al cargar el formulario: " + xhr.status + " " + xhr.statusText); // Mostrar errores de carga
            } else {
                console.log('Formulario cargado exitosamente.'); // Confirmar que la carga fue exitosa
            }
        });
    });
});
