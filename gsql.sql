-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 23, 2021 at 11:49 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------


CREATE TABLE IF NOT EXISTS `backup` (
  `Sno` int(11) NOT NULL AUTO_INCREMENT,
  `BackupFileName` varchar(15) NOT NULL,
  `BackupDate` varchar(12) NOT NULL,
  `BackupStatus` int(11) NOT NULL,
  PRIMARY KEY (`Sno`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `shopdebtors` (
  `Sno` int(11) NOT NULL AUTO_INCREMENT,
  `Date` date NOT NULL,
  `Item` varchar(150) NOT NULL,
  `QtySold` int(11) NOT NULL,
  `cost` varchar(50) NOT NULL,
  `Remark` text,
  `Balance` varchar(50) NOT NULL,
  PRIMARY KEY (`Sno`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `shopitems` (
  `Sno` int(11) NOT NULL AUTO_INCREMENT,
  `Date` date NOT NULL,
  `Item` varchar(60) DEFAULT NULL,
  `BulkPurchasePrice` int(11) NOT NULL,
  `BulkPurchaseQty` int(11) NOT NULL,
  `Buying` text NOT NULL,
  `Selling` int(11) NOT NULL,
  `Target` int(11) NOT NULL,
  `Status` varchar(15) NOT NULL,
  PRIMARY KEY (`Sno`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `shopsales` (
  `Sno` int(11) NOT NULL AUTO_INCREMENT,
  `Date` date NOT NULL,
  `Item` varchar(150) NOT NULL,
  `QtySold` int(11) NOT NULL,
  `cost` varchar(50) NOT NULL,
  `Remark` text,
  `Handler` varchar(50) NOT NULL,
  PRIMARY KEY (`Sno`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `shopexpenses` (
  `Sno` int(11) NOT NULL AUTO_INCREMENT,
  `Date` date NOT NULL,
  `Item` varchar(150) NOT NULL,
  `Qty` int(11) NOT NULL,
  `Cost` varchar(50) NOT NULL,
  `Remark` text,
  PRIMARY KEY (`Sno`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `shoptasks` (
  `Sno` int(11) NOT NULL AUTO_INCREMENT,
  `DueDate` varchar(15) NOT NULL,
  `Activity` varchar(150) NOT NULL,
  `Remarks` text NOT NULL,
  `Status` varchar(150) NOT NULL,
  `Incharge` text NOT NULL,
  PRIMARY KEY (`Sno`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `shopusers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(150) NOT NULL,
  `PassSalt` text NOT NULL,
  `contact` varchar(50) NOT NULL,
  `CLOR` int(11) NOT NULL,
  `AccountStatus` varchar(250) NOT NULL,
  `AccountTerminationDate` datetime DEFAULT NULL,
  `Namba` int(11) NOT NULL,
  `AppointedOn` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;