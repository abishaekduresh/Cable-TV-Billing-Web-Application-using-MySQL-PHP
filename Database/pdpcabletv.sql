-- --------------------------------------------------------
-- Host:                         s2.dureshtech.com
-- Server version:               5.7.43-log - Source distribution
-- Server OS:                    Linux
-- HeidiSQL Version:             12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for pdpcabletv
CREATE DATABASE IF NOT EXISTS `pdpcabletv` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `pdpcabletv`;

-- Dumping structure for table pdpcabletv.bill
CREATE TABLE IF NOT EXISTS `bill` (
  `bill_id` int(5) NOT NULL AUTO_INCREMENT,
  `billNo` int(4) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `bill_by` varchar(25) NOT NULL,
  `mso` varchar(15) NOT NULL,
  `stbno` varchar(25) NOT NULL,
  `name` varchar(60) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `description` varchar(100) NOT NULL,
  `pMode` varchar(10) NOT NULL,
  `oldMonthBal` int(11) NOT NULL,
  `paid_amount` int(11) NOT NULL,
  `discount` int(4) NOT NULL,
  `Rs` int(4) NOT NULL,
  `due_month_timestamp` datetime NOT NULL,
  `adv_status` int(5) NOT NULL COMMENT 'Yes=1 No=0',
  `status` varchar(10) NOT NULL,
  `printStatus` int(2) NOT NULL,
  PRIMARY KEY (`bill_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pdpcabletv.billgroup
CREATE TABLE IF NOT EXISTS `billgroup` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `billNo` int(4) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `group_id` int(5) NOT NULL,
  `mso` varchar(15) NOT NULL,
  `stbNo` varchar(25) NOT NULL,
  `name` varchar(60) NOT NULL,
  `remark` varchar(60) NOT NULL,
  `status` varchar(7) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table pdpcabletv.billgroupdetails
CREATE TABLE IF NOT EXISTS `billgroupdetails` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `billNo` int(5) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `billBy` varchar(10) NOT NULL,
  `group_id` int(25) NOT NULL,
  `groupName` varchar(30) NOT NULL,
  `phone` int(11) NOT NULL,
  `pMode` varchar(20) NOT NULL,
  `oldMonthBal` int(2) NOT NULL,
  `billAmount` int(5) NOT NULL,
  `discount` int(2) NOT NULL,
  `Rs` int(2) NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table pdpcabletv.customer
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `cusGroup` varchar(15) NOT NULL,
  `mso` varchar(10) NOT NULL,
  `stbno` varchar(20) NOT NULL,
  `name` varchar(60) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `description` varchar(100) NOT NULL,
  `amount` int(5) NOT NULL,
  `rc_dc` int(2) NOT NULL COMMENT 'rc=1;dc=0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `stbno` (`stbno`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pdpcabletv.customer_log
CREATE TABLE IF NOT EXISTS `customer_log` (
  `customer_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL,
  `name` varchar(60) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `stbno` varchar(30) NOT NULL,
  `activity` varchar(60) NOT NULL,
  PRIMARY KEY (`customer_log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table pdpcabletv.groupinfo
CREATE TABLE IF NOT EXISTS `groupinfo` (
  `group_id` int(5) NOT NULL AUTO_INCREMENT,
  `createdBy` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `groupName` varchar(25) NOT NULL,
  `phone` int(20) NOT NULL,
  `billAmt` int(5) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pdpcabletv.incomeExpence
CREATE TABLE IF NOT EXISTS `incomeExpence` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `username` varchar(10) NOT NULL,
  `subCategory` varchar(20) NOT NULL,
  `category` varchar(15) NOT NULL,
  `remark` varchar(50) NOT NULL,
  `amount` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table pdpcabletv.in_ex
CREATE TABLE IF NOT EXISTS `in_ex` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `username` varchar(10) NOT NULL,
  `category_id` int(5) NOT NULL,
  `subcategory_id` int(5) NOT NULL,
  `remark` varchar(50) NOT NULL,
  `amount` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_in_ex_category` (`category_id`),
  KEY `fk_in_ex_subcategory` (`subcategory_id`),
  CONSTRAINT `fk_in_ex_category` FOREIGN KEY (`category_id`) REFERENCES `in_ex_category` (`category_id`),
  CONSTRAINT `fk_in_ex_subcategory` FOREIGN KEY (`subcategory_id`) REFERENCES `in_ex_subcategory` (`subcategory_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2382 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

-- Dumping structure for table pdpcabletv.in_ex_category
CREATE TABLE IF NOT EXISTS `in_ex_category` (
  `category_id` int(5) NOT NULL AUTO_INCREMENT,
  `createdBy` varchar(10) NOT NULL,
  `category` varchar(25) NOT NULL,
  `in_ex` varchar(10) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pdpcabletv.in_ex_subcategory
CREATE TABLE IF NOT EXISTS `in_ex_subcategory` (
  `subcategory_id` int(5) NOT NULL AUTO_INCREMENT,
  `category_id` int(5) NOT NULL,
  `subcategory` varchar(30) NOT NULL,
  PRIMARY KEY (`subcategory_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `in_ex_subcategory_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `in_ex_category` (`category_id`),
  CONSTRAINT `in_ex_subcategory_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `in_ex_category` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pdpcabletv.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `appName` varchar(25) NOT NULL,
  `appName2` varchar(25) NOT NULL,
  `email` varchar(35) NOT NULL,
  `addr1` varchar(25) NOT NULL,
  `addr2` varchar(25) NOT NULL,
  `phone` bigint(1) NOT NULL,
  `prtFooter1` varchar(60) NOT NULL,
  `prtFooter2` varchar(60) NOT NULL,
  `lastUpdateBy` varchar(20) NOT NULL,
  `latestUpdate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table pdpcabletv.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(10) NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table pdpcabletv.user: ~9 rows (approximately)
INSERT INTO `user` (`id`, `name`, `username`, `password`, `role`, `status`) VALUES
	(1, 'admin', 'admin', 'e2fc714c4727ee9395f324cd2e7f331f', 'admin', 1);

-- Dumping structure for table pdpcabletv.user_activity
CREATE TABLE IF NOT EXISTS `user_activity` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `userId` int(2) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `userName` varchar(20) NOT NULL,
  `role` varchar(10) NOT NULL,
  `action` varchar(90) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
