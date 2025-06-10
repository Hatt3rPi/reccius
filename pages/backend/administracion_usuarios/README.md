# Módulo administracion_usuarios

Encargado de la gestión de usuarios y roles del sistema.

## Archivos principales

- `obtener_usuariosBE.php` – Devuelve la lista de usuarios registrados.
- `obtener_rolesBE.php` – Lista los roles disponibles.
- `asignar_permisosBE.php` – Asigna o actualiza el rol de un usuario.
- `eliminar_usuarioBE.php` – Desactiva o elimina un usuario.

## Flujo general

1. El frontend solicita la información de usuarios o roles.
2. Los scripts se conectan a la base de datos y devuelven datos en JSON.
3. Se pueden actualizar permisos o eliminar registros según las acciones del administrador.

### Migración a React + Deno + TypeScript

- Implementar un servicio REST para usuarios y roles usando Deno.
- Las validaciones y la autenticación se pueden centralizar en middleware TypeScript.
- React manejará las vistas de administración consumiendo las APIs de Deno.
