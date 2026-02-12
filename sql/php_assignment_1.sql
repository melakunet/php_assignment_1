-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 12, 2026 at 02:50 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php_assignment_1`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `worker_id` int(11) DEFAULT NULL,
  `scheduled_time` time DEFAULT NULL,
  `check_in_time` time DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `worker_id`, `scheduled_time`, `check_in_time`, `status`, `date`) VALUES
(1, 1, '09:00:00', '08:55:00', 'Present', '2026-01-22'),
(2, 2, '09:00:00', '09:02:00', 'Present', '2026-01-22'),
(3, 3, '09:00:00', '09:00:00', 'Present', '2026-01-22'),
(4, 4, '09:00:00', NULL, 'Absent', '2026-01-22'),
(5, 5, '09:00:00', NULL, 'Absent', '2026-01-22'),
(6, 6, '09:00:00', '09:15:00', 'Late', '2026-01-22'),
(7, 5, '09:00:00', '15:52:16', 'Late', '2026-01-22'),
(8, 7, '10:00:00', '16:21:00', 'Late', '2026-01-22'),
(9, 8, '10:21:00', '16:21:13', 'Late', '2026-01-22'),
(10, 5, '09:00:00', '14:18:56', 'Late', '2026-01-29'),
(11, 3, '09:00:00', '14:20:32', 'Late', '2026-01-29'),
(12, 4, '09:00:00', '14:20:39', 'Late', '2026-01-29'),
(13, 8, '09:00:00', '14:21:26', 'Late', '2026-01-29');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(50) NOT NULL,
  `location` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `location`) VALUES
(1, 'Sales', 'Main Floor'),
(2, 'Management', 'Office 2A'),
(3, 'Cashier', 'Front Desk'),
(4, 'Stock', 'Warehouse');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `userID` int(11) NOT NULL,
  `userName` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `last_failed_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `workers`
--

CREATE TABLE `workers` (
  `worker_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `image_filename` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workers`
--

INSERT INTO `workers` (`worker_id`, `full_name`, `phone`, `email`, `hire_date`, `image_filename`, `department_id`, `role`) VALUES
(1, 'John Smith', '555-0101', 'john.smith@company.com', '2026-01-15', 'john.png', 1, 'Sales'),
(2, 'Sarah Jones', '555-0102', 'sarah.jones@company.com', '2026-01-15', 'sarah.png', 3, 'Cashier'),
(3, 'Mike Chen hu', '555-010-4335', 'mike.chen@company.com', '2026-01-10', 'mike.png', 2, 'Manager'),
(4, 'Emma Davis Sun', '555-010-2525', 'emma.davis@company.com', '2026-01-20', 'emma.png', 4, 'Stock'),
(5, 'David.J Lead ', '1234567890', 'david.lee@company.com', '2026-01-18', 'david.png', 3, 'Cashier'),
(6, 'Lisa Wang', '555-0106', 'lisa.wang@company.com', '2026-01-22', 'worker_6984b5c7caf39_1770304967.png', 3, 'Cashier'),
(7, 'hana', '2346578799', 'hana@company.com', '2026-01-25', 'worker_6984bc10b3502_1770306576.png', 1, 'Sales'),
(8, 'semira Mola', '3455768877', 'semira.mola@company.com', '2026-01-28', NULL, 2, 'Manager'),
(10, 'Makeda Habtu', '251 199 6777', 'makeda@company.com', '2026-02-05', 'worker_6984b684f2b3d_1770305156.png', 2, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `worker_id` (`worker_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `userName` (`userName`);

--
-- Indexes for table `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`worker_id`),
  ADD KEY `fk_workers_department` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workers`
--
ALTER TABLE `workers`
  MODIFY `worker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`worker_id`);

--
-- Constraints for table `workers`
--
ALTER TABLE `workers`
  ADD CONSTRAINT `fk_workers_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
