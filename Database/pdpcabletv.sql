-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 25, 2023 at 06:35 AM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pdpcabletv`
--

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

DROP TABLE IF EXISTS `bill`;
CREATE TABLE IF NOT EXISTS `bill` (
  `bill_id` int NOT NULL AUTO_INCREMENT,
  `billNo` int NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `bill_by` varchar(25) NOT NULL,
  `mso` varchar(15) NOT NULL,
  `stbno` varchar(25) NOT NULL,
  `name` varchar(60) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `description` varchar(100) NOT NULL,
  `pMode` varchar(10) NOT NULL,
  `oldMonthBal` int NOT NULL,
  `paid_amount` int NOT NULL,
  `discount` int NOT NULL,
  `Rs` int NOT NULL,
  `due_month_timestamp` datetime NOT NULL,
  `adv_status` int NOT NULL COMMENT 'Yes=1 No=0',
  `status` varchar(10) NOT NULL,
  `printStatus` int NOT NULL,
  PRIMARY KEY (`bill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billgroup`
--

DROP TABLE IF EXISTS `billgroup`;
CREATE TABLE IF NOT EXISTS `billgroup` (
  `id` int NOT NULL AUTO_INCREMENT,
  `billNo` int NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `group_id` int NOT NULL,
  `mso` varchar(15) NOT NULL,
  `stbNo` varchar(25) NOT NULL,
  `name` varchar(60) NOT NULL,
  `remark` varchar(60) NOT NULL,
  `status` varchar(7) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `billgroupdetails`
--

DROP TABLE IF EXISTS `billgroupdetails`;
CREATE TABLE IF NOT EXISTS `billgroupdetails` (
  `id` int NOT NULL AUTO_INCREMENT,
  `billNo` int NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `billBy` varchar(10) NOT NULL,
  `group_id` int NOT NULL,
  `groupName` varchar(30) NOT NULL,
  `phone` int NOT NULL,
  `pMode` varchar(20) NOT NULL,
  `oldMonthBal` int NOT NULL,
  `billAmount` int NOT NULL,
  `discount` int NOT NULL,
  `Rs` int NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `cusGroup` varchar(15) NOT NULL,
  `mso` varchar(10) NOT NULL,
  `stbno` varchar(20) NOT NULL,
  `name` varchar(60) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `description` varchar(100) NOT NULL,
  `amount` int NOT NULL,
  `rc_dc` int NOT NULL COMMENT 'rc=1;dc=0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `stbno` (`stbno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groupinfo`
--

DROP TABLE IF EXISTS `groupinfo`;
CREATE TABLE IF NOT EXISTS `groupinfo` (
  `group_id` int NOT NULL AUTO_INCREMENT,
  `createdBy` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `groupName` varchar(25) NOT NULL,
  `phone` int NOT NULL,
  `billAmt` int NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incomeexpence`
--

DROP TABLE IF EXISTS `incomeexpence`;
CREATE TABLE IF NOT EXISTS `incomeexpence` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `username` varchar(10) NOT NULL,
  `subCategory` varchar(20) NOT NULL,
  `category` varchar(15) NOT NULL,
  `remark` varchar(50) NOT NULL,
  `amount` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `in_ex`
--

DROP TABLE IF EXISTS `in_ex`;
CREATE TABLE IF NOT EXISTS `in_ex` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `username` varchar(10) NOT NULL,
  `category_id` int NOT NULL,
  `subcategory_id` int NOT NULL,
  `remark` varchar(50) NOT NULL,
  `amount` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_in_ex_category` (`category_id`),
  KEY `fk_in_ex_subcategory` (`subcategory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `in_ex_category`
--

DROP TABLE IF EXISTS `in_ex_category`;
CREATE TABLE IF NOT EXISTS `in_ex_category` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `createdBy` varchar(10) NOT NULL,
  `category` varchar(25) NOT NULL,
  `in_ex` varchar(10) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `in_ex_subcategory`
--

DROP TABLE IF EXISTS `in_ex_subcategory`;
CREATE TABLE IF NOT EXISTS `in_ex_subcategory` (
  `subcategory_id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `subcategory` varchar(30) NOT NULL,
  PRIMARY KEY (`subcategory_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `appName` varchar(25) NOT NULL,
  `appName2` varchar(25) NOT NULL,
  `email` varchar(35) NOT NULL,
  `addr1` varchar(25) NOT NULL,
  `addr2` varchar(25) NOT NULL,
  `phone` bigint NOT NULL,
  `prtFooter1` varchar(60) NOT NULL,
  `prtFooter2` varchar(60) NOT NULL,
  `lastUpdateBy` varchar(20) NOT NULL,
  `latestUpdate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(10) NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

DROP TABLE IF EXISTS `user_activity`;
CREATE TABLE IF NOT EXISTS `user_activity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `userId` int NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `userName` varchar(20) NOT NULL,
  `role` varchar(10) NOT NULL,
  `action` varchar(90) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `in_ex`
--
ALTER TABLE `in_ex`
  ADD CONSTRAINT `fk_in_ex_category` FOREIGN KEY (`category_id`) REFERENCES `in_ex_category` (`category_id`),
  ADD CONSTRAINT `fk_in_ex_subcategory` FOREIGN KEY (`subcategory_id`) REFERENCES `in_ex_subcategory` (`subcategory_id`);

--
-- Constraints for table `in_ex_subcategory`
--
ALTER TABLE `in_ex_subcategory`
  ADD CONSTRAINT `in_ex_subcategory_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `in_ex_category` (`category_id`),
  ADD CONSTRAINT `in_ex_subcategory_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `in_ex_category` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
