-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 01, 2025 at 12:29 AM
-- Server version: 8.0.42-cll-lve
-- PHP Version: 8.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `customw2_reccius`
--

-- --------------------------------------------------------

--
-- Table structure for table `administracion_roles_y_usuarios`
--

CREATE TABLE `administracion_roles_y_usuarios` (
  `id_rol` int NOT NULL,
  `url` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_acta_liberacion`
--

CREATE TABLE `calidad_acta_liberacion` (
  `id` int NOT NULL,
  `numero_registro` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `version_registro` int NOT NULL,
  `numero_acta` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `version_acta` int NOT NULL DEFAULT '1',
  `fecha_acta` date DEFAULT NULL,
  `fecha_muestreo` date DEFAULT NULL,
  `id_especificacion` int DEFAULT NULL,
  `id_producto` int NOT NULL,
  `id_analisisExterno` int NOT NULL,
  `id_actaMuestreo` int NOT NULL,
  `id_cuarentena` int DEFAULT NULL COMMENT 'calidad_especificacion_productos',
  `aux_autoincremental` int NOT NULL,
  `aux_anomes` int NOT NULL,
  `aux_tipo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `estado` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'Pendiente Revisión',
  `cantidad_real_liberada` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `nro_parte_ingreso` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `revision_estados` varchar(4) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `revision_liberacion` varchar(4) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `obs1` text COLLATE utf8mb3_unicode_ci,
  `obs2` text COLLATE utf8mb3_unicode_ci,
  `obs3` text COLLATE utf8mb3_unicode_ci,
  `obs4` text COLLATE utf8mb3_unicode_ci,
  `usuario_firma1` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fecha_firma1` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_acta_muestreo`
--

CREATE TABLE `calidad_acta_muestreo` (
  `id` int NOT NULL,
  `numero_registro` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `version_registro` int NOT NULL,
  `id_original` int DEFAULT NULL,
  `numero_acta` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `version_acta` int NOT NULL,
  `fecha_muestreo` date DEFAULT NULL,
  `id_especificacion` int DEFAULT NULL,
  `id_producto` int NOT NULL,
  `id_analisisExterno` int NOT NULL,
  `aux_autoincremental` int DEFAULT NULL,
  `aux_anomes` int DEFAULT NULL,
  `aux_tipo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `estado` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'Pendiente Muestreo',
  `muestreador` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fecha_rechazo` date DEFAULT NULL,
  `motivo_rechazo` text COLLATE utf8mb3_unicode_ci,
  `responsable` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `verificador` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fecha_firma_muestreador` date DEFAULT NULL,
  `fecha_firma_responsable` date DEFAULT NULL,
  `fecha_firma_verificador` date DEFAULT NULL,
  `resultados_muestrador` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `resultados_responsable` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pregunta5` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `pregunta6` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `pregunta7` text COLLATE utf8mb3_unicode_ci,
  `pregunta8` text COLLATE utf8mb3_unicode_ci,
  `plan_muestreo` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_analisis`
--

CREATE TABLE `calidad_analisis` (
  `id_analisis` int NOT NULL,
  `id_especificacion_producto` int NOT NULL,
  `tipo_analisis` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `descripcion_analisis` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `metodologia` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `criterios_aceptacion` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `resultado_laboratorio` text COLLATE utf8mb3_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_analisis_externo`
--

CREATE TABLE `calidad_analisis_externo` (
  `id` int NOT NULL,
  `estado` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `numero_registro` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `version` int DEFAULT NULL,
  `id_original` int DEFAULT NULL,
  `numero_solicitud` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `elaborado_por` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `pais_origen` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `proveedor` text COLLATE utf8mb3_unicode_ci,
  `fecha_registro` date NOT NULL,
  `fecha_liberacion` date DEFAULT NULL,
  `laboratorio` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fecha_solicitud` date DEFAULT NULL,
  `analisis_segun` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `numero_documento` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fecha_cotizacion` date DEFAULT NULL,
  `estandar_segun` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `estandar_otro` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `hds_adjunto` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `hds_otro` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fecha_envio` date DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `fecha_entrega_estimada` date NOT NULL,
  `id_especificacion` int DEFAULT NULL,
  `id_producto` int DEFAULT NULL,
  `id_cuarentena` int DEFAULT NULL,
  `lote` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `registro_isp` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `condicion_almacenamiento` mediumtext COLLATE utf8mb3_unicode_ci,
  `tipo_analisis` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `muestreado_por` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `am_verificado_por` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `am_ejecutado_por` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `numero_pos` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `codigo_mastersoft` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `tamano_lote` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fecha_elaboracion` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `tamano_muestra` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `tamano_contramuestra` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `observaciones` mediumtext COLLATE utf8mb3_unicode_ci,
  `solicitado_por` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `liberado_por` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `revisado_por` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `enviado_lab_por` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fecha_firma_1` date DEFAULT NULL,
  `fecha_firma_2` date DEFAULT NULL,
  `resultados_analisis` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fecha_firma_revisor` date DEFAULT NULL,
  `laboratorio_nro_analisis` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `laboratorio_fecha_analisis` date DEFAULT NULL,
  `url_certificado_de_analisis_externo` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `url_certificado_acta_de_muestreo` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `url_certificado_solicitud_analisis_externo` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `url_certificado_solicitud_analisis_externo_con_resultados` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `url_documento_adicional` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `aux_autoincremental` int DEFAULT NULL,
  `aux_anomes` int DEFAULT NULL,
  `aux_tipo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `motivo_eliminacion` text COLLATE utf8mb3_unicode_ci,
  `fecha_eliminacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_especificacion_productos`
--

CREATE TABLE `calidad_especificacion_productos` (
  `id_especificacion` int NOT NULL,
  `id_producto` int NOT NULL,
  `estado` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'Pendiente de Revisión',
  `documento` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `codigo_mastersoft` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `version` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `id_especificacion_original` int DEFAULT NULL,
  `vigencia` int NOT NULL,
  `creado_por` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `revisado_por` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `aprobado_por` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fecha_edicion` date DEFAULT NULL,
  `fecha_revision` date DEFAULT NULL,
  `fecha_aprobacion` date DEFAULT NULL,
  `fecha_expiracion` date DEFAULT NULL,
  `motivo_eliminacion` text COLLATE utf8mb3_unicode_ci,
  `fecha_eliminacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_opciones_desplegables`
--

CREATE TABLE `calidad_opciones_desplegables` (
  `id` int NOT NULL,
  `categoria` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `nombre_opcion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_otros_documentos`
--

CREATE TABLE `calidad_otros_documentos` (
  `id` int NOT NULL,
  `id_productos_analizados` int NOT NULL,
  `url` text NOT NULL,
  `nombre_documento` text NOT NULL,
  `usuario_carga` varchar(50) NOT NULL,
  `fecha_carga` date NOT NULL,
  `tipo` int NOT NULL,
  `estado` char(1) CHARACTER SET armscii8 COLLATE armscii8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_otros_documentos_tipos`
--

CREATE TABLE `calidad_otros_documentos_tipos` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_productos`
--

CREATE TABLE `calidad_productos` (
  `id` int NOT NULL,
  `identificador_producto` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `nombre_producto` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo_producto` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `tipo_concentracion` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `concentracion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `formato` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `elaborado_por` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pais_origen` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `documento_ingreso` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `proveedor` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calidad_productos_analizados`
--

CREATE TABLE `calidad_productos_analizados` (
  `id` int NOT NULL,
  `id_especificacion` int DEFAULT NULL,
  `id_producto` int NOT NULL,
  `id_analisisExterno` int NOT NULL,
  `id_actaMuestreo` int DEFAULT NULL,
  `id_actaLiberacion` int DEFAULT NULL,
  `estado` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'En cuarentena; liberado; rechazado',
  `cantidad_real_liberada` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `nro_parte_ingreso` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `lote` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tamano_lote` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
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
  `id` int NOT NULL,
  `id_analisisExterno` int DEFAULT NULL,
  `id_analisis` int DEFAULT NULL,
  `id_especificacion_producto` int DEFAULT NULL,
  `criterios_aceptacion` text,
  `descripcion_analisis` text,
  `metodologia` text,
  `resultado_laboratorio` text,
  `tipo_analisis` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `delegacion_tareas`
--

CREATE TABLE `delegacion_tareas` (
  `id` int NOT NULL,
  `id_tarea` int NOT NULL,
  `usuario_original` varchar(255) NOT NULL,
  `usuario_delegado` varchar(255) NOT NULL,
  `fecha_delegacion` datetime NOT NULL,
  `motivo` text,
  `estado` enum('activo','finalizado') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `feriados_chile`
--

CREATE TABLE `feriados_chile` (
  `nombre` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `comentarios` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `dia_semana` int NOT NULL,
  `irrenunciable` tinyint(1) NOT NULL,
  `tipo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laboratorio`
--

CREATE TABLE `laboratorio` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `correo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `laboratorio_con_copia`
--

CREATE TABLE `laboratorio_con_copia` (
  `id` int NOT NULL,
  `laboratorio_id` int NOT NULL,
  `correo` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `paginas`
--

CREATE TABLE `paginas` (
  `id` int NOT NULL,
  `id_tipo_pagina` int NOT NULL,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paginas_roles`
--

CREATE TABLE `paginas_roles` (
  `id` int NOT NULL,
  `pagina_id` int NOT NULL,
  `rol_pagina_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recetariomagistral_costosproduccion`
--

CREATE TABLE `recetariomagistral_costosproduccion` (
  `id` int NOT NULL,
  `tipo_costo` varchar(50) NOT NULL,
  `detalle_costo` varchar(255) NOT NULL,
  `preparacion` varchar(50) NOT NULL,
  `detalle_preparacion` varchar(255) NOT NULL,
  `valor_clp` decimal(10,2) NOT NULL,
  `ultima_modificacion_fecha` date NOT NULL,
  `ultima_modificacion_usuario` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `recetariomagistral_opcionesdeplegables`
--

CREATE TABLE `recetariomagistral_opcionesdeplegables` (
  `id` int NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `nombre_opcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `recetariomagistral_tablaconversion`
--

CREATE TABLE `recetariomagistral_tablaconversion` (
  `unidad` varchar(50) NOT NULL,
  `unidad_minima` varchar(50) NOT NULL,
  `conversion_a_unidadminima` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `recetariomagistral_tarifas_materiasprimas`
--

CREATE TABLE `recetariomagistral_tarifas_materiasprimas` (
  `id` int NOT NULL,
  `materia_prima` varchar(255) NOT NULL,
  `precio_por_kg_lt` decimal(11,3) NOT NULL,
  `factor_reccius` double NOT NULL,
  `disponibilidad` enum('si','no') NOT NULL,
  `ultima_modificacion_fecha` date NOT NULL,
  `ultima_modificacion_usuario` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles_pagina`
--

CREATE TABLE `roles_pagina` (
  `id` int NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `orden` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tareas`
--

CREATE TABLE `tareas` (
  `id` int NOT NULL,
  `fecha_ingreso` datetime NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `fecha_done` datetime DEFAULT NULL,
  `usuario_creador` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `usuario_ejecutor` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `usuario_done` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `descripcion_tarea` mediumtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `estado` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'Activo' COMMENT 'Activo; Finalizado; Fecha de Vencimiento cercano; Atrasado',
  `prioridad` enum('1','2','3') COLLATE utf8mb3_unicode_ci NOT NULL COMMENT '1: Alta, 2: Media, 3: Baja',
  `tipo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL COMMENT 'Firma 1; Firma 2; Firma 3; Generar Acta Muestreo; Completar análisis externo; Enviar a Laboratorio; Ingresar resultados Laboratorio; Generar acta de liberación',
  `id_relacion` int DEFAULT NULL,
  `tabla_relacion` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tareas_cambio_usuarios`
--

CREATE TABLE `tareas_cambio_usuarios` (
  `id` int NOT NULL,
  `id_tarea` int NOT NULL,
  `usuario_original` varchar(255) NOT NULL,
  `usuario_nuevo` varchar(255) NOT NULL,
  `fecha_cambio` datetime NOT NULL,
  `cambiado_por` varchar(50) NOT NULL,
  `motivo` text,
  `estado` enum('activo','finalizado') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tipos_paginas`
--

CREATE TABLE `tipos_paginas` (
  `id` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tokens_reset`
--

CREATE TABLE `tokens_reset` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_expiracion` datetime NOT NULL,
  `consumido` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trazabilidad`
--

CREATE TABLE `trazabilidad` (
  `id` int NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `archivo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `accion` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `base` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `identificador_base` int DEFAULT NULL,
  `query` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `parametros` json DEFAULT NULL,
  `resultado` tinyint(1) NOT NULL,
  `error` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `usuarios_modulos`
--

CREATE TABLE `usuarios_modulos` (
  `id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `tipo_pagina_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios_modulos_roles`
--

CREATE TABLE `usuarios_modulos_roles` (
  `id` int NOT NULL,
  `usuario_modulo_id` int NOT NULL,
  `rol_pagina_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  ADD PRIMARY KEY (`id_analisis`),
  ADD KEY `id_especificacion_producto` (`id_especificacion_producto`);

--
-- Indexes for table `calidad_analisis_externo`
--
ALTER TABLE `calidad_analisis_externo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calidad_especificacion_productos`
--
ALTER TABLE `calidad_especificacion_productos`
  ADD PRIMARY KEY (`id_especificacion`),
  ADD KEY `id_producto` (`id_producto`);

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
-- Indexes for table `calidad_otros_documentos_tipos`
--
ALTER TABLE `calidad_otros_documentos_tipos`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `delegacion_tareas`
--
ALTER TABLE `delegacion_tareas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tarea` (`id_tarea`);

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
-- Indexes for table `paginas`
--
ALTER TABLE `paginas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_paginas_tipos_idx` (`id_tipo_pagina`);

--
-- Indexes for table `paginas_roles`
--
ALTER TABLE `paginas_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_paginas_roles_pagina` (`pagina_id`),
  ADD KEY `fk_paginas_roles_rol` (`rol_pagina_id`);

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
-- Indexes for table `roles_pagina`
--
ALTER TABLE `roles_pagina`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tareas_cambio_usuarios`
--
ALTER TABLE `tareas_cambio_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tarea` (`id_tarea`);

--
-- Indexes for table `tipos_paginas`
--
ALTER TABLE `tipos_paginas`
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
-- Indexes for table `usuarios_modulos`
--
ALTER TABLE `usuarios_modulos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuarios_modulos_usuario_idx` (`usuario_id`),
  ADD KEY `fk_usuarios_modulos_tipo_pagina_idx` (`tipo_pagina_id`);

--
-- Indexes for table `usuarios_modulos_roles`
--
ALTER TABLE `usuarios_modulos_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_umr_usuario_modulo_idx` (`usuario_modulo_id`),
  ADD KEY `fk_umr_rol_idx` (`rol_pagina_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calidad_acta_liberacion`
--
ALTER TABLE `calidad_acta_liberacion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calidad_acta_muestreo`
--
ALTER TABLE `calidad_acta_muestreo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calidad_analisis`
--
ALTER TABLE `calidad_analisis`
  MODIFY `id_analisis` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calidad_analisis_externo`
--
ALTER TABLE `calidad_analisis_externo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calidad_especificacion_productos`
--
ALTER TABLE `calidad_especificacion_productos`
  MODIFY `id_especificacion` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calidad_opciones_desplegables`
--
ALTER TABLE `calidad_opciones_desplegables`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calidad_otros_documentos`
--
ALTER TABLE `calidad_otros_documentos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calidad_otros_documentos_tipos`
--
ALTER TABLE `calidad_otros_documentos_tipos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calidad_productos`
--
ALTER TABLE `calidad_productos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calidad_productos_analizados`
--
ALTER TABLE `calidad_productos_analizados`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calidad_resultados_analisis`
--
ALTER TABLE `calidad_resultados_analisis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delegacion_tareas`
--
ALTER TABLE `delegacion_tareas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laboratorio`
--
ALTER TABLE `laboratorio`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laboratorio_con_copia`
--
ALTER TABLE `laboratorio_con_copia`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paginas`
--
ALTER TABLE `paginas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paginas_roles`
--
ALTER TABLE `paginas_roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recetariomagistral_costosproduccion`
--
ALTER TABLE `recetariomagistral_costosproduccion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recetariomagistral_opcionesdeplegables`
--
ALTER TABLE `recetariomagistral_opcionesdeplegables`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recetariomagistral_tarifas_materiasprimas`
--
ALTER TABLE `recetariomagistral_tarifas_materiasprimas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles_pagina`
--
ALTER TABLE `roles_pagina`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tareas_cambio_usuarios`
--
ALTER TABLE `tareas_cambio_usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tipos_paginas`
--
ALTER TABLE `tipos_paginas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tokens_reset`
--
ALTER TABLE `tokens_reset`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trazabilidad`
--
ALTER TABLE `trazabilidad`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios_modulos`
--
ALTER TABLE `usuarios_modulos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios_modulos_roles`
--
ALTER TABLE `usuarios_modulos_roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `calidad_otros_documentos_ibfk_1` FOREIGN KEY (`tipo`) REFERENCES `calidad_opciones_desplegables` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `delegacion_tareas`
--
ALTER TABLE `delegacion_tareas`
  ADD CONSTRAINT `delegacion_tareas_ibfk_1` FOREIGN KEY (`id_tarea`) REFERENCES `tareas` (`id`);

--
-- Constraints for table `laboratorio_con_copia`
--
ALTER TABLE `laboratorio_con_copia`
  ADD CONSTRAINT `laboratorio_con_copia_ibfk_1` FOREIGN KEY (`laboratorio_id`) REFERENCES `laboratorio` (`id`);

--
-- Constraints for table `paginas`
--
ALTER TABLE `paginas`
  ADD CONSTRAINT `fk_paginas_tipos` FOREIGN KEY (`id_tipo_pagina`) REFERENCES `tipos_paginas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `paginas_roles`
--
ALTER TABLE `paginas_roles`
  ADD CONSTRAINT `fk_paginas_roles_pagina` FOREIGN KEY (`pagina_id`) REFERENCES `paginas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_paginas_roles_rol` FOREIGN KEY (`rol_pagina_id`) REFERENCES `roles_pagina` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tareas_cambio_usuarios`
--
ALTER TABLE `tareas_cambio_usuarios`
  ADD CONSTRAINT `delegaciones_firmas_ibfk_1` FOREIGN KEY (`id_tarea`) REFERENCES `tareas` (`id`);

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

--
-- Constraints for table `usuarios_modulos`
--
ALTER TABLE `usuarios_modulos`
  ADD CONSTRAINT `fk_usuarios_modulos_tipo_pagina` FOREIGN KEY (`tipo_pagina_id`) REFERENCES `tipos_paginas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuarios_modulos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usuarios_modulos_roles`
--
ALTER TABLE `usuarios_modulos_roles`
  ADD CONSTRAINT `fk_umr_rol` FOREIGN KEY (`rol_pagina_id`) REFERENCES `roles_pagina` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_umr_usuario_modulo` FOREIGN KEY (`usuario_modulo_id`) REFERENCES `usuarios_modulos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
