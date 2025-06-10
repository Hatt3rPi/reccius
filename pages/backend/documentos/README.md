# Módulo documentos

Gestiona los archivos adjuntos y documentos asociados a análisis y productos.

## Archivos principales

- `obtener_adjuntos_analisis.php` – Devuelve adjuntos relacionados a un análisis específico.
- `obtener_adjuntos_tipo.php` – Lista documentos opcionales según el tipo de análisis.
- `obtener_tipos.php` – Obtiene los tipos de documentos para un producto analizado.
- `opcionales_analisis.php` – Administra documentos opcionales requeridos por el laboratorio.
- `eliminar_documento.php` – Elimina un archivo almacenado en la nube.

## Flujo general

1. Consultar la base de datos por los adjuntos requeridos.
2. Descargar o eliminar archivos desde Cloudflare R2 mediante `cloud/R2_manager.php`.

### Migración a React + Deno + TypeScript

- Reescribir estos endpoints como servicios REST que devuelvan JSON.
- Utilizar TypeScript para las validaciones y para manejar las rutas de archivos.
- React podrá mostrar listados de adjuntos y permitir la descarga o eliminación de manera asíncrona.
