-- Vrij Wonen Database Schema
-- Modernized schema for the real estate application

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Database schema for Vrij Wonen application
-- This file contains only the table structure, no data

-- Table structure for `cities`
CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `citiename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for `connectprop`
CREATE TABLE `connectprop` (
  `objectid` int(11) NOT NULL,
  `propertieid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for `inquiries`
CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `objectid` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `replyemail` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `handled` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for `objects`
CREATE TABLE `objects` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `adress` varchar(255) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `cityid` int(11) NOT NULL,
  `description` text NOT NULL,
  `mainimage` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL,
  `image4` varchar(255) DEFAULT NULL,
  `image5` varchar(255) DEFAULT NULL,
  `sold` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for `properties`
CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `propertie` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for `staff`
CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwordhash` varchar(255) NOT NULL,
  `sessionkey` varchar(255) NOT NULL DEFAULT '',
  `admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Indexes for dumped tables
-- Indexes for table `cities`
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

-- Indexes for table `connectprop`
ALTER TABLE `connectprop`
  ADD PRIMARY KEY (`objectid`,`propertieid`),
  ADD KEY `propertieid` (`propertieid`);

-- Indexes for table `inquiries`
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `objectid` (`objectid`);

-- Indexes for table `objects`
ALTER TABLE `objects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cityid` (`cityid`);

-- Indexes for table `properties`
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`);

-- Indexes for table `staff`
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

-- AUTO_INCREMENT for dumped tables
-- AUTO_INCREMENT for table `cities`
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- AUTO_INCREMENT for table `inquiries`
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- AUTO_INCREMENT for table `objects`
ALTER TABLE `objects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- AUTO_INCREMENT for table `properties`
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- AUTO_INCREMENT for table `staff`
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Constraints for dumped tables
-- Constraints for table `connectprop`
ALTER TABLE `connectprop`
  ADD CONSTRAINT `connectprop_ibfk_1` FOREIGN KEY (`objectid`) REFERENCES `objects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `connectprop_ibfk_2` FOREIGN KEY (`propertieid`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

-- Constraints for table `inquiries`
ALTER TABLE `inquiries`
  ADD CONSTRAINT `inquiries_ibfk_1` FOREIGN KEY (`objectid`) REFERENCES `objects` (`id`) ON DELETE CASCADE;

-- Constraints for table `objects`
ALTER TABLE `objects`
  ADD CONSTRAINT `objects_ibfk_1` FOREIGN KEY (`cityid`) REFERENCES `cities` (`id`) ON DELETE RESTRICT;

COMMIT;
