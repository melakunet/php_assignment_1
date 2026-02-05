-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 05, 2026 at 04:06 PM
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
(5, 'David Lead', '1234567890', 'david.lee@company.com', '2026-01-18', 'david.png', 3, 'Cashier'),
(6, 'Lisa Wang', '555-0106', 'lisa.wang@company.com', '2026-01-22', NULL, 3, 'Cashier'),
(7, 'hana', '2346578799', 'hana@company.com', '2026-01-25', 'hana.png', 1, 'Sales'),
(8, 'semira Mola', '3455768877', 'semira.mola@company.com', '2026-01-28', NULL, 2, 'Manager');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `workers`
--
ALTER TABLE `workers`
  MODIFY `worker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `workers`
--
ALTER TABLE `workers`
  ADD CONSTRAINT `fk_workers_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
