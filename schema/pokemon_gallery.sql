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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_selection` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`id`, `name`, `default_selection`) VALUES
(1, 'Go', false),
(2, 'Let\'s Go, Pikachu!', false),
(3, 'Let\'s Go, Eevee!', false),
(4, 'Sword', false),
(5, 'Shield', false),
(6, 'Brilliant Diamond', false),
(7, 'Shining Pearl', false),
(8, 'Legends: Arceus', false),
(9, 'Scarlet', true),
(10, 'Violet', false),
(11, 'Trade', false);

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE `type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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

ALTER TABLE `type` AUTO_INCREMENT=19;

-- --------------------------------------------------------

--
-- Table structure for table `gender`
--

DROP TABLE IF EXISTS `gender`;
CREATE TABLE `gender` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `suffix` varchar(20) COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `suffix` (`suffix`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gender`
--

INSERT INTO `gender` (`id`, `name`, `suffix`) VALUES
(1, 'None/Unknown', null),
(2, 'Male', null),
(3, 'Female', '-f');

ALTER TABLE `gender` AUTO_INCREMENT=4;

-- --------------------------------------------------------

--
-- Table structure for table `form_type`
--

DROP TABLE IF EXISTS `form_type`;
CREATE TABLE `form_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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

ALTER TABLE `form_type` AUTO_INCREMENT=8;

-- --------------------------------------------------------

--
-- Table structure for table `form`
--

DROP TABLE IF EXISTS `form`;
CREATE TABLE `form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_type_id` INT(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `suffix` varchar(20) COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `suffix` (`form_type_id`, `suffix`),
  CONSTRAINT `form_ibfk_1` FOREIGN KEY (`form_type_id`) REFERENCES `form_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `form`
--

INSERT INTO `form` (`id`, `form_type_id`, `name`, `suffix`) VALUES
(1, 1, 'Original', null),
(2, 1, 'Alolan', '-a'),
(3, 1, 'Hisuian', '-h'),
(4, 1, 'Galarian', '-g'),
(5, 1, 'Paldean', '-p'),
(6, 7, 'Heat', '-h'),
(7, 7, 'Wash', '-w'),
(8, 7, 'Frost', '-f'),
(9, 7, 'Fan', '-s'),
(10, 7, 'Mow', '-m');

ALTER TABLE `form` AUTO_INCREMENT=11;

-- --------------------------------------------------------
--
-- Table structure for table `pokemon`
--

DROP TABLE IF EXISTS `pokemon`;
CREATE TABLE `pokemon` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender_id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `type1` int(11) NOT NULL,
  `type2` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type1` (`type1`),
  KEY `type2` (`type2`),
  CONSTRAINT `pokemon_ibfk_1` FOREIGN KEY (`gender_id`) REFERENCES `gender` (`id`),
  CONSTRAINT `pokemon_ibfk_2` FOREIGN KEY (`form_id`) REFERENCES `form` (`id`),
  CONSTRAINT `pokemon_ibfk_3` FOREIGN KEY (`type1`) REFERENCES `type` (`id`),
  CONSTRAINT `pokemon_ibfk_4` FOREIGN KEY (`type2`) REFERENCES `type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pokemon`
--

INSERT INTO `pokemon` (`id`, `number`, `name`, `gender_id`, `form_id`, `type1`, `type2`) VALUES
(1, 1, 'Bulbasaur', 1, 1, 4, 8),
(5, 4, 'Charmander', 1, 1, 2, NULL),
(32, 25, 'Pikachu', 3, 1, 5, NULL),
(46, 35, 'Clefairy', 1, 1, 18, NULL),
(47, 36, 'Clefable', 1, 1, 18, NULL),
(50, 38, 'Ninetales', 1, 1, 2, NULL),
(80, 58, 'Growlithe', 1, 1, 2, NULL),
(90, 65, 'Alakazam', 1, 1, 11, NULL),
(104, 76, 'Golem', 1, 1, 13, 9),
(117, 83, 'Farfetch\'d', 1, 4, 7, NULL),
(130, 92, 'Gastly', 1, 1, 14, 8),
(132, 94, 'Gengar', 1, 1, 14, 8),
(172, 123, 'Scyther', 1, 1, 12, 10),
(190, 134, 'Vaporeon', 1, 1, 3, NULL),
(326, 216, 'Teddiursa', 1, 1, 1, NULL),
(361, 245, 'Suicune', 1, 1, 3, NULL),
(429, 302, 'Sableye', 1, 1, 16, 14),
(522, 385, 'Jirachi', 1, 1, 17, 11),
(551, 403, 'Shinx', 2, 1, 1, NULL),
(576, 418, 'Buizel', 2, 1, 3, NULL),
(590, 427, 'Buneary', 1, 1, 1, NULL),
(653, 475, 'Gallade', 1, 1, 11, 7),
(659, 479, 'Rotom', 1, 1, 5, 3),
(671, 488, 'Cresselia', 1, 1, 11, NULL),
(719, 532, 'Timburr', 1, 1, 7, NULL),
(792, 591, 'Amoonguss', 1, 1, 4, 8),
(841, 636, 'Larvesta', 1, 1, 12, 2),
(956, 703, 'Carbink', 1, 1, 13, 18),
(1002, 736, 'Grubbin', 1, 1, 12, NULL),
(1003, 737, 'Charjabug', 1, 1, 12, 5),
(1004, 738, 'Vikavolt', 1, 1, 12, 5),
(1028, 757, 'Salandit', 1, 1, 8, 2),
(1110, 837, 'Rolycoly', 1, 1, 13, NULL),
(1171, 888, 'Zacian', 1, 1, 18, NULL),
(1172, 889, 'Zamazenta', 1, 1, 7, NULL),
(1203, 915, 'Lechonk', 1, 1, 1, NULL),
(1204, 916, 'Oinkologne', 2, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

DROP TABLE IF EXISTS `gallery`;
CREATE TABLE `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pokemon_id` int(11) NOT NULL,
  `datetime` int(11) NOT NULL,
  `filename` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `viewer` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pokemon_id` (`pokemon_id`),
  CONSTRAINT `gallery_ibfk_1` FOREIGN KEY (`pokemon_id`) REFERENCES `pokemon` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `pokemon_id`, `datetime`, `filename`, `viewer`) VALUES
(1, 32, 1693854000, 'img/gallery/25.png', 'Aine_ni20'),
(2, 1, 1694459590, 'img/gallery/1.64ff66c64830e0.27772607.png', 'jpo6388'),
(3, 590, 1694460323, 'img/gallery/427.64ff69a30f1fb9.53857902.png', 'xLucyLouux'),
(4, 130, 1694463039, 'img/gallery/92.64ff743f948b87.06654013.png', 'OverlordStefen'),
(5, 90, 1694464561, 'img/gallery/65.64ff7a313a64c1.37830228.png', 'Aine_ni20'),
(6, 172, 1694467884, 'img/gallery/123.64ff872c3968d5.93037254.png', 'xLucyLouux'),
(7, 46, 1694717459, 'img/gallery/38.650356130bef53.48616629.png', 'Aine_ni20'),
(8, 80, 1694718091, 'img/gallery/58.6503588bd070b9.29153213.png', 'Aine_ni20'),
(9, 659, 1695671417, 'img/gallery/479.6511e479e75158.40599596.png', 'ElderTotoro'),
(10, 132, 1696536337, 'img/gallery/34.651f1711048349.12031089.png', 'CoopCakes');

ALTER TABLE `gallery` AUTO_INCREMENT=11;

-- --------------------------------------------------------

--
-- Table structure for table `shiny`
--

DROP TABLE IF EXISTS `shiny`;
CREATE TABLE `shiny` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pokemon_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `caught_date` date NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`),
  KEY `pokemon_id` (`pokemon_id`),
  CONSTRAINT `shiny_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`),
  CONSTRAINT `shiny_ibfk_2` FOREIGN KEY (`pokemon_id`) REFERENCES `pokemon` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shiny`
--

INSERT INTO `shiny` (`id`, `pokemon_id`, `game_id`, `caught_date`, `filename`) VALUES
(1, 5, 1, '2023-09-02', NULL),
(2, 117, 1, '2023-09-05', NULL),
(3, 190, 1, '2023-08-14', NULL),
(4, 361, 4, '2020-11-09', NULL),
(5, 429, 1, '2023-08-21', NULL),
(6, 576, 1, '2023-09-11', NULL),
(7, 792, 5, '2020-08-10', NULL),
(8, 1171, 4, '2021-11-15', NULL),
(9, 1172, 5, '2021-11-15', NULL),
(10, 1002, 1, '2023-09-23', NULL),
(11, 1003, 1, '2023-09-23', NULL),
(12, 1004, 1, '2023-09-23', NULL),
(13, 1203, 9, '2022-11-20', NULL),
(14, 326, 10, '2023-09-27', NULL),
(15, 719, 9, '2023-09-28', NULL),
(16, 46, 9, '2023-09-30', NULL),
(17, 1204, 9, '2023-09-30', NULL),
(18, 47, 9, '2023-10-01', NULL),
(19, 551, 11, '2023-10-04', NULL),
(20, 956, 9, '2023-10-05', NULL),
(21, 841, 9, '2023-10-05', NULL),
(22, 1110, 9, '2023-10-05', NULL),
(23, 1028, 9, '2023-10-05', NULL),
(24, 104, 11, '2023-10-05', NULL),
(25, 653, 11, '2023-10-05', NULL),
(26, 522, 11, '2023-10-06', NULL),
(27, 671, 11, '2023-10-06', NULL),
(28, 1281, 9, '2023-10-07', NULL),
(29, 1283, 9, '2023-10-07', NULL),
(30, 1280, 9, '2023-10-08', NULL),
(31, 1249, 11, '2023-10-08', NULL),
(32, 1288, 11, '2023-10-08', NULL);

ALTER TABLE `shiny` AUTO_INCREMENT=33;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
