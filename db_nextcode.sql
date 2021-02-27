-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-08-2020 a las 08:54:24
-- Versión del servidor: 10.4.8-MariaDB
-- Versión de PHP: 7.2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_nextcode`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `NEWFACTURADETALLE` (IN `FECHA` DATE, IN `SUBTOTAL` FLOAT, IN `IVA` FLOAT, IN `TOTAL` FLOAT, IN `FACTURA_ID` INT, IN `PRODUCTOS` TEXT)  BEGIN
INSERT INTO `factura_detalle` (`facturas_id`, `fecha`, `subtotal`, `iva`, `total`, `productos`) VALUES (FACTURA_ID, fecha, subtotal, iva, total, productos);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `restarStock` (IN `productos_json` VARCHAR(255))  BEGIN
DECLARE id_producto,cantidad,total,contador int;
DECLARE json varchar(255);
#set @json = productos;
SET @total = (SELECT LENGTH(productos_json) - LENGTH(REPLACE(productos_json,'{', '')));
SET @contador = 0;
WHILE @contador < @total DO
   #select @json,@total,@contador from dual;
   SET @id_producto = (SELECT SUBSTRING(SUBSTRING(productos_json,INSTR(productos_json,'ID"'),6),6,7) AS TEXTO FROM DUAL);
   SET @cantidad = (SELECT SUBSTRING(SUBSTRING(productos_json,INSTR(productos_json,'CANTIDAD"'),12),12,13) AS TEXTO FROM DUAL);
   UPDATE productos set productos.stock=(productos.stock-@cantidad) where id=@id_producto;
   set productos_json = (SELECT SUBSTRING_INDEX(productos_json,'},',-1));
   set @contador=@contador+1;
   #select @id_producto,@cantidad,productos_json,@total,@contador from dual;
END WHILE;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `ci_ruc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `ci_ruc`, `razon_social`, `telefono`, `direccion`, `estado`) VALUES
(1, '1250025872', 'JUAN CASTRO', '0999888', 'SAN EDUARDO', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `establecimiento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `punto_emision` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sec_factura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `establecimiento`, `punto_emision`, `sec_factura`) VALUES
(1, 'GUAYAQUIL', 'GYE', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id` int(11) NOT NULL,
  `ruc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id`, `ruc`, `razon_social`, `direccion`) VALUES
(1, '099999887777', 'NEXTCODE', 'GUAYAQUIL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `clientes_id` int(11) NOT NULL,
  `establecimiento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `punto_emision` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sec_factura` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `impuestos` double NOT NULL,
  `total` double NOT NULL,
  `subtotal` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`id`, `empresa_id`, `clientes_id`, `establecimiento`, `punto_emision`, `sec_factura`, `fecha`, `impuestos`, `total`, `subtotal`) VALUES
(2, 1, 1, 'GUAYAQUIL', 'GYE', '2', '2020-08-11', 9.29, 86.69, 77.4);

--
-- Disparadores `factura`
--
DELIMITER $$
CREATE TRIGGER `agregarDetalle` AFTER INSERT ON `factura` FOR EACH ROW BEGIN
  	CALL NEWFACTURADETALLE(NOW(),NEW.SUBTOTAL,NEW.IMPUESTOS,NEW.TOTAL,NEW.ID,'');
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_detalle`
--

CREATE TABLE `factura_detalle` (
  `id` int(11) NOT NULL,
  `facturas_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `subtotal` float(4,2) NOT NULL,
  `iva` float(4,2) NOT NULL,
  `total` double(4,2) NOT NULL,
  `productos` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `factura_detalle`
--

INSERT INTO `factura_detalle` (`id`, `facturas_id`, `fecha`, `subtotal`, `iva`, `total`, `productos`) VALUES
(2, 2, '2020-08-11', 77.40, 9.29, 86.69, '[{\"ID\":\"1\",\"DESCRIPCION\":\"JABON\",\"PRECIO\":\"25.8\",\"CANTIDAD\":\"3\",\"TOTAL\":\"77.4\"}]');

--
-- Disparadores `factura_detalle`
--
DELIMITER $$
CREATE TRIGGER `actualizaStock` AFTER UPDATE ON `factura_detalle` FOR EACH ROW CALL restarStock (NEW.PRODUCTOS)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` double NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `descripcion`, `precio`, `stock`) VALUES
(1, 'ABC123', 'JABON', 25.8, 7),
(2, 'XYZ123', 'ZAPATOS', 1.99, 27);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `email`) VALUES
(1, 'admin', '$2y$13$nQ3ZoAOd0PhZsKtTaebCYu.l9076eWSYHnbanECMVrvl1S5s/QeYa', 'jcarrielroca98@gmail.com');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_50FE07D7E968D7F0` (`ci_ruc`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F9EBA009521E1991` (`empresa_id`),
  ADD KEY `IDX_F9EBA009FBC3AF09` (`clientes_id`);

--
-- Indices de la tabla `factura_detalle`
--
ALTER TABLE `factura_detalle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_95328C651C55BE39` (`facturas_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_EF687F2F85E0677` (`username`),
  ADD UNIQUE KEY `UNIQ_EF687F2E7927C74` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `factura_detalle`
--
ALTER TABLE `factura_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `FK_F9EBA009521E1991` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`),
  ADD CONSTRAINT `FK_F9EBA009FBC3AF09` FOREIGN KEY (`clientes_id`) REFERENCES `clientes` (`id`);

--
-- Filtros para la tabla `factura_detalle`
--
ALTER TABLE `factura_detalle`
  ADD CONSTRAINT `FK_95328C651C55BE39` FOREIGN KEY (`facturas_id`) REFERENCES `factura` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
