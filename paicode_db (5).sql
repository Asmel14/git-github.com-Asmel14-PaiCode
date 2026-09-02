-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-08-2026 a las 14:22:05
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
-- Base de datos: `paicode_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anios_escolares`
--

CREATE TABLE `anios_escolares` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `anios_escolares`
--

INSERT INTO `anios_escolares` (`id`, `nombre`, `fecha_inicio`, `fecha_fin`) VALUES
(1, '2026-2027', '2026-08-25', '2027-06-25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaciones_laborales`
--

CREATE TABLE `asignaciones_laborales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `personal_id` bigint(20) UNSIGNED NOT NULL,
  `anio_escolar_id` bigint(20) UNSIGNED NOT NULL,
  `departamento_id` bigint(20) UNSIGNED NOT NULL,
  `cargo_id` bigint(20) UNSIGNED NOT NULL,
  `condicion_laboral_id` bigint(20) UNSIGNED NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `asignaciones_laborales`
--

INSERT INTO `asignaciones_laborales` (`id`, `personal_id`, `anio_escolar_id`, `departamento_id`, `cargo_id`, `condicion_laboral_id`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 1, 2, 1, '2026-08-08 19:59:42', '2026-08-08 19:59:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `modulo` varchar(100) NOT NULL,
  `accion` varchar(100) NOT NULL,
  `tabla` varchar(100) DEFAULT NULL,
  `registro_id` bigint(20) UNSIGNED DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`id`, `usuario_id`, `modulo`, `accion`, `tabla`, `registro_id`, `descripcion`, `ip`, `created_at`) VALUES
(1, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:29:33'),
(2, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:29:34'),
(3, 1, 'niveles', 'CONSULTA', 'niveles', NULL, 'limit=5000; offset=0; registros=3', '::1', '2026-08-15 19:29:34'),
(4, 1, 'grados', 'CONSULTA', 'grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:29:34'),
(5, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:29:34'),
(6, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:29:34'),
(7, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:29:34'),
(8, 1, 'tandas', 'CONSULTA', 'tandas', NULL, 'limit=5000; offset=0; registros=2', '::1', '2026-08-15 19:29:34'),
(9, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:29:34'),
(10, 1, 'registros_civiles', 'CONSULTA', 'registros_civiles', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:29:34'),
(11, 1, 'direcciones_estudiantes', 'CONSULTA', 'direcciones_estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:29:34'),
(12, 1, 'estudiante_vacunas', 'CONSULTA', 'estudiante_vacunas', NULL, 'limit=5000; offset=0; registros=90', '::1', '2026-08-15 19:29:34'),
(13, 1, 'vacunas', 'CONSULTA', 'vacunas', NULL, 'limit=5000; offset=0; registros=18', '::1', '2026-08-15 19:29:34'),
(14, 1, 'estudiante_discapacidades', 'CONSULTA', 'estudiante_discapacidades', NULL, 'limit=5000; offset=0; registros=12', '::1', '2026-08-15 19:29:34'),
(15, 1, 'discapacidades', 'CONSULTA', 'discapacidades', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:29:34'),
(16, 1, 'estudiante_familiares', 'CONSULTA', 'estudiante_familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-15 19:29:34'),
(17, 1, 'familiares', 'CONSULTA', 'familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-15 19:29:34'),
(18, 1, 'inscripcion_requisitos', 'CONSULTA', 'inscripcion_requisitos', NULL, 'limit=5000; offset=0; registros=40', '::1', '2026-08-15 19:29:34'),
(19, 1, 'requisitos_inscripcion', 'CONSULTA', 'requisitos_inscripcion', NULL, 'limit=5000; offset=0; registros=8', '::1', '2026-08-15 19:29:34'),
(20, 1, 'tarifarios', 'CONSULTA', 'tarifarios', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:29:34'),
(21, 1, 'tarifas_grados', 'CONSULTA', 'tarifas_grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:29:34'),
(22, 1, 'historial_academico', 'CONSULTA', 'historial_academico', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:29:35'),
(23, 1, 'inscripciones', 'ELIMINAR', 'inscripciones', 2, 'criteria={\"id\":2}; afectados=1', '::1', '2026-08-15 19:29:52'),
(24, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-15 19:29:52'),
(25, 1, 'grados', 'CONSULTA', 'grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:29:52'),
(26, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:29:52'),
(27, 1, 'niveles', 'CONSULTA', 'niveles', NULL, 'limit=5000; offset=0; registros=3', '::1', '2026-08-15 19:29:52'),
(28, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:29:53'),
(29, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:29:53'),
(30, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:29:53'),
(31, 1, 'tandas', 'CONSULTA', 'tandas', NULL, 'limit=5000; offset=0; registros=2', '::1', '2026-08-15 19:29:53'),
(32, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:29:53'),
(33, 1, 'registros_civiles', 'CONSULTA', 'registros_civiles', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:29:53'),
(34, 1, 'direcciones_estudiantes', 'CONSULTA', 'direcciones_estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:29:53'),
(35, 1, 'estudiante_vacunas', 'CONSULTA', 'estudiante_vacunas', NULL, 'limit=5000; offset=0; registros=90', '::1', '2026-08-15 19:29:53'),
(36, 1, 'vacunas', 'CONSULTA', 'vacunas', NULL, 'limit=5000; offset=0; registros=18', '::1', '2026-08-15 19:29:53'),
(37, 1, 'estudiante_discapacidades', 'CONSULTA', 'estudiante_discapacidades', NULL, 'limit=5000; offset=0; registros=12', '::1', '2026-08-15 19:29:53'),
(38, 1, 'discapacidades', 'CONSULTA', 'discapacidades', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:29:53'),
(39, 1, 'estudiante_familiares', 'CONSULTA', 'estudiante_familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-15 19:29:53'),
(40, 1, 'familiares', 'CONSULTA', 'familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-15 19:29:53'),
(41, 1, 'inscripcion_requisitos', 'CONSULTA', 'inscripcion_requisitos', NULL, 'limit=5000; offset=0; registros=32', '::1', '2026-08-15 19:29:53'),
(42, 1, 'requisitos_inscripcion', 'CONSULTA', 'requisitos_inscripcion', NULL, 'limit=5000; offset=0; registros=8', '::1', '2026-08-15 19:29:53'),
(43, 1, 'tarifarios', 'CONSULTA', 'tarifarios', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:29:53'),
(44, 1, 'tarifas_grados', 'CONSULTA', 'tarifas_grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:29:53'),
(45, 1, 'historial_academico', 'CONSULTA', 'historial_academico', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:29:53'),
(46, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=3000; offset=0; registros=5', '::1', '2026-08-15 19:32:04'),
(47, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=3000; offset=0; registros=4', '::1', '2026-08-15 19:32:04'),
(48, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-15 19:32:04'),
(49, 1, 'metodos_pago', 'CONSULTA', 'metodos_pago', NULL, 'limit=200; offset=0; registros=5', '::1', '2026-08-15 19:32:04'),
(50, 1, 'parametros_financieros', 'CONSULTA', 'parametros_financieros', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-15 19:32:04'),
(51, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=3000; offset=0; registros=15', '::1', '2026-08-15 19:32:04'),
(52, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=3000; offset=0; registros=4', '::1', '2026-08-15 19:32:04'),
(53, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=50; offset=0; registros=1', '::1', '2026-08-15 19:32:04'),
(54, 1, 'pagos', 'CREAR', 'pagos', 7, 'campos=[\"estudiante_id\",\"numero_recibo\",\"fecha_pago\",\"metodo_pago_id\",\"referencia\",\"monto_total\",\"estado\",\"observaciones\"]', '::1', '2026-08-15 19:32:14'),
(55, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=3000; offset=0; registros=5', '::1', '2026-08-15 19:32:14'),
(56, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=1000; offset=0; registros=15', '::1', '2026-08-15 19:33:10'),
(57, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=1000; offset=0; registros=1', '::1', '2026-08-15 19:33:10'),
(58, 1, 'niveles', 'CONSULTA', 'niveles', NULL, 'limit=1000; offset=0; registros=3', '::1', '2026-08-15 19:33:10'),
(59, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=1000; offset=0; registros=1', '::1', '2026-08-15 19:33:10'),
(60, 1, 'grados', 'CONSULTA', 'grados', NULL, 'limit=1000; offset=0; registros=15', '::1', '2026-08-15 19:33:10'),
(61, 1, 'tandas', 'CONSULTA', 'tandas', NULL, 'limit=1000; offset=0; registros=2', '::1', '2026-08-15 19:33:10'),
(62, 1, 'tarifarios', 'CONSULTA', 'tarifarios', NULL, 'limit=1000; offset=0; registros=1', '::1', '2026-08-15 19:33:10'),
(63, 1, 'tarifas_grados', 'CONSULTA', 'tarifas_grados', NULL, 'limit=1000; offset=0; registros=15', '::1', '2026-08-15 19:33:11'),
(64, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=1000; offset=0; registros=1', '::1', '2026-08-15 19:33:11'),
(65, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=3000; offset=0; registros=5', '::1', '2026-08-15 19:33:26'),
(66, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=3000; offset=0; registros=4', '::1', '2026-08-15 19:33:26'),
(67, 1, 'parametros_financieros', 'CONSULTA', 'parametros_financieros', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-15 19:33:26'),
(68, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=3000; offset=0; registros=5', '::1', '2026-08-15 19:33:27'),
(69, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-15 19:33:27'),
(70, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=3000; offset=0; registros=15', '::1', '2026-08-15 19:33:27'),
(71, 1, 'periodos_cobro', 'CONSULTA', 'periodos_cobro', NULL, 'limit=500; offset=0; registros=0', '::1', '2026-08-15 19:33:27'),
(72, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:33:56'),
(73, 1, 'datos_laborales', 'CONSULTA', 'datos_laborales', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:33:56'),
(74, 1, 'direcciones_personal', 'CONSULTA', 'direcciones_personal', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:33:57'),
(75, 1, 'departamentos', 'CONSULTA', 'departamentos', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:33:57'),
(76, 1, 'asignaciones_laborales', 'CONSULTA', 'asignaciones_laborales', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:33:57'),
(77, 1, 'personal', 'CONSULTA', 'personal', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:33:57'),
(78, 1, 'cargos', 'CONSULTA', 'cargos', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:33:57'),
(79, 1, 'condiciones_laborales', 'CONSULTA', 'condiciones_laborales', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-15 19:33:57'),
(80, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:34:43'),
(81, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:34:43'),
(82, 1, 'niveles', 'CONSULTA', 'niveles', NULL, 'limit=5000; offset=0; registros=3', '::1', '2026-08-15 19:34:44'),
(83, 1, 'tandas', 'CONSULTA', 'tandas', NULL, 'limit=5000; offset=0; registros=2', '::1', '2026-08-15 19:34:44'),
(84, 1, 'grados', 'CONSULTA', 'grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:34:44'),
(85, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:34:44'),
(86, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-15 19:34:44'),
(87, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:34:44'),
(88, 1, 'historial_academico', 'CONSULTA', 'historial_academico', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:34:44'),
(89, 1, 'historial_academico', 'CONSULTA', 'historial_academico', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:35:06'),
(90, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:40:34'),
(91, 1, 'personal', 'CONSULTA', 'personal', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:40:35'),
(92, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:40:35'),
(93, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-15 19:40:35'),
(94, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:40:35'),
(95, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-15 19:40:35'),
(96, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:40:35'),
(97, 1, 'periodos_cobro', 'CONSULTA', 'periodos_cobro', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-15 19:40:35'),
(98, 1, 'parametros_financieros', 'CONSULTA', 'parametros_financieros', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:40:35'),
(99, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:41:19'),
(100, 1, 'personal', 'CONSULTA', 'personal', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:41:19'),
(101, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:41:19'),
(102, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:41:19'),
(103, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-15 19:41:19'),
(104, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-15 19:41:19'),
(105, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:41:19'),
(106, 1, 'periodos_cobro', 'CONSULTA', 'periodos_cobro', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-15 19:41:19'),
(107, 1, 'parametros_financieros', 'CONSULTA', 'parametros_financieros', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:41:19'),
(108, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:42:29'),
(109, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-15 19:42:29'),
(110, 1, 'personal', 'CONSULTA', 'personal', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:42:29'),
(111, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:42:29'),
(112, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:42:29'),
(113, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-15 19:42:29'),
(114, 1, 'metodos_pago', 'CONSULTA', 'metodos_pago', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:42:29'),
(115, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:42:29'),
(116, 1, 'periodos_cobro', 'CONSULTA', 'periodos_cobro', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-15 19:42:29'),
(117, 1, 'parametros_financieros', 'CONSULTA', 'parametros_financieros', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:42:29'),
(118, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:42:30'),
(119, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-15 19:42:30'),
(120, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:42:30'),
(121, 1, 'metodos_pago', 'CONSULTA', 'metodos_pago', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:42:30'),
(122, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:42:30'),
(123, 1, 'personal', 'CONSULTA', 'personal', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:42:30'),
(124, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-15 19:42:30'),
(125, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:42:30'),
(126, 1, 'periodos_cobro', 'CONSULTA', 'periodos_cobro', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-15 19:42:30'),
(127, 1, 'parametros_financieros', 'CONSULTA', 'parametros_financieros', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:42:30'),
(128, 1, 'metodos_pago', 'CONSULTA', 'metodos_pago', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:44:05'),
(129, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:44:05'),
(130, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:44:05'),
(131, 1, 'personal', 'CONSULTA', 'personal', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:44:05'),
(132, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:44:05'),
(133, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-15 19:44:05'),
(134, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-15 19:44:05'),
(135, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:44:05'),
(136, 1, 'periodos_cobro', 'CONSULTA', 'periodos_cobro', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-15 19:44:05'),
(137, 1, 'parametros_financieros', 'CONSULTA', 'parametros_financieros', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:44:05'),
(138, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:44:07'),
(139, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:44:07'),
(140, 1, 'personal', 'CONSULTA', 'personal', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:44:07'),
(141, 1, 'metodos_pago', 'CONSULTA', 'metodos_pago', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:44:07'),
(142, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-15 19:44:07'),
(143, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:44:07'),
(144, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-15 19:44:07'),
(145, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:44:07'),
(146, 1, 'periodos_cobro', 'CONSULTA', 'periodos_cobro', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-15 19:44:07'),
(147, 1, 'parametros_financieros', 'CONSULTA', 'parametros_financieros', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:44:07'),
(148, 1, 'niveles', 'CONSULTA', 'niveles', NULL, 'limit=500; offset=0; registros=3', '::1', '2026-08-15 19:44:17'),
(149, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=3000; offset=0; registros=4', '::1', '2026-08-15 19:44:19'),
(150, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-15 19:44:19'),
(151, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=3000; offset=0; registros=15', '::1', '2026-08-15 19:44:19'),
(152, 1, 'metodos_pago', 'CONSULTA', 'metodos_pago', NULL, 'limit=200; offset=0; registros=5', '::1', '2026-08-15 19:44:19'),
(153, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=3000; offset=0; registros=5', '::1', '2026-08-15 19:44:19'),
(154, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=3000; offset=0; registros=5', '::1', '2026-08-15 19:44:20'),
(155, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=50; offset=0; registros=1', '::1', '2026-08-15 19:44:20'),
(156, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=500; offset=0; registros=1', '::1', '2026-08-15 19:44:21'),
(157, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=3000; offset=0; registros=4', '::1', '2026-08-15 19:44:25'),
(158, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=3000; offset=0; registros=15', '::1', '2026-08-15 19:44:25'),
(159, 1, 'metodos_pago', 'CONSULTA', 'metodos_pago', NULL, 'limit=200; offset=0; registros=5', '::1', '2026-08-15 19:44:26'),
(160, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=3000; offset=0; registros=5', '::1', '2026-08-15 19:44:26'),
(161, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=3000; offset=0; registros=5', '::1', '2026-08-15 19:44:26'),
(162, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-15 19:44:26'),
(163, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=50; offset=0; registros=1', '::1', '2026-08-15 19:44:26'),
(164, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=3000; offset=0; registros=4', '::1', '2026-08-15 19:44:29'),
(165, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=3000; offset=0; registros=5', '::1', '2026-08-15 19:44:29'),
(166, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=3000; offset=0; registros=15', '::1', '2026-08-15 19:44:29'),
(167, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-15 19:44:29'),
(168, 1, 'periodos_cobro', 'CONSULTA', 'periodos_cobro', NULL, 'limit=500; offset=0; registros=0', '::1', '2026-08-15 19:44:29'),
(169, 1, 'parametros_financieros', 'CONSULTA', 'parametros_financieros', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-15 19:44:29'),
(170, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=3000; offset=0; registros=5', '::1', '2026-08-15 19:44:29'),
(171, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=3000; offset=0; registros=5', '::1', '2026-08-15 19:44:39'),
(172, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=3000; offset=0; registros=15', '::1', '2026-08-15 19:44:39'),
(173, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-15 19:44:39'),
(174, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=3000; offset=0; registros=4', '::1', '2026-08-15 19:44:39'),
(175, 1, 'periodos_cobro', 'CONSULTA', 'periodos_cobro', NULL, 'limit=500; offset=0; registros=0', '::1', '2026-08-15 19:44:39'),
(176, 1, 'parametros_financieros', 'CONSULTA', 'parametros_financieros', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-15 19:44:39'),
(177, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=3000; offset=0; registros=5', '::1', '2026-08-15 19:44:39'),
(178, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-15 19:59:26'),
(179, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:59:26'),
(180, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:59:26'),
(181, 1, 'niveles', 'CONSULTA', 'niveles', NULL, 'limit=5000; offset=0; registros=3', '::1', '2026-08-15 19:59:26'),
(182, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:59:26'),
(183, 1, 'grados', 'CONSULTA', 'grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:59:26'),
(184, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:59:26'),
(185, 1, 'tandas', 'CONSULTA', 'tandas', NULL, 'limit=5000; offset=0; registros=2', '::1', '2026-08-15 19:59:27'),
(186, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:59:27'),
(187, 1, 'registros_civiles', 'CONSULTA', 'registros_civiles', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:59:27'),
(188, 1, 'direcciones_estudiantes', 'CONSULTA', 'direcciones_estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:59:27'),
(189, 1, 'estudiante_vacunas', 'CONSULTA', 'estudiante_vacunas', NULL, 'limit=5000; offset=0; registros=90', '::1', '2026-08-15 19:59:27'),
(190, 1, 'vacunas', 'CONSULTA', 'vacunas', NULL, 'limit=5000; offset=0; registros=18', '::1', '2026-08-15 19:59:27'),
(191, 1, 'estudiante_discapacidades', 'CONSULTA', 'estudiante_discapacidades', NULL, 'limit=5000; offset=0; registros=12', '::1', '2026-08-15 19:59:27'),
(192, 1, 'discapacidades', 'CONSULTA', 'discapacidades', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:59:27'),
(193, 1, 'estudiante_familiares', 'CONSULTA', 'estudiante_familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-15 19:59:27'),
(194, 1, 'familiares', 'CONSULTA', 'familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-15 19:59:27'),
(195, 1, 'inscripcion_requisitos', 'CONSULTA', 'inscripcion_requisitos', NULL, 'limit=5000; offset=0; registros=32', '::1', '2026-08-15 19:59:27'),
(196, 1, 'requisitos_inscripcion', 'CONSULTA', 'requisitos_inscripcion', NULL, 'limit=5000; offset=0; registros=8', '::1', '2026-08-15 19:59:27'),
(197, 1, 'tarifarios', 'CONSULTA', 'tarifarios', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:59:27'),
(198, 1, 'tarifas_grados', 'CONSULTA', 'tarifas_grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:59:27'),
(199, 1, 'historial_academico', 'CONSULTA', 'historial_academico', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:59:27'),
(200, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=500; offset=0; registros=1', '::1', '2026-08-15 19:59:28'),
(201, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=1000; offset=0; registros=15', '::1', '2026-08-15 19:59:35'),
(202, 1, 'niveles', 'CONSULTA', 'niveles', NULL, 'limit=1000; offset=0; registros=3', '::1', '2026-08-15 19:59:35'),
(203, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=1000; offset=0; registros=1', '::1', '2026-08-15 19:59:35'),
(204, 1, 'grados', 'CONSULTA', 'grados', NULL, 'limit=1000; offset=0; registros=15', '::1', '2026-08-15 19:59:35'),
(205, 1, 'tandas', 'CONSULTA', 'tandas', NULL, 'limit=1000; offset=0; registros=2', '::1', '2026-08-15 19:59:35'),
(206, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=1000; offset=0; registros=1', '::1', '2026-08-15 19:59:35'),
(207, 1, 'tarifarios', 'CONSULTA', 'tarifarios', NULL, 'limit=1000; offset=0; registros=1', '::1', '2026-08-15 19:59:35'),
(208, 1, 'tarifas_grados', 'CONSULTA', 'tarifas_grados', NULL, 'limit=1000; offset=0; registros=15', '::1', '2026-08-15 19:59:35'),
(209, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=1000; offset=0; registros=1', '::1', '2026-08-15 19:59:35'),
(210, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:59:39'),
(211, 1, 'grados', 'CONSULTA', 'grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:59:39'),
(212, 1, 'niveles', 'CONSULTA', 'niveles', NULL, 'limit=5000; offset=0; registros=3', '::1', '2026-08-15 19:59:40'),
(213, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:59:40'),
(214, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-15 19:59:40'),
(215, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:59:40'),
(216, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:59:40'),
(217, 1, 'tandas', 'CONSULTA', 'tandas', NULL, 'limit=5000; offset=0; registros=2', '::1', '2026-08-15 19:59:40'),
(218, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:59:40'),
(219, 1, 'registros_civiles', 'CONSULTA', 'registros_civiles', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:59:40'),
(220, 1, 'direcciones_estudiantes', 'CONSULTA', 'direcciones_estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:59:40'),
(221, 1, 'estudiante_vacunas', 'CONSULTA', 'estudiante_vacunas', NULL, 'limit=5000; offset=0; registros=90', '::1', '2026-08-15 19:59:40'),
(222, 1, 'vacunas', 'CONSULTA', 'vacunas', NULL, 'limit=5000; offset=0; registros=18', '::1', '2026-08-15 19:59:40'),
(223, 1, 'estudiante_discapacidades', 'CONSULTA', 'estudiante_discapacidades', NULL, 'limit=5000; offset=0; registros=12', '::1', '2026-08-15 19:59:40'),
(224, 1, 'discapacidades', 'CONSULTA', 'discapacidades', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-15 19:59:40'),
(225, 1, 'estudiante_familiares', 'CONSULTA', 'estudiante_familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-15 19:59:40'),
(226, 1, 'familiares', 'CONSULTA', 'familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-15 19:59:40'),
(227, 1, 'inscripcion_requisitos', 'CONSULTA', 'inscripcion_requisitos', NULL, 'limit=5000; offset=0; registros=32', '::1', '2026-08-15 19:59:40'),
(228, 1, 'requisitos_inscripcion', 'CONSULTA', 'requisitos_inscripcion', NULL, 'limit=5000; offset=0; registros=8', '::1', '2026-08-15 19:59:40'),
(229, 1, 'tarifarios', 'CONSULTA', 'tarifarios', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:59:40'),
(230, 1, 'tarifas_grados', 'CONSULTA', 'tarifas_grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-15 19:59:40'),
(231, 1, 'historial_academico', 'CONSULTA', 'historial_academico', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-15 19:59:40'),
(232, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-16 12:46:26'),
(233, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:46:27'),
(234, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-16 12:46:27'),
(235, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-16 12:46:27'),
(236, 1, 'grados', 'CONSULTA', 'grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-16 12:46:27'),
(237, 1, 'niveles', 'CONSULTA', 'niveles', NULL, 'limit=5000; offset=0; registros=3', '::1', '2026-08-16 12:46:27'),
(238, 1, 'tandas', 'CONSULTA', 'tandas', NULL, 'limit=5000; offset=0; registros=2', '::1', '2026-08-16 12:46:27'),
(239, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:46:27'),
(240, 1, 'registros_civiles', 'CONSULTA', 'registros_civiles', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-16 12:46:27'),
(241, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:46:27'),
(242, 1, 'estudiante_vacunas', 'CONSULTA', 'estudiante_vacunas', NULL, 'limit=5000; offset=0; registros=90', '::1', '2026-08-16 12:46:27'),
(243, 1, 'direcciones_estudiantes', 'CONSULTA', 'direcciones_estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-16 12:46:27'),
(244, 1, 'vacunas', 'CONSULTA', 'vacunas', NULL, 'limit=5000; offset=0; registros=18', '::1', '2026-08-16 12:46:27'),
(245, 1, 'estudiante_discapacidades', 'CONSULTA', 'estudiante_discapacidades', NULL, 'limit=5000; offset=0; registros=12', '::1', '2026-08-16 12:46:27'),
(246, 1, 'discapacidades', 'CONSULTA', 'discapacidades', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-16 12:46:27'),
(247, 1, 'estudiante_familiares', 'CONSULTA', 'estudiante_familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-16 12:46:28'),
(248, 1, 'inscripcion_requisitos', 'CONSULTA', 'inscripcion_requisitos', NULL, 'limit=5000; offset=0; registros=32', '::1', '2026-08-16 12:46:28'),
(249, 1, 'requisitos_inscripcion', 'CONSULTA', 'requisitos_inscripcion', NULL, 'limit=5000; offset=0; registros=8', '::1', '2026-08-16 12:46:28'),
(250, 1, 'familiares', 'CONSULTA', 'familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-16 12:46:28'),
(251, 1, 'tarifarios', 'CONSULTA', 'tarifarios', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:46:28'),
(252, 1, 'tarifas_grados', 'CONSULTA', 'tarifas_grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-16 12:46:28'),
(253, 1, 'historial_academico', 'CONSULTA', 'historial_academico', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:46:28'),
(254, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-16 12:46:29'),
(255, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-16 12:46:29'),
(256, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-16 12:46:29'),
(257, 1, 'metodos_pago', 'CONSULTA', 'metodos_pago', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-16 12:46:29'),
(258, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-16 12:46:29'),
(259, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:46:29'),
(260, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-16 12:46:30'),
(261, 1, 'personal', 'CONSULTA', 'personal', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:46:30'),
(262, 1, 'periodos_cobro', 'CONSULTA', 'periodos_cobro', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-16 12:46:30'),
(263, 1, 'parametros_financieros', 'CONSULTA', 'parametros_financieros', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:46:30'),
(264, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-16 12:48:16'),
(265, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-16 12:48:16'),
(266, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-16 12:48:16'),
(267, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-16 12:48:16'),
(268, 1, 'metodos_pago', 'CONSULTA', 'metodos_pago', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-16 12:48:17'),
(269, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-16 12:48:17'),
(270, 1, 'personal', 'CONSULTA', 'personal', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:48:17'),
(271, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:48:17'),
(272, 1, 'periodos_cobro', 'CONSULTA', 'periodos_cobro', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-16 12:48:17'),
(273, 1, 'parametros_financieros', 'CONSULTA', 'parametros_financieros', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:48:17'),
(274, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=1000; offset=0; registros=15', '::1', '2026-08-16 12:48:19'),
(275, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=1000; offset=0; registros=1', '::1', '2026-08-16 12:48:19'),
(276, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=1000; offset=0; registros=1', '::1', '2026-08-16 12:48:19'),
(277, 1, 'niveles', 'CONSULTA', 'niveles', NULL, 'limit=1000; offset=0; registros=3', '::1', '2026-08-16 12:48:19'),
(278, 1, 'tandas', 'CONSULTA', 'tandas', NULL, 'limit=1000; offset=0; registros=2', '::1', '2026-08-16 12:48:19'),
(279, 1, 'grados', 'CONSULTA', 'grados', NULL, 'limit=1000; offset=0; registros=15', '::1', '2026-08-16 12:48:19'),
(280, 1, 'tarifarios', 'CONSULTA', 'tarifarios', NULL, 'limit=1000; offset=0; registros=1', '::1', '2026-08-16 12:48:19'),
(281, 1, 'tarifas_grados', 'CONSULTA', 'tarifas_grados', NULL, 'limit=1000; offset=0; registros=15', '::1', '2026-08-16 12:48:19'),
(282, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=1000; offset=0; registros=1', '::1', '2026-08-16 12:48:19'),
(283, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-16 12:48:22'),
(284, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-16 12:48:22'),
(285, 1, 'niveles', 'CONSULTA', 'niveles', NULL, 'limit=5000; offset=0; registros=3', '::1', '2026-08-16 12:48:22'),
(286, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-16 12:48:22'),
(287, 1, 'grados', 'CONSULTA', 'grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-16 12:48:22'),
(288, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:48:22'),
(289, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:48:22'),
(290, 1, 'tandas', 'CONSULTA', 'tandas', NULL, 'limit=5000; offset=0; registros=2', '::1', '2026-08-16 12:48:22'),
(291, 1, 'tarifarios', 'CONSULTA', 'tarifarios', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:48:22'),
(292, 1, 'tarifas_grados', 'CONSULTA', 'tarifas_grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-16 12:48:23'),
(293, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:48:26'),
(294, 1, 'condiciones_laborales', 'CONSULTA', 'condiciones_laborales', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-16 12:48:26'),
(295, 1, 'cargos', 'CONSULTA', 'cargos', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:48:27'),
(296, 1, 'departamentos', 'CONSULTA', 'departamentos', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-16 12:48:27'),
(297, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:48:34'),
(298, 1, 'niveles', 'CONSULTA', 'niveles', NULL, 'limit=5000; offset=0; registros=3', '::1', '2026-08-16 12:48:34'),
(299, 1, 'tandas', 'CONSULTA', 'tandas', NULL, 'limit=5000; offset=0; registros=2', '::1', '2026-08-16 12:48:34'),
(300, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:48:34'),
(301, 1, 'grados', 'CONSULTA', 'grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-16 12:48:34'),
(302, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-16 12:48:34'),
(303, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-16 12:48:34'),
(304, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-16 12:48:34'),
(305, 1, 'historial_academico', 'CONSULTA', 'historial_academico', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:48:34'),
(306, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-16 12:58:26'),
(307, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-16 12:58:26'),
(308, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-16 12:58:26'),
(309, 1, 'niveles', 'CONSULTA', 'niveles', NULL, 'limit=5000; offset=0; registros=3', '::1', '2026-08-16 12:58:26'),
(310, 1, 'grados', 'CONSULTA', 'grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-16 12:58:26'),
(311, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:58:26'),
(312, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:58:26'),
(313, 1, 'tandas', 'CONSULTA', 'tandas', NULL, 'limit=5000; offset=0; registros=2', '::1', '2026-08-16 12:58:26'),
(314, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:58:26'),
(315, 1, 'registros_civiles', 'CONSULTA', 'registros_civiles', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-16 12:58:26'),
(316, 1, 'direcciones_estudiantes', 'CONSULTA', 'direcciones_estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-16 12:58:26'),
(317, 1, 'estudiante_vacunas', 'CONSULTA', 'estudiante_vacunas', NULL, 'limit=5000; offset=0; registros=90', '::1', '2026-08-16 12:58:26'),
(318, 1, 'vacunas', 'CONSULTA', 'vacunas', NULL, 'limit=5000; offset=0; registros=18', '::1', '2026-08-16 12:58:26'),
(319, 1, 'estudiante_discapacidades', 'CONSULTA', 'estudiante_discapacidades', NULL, 'limit=5000; offset=0; registros=12', '::1', '2026-08-16 12:58:26'),
(320, 1, 'discapacidades', 'CONSULTA', 'discapacidades', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-16 12:58:26'),
(321, 1, 'estudiante_familiares', 'CONSULTA', 'estudiante_familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-16 12:58:26'),
(322, 1, 'familiares', 'CONSULTA', 'familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-16 12:58:26'),
(323, 1, 'inscripcion_requisitos', 'CONSULTA', 'inscripcion_requisitos', NULL, 'limit=5000; offset=0; registros=32', '::1', '2026-08-16 12:58:26'),
(324, 1, 'requisitos_inscripcion', 'CONSULTA', 'requisitos_inscripcion', NULL, 'limit=5000; offset=0; registros=8', '::1', '2026-08-16 12:58:27'),
(325, 1, 'tarifarios', 'CONSULTA', 'tarifarios', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:58:27'),
(326, 1, 'tarifas_grados', 'CONSULTA', 'tarifas_grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-16 12:58:27'),
(327, 1, 'historial_academico', 'CONSULTA', 'historial_academico', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-16 12:58:27'),
(328, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=3000; offset=0; registros=15', '::1', '2026-08-17 12:13:41'),
(329, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=3000; offset=0; registros=4', '::1', '2026-08-17 12:13:41'),
(330, 1, 'metodos_pago', 'CONSULTA', 'metodos_pago', NULL, 'limit=200; offset=0; registros=5', '::1', '2026-08-17 12:13:41'),
(331, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=3000; offset=0; registros=5', '::1', '2026-08-17 12:13:41'),
(332, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=3000; offset=0; registros=5', '::1', '2026-08-17 12:13:41'),
(333, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-17 12:13:41'),
(334, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=50; offset=0; registros=1', '::1', '2026-08-17 12:13:41'),
(335, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=3000; offset=0; registros=5', '::1', '2026-08-17 12:13:53'),
(336, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=3000; offset=0; registros=4', '::1', '2026-08-17 12:13:53'),
(337, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=3000; offset=0; registros=15', '::1', '2026-08-17 12:13:54'),
(338, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-17 12:13:54'),
(339, 1, 'metodos_pago', 'CONSULTA', 'metodos_pago', NULL, 'limit=200; offset=0; registros=5', '::1', '2026-08-17 12:13:54'),
(340, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=3000; offset=0; registros=5', '::1', '2026-08-17 12:13:54'),
(341, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=50; offset=0; registros=1', '::1', '2026-08-17 12:13:54'),
(342, 1, 'parametros_financieros', 'CONSULTA', 'parametros_financieros', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-17 12:13:54'),
(343, 1, 'pagos', 'ELIMINAR', 'pagos', 6, 'criteria={\"id\":6}; afectados=1', '::1', '2026-08-17 12:14:00'),
(344, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=3000; offset=0; registros=4', '::1', '2026-08-17 12:14:00'),
(345, 1, 'pagos', 'ELIMINAR', 'pagos', 5, 'criteria={\"id\":5}; afectados=1', '::1', '2026-08-17 12:14:02'),
(346, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=3000; offset=0; registros=3', '::1', '2026-08-17 12:14:02'),
(347, 1, 'pagos', 'ELIMINAR', 'pagos', 7, 'criteria={\"id\":7}; afectados=1', '::1', '2026-08-17 12:14:14'),
(348, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=3000; offset=0; registros=2', '::1', '2026-08-17 12:14:14'),
(349, 1, 'pagos', 'ELIMINAR', 'pagos', 2, 'criteria={\"id\":2}; afectados=1', '::1', '2026-08-17 12:14:24'),
(350, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=3000; offset=0; registros=1', '::1', '2026-08-17 12:14:24'),
(351, 1, 'pagos', 'ELIMINAR', 'pagos', 1, 'criteria={\"id\":1}; afectados=1', '::1', '2026-08-17 12:14:26'),
(352, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=3000; offset=0; registros=0', '::1', '2026-08-17 12:14:26'),
(353, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-17 12:14:35'),
(354, 1, 'metodos_pago', 'CONSULTA', 'metodos_pago', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:14:35'),
(355, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:14:35'),
(356, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:14:35'),
(357, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-17 12:14:35'),
(358, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-17 12:14:35'),
(359, 1, 'personal', 'CONSULTA', 'personal', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:14:35'),
(360, 1, 'periodos_cobro', 'CONSULTA', 'periodos_cobro', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-17 12:14:36'),
(361, 1, 'parametros_financieros', 'CONSULTA', 'parametros_financieros', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:14:36'),
(362, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:14:36'),
(363, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:14:51'),
(364, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-17 12:14:51'),
(365, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:14:51'),
(366, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:14:51'),
(367, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:14:51'),
(368, 1, 'grados', 'CONSULTA', 'grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:14:51'),
(369, 1, 'niveles', 'CONSULTA', 'niveles', NULL, 'limit=5000; offset=0; registros=3', '::1', '2026-08-17 12:14:51'),
(370, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:14:51'),
(371, 1, 'tandas', 'CONSULTA', 'tandas', NULL, 'limit=5000; offset=0; registros=2', '::1', '2026-08-17 12:14:52'),
(372, 1, 'registros_civiles', 'CONSULTA', 'registros_civiles', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:14:52'),
(373, 1, 'direcciones_estudiantes', 'CONSULTA', 'direcciones_estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:14:52'),
(374, 1, 'vacunas', 'CONSULTA', 'vacunas', NULL, 'limit=5000; offset=0; registros=18', '::1', '2026-08-17 12:14:52'),
(375, 1, 'estudiante_discapacidades', 'CONSULTA', 'estudiante_discapacidades', NULL, 'limit=5000; offset=0; registros=12', '::1', '2026-08-17 12:14:52'),
(376, 1, 'estudiante_vacunas', 'CONSULTA', 'estudiante_vacunas', NULL, 'limit=5000; offset=0; registros=90', '::1', '2026-08-17 12:14:52'),
(377, 1, 'estudiante_familiares', 'CONSULTA', 'estudiante_familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-17 12:14:52'),
(378, 1, 'discapacidades', 'CONSULTA', 'discapacidades', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:14:52'),
(379, 1, 'inscripcion_requisitos', 'CONSULTA', 'inscripcion_requisitos', NULL, 'limit=5000; offset=0; registros=32', '::1', '2026-08-17 12:14:52'),
(380, 1, 'familiares', 'CONSULTA', 'familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-17 12:14:52'),
(381, 1, 'requisitos_inscripcion', 'CONSULTA', 'requisitos_inscripcion', NULL, 'limit=5000; offset=0; registros=8', '::1', '2026-08-17 12:14:52'),
(382, 1, 'tarifarios', 'CONSULTA', 'tarifarios', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:14:52'),
(383, 1, 'tarifas_grados', 'CONSULTA', 'tarifas_grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:14:52'),
(384, 1, 'historial_academico', 'CONSULTA', 'historial_academico', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:14:53'),
(385, 1, 'inscripciones', 'ELIMINAR', 'inscripciones', 5, 'criteria={\"id\":5}; afectados=1', '::1', '2026-08-17 12:15:00');
INSERT INTO `auditoria` (`id`, `usuario_id`, `modulo`, `accion`, `tabla`, `registro_id`, `descripcion`, `ip`, `created_at`) VALUES
(386, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=3', '::1', '2026-08-17 12:15:00'),
(387, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:15:00'),
(388, 1, 'niveles', 'CONSULTA', 'niveles', NULL, 'limit=5000; offset=0; registros=3', '::1', '2026-08-17 12:15:00'),
(389, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:00'),
(390, 1, 'grados', 'CONSULTA', 'grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:15:00'),
(391, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:00'),
(392, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:00'),
(393, 1, 'tandas', 'CONSULTA', 'tandas', NULL, 'limit=5000; offset=0; registros=2', '::1', '2026-08-17 12:15:00'),
(394, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:00'),
(395, 1, 'registros_civiles', 'CONSULTA', 'registros_civiles', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:00'),
(396, 1, 'direcciones_estudiantes', 'CONSULTA', 'direcciones_estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:00'),
(397, 1, 'estudiante_vacunas', 'CONSULTA', 'estudiante_vacunas', NULL, 'limit=5000; offset=0; registros=90', '::1', '2026-08-17 12:15:00'),
(398, 1, 'vacunas', 'CONSULTA', 'vacunas', NULL, 'limit=5000; offset=0; registros=18', '::1', '2026-08-17 12:15:00'),
(399, 1, 'estudiante_discapacidades', 'CONSULTA', 'estudiante_discapacidades', NULL, 'limit=5000; offset=0; registros=12', '::1', '2026-08-17 12:15:00'),
(400, 1, 'discapacidades', 'CONSULTA', 'discapacidades', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:00'),
(401, 1, 'estudiante_familiares', 'CONSULTA', 'estudiante_familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-17 12:15:00'),
(402, 1, 'familiares', 'CONSULTA', 'familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-17 12:15:00'),
(403, 1, 'inscripcion_requisitos', 'CONSULTA', 'inscripcion_requisitos', NULL, 'limit=5000; offset=0; registros=24', '::1', '2026-08-17 12:15:00'),
(404, 1, 'requisitos_inscripcion', 'CONSULTA', 'requisitos_inscripcion', NULL, 'limit=5000; offset=0; registros=8', '::1', '2026-08-17 12:15:00'),
(405, 1, 'tarifarios', 'CONSULTA', 'tarifarios', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:00'),
(406, 1, 'tarifas_grados', 'CONSULTA', 'tarifas_grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:15:00'),
(407, 1, 'historial_academico', 'CONSULTA', 'historial_academico', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:01'),
(408, 1, 'inscripciones', 'ELIMINAR', 'inscripciones', 4, 'criteria={\"id\":4}; afectados=1', '::1', '2026-08-17 12:15:03'),
(409, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=2', '::1', '2026-08-17 12:15:03'),
(410, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:03'),
(411, 1, 'niveles', 'CONSULTA', 'niveles', NULL, 'limit=5000; offset=0; registros=3', '::1', '2026-08-17 12:15:03'),
(412, 1, 'grados', 'CONSULTA', 'grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:15:03'),
(413, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:03'),
(414, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:15:03'),
(415, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:03'),
(416, 1, 'tandas', 'CONSULTA', 'tandas', NULL, 'limit=5000; offset=0; registros=2', '::1', '2026-08-17 12:15:03'),
(417, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:03'),
(418, 1, 'registros_civiles', 'CONSULTA', 'registros_civiles', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:03'),
(419, 1, 'direcciones_estudiantes', 'CONSULTA', 'direcciones_estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:03'),
(420, 1, 'estudiante_vacunas', 'CONSULTA', 'estudiante_vacunas', NULL, 'limit=5000; offset=0; registros=90', '::1', '2026-08-17 12:15:04'),
(421, 1, 'vacunas', 'CONSULTA', 'vacunas', NULL, 'limit=5000; offset=0; registros=18', '::1', '2026-08-17 12:15:04'),
(422, 1, 'estudiante_discapacidades', 'CONSULTA', 'estudiante_discapacidades', NULL, 'limit=5000; offset=0; registros=12', '::1', '2026-08-17 12:15:04'),
(423, 1, 'discapacidades', 'CONSULTA', 'discapacidades', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:04'),
(424, 1, 'estudiante_familiares', 'CONSULTA', 'estudiante_familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-17 12:15:04'),
(425, 1, 'familiares', 'CONSULTA', 'familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-17 12:15:04'),
(426, 1, 'inscripcion_requisitos', 'CONSULTA', 'inscripcion_requisitos', NULL, 'limit=5000; offset=0; registros=16', '::1', '2026-08-17 12:15:04'),
(427, 1, 'requisitos_inscripcion', 'CONSULTA', 'requisitos_inscripcion', NULL, 'limit=5000; offset=0; registros=8', '::1', '2026-08-17 12:15:04'),
(428, 1, 'tarifarios', 'CONSULTA', 'tarifarios', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:04'),
(429, 1, 'tarifas_grados', 'CONSULTA', 'tarifas_grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:15:04'),
(430, 1, 'historial_academico', 'CONSULTA', 'historial_academico', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:04'),
(431, 1, 'inscripciones', 'ELIMINAR', 'inscripciones', 3, 'criteria={\"id\":3}; afectados=1', '::1', '2026-08-17 12:15:07'),
(432, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:07'),
(433, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:15:07'),
(434, 1, 'niveles', 'CONSULTA', 'niveles', NULL, 'limit=5000; offset=0; registros=3', '::1', '2026-08-17 12:15:07'),
(435, 1, 'grados', 'CONSULTA', 'grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:15:07'),
(436, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:07'),
(437, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:07'),
(438, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:07'),
(439, 1, 'tandas', 'CONSULTA', 'tandas', NULL, 'limit=5000; offset=0; registros=2', '::1', '2026-08-17 12:15:07'),
(440, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:07'),
(441, 1, 'registros_civiles', 'CONSULTA', 'registros_civiles', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:07'),
(442, 1, 'direcciones_estudiantes', 'CONSULTA', 'direcciones_estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:07'),
(443, 1, 'estudiante_vacunas', 'CONSULTA', 'estudiante_vacunas', NULL, 'limit=5000; offset=0; registros=90', '::1', '2026-08-17 12:15:07'),
(444, 1, 'vacunas', 'CONSULTA', 'vacunas', NULL, 'limit=5000; offset=0; registros=18', '::1', '2026-08-17 12:15:07'),
(445, 1, 'estudiante_discapacidades', 'CONSULTA', 'estudiante_discapacidades', NULL, 'limit=5000; offset=0; registros=12', '::1', '2026-08-17 12:15:08'),
(446, 1, 'discapacidades', 'CONSULTA', 'discapacidades', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:08'),
(447, 1, 'estudiante_familiares', 'CONSULTA', 'estudiante_familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-17 12:15:08'),
(448, 1, 'familiares', 'CONSULTA', 'familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-17 12:15:08'),
(449, 1, 'inscripcion_requisitos', 'CONSULTA', 'inscripcion_requisitos', NULL, 'limit=5000; offset=0; registros=8', '::1', '2026-08-17 12:15:08'),
(450, 1, 'requisitos_inscripcion', 'CONSULTA', 'requisitos_inscripcion', NULL, 'limit=5000; offset=0; registros=8', '::1', '2026-08-17 12:15:08'),
(451, 1, 'tarifarios', 'CONSULTA', 'tarifarios', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:08'),
(452, 1, 'tarifas_grados', 'CONSULTA', 'tarifas_grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:15:08'),
(453, 1, 'historial_academico', 'CONSULTA', 'historial_academico', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:08'),
(454, 1, 'inscripciones', 'ELIMINAR', 'inscripciones', 1, 'criteria={\"id\":1}; afectados=1', '::1', '2026-08-17 12:15:11'),
(455, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-17 12:15:12'),
(456, 1, 'grados', 'CONSULTA', 'grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:15:12'),
(457, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:12'),
(458, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:12'),
(459, 1, 'niveles', 'CONSULTA', 'niveles', NULL, 'limit=5000; offset=0; registros=3', '::1', '2026-08-17 12:15:12'),
(460, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:15:12'),
(461, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:12'),
(462, 1, 'tandas', 'CONSULTA', 'tandas', NULL, 'limit=5000; offset=0; registros=2', '::1', '2026-08-17 12:15:12'),
(463, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:12'),
(464, 1, 'registros_civiles', 'CONSULTA', 'registros_civiles', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:12'),
(465, 1, 'direcciones_estudiantes', 'CONSULTA', 'direcciones_estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:12'),
(466, 1, 'estudiante_vacunas', 'CONSULTA', 'estudiante_vacunas', NULL, 'limit=5000; offset=0; registros=90', '::1', '2026-08-17 12:15:12'),
(467, 1, 'vacunas', 'CONSULTA', 'vacunas', NULL, 'limit=5000; offset=0; registros=18', '::1', '2026-08-17 12:15:12'),
(468, 1, 'estudiante_discapacidades', 'CONSULTA', 'estudiante_discapacidades', NULL, 'limit=5000; offset=0; registros=12', '::1', '2026-08-17 12:15:12'),
(469, 1, 'discapacidades', 'CONSULTA', 'discapacidades', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:12'),
(470, 1, 'estudiante_familiares', 'CONSULTA', 'estudiante_familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-17 12:15:12'),
(471, 1, 'familiares', 'CONSULTA', 'familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-17 12:15:12'),
(472, 1, 'inscripcion_requisitos', 'CONSULTA', 'inscripcion_requisitos', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-17 12:15:12'),
(473, 1, 'requisitos_inscripcion', 'CONSULTA', 'requisitos_inscripcion', NULL, 'limit=5000; offset=0; registros=8', '::1', '2026-08-17 12:15:12'),
(474, 1, 'tarifarios', 'CONSULTA', 'tarifarios', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:12'),
(475, 1, 'tarifas_grados', 'CONSULTA', 'tarifas_grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:15:12'),
(476, 1, 'historial_academico', 'CONSULTA', 'historial_academico', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:12'),
(477, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:15'),
(478, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:15:15'),
(479, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-17 12:15:15'),
(480, 1, 'personal', 'CONSULTA', 'personal', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:15'),
(481, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-17 12:15:15'),
(482, 1, 'metodos_pago', 'CONSULTA', 'metodos_pago', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:15'),
(483, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-17 12:15:15'),
(484, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:15'),
(485, 1, 'periodos_cobro', 'CONSULTA', 'periodos_cobro', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-17 12:15:15'),
(486, 1, 'parametros_financieros', 'CONSULTA', 'parametros_financieros', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:15'),
(487, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:22'),
(488, 1, 'personal', 'CONSULTA', 'personal', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:22'),
(489, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-17 12:15:22'),
(490, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-17 12:15:22'),
(491, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:15:22'),
(492, 1, 'metodos_pago', 'CONSULTA', 'metodos_pago', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:22'),
(493, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-17 12:15:22'),
(494, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:22'),
(495, 1, 'periodos_cobro', 'CONSULTA', 'periodos_cobro', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-17 12:15:22'),
(496, 1, 'parametros_financieros', 'CONSULTA', 'parametros_financieros', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:22'),
(497, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:15:23'),
(498, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-17 12:15:23'),
(499, 1, 'personal', 'CONSULTA', 'personal', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:23'),
(500, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:23'),
(501, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-17 12:15:23'),
(502, 1, 'metodos_pago', 'CONSULTA', 'metodos_pago', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:23'),
(503, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-17 12:15:23'),
(504, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:23'),
(505, 1, 'periodos_cobro', 'CONSULTA', 'periodos_cobro', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-17 12:15:23'),
(506, 1, 'parametros_financieros', 'CONSULTA', 'parametros_financieros', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:23'),
(507, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-17 12:15:30'),
(508, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:30'),
(509, 1, 'niveles', 'CONSULTA', 'niveles', NULL, 'limit=5000; offset=0; registros=3', '::1', '2026-08-17 12:15:30'),
(510, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:15:30'),
(511, 1, 'grados', 'CONSULTA', 'grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:15:30'),
(512, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:30'),
(513, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:30'),
(514, 1, 'tandas', 'CONSULTA', 'tandas', NULL, 'limit=5000; offset=0; registros=2', '::1', '2026-08-17 12:15:30'),
(515, 1, 'datos_centro_educativo', 'CONSULTA', 'datos_centro_educativo', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:30'),
(516, 1, 'registros_civiles', 'CONSULTA', 'registros_civiles', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:30'),
(517, 1, 'direcciones_estudiantes', 'CONSULTA', 'direcciones_estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:30'),
(518, 1, 'estudiante_vacunas', 'CONSULTA', 'estudiante_vacunas', NULL, 'limit=5000; offset=0; registros=90', '::1', '2026-08-17 12:15:30'),
(519, 1, 'vacunas', 'CONSULTA', 'vacunas', NULL, 'limit=5000; offset=0; registros=18', '::1', '2026-08-17 12:15:30'),
(520, 1, 'estudiante_discapacidades', 'CONSULTA', 'estudiante_discapacidades', NULL, 'limit=5000; offset=0; registros=12', '::1', '2026-08-17 12:15:31'),
(521, 1, 'discapacidades', 'CONSULTA', 'discapacidades', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:31'),
(522, 1, 'estudiante_familiares', 'CONSULTA', 'estudiante_familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-17 12:15:31'),
(523, 1, 'familiares', 'CONSULTA', 'familiares', NULL, 'limit=5000; offset=0; registros=10', '::1', '2026-08-17 12:15:31'),
(524, 1, 'inscripcion_requisitos', 'CONSULTA', 'inscripcion_requisitos', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-17 12:15:31'),
(525, 1, 'requisitos_inscripcion', 'CONSULTA', 'requisitos_inscripcion', NULL, 'limit=5000; offset=0; registros=8', '::1', '2026-08-17 12:15:31'),
(526, 1, 'tarifarios', 'CONSULTA', 'tarifarios', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:31'),
(527, 1, 'tarifas_grados', 'CONSULTA', 'tarifas_grados', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:15:31'),
(528, 1, 'historial_academico', 'CONSULTA', 'historial_academico', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:31'),
(529, 1, 'pagos', 'CONSULTA', 'pagos', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-17 12:15:42'),
(530, 1, 'personal', 'CONSULTA', 'personal', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:43'),
(531, 1, 'metodos_pago', 'CONSULTA', 'metodos_pago', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:43'),
(532, 1, 'estudiantes', 'CONSULTA', 'estudiantes', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:15:43'),
(533, 1, 'inscripciones', 'CONSULTA', 'inscripciones', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-17 12:15:43'),
(534, 1, 'planificaciones_academicas', 'CONSULTA', 'planificaciones_academicas', NULL, 'limit=5000; offset=0; registros=15', '::1', '2026-08-17 12:15:43'),
(535, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=200; offset=0; registros=1', '::1', '2026-08-17 12:15:43'),
(536, 1, 'secciones', 'CONSULTA', 'secciones', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:43'),
(537, 1, 'periodos_cobro', 'CONSULTA', 'periodos_cobro', NULL, 'limit=5000; offset=0; registros=0', '::1', '2026-08-17 12:15:43'),
(538, 1, 'parametros_financieros', 'CONSULTA', 'parametros_financieros', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:15:43'),
(539, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=500; offset=0; registros=1', '::1', '2026-08-17 12:15:53'),
(540, 1, 'anios_escolares', 'CONSULTA', 'anios_escolares', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:16:03'),
(541, 1, 'personal', 'CONSULTA', 'personal', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:16:03'),
(542, 1, 'condiciones_laborales', 'CONSULTA', 'condiciones_laborales', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-17 12:16:03'),
(543, 1, 'cargos', 'CONSULTA', 'cargos', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:16:03'),
(544, 1, 'asignaciones_laborales', 'CONSULTA', 'asignaciones_laborales', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:16:03'),
(545, 1, 'datos_laborales', 'CONSULTA', 'datos_laborales', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:16:03'),
(546, 1, 'departamentos', 'CONSULTA', 'departamentos', NULL, 'limit=5000; offset=0; registros=5', '::1', '2026-08-17 12:16:03'),
(547, 1, 'direcciones_personal', 'CONSULTA', 'direcciones_personal', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:16:03'),
(548, 1, 'roles', 'CONSULTA', 'roles', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-17 12:21:29'),
(549, 1, 'usuario_roles', 'CONSULTA', 'usuario_roles', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:21:29'),
(550, 1, 'roles', 'CONSULTA', 'roles', NULL, 'limit=5000; offset=0; registros=4', '::1', '2026-08-17 12:21:58'),
(551, 1, 'usuario_roles', 'CONSULTA', 'usuario_roles', NULL, 'limit=5000; offset=0; registros=1', '::1', '2026-08-17 12:21:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajas`
--

CREATE TABLE `cajas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo` varchar(30) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `ubicacion` varchar(150) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cajas`
--

INSERT INTO `cajas` (`id`, `nombre`, `codigo`, `descripcion`, `ubicacion`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Caja Principal', 'CAJA-01', 'Caja principal del Colegio Doña Elena', 'Administración', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja_sesiones`
--

CREATE TABLE `caja_sesiones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `caja_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_apertura_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_cierre_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fecha_apertura` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_cierre` datetime DEFAULT NULL,
  `monto_inicial` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_ingresos` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_egresos` decimal(12,2) NOT NULL DEFAULT 0.00,
  `monto_esperado` decimal(12,2) NOT NULL DEFAULT 0.00,
  `monto_contado` decimal(12,2) DEFAULT NULL,
  `diferencia` decimal(12,2) DEFAULT NULL,
  `estado` enum('ABIERTA','CERRADA','ANULADA') NOT NULL DEFAULT 'ABIERTA',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos`
--

CREATE TABLE `cargos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cargos`
--

INSERT INTO `cargos` (`id`, `nombre`, `descripcion`, `estado`) VALUES
(1, 'Docente de Primarias  Ambos ciclo', 'de primero a sexto de primaria', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargos_estudiantes`
--

CREATE TABLE `cargos_estudiantes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estudiante_id` bigint(20) UNSIGNED NOT NULL,
  `inscripcion_id` bigint(20) UNSIGNED DEFAULT NULL,
  `concepto_id` bigint(20) UNSIGNED NOT NULL,
  `periodo_id` bigint(20) UNSIGNED DEFAULT NULL,
  `descripcion` varchar(255) NOT NULL,
  `fecha_emision` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `monto` decimal(10,2) NOT NULL DEFAULT 0.00,
  `monto_pagado` decimal(10,2) NOT NULL DEFAULT 0.00,
  `monto_pendiente` decimal(10,2) GENERATED ALWAYS AS (`monto` - `monto_pagado`) STORED,
  `estado` enum('PENDIENTE','PARCIAL','PAGADO','ANULADO') NOT NULL DEFAULT 'PENDIENTE',
  `genera_mora` tinyint(1) NOT NULL DEFAULT 0,
  `mora_generada` tinyint(1) NOT NULL DEFAULT 0,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conceptos_cobro`
--

CREATE TABLE `conceptos_cobro` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(30) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` enum('INSCRIPCION','COLEGIATURA','SERVICIO','MORA') NOT NULL,
  `requiere_periodo` tinyint(1) NOT NULL DEFAULT 0,
  `genera_mora` tinyint(1) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `conceptos_cobro`
--

INSERT INTO `conceptos_cobro` (`id`, `codigo`, `nombre`, `tipo`, `requiere_periodo`, `genera_mora`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'INSCRIPCION', 'Inscripción', 'INSCRIPCION', 0, 0, 1, '2026-08-08 13:26:06', '2026-08-08 13:26:06'),
(2, 'COLEGIATURA', 'Colegiatura', 'COLEGIATURA', 1, 1, 1, '2026-08-08 13:26:06', '2026-08-08 13:26:06'),
(3, 'SERVICIO', 'Servicio', 'SERVICIO', 0, 0, 1, '2026-08-08 13:26:06', '2026-08-08 13:26:06'),
(4, 'MORA', 'Mora', 'MORA', 1, 0, 1, '2026-08-08 13:26:06', '2026-08-08 13:26:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conceptos_nomina`
--

CREATE TABLE `conceptos_nomina` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `tipo` enum('INGRESO','DEDUCCION') NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `es_porcentaje` tinyint(1) NOT NULL DEFAULT 0,
  `valor_default` decimal(12,2) NOT NULL DEFAULT 0.00,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `conceptos_nomina`
--

INSERT INTO `conceptos_nomina` (`id`, `nombre`, `tipo`, `descripcion`, `es_porcentaje`, `valor_default`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Sueldo base', 'INGRESO', 'Salario base correspondiente al empleado', 0, 0.00, 1, '2026-08-08 13:15:05', '2026-08-08 13:15:05'),
(2, 'Bonificación', 'INGRESO', 'Bonificaciones adicionales otorgadas al empleado', 0, 0.00, 1, '2026-08-08 13:15:05', '2026-08-08 13:15:05'),
(3, 'Horas extras', 'INGRESO', 'Pago correspondiente a horas extraordinarias trabajadas', 0, 0.00, 1, '2026-08-08 13:15:05', '2026-08-08 13:15:05'),
(4, 'Otros ingresos', 'INGRESO', 'Otros ingresos adicionales', 0, 0.00, 1, '2026-08-08 13:15:05', '2026-08-08 13:15:05'),
(5, 'AFP', 'DEDUCCION', 'Aporte del empleado al sistema de pensiones', 1, 0.00, 1, '2026-08-08 13:15:05', '2026-08-08 13:15:05'),
(6, 'Seguro familiar de salud', 'DEDUCCION', 'Aporte del empleado al seguro familiar de salud', 1, 0.00, 1, '2026-08-08 13:15:05', '2026-08-08 13:15:05'),
(7, 'ISR', 'DEDUCCION', 'Impuesto sobre la renta', 0, 0.00, 1, '2026-08-08 13:15:05', '2026-08-08 13:15:05'),
(8, 'Ausencia', 'DEDUCCION', 'Descuento por ausencia del empleado', 0, 0.00, 1, '2026-08-08 13:15:05', '2026-08-08 13:15:05'),
(9, 'Tardanza', 'DEDUCCION', 'Descuento por tardanza', 0, 0.00, 1, '2026-08-08 13:15:05', '2026-08-08 13:15:05'),
(10, 'Otros descuentos', 'DEDUCCION', 'Otros descuentos aplicables al empleado', 0, 0.00, 1, '2026-08-08 13:15:05', '2026-08-08 13:15:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `condiciones_laborales`
--

CREATE TABLE `condiciones_laborales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `condiciones_laborales`
--

INSERT INTO `condiciones_laborales` (`id`, `nombre`, `estado`) VALUES
(1, 'Fijo', 1),
(2, 'Contratado', 1),
(3, 'Temporero', 1),
(4, 'Empleado de tiempo parcial', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas_bancarias`
--

CREATE TABLE `cuentas_bancarias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `banco` varchar(150) NOT NULL,
  `nombre_cuenta` varchar(150) NOT NULL,
  `tipo_cuenta` enum('AHORRO','CORRIENTE') NOT NULL,
  `numero_cuenta` varchar(100) NOT NULL,
  `titular` varchar(255) DEFAULT NULL,
  `moneda` varchar(10) NOT NULL DEFAULT 'DOP',
  `saldo_inicial` decimal(14,2) NOT NULL DEFAULT 0.00,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_centro_educativo`
--

CREATE TABLE `datos_centro_educativo` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre_centro` varchar(255) NOT NULL,
  `codigo_centro` varchar(50) DEFAULT NULL,
  `rnc` varchar(30) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `celular` varchar(30) DEFAULT NULL,
  `correo_electronico` varchar(150) DEFAULT NULL,
  `lema` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `datos_centro_educativo`
--

INSERT INTO `datos_centro_educativo` (`id`, `nombre_centro`, `codigo_centro`, `rnc`, `telefono`, `celular`, `correo_electronico`, `lema`, `direccion`, `logo`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Colegio Doña Elena', '08716', '032-0003364-9', '809-580-3717', '809-405-6131', 'colegiodonaelena@gmail.com', 'Amor, Honestidad y Responsabilidad', 'Calle Real No.23, Tamboril, Santiago, Rep. Dom.', 'uploads/logos/logo_20260808_175609_dc554a1b.jpg', 1, '2026-08-08 13:26:07', '2026-08-08 15:56:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_laborales`
--

CREATE TABLE `datos_laborales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `personal_id` bigint(20) UNSIGNED NOT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `tanda` enum('MATUTINA','VESPERTINA','MATUTINA_VESPERTINA') DEFAULT NULL,
  `salario` decimal(12,2) NOT NULL DEFAULT 0.00,
  `banco` varchar(150) DEFAULT NULL,
  `numero_cuenta_bancaria` varchar(100) DEFAULT NULL,
  `acepta_terminos` tinyint(1) NOT NULL DEFAULT 0,
  `empleado_activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `datos_laborales`
--

INSERT INTO `datos_laborales` (`id`, `personal_id`, `fecha_ingreso`, `tanda`, `salario`, `banco`, `numero_cuenta_bancaria`, `acepta_terminos`, `empleado_activo`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-08-18', 'MATUTINA_VESPERTINA', 15600.00, 'asociacion  cibao', '1000505458', 1, 1, '2026-08-08 19:59:42', '2026-08-08 19:59:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`id`, `nombre`, `estado`) VALUES
(1, 'Conserjeria', 1),
(2, 'Coordinacion', 1),
(3, 'Direccion Administrativa', 1),
(4, 'Direccion Docente', 1),
(5, 'Secretaria', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones_estudiantes`
--

CREATE TABLE `direcciones_estudiantes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estudiante_id` bigint(20) UNSIGNED NOT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `municipio` varchar(100) DEFAULT NULL,
  `distrito_municipal` varchar(100) DEFAULT NULL,
  `seccion` varchar(100) DEFAULT NULL,
  `barrio` varchar(100) DEFAULT NULL,
  `sub_barrio` varchar(100) DEFAULT NULL,
  `calle_numero` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `direcciones_estudiantes`
--

INSERT INTO `direcciones_estudiantes` (`id`, `estudiante_id`, `provincia`, `municipio`, `distrito_municipal`, `seccion`, `barrio`, `sub_barrio`, `calle_numero`, `created_at`, `updated_at`) VALUES
(1, 1, 'SANTIAGO', 'tambororil', 'N/A', 'CENTRO DEL PUEBLO', 'N/A', 'N/A', 'REAL 23', '2026-08-08 17:13:29', '2026-08-08 17:13:29'),
(2, 3, 'SANTIAGO', 'tambororil', 'N/A', 'CENTRO DEL PUEBLO', 'N/A', 'N/A', 'REAL 23', '2026-08-08 19:16:29', '2026-08-08 19:16:29'),
(3, 4, 'SANTIAGO', 'tambororil', 'N/A', 'CENTRO DEL PUEBLO', 'N/A', 'N/A', 'REAL 23', '2026-08-15 12:38:11', '2026-08-15 12:38:11'),
(4, 5, 'SANTIAGO', 'tambororil', 'N/A', 'CENTRO DEL PUEBLO', 'N/A', 'N/A', 'REAL 23', '2026-08-15 15:20:38', '2026-08-15 15:20:38'),
(5, 6, 'SANTIAGO', 'tambororil', 'N/A', 'CENTRO DEL PUEBLO', 'N/A', 'N/A', 'REAL 23', '2026-08-15 15:26:49', '2026-08-15 15:26:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones_personal`
--

CREATE TABLE `direcciones_personal` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `personal_id` bigint(20) UNSIGNED NOT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `municipio` varchar(100) DEFAULT NULL,
  `distrito_municipal` varchar(100) DEFAULT NULL,
  `seccion` varchar(100) DEFAULT NULL,
  `barrio` varchar(100) DEFAULT NULL,
  `sub_barrio` varchar(100) DEFAULT NULL,
  `calle_numero` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `direcciones_personal`
--

INSERT INTO `direcciones_personal` (`id`, `personal_id`, `provincia`, `municipio`, `distrito_municipal`, `seccion`, `barrio`, `sub_barrio`, `calle_numero`, `created_at`, `updated_at`) VALUES
(1, 1, 'SANTIAGO', 'tambororil', 'n/a', 'n/a', 'N/A', 'n/a', 'calle45', '2026-08-08 19:59:42', '2026-08-08 19:59:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discapacidades`
--

CREATE TABLE `discapacidades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `discapacidades`
--

INSERT INTO `discapacidades` (`id`, `nombre`, `estado`) VALUES
(1, 'Visual', 1),
(2, 'Auditiva', 1),
(3, 'Fisica', 1),
(4, 'Intelectual', 1),
(5, 'Trastorno generalizado del desarrollo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `ruta` varchar(500) NOT NULL,
  `extension` varchar(20) DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `tamano` bigint(20) UNSIGNED DEFAULT NULL,
  `tipo_documento` varchar(100) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `usuario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `id_sigerd` varchar(50) DEFAULT NULL,
  `primer_nombre` varchar(100) NOT NULL,
  `segundo_nombre` varchar(100) DEFAULT NULL,
  `primer_apellido` varchar(100) NOT NULL,
  `segundo_apellido` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `sexo` enum('MASCULINO','FEMENINO') DEFAULT NULL,
  `estado_civil` enum('SOLTERO','CASADO','VIUDO','DIVORCIADO') DEFAULT NULL,
  `nacionalidad` varchar(100) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `celular` varchar(30) DEFAULT NULL,
  `whatsapp` varchar(30) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id`, `foto`, `id_sigerd`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `fecha_nacimiento`, `sexo`, `estado_civil`, `nacionalidad`, `telefono`, `celular`, `whatsapp`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 'uploads/estudiantes/estudiante_20260808_202644_4a4f72f4.webp', '254584', 'MARIA', 'MABEL', 'FERNANDEZ', 'VASQUEZ', '2013-01-07', 'FEMENINO', 'SOLTERO', 'Dominicana', '8094056131', '809-405-6131', '8097784864', 'zfdtgh', '2026-08-08 17:13:29', '2026-08-08 18:26:44'),
(3, 'uploads/estudiantes/estudiante_20260808_211629_b8ed2c88.jpg', '2548841', 'ASmel', 'MABEL', 'FERNANDEZ', 'VASQUEZ', '2026-08-11', 'MASCULINO', 'SOLTERO', 'Dominicana', '8094056131', '809-405-6131', '8092985866', 'dfhgsdth', '2026-08-08 19:16:29', '2026-08-08 19:16:29'),
(4, 'uploads/estudiantes/estudiante_20260815_143811_c41b09df.jpg', '5465656', 'CARLOS', 'JUAN', 'GIL', 'MARTINEZ', '2026-08-04', 'MASCULINO', 'SOLTERO', 'Dominicana', '8098665881', '809-405-6131', '8097784864', NULL, '2026-08-15 12:38:11', '2026-08-15 12:38:11'),
(5, 'uploads/estudiantes/estudiante_20260815_172038_414220db.jpg', '35465484', 'HANDRI', 'LICELLOT', 'FERNADEZ', 'VASQUEZ', '2022-06-27', 'FEMENINO', NULL, 'Dominicana', '8092985866', '809-405-6131', '8097784864', NULL, '2026-08-15 15:20:38', '2026-08-15 15:20:38'),
(6, NULL, '6874668', 'ALICA', 'SHERIBEL', 'VASQUEZ', 'FERNANDEZ', '2019-06-17', 'FEMENINO', 'SOLTERO', 'Dominicana', '8094056131', '809-405-6131', '8097784864', NULL, '2026-08-15 15:26:49', '2026-08-15 15:26:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante_discapacidades`
--

CREATE TABLE `estudiante_discapacidades` (
  `estudiante_id` bigint(20) UNSIGNED NOT NULL,
  `discapacidad_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estudiante_discapacidades`
--

INSERT INTO `estudiante_discapacidades` (`estudiante_id`, `discapacidad_id`) VALUES
(1, 1),
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(4, 5),
(5, 1),
(6, 1),
(6, 2),
(6, 3),
(6, 4),
(6, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante_documentos`
--

CREATE TABLE `estudiante_documentos` (
  `estudiante_id` bigint(20) UNSIGNED NOT NULL,
  `documento_id` bigint(20) UNSIGNED NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante_familiares`
--

CREATE TABLE `estudiante_familiares` (
  `estudiante_id` bigint(20) UNSIGNED NOT NULL,
  `familiar_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estudiante_familiares`
--

INSERT INTO `estudiante_familiares` (`estudiante_id`, `familiar_id`) VALUES
(1, 1),
(1, 2),
(3, 3),
(3, 4),
(4, 5),
(4, 6),
(5, 7),
(5, 8),
(6, 9),
(6, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante_vacunas`
--

CREATE TABLE `estudiante_vacunas` (
  `estudiante_id` bigint(20) UNSIGNED NOT NULL,
  `vacuna_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estudiante_vacunas`
--

INSERT INTO `estudiante_vacunas` (`estudiante_id`, `vacuna_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(3, 6),
(3, 7),
(3, 8),
(3, 9),
(3, 10),
(3, 11),
(3, 12),
(3, 13),
(3, 14),
(3, 15),
(3, 16),
(3, 17),
(3, 18),
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(4, 5),
(4, 6),
(4, 7),
(4, 8),
(4, 9),
(4, 10),
(4, 11),
(4, 12),
(4, 13),
(4, 14),
(4, 15),
(4, 16),
(4, 17),
(4, 18),
(5, 1),
(5, 2),
(5, 3),
(5, 4),
(5, 5),
(5, 6),
(5, 7),
(5, 8),
(5, 9),
(5, 10),
(5, 11),
(5, 12),
(5, 13),
(5, 14),
(5, 15),
(5, 16),
(5, 17),
(5, 18),
(6, 1),
(6, 2),
(6, 3),
(6, 4),
(6, 5),
(6, 6),
(6, 7),
(6, 8),
(6, 9),
(6, 10),
(6, 11),
(6, 12),
(6, 13),
(6, 14),
(6, 15),
(6, 16),
(6, 17),
(6, 18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudios_concluidos`
--

CREATE TABLE `estudios_concluidos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `personal_id` bigint(20) UNSIGNED NOT NULL,
  `nivel_academico` varchar(150) DEFAULT NULL,
  `entidad` varchar(255) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `anio_inicio` year(4) DEFAULT NULL,
  `anio_fin` year(4) DEFAULT NULL,
  `numero_registro` varchar(100) DEFAULT NULL,
  `numero_folio` varchar(100) DEFAULT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estudios_concluidos`
--

INSERT INTO `estudios_concluidos` (`id`, `personal_id`, `nivel_academico`, `entidad`, `titulo`, `anio_inicio`, `anio_fin`, `numero_registro`, `numero_folio`, `pais`, `ciudad`, `created_at`, `updated_at`) VALUES
(1, 1, 'dfg', 'dfg', 'dfg', '0000', '0000', 'sdfg', 'fg', 'fg', 'fd', '2026-08-08 19:59:42', '2026-08-08 19:59:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudios_proceso`
--

CREATE TABLE `estudios_proceso` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `personal_id` bigint(20) UNSIGNED NOT NULL,
  `area_estudio` varchar(150) DEFAULT NULL,
  `entidad` varchar(255) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `anio_inicio` year(4) DEFAULT NULL,
  `duracion` varchar(100) DEFAULT NULL,
  `horas` int(10) UNSIGNED DEFAULT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estudios_proceso`
--

INSERT INTO `estudios_proceso` (`id`, `personal_id`, `area_estudio`, `entidad`, `titulo`, `anio_inicio`, `duracion`, `horas`, `pais`, `ciudad`, `created_at`, `updated_at`) VALUES
(1, 1, 'dsfg', 'fdgf', 'fdgdsf', '0000', 'fdgs', 4, 'dsfg', 'fdg', '2026-08-08 19:59:42', '2026-08-08 19:59:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familiares`
--

CREATE TABLE `familiares` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipo_familiar` enum('MADRE','PADRE','TUTOR') NOT NULL,
  `primer_nombre` varchar(100) NOT NULL,
  `primer_apellido` varchar(100) NOT NULL,
  `cedula` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `familiares`
--

INSERT INTO `familiares` (`id`, `tipo_familiar`, `primer_nombre`, `primer_apellido`, `cedula`, `created_at`, `updated_at`) VALUES
(1, 'MADRE', 'KATY', 'FERNADEZ', '032-5487652-0', '2026-08-08 17:13:29', '2026-08-08 17:13:29'),
(2, 'PADRE', 'PEDRO', 'VASQUEZ', '032-00653298-0', '2026-08-08 17:13:29', '2026-08-08 17:13:29'),
(3, 'MADRE', 'KATY', 'FERNADEZ', '032-5487652-0', '2026-08-08 19:16:29', '2026-08-08 19:16:29'),
(4, 'PADRE', 'PEDRO', 'VASQUEZ', '032-00653298-0', '2026-08-08 19:16:30', '2026-08-08 19:16:30'),
(5, 'MADRE', 'KATY', 'FERNADEZ', '032-5487652-0', '2026-08-15 12:38:11', '2026-08-15 12:38:11'),
(6, 'PADRE', 'PEDRO', 'VASQUEZ', '032-00653298-0', '2026-08-15 12:38:11', '2026-08-15 12:38:11'),
(7, 'MADRE', 'KATY', 'FERNADEZ', '032-5487652-0', '2026-08-15 15:20:38', '2026-08-15 15:20:38'),
(8, 'PADRE', 'PEDRO', 'VASQUEZ', '032-00653298-0', '2026-08-15 15:20:38', '2026-08-15 15:20:38'),
(9, 'MADRE', 'KATY', 'FERNADEZ', '032-5487652-0', '2026-08-15 15:26:50', '2026-08-15 15:26:50'),
(10, 'PADRE', 'PEDRO', 'VASQUEZ', '032-00653298-0', '2026-08-15 15:26:50', '2026-08-15 15:26:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grados`
--

CREATE TABLE `grados` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `grado` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `grados`
--

INSERT INTO `grados` (`id`, `grado`) VALUES
(1, 'Pre-Kinder'),
(2, 'Kinder'),
(3, 'Pre-Primario'),
(4, 'Primero'),
(5, 'Segundo'),
(6, 'Tercero'),
(7, 'Cuarto'),
(8, 'Quinto'),
(9, 'Sexto'),
(10, 'Primero'),
(11, 'Segundo'),
(12, 'Tercero'),
(13, 'Cuarto'),
(14, 'Quinto'),
(15, 'Sexto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_academico`
--

CREATE TABLE `historial_academico` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estudiante_id` bigint(20) UNSIGNED NOT NULL,
  `anio_escolar_id` bigint(20) UNSIGNED NOT NULL,
  `nivel_id` bigint(20) UNSIGNED NOT NULL,
  `grado_id` bigint(20) UNSIGNED NOT NULL,
  `seccion_id` bigint(20) UNSIGNED NOT NULL,
  `jornada` enum('MATUTINO','VESPERTINO') NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` enum('CURSANDO','PROMOVIDO','REPROBADO','RETIRADO','TRASLADADO','GRADUADO') NOT NULL DEFAULT 'CURSANDO',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_academico`
--

INSERT INTO `historial_academico` (`id`, `estudiante_id`, `anio_escolar_id`, `nivel_id`, `grado_id`, `seccion_id`, `jornada`, `fecha_inicio`, `fecha_fin`, `estado`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 3, 15, 1, 'VESPERTINO', '2026-08-08', NULL, 'CURSANDO', 'Condicion final actualizada desde modulo Condicion final de estudiante.', '2026-08-08 19:37:02', '2026-08-08 19:39:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_salarios`
--

CREATE TABLE `historial_salarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `personal_id` bigint(20) UNSIGNED NOT NULL,
  `salario` decimal(12,2) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `usuario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidencias_nomina`
--

CREATE TABLE `incidencias_nomina` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `personal_id` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `tipo` enum('AUSENCIA','TARDANZA','PERMISO','LICENCIA','HORAS_EXTRAS','OTRA') NOT NULL,
  `cantidad` decimal(8,2) NOT NULL DEFAULT 1.00,
  `horas` decimal(8,2) NOT NULL DEFAULT 0.00,
  `justificada` tinyint(1) NOT NULL DEFAULT 0,
  `observaciones` varchar(255) DEFAULT NULL,
  `estado` enum('PENDIENTE','APROBADA','RECHAZADA','APLICADA') NOT NULL DEFAULT 'PENDIENTE',
  `usuario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estudiante_id` bigint(20) UNSIGNED NOT NULL,
  `planificacion_academica_id` bigint(20) UNSIGNED NOT NULL,
  `centro_procedencia` varchar(255) DEFAULT NULL,
  `tarifa_inscripcion` decimal(10,2) NOT NULL DEFAULT 0.00,
  `mensualidad` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fecha_inscripcion` date NOT NULL,
  `acepta_terminos` tinyint(1) NOT NULL DEFAULT 0,
  `inscripcion_activa` tinyint(1) NOT NULL DEFAULT 1,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcion_requisitos`
--

CREATE TABLE `inscripcion_requisitos` (
  `inscripcion_id` bigint(20) UNSIGNED NOT NULL,
  `requisito_id` bigint(20) UNSIGNED NOT NULL,
  `presentado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pago`
--

CREATE TABLE `metodos_pago` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo` varchar(30) NOT NULL,
  `requiere_referencia` tinyint(1) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `metodos_pago`
--

INSERT INTO `metodos_pago` (`id`, `nombre`, `codigo`, `requiere_referencia`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'Efectivo', 'EFECTIVO', 0, 1, '2026-08-08 13:26:07', '2026-08-08 13:26:07'),
(2, 'Transferencia bancaria', 'TRANSFERENCIA', 1, 1, '2026-08-08 13:26:07', '2026-08-08 13:26:07'),
(3, 'Depósito bancario', 'DEPOSITO', 1, 1, '2026-08-08 13:26:07', '2026-08-08 13:26:07'),
(4, 'Tarjeta', 'TARJETA', 1, 1, '2026-08-08 13:26:07', '2026-08-08 13:26:07'),
(5, 'Cheque', 'CHEQUE', 1, 1, '2026-08-08 13:26:07', '2026-08-08 13:26:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_bancarios`
--

CREATE TABLE `movimientos_bancarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cuenta_bancaria_id` bigint(20) UNSIGNED NOT NULL,
  `tipo` enum('DEPOSITO','TRANSFERENCIA_ENTRADA','TRANSFERENCIA_SALIDA','RETIRO','CHEQUE','COMISION','OTRO') NOT NULL,
  `concepto` varchar(255) NOT NULL,
  `monto` decimal(14,2) NOT NULL DEFAULT 0.00,
  `fecha_movimiento` datetime NOT NULL DEFAULT current_timestamp(),
  `referencia` varchar(150) DEFAULT NULL,
  `pago_id` bigint(20) UNSIGNED DEFAULT NULL,
  `usuario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `estado` enum('APLICADO','ANULADO') NOT NULL DEFAULT 'APLICADO',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_caja`
--

CREATE TABLE `movimientos_caja` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `caja_sesion_id` bigint(20) UNSIGNED NOT NULL,
  `tipo` enum('INGRESO','EGRESO') NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `concepto` varchar(255) NOT NULL,
  `monto` decimal(12,2) NOT NULL DEFAULT 0.00,
  `fecha_movimiento` datetime NOT NULL DEFAULT current_timestamp(),
  `pago_id` bigint(20) UNSIGNED DEFAULT NULL,
  `usuario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `referencia` varchar(150) DEFAULT NULL,
  `estado` enum('APLICADO','ANULADO') NOT NULL DEFAULT 'APLICADO',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niveles`
--

CREATE TABLE `niveles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nivel` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `niveles`
--

INSERT INTO `niveles` (`id`, `nivel`) VALUES
(1, 'Inicial'),
(2, 'Primaria'),
(3, 'Secundaria');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nominas`
--

CREATE TABLE `nominas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `periodo_nomina_id` bigint(20) UNSIGNED NOT NULL,
  `numero_nomina` varchar(50) NOT NULL,
  `fecha_proceso` datetime NOT NULL DEFAULT current_timestamp(),
  `total_ingresos` decimal(14,2) NOT NULL DEFAULT 0.00,
  `total_deducciones` decimal(14,2) NOT NULL DEFAULT 0.00,
  `total_neto` decimal(14,2) NOT NULL DEFAULT 0.00,
  `estado` enum('BORRADOR','PROCESADA','PAGADA','ANULADA') NOT NULL DEFAULT 'BORRADOR',
  `observaciones` text DEFAULT NULL,
  `usuario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nomina_detalles`
--

CREATE TABLE `nomina_detalles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomina_id` bigint(20) UNSIGNED NOT NULL,
  `personal_id` bigint(20) UNSIGNED NOT NULL,
  `salario_base` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_ingresos` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_deducciones` decimal(12,2) NOT NULL DEFAULT 0.00,
  `salario_neto` decimal(12,2) NOT NULL DEFAULT 0.00,
  `dias_trabajados` decimal(6,2) NOT NULL DEFAULT 0.00,
  `dias_ausentes` decimal(6,2) NOT NULL DEFAULT 0.00,
  `horas_extras` decimal(8,2) NOT NULL DEFAULT 0.00,
  `estado_pago` enum('PENDIENTE','PAGADO','ANULADO') NOT NULL DEFAULT 'PENDIENTE',
  `fecha_pago` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nomina_detalle_conceptos`
--

CREATE TABLE `nomina_detalle_conceptos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomina_detalle_id` bigint(20) UNSIGNED NOT NULL,
  `concepto_id` bigint(20) UNSIGNED NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `cantidad` decimal(10,2) NOT NULL DEFAULT 1.00,
  `porcentaje` decimal(8,4) DEFAULT NULL,
  `valor` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nomina_historial`
--

CREATE TABLE `nomina_historial` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomina_id` bigint(20) UNSIGNED NOT NULL,
  `estado_anterior` varchar(30) DEFAULT NULL,
  `estado_nuevo` varchar(30) NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `usuario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estudiante_id` bigint(20) UNSIGNED NOT NULL,
  `numero_recibo` varchar(50) NOT NULL,
  `fecha_pago` datetime NOT NULL DEFAULT current_timestamp(),
  `metodo_pago_id` bigint(20) UNSIGNED NOT NULL,
  `referencia` varchar(150) DEFAULT NULL,
  `monto_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estado` enum('APLICADO','ANULADO') NOT NULL DEFAULT 'APLICADO',
  `observaciones` text DEFAULT NULL,
  `usuario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_nomina`
--

CREATE TABLE `pagos_nomina` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomina_detalle_id` bigint(20) UNSIGNED NOT NULL,
  `fecha_pago` date NOT NULL,
  `metodo_pago` enum('EFECTIVO','TRANSFERENCIA','CHEQUE') NOT NULL,
  `banco` varchar(150) DEFAULT NULL,
  `numero_referencia` varchar(100) DEFAULT NULL,
  `monto` decimal(12,2) NOT NULL,
  `estado` enum('PENDIENTE','CONFIRMADO','ANULADO') NOT NULL DEFAULT 'PENDIENTE',
  `observaciones` varchar(255) DEFAULT NULL,
  `usuario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago_detalles`
--

CREATE TABLE `pago_detalles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pago_id` bigint(20) UNSIGNED NOT NULL,
  `cargo_id` bigint(20) UNSIGNED NOT NULL,
  `monto` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parametros_financieros`
--

CREATE TABLE `parametros_financieros` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `anio_escolar_id` bigint(20) UNSIGNED NOT NULL,
  `dia_vencimiento_mensual` tinyint(3) UNSIGNED NOT NULL DEFAULT 25,
  `mora_mensual` decimal(10,2) NOT NULL DEFAULT 200.00,
  `regla_especial` text DEFAULT NULL,
  `pago_agosto_libera_junio` tinyint(1) NOT NULL DEFAULT 1,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `parametros_financieros`
--

INSERT INTO `parametros_financieros` (`id`, `anio_escolar_id`, `dia_vencimiento_mensual`, `mora_mensual`, `regla_especial`, `pago_agosto_libera_junio`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 25, 300.00, NULL, 1, 1, '2026-08-08 18:18:48', '2026-08-15 19:06:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos_cobro`
--

CREATE TABLE `periodos_cobro` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `anio_escolar_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `numero_mes` tinyint(3) UNSIGNED NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `es_junio` tinyint(1) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos_nomina`
--

CREATE TABLE `periodos_nomina` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `anio_escolar_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `fecha_pago` date NOT NULL,
  `estado` enum('ABIERTO','PROCESADO','PAGADO','CERRADO') NOT NULL DEFAULT 'ABIERTO',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `modulo` varchar(100) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `codigo` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `modulo`, `nombre`, `codigo`, `descripcion`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'ESTUDIANTES', 'Ver estudiantes', 'ESTUDIANTES_VER', 'Permite consultar estudiantes', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(2, 'ESTUDIANTES', 'Crear estudiantes', 'ESTUDIANTES_CREAR', 'Permite registrar estudiantes', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(3, 'ESTUDIANTES', 'Editar estudiantes', 'ESTUDIANTES_EDITAR', 'Permite modificar estudiantes', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(4, 'ESTUDIANTES', 'Eliminar estudiantes', 'ESTUDIANTES_ELIMINAR', 'Permite eliminar estudiantes', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(5, 'INSCRIPCIONES', 'Ver inscripciones', 'INSCRIPCIONES_VER', 'Permite consultar inscripciones', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(6, 'INSCRIPCIONES', 'Crear inscripciones', 'INSCRIPCIONES_CREAR', 'Permite registrar inscripciones', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(7, 'INSCRIPCIONES', 'Editar inscripciones', 'INSCRIPCIONES_EDITAR', 'Permite modificar inscripciones', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(8, 'CAJA', 'Ver caja', 'CAJA_VER', 'Permite consultar movimientos de caja', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(9, 'CAJA', 'Abrir caja', 'CAJA_ABRIR', 'Permite realizar apertura de caja', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(10, 'CAJA', 'Cerrar caja', 'CAJA_CERRAR', 'Permite realizar cierre de caja', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(11, 'CAJA', 'Registrar ingreso', 'CAJA_INGRESO', 'Permite registrar ingresos', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(12, 'CAJA', 'Registrar egreso', 'CAJA_EGRESO', 'Permite registrar egresos', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(13, 'PAGOS', 'Ver pagos', 'PAGOS_VER', 'Permite consultar pagos', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(14, 'PAGOS', 'Registrar pagos', 'PAGOS_CREAR', 'Permite registrar pagos', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(15, 'PAGOS', 'Anular pagos', 'PAGOS_ANULAR', 'Permite anular pagos', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(16, 'PERSONAL', 'Ver personal', 'PERSONAL_VER', 'Permite consultar personal', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(17, 'PERSONAL', 'Crear personal', 'PERSONAL_CREAR', 'Permite registrar personal', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(18, 'PERSONAL', 'Editar personal', 'PERSONAL_EDITAR', 'Permite modificar personal', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(19, 'NOMINA', 'Ver nómina', 'NOMINA_VER', 'Permite consultar nóminas', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(20, 'NOMINA', 'Procesar nómina', 'NOMINA_PROCESAR', 'Permite procesar nóminas', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(21, 'NOMINA', 'Pagar nómina', 'NOMINA_PAGAR', 'Permite registrar pagos de nómina', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(22, 'REPORTES', 'Ver reportes', 'REPORTES_VER', 'Permite consultar reportes', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(23, 'CONFIGURACION', 'Configurar sistema', 'CONFIGURACION_EDITAR', 'Permite modificar parámetros del sistema', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(24, 'USUARIOS', 'Gestionar usuarios', 'USUARIOS_GESTIONAR', 'Permite administrar usuarios y roles', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24'),
(25, 'AUDITORIA', 'Ver auditoría', 'AUDITORIA_VER', 'Permite consultar registros de auditoría', 1, '2026-08-08 13:32:24', '2026-08-08 13:32:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `cedula_pasaporte` varchar(30) NOT NULL,
  `primer_nombre` varchar(100) NOT NULL,
  `segundo_nombre` varchar(100) DEFAULT NULL,
  `primer_apellido` varchar(100) NOT NULL,
  `segundo_apellido` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `sexo` enum('MASCULINO','FEMENINO') DEFAULT NULL,
  `estado_civil` enum('SOLTERO','CASADO','VIUDO','DIVORCIADO') DEFAULT NULL,
  `nacionalidad` varchar(100) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `celular` varchar(30) DEFAULT NULL,
  `whatsapp` varchar(30) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`id`, `foto`, `cedula_pasaporte`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `fecha_nacimiento`, `sexo`, `estado_civil`, `nacionalidad`, `telefono`, `celular`, `whatsapp`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'uploads/personal/personal_20260808_215942_1021b584.jpg', '03200256584', 'JUAN', 'CARLOS', 'MARTINEZ', 'FAÑA', '2026-08-05', 'MASCULINO', 'CASADO', 'Dominicana', '8094056131', '809-405-6131', '8092985866', 1, '2026-08-08 19:59:42', '2026-08-08 19:59:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_documentos`
--

CREATE TABLE `personal_documentos` (
  `personal_id` bigint(20) UNSIGNED NOT NULL,
  `documento_id` bigint(20) UNSIGNED NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planificaciones_academicas`
--

CREATE TABLE `planificaciones_academicas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `anio_escolar_id` bigint(20) UNSIGNED NOT NULL,
  `nivel_id` bigint(20) UNSIGNED NOT NULL,
  `grado_id` bigint(20) UNSIGNED NOT NULL,
  `seccion_id` bigint(20) UNSIGNED NOT NULL,
  `tanda_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jornada` enum('MATUTINO','VESPERTINO') NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `planificaciones_academicas`
--

INSERT INTO `planificaciones_academicas` (`id`, `anio_escolar_id`, `nivel_id`, `grado_id`, `seccion_id`, `tanda_id`, `jornada`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 2, 'VESPERTINO', 1, '2026-08-08 15:31:21', '2026-08-08 15:36:19'),
(2, 1, 1, 2, 1, 2, 'VESPERTINO', 1, '2026-08-08 15:31:35', '2026-08-08 15:35:41'),
(3, 1, 1, 3, 1, 2, 'VESPERTINO', 1, '2026-08-08 15:31:53', '2026-08-08 15:36:23'),
(4, 1, 2, 4, 1, 2, 'VESPERTINO', 1, '2026-08-08 15:32:13', '2026-08-08 15:36:29'),
(5, 1, 2, 5, 1, 2, 'VESPERTINO', 1, '2026-08-08 15:32:45', '2026-08-08 15:36:36'),
(6, 1, 2, 6, 1, 2, 'VESPERTINO', 1, '2026-08-08 15:36:59', '2026-08-08 15:36:59'),
(7, 1, 2, 7, 1, 2, 'VESPERTINO', 1, '2026-08-08 15:37:36', '2026-08-08 15:37:36'),
(8, 1, 2, 8, 1, 2, 'VESPERTINO', 1, '2026-08-08 15:38:01', '2026-08-08 15:38:01'),
(9, 1, 2, 9, 1, 2, 'VESPERTINO', 1, '2026-08-08 15:38:17', '2026-08-08 15:38:17'),
(10, 1, 3, 10, 1, 2, 'VESPERTINO', 1, '2026-08-08 15:47:21', '2026-08-08 15:47:21'),
(11, 1, 3, 11, 1, 2, 'VESPERTINO', 1, '2026-08-08 15:47:38', '2026-08-08 15:47:38'),
(12, 1, 3, 12, 1, 2, 'VESPERTINO', 1, '2026-08-08 15:47:52', '2026-08-08 15:47:52'),
(13, 1, 3, 13, 1, 2, 'VESPERTINO', 1, '2026-08-08 15:48:05', '2026-08-08 15:48:05'),
(14, 1, 3, 14, 1, 2, 'VESPERTINO', 1, '2026-08-08 15:48:18', '2026-08-08 15:48:18'),
(15, 1, 3, 15, 1, 2, 'VESPERTINO', 1, '2026-08-08 15:48:29', '2026-08-08 15:48:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros_civiles`
--

CREATE TABLE `registros_civiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estudiante_id` bigint(20) UNSIGNED NOT NULL,
  `estado_acta` enum('DECLARADO','NO_DECLARADO','NO_DISPONIBLE') DEFAULT NULL,
  `numero_acta` varchar(50) DEFAULT NULL,
  `provincia_jce` varchar(100) DEFAULT NULL,
  `municipio_jce` varchar(100) DEFAULT NULL,
  `oficialia_jce` varchar(150) DEFAULT NULL,
  `libro` varchar(50) DEFAULT NULL,
  `folio` varchar(50) DEFAULT NULL,
  `anio` year(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `registros_civiles`
--

INSERT INTO `registros_civiles` (`id`, `estudiante_id`, `estado_acta`, `numero_acta`, `provincia_jce`, `municipio_jce`, `oficialia_jce`, `libro`, `folio`, `anio`, `created_at`, `updated_at`) VALUES
(1, 1, 'DECLARADO', '025-h', 'santiago', 'tamboril', '01', '00254', '0054', '2015', '2026-08-08 17:13:29', '2026-08-08 17:13:29'),
(2, 3, 'DECLARADO', '025-h', 'santiago', NULL, '01', '00254', '0054', '2015', '2026-08-08 19:16:29', '2026-08-08 19:16:29'),
(3, 4, 'DECLARADO', '025-h', 'santiago', 'tamboril', '01', '00254', '0054', '2015', '2026-08-15 12:38:11', '2026-08-15 12:38:11'),
(4, 5, 'DECLARADO', '025-h', 'santiago', 'tamboril', '01', '00254', '0054', '2015', '2026-08-15 15:20:38', '2026-08-15 15:20:38'),
(5, 6, 'DECLARADO', '025-h', 'santiago', 'tamboril', '01', '00254', '0054', '2015', '2026-08-15 15:26:49', '2026-08-15 15:26:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `requisitos_inscripcion`
--

CREATE TABLE `requisitos_inscripcion` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `requisitos_inscripcion`
--

INSERT INTO `requisitos_inscripcion` (`id`, `nombre`, `estado`) VALUES
(1, 'Acta de nacimiento', 1),
(2, '2 fotos 2x2', 1),
(3, 'Copia de cedula padre/madre/tutor', 1),
(4, 'Certificacion de conducta', 1),
(5, 'Certificado medico', 1),
(6, 'Record de vacunas', 1),
(7, 'Record de notas / boletin', 1),
(8, 'Certificado de conclusion de estudio', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Recursos Humanos', 'Acceso al módulo de Recursos Humanos', 1, '2026-08-08 12:58:55', '2026-08-08 12:58:55'),
(2, 'Académico', 'Acceso al módulo académico', 1, '2026-08-08 12:58:55', '2026-08-08 12:58:55'),
(3, 'Caja', 'Acceso al módulo de caja y pagos', 1, '2026-08-08 12:58:55', '2026-08-08 12:58:55'),
(4, 'Administrador', 'Acceso completo al sistema', 1, '2026-08-08 12:58:55', '2026-08-08 12:58:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_permisos`
--

CREATE TABLE `rol_permisos` (
  `rol_id` bigint(20) UNSIGNED NOT NULL,
  `permiso_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rol_permisos`
--

INSERT INTO `rol_permisos` (`rol_id`, `permiso_id`, `created_at`) VALUES
(4, 1, '2026-08-08 13:32:24'),
(4, 2, '2026-08-08 13:32:24'),
(4, 3, '2026-08-08 13:32:24'),
(4, 4, '2026-08-08 13:32:24'),
(4, 5, '2026-08-08 13:32:24'),
(4, 6, '2026-08-08 13:32:24'),
(4, 7, '2026-08-08 13:32:24'),
(4, 8, '2026-08-08 13:32:24'),
(4, 9, '2026-08-08 13:32:24'),
(4, 10, '2026-08-08 13:32:24'),
(4, 11, '2026-08-08 13:32:24'),
(4, 12, '2026-08-08 13:32:24'),
(4, 13, '2026-08-08 13:32:24'),
(4, 14, '2026-08-08 13:32:24'),
(4, 15, '2026-08-08 13:32:24'),
(4, 16, '2026-08-08 13:32:24'),
(4, 17, '2026-08-08 13:32:24'),
(4, 18, '2026-08-08 13:32:24'),
(4, 19, '2026-08-08 13:32:24'),
(4, 20, '2026-08-08 13:32:24'),
(4, 21, '2026-08-08 13:32:24'),
(4, 22, '2026-08-08 13:32:24'),
(4, 23, '2026-08-08 13:32:24'),
(4, 24, '2026-08-08 13:32:24'),
(4, 25, '2026-08-08 13:32:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones`
--

CREATE TABLE `secciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `seccion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `secciones`
--

INSERT INTO `secciones` (`id`, `seccion`) VALUES
(1, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tandas`
--

CREATE TABLE `tandas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `codigo` varchar(30) NOT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tandas`
--

INSERT INTO `tandas` (`id`, `nombre`, `codigo`, `hora_inicio`, `hora_fin`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Matutino', 'MATUTINO', '06:00:00', '12:00:00', 1, '2026-08-08 15:25:24', '2026-08-08 15:30:18'),
(2, 'Vespertino', 'VESPERTINO', '13:00:00', '18:00:00', 1, '2026-08-08 15:25:24', '2026-08-08 15:25:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarifarios`
--

CREATE TABLE `tarifarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `anio_escolar_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tarifarios`
--

INSERT INTO `tarifarios` (`id`, `anio_escolar_id`, `nombre`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 'Tarifario base 2026-08-08', 1, '2026-08-08 16:24:42', '2026-08-08 16:24:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarifas_grados`
--

CREATE TABLE `tarifas_grados` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tarifario_id` bigint(20) UNSIGNED NOT NULL,
  `nivel_id` bigint(20) UNSIGNED NOT NULL,
  `grado_id` bigint(20) UNSIGNED NOT NULL,
  `jornada` enum('MATUTINO','VESPERTINO') NOT NULL,
  `tarifa_inscripcion` decimal(10,2) NOT NULL DEFAULT 0.00,
  `mensualidad` decimal(10,2) NOT NULL DEFAULT 0.00,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tarifas_grados`
--

INSERT INTO `tarifas_grados` (`id`, `tarifario_id`, `nivel_id`, `grado_id`, `jornada`, `tarifa_inscripcion`, `mensualidad`, `activo`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'VESPERTINO', 2500.00, 2500.00, 1, '2026-08-08 16:24:42', '2026-08-08 16:24:42'),
(2, 1, 1, 2, 'VESPERTINO', 2500.00, 2500.00, 1, '2026-08-08 16:25:01', '2026-08-08 16:25:01'),
(3, 1, 1, 3, 'VESPERTINO', 2500.00, 2500.00, 1, '2026-08-08 16:25:20', '2026-08-08 16:25:20'),
(4, 1, 2, 4, 'VESPERTINO', 2500.00, 2500.00, 1, '2026-08-08 16:25:45', '2026-08-08 16:25:45'),
(5, 1, 2, 5, 'VESPERTINO', 2500.00, 2500.00, 1, '2026-08-08 16:26:23', '2026-08-08 16:26:23'),
(6, 1, 2, 6, 'VESPERTINO', 2500.00, 2500.00, 1, '2026-08-08 16:26:36', '2026-08-08 16:26:36'),
(7, 1, 2, 7, 'VESPERTINO', 2800.00, 2800.00, 1, '2026-08-08 16:26:59', '2026-08-08 16:26:59'),
(8, 1, 2, 8, 'VESPERTINO', 2800.00, 2800.00, 1, '2026-08-08 16:27:16', '2026-08-08 16:27:16'),
(9, 1, 2, 9, 'VESPERTINO', 2800.00, 2800.00, 1, '2026-08-08 16:27:37', '2026-08-08 16:27:37'),
(10, 1, 3, 10, 'VESPERTINO', 3800.00, 3800.00, 1, '2026-08-08 16:28:04', '2026-08-08 16:28:04'),
(11, 1, 3, 11, 'VESPERTINO', 3800.00, 3800.00, 1, '2026-08-08 16:28:22', '2026-08-08 16:28:22'),
(12, 1, 3, 12, 'VESPERTINO', 4000.00, 4000.00, 1, '2026-08-08 16:28:44', '2026-08-08 16:28:44'),
(13, 1, 3, 13, 'VESPERTINO', 4000.00, 4000.00, 1, '2026-08-08 16:28:57', '2026-08-08 16:28:57'),
(14, 1, 3, 14, 'VESPERTINO', 4000.00, 4000.00, 1, '2026-08-08 16:29:12', '2026-08-08 16:29:12'),
(15, 1, 3, 15, 'VESPERTINO', 4000.00, 4000.00, 1, '2026-08-08 16:29:27', '2026-08-08 16:29:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre_completo` varchar(150) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `ultimo_acceso` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_completo`, `correo`, `contrasena`, `estado`, `ultimo_acceso`, `created_at`, `updated_at`) VALUES
(1, 'Asmelvin Bolivar Vasquez Germosen', 'asmelvin@gmail.com', '$2y$10$r5z.v2jnJoIrRVGHWhWeD.6M6k8kl1uzfZujV8Z5wo5e8lbTCcwKO', 1, '2026-08-08 17:06:04', '2026-08-08 14:54:02', '2026-08-17 12:21:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_roles`
--

CREATE TABLE `usuario_roles` (
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `rol_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario_roles`
--

INSERT INTO `usuario_roles` (`usuario_id`, `rol_id`, `created_at`) VALUES
(1, 4, '2026-08-08 14:54:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacunas`
--

CREATE TABLE `vacunas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `vacunas`
--

INSERT INTO `vacunas` (`id`, `nombre`, `estado`) VALUES
(1, 'Tuberculosis', 1),
(2, 'Tosferina', 1),
(3, 'Difteria / Tetano 1', 1),
(4, 'Difteria / Tetano 2', 1),
(5, 'Antipolio 1', 1),
(6, 'Antipolio 2', 1),
(7, 'Antipolio Refuerzo', 1),
(8, 'Antisarampion 1', 1),
(9, 'Antisarampion Refuerzo', 1),
(10, 'Meningitis', 1),
(11, 'Hepatitis 1', 1),
(12, 'Hepatitis 2', 1),
(13, 'Hepatitis 3', 1),
(14, 'Difteria / Tetano DT 1', 1),
(15, 'Difteria / Tetano DT 2', 1),
(16, 'Difteria / Tetano DT 3', 1),
(17, 'Difteria / Tetano DT 3 Refuerzo', 1),
(18, 'Gripe AH1N1', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `anios_escolares`
--
ALTER TABLE `anios_escolares`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_anio_escolar_nombre` (`nombre`);

--
-- Indices de la tabla `asignaciones_laborales`
--
ALTER TABLE `asignaciones_laborales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_personal_anio` (`personal_id`,`anio_escolar_id`),
  ADD KEY `idx_asignacion_personal` (`personal_id`),
  ADD KEY `idx_asignacion_anio` (`anio_escolar_id`),
  ADD KEY `idx_asignacion_departamento` (`departamento_id`),
  ADD KEY `idx_asignacion_cargo` (`cargo_id`),
  ADD KEY `idx_asignacion_condicion` (`condicion_laboral_id`);

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_auditoria_usuario` (`usuario_id`),
  ADD KEY `idx_auditoria_modulo` (`modulo`),
  ADD KEY `idx_auditoria_accion` (`accion`),
  ADD KEY `idx_auditoria_tabla` (`tabla`),
  ADD KEY `idx_auditoria_registro` (`registro_id`),
  ADD KEY `idx_auditoria_fecha` (`created_at`);

--
-- Indices de la tabla `cajas`
--
ALTER TABLE `cajas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_caja_codigo` (`codigo`),
  ADD UNIQUE KEY `uk_caja_nombre` (`nombre`),
  ADD KEY `idx_caja_estado` (`estado`);

--
-- Indices de la tabla `caja_sesiones`
--
ALTER TABLE `caja_sesiones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_caja_sesion_caja` (`caja_id`),
  ADD KEY `idx_caja_sesion_apertura` (`usuario_apertura_id`),
  ADD KEY `idx_caja_sesion_cierre` (`usuario_cierre_id`),
  ADD KEY `idx_caja_sesion_fecha` (`fecha_apertura`),
  ADD KEY `idx_caja_sesion_estado` (`estado`);

--
-- Indices de la tabla `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_cargo_nombre` (`nombre`);

--
-- Indices de la tabla `cargos_estudiantes`
--
ALTER TABLE `cargos_estudiantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_cargo_periodo_concepto` (`estudiante_id`,`concepto_id`,`periodo_id`),
  ADD KEY `idx_cargo_estudiante` (`estudiante_id`),
  ADD KEY `idx_cargo_inscripcion` (`inscripcion_id`),
  ADD KEY `idx_cargo_concepto` (`concepto_id`),
  ADD KEY `idx_cargo_periodo` (`periodo_id`),
  ADD KEY `idx_cargo_estado` (`estado`);

--
-- Indices de la tabla `conceptos_cobro`
--
ALTER TABLE `conceptos_cobro`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_concepto_codigo` (`codigo`),
  ADD UNIQUE KEY `uk_concepto_nombre` (`nombre`);

--
-- Indices de la tabla `conceptos_nomina`
--
ALTER TABLE `conceptos_nomina`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_concepto_nomina_nombre` (`nombre`);

--
-- Indices de la tabla `condiciones_laborales`
--
ALTER TABLE `condiciones_laborales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_condicion_nombre` (`nombre`);

--
-- Indices de la tabla `cuentas_bancarias`
--
ALTER TABLE `cuentas_bancarias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_cuenta_bancaria_numero` (`numero_cuenta`),
  ADD KEY `idx_cuenta_banco` (`banco`),
  ADD KEY `idx_cuenta_estado` (`estado`);

--
-- Indices de la tabla `datos_centro_educativo`
--
ALTER TABLE `datos_centro_educativo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `datos_laborales`
--
ALTER TABLE `datos_laborales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_datos_laborales_personal` (`personal_id`);

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_departamento_nombre` (`nombre`);

--
-- Indices de la tabla `direcciones_estudiantes`
--
ALTER TABLE `direcciones_estudiantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_direccion_estudiante` (`estudiante_id`);

--
-- Indices de la tabla `direcciones_personal`
--
ALTER TABLE `direcciones_personal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_direccion_personal` (`personal_id`);

--
-- Indices de la tabla `discapacidades`
--
ALTER TABLE `discapacidades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_discapacidad_nombre` (`nombre`);

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_documento_tipo` (`tipo_documento`),
  ADD KEY `idx_documento_usuario` (`usuario_id`),
  ADD KEY `idx_documento_estado` (`estado`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_estudiante_sigerd` (`id_sigerd`);

--
-- Indices de la tabla `estudiante_discapacidades`
--
ALTER TABLE `estudiante_discapacidades`
  ADD PRIMARY KEY (`estudiante_id`,`discapacidad_id`),
  ADD KEY `idx_ed_discapacidad` (`discapacidad_id`);

--
-- Indices de la tabla `estudiante_documentos`
--
ALTER TABLE `estudiante_documentos`
  ADD PRIMARY KEY (`estudiante_id`,`documento_id`),
  ADD KEY `idx_est_doc_documento` (`documento_id`),
  ADD KEY `idx_est_doc_tipo` (`tipo`);

--
-- Indices de la tabla `estudiante_familiares`
--
ALTER TABLE `estudiante_familiares`
  ADD PRIMARY KEY (`estudiante_id`,`familiar_id`),
  ADD KEY `idx_ef_familiar` (`familiar_id`);

--
-- Indices de la tabla `estudiante_vacunas`
--
ALTER TABLE `estudiante_vacunas`
  ADD PRIMARY KEY (`estudiante_id`,`vacuna_id`),
  ADD KEY `idx_ev_vacuna` (`vacuna_id`);

--
-- Indices de la tabla `estudios_concluidos`
--
ALTER TABLE `estudios_concluidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_estudios_concluidos_personal` (`personal_id`);

--
-- Indices de la tabla `estudios_proceso`
--
ALTER TABLE `estudios_proceso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_estudios_proceso_personal` (`personal_id`);

--
-- Indices de la tabla `familiares`
--
ALTER TABLE `familiares`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `grados`
--
ALTER TABLE `grados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_academico`
--
ALTER TABLE `historial_academico`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_historial_estudiante_anio` (`estudiante_id`,`anio_escolar_id`),
  ADD KEY `idx_historial_estudiante` (`estudiante_id`),
  ADD KEY `idx_historial_anio` (`anio_escolar_id`),
  ADD KEY `idx_historial_nivel` (`nivel_id`),
  ADD KEY `idx_historial_grado` (`grado_id`),
  ADD KEY `idx_historial_seccion` (`seccion_id`);

--
-- Indices de la tabla `historial_salarios`
--
ALTER TABLE `historial_salarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_historial_salario_personal` (`personal_id`),
  ADD KEY `idx_historial_salario_usuario` (`usuario_id`),
  ADD KEY `idx_historial_salario_fecha` (`fecha_inicio`);

--
-- Indices de la tabla `incidencias_nomina`
--
ALTER TABLE `incidencias_nomina`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_incidencia_personal` (`personal_id`),
  ADD KEY `idx_incidencia_fecha` (`fecha`),
  ADD KEY `idx_incidencia_usuario` (`usuario_id`);

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_inscripcion_estudiante` (`estudiante_id`),
  ADD KEY `idx_inscripcion_planificacion` (`planificacion_academica_id`);

--
-- Indices de la tabla `inscripcion_requisitos`
--
ALTER TABLE `inscripcion_requisitos`
  ADD PRIMARY KEY (`inscripcion_id`,`requisito_id`),
  ADD KEY `idx_ir_requisito` (`requisito_id`);

--
-- Indices de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_metodo_pago_nombre` (`nombre`),
  ADD UNIQUE KEY `uk_metodo_pago_codigo` (`codigo`);

--
-- Indices de la tabla `movimientos_bancarios`
--
ALTER TABLE `movimientos_bancarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mov_banco_cuenta` (`cuenta_bancaria_id`),
  ADD KEY `idx_mov_banco_fecha` (`fecha_movimiento`),
  ADD KEY `idx_mov_banco_pago` (`pago_id`),
  ADD KEY `idx_mov_banco_usuario` (`usuario_id`),
  ADD KEY `idx_mov_banco_estado` (`estado`);

--
-- Indices de la tabla `movimientos_caja`
--
ALTER TABLE `movimientos_caja`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_movimiento_caja_sesion` (`caja_sesion_id`),
  ADD KEY `idx_movimiento_tipo` (`tipo`),
  ADD KEY `idx_movimiento_fecha` (`fecha_movimiento`),
  ADD KEY `idx_movimiento_pago` (`pago_id`),
  ADD KEY `idx_movimiento_usuario` (`usuario_id`),
  ADD KEY `idx_movimiento_estado` (`estado`);

--
-- Indices de la tabla `niveles`
--
ALTER TABLE `niveles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_nivel_nombre` (`nivel`);

--
-- Indices de la tabla `nominas`
--
ALTER TABLE `nominas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_nomina_numero` (`numero_nomina`),
  ADD KEY `idx_nomina_periodo` (`periodo_nomina_id`),
  ADD KEY `idx_nomina_usuario` (`usuario_id`);

--
-- Indices de la tabla `nomina_detalles`
--
ALTER TABLE `nomina_detalles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_nomina_personal` (`nomina_id`,`personal_id`),
  ADD KEY `idx_nomina_detalle_nomina` (`nomina_id`),
  ADD KEY `idx_nomina_detalle_personal` (`personal_id`);

--
-- Indices de la tabla `nomina_detalle_conceptos`
--
ALTER TABLE `nomina_detalle_conceptos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ndc_detalle` (`nomina_detalle_id`),
  ADD KEY `idx_ndc_concepto` (`concepto_id`);

--
-- Indices de la tabla `nomina_historial`
--
ALTER TABLE `nomina_historial`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_nomina_historial_nomina` (`nomina_id`),
  ADD KEY `idx_nomina_historial_usuario` (`usuario_id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_pago_recibo` (`numero_recibo`),
  ADD KEY `idx_pago_estudiante` (`estudiante_id`),
  ADD KEY `idx_pago_fecha` (`fecha_pago`),
  ADD KEY `idx_pago_metodo` (`metodo_pago_id`),
  ADD KEY `fk_pago_usuario` (`usuario_id`);

--
-- Indices de la tabla `pagos_nomina`
--
ALTER TABLE `pagos_nomina`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pago_nomina_detalle` (`nomina_detalle_id`),
  ADD KEY `idx_pago_nomina_usuario` (`usuario_id`);

--
-- Indices de la tabla `pago_detalles`
--
ALTER TABLE `pago_detalles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_pago_cargo` (`pago_id`,`cargo_id`),
  ADD KEY `idx_pago_detalle_cargo` (`cargo_id`);

--
-- Indices de la tabla `parametros_financieros`
--
ALTER TABLE `parametros_financieros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_parametros_financieros_anio` (`anio_escolar_id`);

--
-- Indices de la tabla `periodos_cobro`
--
ALTER TABLE `periodos_cobro`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_periodo_anio_mes` (`anio_escolar_id`,`numero_mes`);

--
-- Indices de la tabla `periodos_nomina`
--
ALTER TABLE `periodos_nomina`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_periodo_nomina` (`fecha_inicio`,`fecha_fin`),
  ADD KEY `idx_periodo_nomina_anio` (`anio_escolar_id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_permiso_codigo` (`codigo`),
  ADD UNIQUE KEY `uk_permiso_nombre_modulo` (`modulo`,`nombre`),
  ADD KEY `idx_permiso_modulo` (`modulo`),
  ADD KEY `idx_permiso_estado` (`estado`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_personal_documento` (`cedula_pasaporte`);

--
-- Indices de la tabla `personal_documentos`
--
ALTER TABLE `personal_documentos`
  ADD PRIMARY KEY (`personal_id`,`documento_id`),
  ADD KEY `idx_per_doc_documento` (`documento_id`),
  ADD KEY `idx_per_doc_tipo` (`tipo`);

--
-- Indices de la tabla `planificaciones_academicas`
--
ALTER TABLE `planificaciones_academicas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_planificacion` (`anio_escolar_id`,`nivel_id`,`grado_id`,`seccion_id`,`jornada`),
  ADD UNIQUE KEY `uq_planif_anio_nivel_grado_seccion_tanda` (`anio_escolar_id`,`nivel_id`,`grado_id`,`seccion_id`,`tanda_id`),
  ADD KEY `idx_planificacion_anio` (`anio_escolar_id`),
  ADD KEY `idx_planificacion_nivel` (`nivel_id`),
  ADD KEY `idx_planificacion_grado` (`grado_id`),
  ADD KEY `idx_planificacion_seccion` (`seccion_id`),
  ADD KEY `idx_planif_tanda_id` (`tanda_id`);

--
-- Indices de la tabla `registros_civiles`
--
ALTER TABLE `registros_civiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_registro_estudiante` (`estudiante_id`);

--
-- Indices de la tabla `requisitos_inscripcion`
--
ALTER TABLE `requisitos_inscripcion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_requisito_nombre` (`nombre`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_roles_nombre` (`nombre`);

--
-- Indices de la tabla `rol_permisos`
--
ALTER TABLE `rol_permisos`
  ADD PRIMARY KEY (`rol_id`,`permiso_id`),
  ADD KEY `idx_rol_permisos_permiso` (`permiso_id`);

--
-- Indices de la tabla `secciones`
--
ALTER TABLE `secciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_seccion_nombre` (`seccion`);

--
-- Indices de la tabla `tandas`
--
ALTER TABLE `tandas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_tandas_nombre` (`nombre`),
  ADD UNIQUE KEY `uq_tandas_codigo` (`codigo`),
  ADD KEY `idx_tandas_estado` (`estado`);

--
-- Indices de la tabla `tarifarios`
--
ALTER TABLE `tarifarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_tarifario_anio_nombre` (`anio_escolar_id`,`nombre`),
  ADD KEY `idx_tarifario_anio` (`anio_escolar_id`);

--
-- Indices de la tabla `tarifas_grados`
--
ALTER TABLE `tarifas_grados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_tarifa_grado` (`tarifario_id`,`nivel_id`,`grado_id`,`jornada`),
  ADD KEY `idx_tarifa_nivel` (`nivel_id`),
  ADD KEY `idx_tarifa_grado` (`grado_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_usuarios_correo` (`correo`);

--
-- Indices de la tabla `usuario_roles`
--
ALTER TABLE `usuario_roles`
  ADD PRIMARY KEY (`usuario_id`,`rol_id`),
  ADD KEY `idx_usuario_roles_rol` (`rol_id`);

--
-- Indices de la tabla `vacunas`
--
ALTER TABLE `vacunas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_vacuna_nombre` (`nombre`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `anios_escolares`
--
ALTER TABLE `anios_escolares`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `asignaciones_laborales`
--
ALTER TABLE `asignaciones_laborales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=552;

--
-- AUTO_INCREMENT de la tabla `cajas`
--
ALTER TABLE `cajas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `caja_sesiones`
--
ALTER TABLE `caja_sesiones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cargos_estudiantes`
--
ALTER TABLE `cargos_estudiantes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conceptos_cobro`
--
ALTER TABLE `conceptos_cobro`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `conceptos_nomina`
--
ALTER TABLE `conceptos_nomina`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `condiciones_laborales`
--
ALTER TABLE `condiciones_laborales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `cuentas_bancarias`
--
ALTER TABLE `cuentas_bancarias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `datos_centro_educativo`
--
ALTER TABLE `datos_centro_educativo`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `datos_laborales`
--
ALTER TABLE `datos_laborales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `direcciones_estudiantes`
--
ALTER TABLE `direcciones_estudiantes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `direcciones_personal`
--
ALTER TABLE `direcciones_personal`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `discapacidades`
--
ALTER TABLE `discapacidades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `estudios_concluidos`
--
ALTER TABLE `estudios_concluidos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estudios_proceso`
--
ALTER TABLE `estudios_proceso`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `familiares`
--
ALTER TABLE `familiares`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `grados`
--
ALTER TABLE `grados`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `historial_academico`
--
ALTER TABLE `historial_academico`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `historial_salarios`
--
ALTER TABLE `historial_salarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `incidencias_nomina`
--
ALTER TABLE `incidencias_nomina`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `movimientos_bancarios`
--
ALTER TABLE `movimientos_bancarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `movimientos_caja`
--
ALTER TABLE `movimientos_caja`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `niveles`
--
ALTER TABLE `niveles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `nominas`
--
ALTER TABLE `nominas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `nomina_detalles`
--
ALTER TABLE `nomina_detalles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `nomina_detalle_conceptos`
--
ALTER TABLE `nomina_detalle_conceptos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `nomina_historial`
--
ALTER TABLE `nomina_historial`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pagos_nomina`
--
ALTER TABLE `pagos_nomina`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pago_detalles`
--
ALTER TABLE `pago_detalles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `parametros_financieros`
--
ALTER TABLE `parametros_financieros`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `periodos_cobro`
--
ALTER TABLE `periodos_cobro`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `periodos_nomina`
--
ALTER TABLE `periodos_nomina`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `planificaciones_academicas`
--
ALTER TABLE `planificaciones_academicas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `registros_civiles`
--
ALTER TABLE `registros_civiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `requisitos_inscripcion`
--
ALTER TABLE `requisitos_inscripcion`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `secciones`
--
ALTER TABLE `secciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tandas`
--
ALTER TABLE `tandas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tarifarios`
--
ALTER TABLE `tarifarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tarifas_grados`
--
ALTER TABLE `tarifas_grados`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `vacunas`
--
ALTER TABLE `vacunas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignaciones_laborales`
--
ALTER TABLE `asignaciones_laborales`
  ADD CONSTRAINT `fk_asignacion_anio` FOREIGN KEY (`anio_escolar_id`) REFERENCES `anios_escolares` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asignacion_cargo` FOREIGN KEY (`cargo_id`) REFERENCES `cargos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asignacion_condicion` FOREIGN KEY (`condicion_laboral_id`) REFERENCES `condiciones_laborales` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asignacion_departamento` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asignacion_personal` FOREIGN KEY (`personal_id`) REFERENCES `personal` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD CONSTRAINT `fk_auditoria_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `caja_sesiones`
--
ALTER TABLE `caja_sesiones`
  ADD CONSTRAINT `fk_caja_sesion_caja` FOREIGN KEY (`caja_id`) REFERENCES `cajas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_caja_sesion_usuario_apertura` FOREIGN KEY (`usuario_apertura_id`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_caja_sesion_usuario_cierre` FOREIGN KEY (`usuario_cierre_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `cargos_estudiantes`
--
ALTER TABLE `cargos_estudiantes`
  ADD CONSTRAINT `fk_cargo_concepto` FOREIGN KEY (`concepto_id`) REFERENCES `conceptos_cobro` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cargo_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cargo_inscripcion` FOREIGN KEY (`inscripcion_id`) REFERENCES `inscripciones` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cargo_periodo` FOREIGN KEY (`periodo_id`) REFERENCES `periodos_cobro` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `datos_laborales`
--
ALTER TABLE `datos_laborales`
  ADD CONSTRAINT `fk_datos_laborales_personal` FOREIGN KEY (`personal_id`) REFERENCES `personal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `direcciones_estudiantes`
--
ALTER TABLE `direcciones_estudiantes`
  ADD CONSTRAINT `fk_direccion_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `direcciones_personal`
--
ALTER TABLE `direcciones_personal`
  ADD CONSTRAINT `fk_direccion_personal` FOREIGN KEY (`personal_id`) REFERENCES `personal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `fk_documento_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiante_discapacidades`
--
ALTER TABLE `estudiante_discapacidades`
  ADD CONSTRAINT `fk_ed_discapacidad` FOREIGN KEY (`discapacidad_id`) REFERENCES `discapacidades` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ed_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiante_documentos`
--
ALTER TABLE `estudiante_documentos`
  ADD CONSTRAINT `fk_est_doc_documento` FOREIGN KEY (`documento_id`) REFERENCES `documentos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_est_doc_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiante_familiares`
--
ALTER TABLE `estudiante_familiares`
  ADD CONSTRAINT `fk_ef_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ef_familiar` FOREIGN KEY (`familiar_id`) REFERENCES `familiares` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiante_vacunas`
--
ALTER TABLE `estudiante_vacunas`
  ADD CONSTRAINT `fk_ev_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ev_vacuna` FOREIGN KEY (`vacuna_id`) REFERENCES `vacunas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudios_concluidos`
--
ALTER TABLE `estudios_concluidos`
  ADD CONSTRAINT `fk_estudios_concluidos_personal` FOREIGN KEY (`personal_id`) REFERENCES `personal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudios_proceso`
--
ALTER TABLE `estudios_proceso`
  ADD CONSTRAINT `fk_estudios_proceso_personal` FOREIGN KEY (`personal_id`) REFERENCES `personal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial_academico`
--
ALTER TABLE `historial_academico`
  ADD CONSTRAINT `fk_historial_anio` FOREIGN KEY (`anio_escolar_id`) REFERENCES `anios_escolares` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_historial_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_historial_grado` FOREIGN KEY (`grado_id`) REFERENCES `grados` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_historial_nivel` FOREIGN KEY (`nivel_id`) REFERENCES `niveles` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_historial_seccion` FOREIGN KEY (`seccion_id`) REFERENCES `secciones` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial_salarios`
--
ALTER TABLE `historial_salarios`
  ADD CONSTRAINT `fk_historial_salario_personal` FOREIGN KEY (`personal_id`) REFERENCES `personal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_historial_salario_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `incidencias_nomina`
--
ALTER TABLE `incidencias_nomina`
  ADD CONSTRAINT `fk_incidencia_personal` FOREIGN KEY (`personal_id`) REFERENCES `personal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_incidencia_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `fk_inscripcion_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inscripcion_planificacion` FOREIGN KEY (`planificacion_academica_id`) REFERENCES `planificaciones_academicas` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `inscripcion_requisitos`
--
ALTER TABLE `inscripcion_requisitos`
  ADD CONSTRAINT `fk_ir_inscripcion` FOREIGN KEY (`inscripcion_id`) REFERENCES `inscripciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ir_requisito` FOREIGN KEY (`requisito_id`) REFERENCES `requisitos_inscripcion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `movimientos_bancarios`
--
ALTER TABLE `movimientos_bancarios`
  ADD CONSTRAINT `fk_mov_banco_cuenta` FOREIGN KEY (`cuenta_bancaria_id`) REFERENCES `cuentas_bancarias` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mov_banco_pago` FOREIGN KEY (`pago_id`) REFERENCES `pagos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mov_banco_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `movimientos_caja`
--
ALTER TABLE `movimientos_caja`
  ADD CONSTRAINT `fk_movimiento_caja_pago` FOREIGN KEY (`pago_id`) REFERENCES `pagos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_movimiento_caja_sesion` FOREIGN KEY (`caja_sesion_id`) REFERENCES `caja_sesiones` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_movimiento_caja_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `nominas`
--
ALTER TABLE `nominas`
  ADD CONSTRAINT `fk_nomina_periodo` FOREIGN KEY (`periodo_nomina_id`) REFERENCES `periodos_nomina` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nomina_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `nomina_detalles`
--
ALTER TABLE `nomina_detalles`
  ADD CONSTRAINT `fk_nomina_detalle_nomina` FOREIGN KEY (`nomina_id`) REFERENCES `nominas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nomina_detalle_personal` FOREIGN KEY (`personal_id`) REFERENCES `personal` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `nomina_detalle_conceptos`
--
ALTER TABLE `nomina_detalle_conceptos`
  ADD CONSTRAINT `fk_ndc_concepto` FOREIGN KEY (`concepto_id`) REFERENCES `conceptos_nomina` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ndc_detalle` FOREIGN KEY (`nomina_detalle_id`) REFERENCES `nomina_detalles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `nomina_historial`
--
ALTER TABLE `nomina_historial`
  ADD CONSTRAINT `fk_nomina_historial_nomina` FOREIGN KEY (`nomina_id`) REFERENCES `nominas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nomina_historial_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `fk_pago_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pago_metodo` FOREIGN KEY (`metodo_pago_id`) REFERENCES `metodos_pago` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pago_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos_nomina`
--
ALTER TABLE `pagos_nomina`
  ADD CONSTRAINT `fk_pago_nomina_detalle` FOREIGN KEY (`nomina_detalle_id`) REFERENCES `nomina_detalles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pago_nomina_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `pago_detalles`
--
ALTER TABLE `pago_detalles`
  ADD CONSTRAINT `fk_pago_detalle_cargo` FOREIGN KEY (`cargo_id`) REFERENCES `cargos_estudiantes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pago_detalle_pago` FOREIGN KEY (`pago_id`) REFERENCES `pagos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `parametros_financieros`
--
ALTER TABLE `parametros_financieros`
  ADD CONSTRAINT `fk_parametros_financieros_anio` FOREIGN KEY (`anio_escolar_id`) REFERENCES `anios_escolares` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `periodos_cobro`
--
ALTER TABLE `periodos_cobro`
  ADD CONSTRAINT `fk_periodo_anio` FOREIGN KEY (`anio_escolar_id`) REFERENCES `anios_escolares` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `periodos_nomina`
--
ALTER TABLE `periodos_nomina`
  ADD CONSTRAINT `fk_periodo_nomina_anio` FOREIGN KEY (`anio_escolar_id`) REFERENCES `anios_escolares` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `personal_documentos`
--
ALTER TABLE `personal_documentos`
  ADD CONSTRAINT `fk_per_doc_documento` FOREIGN KEY (`documento_id`) REFERENCES `documentos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_per_doc_personal` FOREIGN KEY (`personal_id`) REFERENCES `personal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `planificaciones_academicas`
--
ALTER TABLE `planificaciones_academicas`
  ADD CONSTRAINT `fk_planif_tanda` FOREIGN KEY (`tanda_id`) REFERENCES `tandas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_planificacion_anio` FOREIGN KEY (`anio_escolar_id`) REFERENCES `anios_escolares` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_planificacion_grado` FOREIGN KEY (`grado_id`) REFERENCES `grados` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_planificacion_nivel` FOREIGN KEY (`nivel_id`) REFERENCES `niveles` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_planificacion_seccion` FOREIGN KEY (`seccion_id`) REFERENCES `secciones` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `registros_civiles`
--
ALTER TABLE `registros_civiles`
  ADD CONSTRAINT `fk_registro_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rol_permisos`
--
ALTER TABLE `rol_permisos`
  ADD CONSTRAINT `fk_rol_permisos_permiso` FOREIGN KEY (`permiso_id`) REFERENCES `permisos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rol_permisos_rol` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tarifarios`
--
ALTER TABLE `tarifarios`
  ADD CONSTRAINT `fk_tarifario_anio` FOREIGN KEY (`anio_escolar_id`) REFERENCES `anios_escolares` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tarifas_grados`
--
ALTER TABLE `tarifas_grados`
  ADD CONSTRAINT `fk_tarifa_grado_grado` FOREIGN KEY (`grado_id`) REFERENCES `grados` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tarifa_grado_nivel` FOREIGN KEY (`nivel_id`) REFERENCES `niveles` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tarifa_grado_tarifario` FOREIGN KEY (`tarifario_id`) REFERENCES `tarifarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario_roles`
--
ALTER TABLE `usuario_roles`
  ADD CONSTRAINT `fk_usuario_roles_rol` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuario_roles_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
