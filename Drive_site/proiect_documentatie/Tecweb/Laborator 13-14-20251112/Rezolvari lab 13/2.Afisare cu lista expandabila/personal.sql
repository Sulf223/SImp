-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 08, 2017 at 08:48 PM
-- Server version: 5.5.20
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `personal`
--
CREATE DATABASE `personal` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `personal`;

-- --------------------------------------------------------

--
-- Table structure for table `angajati`
--

CREATE TABLE IF NOT EXISTS `angajati` (
  `Cod` int(11) NOT NULL AUTO_INCREMENT,
  `Nume` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `Prenume` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `CodD` int(6) NOT NULL,
  `DataN` date NOT NULL,
  PRIMARY KEY (`Cod`),
  KEY `CodD` (`CodD`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `angajati`
--

INSERT INTO `angajati` (`Cod`, `Nume`, `Prenume`, `CodD`, `DataN`) VALUES
(4, 'Popescu', 'Maria', 1, '1985-01-21'),
(5, 'Ilinca', 'Raisa', 1, '1970-09-09'),
(6, 'Frentoni', 'Ionel', 2, '1960-09-09');

-- --------------------------------------------------------

--
-- Table structure for table `departamente`
--

CREATE TABLE IF NOT EXISTS `departamente` (
  `Cod` int(11) NOT NULL AUTO_INCREMENT,
  `Denumire` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Cod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `departamente`
--

INSERT INTO `departamente` (`Cod`, `Denumire`) VALUES
(1, 'IEII'),
(2, 'CALCULATOARE'),
(3, 'ACHIZITII');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `angajati`
--
ALTER TABLE `angajati`
  ADD CONSTRAINT `angajati_ibfk_1` FOREIGN KEY (`CodD`) REFERENCES `departamente` (`Cod`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
