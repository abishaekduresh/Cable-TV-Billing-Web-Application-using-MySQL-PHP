-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2023 at 05:52 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ctv.pdpgroups`
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
  `groupID` varchar(25) NOT NULL,
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
  `billGroupNo` int(5) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `billBy` varchar(10) NOT NULL,
  `groupID` varchar(25) NOT NULL,
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
  `date` date NOT NULL DEFAULT current_timestamp(),
  `time` time NOT NULL DEFAULT current_timestamp(),
  `cusGroup` varchar(15) NOT NULL,
  `mso` varchar(10) NOT NULL,
  `stbno` varchar(20) NOT NULL,
  `name` varchar(60) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `description` varchar(100) NOT NULL,
  `amount` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groupinfo`
--

CREATE TABLE `groupinfo` (
  `id` int(5) NOT NULL,
  `createdBy` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `groupName` varchar(25) NOT NULL,
  `phone` int(20) NOT NULL,
  `billAmt` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incomeexpence`
--

CREATE TABLE `incomeexpence` (
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
-- Table structure for table `incomeexpenceinfo`
--

CREATE TABLE `incomeexpenceinfo` (
  `id` int(5) NOT NULL,
  `timestamp` datetime(6) NOT NULL,
  `category` varchar(30) NOT NULL,
  `subategory` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(5) NOT NULL,
  `appName` varchar(25) NOT NULL,
  `email` varchar(35) NOT NULL,
  `addr1` varchar(25) NOT NULL,
  `addr2` varchar(25) NOT NULL,
  `phone` bigint(1) NOT NULL,
  `prtFooter1` varchar(25) NOT NULL,
  `prtFooter2` varchar(25) NOT NULL,
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
  `action` varchar(60) NOT NULL
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
-- Indexes for table `groupinfo`
--
ALTER TABLE `groupinfo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incomeexpence`
--
ALTER TABLE `incomeexpence`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incomeexpenceinfo`
--
ALTER TABLE `incomeexpenceinfo`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `groupinfo`
--
ALTER TABLE `groupinfo`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incomeexpence`
--
ALTER TABLE `incomeexpence`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incomeexpenceinfo`
--
ALTER TABLE `incomeexpenceinfo`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
