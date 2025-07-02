-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-07-2025 a las 00:03:22
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
-- Base de datos: `tickets_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area`
--

CREATE TABLE `area` (
  `id_area` int(11) NOT NULL,
  `nombre_area` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `area`
--

INSERT INTO `area` (`id_area`, `nombre_area`) VALUES
(1, 'Sistemas'),
(2, 'Mantenimiento'),
(3, 'Recepción'),
(4, 'Cocina'),
(5, 'Ama de Llaves'),
(6, 'Ventas'),
(7, 'Banquetes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket`
--

CREATE TABLE `ticket` (
  `id_ticket` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `prioridad` enum('Baja','Media','Alta') NOT NULL,
  `estado` enum('Pendiente','En proceso','Realizado') NOT NULL DEFAULT 'Pendiente',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_usuario` int(11) DEFAULT NULL,
  `id_area` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ticket`
--

INSERT INTO `ticket` (`id_ticket`, `titulo`, `descripcion`, `prioridad`, `estado`, `fecha_creacion`, `id_usuario`, `id_area`) VALUES
(3, 'No jalan los baños', 'E we no jalan los baños', 'Media', 'Realizado', '2025-06-30 20:13:04', 9, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('Administrador','JefeArea','Usuario') NOT NULL,
  `id_area` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `correo`, `password`, `rol`, `id_area`) VALUES
(9, 'Admin Principal', 'admin@demo.com', 'admin123', 'Administrador', NULL),
(10, 'Jefe Sistemas', 'jefe@demo.com', 'jefe123', 'JefeArea', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id_area`),
  ADD UNIQUE KEY `nombre_area` (`nombre_area`);

--
-- Indices de la tabla `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`id_ticket`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_area` (`id_area`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `id_area` (`id_area`);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset`
--

CREATE TABLE `password_reset` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `area`
--
ALTER TABLE `area`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id_ticket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `password_reset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `ticket_ibfk_2` FOREIGN KEY (`id_area`) REFERENCES `area` (`id_area`);
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_area`) REFERENCES `area` (`id_area`);

ALTER TABLE `password_reset`
  ADD CONSTRAINT `password_reset_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
