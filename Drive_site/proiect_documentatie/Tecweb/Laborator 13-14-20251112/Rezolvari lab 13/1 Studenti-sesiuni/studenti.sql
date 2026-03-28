-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2022 at 06:56 AM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `studenti`
--
CREATE DATABASE IF NOT EXISTS `studenti` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `studenti`;

-- --------------------------------------------------------

--
-- Table structure for table `an1`
--

CREATE TABLE `an1` (
  `ID` int(11) NOT NULL,
  `student` varchar(100) NOT NULL,
  `data_n` date NOT NULL,
  `an_inscriere` year(4) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefon` varchar(40) NOT NULL,
  `mobil` varchar(40) NOT NULL,
  `obs` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `an1`
--

INSERT INTO `an1` (`ID`, `student`, `data_n`, `an_inscriere`, `email`, `telefon`, `mobil`, `obs`) VALUES
(1, 'asdsad', '1990-10-10', 2001, 'sdsd', '21312', '23123', '213213');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `an1`
--
ALTER TABLE `an1`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `an1`
--
ALTER TABLE `an1`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
