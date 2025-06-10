# Módulo calidad

Maneja especificaciones de productos y documentos de calidad.

## Archivos principales

- `especificacion_productoBE.php` – Registra nuevas especificaciones y metadatos.
- `documento_especificacion_productoBE.php` – Carga documentos asociados a una especificación.
- `eliminar_especificacion_producto.php` – Borra especificaciones obsoletas.
- `add_documentos.php` – Adjunta documentos adicionales.
- `firma_documentoBE.php` – Gestiona la firma digital de documentos.
- `listado_especificaciones_productoBE.php` – Devuelve todas las especificaciones registradas.
- `listado_analisis_por_especificacion.php` – Consulta los análisis realizados por especificación.

## Flujo general

1. Registrar o actualizar especificaciones de producto.
2. Subir archivos y documentos firmados a la nube.
3. Listar y filtrar especificaciones para posteriores análisis.

### Migración a React + Deno + TypeScript

- Definir un API REST en Deno que manipule las especificaciones y documentos.
- Utilizar TypeScript para validar datos antes de guardarlos en la base.
- React puede ofrecer formularios dinámicos para subir y firmar documentos.
