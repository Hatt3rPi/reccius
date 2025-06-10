# Módulo index

Endpoints que sirven datos para las vistas principales de administradores y superadministradores.

## Archivos principales

- `index_administrador.php` – Recolecta datos de trazabilidad y estadísticas para la vista de administrador.
- `index_superadminBE.php` – Similar al anterior pero con mayor nivel de detalle y acceso a registros históricos.

## Flujo general

1. Autenticar al usuario y verificar su rol.
2. Consultar la base de datos por eventos recientes (trazabilidad, acciones de usuarios, etc.).
3. Devolver la información en formato JSON para alimentar paneles y gráficos.

### Migración a React + Deno + TypeScript

- Implementar endpoints en Deno que devuelvan la información de trazabilidad.
- Usar TypeScript para definir claramente las estructuras de datos (fechas, acciones, usuarios).
- React consumirá estos endpoints para construir dashboards en tiempo real.
