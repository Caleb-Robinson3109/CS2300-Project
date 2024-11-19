-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2024 at 09:42 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE hr_database;
USE hr_database;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hr_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

CREATE TABLE `access` (
  `hr_Ssn` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `accountant`
--

CREATE TABLE `accountant` (
  `acc_Ssn` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `F_name` varchar(100) DEFAULT NULL,
  `L_name` varchar(100) DEFAULT NULL,
  `phone_no` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `associate`
--

CREATE TABLE `associate` (
  `acc_Ssn` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `F_name` varchar(100) DEFAULT NULL,
  `L_name` varchar(100) DEFAULT NULL,
  `phone_no` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `Name` varchar(100) NOT NULL,
  `Address` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `E_Ssn` int(11) NOT NULL,
  `role` enum('associate','hr','accountant') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employs`
--

CREATE TABLE `employs` (
  `C_name` varchar(100) NOT NULL,
  `E_Ssn` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gets`
--

CREATE TABLE `gets` (
  `E_Ssn` int(11) NOT NULL,
  `pay_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `has`
--

CREATE TABLE `has` (
  `E_Ssn` int(11) NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr`
--

CREATE TABLE `hr` (
  `hr_Ssn` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `F_name` varchar(100) DEFAULT NULL,
  `L_name` varchar(100) DEFAULT NULL,
  `phone_no` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manages`
--

CREATE TABLE `manages` (
  `E_Ssn` int(11) NOT NULL,
  `pay_id` int(11) NOT NULL,
  `det_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pay`
--

CREATE TABLE `pay` (
  `id` int(11) NOT NULL,
  `acc_num` int(11) DEFAULT NULL,
  `rout_num` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pay_details`
--

CREATE TABLE `pay_details` (
  `det_id` int(11) NOT NULL,
  `pay_id` int(11) NOT NULL,
  `salary` float DEFAULT NULL,
  `bonus` float DEFAULT NULL,
  `benefits` float DEFAULT NULL,
  `tax_rate` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`hr_Ssn`,`username`),
  ADD KEY `username` (`username`),
  ADD KEY `password` (`password`);

--
-- Indexes for table `accountant`
--
ALTER TABLE `accountant`
  ADD PRIMARY KEY (`acc_Ssn`);

--
-- Indexes for table `associate`
--
ALTER TABLE `associate`
  ADD PRIMARY KEY (`acc_Ssn`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`Name`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`E_Ssn`);

--
-- Indexes for table `employs`
--
ALTER TABLE `employs`
  ADD PRIMARY KEY (`C_name`,`E_Ssn`),
  ADD KEY `E_Ssn` (`E_Ssn`);

--
-- Indexes for table `gets`
--
ALTER TABLE `gets`
  ADD PRIMARY KEY (`E_Ssn`,`pay_id`),
  ADD KEY `pay_id` (`pay_id`);

--
-- Indexes for table `has`
--
ALTER TABLE `has`
  ADD PRIMARY KEY (`E_Ssn`,`username`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `hr`
--
ALTER TABLE `hr`
  ADD PRIMARY KEY (`hr_Ssn`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `unique_password` (`password`);

--
-- Indexes for table `manages`
--
ALTER TABLE `manages`
  ADD PRIMARY KEY (`E_Ssn`,`pay_id`,`det_id`),
  ADD KEY `pay_id` (`pay_id`),
  ADD KEY `det_id` (`det_id`);

--
-- Indexes for table `pay`
--
ALTER TABLE `pay`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pay_details`
--
ALTER TABLE `pay_details`
  ADD PRIMARY KEY (`det_id`,`pay_id`),
  ADD KEY `pay_id` (`pay_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access`
--
ALTER TABLE `access`
  ADD CONSTRAINT `access_ibfk_1` FOREIGN KEY (`hr_Ssn`) REFERENCES `hr` (`hr_Ssn`),
  ADD CONSTRAINT `access_ibfk_2` FOREIGN KEY (`username`) REFERENCES `login` (`username`),
  ADD CONSTRAINT `access_ibfk_3` FOREIGN KEY (`password`) REFERENCES `login` (`password`);

--
-- Constraints for table `accountant`
--
ALTER TABLE `accountant`
  ADD CONSTRAINT `accountant_ibfk_1` FOREIGN KEY (`acc_Ssn`) REFERENCES `employee` (`E_Ssn`);

--
-- Constraints for table `associate`
--
ALTER TABLE `associate`
  ADD CONSTRAINT `associate_ibfk_1` FOREIGN KEY (`acc_Ssn`) REFERENCES `employee` (`E_Ssn`);

--
-- Constraints for table `employs`
--
ALTER TABLE `employs`
  ADD CONSTRAINT `employs_ibfk_1` FOREIGN KEY (`C_name`) REFERENCES `company` (`Name`),
  ADD CONSTRAINT `employs_ibfk_2` FOREIGN KEY (`E_Ssn`) REFERENCES `employee` (`E_Ssn`);

--
-- Constraints for table `gets`
--
ALTER TABLE `gets`
  ADD CONSTRAINT `gets_ibfk_1` FOREIGN KEY (`E_Ssn`) REFERENCES `employee` (`E_Ssn`),
  ADD CONSTRAINT `gets_ibfk_2` FOREIGN KEY (`pay_id`) REFERENCES `pay` (`id`);

--
-- Constraints for table `has`
--
ALTER TABLE `has`
  ADD CONSTRAINT `has_ibfk_1` FOREIGN KEY (`E_Ssn`) REFERENCES `employee` (`E_Ssn`),
  ADD CONSTRAINT `has_ibfk_2` FOREIGN KEY (`username`) REFERENCES `login` (`username`);

--
-- Constraints for table `hr`
--
ALTER TABLE `hr`
  ADD CONSTRAINT `hr_ibfk_1` FOREIGN KEY (`hr_Ssn`) REFERENCES `employee` (`E_Ssn`);

--
-- Constraints for table `manages`
--
ALTER TABLE `manages`
  ADD CONSTRAINT `manages_ibfk_1` FOREIGN KEY (`E_Ssn`) REFERENCES `accountant` (`acc_Ssn`),
  ADD CONSTRAINT `manages_ibfk_2` FOREIGN KEY (`pay_id`) REFERENCES `pay` (`id`),
  ADD CONSTRAINT `manages_ibfk_3` FOREIGN KEY (`det_id`) REFERENCES `pay_details` (`det_id`);

--
-- Constraints for table `pay_details`
--
ALTER TABLE `pay_details`
  ADD CONSTRAINT `pay_details_ibfk_1` FOREIGN KEY (`pay_id`) REFERENCES `pay` (`id`);

-- Inserting some sample data for all the tables. 

  -- Step 1: Insert data into `employee` table
INSERT INTO `employee` (`E_Ssn`, `role`) VALUES
(123456789, 'associate'),
(987654321, 'hr'),
(555555555, 'accountant');

-- Step 2: Insert data into `hr` table
INSERT INTO `hr` (`hr_Ssn`, `email`, `F_name`, `L_name`, `phone_no`) VALUES
(987654321, 'asmith_hr@example.com', 'Alice', 'Smith', '555-5678');

-- Step 3: Insert data into `accountant` table
INSERT INTO `accountant` (`acc_Ssn`, `email`, `F_name`, `L_name`, `phone_no`) VALUES
(555555555, 'mjohnson_acc@example.com', 'Mark', 'Johnson', '555-8765');

-- Step 4: Insert data into `associate` table
INSERT INTO `associate` (`acc_Ssn`, `email`, `F_name`, `L_name`, `phone_no`) VALUES
(123456789, 'jdoe@example.com', 'John', 'Doe', '555-1234');

-- Step 5: Insert data into `login` table
INSERT INTO `login` (`username`, `password`) VALUES
('jdoe', 'password123'),
('asmith', 'securePass!23'),
('mjohnson', 'P@ssw0rd');

-- Step 6: Insert data into `access` table
INSERT INTO `access` (`hr_Ssn`, `username`, `password`) VALUES
(987654321, 'asmith', 'securePass!23');

-- Step 7: Insert data into `company` table
INSERT INTO `company` (`Name`, `Address`) VALUES
('Tech Solutions', '123 Tech Street'),
('Innovate Inc.', '456 Innovation Way'),
('Global Corp', '789 Global Blvd');

-- Step 8: Insert data into `employs` table
INSERT INTO `employs` (`C_name`, `E_Ssn`) VALUES
('Tech Solutions', 123456789),
('Innovate Inc.', 987654321),
('Global Corp', 555555555);

-- Step 9: Insert data into `pay` table
INSERT INTO `pay` (`id`, `acc_num`, `rout_num`) VALUES
(1, 123456, 654321),
(2, 123457, 654322),
(3, 123458, 654323);

-- Step 10: Insert data into `gets` table
INSERT INTO `gets` (`E_Ssn`, `pay_id`) VALUES
(123456789, 1),
(987654321, 2),
(555555555, 3);

-- Step 11: Insert data into `has` table
INSERT INTO `has` (`E_Ssn`, `username`) VALUES
(123456789, 'jdoe'),
(987654321, 'asmith'),
(555555555, 'mjohnson');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
