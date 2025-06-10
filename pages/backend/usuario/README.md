# Módulo usuario

Manejo de creación y modificación de perfiles de usuario.

## Archivos principales

- `crear_usuarioBE.php` – Registra un nuevo usuario y envía correo de bienvenida.
- `modificar_perfilBE.php` – Actualiza datos personales y cargo.
- `modifica_perfilFETCH.php` – Obtiene información del usuario para precargar formularios.
- `obtener_usuarioBE.php` – Devuelve el detalle de un usuario por su nombre o ID.
- `restablece-contrasenaBE.php` – Envía correo para restablecer la contraseña.

## Flujo general

1. El administrador registra usuarios y define su rol inicial.
2. Los usuarios pueden modificar su perfil y restablecer contraseñas.
3. Se envían correos de confirmación y notificaciones.

### Migración a React + Deno + TypeScript

- Implementar servicios REST en Deno para crear y actualizar usuarios.
- Manejar el envío de correos desde un módulo centralizado (ver `email/`).
- React gestionará los formularios y validaciones de manera más dinámica.
