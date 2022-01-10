-- Adminer 4.8.1 MySQL 5.5.5-10.4.12-MariaDB dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE DATABASE `tokens` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci */;
USE `tokens`;

DROP TABLE IF EXISTS `perms`;
CREATE TABLE `perms` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `descripción` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

INSERT INTO `perms` (`ID`, `descripción`) VALUES
(1,	'Crear Usuarios'),
(2,	'Generar Tokens');

DROP TABLE IF EXISTS `token`;
CREATE TABLE `token` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ownerID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `owner` (`ownerID`),
  CONSTRAINT `token_ibfk_1` FOREIGN KEY (`ownerID`) REFERENCES `user` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `nick` varchar(100) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `password` char(60) COLLATE utf8mb4_spanish_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;


DROP TABLE IF EXISTS `users_perms`;
CREATE TABLE `users_perms` (
  `userID` int(11) NOT NULL,
  `permID` int(11) NOT NULL,
  PRIMARY KEY (`userID`,`permID`),
  KEY `permID` (`permID`),
  CONSTRAINT `users_perms_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`ID`),
  CONSTRAINT `users_perms_ibfk_2` FOREIGN KEY (`permID`) REFERENCES `perms` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;


-- 2022-01-10 20:02:48
