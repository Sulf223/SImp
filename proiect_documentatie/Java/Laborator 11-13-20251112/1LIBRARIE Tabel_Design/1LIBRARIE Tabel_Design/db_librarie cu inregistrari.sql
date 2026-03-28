-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 15, 2021 at 01:40 PM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_librarie`
--
CREATE DATABASE IF NOT EXISTS `db_librarie` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `db_librarie`;

-- --------------------------------------------------------

--
-- Table structure for table `carti`
--

DROP TABLE IF EXISTS `carti`;
CREATE TABLE IF NOT EXISTS `carti` (
  `Cod` int(6) NOT NULL AUTO_INCREMENT,
  `Titlu` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `Autor` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `NrPag` int(11) NOT NULL,
  `Pret` double NOT NULL,
  PRIMARY KEY (`Cod`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `carti`
--

INSERT INTO `carti` (`Cod`, `Titlu`, `Autor`, `NrPag`, `Pret`) VALUES
(1, 'Programare Java', 'Liviu Negrescu', 860, 60),
(3, 'Tehnologii WEB', 'Teodora Negrea', 400, 42.5),
(4, 'POO', 'Andrei Muresan', 280, 62.5),
(5, 'Programarea in C', 'Mircea Nechifor', 300, 30);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
