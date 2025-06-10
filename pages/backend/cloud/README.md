# Módulo cloud

Abstrae la comunicación con el almacenamiento externo (Cloudflare R2).

## Archivo principal

- `R2_manager.php` – Maneja subidas y descargas de archivos a la nube. Define configuraciones de bucket y autenticación.

## Flujo general

1. Inicializar el cliente de R2 con las credenciales definidas en variables de entorno.
2. Subir archivos o eliminarlos según las peticiones del resto de módulos.
3. Generar URLs públicas para que el frontend pueda descargar los archivos.

### Migración a React + Deno + TypeScript

- Reescribir este manejador en TypeScript utilizando librerías compatibles con S3/R2 para Deno.
- Centralizar la lógica de generación de URLs y manejo de permisos.
- El frontend React consumirá dichas URLs para mostrar descargas o vistas previas.
