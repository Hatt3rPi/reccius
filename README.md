# Reccius

Este repositorio contiene una aplicación web para la gestión de documentos, análisis y tareas de la empresa Reccius. El proyecto está principalmente construido en **PHP** para el backend y **HTML/CSS/JavaScript** para el frontend.

## Tecnologías y dependencias principales

- **PHP**: Lógica de servidor.
- **MySQL**: Base de datos (se accede mediante funciones `mysqli`).
- **JavaScript / jQuery**: Funcionalidad en el lado del cliente.
- **DataTables**: Para tablas dinámicas en la interfaz.
- **Chart.js**: Gráficos estadísticos.
- **PHPMailer**: Envío de correos electrónicos.
- **AWS SDK / Cloudflare R2**: Gestión de archivos en la nube (`pages/backend/cloud/R2_manager.php`).
- **Bootstrap**: Estilos y componentes responsivos.
- **jsPDF / html2canvas**: Generación de documentos PDF.
- **GitHub Actions**: Flujo de despliegue definido en `.github/workflows/deploy-to-production.yml`.

## Estructura de carpetas

- `assets/` – Contiene hojas de estilo CSS, imágenes y scripts JavaScript reutilizables.
- `documentos_publicos/` – Archivos PDF públicos almacenados en el repositorio.
- `pages/` – Código principal de la aplicación dividido en frontend y backend.
- `pages/backend/` – Lógica del lado del servidor: endpoints PHP, modelos y utilidades.
- `test/` – Archivos de prueba y ejemplos estáticos.
- `.github/` – Workflows para despliegue automático.

## Principales rutas y funcionalidades

A continuación se describen las rutas más relevantes dentro de `pages/`.

### Autenticación y usuarios

- `pages/login.html` – Formulario de inicio de sesión.
- `pages/backend/login/loginBE.php` – Valida credenciales y crea la sesión del usuario.
- `pages/backend/login/logoutBE.php` – Cierra la sesión.
- `pages/crear_usuario.php` – Página para registrar un nuevo usuario.
- `pages/asignar_roles.php` – Interfaz de asignación de roles.
- `pages/backend/administracion_usuarios/*` – Endpoints de administración (obtención, modificación y eliminación de usuarios y roles).

### Página principal

- `pages/index.php` – Panel principal de la aplicación. Carga menús dinámicos y muestra accesos a las distintas áreas: usuarios, documentos, tareas, etc.
- `pages/index_administrador.php` / `pages/index_superadmin.php` – Variaciones del panel para diferentes niveles de rol.

### Gestión de tareas

- `pages/listado_tareas.php` – Listado y seguimiento de tareas asignadas.
- `pages/backend/tareas/*` – Backend para crear, modificar y listar tareas, así como envío de recordatorios.

### Gestión de especificaciones y productos

- `pages/documento_especificacion_producto.php` – Formulario para subir especificaciones de productos.
- `pages/listado_especificaciones_producto.php` – Muestra todas las especificaciones cargadas.
- `pages/backend/*especificacion*` – Scripts que guardan y procesan documentos de especificaciones en la base de datos y en la nube.

### Actas y análisis de laboratorio

- `pages/CALIDAD_acta_muestreo.php` – Registro de actas de muestreo.
- `pages/CALIDAD_documento_analisisExterno.php` – Información de análisis externos.
- `pages/CALIDAD_listado_actaLiberacion.php` – Visualización de actas de liberación generadas.
- `pages/LABORATORIO_preparacion_solicitud.php` – Preparación de solicitudes de análisis.
- `pages/LABORATORIO_envio_laboratorio.php` – Envío de muestras al laboratorio.
- `pages/backend/analisis/*` – Obtención y registro de resultados de laboratorio.
- `pages/backend/acta_liberacion/*` – Creación de actas de liberación con correlativos automáticos y generación de PDF.

### Gestión de documentos

- `pages/backend/documentos/obtener_tipos.php` – Devuelve la lista de documentos asociados a un producto analizado.
- `pages/backend/documentos/eliminar_documento.php` – Elimina archivos almacenados en Cloudflare R2.

### Componentes reutilizables

- `pages/components/` – Fragmentos de HTML y scripts que se cargan dinámicamente en la interfaz principal. Ejemplos: gráficos de actas, listado de laboratorios, etc.

### Notificaciones y correos

- `pages/backend/email/envia_correoBE.php` – Envío de correos electrónicos mediante PHPMailer. Incluye funciones para envíos múltiples y con copia.
- `pages/backend/login/notificaciones.php` – Consulta notificaciones pendientes para el usuario autenticado.

### Archivos de configuración externos

Algunas rutas hacen referencia a archivos de configuración fuera del repositorio (por ejemplo `/home/customw2/conexiones/config_reccius.php`). Dichos archivos definen parámetros de conexión a base de datos, credenciales de correo, etc., y no se encuentran versionados por razones de seguridad.

## Flujo de despliegue

El workflow `deploy-to-production.yml` permite clonar el repositorio, ajustar rutas absolutas y desplegar mediante FTP tanto a un ambiente de desarrollo como a producción.

```yaml
name: Espejo a Producción y Despliegue en Desarrollo
on:
  push:
    branches: [main, ambiente_desarrollo]
```

El workflow utiliza `webfactory/ssh-agent` para autenticarse y `SamKirkland/FTP-Deploy-Action` para subir los archivos al servidor remoto. En la rama `main` también se clona un repositorio espejo (`recciuscl`) y se reemplazan rutas a dominios y carpetas.

## Referencias adicionales

- La carpeta `pages/backend/BACK.md` documenta la filosofía de los modelos PHP y muestra ejemplos de uso.
- `pages/backend/cloud/R2_manager.php` contiene funciones para subir y eliminar archivos en Cloudflare R2. Maneja tipos MIME y genera URLs públicas.

---

Esta documentación entrega una visión global del proyecto: estructura de carpetas, tecnologías usadas y una descripción de las rutas y funcionalidades más importantes. Para detalles específicos de cada módulo se recomienda revisar directamente los archivos PHP correspondientes.

