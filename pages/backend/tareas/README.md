# Módulo tareas

Administra la creación y seguimiento de tareas asignadas a los usuarios.

## Archivos principales

- `tareasBE.php` – Endpoint principal para crear o actualizar tareas.
- `listado_tareasBE.php` – Devuelve el listado completo de tareas para el usuario autenticado.
- `Componente_tareasBE.php` – Fragmento utilizado en la página principal para mostrar tareas resumidas.
- `cambiar_usuarioBE.php` – Permite reasignar una tarea a otro usuario.
- `recordatorioBE.php` – Envía recordatorios de tareas pendientes por correo.

## Flujo general

1. Crear o modificar tareas mediante formularios del frontend.
2. Enviar notificaciones por correo cuando corresponda.
3. Listar y filtrar tareas por usuario o estado.

### Migración a React + Deno + TypeScript

- Definir un API REST para tareas que permita operaciones CRUD completas.
- Implementar websockets o suscripción para actualizaciones en tiempo real si es necesario.
- React puede utilizar componentes de tabla para mostrar el seguimiento.
