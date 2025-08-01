# Instrucciones de Actualización de Base de Datos - Reccius

## 📋 Resumen

Este script (`reccius_update.sql`) sincroniza la base de datos de **reccius.cl** con las nuevas funcionalidades presentes en **customware.cl**.

## 🎯 Cambios Implementados

### 1. **Nuevas Tablas Creadas** (9 tablas)

| Tabla | Propósito |
|-------|-----------|
| `delegacion_tareas` | Sistema de delegación de tareas entre usuarios |
| `tipos_paginas` | Definición de tipos de páginas del sistema |
| `roles_pagina` | Definición de roles específicos por página |
| `paginas` | Gestión de páginas del sistema |
| `paginas_roles` | Relación entre páginas y roles |
| `usuarios_modulos` | Asignación de módulos a usuarios |
| `usuarios_modulos_roles` | Roles de usuarios por módulos |
| `tareas_cambio_usuarios` | Historial de cambios de usuarios en tareas |
| `calidad_otros_documentos_tipos` | Tipos de documentos adicionales |

### 2. **Modificaciones en Tablas Existentes**

#### `calidad_acta_muestreo`
- ✅ Agregados: `muestreador`, `fecha_rechazo`, `motivo_rechazo`

#### `calidad_analisis`
- ✅ Ajustada restricción: `id_especificacion_producto` ahora NOT NULL

#### `calidad_analisis_externo`
- ✅ Agregados: `liberado_por`, `enviado_lab_por`

#### `calidad_especificacion_productos`
- ✅ Ajustada restricción: `id_producto` ahora NOT NULL

### 3. **Foreign Keys y Constraints**
- ✅ Agregadas todas las relaciones de integridad referencial
- ✅ Configurados CASCADE para eliminaciones relacionadas
- ✅ Preservadas todas las relaciones existentes

## 🚨 Consideraciones Importantes

### ✅ **SEGURIDAD DE DATOS**
- **AUTO_INCREMENT**: Los contadores existentes NO se modifican
- **DATOS EXISTENTES**: Todos los datos actuales se mantienen intactos
- **BACKWARD COMPATIBILITY**: El sistema actual seguirá funcionando

### 📝 **Antes de Ejecutar**
1. **Hacer backup completo** de la base de datos actual
2. **Verificar conexión** a la base de datos correcta
3. **Probar en ambiente de staging** si está disponible

## 🔧 **Instrucciones de Ejecución**

### Opción 1: Via phpMyAdmin
1. Acceder a phpMyAdmin de reccius.cl
2. Seleccionar la base de datos `recciusc_customware`
3. Ir a la pestaña "SQL"
4. Copiar y pegar el contenido de `reccius_update.sql`
5. Ejecutar el script

### Opción 2: Via MySQL CLI
```bash
mysql -u [usuario] -p[contraseña] recciusc_customware < reccius_update.sql
```

### Opción 3: Via Línea de Comandos con archivo
```bash
mysql -u [usuario] -p
use recciusc_customware;
source /ruta/al/archivo/reccius_update.sql;
```

## ✅ **Verificación Post-Ejecución**

Después de ejecutar el script, verificar:

```sql
-- 1. Verificar que las nuevas tablas existen
SHOW TABLES LIKE '%delegacion%';
SHOW TABLES LIKE '%paginas%';
SHOW TABLES LIKE '%usuarios_modulos%';

-- 2. Verificar nuevos campos en tablas existentes
DESCRIBE calidad_acta_muestreo;
DESCRIBE calidad_analisis_externo;

-- 3. Verificar foreign keys
SELECT * FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE REFERENCED_TABLE_NAME IN ('tareas', 'tipos_paginas', 'usuarios');
```

## 🔄 **Funcionalidades Habilitadas**

Con esta actualización, la base de datos de reccius.cl tendrá:

- ✅ **Sistema de gestión de páginas y roles granular**
- ✅ **Delegación de tareas entre usuarios**
- ✅ **Módulos personalizables por usuario**
- ✅ **Historial de cambios en asignaciones**
- ✅ **Tipos de documentos expandidos**
- ✅ **Campos adicionales para trazabilidad**

## 📞 **Soporte**

En caso de problemas durante la ejecución:
1. Verificar logs de MySQL para errores específicos
2. Comprobar que el usuario tiene permisos CREATE, ALTER, y REFERENCES
3. Verificar que no existan conflictos de nombres con tablas personalizadas

---
**Fecha de creación**: 2025-08-01  
**Versión**: 1.0  
**Compatibilidad**: MySQL 5.7+ / MariaDB 10.2+