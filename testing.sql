-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2023 at 10:43 AM
-- Server version: 5.7.31
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `testing`
--

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id_transactions` int(11) NOT NULL AUTO_INCREMENT,
  `times` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('DEPOSIT','WITHDRAW','TRANSFER') DEFAULT NULL,
  `status` enum('DEBIT','CREDIT') DEFAULT NULL,
  `amount` decimal(10,0) DEFAULT '0',
  PRIMARY KEY (`id_transactions`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `transactions2`
--

CREATE TABLE IF NOT EXISTS `transactions2` (
  `id_transactions` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_recipient` int(11) DEFAULT NULL,
  `id_sender` int(11) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `times` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('DEPOSIT','WITHDRAW','TRANSFER') DEFAULT NULL,
  `status` enum('DEBIT','CREDIT') DEFAULT NULL,
  `amount` decimal(10,0) DEFAULT '0',
  PRIMARY KEY (`id_transactions`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;


-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(10) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`) VALUES
(1, 'Feon', 'Feon'),
(2, 'Vira', 'Vira');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
