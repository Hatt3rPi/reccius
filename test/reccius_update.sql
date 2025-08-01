-- =========================================================================
-- SCRIPT DE ACTUALIZACIÓN PARA BASE DE DATOS RECCIUS
-- =========================================================================
-- Fecha: 2025-08-01
-- Propósito: Sincronizar BD de reccius.cl con funcionalidades de customware.cl
-- IMPORTANTE: Los AUTO_INCREMENT existentes se mantienen sin modificar
-- =========================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- =========================================================================
-- SECCIÓN 1: CREAR NUEVAS TABLAS FALTANTES
-- =========================================================================

-- Tabla: delegacion_tareas
CREATE TABLE IF NOT EXISTS `delegacion_tareas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tarea` int(11) NOT NULL,
  `usuario_original` varchar(255) NOT NULL,
  `usuario_delegado` varchar(255) NOT NULL,
  `fecha_delegacion` datetime NOT NULL,
  `motivo` text DEFAULT NULL,
  `estado` enum('activo','finalizado') NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`id`),
  KEY `id_tarea` (`id_tarea`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Tabla: tipos_paginas
CREATE TABLE IF NOT EXISTS `tipos_paginas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: roles_pagina
CREATE TABLE IF NOT EXISTS `roles_pagina` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: paginas
CREATE TABLE IF NOT EXISTS `paginas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo_pagina` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_paginas_tipos_idx` (`id_tipo_pagina`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: paginas_roles
CREATE TABLE IF NOT EXISTS `paginas_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pagina_id` int(11) NOT NULL,
  `rol_pagina_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_paginas_roles_pagina` (`pagina_id`),
  KEY `fk_paginas_roles_rol` (`rol_pagina_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: usuarios_modulos
CREATE TABLE IF NOT EXISTS `usuarios_modulos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `tipo_pagina_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_usuarios_modulos_usuario_idx` (`usuario_id`),
  KEY `fk_usuarios_modulos_tipo_pagina_idx` (`tipo_pagina_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: usuarios_modulos_roles
CREATE TABLE IF NOT EXISTS `usuarios_modulos_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_modulo_id` int(11) NOT NULL,
  `rol_pagina_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_umr_usuario_modulo_idx` (`usuario_modulo_id`),
  KEY `fk_umr_rol_idx` (`rol_pagina_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: tareas_cambio_usuarios
CREATE TABLE IF NOT EXISTS `tareas_cambio_usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tarea` int(11) NOT NULL,
  `usuario_original` varchar(255) NOT NULL,
  `usuario_nuevo` varchar(255) NOT NULL,
  `fecha_cambio` datetime NOT NULL,
  `cambiado_por` varchar(50) NOT NULL,
  `motivo` text DEFAULT NULL,
  `estado` enum('activo','finalizado') NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`id`),
  KEY `id_tarea` (`id_tarea`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Tabla: calidad_otros_documentos_tipos
CREATE TABLE IF NOT EXISTS `calidad_otros_documentos_tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- =========================================================================
-- SECCIÓN 2: MODIFICAR TABLAS EXISTENTES
-- =========================================================================

-- Actualizar calidad_acta_muestreo: Agregar campos faltantes
ALTER TABLE `calidad_acta_muestreo` 
ADD COLUMN IF NOT EXISTS `muestreador` varchar(50) DEFAULT NULL AFTER `plan_muestreo`,
ADD COLUMN IF NOT EXISTS `fecha_rechazo` date DEFAULT NULL AFTER `muestreador`,
ADD COLUMN IF NOT EXISTS `motivo_rechazo` text DEFAULT NULL AFTER `fecha_rechazo`;

-- Actualizar calidad_analisis: Ajustar constraint
ALTER TABLE `calidad_analisis` 
MODIFY COLUMN `id_especificacion_producto` int(11) NOT NULL;

-- Actualizar calidad_analisis_externo: Agregar campos faltantes de customware
ALTER TABLE `calidad_analisis_externo`
ADD COLUMN IF NOT EXISTS `liberado_por` varchar(255) DEFAULT NULL AFTER `solicitado_por`,
ADD COLUMN IF NOT EXISTS `enviado_lab_por` varchar(255) DEFAULT NULL AFTER `revisado_por`;

-- Actualizar calidad_especificacion_productos: Ajustar constraint
ALTER TABLE `calidad_especificacion_productos` 
MODIFY COLUMN `id_producto` int(11) NOT NULL;

-- =========================================================================
-- SECCIÓN 3: CREAR CONSTRAINTS Y FOREIGN KEYS
-- =========================================================================

-- Foreign Keys para delegacion_tareas
ALTER TABLE `delegacion_tareas`
ADD CONSTRAINT `delegacion_tareas_ibfk_1` FOREIGN KEY (`id_tarea`) REFERENCES `tareas` (`id`);

-- Foreign Keys para paginas
ALTER TABLE `paginas`
ADD CONSTRAINT `fk_paginas_tipos` FOREIGN KEY (`id_tipo_pagina`) REFERENCES `tipos_paginas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Foreign Keys para paginas_roles
ALTER TABLE `paginas_roles`
ADD CONSTRAINT `fk_paginas_roles_pagina` FOREIGN KEY (`pagina_id`) REFERENCES `paginas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_paginas_roles_rol` FOREIGN KEY (`rol_pagina_id`) REFERENCES `roles_pagina` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Foreign Keys para usuarios_modulos
ALTER TABLE `usuarios_modulos`
ADD CONSTRAINT `fk_usuarios_modulos_tipo_pagina` FOREIGN KEY (`tipo_pagina_id`) REFERENCES `tipos_paginas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_usuarios_modulos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Foreign Keys para usuarios_modulos_roles
ALTER TABLE `usuarios_modulos_roles`
ADD CONSTRAINT `fk_umr_rol` FOREIGN KEY (`rol_pagina_id`) REFERENCES `roles_pagina` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_umr_usuario_modulo` FOREIGN KEY (`usuario_modulo_id`) REFERENCES `usuarios_modulos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Foreign Keys para tareas_cambio_usuarios
ALTER TABLE `tareas_cambio_usuarios`
ADD CONSTRAINT `delegaciones_firmas_ibfk_1` FOREIGN KEY (`id_tarea`) REFERENCES `tareas` (`id`);

-- Actualizar Foreign Key para calidad_analisis (si no existe)
ALTER TABLE `calidad_analisis`
ADD CONSTRAINT `calidad_analisis_ibfk_1` FOREIGN KEY (`id_especificacion_producto`) REFERENCES `calidad_especificacion_productos` (`id_especificacion`);

-- Actualizar Foreign Key para calidad_especificacion_productos (si no existe)
ALTER TABLE `calidad_especificacion_productos`
ADD CONSTRAINT `calidad_especificacion_productos_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `calidad_productos` (`id`);

-- =========================================================================
-- SECCIÓN 4: AJUSTES FINALES Y VERIFICACIONES
-- =========================================================================

-- Verificar que la tabla calidad_otros_documentos tenga el constraint correcto
ALTER TABLE `calidad_otros_documentos`
ADD CONSTRAINT `calidad_otros_documentos_ibfk_1` FOREIGN KEY (`tipo`) REFERENCES `calidad_otros_documentos_tipos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

COMMIT;

-- =========================================================================
-- FIN DEL SCRIPT DE ACTUALIZACIÓN
-- =========================================================================
-- IMPORTANTE: 
-- - Los AUTO_INCREMENT existentes NO fueron modificados
-- - Los datos existentes se mantienen intactos
-- - Solo se agregaron nuevas funcionalidades de customware
-- =========================================================================