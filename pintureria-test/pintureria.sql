-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-06-2025 a las 17:42:39
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
-- Base de datos: `pintureria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `idcategorias` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`idcategorias`, `descripcion`) VALUES
(1, 'Hogar'),
(2, 'Automotor'),
(3, 'Insumos'),
(4, 'Látex interior'),
(5, 'Látex exterior'),
(6, 'Esmalte sintético'),
(7, 'Impermeabilizante'),
(8, 'Aerosol');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idCliente` int(11) NOT NULL,
  `NombreApellido` varchar(100) DEFAULT NULL,
  `Dni` varchar(20) DEFAULT NULL,
  `Direccion` varchar(150) DEFAULT NULL,
  `CuentaCorriente` varchar(45) DEFAULT NULL,
  `Mail` varchar(100) DEFAULT NULL,
  `NTelefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idCliente`, `NombreApellido`, `Dni`, `Direccion`, `CuentaCorriente`, `Mail`, `NTelefono`) VALUES
(1, 'Juan Pérez', '30123456', 'Calle Falsa 123', 'SI', 'juanperez@mail.com', '1112345678'),
(2, 'Consumidor Final', NULL, NULL, 'NO', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `color`
--

CREATE TABLE `color` (
  `idcolor` int(11) NOT NULL,
  `codigoDescripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `color`
--

INSERT INTO `color` (`idcolor`, `codigoDescripcion`) VALUES
(1, 'Rojo Carmín'),
(2, 'Blanco Nieve'),
(3, 'Negro Azabache'),
(4, 'Blanco'),
(5, 'Negro'),
(6, 'Rojo'),
(7, 'Verde'),
(8, 'Azul'),
(9, 'rojo fiesta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `linea`
--

CREATE TABLE `linea` (
  `idlinea` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `linea`
--

INSERT INTO `linea` (`idlinea`, `descripcion`) VALUES
(1, 'Sintética'),
(2, 'Acrílica'),
(3, 'Al agua'),
(4, 'Premium'),
(5, 'Económica'),
(6, 'Industrial'),
(7, 'sintetico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presentacion`
--

CREATE TABLE `presentacion` (
  `idpresentacion` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `presentacion`
--

INSERT INTO `presentacion` (`idpresentacion`, `descripcion`) VALUES
(1, '1 Litro'),
(2, '4 Litros'),
(3, '10 Litros'),
(4, '4L');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idproducto` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `idcategorias` int(11) NOT NULL,
  `idlinea` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idproducto`, `descripcion`, `idcategorias`, `idlinea`, `precio`) VALUES
(1, 'Tersuave Latex Interior', 1, 3, 15000.00),
(2, 'Sinteplast Esmalte Sintético', 2, 1, 18000.00),
(3, 'Pintura Acrílica ', 1, 2, 12000.00),
(4, 'Pintura Interior 4L', 1, 1, 5200.00),
(5, 'Pintura Exterior 10L', 2, 1, 9800.00),
(6, 'Esmalte 1L', 3, 2, 4300.00),
(7, 'Impermeabilizante 20L', 4, 3, 12500.00),
(8, 'Spray 400ml', 5, 2, 2900.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_has_presentacion`
--

CREATE TABLE `productos_has_presentacion` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `presentacion_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_has_presentacion`
--

INSERT INTO `productos_has_presentacion` (`id`, `producto_id`, `presentacion_id`) VALUES
(1, 1, 2),
(2, 2, 1),
(3, 3, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_has_ventas`
--

CREATE TABLE `productos_has_ventas` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_has_ventas`
--

INSERT INTO `productos_has_ventas` (`id`, `producto_id`, `venta_id`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 1, 2, 15000.00),
(2, 3, 2, 1, 12000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `idstock` int(11) NOT NULL,
  `idproducto` int(11) NOT NULL,
  `idcolor` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 0,
  `fecha_carga` datetime DEFAULT current_timestamp(),
  `lote` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock`
--

INSERT INTO `stock` (`idstock`, `idproducto`, `idcolor`, `cantidad`, `fecha_carga`, `lote`) VALUES
(6, 1, 1, 10, '2025-06-12 10:22:08', 0),
(7, 2, 3, 8, '2025-06-12 10:22:08', 0),
(8, 3, 2, 15, '2025-06-12 10:22:08', 0),
(9, 4, 4, 6, '2025-06-12 10:22:08', 0),
(10, 5, 5, 20, '2025-06-12 10:22:08', 0),
(11, 3, 1, 7, '2025-06-12 12:41:56', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `idventa` int(11) NOT NULL,
  `Cliente_idCliente` int(11) DEFAULT NULL,
  `tipocomprobante` varchar(45) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `montosinimp` decimal(10,2) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`idventa`, `Cliente_idCliente`, `tipocomprobante`, `fecha`, `montosinimp`, `monto`) VALUES
(1, 1, 'Factura A', '2025-06-11 21:32:03', 30000.00, 36300.00),
(2, 2, 'Ticket', '2025-06-11 21:32:03', 12000.00, 14520.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`idcategorias`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idCliente`);

--
-- Indices de la tabla `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`idcolor`);

--
-- Indices de la tabla `linea`
--
ALTER TABLE `linea`
  ADD PRIMARY KEY (`idlinea`);

--
-- Indices de la tabla `presentacion`
--
ALTER TABLE `presentacion`
  ADD PRIMARY KEY (`idpresentacion`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idproducto`),
  ADD KEY `idcategorias` (`idcategorias`),
  ADD KEY `idlinea` (`idlinea`);

--
-- Indices de la tabla `productos_has_presentacion`
--
ALTER TABLE `productos_has_presentacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `presentacion_id` (`presentacion_id`);

--
-- Indices de la tabla `productos_has_ventas`
--
ALTER TABLE `productos_has_ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `venta_id` (`venta_id`);

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`idstock`),
  ADD KEY `idproducto` (`idproducto`),
  ADD KEY `fk_stock_color` (`idcolor`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`idventa`),
  ADD KEY `Cliente_idCliente` (`Cliente_idCliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `idcategorias` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `color`
--
ALTER TABLE `color`
  MODIFY `idcolor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `linea`
--
ALTER TABLE `linea`
  MODIFY `idlinea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `presentacion`
--
ALTER TABLE `presentacion`
  MODIFY `idpresentacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idproducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `productos_has_presentacion`
--
ALTER TABLE `productos_has_presentacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos_has_ventas`
--
ALTER TABLE `productos_has_ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `idstock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `idventa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`idcategorias`) REFERENCES `categorias` (`idcategorias`),
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`idlinea`) REFERENCES `linea` (`idlinea`);

--
-- Filtros para la tabla `productos_has_presentacion`
--
ALTER TABLE `productos_has_presentacion`
  ADD CONSTRAINT `productos_has_presentacion_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`idproducto`),
  ADD CONSTRAINT `productos_has_presentacion_ibfk_2` FOREIGN KEY (`presentacion_id`) REFERENCES `presentacion` (`idpresentacion`);

--
-- Filtros para la tabla `productos_has_ventas`
--
ALTER TABLE `productos_has_ventas`
  ADD CONSTRAINT `productos_has_ventas_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`idproducto`),
  ADD CONSTRAINT `productos_has_ventas_ibfk_2` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`idventa`);

--
-- Filtros para la tabla `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `fk_stock_color` FOREIGN KEY (`idcolor`) REFERENCES `color` (`idcolor`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`idproducto`) REFERENCES `productos` (`idproducto`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`Cliente_idCliente`) REFERENCES `cliente` (`idCliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
