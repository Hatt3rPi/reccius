# Nuevas tablas para la seleccionde pagina

```sql
-- ======================================
-- Creación de la tabla para Tipos de Página
-- ======================================
CREATE TABLE `tipos_paginas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ejemplo de inserción de tipos de página:
INSERT INTO tipos_paginas (nombre) VALUES 
('General'),
('usuarios_y_roles'),
('Calidad'),
('Recetario'),
('Produccion');

-- ======================================
-- Creación de la tabla para Páginas
-- ======================================
CREATE TABLE `paginas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_tipo_pagina` INT NOT NULL,
  `nombre` VARCHAR(150) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_paginas_tipos_idx` (`id_tipo_pagina`),
  CONSTRAINT `fk_paginas_tipos` FOREIGN KEY (`id_tipo_pagina`) REFERENCES `tipos_paginas` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tipo General (id_tipo_pagina = 1)
INSERT INTO paginas (id_tipo_pagina, nombre, url) VALUES
(1, 'Home', '/home');

-- Tipo usuarios_y_roles (id_tipo_pagina = 2)
INSERT INTO paginas (id_tipo_pagina, nombre, url) VALUES
(2, 'Crear Usuario', '/crear_usuario'),
(2, 'Asignar Roles', '/asignar_roles'),
(2, 'Accesos a paginas', '/accesos_paginas');

-- Tipo Calidad (id_tipo_pagina = 3)
INSERT INTO paginas (id_tipo_pagina, nombre, url) VALUES
(3, 'Crear Especificaciones de Producto', '/crear_especificaciones'),
(3, 'Listado de Especificaciones de Producto', '/listado_especificaciones'),
(3, 'Listado de Solicitudes de Análisis', '/listado_solicitudes_analisis'),
(3, 'Listado de Actas de Muestreo', '/listado_acta_muestreo'),
(3, 'Productos en Cuarentena y Liberados', '/productos_calidad');

-- Tipo Recetario (id_tipo_pagina = 4)
INSERT INTO paginas (id_tipo_pagina, nombre, url) VALUES
(4, 'Cotizador - Ingreso', '/cotizador_ingreso'),
(4, 'Cotizador - Buscar', '/cotizador_busqueda');

-- Tipo Produccion (id_tipo_pagina = 5)
INSERT INTO paginas (id_tipo_pagina, nombre, url) VALUES
(5, 'Ingreso Ordenes de Compra', '/ingreso_oc'),
(5, 'Listado de Ordenes de Compra', '/listado_oc'),
(5, 'Listado de Clientes', '/listado_clientes'),
(5, 'Pantalla 5 (Producción)', '/pantalla5'),
(5, 'Pantalla 6 (Facturación)', '/pantalla6'),
(5, 'Pantalla 7 (Despacho)', '/pantalla7'),
(5, 'Pantalla 8 (Cobranza)', '/pantalla8'),
(5, 'Pantalla 9 (Vista General)', '/pantalla9');

-- ======================================
-- Creación de la tabla para Relación Usuario-Página
-- ======================================
CREATE TABLE `usuarios_paginas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `usuario_id` INT NOT NULL,
  `pagina_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_usuarios_paginas_usuario_idx` (`usuario_id`),
  KEY `fk_usuarios_paginas_pagina_idx` (`pagina_id`),
  CONSTRAINT `fk_usuarios_paginas_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_usuarios_paginas_pagina` FOREIGN KEY (`pagina_id`) REFERENCES `paginas` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ejemplo de inserción de relación entre usuario y página:
--  INSERT INTO `usuarios_paginas` (`usuario_id`, `pagina_id`) VALUES
--  (1, 1), -- el usuario con id=1 tiene acceso al Dashboard de Administración
--  (1, 2), -- acceso a Gestión de Usuarios
--  (2, 3), -- el usuario con id=2 tiene acceso a Reportes de Ventas
--  (3, 4); -- el usuario con id=3 tiene acceso al Listado de Clientes

-- ======================================
-- Creación de la tabla para Roles de Página
-- ======================================
CREATE TABLE `roles_pagina` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  `descripcion` VARCHAR(255),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar roles básicos
INSERT INTO roles_pagina (id, nombre, descripcion) VALUES 
( 1,'Admin', 'Control total sobre la página'),
( 2,'Lectura', 'Solo puede ver la página'),
( 3,'Escritura', 'Puede ver y modificar contenido');

-- ======================================
-- Creación de la tabla de Relación Usuario-Página-Rol
-- ======================================
CREATE TABLE `usuarios_paginas_roles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `usuario_pagina_id` INT NOT NULL,
  `rol_pagina_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_upr_usuario_pagina_idx` (`usuario_pagina_id`),
  KEY `fk_upr_rol_idx` (`rol_pagina_id`),
  CONSTRAINT `fk_upr_usuario_pagina` FOREIGN KEY (`usuario_pagina_id`) REFERENCES `usuarios_paginas` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_upr_rol` FOREIGN KEY (`rol_pagina_id`) REFERENCES `roles_pagina` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
