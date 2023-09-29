-- phpMyAdmin SQL Dump
-- version 4.9.11
-- Server version: 5.7.42-cll-lve
-- PHP Version: 7.4.33

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `pokemon_gallery`
--
CREATE DATABASE IF NOT EXISTS `pokemon_gallery` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pokemon_gallery`;

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

DROP TABLE IF EXISTS `game`;
CREATE TABLE `game` (
  `id` int(11) NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`id`, `name`) VALUES
(1, 'Go'),
(2, 'Let\'s Go, Pikachu!'),
(3, 'Let\'s Go, Eevee!'),
(4, 'Sword'),
(5, 'Shield'),
(6, 'Brilliant Diamond'),
(7, 'Shining Pearl'),
(8, 'Legends: Arceus'),
(9, 'Scarlet'),
(10, 'Violet');

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE `type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `colour` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `border` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`id`, `name`, `colour`, `border`) VALUES
(1, 'Normal', '#A8A878', '#6D6D4E'),
(2, 'Fire', '#F08030', '#9C531F'),
(3, 'Water', '#6890F0', '#445E9C'),
(4, 'Grass', '#78C850', '#4E8234'),
(5, 'Electric', '#F8D030', '#A1871F'),
(6, 'Ice', '#98D8D8', '#638D8D'),
(7, 'Fighting', '#C03028', '#7D1F1A'),
(8, 'Poison', '#A040A0', '#682A68'),
(9, 'Ground', '#E0C068', '#927D44'),
(10, 'Flying', '#A890F0', '#6D5E9C'),
(11, 'Psychic', '#F85888', '#A13959'),
(12, 'Bug', '#A8B820', '#6D7815'),
(13, 'Rock', '#B8A038', '#786824'),
(14, 'Ghost', '#705898', '#493963'),
(15, 'Dragon', '#7038F8', '#4924A1'),
(16, 'Dark', '#705848', '#49392F'),
(17, 'Steel', '#B8B8D0', '#787887'),
(18, 'Fairy', '#EE99AC', '#9B6470');

-- --------------------------------------------------------

--
-- Table structure for table `gender`
--

DROP TABLE IF EXISTS `gender`;
CREATE TABLE `gender` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gender`
--

INSERT INTO `gender` (`id`, `name`) VALUES
(1, 'None/Unknown'),
(2, 'Male'),
(3, 'Female');

-- --------------------------------------------------------

--
-- Table structure for table `form_type`
--

DROP TABLE IF EXISTS `form_type`;
CREATE TABLE `form_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `form_type`
--

INSERT INTO `form_type` (`id`, `name`) VALUES
(1, 'Regional'),
(2, 'Mega Evolution'),
(3, 'Gigantamax'),
(4, 'Terastallised'),
(5, 'Unown'),
(6, 'Castform'),
(7, 'Rotom');


-- --------------------------------------------------------

--
-- Table structure for table `form`
--

DROP TABLE IF EXISTS `form`;
CREATE TABLE `form` (
  `id` int(11) NOT NULL,
  `form_type_id` INT(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  CONSTRAINT `form_ibfk_1` FOREIGN KEY (`form_type_id`) REFERENCES `form_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `form`
--

INSERT INTO `form` (`id`, `form_type_id`, `name`) VALUES
(1, 1, 'Original'),
(2, 1, 'Alolan'),
(3, 1, 'Hisuian'),
(4, 1, 'Galarian'),
(5, 1, 'Paldean');

-- --------------------------------------------------------
--
-- Table structure for table `pokemon`
--

DROP TABLE IF EXISTS `pokemon`;
CREATE TABLE `pokemon` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender_id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `type1` int(11) NOT NULL,
  `type2` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`, `gender_id`, `form_id`),
  KEY `type1` (`type1`),
  KEY `type2` (`type2`),
  CONSTRAINT `pokemon_ibfk_1` FOREIGN KEY (`gender_id`) REFERENCES `gender` (`id`),
  CONSTRAINT `pokemon_ibfk_2` FOREIGN KEY (`form_id`) REFERENCES `form` (`id`),
  CONSTRAINT `pokemon_ibfk_3` FOREIGN KEY (`type1`) REFERENCES `type` (`id`),
  CONSTRAINT `pokemon_ibfk_4` FOREIGN KEY (`type2`) REFERENCES `type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

DROP TABLE IF EXISTS `gallery`;
CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `pokemon_id` int(11) NOT NULL,
  `datetime` int(11) NOT NULL,
  `filename` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `viewer` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pokemon_id` (`pokemon_id`),
  CONSTRAINT `gallery_ibfk_1` FOREIGN KEY (`pokemon_id`) REFERENCES `pokemon` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shiny`
--

DROP TABLE IF EXISTS `shiny`;
CREATE TABLE `shiny` (
  `id` int(11) NOT NULL,
  `pokemon_id` int(11) NOT NULL,
  `gender_id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `caught_date` date NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`),
  KEY `pokemon_id` (`pokemon_id`),
  CONSTRAINT `shiny_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`),
  CONSTRAINT `shiny_ibfk_2` FOREIGN KEY (`pokemon_id`, `gender_id`, `form_id`) REFERENCES `pokemon` (`id`, `gender_id`, `form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
