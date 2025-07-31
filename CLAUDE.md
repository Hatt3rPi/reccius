# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Información General del Proyecto

Sistema de gestión integral para Reccius Medicina Especializada que incluye módulos de:
- Control de Calidad (actas de muestreo, liberación, especificaciones)
- Gestión de Producción (órdenes de compra, facturación, despacho)
- Laboratorio (solicitudes de análisis, resultados)
- Administración de Usuarios y Roles
- Sistema de Cotización

## Stack Tecnológico

- **Backend**: PHP 7+ (sin framework)
- **Frontend**: HTML, CSS, JavaScript (jQuery, Bootstrap 4)
- **Base de datos**: MySQL/MariaDB
- **Librerías JavaScript**: DataTables, Bootstrap Datepicker, Chart.js
- **Almacenamiento en la nube**: R2 (Cloudflare)
- **Sesiones**: Configuradas para 2 horas (7200 segundos)

## Comandos de Desarrollo

### Base de datos
```bash
# Conexión a base de datos está en:
/home/customw2/conexiones/config_reccius.php
```

### Servidor de desarrollo
```bash
# Para PHP local (desde el directorio raíz):
php -S localhost:8000 -t .

# O usar servidor web configurado (Apache/Nginx)
```

### Verificación de código
```bash
# No hay comandos de lint o tests configurados actualmente
# Considerar agregar PHPStan o PHP_CodeSniffer
```

## Arquitectura de Alto Nivel

### Estructura de Directorios

```
/data2/reccius/
├── assets/           # Recursos estáticos
│   ├── css/         # Estilos organizados por módulo
│   ├── js/          # Scripts JavaScript
│   └── images/      # Imágenes y logos
├── pages/           # Páginas principales del sistema
│   ├── backend/     # Lógica de negocio y APIs
│   │   ├── acta_liberacion/
│   │   ├── acta_muestreo/
│   │   ├── administracion_usuarios/
│   │   ├── calidad/
│   │   ├── laboratorio/
│   │   └── login/
│   ├── components/  # Componentes reutilizables
│   └── Produccion/ # Módulo de producción
└── documentos_publicos/ # PDFs y documentos
```

### Módulos Principales

1. **Sistema de Autenticación**
   - Login con usuario o correo
   - Protección CSRF
   - Sesiones PHP con timeout de 2 horas
   - Verificación de firma digital obligatoria

2. **Control de Calidad**
   - Especificaciones de productos
   - Actas de muestreo y liberación
   - Gestión de productos en cuarentena
   - Sistema de firmas digitales para documentos

3. **Producción**
   - Ingreso y listado de órdenes de compra
   - Facturación, despacho y cobranza
   - Vista general de producción

4. **Laboratorio**
   - Solicitudes de análisis externos
   - Preparación y envío de muestras
   - Registro de resultados

5. **Sistema de Permisos**
   - Roles por página (Administrador, Usuario, Visualizador)
   - Asignación de módulos por usuario
   - Control granular de accesos

### Patrones de Código

1. **Archivos Backend (BE)**
   - Nomenclatura: `nombreFuncionalidadBE.php`
   - Incluyen conexión a BD: `require_once "/home/customw2/conexiones/config_reccius.php"`
   - Uso de `mysqli_real_escape_string` para prevenir SQL injection
   - Respuestas JSON para AJAX

2. **Frontend**
   - jQuery para interacciones AJAX
   - DataTables para listados
   - Bootstrap 4 para UI
   - Notificaciones con notify.js

3. **Seguridad**
   - Verificación de sesión en cada página
   - Tokens CSRF para formularios
   - Escape de datos de entrada
   - Validación de permisos por rol

### Flujos de Trabajo Importantes

1. **Crear nuevo documento/acta**:
   - Generar formulario → Guardar borrador → Firmar digitalmente → Versionar

2. **Gestión de usuarios**:
   - Crear usuario → Asignar rol → Asignar módulos → Configurar permisos por página

3. **Proceso de análisis**:
   - Crear solicitud → Preparar muestra → Enviar a laboratorio → Registrar resultados

### Consideraciones Especiales

- **Firma Digital**: Obligatoria para usuarios, se valida en `$_SESSION['foto_firma']`
- **Archivos en R2**: Gestión mediante `R2_manager.php`
- **Versionado de documentos**: Sistema automático al modificar actas
- **Estados de documentos**: Borrador, Firmado, Rechazado, Versionado

### Puntos de Entrada Principales

- `/pages/login.html` - Página de inicio de sesión
- `/pages/index.php` - Dashboard principal (requiere autenticación)
- `/pages/index_administrador.php` - Dashboard de administrador
- `/pages/backend/` - APIs y lógica de negocio

### Variables de Sesión Importantes

- `$_SESSION['usuario']` - Nombre de usuario
- `$_SESSION['nombre']` - Nombre completo
- `$_SESSION['rol']` - ID del rol del usuario
- `$_SESSION['foto_firma']` - Ruta de la firma digital
- `$_SESSION['csrf_token']` - Token CSRF actual