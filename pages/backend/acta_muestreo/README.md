# Módulo acta_muestreo

Gestiona las actas de muestreo y sus resultados de laboratorio.

## Archivos principales

- `genera_acta.php` – Construye una nueva acta de muestreo a partir de un análisis externo.
- `guardar_y_firmar.php` – Almacena la acta y registra firmas digitales.
- `ingresa_resultados.php` – Registra los resultados y adjuntos de laboratorio.
- `listado_acta_muestreoBE.php` – Devuelve listado de actas existentes.
- `consulta_resultados.php` – Permite consultar resultados de muestreo por ID.
- `rechazar_acta_muestreoBE.php` – Marca un acta como rechazada.
- `versiona_acta.php` – Genera una nueva versión manteniendo trazabilidad.

Existen scripts antiguos (`old_*`) utilizados para versiones previas.

## Flujo general

1. Obtener datos de análisis y producto asociado.
2. Crear la acta inicial y asignar responsables.
3. Registrar firmas y resultados.
4. Mantener historial de versiones y estados.

### Migración a React + Deno + TypeScript

- Crear una API en Deno para CRUD de actas y resultados.
- Utilizar módulos TypeScript para manejar validaciones y control de versiones.
- React presentará formularios dinámicos y tablas con los resultados.
- Usar un cliente MySQL o Postgres en Deno para almacenar actas.
