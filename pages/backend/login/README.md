# Módulo login

Controla la autenticación de usuarios y la gestión de sesiones.

## Archivos principales

- `loginBE.php` – Valida las credenciales, crea la sesión y establece tokens CSRF.
- `logoutBE.php` – Cierra la sesión activa del usuario.
- `notificaciones.php` – Devuelve notificaciones pendientes tras el inicio de sesión.

## Flujo general

1. El usuario envía sus credenciales desde el formulario de `pages/login.html`.
2. `loginBE.php` valida las credenciales con la base de datos y crea la sesión.
3. Posteriormente se consultan notificaciones pendientes.
4. `logoutBE.php` destruye la sesión cuando el usuario decide salir.

### Migración a React + Deno + TypeScript

- Implementar autenticación basada en tokens JWT generados por un servicio Deno.
- Utilizar middlewares en TypeScript para validar sesiones y permisos.
- React gestionará el estado de autenticación utilizando contextos o librerías como Redux.
