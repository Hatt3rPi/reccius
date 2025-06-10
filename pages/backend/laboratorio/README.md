# Módulo laboratorio

Gestiona las solicitudes y resultados enviados a laboratorios externos.

## Archivos principales

- `LABORATORIO_preparacion_solicitudBE.php` – Crea una solicitud de análisis y genera un número correlativo.
- `cargaEsp_solicitudBE.php` – Sube documentos y especificaciones adjuntas a la solicitud.
- `enviar_solicitud_carga.php` – Envía la solicitud a un laboratorio específico con copia a otros correos.
- `enviar_solicitud_del_cc.php` – Elimina correos de copia en una solicitud.
- `enviar_solicitud_externa.php` – Envía correos a laboratorios externos.
- `firma_solicitud_externa.php` – Registra la firma digital de la solicitud.
- `genera_version.php` – Crea una nueva versión de la solicitud.
- `listado_solicitudesBE.php` – Listado y búsqueda de solicitudes registradas.

## Flujo general

1. Crear la solicitud y adjuntar documentación.
2. Enviar la solicitud al laboratorio correspondiente.
3. Firmar digitalmente y guardar la versión final.
4. Consultar o buscar solicitudes previas.

### Migración a React + Deno + TypeScript

- Dividir las operaciones en endpoints REST para crear, enviar y versionar solicitudes.
- Utilizar un ORM en Deno para manejar las tablas de solicitudes y laboratorios.
- React permitirá cargar archivos y firmar de forma más amigable.
