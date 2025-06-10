# Módulo email

Encargado del envío de notificaciones por correo.

## Archivos principales

- `envia_correoBE.php` – Funciones para enviar correos utilizando PHPMailer. Soporta múltiples destinatarios y archivos adjuntos.
- `tester.php` – Script de prueba para verificar la configuración de correo.

## Flujo general

1. Los módulos de actas o tareas llaman a `envia_correoBE.php` para notificar a los usuarios.
2. Se carga la librería PHPMailer desde un directorio externo y se envía el mensaje.

### Migración a React + Deno + TypeScript

- Utilizar un módulo SMTP para Deno (por ejemplo `denomailer`).
- Centralizar el envío de correos en un servicio TypeScript reutilizable por los demás módulos.
- El frontend React solo invocará los endpoints correspondientes.
