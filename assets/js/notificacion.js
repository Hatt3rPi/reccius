function mostrarNotificacion(mensaje, tipoNotificacion) {
    // Crear el contenedor de la notificación
    var notificacion = document.createElement('div');
    notificacion.className = 'notification-container notify';
    notificacion.style.display = 'block'; // Asegurar que esté visible

    // Crear el párrafo para el mensaje
    var elementoMensaje = document.createElement('p');
    elementoMensaje.textContent = mensaje;
    notificacion.appendChild(elementoMensaje); // Añadir el mensaje al contenedor

    // Determinar la clase basada en el tipo de notificación
    switch (tipoNotificacion) {
        case 'éxito':
            notificacion.classList.add('success');
            break;
        case 'advertencia':
            notificacion.classList.add('warning');
            break;
        case 'peligro':
            notificacion.classList.add('danger');
            break;
        case 'error':
            notificacion.classList.add('error');
            break;
        default:
            // No añadir ninguna clase adicional si el tipo no se reconoce
    }

    // Ajustar la posición 'bottom' basado en las notificaciones existentes
    var notificacionesExistentes = document.querySelectorAll('.notification-container').length;
    var bottomPosition = 600 + (notificacionesExistentes * 100); // Ajustar este valor según necesidad
    notificacion.style.bottom = `${bottomPosition}px`;

    // Añadir la notificación al cuerpo del documento
    document.body.appendChild(notificacion);

    // Función para desvanecer y eliminar la notificación
    setTimeout(function () {
        notificacion.style.opacity = 1;
        (function fadeOut() {
            if ((notificacion.style.opacity -= .1) < 0) {
                notificacion.style.display = "none";
                notificacion.remove(); // Eliminar del DOM después de ocultar
            } else {
                requestAnimationFrame(fadeOut);
            }
        })();
    }, 5000);
}
