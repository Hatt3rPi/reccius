# Módulo analisis

Procesa los resultados de análisis de laboratorio y gestiona revisiones.

## Archivos principales

- `ingresar_resultados_analisis.php` – Guarda resultados de análisis y firmas.
- `agnadir_revision.php` – Permite agregar observaciones o revisiones a un análisis.
- `eliminar_analisis_externoBE.php` – Elimina registros de análisis externo.
- `Componente_laboratorios.php` – Devuelve la lista de laboratorios disponibles.

## Flujo general

1. El sistema recibe resultados y los vincula a actas existentes.
2. Se registran firmas electrónicas y se actualiza el estado del análisis.
3. Puede adjuntarse documentación adicional para cada revisión.

### Migración a React + Deno + TypeScript

- Crear endpoints en Deno para registrar resultados y revisiones.
- Utilizar tipos de datos fuertes en TypeScript para evitar errores de formato.
- React presentará formularios para la carga de resultados e historial de revisiones.
