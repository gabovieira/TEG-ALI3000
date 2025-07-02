-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-07-2025 a las 05:17:54
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ali3000_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `accion` varchar(100) NOT NULL,
  `tabla_afectada` varchar(50) NOT NULL,
  `registro_id` int(11) DEFAULT NULL,
  `datos_anteriores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_anteriores`)),
  `datos_nuevos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_nuevos`)),
  `direccion_ip` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `fecha_accion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuraciones`
--

CREATE TABLE `configuraciones` (
  `id` int(11) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `valor` text NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` enum('texto','numero','booleano','json') DEFAULT 'texto',
  `categoria` varchar(50) DEFAULT 'general',
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `actualizado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuraciones`
--

INSERT INTO `configuraciones` (`id`, `clave`, `valor`, `descripcion`, `tipo`, `categoria`, `fecha_actualizacion`, `actualizado_por`) VALUES
(1, 'iva_porcentaje', '16.00', 'Porcentaje de IVA aplicado a facturas', 'numero', 'impuestos', '2025-06-28 19:16:47', NULL),
(2, 'islr_porcentaje', '3.00', 'Porcentaje de ISLR retenido en pagos', 'numero', 'impuestos', '2025-06-28 19:16:47', NULL),
(3, 'horas_maximas_normales', '8.0', 'Máximo de horas normales por día', 'numero', 'horas', '2025-06-28 19:16:47', NULL),
(4, 'horas_maximas_extra', '12.0', 'Máximo de horas extra por día', 'numero', 'horas', '2025-06-28 19:16:47', NULL),
(5, 'dias_limite_validacion', '8', 'Días límite para validar horas', 'numero', 'validacion', '2025-06-28 19:16:47', NULL),
(6, 'whatsapp_api_token', '', 'Token de API de WhatsApp', 'texto', 'notificaciones', '2025-06-28 19:16:47', NULL),
(7, 'bcv_api_url', 'https://api.bcv.org.ve/', 'URL de API del BCV para tasa de cambio', 'texto', 'api', '2025-06-28 19:16:47', NULL),
(8, 'empresa_nombre', 'ALI 3000, C.A.', 'Nombre de la empresa', 'texto', 'empresa', '2025-06-28 19:16:47', NULL),
(9, 'empresa_rif', 'J-123456789', 'RIF de la empresa', 'texto', 'empresa', '2025-06-28 19:16:47', NULL),
(10, 'prefijo_codigo_pago', 'ALI', 'Prefijo para códigos de pago', 'texto', 'pagos', '2025-06-28 19:16:47', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `rif` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `validador_id` int(11) NOT NULL,
  `estado` enum('activa','inactiva') DEFAULT 'activa',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `numero_factura` varchar(20) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `pago_id` int(11) NOT NULL,
  `fecha_emision` date NOT NULL,
  `descripcion` text NOT NULL,
  `subtotal_usd` decimal(10,2) NOT NULL,
  `iva_monto` decimal(10,2) NOT NULL,
  `total_usd` decimal(10,2) NOT NULL,
  `tasa_cambio` decimal(8,2) NOT NULL,
  `total_bs` decimal(12,2) NOT NULL,
  `estado` enum('generada','enviada','pagada') DEFAULT 'generada',
  `ruta_archivo` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` enum('whatsapp','email','sistema') NOT NULL,
  `asunto` varchar(255) NOT NULL,
  `mensaje` text NOT NULL,
  `estado` enum('pendiente','enviada','fallida','leida') DEFAULT 'pendiente',
  `datos_extra` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_extra`)),
  `fecha_programada` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_enviada` timestamp NULL DEFAULT NULL,
  `intentos` int(11) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `codigo_pago` varchar(20) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `periodo_inicio` date NOT NULL,
  `periodo_fin` date NOT NULL,
  `total_horas_normales` decimal(5,1) NOT NULL,
  `total_horas_extra` decimal(5,1) NOT NULL,
  `tarifa_por_hora` decimal(8,2) NOT NULL,
  `subtotal_usd` decimal(10,2) NOT NULL,
  `iva_porcentaje` decimal(4,2) NOT NULL DEFAULT 16.00,
  `iva_monto` decimal(10,2) NOT NULL,
  `islr_porcentaje` decimal(4,2) NOT NULL DEFAULT 3.00,
  `islr_monto` decimal(10,2) NOT NULL,
  `total_usd` decimal(10,2) NOT NULL,
  `tasa_cambio` decimal(8,2) NOT NULL,
  `total_bs` decimal(12,2) NOT NULL,
  `fecha_pago` date NOT NULL,
  `numero_comprobante` varchar(50) DEFAULT NULL,
  `estado` enum('pendiente','pagado','anulado') DEFAULT 'pendiente',
  `metodo_pago` varchar(50) DEFAULT 'Banesco',
  `observaciones` text DEFAULT NULL,
  `procesado_por` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros_horas`
--

CREATE TABLE `registros_horas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `fecha_trabajo` date NOT NULL,
  `actividades` text NOT NULL,
  `horas_normales` decimal(3,1) NOT NULL DEFAULT 0.0,
  `horas_extra` decimal(3,1) NOT NULL DEFAULT 0.0,
  `estado` enum('borrador','pendiente_validacion','validado','rechazado','modificacion_solicitada') DEFAULT 'borrador',
  `comentarios_validador` text DEFAULT NULL,
  `fecha_envio_validacion` timestamp NULL DEFAULT NULL,
  `fecha_validacion` timestamp NULL DEFAULT NULL,
  `validador_id` int(11) DEFAULT NULL,
  `quincena` varchar(20) NOT NULL,
  `periodo_pago` varchar(20) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasas_bcv`
--

CREATE TABLE `tasas_bcv` (
  `id` int(11) NOT NULL,
  `tasa` decimal(10,2) NOT NULL,
  `fecha_registro` date NOT NULL,
  `origen` varchar(100) DEFAULT 'API',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `codigo_usuario` varchar(10) NOT NULL,
  `tipo_usuario` enum('consultor','validador','admin') NOT NULL,
  `primer_nombre` varchar(50) NOT NULL,
  `segundo_nombre` varchar(50) DEFAULT NULL,
  `primer_apellido` varchar(50) NOT NULL,
  `segundo_apellido` varchar(50) DEFAULT NULL,
  `cedula` varchar(15) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nombre_usuario` varchar(50) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `tarifa_por_hora` decimal(8,2) DEFAULT NULL,
  `nivel_desarrollo` enum('junior','semi-senior','senior') DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `creado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `codigo_usuario`, `tipo_usuario`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `cedula`, `fecha_nacimiento`, `telefono`, `email`, `nombre_usuario`, `password_hash`, `tarifa_por_hora`, `nivel_desarrollo`, `estado`, `fecha_creacion`, `fecha_actualizacion`, `creado_por`) VALUES
(1, 'A-00001', 'admin', 'Administrador', NULL, 'Sistema', NULL, '12345678', '1990-01-01', NULL, NULL, 'ASISTEMA', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'activo', '2025-06-28 19:16:33', '2025-06-28 19:37:23', NULL),
(2, 'A-00002', 'admin', 'Nombre', NULL, 'Apellido', NULL, '87654321', '1990-01-01', NULL, NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'activo', '2025-07-02 02:56:47', '2025-07-02 02:56:47', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_empresas`
--

CREATE TABLE `usuario_empresas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `fecha_asignacion` date NOT NULL,
  `fecha_desasignacion` date DEFAULT NULL,
  `estado` enum('activa','inactiva') DEFAULT 'activa',
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usuario` (`usuario_id`),
  ADD KEY `idx_accion` (`accion`),
  ADD KEY `idx_tabla` (`tabla_afectada`),
  ADD KEY `idx_fecha` (`fecha_accion`),
  ADD KEY `idx_usuario_fecha` (`usuario_id`,`fecha_accion`),
  ADD KEY `idx_tabla_fecha` (`tabla_afectada`,`fecha_accion`);

--
-- Indices de la tabla `configuraciones`
--
ALTER TABLE `configuraciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`),
  ADD KEY `idx_clave` (`clave`),
  ADD KEY `idx_categoria` (`categoria`),
  ADD KEY `idx_tipo` (`tipo`),
  ADD KEY `idx_fecha_actualizacion` (`fecha_actualizacion`),
  ADD KEY `actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rif` (`rif`),
  ADD KEY `idx_nombre` (`nombre`),
  ADD KEY `idx_validador` (`validador_id`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_rif` (`rif`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_telefono` (`telefono`),
  ADD KEY `idx_fecha_creacion` (`fecha_creacion`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_factura` (`numero_factura`),
  ADD KEY `idx_numero_factura` (`numero_factura`),
  ADD KEY `idx_usuario` (`usuario_id`),
  ADD KEY `idx_pago` (`pago_id`),
  ADD KEY `idx_fecha_emision` (`fecha_emision`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_total_bs` (`total_bs`),
  ADD KEY `idx_usuario_fecha` (`usuario_id`,`fecha_emision`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usuario` (`usuario_id`),
  ADD KEY `idx_tipo` (`tipo`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_fecha_programada` (`fecha_programada`),
  ADD KEY `idx_fecha_enviada` (`fecha_enviada`),
  ADD KEY `idx_usuario_estado` (`usuario_id`,`estado`),
  ADD KEY `idx_tipo_estado` (`tipo`,`estado`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_pago` (`codigo_pago`),
  ADD KEY `idx_usuario` (`usuario_id`),
  ADD KEY `idx_codigo_pago` (`codigo_pago`),
  ADD KEY `idx_periodo` (`periodo_inicio`,`periodo_fin`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_fecha_pago` (`fecha_pago`),
  ADD KEY `idx_procesado_por` (`procesado_por`),
  ADD KEY `idx_usuario_periodo` (`usuario_id`,`periodo_inicio`,`periodo_fin`),
  ADD KEY `idx_estado_fecha` (`estado`,`fecha_pago`),
  ADD KEY `idx_total_bs` (`total_bs`);

--
-- Indices de la tabla `registros_horas`
--
ALTER TABLE `registros_horas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_fecha_usuario_empresa` (`usuario_id`,`empresa_id`,`fecha_trabajo`),
  ADD KEY `idx_usuario` (`usuario_id`),
  ADD KEY `idx_empresa` (`empresa_id`),
  ADD KEY `idx_fecha_trabajo` (`fecha_trabajo`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_quincena` (`quincena`),
  ADD KEY `idx_periodo_pago` (`periodo_pago`),
  ADD KEY `idx_validador` (`validador_id`),
  ADD KEY `idx_fecha_envio` (`fecha_envio_validacion`),
  ADD KEY `idx_fecha_validacion` (`fecha_validacion`),
  ADD KEY `idx_usuario_periodo` (`usuario_id`,`periodo_pago`),
  ADD KEY `idx_empresa_periodo` (`empresa_id`,`periodo_pago`),
  ADD KEY `idx_validador_estado` (`validador_id`,`estado`);

--
-- Indices de la tabla `tasas_bcv`
--
ALTER TABLE `tasas_bcv`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_fecha_registro` (`fecha_registro`),
  ADD KEY `idx_fecha_registro` (`fecha_registro`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_usuario` (`codigo_usuario`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD KEY `idx_codigo_usuario` (`codigo_usuario`),
  ADD KEY `idx_tipo_usuario` (`tipo_usuario`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_cedula` (`cedula`),
  ADD KEY `idx_telefono` (`telefono`),
  ADD KEY `idx_fecha_creacion` (`fecha_creacion`),
  ADD KEY `creado_por` (`creado_por`);

--
-- Indices de la tabla `usuario_empresas`
--
ALTER TABLE `usuario_empresas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_asignacion_activa` (`usuario_id`,`empresa_id`,`estado`),
  ADD KEY `idx_usuario` (`usuario_id`),
  ADD KEY `idx_empresa` (`empresa_id`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_fecha_asignacion` (`fecha_asignacion`),
  ADD KEY `idx_fecha_desasignacion` (`fecha_desasignacion`),
  ADD KEY `idx_usuario_empresa_activa` (`usuario_id`,`empresa_id`,`estado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `configuraciones`
--
ALTER TABLE `configuraciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registros_horas`
--
ALTER TABLE `registros_horas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tasas_bcv`
--
ALTER TABLE `tasas_bcv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario_empresas`
--
ALTER TABLE `usuario_empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `configuraciones`
--
ALTER TABLE `configuraciones`
  ADD CONSTRAINT `configuraciones_ibfk_1` FOREIGN KEY (`actualizado_por`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD CONSTRAINT `empresas_ibfk_1` FOREIGN KEY (`validador_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `facturas_ibfk_2` FOREIGN KEY (`pago_id`) REFERENCES `pagos` (`id`);

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`procesado_por`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `registros_horas`
--
ALTER TABLE `registros_horas`
  ADD CONSTRAINT `registros_horas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `registros_horas_ibfk_2` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`),
  ADD CONSTRAINT `registros_horas_ibfk_3` FOREIGN KEY (`validador_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `usuario_empresas`
--
ALTER TABLE `usuario_empresas`
  ADD CONSTRAINT `usuario_empresas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `usuario_empresas_ibfk_2` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
