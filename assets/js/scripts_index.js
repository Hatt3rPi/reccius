// archivo assets\js\scripts_index.js
function featureNoDisponible(){
    event.preventDefault();
    $('#dynamic-content').hide();
    $('#loading-spinner').show();
    $('#dynamic-content').load('feature_en_contruccion.html', function () {
        $('#loading-spinner').hide();
        $('#dynamic-content').show();
    });
}

function desactivar_boton_temporalmente(elemento, tiempo = 500) {
    console.log('============== inicia bloqueo temporal ================');

    if (elemento.type === 'submit') {
        // Deshabilitar el botón de submit después de un pequeño retraso
        setTimeout(function() {
            elemento.disabled = true;
        }, 0);
    } else {
        elemento.disabled = true;  // Deshabilitar el botón inmediatamente
    }

    // Reactivar el botón después del tiempo especificado
    setTimeout(function() {
        elemento.disabled = false;  // Habilitar el botón nuevamente después de 'tiempo' milisegundos
    }, tiempo);
}

$(document).on('click', '.ingControl', function(event) {
    // Verificar si el botón no es de tipo 'submit'
    if ($(this).attr('type') !== 'submit') {
        console.log('Botón tipo submit');
       // event.preventDefault();  // Evitar la acción predeterminada solo para botones que no sean 'submit'
    } else {
        console.log('Botón tipo button');
    }
    desactivar_boton_temporalmente(this);
});


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
        if(AppConfig.FLAGS.crear_usuario){
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
        }
        else
        {
            featureNoDisponible();
        }
    });
});

$(document).ready(function () {
    $('#asignar-roles').click(function (event) {
        if(AppConfig.FLAGS.asignarRoles){
        event.preventDefault(); // Prevenir la navegación predeterminada
         $('#dynamic-content').hide();
        $('#loading-spinner').show();
        // Cargar el formulario de asignación de roles dentro del div #dynamic-content
        obtenNotificaciones();
        $('#dynamic-content').load('asignar_roles.php');
        $('#loading-spinner').hide();
        $('#dynamic-content').show();
        } else {
            featureNoDisponible();
        }
    });
});
$(document).ready(function () {
    $('#configuracion').click(function (event) {
        if(AppConfig.FLAGS.configuracion_perfil){
        event.preventDefault(); // Prevenir la navegación predeterminada
         $('#dynamic-content').hide();
        $('#loading-spinner').show();
        // Cargar el formulario de configuración dentro del div #dynamic-content
        obtenNotificaciones();
        $('#dynamic-content').load('modificar_perfil.php', function () {
            cargarInformacionExistente();
        });
        
        $('#loading-spinner').hide();
        $('#dynamic-content').show();
        } else {
            featureNoDisponible();
        }
    });
});

$(document).ready(function () {
    $('#especificacion_producto').click(function (event) {
        if(AppConfig.FLAGS.especificacionProducto_creacion){
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
        } else {
            featureNoDisponible();
        }
    });
});

$(document).ready(function () {
    $('#home').click(function (event) {
        event.preventDefault(); // Prevenir la navegación predeterminada
         $('#dynamic-content').hide();
        $('#loading-spinner').show();
        console.log('El enlace de solicitud de análisis fue clickeado.'); 
        // Confirmar que el evento click funciona

        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('index_administrador.php', function (response, status, xhr) {
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
        if(AppConfig.FLAGS.ingreso_resultados_laboratorio){
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
        } else {
            featureNoDisponible();
        }
    });
});

// Cotizador

$(document).ready(function () {
    $('#cotizador_ingreso').click(function (event) {
        if(AppConfig.FLAGS.recetario_magistral){
        event.preventDefault();
        $('#dynamic-content').hide();
        $('#loading-spinner').show();
        obtenNotificaciones();
        $('#dynamic-content').load('cotizador/ingreso_cotizacion.php?nuevo=true', function () {
            $('#loading-spinner').hide();
            $('#dynamic-content').show();
        });
        } else {
            featureNoDisponible();
        }
    });
});

$(document).ready(function () {
    $('#cotizador_busqueda').click(function (event) {
        if(AppConfig.FLAGS.recetario_magistral){
        event.preventDefault();
        $('#dynamic-content').hide();
        $('#loading-spinner').show();
        obtenNotificaciones();
        //$('#dynamic-content').load('cotizador/busqueda_cotizacion.php?nuevo=true', function () {
        $('#dynamic-content').load('TEST2.php', function () {
            $('#loading-spinner').hide();
            $('#dynamic-content').show();
        });
        } else {
            featureNoDisponible();
        }
    });
});

// Cotizador final

$(document).ready(function () {
    $('#listado_especificacion_producto').click(function (event) {
        
        if(AppConfig.FLAGS.especificacionProducto_listado){
            

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
                
                carga_listado_especificacionProducto();
            }
            $('#loading-spinner').hide();
            $('#dynamic-content').show();
        });
        } else {
            featureNoDisponible();
        }
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


$(window).on('load', function() {
    // Oculta el spinner una vez que se haya cargado toda la página
    $('#loading-spinner').hide();
    // Asegúrate de que el contenido dinámico se muestre
    $('#dynamic-content').show();
});

$(document).ready(function () {
    $('#listado_solicitudes_analisis').click(function (event) {
        if(AppConfig.FLAGS.analisis_externo_listado){
        event.preventDefault(); // Prevenir la navegación predeterminada
         $('#dynamic-content').hide();
        $('#loading-spinner').show();
        console.log('El enlace de solicitud de análisis fue clickeado.'); // Confirmar que el evento click funciona

        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('LABORATORIO_listado_solicitudes.php', function (response, status, xhr) {
            if (status == "error") {
                console.log("Error al cargar el formulario: " + xhr.status + " " + xhr.statusText); // Mostrar errores de carga
            } else {
                obtenNotificaciones();
                carga_listado();
                console.log('Formulario cargado exitosamente.'); // Confirmar que la carga fue exitosa
            }
            $('#loading-spinner').hide();
            $('#dynamic-content').show();
        });
        } else {
            featureNoDisponible();
        }
    });
});

$(document).ready(function () {
    $('#listado_productos_disponibles').click(function (event) {
        if(AppConfig.FLAGS.productos_disponibles_listado){
        event.preventDefault(); // Prevenir la navegación predeterminada
         $('#dynamic-content').hide();
        $('#loading-spinner').show();
        console.log('El enlace de solicitud de análisis fue clickeado.'); // Confirmar que el evento click funciona

        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('CALIDAD_listado_productosDisponibles.php', function (response, status, xhr) {
            if (status == "error") {
                console.log("Error al cargar el formulario: " + xhr.status + " " + xhr.statusText); // Mostrar errores de carga
            } else {
                obtenNotificaciones();
                carga_listado();
                console.log('Formulario cargado exitosamente.'); // Confirmar que la carga fue exitosa
            }
            $('#loading-spinner').hide();
            $('#dynamic-content').show();
        });
        } else {
            featureNoDisponible();
        }
    });
});

$(document).ready(function () {
    $('#listado_acta_muestreo').click(function (event) {
        if(AppConfig.FLAGS.acta_muestreo_listado){
        event.preventDefault(); // Prevenir la navegación predeterminada
         $('#dynamic-content').hide();
        $('#loading-spinner').show();
        console.log('El enlace de solicitud de análisis fue clickeado.'); // Confirmar que el evento click funciona

        // Cargar el formulario de configuración dentro del div #dynamic-content
        $('#dynamic-content').load('CALIDAD_listado_actaMuestreo.php', function (response, status, xhr) {
            if (status == "error") {
                console.log("Error al cargar el formulario: " + xhr.status + " " + xhr.statusText); // Mostrar errores de carga
            } else {
                obtenNotificaciones();
                carga_listado();
                console.log('Formulario cargado exitosamente.'); // Confirmar que la carga fue exitosa
            }
            $('#loading-spinner').hide();
            $('#dynamic-content').show();
        });
        } else {
            featureNoDisponible();
        }
    });
});


// ================================
//       SECCIÓN: Producción
// ================================

$(document).ready(function () {
    // Manejo de clic para "Listado de Clientes"
    $('#Listado_Clientes').click(function (event) {
        if (AppConfig.FLAGS.listado_clientes) {
            event.preventDefault(); // Prevenir la navegación predeterminada
            $('#dynamic-content').hide(); // Ocultar contenido actual
            $('#loading-spinner').show(); // Mostrar spinner

            console.log('El enlace Listado de Clientes fue clickeado.');

            $('#dynamic-content').load('Produccion/Listado_clientes.php', function (response, status, xhr) {
                if (status === "error") {
                    console.error("Error al cargar Listado de Clientes: " + xhr.status + " " + xhr.statusText);
                } else {
                    obtenNotificaciones(); // Actualizar notificaciones
                    carga_listado(); // Llamar a la función para manejar la lista
                    console.log('Listado de Clientes cargado exitosamente.');
                }
                $('#loading-spinner').hide(); // Ocultar spinner
                $('#dynamic-content').show(); // Mostrar contenido
            });
        } else {
            featureNoDisponible(); // Función no disponible
        }
    });

    // Manejo de clic para "Nuevo Reporte"
    $('#Nuevo_Reporte').click(function (event) {
        if (AppConfig.FLAGS.nuevo_reporte) {
            event.preventDefault();
            $('#dynamic-content').hide();
            $('#loading-spinner').show();

            console.log('El enlace Nuevo Reporte fue clickeado.');

            $('#dynamic-content').load('Produccion/Nuevo_reporte.php', function (response, status, xhr) {
                if (status === "error") {
                    console.error("Error al cargar Nuevo Reporte: " + xhr.status + " " + xhr.statusText);
                } else {
                    obtenNotificaciones();
                    console.log('Nuevo Reporte cargado exitosamente.');
                }
                $('#loading-spinner').hide();
                $('#dynamic-content').show();
            });
        } else {
            featureNoDisponible();
        }
    });
});



/**
 * Actualiza el breadcrumb con los nodos y URLs proporcionados.
 * @param {Array} nodos - Array de strings con los nombres de cada nodo del breadcrumb.
 * @param {Array} urls - Array de strings con las URLs correspondientes a cada nodo. Puede haber elementos null o undefined si no hay URL para un nodo específico.
 */
function actualizarBreadcrumb(nodos, urls) {
    const breadcrumb = document.querySelector(".breadcrumb");
    breadcrumb.innerHTML = ''; // Limpia el breadcrumb existente

    nodos.forEach((nodo, index) => {
        const li = document.createElement("li");
        li.className = "breadcrumb-item";
        if (index === nodos.length - 1) {
            // El último nodo se muestra como texto plano sin enlace
            li.textContent = nodo;
        } else {
            // Crea un enlace si hay una URL definida para el nodo
            const a = document.createElement("a");
            if (urls[index]) {
                a.href = urls[index];
            } else {
                // Si no hay URL, se usa "#" como placeholder
                a.href = "#";
                a.addEventListener("click", (e) => e.preventDefault()); // Previene la navegación si es un placeholder
            }
            a.textContent = nodo;
            li.appendChild(a);
        }
        breadcrumb.appendChild(li);
    });
}

let QA_solicitud_analisis_editing = false  

function botones(id, accion, base, opcional = null, opcional2 = null) {
    console.log({id, accion, base, opcional, opcional2});
    switch (base) {
        case "especificacion": {
            switch (accion) {
                case "revisar": {
                    console.log('El enlace de solicitud de análisis fue clickeado desde listado.');
                    $.ajax({
                        url: 'especificacion_producto.php',
                        type: 'POST',
                        data: { 
                            'id': id,
                            'accion': accion
                        },
                        success: function(response) {
                            $('#dynamic-content').hide();
                            $('#loading-spinner').show();
                            console.log('especificacion_producto redirigida con éxito');
                            $('#dynamic-content').html(response);
                            cargarDatosEspecificacion(id);
                            $('#loading-spinner').hide();
                            $('#dynamic-content').show();
                        },
                        error: function(xhr, status, error) {
                            console.error("Error en la solicitud: ", status, error);
                        }
                    });
                    break;
                }
                case "generar_documento": {
                    console.log('El enlace de solicitud de análisis fue clickeado desde listado.');
                    $.ajax({
                        url: 'documento_especificacion_producto.php',
                        type: 'POST',
                        data: { 
                            'id': id,
                            'accion': accion
                        },
                        success: function(response) {
                            console.log('especificacion_producto redirigida con éxito');
                            $('#dynamic-content').html(response);
                            cargarDatosEspecificacion(id);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error en la solicitud: ", status, error);
                        }
                    });
                    break;
                }
                case "prepararSolicitud": {
                    console.log('El enlace de solicitud de análisis fue clickeado desde listado.');
                    if (AppConfig.FLAGS.analisis_externo) {
                        var {analisisExterno, especificacion} = id;
                        $.ajax({
                            url: 'LABORATORIO_preparacion_solicitud.php',
                            type: 'POST',
                            data: { 
                                'analisisExterno': analisisExterno,
                                'especificacion': especificacion,
                                'accion': accion
                            },
                            success: function(response) {
                                console.log('especificacion_producto redirigida con éxito');
                                $('#dynamic-content').html(response);
                            },
                            error: function(xhr, status, error) {
                                console.error("Error en la solicitud: ", status, error);
                            }
                        });
                    } else {
                        featureNoDisponible();
                    }
                    break;
                }
            }
            break;
        }
        case "tareas": {
            switch (accion) {
                case "recordar": {
                    $.ajax({
                        url: '../pages/backend/tareas/recordatorioBE.php',
                        type: 'POST',
                        data: {
                            'idTarea': id
                        },
                        success: function(response) {
                            alert("Recordatorio enviado correctamente.");
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al enviar el recordatorio: ", status, error);
                        }
                    });
                    break;
                }
                case "finalizar_tarea": {
                    console.log('El enlace de solicitud de análisis fue clickeado desde listado.');
                    switch (opcional) {
                        //botones(this.id, this.name, 'tareas', 'calidad_especificacion_producto', 'Firma 3')
                        case "calidad_especificacion_productos": {
                            console.log(opcional, opcional2);
                            switch (opcional2) {

                                case 'Firma 2':
                                case 'Firma 3': {
                                    // Llamar a la función botones con los parámetros adecuados para especificación
                                    botones(id, 'generar_documento', 'especificacion');
                                    break;
                                }
                            }
                            break;
                        }
                        case "calidad_acta_muestreo": {
                            console.log(opcional, opcional2);
                            switch (opcional2) {
                                case 'Firma 1': {
                                    botones(id, 'resultados_actaMuestreo', 'laboratorio');
                                    break;
                                }
                                case 'Firma 2':
                                case 'Firma 3': {
                                    // Llamar a la función botones con los parámetros adecuados para acta de muestreo
                                    botones(id, 'firmar_acta_muestreo', 'laboratorio');
                                    break;
                                }
                            }
                            break;
                        }
                        case "calidad_analisis_externo": {
                            console.log(opcional, opcional2);
                            switch (opcional2) {
                                case 'Generar Acta Muestreo':{
                                    botones(id, 'generar_acta_muestreo', 'laboratorio');
                                    break;
                                }
                                case 'Firma 1': {
                                    botones(id, 'revisar', 'laboratorio', 'poblar_info_faltante');
                                    break;
                                }
                                case 'Enviar a Laboratorio': {
                                    botones(id, 'enviarSolicitud_laboratorio','laboratorio');
                                    break;
                                }
                                case 'Emitir acta de liberación': {
                                    botones(id, 'Liberacion', 'laboratorio');
                                    break;
                                }
                            }
                            break;
                        }
                    }
                    console.log('Proceso finalizado');
                    break;
                }
                case "cambiar_usuario":{
                    $.ajax({
                        url: '../pages/backend/tareas/cambiar_usuarioBE.php',
                        type: 'POST',
                        data: {
                            'idTarea': id,
                            'usuarioNuevo': opcional

                        },
                        success: function(response) {
                            if (response.exito) {
                                alert("Usuario actualizado correctamente.");
                            } else {
                                alert("Error: " + response.mensaje);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al modificar usuario ejecutor: ", status, error);
                        }
                    });
                    break;
                }
            }
            break;
        }
        case "laboratorio": {
            switch (accion) {
                case "eliminar_analisis_externo": {

                    break;
                }
                case "enviarSolicitud_laboratorio": {
                    $.ajax({
                        url: '../pages/LABORATORIO_envio_laboratorio.php',
                        type: 'POST',
                        data: {
                            'id': id,
                        },
                        success: function(response) {
                            $('#dynamic-content').html(response);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al visualizar el documento: ", status, error);
                        }
                    });
                    break;
                }
                case "generar_acta_muestreo": {
                    console.log('generar_acta_muestreo');
                    $.ajax({
                        url: '../pages/CALIDAD_documento_actaMuestreo.php',
                        type: 'POST',
                        data: {
                            'id': id,
                            'resultados': false,
                            'etapa': '0'
                        },
                        success: function(response) {
                            $('#dynamic-content').html(response);
                            cargarDatosEspecificacion(id, false, '0');
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al enviar el recordatorio: ", status, error);
                        }
                    });
                    break;
                }
                case "generar_acta_muestreo_versionado": {
                    console.log('generar_acta_muestreo_versionado', opcional, opcional2);
                    console.log(opcional, opcional2);
                    $.ajax({
                        url: '../pages/CALIDAD_documento_actaMuestreo.php',
                        type: 'POST',
                        data: {
                            'id': id,
                            'resultados': false,
                            'etapa': '0',
                            'id_actaMuestreo': opcional,
                            'version_actaMuestreo': opcional2
                        },
                        success: function(response) {
                            $('#dynamic-content').html(response);
                            cargarDatosEspecificacion(id, false, '0', opcional, opcional2);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al enviar el recordatorio: ", status, error);
                        }
                    });
                    break;
                }
                case "generar_documento_pdf": {
                    $.ajax({
                        url: '../pages/CALIDAD_documento_analisisExterno.php',
                        type: 'POST',
                        data: {
                            'id': id,
                            'resultados': true,
                            'etapa': '0'
                        },
                        success: function(response) {
                            $('#dynamic-content').html(response, true);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al enviar el recordatorio: ", status, error);
                        }
                    });
                    break;
                }
                case "generar_documento_solicitudes": {
                    $.ajax({
                        url: '../pages/CALIDAD_documento_analisisExterno.php',
                        type: 'POST',
                        data: {
                            'id': id,
                            'resultados': true,
                            'etapa': '1'
                        },
                        success: function(response) {
                            console.log('Solicitud de Análisis Externo Control de Calidad redirigida con éxito');
                            $('#dynamic-content').html(response, true);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al enviar el recordatorio: ", status, error);
                        }
                    });
                    break;
                }
                case "revisar": {
                    console.log("REVISAR CLICKEADO CON EXITO");
                    $.ajax({
                        url: '../pages/LABORATORIO_preparacion_solicitud.php',
                        type: 'POST',
                        data: {
                            'analisisExterno': id,
                            'accion': accion,
                            'ruta_edicion':opcional
                        },
                        success: function(response) {
                            console.log('Revision de documento Acta Muestreo redirigido con éxito');
                            $('#dynamic-content').html(response);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al visualizar el documento: ", status, error);
                        }
                    });
                    break;
                }
                case "resultados_actaMuestreo": {
                    console.log("exito al oprimir resultados_actaMuestreo");
                    $.ajax({
                        url: '../pages/CALIDAD_documento_actaMuestreo.php',
                        type: 'POST',
                        data: {
                            'id': id,
                            'resultados': true,
                            'etapa': '0'
                        },
                        success: function(response) {
                            $('#dynamic-content').html(response);
                            cargarDatosEspecificacion(id, true, '0');
                            console.log('resultados_actaMuestreo');
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al enviar el recordatorio: ", status, error);
                        }
                    });
                    break;
                }
                case "generar_documento_actaMuestreo": {
                    $.ajax({
                        url: '../pages/CALIDAD_documento_actaMuestreo.php',
                        type: 'POST',
                        data: {
                            'id': id,
                            'resultados': false,
                            'etapa': '0'
                        },
                        success: function(response) {
                            $('#dynamic-content').html(response);
                            cargarDatosEspecificacion(id, false, '0');
                            console.log('generar_acta_muestreo');
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al enviar el recordatorio: ", status, error);
                        }
                    });
                    break;
                }
                case "firmar_acta_muestreo": {
                    $.ajax({
                        url: '../pages/CALIDAD_documento_actaMuestreo.php',
                        type: 'POST',
                        data: {
                            'id': id,
                            'resultados': true,
                            'etapa': '1'
                        },
                        success: function(response) {
                            $('#dynamic-content').html(response);
                            cargarDatosEspecificacion(id, true, '1');
                            console.log('generar_acta_muestreo');
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al enviar el recordatorio: ", status, error);
                        }
                    });
                    break;
                }
                case "revisar_acta": {
                    $.ajax({
                        url: '../pages/CALIDAD_documento_actaMuestreo.php',
                        type: 'POST',
                        data: {
                            'id': id,
                            'resultados': true,
                            'etapa': '3',
                            'editable': false
                        },
                        success: function(response) {
                            $('#dynamic-content').html(response);
                            cargarDatosEspecificacion(id, true, '3');
                            console.log('ver documento');
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al enviar el recordatorio: ", status, error);
                        }
                    });
                    break;
                }
                case "revisar_acta_liberacion": {
                    $.ajax({
                        url: '../pages/CALIDAD_documento_ActaLiberacion.php',
                        type: 'POST',
                        data: {
                            'id': id,
                            'liberado':true
                        },
                        success: function(response) {
                            $('#dynamic-content').html(response);
                            carga_acta_liberacion_firmado(id);
                            console.log('ver documento');
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al enviar el recordatorio: ", status, error);
                        }
                    });
                    break;
                }
                case "Liberacion": {
                    $.ajax({
                        url: '../pages/CALIDAD_documento_ActaLiberacion.php',
                        type: 'POST',
                        data: {
                            'id': id,
                            'resultados': true,
                            'etapa': '1',
                            'liberado':false
                        },
                        success: function(response) {
                            console.log('Solicitud de Análisis Externo Control de Calidad redirigida con éxito');
                            $('#dynamic-content').html(response, true);
                            loadData();
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al enviar el recordatorio: ", status, error);
                        }
                    });
                    break;
                }
                case "revisar_informe_laboratorio": {
                    console.log("REVISAR INFORME LABORATORIO CLICKEADO CON EXITO");
                    // Cargar el PDF en un iframe
                    $.ajax({
                        url: '../pages/backend/acta_liberacion/extrae_informe.php',
                        type: 'GET',
                        data: { id: id },
                        dataType: 'json',
                        success: function(response) {
                            if (response && response.url_certificado_de_analisis_externo) {
                                const pdfUrl = response.url_certificado_de_analisis_externo;
                                const iframeHtml = `<iframe src="${pdfUrl}" width="100%" height="600px"></iframe>`;
                                
                                $('#dynamic-content').hide();
                                $('#loading-spinner').show();
                                $('#dynamic-content').html(iframeHtml);
                                $('#loading-spinner').hide();
                                $('#dynamic-content').show();
                            } else {
                                console.error("No se encontró la URL del certificado de análisis externo.");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error en la solicitud AJAX: ", status, error);
                        }
                    });
                    break;
                }
                case "firmar_solicitud_analisis_externo": {
                    fetch(`./backend/laboratorio/cargaEsp_solicitudBE.php?id=0&id_analisis_externo=${id}`, {
                        method: 'GET'
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.statusText);
                        }
                        return response.json();
                    }).then(response => {
                        var bodyHtml = [
                            {text: 'Numero de registro', value: response['analisis'].numero_registro},
                            {text: 'Numero de solicitud', value: response['analisis'].numero_solicitud},
                            {text: 'Solicitante', value: response['analisis'].solicitado_por},
                            {text: 'Version de analisis', value: response['analisis'].version},
                            {text: 'Nombre de producto', value: response['analisis'].prod_nombre_producto},
                            {text: 'Concentracion de producto', value: response['analisis'].prod_tipo_concentracion},
                            {text: 'Estandar segun', value: response['analisis'].estandar_segun},
                            {text: 'Registro isp', value: response['analisis'].registro_isp},
                            {text: 'Revisado por', value: response['analisis'].revisado_por},
                            {text: 'Especificacion', value: response['analisis'].id_especificacion},
                            {text: 'Version especificacion', value: response['analisis'].version_especificacion},
                        ].map(({text, value}) => /*html*/`
                        <fieldset class="form-group border-left pl-2">
                            <legend class="h6 font-weight-normal">${text}</legend>
                            <div class="form-group">
                                <input class="form-control mx-0" readonly value="${value}">
                            </div>
                        </fieldset>
                        `);

                        $.ajax({
                            url: '../pages/components/modal_confirm.php',
                            type: 'POST',
                            data: {
                                'title': 'Firmar documento',
                                'body': bodyHtml.join(''),
                                'button_text': 'Firmar',
                                'button_action': `firmarDocumentoSolicitudExterna(${id})`
                            },
                            success: function(response) {
                                $('#dynamic-content').append(response);
                            },
                            error: function(xhr, status, error) {
                                console.error("Error al enviar el recordatorio: ", status, error);
                            }
                        });
                    }).catch(error => {
                        console.error('Error:', error);
                    });
                    break;
                }
            }
            break;
        }

        default: {
            console.log("Base no reconocida:", base);
        }
    }
}


async function firmarDocumentoSolicitudExterna(idAnalisisExternoFirmar) {
  try {
    const responseFirmaSolicitudExterna = await fetch(`./backend/laboratorio/firma_solicitud_externa.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id_analisis_externo: idAnalisisExternoFirmar })
    });

    const dataFromResp = await responseFirmaSolicitudExterna.json();

    if (!responseFirmaSolicitudExterna.ok) {
        throw new Error(dataFromResp.mensaje || "Error desconocido del servidor");
    }

    if (!dataFromResp.exito) {
        throw new Error(dataFromResp.mensaje);
    }

    $.notify("Firma realizada", 'success');
    carga_listado()
    
  } catch (error) {
    alert("Error: " + error.message);
  }
}