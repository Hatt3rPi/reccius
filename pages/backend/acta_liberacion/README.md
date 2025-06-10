# Módulo acta_liberacion

Este directorio contiene la lógica para generar y gestionar las actas de liberación de productos.

## Archivos principales

- `acta_liberacion_guardayfirma.php` – Guarda el acta final y registra la firma del responsable. Incluye envío de correos.
- `carga_acta_liberacion.php` – Carga los datos de un análisis externo para elaborar la acta.
- `carga_acta_liberacion_firmada.php` – Recibe y almacena la versión firmada en la nube.
- `extrae_informe.php` – Genera un informe en PDF listo para el cliente.
- `listado_actaLiberacionBE.php` – Devuelve el listado de actas emitidas.
- `listado_productosDisponiblesBE.php` – Lista productos con análisis terminado listos para liberar.

## Flujo general

1. Obtener los datos de análisis y del producto.
2. Completar la información de la acta y almacenarla en la base de datos.
3. Firmar digitalmente y notificar por correo.
4. Almacenar versiones finales en Cloudflare R2.

### Migración a React + Deno + TypeScript

- Exponer endpoints REST en Deno usando un framework como `oak`.
- Reescribir la lógica de consultas en un módulo TypeScript que utilice un cliente MySQL para Deno.
- El manejo de archivos y firmas puede implementarse con bibliotecas de Deno y servicios externos para PDF.
- El frontend en React consumirá las APIs para crear y listar actas de liberación.
