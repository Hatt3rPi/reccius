// archivo: assets/js/features_customware.js
// Configuración central de feature flags para el sistema Reccius

AppConfig = {
    VERSION: "1.0.0",
    FLAGS: {
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
        recetario_magistral: true,
        
        // NUEVA FLAG: Control de sección Producción
        experimental_produccion: false, // Controla toda la sección de Producción
    }
};