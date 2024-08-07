
--
-- Base de datos: `messenger`
--
CREATE DATABASE IF NOT EXISTS `messenger` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `messenger`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacts`
--

CREATE TABLE `contacts` (
  `iduser` int NOT NULL,
  `idcontact` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `contacts`
--

INSERT INTO `contacts` (`iduser`, `idcontact`) VALUES
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 12),
(2, 1),
(3, 1),
(7, 1),
(7, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `messages`
--

CREATE TABLE `messages` (
  `idmessage` int UNSIGNED NOT NULL,
  `refsender` int UNSIGNED NOT NULL,
  `refrecipient` int UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `subject` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '[without subject]',
  `body` varchar(200) DEFAULT NULL,
  `leido` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `messages`
--

INSERT INTO `messages` (`idmessage`, `refsender`, `refrecipient`, `date`, `time`, `subject`, `body`, `leido`) VALUES
(10, 1, 3, '2022-01-10', '22:00:59', 'Problem', 'Hi Álvaro.\r\nThere is an error in the method generateInvoice(). Please have a look and fix the problem.\r\nThank you so much.', 1),
(9, 1, 3, '2022-01-10', '21:43:40', 'Next meeting', 'Hi Álvaro.\r\nNext meeting will be on Monday morning at 11h.\r\nRegards', 1),
(8, 1, 7, '2021-04-19', '12:08:22', 'Prueba mensaje Daniel', 'Hi Daniel', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `iduser` int UNSIGNED NOT NULL,
  `nick` varchar(16) NOT NULL,
  `password` varchar(16) NOT NULL DEFAULT '0000',
  `name` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`iduser`, `nick`, `password`, `name`) VALUES
(1, 'emilio', '0000', 'Emilio Jesús Ramírez Velasco'),
(2, 'rocio', '1111', 'Rocío Gómez Caballero'),
(3, 'alvaro', '2222', 'Álvaro Yeste'),
(4, 'javier', '0000', 'Javier Jiménez Rodríguez'),
(5, 'beatriz', '1111', 'Beatriz Morales Rosales'),
(6, 'ana', '2222', 'Ana María Bernier Blanco'),
(7, 'danielp', '0000', 'Daniel Palma'),
(8, 'josef', '1111', 'José de la Fuente Murillo'),
(9, 'joseb', '2222', 'José Belmonte Gómez'),
(10, 'israel', '0000', 'José Israel Fernández Barroso'),
(11, 'rafael', '1111', 'Rafael Sánchez Santos'),
(12, 'danielc', '2222', 'Daniel Curtean');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`iduser`,`idcontact`);

--
-- Indices de la tabla `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`idmessage`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`iduser`),
  ADD UNIQUE KEY `nick` (`nick`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `messages`
--
ALTER TABLE `messages`
  MODIFY `idmessage` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `iduser` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;
