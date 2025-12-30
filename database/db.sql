-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               9.1.0 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table pdpctv_dt_com.bill
CREATE TABLE IF NOT EXISTS `bill` (
  `bill_id` int NOT NULL AUTO_INCREMENT,
  `billNo` int NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `bill_by` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mso` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `stbno` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `remark2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `X_transaction_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `X_pg_vendor` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `X_pg_order_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pMode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `oldMonthBal` int NOT NULL,
  `paid_amount` int NOT NULL,
  `discount` int NOT NULL,
  `Rs` int NOT NULL,
  `due_month_timestamp` datetime NOT NULL,
  `adv_status` int NOT NULL COMMENT 'Yes=1 No=0',
  `notified_status` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'No',
  `status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `printStatus` int NOT NULL,
  PRIMARY KEY (`bill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pdpctv_dt_com.billgroup
CREATE TABLE IF NOT EXISTS `billgroup` (
  `id` int NOT NULL AUTO_INCREMENT,
  `billNo` int NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `group_id` int NOT NULL,
  `mso` varchar(15) NOT NULL,
  `stbNo` varchar(25) NOT NULL,
  `name` varchar(60) NOT NULL,
  `remark` varchar(60) NOT NULL,
  `status` varchar(7) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Data exporting was unselected.

-- Dumping structure for table pdpctv_dt_com.billgroupdetails
CREATE TABLE IF NOT EXISTS `billgroupdetails` (
  `id` int NOT NULL AUTO_INCREMENT,
  `billNo` int NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `ad` tinyint NOT NULL DEFAULT (0) COMMENT 'Is advanced bill',
  `date` date NOT NULL,
  `time` time NOT NULL,
  `billBy` varchar(10) NOT NULL,
  `group_id` int NOT NULL,
  `groupName` varchar(30) NOT NULL,
  `phone` bigint NOT NULL,
  `pMode` varchar(20) NOT NULL,
  `oldMonthBal` int NOT NULL,
  `billAmount` int NOT NULL,
  `discount` int NOT NULL,
  `Rs` int NOT NULL,
  `status` varchar(10) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Data exporting was unselected.

-- Dumping structure for table pdpctv_dt_com.customer
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `cusGroup` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mso` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `stbno` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `customer_area_code` char(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `accessories` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `amount` int NOT NULL,
  `rc_dc` int NOT NULL COMMENT 'rc=1;dc=0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `stbno` (`stbno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pdpctv_dt_com.customer_area
CREATE TABLE IF NOT EXISTS `customer_area` (
  `customer_area_id` int NOT NULL AUTO_INCREMENT,
  `customer_area_code` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `customer_area_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `customer_area_status` enum('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`customer_area_id`),
  UNIQUE KEY `customer_area_code` (`customer_area_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pdpctv_dt_com.groupinfo
CREATE TABLE IF NOT EXISTS `groupinfo` (
  `group_id` int NOT NULL AUTO_INCREMENT,
  `createdBy` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `groupName` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` bigint NOT NULL DEFAULT '0',
  `billAmt` int NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pdpctv_dt_com.in_ex
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
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_in_ex_category` (`category_id`),
  KEY `fk_in_ex_subcategory` (`subcategory_id`),
  CONSTRAINT `fk_in_ex_category` FOREIGN KEY (`category_id`) REFERENCES `in_ex_category` (`category_id`),
  CONSTRAINT `fk_in_ex_subcategory` FOREIGN KEY (`subcategory_id`) REFERENCES `in_ex_subcategory` (`subcategory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Data exporting was unselected.

-- Dumping structure for table pdpctv_dt_com.in_ex_category
CREATE TABLE IF NOT EXISTS `in_ex_category` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `createdBy` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `category` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `in_ex` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pdpctv_dt_com.in_ex_subcategory
CREATE TABLE IF NOT EXISTS `in_ex_subcategory` (
  `subcategory_id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `subcategory` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`subcategory_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `in_ex_subcategory_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `in_ex_category` (`category_id`),
  CONSTRAINT `in_ex_subcategory_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `in_ex_category` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pdpctv_dt_com.loc_bills
CREATE TABLE IF NOT EXISTS `loc_bills` (
  `loc_bills_id` int NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `created_user_id` int NOT NULL,
  `loc_gen_bill_id` int NOT NULL,
  `channel_uid` varchar(5) NOT NULL,
  `paid_amount` int NOT NULL,
  `discount` int NOT NULL,
  `remark` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '',
  `status` tinyint(1) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_user_id` int NOT NULL,
  PRIMARY KEY (`loc_bills_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Data exporting was unselected.

-- Dumping structure for table pdpctv_dt_com.loc_channels
CREATE TABLE IF NOT EXISTS `loc_channels` (
  `loc_channel_id` int NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `created_user_id` int NOT NULL,
  `channel_uid` varchar(20) NOT NULL,
  `channel_name` varchar(20) NOT NULL,
  `prop_name` varchar(20) NOT NULL,
  `prop_phone` bigint NOT NULL,
  `prop_address` varchar(30) NOT NULL,
  `network_amount` int NOT NULL,
  `remark` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_user_id` int NOT NULL,
  PRIMARY KEY (`loc_channel_id`),
  UNIQUE KEY `channel_uid` (`channel_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Data exporting was unselected.

-- Dumping structure for table pdpctv_dt_com.loc_gen_bills
CREATE TABLE IF NOT EXISTS `loc_gen_bills` (
  `loc_gen_bill_id` int NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `created_user_id` int NOT NULL,
  `loc_gen_bill_log_id` int NOT NULL,
  `channel_uid` varchar(5) NOT NULL,
  `due_amount` int NOT NULL,
  `due_status` tinyint(1) NOT NULL,
  `remark` varchar(30) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_user_id` int NOT NULL,
  PRIMARY KEY (`loc_gen_bill_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Data exporting was unselected.

-- Dumping structure for table pdpctv_dt_com.loc_gen_bills_log
CREATE TABLE IF NOT EXISTS `loc_gen_bills_log` (
  `loc_gen_bill_log_id` int NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `created_user_id` int NOT NULL,
  `due_month` tinyint NOT NULL,
  `due_year` int NOT NULL,
  `status` int NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_user_id` int NOT NULL,
  PRIMARY KEY (`loc_gen_bill_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pdpctv_dt_com.pay_mode
CREATE TABLE IF NOT EXISTS `pay_mode` (
  `pay_mode_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '0',
  `status` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pay_mode_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Data exporting was unselected.

-- Dumping structure for table pdpctv_dt_com.pos_bill
CREATE TABLE IF NOT EXISTS `pos_bill` (
  `pos_bill_id` int NOT NULL AUTO_INCREMENT,
  `entry_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `bill_no` int NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL DEFAULT '0',
  `cus_name` varchar(50) NOT NULL DEFAULT '0',
  `cus_phone` bigint NOT NULL DEFAULT '0',
  `discount` int NOT NULL DEFAULT '0',
  `amount` int DEFAULT '0',
  `token` varchar(50) NOT NULL DEFAULT '0',
  `pay_mode` tinyint NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL,
  PRIMARY KEY (`pos_bill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Data exporting was unselected.

-- Dumping structure for table pdpctv_dt_com.pos_bill_items
CREATE TABLE IF NOT EXISTS `pos_bill_items` (
  `pos_bill_items_id` int NOT NULL AUTO_INCREMENT,
  `entry_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `username` varchar(50) NOT NULL,
  `pos_bill_id` int NOT NULL DEFAULT '0',
  `pos_product_id` int NOT NULL DEFAULT '0',
  `r_or_hs` tinyint NOT NULL DEFAULT '0' COMMENT 'r=0; hs=1',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `qty` int NOT NULL DEFAULT '0',
  `token` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pos_bill_items_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Data exporting was unselected.

-- Dumping structure for table pdpctv_dt_com.pos_product
CREATE TABLE IF NOT EXISTS `pos_product` (
  `pos_product_id` int NOT NULL AUTO_INCREMENT,
  `entry_timestamp` datetime DEFAULT NULL,
  `username` varchar(50) NOT NULL DEFAULT '',
  `product_name` varchar(50) NOT NULL DEFAULT '',
  `r_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `hs_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock` int NOT NULL,
  PRIMARY KEY (`pos_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Data exporting was unselected.

-- Dumping structure for table pdpctv_dt_com.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `appName` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `appName2` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `addr1` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `addr2` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` bigint NOT NULL,
  `sentSMS` tinyint(1) NOT NULL,
  `prtFooter1` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `prtFooter2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lastUpdateBy` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `latestUpdate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pdpctv_dt_com.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` bigint NOT NULL,
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `passcode` int DEFAULT NULL,
  `google_totp_auth_secret` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table pdpctv_dt_com.user_activity
CREATE TABLE IF NOT EXISTS `user_activity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `userId` int NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `userName` varchar(20) NOT NULL,
  `role` varchar(10) NOT NULL,
  `action` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
