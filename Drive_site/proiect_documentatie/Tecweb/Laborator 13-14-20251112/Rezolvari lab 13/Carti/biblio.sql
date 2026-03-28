-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 18, 2016 at 04:54 PM
-- Server version: 5.5.20
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `biblio`
--
CREATE DATABASE `biblio` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `biblio`;

-- --------------------------------------------------------

--
-- Table structure for table `carti`
--

CREATE TABLE IF NOT EXISTS `carti` (
  `CodC` int(11) NOT NULL AUTO_INCREMENT,
  `Titlu` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `Autor` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `Editura` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `DataA` date NOT NULL,
  `Pret` int(6) NOT NULL,
  PRIMARY KEY (`CodC`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `carti`
--

INSERT INTO `carti` (`CodC`, `Titlu`, `Autor`, `Editura`, `DataA`, `Pret`) VALUES
(2, 'Programare Java', 'Cristina Damasc', 'ALL', '2016-03-04', 25),
(3, 'Insomnii', 'Irina Binder', 'For YOU', '2016-02-12', 45);

-- --------------------------------------------------------

--
-- Table structure for table `useri`
--

CREATE TABLE IF NOT EXISTS `useri` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `parola` char(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `useri`
--

INSERT INTO `useri` (`ID`, `user`, `parola`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
