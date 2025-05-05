-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 05, 2025 at 04:32 PM
-- Server version: 8.0.39-30
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbnqnobrek5ptr`
--

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `bill_id` int NOT NULL,
  `billNo` int NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `bill_by` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mso` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `stbno` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pMode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `oldMonthBal` int NOT NULL,
  `paid_amount` int NOT NULL,
  `discount` int NOT NULL,
  `Rs` int NOT NULL,
  `due_month_timestamp` datetime NOT NULL,
  `adv_status` int NOT NULL COMMENT 'Yes=1 No=0',
  `status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `printStatus` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billgroup`
--

CREATE TABLE `billgroup` (
  `id` int NOT NULL,
  `billNo` int NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `group_id` int NOT NULL,
  `mso` varchar(15) NOT NULL,
  `stbNo` varchar(25) NOT NULL,
  `name` varchar(60) NOT NULL,
  `remark` varchar(60) NOT NULL,
  `status` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `billgroupdetails`
--

CREATE TABLE `billgroupdetails` (
  `id` int NOT NULL,
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
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `cusGroup` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mso` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `stbno` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `customer_area_code` char(5) COLLATE utf8mb4_general_ci NOT NULL,
  `accessories` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `amount` int NOT NULL,
  `rc_dc` int NOT NULL COMMENT 'rc=1;dc=0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_area`
--

CREATE TABLE `customer_area` (
  `customer_area_id` int NOT NULL,
  `customer_area_code` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `customer_area_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `customer_area_status` enum('Active','Inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groupinfo`
--

CREATE TABLE `groupinfo` (
  `group_id` int NOT NULL,
  `createdBy` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `groupName` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` bigint NOT NULL DEFAULT '0',
  `billAmt` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incomeExpence`
--

CREATE TABLE `incomeExpence` (
  `id` int NOT NULL,
  `type` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `username` varchar(10) NOT NULL,
  `subCategory` varchar(20) NOT NULL,
  `category` varchar(15) NOT NULL,
  `remark` varchar(50) NOT NULL,
  `amount` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `in_ex`
--

CREATE TABLE `in_ex` (
  `id` int NOT NULL,
  `type` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `username` varchar(10) NOT NULL,
  `category_id` int NOT NULL,
  `subcategory_id` int NOT NULL,
  `remark` varchar(50) NOT NULL,
  `amount` int NOT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `in_ex_category`
--

CREATE TABLE `in_ex_category` (
  `category_id` int NOT NULL,
  `createdBy` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `category` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `in_ex` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `in_ex_subcategory`
--

CREATE TABLE `in_ex_subcategory` (
  `subcategory_id` int NOT NULL,
  `category_id` int NOT NULL,
  `subcategory` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loc_bills`
--

CREATE TABLE `loc_bills` (
  `loc_bills_id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `created_user_id` int NOT NULL,
  `loc_gen_bill_id` int NOT NULL,
  `channel_uid` varchar(5) NOT NULL,
  `paid_amount` int NOT NULL,
  `discount` int NOT NULL,
  `remark` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '',
  `status` tinyint(1) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `loc_channels`
--

CREATE TABLE `loc_channels` (
  `loc_channel_id` int NOT NULL,
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
  `updated_user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `loc_gen_bills`
--

CREATE TABLE `loc_gen_bills` (
  `loc_gen_bill_id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `created_user_id` int NOT NULL,
  `loc_gen_bill_log_id` int NOT NULL,
  `channel_uid` varchar(5) NOT NULL,
  `due_amount` int NOT NULL,
  `due_status` tinyint(1) NOT NULL,
  `remark` varchar(30) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `loc_gen_bills_log`
--

CREATE TABLE `loc_gen_bills_log` (
  `loc_gen_bill_log_id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `created_user_id` int NOT NULL,
  `due_month` tinyint NOT NULL,
  `due_year` int NOT NULL,
  `status` int NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loc_prop_login`
--

CREATE TABLE `loc_prop_login` (
  `loc_prop_login_id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `loc_gen_bill_id` int NOT NULL,
  `channel_uid` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pay_mode`
--

CREATE TABLE `pay_mode` (
  `pay_mode_id` int NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '0',
  `status` varchar(50) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `pos_bill`
--

CREATE TABLE `pos_bill` (
  `pos_bill_id` int NOT NULL,
  `entry_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `bill_no` int NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL DEFAULT '0',
  `cus_name` varchar(50) NOT NULL DEFAULT '0',
  `cus_phone` bigint NOT NULL DEFAULT '0',
  `discount` int NOT NULL DEFAULT '0',
  `amount` int DEFAULT '0',
  `token` varchar(50) NOT NULL DEFAULT '0',
  `pay_mode` tinyint NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `pos_bill_items`
--

CREATE TABLE `pos_bill_items` (
  `pos_bill_items_id` int NOT NULL,
  `entry_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `username` varchar(50) NOT NULL,
  `pos_bill_id` int NOT NULL DEFAULT '0',
  `pos_product_id` int NOT NULL DEFAULT '0',
  `r_or_hs` tinyint NOT NULL DEFAULT '0' COMMENT 'r=0; hs=1',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `qty` int NOT NULL DEFAULT '0',
  `token` varchar(50) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `pos_product`
--

CREATE TABLE `pos_product` (
  `pos_product_id` int NOT NULL,
  `entry_timestamp` datetime DEFAULT NULL,
  `username` varchar(50) NOT NULL DEFAULT '',
  `product_name` varchar(50) NOT NULL DEFAULT '',
  `r_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `hs_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `appName` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `appName2` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `addr1` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `addr2` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` bigint NOT NULL,
  `prtFooter1` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `prtFooter2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lastUpdateBy` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `latestUpdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `name` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` bigint NOT NULL,
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `google_totp_auth_secret` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `id` int NOT NULL,
  `userId` int NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `userName` varchar(20) NOT NULL,
  `role` varchar(10) NOT NULL,
  `action` varchar(90) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`bill_id`);

--
-- Indexes for table `billgroup`
--
ALTER TABLE `billgroup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `billgroupdetails`
--
ALTER TABLE `billgroupdetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stbno` (`stbno`);

--
-- Indexes for table `customer_area`
--
ALTER TABLE `customer_area`
  ADD PRIMARY KEY (`customer_area_id`),
  ADD UNIQUE KEY `customer_area_code` (`customer_area_code`);

--
-- Indexes for table `groupinfo`
--
ALTER TABLE `groupinfo`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `incomeExpence`
--
ALTER TABLE `incomeExpence`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `in_ex`
--
ALTER TABLE `in_ex`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_in_ex_category` (`category_id`),
  ADD KEY `fk_in_ex_subcategory` (`subcategory_id`);

--
-- Indexes for table `in_ex_category`
--
ALTER TABLE `in_ex_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `in_ex_subcategory`
--
ALTER TABLE `in_ex_subcategory`
  ADD PRIMARY KEY (`subcategory_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `loc_bills`
--
ALTER TABLE `loc_bills`
  ADD PRIMARY KEY (`loc_bills_id`);

--
-- Indexes for table `loc_channels`
--
ALTER TABLE `loc_channels`
  ADD PRIMARY KEY (`loc_channel_id`),
  ADD UNIQUE KEY `channel_uid` (`channel_uid`);

--
-- Indexes for table `loc_gen_bills`
--
ALTER TABLE `loc_gen_bills`
  ADD PRIMARY KEY (`loc_gen_bill_id`) USING BTREE;

--
-- Indexes for table `loc_gen_bills_log`
--
ALTER TABLE `loc_gen_bills_log`
  ADD PRIMARY KEY (`loc_gen_bill_log_id`);

--
-- Indexes for table `loc_prop_login`
--
ALTER TABLE `loc_prop_login`
  ADD PRIMARY KEY (`loc_prop_login_id`);

--
-- Indexes for table `pay_mode`
--
ALTER TABLE `pay_mode`
  ADD PRIMARY KEY (`pay_mode_id`);

--
-- Indexes for table `pos_bill`
--
ALTER TABLE `pos_bill`
  ADD PRIMARY KEY (`pos_bill_id`);

--
-- Indexes for table `pos_bill_items`
--
ALTER TABLE `pos_bill_items`
  ADD PRIMARY KEY (`pos_bill_items_id`) USING BTREE;

--
-- Indexes for table `pos_product`
--
ALTER TABLE `pos_product`
  ADD PRIMARY KEY (`pos_product_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `bill_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billgroup`
--
ALTER TABLE `billgroup`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billgroupdetails`
--
ALTER TABLE `billgroupdetails`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_area`
--
ALTER TABLE `customer_area`
  MODIFY `customer_area_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groupinfo`
--
ALTER TABLE `groupinfo`
  MODIFY `group_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incomeExpence`
--
ALTER TABLE `incomeExpence`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `in_ex`
--
ALTER TABLE `in_ex`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `in_ex_category`
--
ALTER TABLE `in_ex_category`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `in_ex_subcategory`
--
ALTER TABLE `in_ex_subcategory`
  MODIFY `subcategory_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loc_bills`
--
ALTER TABLE `loc_bills`
  MODIFY `loc_bills_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loc_channels`
--
ALTER TABLE `loc_channels`
  MODIFY `loc_channel_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loc_gen_bills`
--
ALTER TABLE `loc_gen_bills`
  MODIFY `loc_gen_bill_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loc_gen_bills_log`
--
ALTER TABLE `loc_gen_bills_log`
  MODIFY `loc_gen_bill_log_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loc_prop_login`
--
ALTER TABLE `loc_prop_login`
  MODIFY `loc_prop_login_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pay_mode`
--
ALTER TABLE `pay_mode`
  MODIFY `pay_mode_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_bill`
--
ALTER TABLE `pos_bill`
  MODIFY `pos_bill_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_bill_items`
--
ALTER TABLE `pos_bill_items`
  MODIFY `pos_bill_items_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_product`
--
ALTER TABLE `pos_product`
  MODIFY `pos_product_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
