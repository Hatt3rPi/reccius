# Módulo otros

Contiene utilidades y clases auxiliares utilizadas por distintos módulos.

## Archivos principales

- `laboratorio.php` – Clase `Laboratorio` para consultar y gestionar laboratorios (incluye correos en copia).
- `feriados_chile.php` – Funciones para determinar feriados nacionales (útil para cálculos de plazos).
- `convertir_imagenes.php` – Utilidad para convertir imágenes a PDF o ajustar su tamaño.

## Uso

Estos archivos son referenciados por otros módulos como `laboratorio/` y `tareas/` para funciones comunes.

### Migración a React + Deno + TypeScript

- Reescribir las clases y utilidades en módulos TypeScript.
- Exponer funciones compartidas a través de paquetes internos que pueda consumir la API y el frontend.
