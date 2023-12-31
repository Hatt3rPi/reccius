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
         $('#dynamic-content').hide();
        $('#loading-spinner').show();
        $('#dynamic-content').load('crear_usuario.php', function () {
            // Llamar a la función de inicialización después de cargar el formulario
            obtenNotificaciones();
            inicializarFormularioCrearUsuario();
            $('#loading-spinner').hide();
            $('#dynamic-content').show();
        });
    });
});

$(document).ready(function () {
    $('#asignar-roles').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
         $('#dynamic-content').hide();
        $('#loading-spinner').show();
        // Cargar el formulario de asignación de roles dentro del div #dynamic-content
        obtenNotificaciones();
        $('#dynamic-content').load('asignar_roles.php');
        $('#loading-spinner').hide();
        $('#dynamic-content').show();
    });
});
$(document).ready(function () {
    $('#configuracion').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
         $('#dynamic-content').hide();
        $('#loading-spinner').show();
        // Cargar el formulario de configuración dentro del div #dynamic-content
        obtenNotificaciones();
        $('#dynamic-content').load('modificar_perfil.php');
        $('#loading-spinner').hide();
        $('#dynamic-content').show();
    });
});

$(document).ready(function () {
    $('#especificacion_producto').click(function (event) {
        event.preventDefault();
         $('#dynamic-content').hide();
        $('#loading-spinner').show();
        console.log('El enlace de solicitud de análisis fue clickeado.');
        obtenNotificaciones();
        $('#dynamic-content').load('especificacion_producto.php?nuevo=true', function () {
            // Llamar a la función de inicialización después de cargar el formulario
            carga_tabla('FQ');
            carga_tabla('MB');
            $('#loading-spinner').hide();
            $('#dynamic-content').show();
        });
        
        console.log('Proceso finalizado');
    });
});

$(document).ready(function () {
    $('#preparacion_solicitud').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
         $('#dynamic-content').hide();
        $('#loading-spinner').show();
        console.log('El enlace de solicitud de análisis fue clickeado.'); // Confirmar que el evento click funciona

        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('preparacion_solicitud.php', function (response, status, xhr) {
            if (status == "error") {
                console.log("Error al cargar el formulario: " + xhr.status + " " + xhr.statusText); // Mostrar errores de carga
            } else {
                obtenNotificaciones();
                console.log('Formulario cargado exitosamente.'); // Confirmar que la carga fue exitosa
            }
            $('#loading-spinner').hide();
            $('#dynamic-content').show();
        });
    });
});

$(document).ready(function () {
     $('#dynamic-content').hide();
    $('#loading-spinner').show();
    $('#preparacion_solicitud').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        // Cargar el formulario de configuración dentro del div #dynamic-content
        obtenNotificaciones();
        $('#testing').load('preparacion_solicitud.php');
        $('#loading-spinner').hide();
        $('#dynamic-content').show();
    });
    $('#loading-spinner').hide();
});


$(document).ready(function () {
    $('#preparacion_analisis').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
         $('#dynamic-content').hide();
        $('#loading-spinner').show();
        console.log('El enlace de solicitud de análisis fue clickeado.'); // Confirmar que el evento click funciona

        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('preparacion_analisis.html', function (response, status, xhr) {
            if (status == "error") {
                console.log("Error al cargar el formulario: " + xhr.status + " " + xhr.statusText); // Mostrar errores de carga
            } else {
                obtenNotificaciones();
                console.log('Formulario cargado exitosamente.'); // Confirmar que la carga fue exitosa
            }
            $('#loading-spinner').hide();
            $('#dynamic-content').show();
        });
    });
});

$(document).ready(function () {
    $('#acta_liberacion').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
         $('#dynamic-content').hide();
        $('#loading-spinner').show();
        console.log('El enlace de solicitud de análisis fue clickeado.'); // Confirmar que el evento click funciona

        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('acta_liberacion.html', function (response, status, xhr) {
            if (status == "error") {
                console.log("Error al cargar el formulario: " + xhr.status + " " + xhr.statusText); // Mostrar errores de carga
            } else {
                obtenNotificaciones();
                console.log('Formulario cargado exitosamente.'); // Confirmar que la carga fue exitosa
            }
            $('#loading-spinner').hide();
            $('#dynamic-content').show();
        });
    });
});

$(document).ready(function () {
    $('#resultados_laboratorio').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
         $('#dynamic-content').hide();
        $('#loading-spinner').show();
        console.log('El enlace de solicitud de análisis fue clickeado.'); // Confirmar que el evento click funciona

        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('ingreso_resultados_laboratorio.html', function (response, status, xhr) {
            if (status == "error") {
                console.log("Error al cargar el formulario: " + xhr.status + " " + xhr.statusText); // Mostrar errores de carga
            } else {
                obtenNotificaciones();
                console.log('Formulario cargado exitosamente.'); // Confirmar que la carga fue exitosa
            }
            $('#loading-spinner').hide();
            $('#dynamic-content').show();
        });
    });
});

$(document).ready(function () {
    $('#listado_especificacion_producto').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        $('#dynamic-content').hide();
        $('#loading-spinner').show();
        console.log('El enlace de solicitud de análisis fue clickeado.'); // Confirmar que el evento click funciona

        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('listado_especificaciones_producto.php', function (response, status, xhr) {
            if (status == "error") {
                console.log("Error al cargar el formulario: " + xhr.status + " " + xhr.statusText); // Mostrar errores de carga
            } else {
                console.log('Listado cargado correctamente cargado exitosamente.'); // Confirmar que la carga fue exitosa
                obtenNotificaciones();
                
                carga_listadoEspecificacionesProductos();
            }
            $('#loading-spinner').hide();
            $('#dynamic-content').show();
        });
    });
});
$(document).ready(function () {
    $('#logo').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        $('#dynamic-content').hide();
        $('#loading-spinner').show();
        console.log('El enlace de solicitud de análisis fue clickeado.'); // Confirmar que el evento click funciona

        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('index_superadmin.php', function (response, status, xhr) {
            if (status == "error") {
                console.log("Error al cargar el formulario: " + xhr.status + " " + xhr.statusText); // Mostrar errores de carga
            } else {
                console.log('Listado cargado correctamente cargado exitosamente.'); // Confirmar que la carga fue exitosa
                obtenNotificaciones();
                cargaTrazabilidad();
            }
            $('#loading-spinner').hide();
            $('#dynamic-content').show();
        });
    });
});
$(document).ready(function () {
    $('#notificaciones').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        $('#dynamic-content').hide();
        $('#loading-spinner').show();
        console.log('El enlace de solicitud de análisis fue clickeado.'); // Confirmar que el evento click funciona

        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('listado_tareas.php', function (response, status, xhr) {
            if (status == "error") {
                console.log("Error al cargar el formulario: " + xhr.status + " " + xhr.statusText); // Mostrar errores de carga
            } else {
                console.log('Listado cargado correctamente cargado exitosamente.'); // Confirmar que la carga fue exitosa
                obtenNotificaciones();
                cargaListadoTareas();
            }
            $('#loading-spinner').hide();
            $('#dynamic-content').show();
        });
    });
});
function botones(id, accion, base) {
    switch (base){
        case "especificacion":{
            switch (accion) {
                case "revisar": {
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
                            $('#dynamic-content').hide();
                            $('#loading-spinner').show();
                            console.log('especificacion_producto redirigida con éxito ');
                            $('#dynamic-content').html(response); // Inserta el contenido en el elemento del DOM
                            cargarDatosEspecificacion(id);
                            $('#loading-spinner').hide();
                            $('#dynamic-content').show();
                        },
                        error: function(xhr, status, error) {
                            // Esta función se ejecuta en caso de error en la solicitud
                            console.error("Error en la solicitud: ", status, error);
                        }
                    });
                    
                    console.log('Proceso finalizado');
                    break;
                }
                case "generar_documento": {
                    console.log('El enlace de solicitud de análisis fue clickeado desde listado.');
                    
                    $.ajax({
                        url: 'documento_especificacion_producto.php', // URL del script PHP
                        type: 'POST', // Tipo de solicitud
                        data: { 
                            'id': id,
                            'accion': accion
                             }, // Datos que se enviarán con la solicitud
                        success: function(response) {
                            // Esta función se ejecuta cuando la solicitud es exitosa
                            console.log('especificacion_producto redirigida con éxito ');
                            $('#dynamic-content').html(response); // Inserta el contenido en el elemento del DOM
                            cargarDatosEspecificacion(id);
                        },
                        error: function(xhr, status, error) {
                            // Esta función se ejecuta en caso de error en la solicitud
                            console.error("Error en la solicitud: ", status, error);
                        }
                    });
                    
                    console.log('Proceso finalizado');
                    break;
                }
            }
        }
    }

}
function obtenNotificaciones() {
    fetch('../pages/backend/login/notificaciones.php')
        .then(response => response.json())
        .then(data => {
            const notificationCountElement = document.querySelector('.notification-count');
            contador_notificaciones
            if (data.count > 0) {
                $('#contador_notificaciones').text(data.count).show();
            } else {
                $('#contador_notificaciones').text(0).hide();
            }
        })
        .catch(error => console.error('Error:', error));
}
$(document).ready(function () {
    $('#testeo').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
        $('#dynamic-content').hide();
        $('#loading-spinner').show();
        console.log('El enlace de solicitud de análisis fue clickeado.'); // Confirmar que el evento click funciona

        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('DocumentsEspecs2.html', function (response, status, xhr) {
            if (status == "error") {
                console.log("Error al cargar el formulario: " + xhr.status + " " + xhr.statusText); // Mostrar errores de carga
            } else {
                obtenNotificaciones();
                console.log('Formulario cargado exitosamente.'); // Confirmar que la carga fue exitosa
            }
            $('#loading-spinner').hide();
            $('#dynamic-content').show();
        });
    });
});
$(window).on('load', function() {
    // Oculta el spinner una vez que se haya cargado toda la página
    $('#loading-spinner').hide();
    // Asegúrate de que el contenido dinámico se muestre
    $('#dynamic-content').show();
});