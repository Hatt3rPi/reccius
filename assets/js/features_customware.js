// archivo: assets/js/features_customware.js
// Configuración central de feature flags con administración independiente por ambiente

// ==================================================================================
// CONFIGURACIÓN PARA AMBIENTE RECCIUS (reccius.cl - Producción)
// ==================================================================================
const reccius_flags = {
    // Usuarios y Roles
    crear_usuario: true,
    asignarRoles: true,
    perfilamiento_ratapan: false, // Sistema complejo de perfilamiento por ratapan
    
    // Configuración de perfil
    configuracion_perfil: true,
    
    // Especificaciones de producto
    especificacionProducto_creacion: true,
    especificacionProducto_listado: true,
    
    // Laboratorio y análisis
    analisis_externo: true,
    analisis_externo_listado: true,
    ingreso_resultados_laboratorio: true,
    acta_muestreo_listado: true,
    productos_disponibles_listado: true,
    
    // Cotizador
    recetario_magistral: false, // Más conservador en producción
    
    // Control de sección Producción
    experimental_produccion: false, // Deshabilitado en producción hasta validación completa
};

// ==================================================================================
// CONFIGURACIÓN PARA AMBIENTE CUSTOMWARE (customware.cl - Desarrollo)
// ==================================================================================
const customware_flags = {
    // Usuarios y Roles
    crear_usuario: true,
    asignarRoles: true,
    perfilamiento_ratapan: false, // Sistema complejo de perfilamiento por ratapan
    
    // Configuración de perfil
    configuracion_perfil: true,
    
    // Especificaciones de producto
    especificacionProducto_creacion: true,
    especificacionProducto_listado: true,
    
    // Laboratorio y análisis
    analisis_externo: true,
    analisis_externo_listado: true,
    ingreso_resultados_laboratorio: true,
    acta_muestreo_listado: true,
    productos_disponibles_listado: true,
    
    // Cotizador
    recetario_magistral: true, // Habilitado en desarrollo para testing
    
    // Control de sección Producción
    experimental_produccion: false, // Deshabilitado hasta completar testing
};

// ==================================================================================
// DETECCIÓN AUTOMÁTICA DE AMBIENTE Y CONFIGURACIÓN
// ==================================================================================
(function() {
    const hostname = window.location.hostname;
    let selectedFlags;
    let environmentName;
    
    // Detectar ambiente basado en hostname
    if (hostname.includes('reccius.cl')) {
        selectedFlags = reccius_flags;
        environmentName = 'RECCIUS (Producción)';
    } else if (hostname.includes('customware.cl') || hostname.includes('localhost') || hostname.includes('127.0.0.1')) {
        selectedFlags = customware_flags;
        environmentName = 'CUSTOMWARE (Desarrollo)';
    } else {
        // Fallback a configuración de desarrollo si no se puede determinar
        selectedFlags = customware_flags;
        environmentName = 'UNKNOWN (Fallback a desarrollo)';
    }
    
    // Configuración global (mantiene compatibilidad con código existente)
    window.AppConfig = {
        VERSION: "1.0.0",
        ENVIRONMENT: environmentName,
        FLAGS: selectedFlags
    };
    
    // Información de debugging (solo visible en ambiente de desarrollo)
    if (hostname.includes('customware.cl') || hostname.includes('localhost') || hostname.includes('127.0.0.1')) {
        if (window.console && window.console.log) {
            console.log('🚀 Feature Flags inicializados para:', environmentName);
            console.log('🎛️ Configuración activa:', window.AppConfig.FLAGS);
            console.log('🌐 Hostname detectado:', hostname);
        }
    }
})();