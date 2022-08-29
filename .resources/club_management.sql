-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: club-management-mysql
-- Tiempo de generación: 29-08-2022 a las 00:55:11
-- Versión del servidor: 8.0.26
-- Versión de PHP: 8.0.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `club_management`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `club`
--

CREATE TABLE `club` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '(DC2Type:datetime_immutable)',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `budget` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `club`
--

INSERT INTO `club` (`id`, `created_at`, `updated_at`, `name`, `city`, `country`, `budget`) VALUES
('60ae1dd4-c615-4347-b1bc-94e2e1cbe38a', '2022-08-29 00:38:08', NULL, 'Real Madrid CF', 'Madrid', 'España', 4000000000),
('aa47a336-9866-46b3-a731-7ba74584211e', '2022-08-29 00:38:40', '2022-08-29 00:46:43', 'FC Barcelona', 'Barcelona', 'España', 6500000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coach`
--

CREATE TABLE `coach` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `club_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '(DC2Type:datetime_immutable)',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary` bigint DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `coach`
--

INSERT INTO `coach` (`id`, `club_id`, `date_of_birth`, `created_at`, `updated_at`, `name`, `surname`, `city`, `country`, `salary`, `email`) VALUES
('8a863db6-3bdf-4542-8786-ea5fa0d95d74', '60ae1dd4-c615-4347-b1bc-94e2e1cbe38a', '1950-08-15', '2022-08-29 00:45:56', '2022-08-29 00:51:05', 'Carlo', 'Ancelotti', 'Some', 'Italia', 44000, 'carletto@email.com'),
('92c1d28c-15ca-4e40-b5b3-100e72e93c23', NULL, '1959-10-08', '2022-08-29 00:40:30', NULL, 'Rafael', 'Benitez', 'Valencia', 'España', NULL, 'rafa@email.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220825114438', '2022-08-25 11:44:43', 388),
('DoctrineMigrations\\Version20220825160144', '2022-08-25 16:02:08', 389),
('DoctrineMigrations\\Version20220825211143', '2022-08-25 21:12:06', 519),
('DoctrineMigrations\\Version20220827155535', '2022-08-27 15:56:04', 1061);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `player`
--

CREATE TABLE `player` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `club_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '(DC2Type:datetime_immutable)',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary` bigint DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `player`
--

INSERT INTO `player` (`id`, `club_id`, `date_of_birth`, `created_at`, `updated_at`, `name`, `surname`, `city`, `country`, `salary`, `email`) VALUES
('6358b749-f417-487b-9a15-89285bf0ec6d', NULL, '1984-07-15', '2022-08-29 00:39:07', NULL, 'Cristiano', 'Ronaldo', 'Ciudad', 'Portugal', NULL, 'cr7@gmail.com'),
('d7632ec1-1ad5-4616-b1ca-dbcf77cbfc09', '60ae1dd4-c615-4347-b1bc-94e2e1cbe38a', '1990-09-15', '2022-08-29 00:43:27', '2022-08-29 00:49:16', 'Dani', 'Ceballos', 'Sevilla', 'España', 35000, 'ceballos@email.com'),
('f2b94696-cf64-499a-a32f-81e66daa27d1', '60ae1dd4-c615-4347-b1bc-94e2e1cbe38a', '1989-09-15', '2022-08-29 00:44:20', NULL, 'Antonio', 'Kroos', 'Some', 'Alemania', 68000, 'kroos@email.com');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `club`
--
ALTER TABLE `club`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `coach`
--
ALTER TABLE `coach`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_3F596DCCE7927C74` (`email`),
  ADD KEY `IDX_3F596DCC61190A32` (`club_id`);

--
-- Indices de la tabla `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indices de la tabla `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_98197A65E7927C74` (`email`),
  ADD KEY `IDX_98197A6561190A32` (`club_id`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `coach`
--
ALTER TABLE `coach`
  ADD CONSTRAINT `FK_3F596DCC61190A32` FOREIGN KEY (`club_id`) REFERENCES `club` (`id`);

--
-- Filtros para la tabla `player`
--
ALTER TABLE `player`
  ADD CONSTRAINT `FK_98197A6561190A32` FOREIGN KEY (`club_id`) REFERENCES `club` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
