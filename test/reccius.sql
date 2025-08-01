-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 01, 2025 at 12:29 AM
-- Server version: 10.6.21-MariaDB-cll-lve
-- PHP Version: 8.3.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `recciusc_customware`
--

-- --------------------------------------------------------

--
-- Table structure for table `administracion_roles_y_usuarios`
--

CREATE TABLE `administracion_roles_y_usuarios` (
  `id_rol` int(11) NOT NULL,
  `url` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_acta_liberacion`
--

CREATE TABLE `calidad_acta_liberacion` (
  `id` int(11) NOT NULL,
  `numero_registro` varchar(50) DEFAULT NULL,
  `version_registro` int(11) NOT NULL,
  `numero_acta` varchar(50) DEFAULT NULL,
  `version_acta` int(11) NOT NULL DEFAULT 1,
  `fecha_acta` date DEFAULT NULL,
  `fecha_muestreo` date DEFAULT NULL,
  `id_especificacion` int(11) DEFAULT NULL,
  `id_producto` int(11) NOT NULL,
  `id_analisisExterno` int(11) NOT NULL,
  `id_actaMuestreo` int(11) NOT NULL,
  `id_cuarentena` int(11) DEFAULT NULL COMMENT 'calidad_especificacion_productos',
  `aux_autoincremental` int(11) NOT NULL,
  `aux_anomes` int(11) NOT NULL,
  `aux_tipo` varchar(50) NOT NULL,
  `estado` varchar(100) NOT NULL DEFAULT 'Pendiente Revisión',
  `cantidad_real_liberada` varchar(255) DEFAULT NULL,
  `nro_parte_ingreso` varchar(255) DEFAULT NULL,
  `revision_estados` varchar(4) DEFAULT NULL,
  `revision_liberacion` varchar(4) DEFAULT NULL,
  `obs1` text DEFAULT NULL,
  `obs2` text DEFAULT NULL,
  `obs3` text DEFAULT NULL,
  `obs4` text DEFAULT NULL,
  `usuario_firma1` varchar(50) DEFAULT NULL,
  `fecha_firma1` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_acta_muestreo`
--

CREATE TABLE `calidad_acta_muestreo` (
  `id` int(11) NOT NULL,
  `numero_registro` varchar(50) DEFAULT NULL,
  `version_registro` int(11) NOT NULL,
  `id_original` int(11) DEFAULT NULL,
  `numero_acta` varchar(50) DEFAULT NULL,
  `version_acta` int(11) NOT NULL,
  `fecha_muestreo` date DEFAULT NULL,
  `id_especificacion` int(11) DEFAULT NULL,
  `id_producto` int(11) NOT NULL,
  `id_analisisExterno` int(11) NOT NULL,
  `aux_autoincremental` int(11) NOT NULL,
  `aux_anomes` int(11) NOT NULL,
  `aux_tipo` varchar(50) NOT NULL,
  `estado` varchar(100) NOT NULL DEFAULT 'Pendiente Muestreo',
  `responsable` varchar(50) DEFAULT NULL,
  `ejecutor` varchar(50) DEFAULT NULL,
  `verificador` varchar(50) DEFAULT NULL,
  `fecha_firma_responsable` date DEFAULT NULL,
  `fecha_firma_ejecutor` date DEFAULT NULL,
  `fecha_firma_verificador` date DEFAULT NULL,
  `fecha_firma_muestreador` date DEFAULT NULL,
  `resultados_muestrador` varchar(20) DEFAULT NULL,
  `resultados_responsable` varchar(20) DEFAULT NULL,
  `pregunta5` text DEFAULT NULL,
  `pregunta6` text DEFAULT NULL,
  `pregunta7` text DEFAULT NULL,
  `pregunta8` text DEFAULT NULL,
  `plan_muestreo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`plan_muestreo`)),
  `muestreador` varchar(50) DEFAULT NULL,
  `fecha_rechazo` date DEFAULT NULL,
  `motivo_rechazo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_analisis`
--

CREATE TABLE `calidad_analisis` (
  `id_analisis` int(11) NOT NULL,
  `id_especificacion_producto` int(11) DEFAULT NULL,
  `tipo_analisis` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `descripcion_analisis` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `metodologia` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `criterios_aceptacion` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `resultado_laboratorio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_analisis_externo`
--

CREATE TABLE `calidad_analisis_externo` (
  `id` int(11) NOT NULL,
  `estado` varchar(255) NOT NULL,
  `numero_registro` varchar(50) DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `id_original` int(11) DEFAULT NULL,
  `numero_solicitud` varchar(50) DEFAULT NULL,
  `elaborado_por` text DEFAULT NULL,
  `pais_origen` text DEFAULT NULL,
  `proveedor` text DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `fecha_liberacion` date DEFAULT NULL,
  `laboratorio` varchar(255) DEFAULT NULL,
  `fecha_solicitud` date DEFAULT NULL,
  `analisis_segun` varchar(255) DEFAULT NULL,
  `numero_documento` varchar(50) DEFAULT NULL,
  `fecha_cotizacion` date DEFAULT NULL,
  `estandar_segun` varchar(50) DEFAULT NULL,
  `estandar_otro` varchar(50) DEFAULT NULL,
  `hds_adjunto` varchar(50) DEFAULT NULL,
  `hds_otro` varchar(50) DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `fecha_entrega_estimada` date NOT NULL,
  `id_especificacion` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `lote` varchar(50) DEFAULT NULL,
  `registro_isp` varchar(50) DEFAULT NULL,
  `condicion_almacenamiento` mediumtext DEFAULT NULL,
  `tipo_analisis` varchar(255) NOT NULL,
  `muestreado_por` varchar(255) DEFAULT NULL,
  `numero_pos` varchar(50) DEFAULT NULL,
  `codigo_mastersoft` varchar(50) DEFAULT NULL,
  `tamano_lote` varchar(50) DEFAULT NULL,
  `fecha_elaboracion` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `tamano_muestra` varchar(50) DEFAULT NULL,
  `tamano_contramuestra` varchar(50) DEFAULT NULL,
  `observaciones` mediumtext DEFAULT NULL,
  `solicitado_por` varchar(255) DEFAULT NULL,
  `revisado_por` varchar(255) DEFAULT NULL,
  `fecha_firma_1` date DEFAULT NULL,
  `fecha_firma_2` date DEFAULT NULL,
  `fecha_firma_revisor` date DEFAULT NULL,
  `id_cuarentena` int(11) DEFAULT NULL,
  `fecha_envio` date DEFAULT NULL,
  `am_verificado_por` varchar(255) NOT NULL,
  `am_ejecutado_por` varchar(255) NOT NULL,
  `resultados_analisis` varchar(50) DEFAULT NULL,
  `laboratorio_nro_analisis` varchar(255) DEFAULT NULL,
  `laboratorio_fecha_analisis` date DEFAULT NULL,
  `url_certificado_de_analisis_externo` varchar(255) DEFAULT NULL,
  `url_certificado_acta_de_muestreo` varchar(255) DEFAULT NULL,
  `url_certificado_solicitud_analisis_externo` varchar(255) DEFAULT NULL,
  `url_certificado_solicitud_analisis_externo_con_resultados` varchar(255) DEFAULT NULL,
  `url_documento_adicional` varchar(255) DEFAULT NULL,
  `aux_autoincremental` int(11) NOT NULL,
  `aux_anomes` int(11) NOT NULL,
  `aux_tipo` varchar(50) NOT NULL,
  `motivo_eliminacion` text DEFAULT NULL,
  `fecha_eliminacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_especificacion_productos`
--

CREATE TABLE `calidad_especificacion_productos` (
  `id_especificacion` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `estado` varchar(255) NOT NULL DEFAULT 'Pendiente de Revisión',
  `documento` varchar(50) DEFAULT NULL,
  `codigo_mastersoft` varchar(50) DEFAULT NULL,
  `version` varchar(50) DEFAULT NULL,
  `id_especificacion_original` int(11) DEFAULT NULL,
  `vigencia` int(11) NOT NULL,
  `creado_por` varchar(255) DEFAULT NULL,
  `revisado_por` varchar(255) DEFAULT NULL,
  `aprobado_por` varchar(255) DEFAULT NULL,
  `fecha_edicion` date DEFAULT NULL,
  `fecha_revision` date DEFAULT NULL,
  `fecha_aprobacion` date DEFAULT NULL,
  `fecha_expiracion` date DEFAULT NULL,
  `motivo_eliminacion` text DEFAULT NULL,
  `fecha_eliminacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_opciones_desplegables`
--

CREATE TABLE `calidad_opciones_desplegables` (
  `id` int(11) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `nombre_opcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_otros_documentos`
--

CREATE TABLE `calidad_otros_documentos` (
  `id` int(11) NOT NULL,
  `id_productos_analizados` int(11) NOT NULL,
  `url` mediumtext NOT NULL,
  `nombre_documento` mediumtext NOT NULL,
  `usuario_carga` varchar(50) NOT NULL,
  `fecha_carga` date NOT NULL,
  `tipo` int(11) NOT NULL,
  `estado` char(1) CHARACTER SET armscii8 COLLATE armscii8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_productos`
--

CREATE TABLE `calidad_productos` (
  `id` int(11) NOT NULL,
  `identificador_producto` varchar(5) DEFAULT NULL,
  `nombre_producto` varchar(255) NOT NULL,
  `tipo_producto` varchar(255) DEFAULT NULL,
  `tipo_concentracion` varchar(50) DEFAULT NULL,
  `concentracion` varchar(255) DEFAULT NULL,
  `formato` varchar(255) DEFAULT NULL,
  `elaborado_por` varchar(255) DEFAULT NULL,
  `pais_origen` varchar(255) DEFAULT NULL,
  `documento_ingreso` varchar(255) DEFAULT NULL,
  `proveedor` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_productos_analizados`
--

CREATE TABLE `calidad_productos_analizados` (
  `id` int(11) NOT NULL,
  `id_especificacion` int(11) DEFAULT NULL,
  `id_producto` int(11) NOT NULL,
  `id_analisisExterno` int(11) NOT NULL,
  `id_actaMuestreo` int(11) DEFAULT NULL,
  `id_actaLiberacion` int(11) DEFAULT NULL,
  `estado` varchar(100) NOT NULL COMMENT 'En cuarentena; liberado; rechazado',
  `cantidad_real_liberada` varchar(255) DEFAULT NULL,
  `nro_parte_ingreso` varchar(255) DEFAULT NULL,
  `lote` varchar(50) NOT NULL,
  `tamano_lote` varchar(50) NOT NULL,
  `fecha_in_cuarentena` date DEFAULT NULL COMMENT 'Fecha creación análisis externo',
  `fecha_out_cuarentena` date DEFAULT NULL COMMENT 'Fecha firma acta de liberación o rechazo',
  `fecha_elaboracion` date NOT NULL,
  `fecha_vencimiento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_resultados_analisis`
--

CREATE TABLE `calidad_resultados_analisis` (
  `id` int(11) NOT NULL,
  `id_analisisExterno` int(11) DEFAULT NULL,
  `id_analisis` int(11) DEFAULT NULL,
  `id_especificacion_producto` int(11) DEFAULT NULL,
  `criterios_aceptacion` text DEFAULT NULL,
  `descripcion_analisis` text DEFAULT NULL,
  `metodologia` text DEFAULT NULL,
  `resultado_laboratorio` text DEFAULT NULL,
  `tipo_analisis` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feriados_chile`
--

CREATE TABLE `feriados_chile` (
  `nombre` text NOT NULL,
  `comentarios` text NOT NULL,
  `fecha` date NOT NULL,
  `dia_semana` int(11) NOT NULL,
  `irrenunciable` tinyint(1) NOT NULL,
  `tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laboratorio`
--

CREATE TABLE `laboratorio` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `correo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laboratorio_con_copia`
--

CREATE TABLE `laboratorio_con_copia` (
  `id` int(11) NOT NULL,
  `laboratorio_id` int(11) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recetariomagistral_costosproduccion`
--

CREATE TABLE `recetariomagistral_costosproduccion` (
  `id` int(11) NOT NULL,
  `tipo_costo` varchar(50) NOT NULL,
  `detalle_costo` varchar(255) NOT NULL,
  `preparacion` varchar(50) NOT NULL,
  `detalle_preparacion` varchar(255) NOT NULL,
  `valor_clp` decimal(10,2) NOT NULL,
  `ultima_modificacion_fecha` date NOT NULL,
  `ultima_modificacion_usuario` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recetariomagistral_opcionesdeplegables`
--

CREATE TABLE `recetariomagistral_opcionesdeplegables` (
  `id` int(11) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `nombre_opcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recetariomagistral_tablaconversion`
--

CREATE TABLE `recetariomagistral_tablaconversion` (
  `unidad` varchar(50) NOT NULL,
  `unidad_minima` varchar(50) NOT NULL,
  `conversion_a_unidadminima` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recetariomagistral_tarifas_materiasprimas`
--

CREATE TABLE `recetariomagistral_tarifas_materiasprimas` (
  `id` int(11) NOT NULL,
  `materia_prima` varchar(255) NOT NULL,
  `precio_por_kg_lt` decimal(11,3) NOT NULL,
  `factor_reccius` double NOT NULL,
  `disponibilidad` enum('si','no') NOT NULL,
  `ultima_modificacion_fecha` date NOT NULL,
  `ultima_modificacion_usuario` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tareas`
--

CREATE TABLE `tareas` (
  `id` int(11) NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `fecha_done` datetime DEFAULT NULL,
  `usuario_creador` varchar(255) NOT NULL,
  `usuario_ejecutor` varchar(255) DEFAULT NULL,
  `descripcion_tarea` mediumtext NOT NULL,
  `estado` varchar(50) NOT NULL DEFAULT 'Activo',
  `prioridad` enum('1','2','3') NOT NULL COMMENT '1: Alta, 2: Media, 3: Baja',
  `tipo` varchar(255) NOT NULL,
  `id_relacion` int(11) DEFAULT NULL,
  `usuario_done` varchar(255) DEFAULT NULL,
  `tabla_relacion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tokens_reset`
--

CREATE TABLE `tokens_reset` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_expiracion` datetime NOT NULL,
  `consumido` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 disponible, 1 utilizado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trazabilidad`
--

CREATE TABLE `trazabilidad` (
  `id` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario` varchar(255) DEFAULT NULL,
  `archivo` varchar(255) NOT NULL,
  `accion` varchar(100) NOT NULL,
  `base` varchar(100) NOT NULL,
  `identificador_base` int(11) DEFAULT NULL,
  `query` mediumtext NOT NULL,
  `parametros` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parametros`)),
  `resultado` tinyint(1) NOT NULL,
  `error` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `nombre_corto` varchar(50) DEFAULT NULL,
  `correo` varchar(50) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `foto_firma` varchar(255) DEFAULT NULL,
  `qr_documento` varchar(255) DEFAULT NULL,
  `ruta_registroPrestadoresSalud` varchar(255) DEFAULT NULL,
  `cargo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administracion_roles_y_usuarios`
--
ALTER TABLE `administracion_roles_y_usuarios`
  ADD KEY `fk_roles_administracion` (`id_rol`);

--
-- Indexes for table `calidad_acta_liberacion`
--
ALTER TABLE `calidad_acta_liberacion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calidad_acta_muestreo`
--
ALTER TABLE `calidad_acta_muestreo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calidad_analisis`
--
ALTER TABLE `calidad_analisis`
  ADD PRIMARY KEY (`id_analisis`);

--
-- Indexes for table `calidad_analisis_externo`
--
ALTER TABLE `calidad_analisis_externo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calidad_especificacion_productos`
--
ALTER TABLE `calidad_especificacion_productos`
  ADD PRIMARY KEY (`id_especificacion`);

--
-- Indexes for table `calidad_opciones_desplegables`
--
ALTER TABLE `calidad_opciones_desplegables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calidad_otros_documentos`
--
ALTER TABLE `calidad_otros_documentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo` (`tipo`);

--
-- Indexes for table `calidad_productos`
--
ALTER TABLE `calidad_productos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calidad_productos_analizados`
--
ALTER TABLE `calidad_productos_analizados`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calidad_resultados_analisis`
--
ALTER TABLE `calidad_resultados_analisis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feriados_chile`
--
ALTER TABLE `feriados_chile`
  ADD UNIQUE KEY `unique_nombre_fecha` (`nombre`(100),`fecha`);

--
-- Indexes for table `laboratorio`
--
ALTER TABLE `laboratorio`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laboratorio_con_copia`
--
ALTER TABLE `laboratorio_con_copia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `laboratorio_id` (`laboratorio_id`,`correo`);

--
-- Indexes for table `recetariomagistral_costosproduccion`
--
ALTER TABLE `recetariomagistral_costosproduccion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recetariomagistral_opcionesdeplegables`
--
ALTER TABLE `recetariomagistral_opcionesdeplegables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recetariomagistral_tablaconversion`
--
ALTER TABLE `recetariomagistral_tablaconversion`
  ADD PRIMARY KEY (`unidad`);

--
-- Indexes for table `recetariomagistral_tarifas_materiasprimas`
--
ALTER TABLE `recetariomagistral_tarifas_materiasprimas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `materia_prima` (`materia_prima`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tokens_reset`
--
ALTER TABLE `tokens_reset`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `trazabilidad`
--
ALTER TABLE `trazabilidad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calidad_acta_liberacion`
--
ALTER TABLE `calidad_acta_liberacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calidad_acta_muestreo`
--
ALTER TABLE `calidad_acta_muestreo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calidad_analisis`
--
ALTER TABLE `calidad_analisis`
  MODIFY `id_analisis` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calidad_analisis_externo`
--
ALTER TABLE `calidad_analisis_externo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calidad_especificacion_productos`
--
ALTER TABLE `calidad_especificacion_productos`
  MODIFY `id_especificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calidad_opciones_desplegables`
--
ALTER TABLE `calidad_opciones_desplegables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calidad_otros_documentos`
--
ALTER TABLE `calidad_otros_documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calidad_productos`
--
ALTER TABLE `calidad_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calidad_productos_analizados`
--
ALTER TABLE `calidad_productos_analizados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calidad_resultados_analisis`
--
ALTER TABLE `calidad_resultados_analisis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laboratorio`
--
ALTER TABLE `laboratorio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laboratorio_con_copia`
--
ALTER TABLE `laboratorio_con_copia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tokens_reset`
--
ALTER TABLE `tokens_reset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trazabilidad`
--
ALTER TABLE `trazabilidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `administracion_roles_y_usuarios`
--
ALTER TABLE `administracion_roles_y_usuarios`
  ADD CONSTRAINT `fk_roles_administracion` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `calidad_analisis`
--
ALTER TABLE `calidad_analisis`
  ADD CONSTRAINT `calidad_analisis_ibfk_1` FOREIGN KEY (`id_especificacion_producto`) REFERENCES `calidad_especificacion_productos` (`id_especificacion`);

--
-- Constraints for table `calidad_especificacion_productos`
--
ALTER TABLE `calidad_especificacion_productos`
  ADD CONSTRAINT `calidad_especificacion_productos_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `calidad_productos` (`id`);

--
-- Constraints for table `calidad_otros_documentos`
--
ALTER TABLE `calidad_otros_documentos`
  ADD CONSTRAINT `calidad_otros_documentos_ibfk_1` FOREIGN KEY (`tipo`) REFERENCES `calidad_opciones_desplegables` (`id`);

--
-- Constraints for table `laboratorio_con_copia`
--
ALTER TABLE `laboratorio_con_copia`
  ADD CONSTRAINT `laboratorio_con_copia_ibfk_1` FOREIGN KEY (`laboratorio_id`) REFERENCES `laboratorio` (`id`);

--
-- Constraints for table `tokens_reset`
--
ALTER TABLE `tokens_reset`
  ADD CONSTRAINT `tokens_reset_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
