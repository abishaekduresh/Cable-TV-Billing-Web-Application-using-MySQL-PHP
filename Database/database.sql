-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 10, 2024 at 07:08 PM
-- Server version: 10.3.38-MariaDB-cll-lve
-- PHP Version: 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pdpctvUHBUHjj`
--

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `bill_id` int(5) NOT NULL,
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
  `printStatus` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billgroup`
--

CREATE TABLE `billgroup` (
  `id` int(5) NOT NULL,
  `billNo` int(4) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `group_id` int(5) NOT NULL,
  `mso` varchar(15) NOT NULL,
  `stbNo` varchar(25) NOT NULL,
  `name` varchar(60) NOT NULL,
  `remark` varchar(60) NOT NULL,
  `status` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billgroupdetails`
--

CREATE TABLE `billgroupdetails` (
  `id` int(5) NOT NULL,
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
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(5) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `cusGroup` varchar(15) NOT NULL,
  `mso` varchar(10) NOT NULL,
  `stbno` varchar(20) NOT NULL,
  `name` varchar(60) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `description` varchar(100) NOT NULL,
  `amount` int(5) NOT NULL,
  `rc_dc` int(2) NOT NULL COMMENT 'rc=1;dc=0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_log`
--

CREATE TABLE `customer_log` (
  `customer_log_id` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `name` varchar(60) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `stbno` varchar(30) NOT NULL,
  `activity` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groupinfo`
--

CREATE TABLE `groupinfo` (
  `group_id` int(5) NOT NULL,
  `createdBy` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `groupName` varchar(25) NOT NULL,
  `phone` bigint(10) NOT NULL DEFAULT 0,
  `billAmt` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incomeExpence`
--

CREATE TABLE `incomeExpence` (
  `id` int(5) NOT NULL,
  `type` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `username` varchar(10) NOT NULL,
  `subCategory` varchar(20) NOT NULL,
  `category` varchar(15) NOT NULL,
  `remark` varchar(50) NOT NULL,
  `amount` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `in_ex`
--

CREATE TABLE `in_ex` (
  `id` int(5) NOT NULL,
  `type` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `username` varchar(10) NOT NULL,
  `category_id` int(5) NOT NULL,
  `subcategory_id` int(5) NOT NULL,
  `remark` varchar(50) NOT NULL,
  `amount` int(5) NOT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `in_ex_category`
--

CREATE TABLE `in_ex_category` (
  `category_id` int(5) NOT NULL,
  `createdBy` varchar(10) NOT NULL,
  `category` varchar(25) NOT NULL,
  `in_ex` varchar(10) NOT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `in_ex_subcategory`
--

CREATE TABLE `in_ex_subcategory` (
  `subcategory_id` int(5) NOT NULL,
  `category_id` int(5) NOT NULL,
  `subcategory` varchar(30) NOT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pay_mode`
--

CREATE TABLE `pay_mode` (
  `pay_mode_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '0',
  `status` varchar(50) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_bill`
--

CREATE TABLE `pos_bill` (
  `pos_bill_id` int(11) NOT NULL,
  `entry_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `bill_no` int(11) NOT NULL DEFAULT 0,
  `username` varchar(50) NOT NULL DEFAULT '0',
  `cus_name` varchar(50) NOT NULL DEFAULT '0',
  `cus_phone` bigint(20) NOT NULL DEFAULT 0,
  `discount` int(11) NOT NULL DEFAULT 0,
  `amount` int(11) DEFAULT 0,
  `token` varchar(50) NOT NULL DEFAULT '0',
  `pay_mode` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_bill_items`
--

CREATE TABLE `pos_bill_items` (
  `pos_bill_items_id` int(11) NOT NULL,
  `entry_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `username` varchar(50) NOT NULL,
  `pos_bill_id` int(11) NOT NULL DEFAULT 0,
  `pos_product_id` int(11) NOT NULL DEFAULT 0,
  `r_or_hs` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'r=0; hs=1',
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `qty` int(11) NOT NULL DEFAULT 0,
  `token` varchar(50) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_product`
--

CREATE TABLE `pos_product` (
  `pos_product_id` int(11) NOT NULL,
  `entry_timestamp` datetime DEFAULT NULL,
  `username` varchar(50) NOT NULL DEFAULT '',
  `product_name` varchar(50) NOT NULL DEFAULT '',
  `r_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `hs_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(5) NOT NULL,
  `appName` varchar(25) NOT NULL,
  `appName2` varchar(25) NOT NULL,
  `email` varchar(35) NOT NULL,
  `addr1` varchar(25) NOT NULL,
  `addr2` varchar(25) NOT NULL,
  `phone` bigint(1) NOT NULL,
  `prtFooter1` varchar(60) NOT NULL,
  `prtFooter2` varchar(60) NOT NULL,
  `lastUpdateBy` varchar(20) NOT NULL,
  `latestUpdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(2) NOT NULL,
  `name` varchar(25) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(10) NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `username`, `password`, `role`, `status`) VALUES
(1, 'Admin', '23A002', '21232f297a57a5a743894a0e4a801fc3', 'admin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `id` int(5) NOT NULL,
  `userId` int(2) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `userName` varchar(20) NOT NULL,
  `role` varchar(10) NOT NULL,
  `action` varchar(90) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
-- Indexes for table `customer_log`
--
ALTER TABLE `customer_log`
  ADD PRIMARY KEY (`customer_log_id`);

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
  MODIFY `bill_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billgroup`
--
ALTER TABLE `billgroup`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billgroupdetails`
--
ALTER TABLE `billgroupdetails`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_log`
--
ALTER TABLE `customer_log`
  MODIFY `customer_log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groupinfo`
--
ALTER TABLE `groupinfo`
  MODIFY `group_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incomeExpence`
--
ALTER TABLE `incomeExpence`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `in_ex`
--
ALTER TABLE `in_ex`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `in_ex_category`
--
ALTER TABLE `in_ex_category`
  MODIFY `category_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `in_ex_subcategory`
--
ALTER TABLE `in_ex_subcategory`
  MODIFY `subcategory_id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pay_mode`
--
ALTER TABLE `pay_mode`
  MODIFY `pay_mode_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_bill`
--
ALTER TABLE `pos_bill`
  MODIFY `pos_bill_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_bill_items`
--
ALTER TABLE `pos_bill_items`
  MODIFY `pos_bill_items_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pos_product`
--
ALTER TABLE `pos_product`
  MODIFY `pos_product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

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
