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
                case "prepararSolicitud": {
                    console.log('El enlace de solicitud de análisis fue clickeado desde listado.');
                    if(FLAGS.analisis_externo){
                        $.ajax({
                            url: 'LABORATORIO_preparacion_solicitud.php', // URL del script PHP
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
                    // Llamar a una función que maneje el envío del recordatorio
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
                case "firmar_documento": {
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
            break;
        }
        case "laboratorio": {
            switch (accion) {
                case "generar_acta_muestreo": {
                    // Llamar a una función que maneje el envío del recordatorio
                    $.ajax({
                        url: '../pages/CALIDAD_documento_actaMuestreo.php',
                        type: 'POST',
                        data: {
                            'id': id,
                            'resultados': false,
                            'etapa':'0'
                        },
                        success: function(response) {
                            console.log('especificacion_producto redirigida con éxito ');
                            $('#dynamic-content').html(response); 
                            cargarDatosEspecificacion(id, false, '0');
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al enviar el recordatorio: ", status, error);
                        }
                    });
                    break;
                }
                case "resultados_actaMuestreo": {
                    // Llamar a una función que maneje el envío del recordatorio
                    $.ajax({
                        url: '../pages/CALIDAD_documento_actaMuestreo.php',
                        type: 'POST',
                        data: {
                            'id': id,
                            'resultados': true,
                            'etapa':'1'
                        },
                        success: function(response) {
                            console.log('especificacion_producto redirigida con éxito ');
                            $('#dynamic-content').html(response, true); 
                            cargarDatosEspecificacion(id, true, '1');
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al enviar el recordatorio: ", status, error);
                        }
                    });
                    break;
                }
            }
            break;
        }
    }

}

