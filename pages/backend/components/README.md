# Módulo components

Scripts que devuelven fragmentos reutilizables de la aplicación (estadísticas, listados, etc.).

## Archivos principales

- `acta.php` – Estados y conteos de actas de liberación.
- `acumulados.php` – Totales acumulados de análisis o actas por período.
- `analisis.php` – Datos resumidos de análisis para gráficas.
- `especs.php` – Información de especificaciones de productos.
- `porcentaje.php` – Cálculo de porcentajes de cumplimiento.
- `productos.php` – Listado de productos disponibles.
- `productos_analisados.php` – Productos analizados por fecha.
- `promedio.php` – Cálculo de promedios de resultados de laboratorio.

## Uso

Estos scripts son llamados vía AJAX por el frontend para actualizar tablas y gráficos sin recargar la página.

### Migración a React + Deno + TypeScript

- Convertir cada script en un endpoint REST o GraphQL.
- Centralizar las consultas a la base de datos en servicios TypeScript reutilizables.
- En React, utilizar hooks para consumir estos datos y actualizar la interfaz.
