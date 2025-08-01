# Cambios de Ratapan

## Resumen
Los 83 commits de Ratapan en ambiente_desarrollo incluyen principalmente el desarrollo del nuevo sistema de gestión de páginas y roles.

## Funcionalidades desarrolladas
1. **Sistema de gestión de páginas** (`pages/asignar_pages.php`)
   - Interfaz para asignar páginas a usuarios
   - Gestión de roles por página
   - Búsqueda y filtrado de usuarios

2. **Backend de páginas** (`pages/backend/paginas/`)
   - API REST para gestión de páginas
   - Manejo de relaciones usuario-módulo-rol
   - Funciones CRUD completas

3. **Nuevas tablas de base de datos**
   - `roles_pagina`: Define roles disponibles por página
   - `usuario_modulo_rol`: Relaciona usuarios con módulos y roles
   - Columna `orden` agregada para ordenamiento

## Archivos creados/modificados
- `pages/asignar_pages.php` (nuevo)
- `pages/backend/paginas/pagesBe.php` (nuevo)
- `pages/backend/paginas/pagina_model.php` (nuevo)
- `assets/js/scripts_index.js` (modificado)
- `pages/backend/usuarios/getUser.php` (modificado)

## Nota de integración
Estos cambios representan nuevas funcionalidades que no existen en la rama main. Se recomienda una revisión completa antes de integrar.