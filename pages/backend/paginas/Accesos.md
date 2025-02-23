# Nuevas tablas para la seleccionde pagina

```sql
CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `usuario` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `contrasena` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `rol_id` int DEFAULT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `nombre_corto` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `correo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `foto_perfil` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `foto_firma` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `qr_documento` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ruta_registroPrestadoresSalud` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `cargo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rol_id` (`rol_id`);

ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
-- Creación de la tabla para Roles de Página
-- ======================================
CREATE TABLE `roles_pagina` (
  `id` int NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `orden` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `roles_pagina`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `roles_pagina`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;


-- Insertar roles básicos
INSERT INTO roles_pagina (id, nombre, descripcion) VALUES 
( 1,'Administrador', 'Control total sobre la página'),
( 3,'Usuario', 'Puede ver y modificar contenido'),
( 2,'Visualizador', 'Solo puede ver la página');

-- Nueva relación entre roles_pagina y paginas
CREATE TABLE `paginas_roles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `pagina_id` INT NOT NULL,
  `rol_pagina_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_paginas_roles_pagina` FOREIGN KEY (`pagina_id`) REFERENCES `paginas` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_paginas_roles_rol` FOREIGN KEY (`rol_pagina_id`) REFERENCES `roles_pagina` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- Creación de la tabla para Relación Usuario-Módulo (Tipos de Página)
-- ======================================
CREATE TABLE `usuarios_modulos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `usuario_id` INT NOT NULL,
  `tipo_pagina_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_usuarios_modulos_usuario_idx` (`usuario_id`),
  KEY `fk_usuarios_modulos_tipo_pagina_idx` (`tipo_pagina_id`),
  CONSTRAINT `fk_usuarios_modulos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_usuarios_modulos_tipo_pagina` FOREIGN KEY (`tipo_pagina_id`) REFERENCES `tipos_paginas` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- Creación de la tabla para Relación Usuario-Módulo-Rol
-- ======================================
CREATE TABLE `usuarios_modulos_roles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `usuario_modulo_id` INT NOT NULL,
  `rol_pagina_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_umr_usuario_modulo_idx` (`usuario_modulo_id`),
  KEY `fk_umr_rol_idx` (`rol_pagina_id`),
  CONSTRAINT `fk_umr_usuario_modulo` FOREIGN KEY (`usuario_modulo_id`) REFERENCES `usuarios_modulos` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_umr_rol` FOREIGN KEY (`rol_pagina_id`) REFERENCES `roles_pagina` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```