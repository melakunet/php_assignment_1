-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 19, 2026 at 03:00 PM
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
-- Table structure for table `email_logs`
--

CREATE TABLE `email_logs` (
  `email_log_id` int(11) NOT NULL,
  `to_address` varchar(255) NOT NULL,
  `to_name` varchar(100) NOT NULL,
  `from_address` varchar(255) NOT NULL,
  `from_name` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `email_type` varchar(50) DEFAULT 'General',
  `sent_date` datetime DEFAULT current_timestamp(),
  `status` enum('Queued','Sent','Failed') DEFAULT 'Sent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_logs`
--

INSERT INTO `email_logs` (`email_log_id`, `to_address`, `to_name`, `from_address`, `from_name`, `subject`, `body`, `email_type`, `sent_date`, `status`) VALUES
(1, 'john.smith@company.com', 'John Smith', 'noreply@attendance.com', 'Attendance System', 'Late Arrival Notice - January 22, 2026', '<h3>Late Arrival Notification</h3><p>Dear John Smith,</p><p>You were marked <strong>Late</strong> on January 22, 2026.</p><p>Scheduled Time: 09:00:00</p><p>Check-in Time: 09:15:00</p><p>Please ensure to arrive on time for future shifts.</p>', 'Late Notification', '2026-01-22 09:15:30', 'Sent'),
(2, 'sarah.jones@company.com', 'Sarah Jones', 'noreply@attendance.com', 'Attendance System', 'Welcome to Worker Attendance System!', '<h2>Welcome!</h2><p>Dear Sarah,</p><p>Your account has been successfully created.</p><p>You can now access the Worker Attendance System.</p>', 'Welcome', '2026-01-15 10:00:00', 'Sent'),
(3, 'manager@company.com', 'HR Manager', 'noreply@attendance.com', 'Attendance System', 'Daily Attendance Report - January 29, 2026', '<h2>Daily Attendance Summary</h2><p><strong>Date:</strong> January 29, 2026</p><p><strong>Total Present:</strong> 2</p><p><strong>Total Late:</strong> 4</p><p><strong>Total Absent:</strong> 3</p><hr><h3>Late Arrivals:</h3><ul><li>David Lee - 14:18:56</li><li>Mike Chen - 14:20:32</li></ul>', 'Daily Summary', '2026-01-29 17:00:00', 'Sent');

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

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`userID`, `userName`, `password`, `emailAddress`, `failed_attempts`, `last_failed_login`) VALUES
(1, 'Abel', '$2y$10$LBG03a7rDlcXHVmVeQPytOwCmFbDrgOF8SjGfVEnDZ6Ktwcg1Eu2e', 'abelconltd@gmail.com', 3, '2026-02-12 09:19:22'),
(2, 'John ', '$2y$10$LHfHkMIM6Af1dsD5Ytm2muEGPDCUM6fT7xikhGP0XycxUJ6pKzxSy', 'john.smith@company.com', 0, NULL),
(3, 'etef', '$2y$10$ItucrbKTsGLj2qtmfvPn3.PiRx1oJfz.nBZ131MeugJ0.D5Uks4VS', '12345', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `skill_id` int(11) NOT NULL,
  `skill_name` varchar(100) NOT NULL,
  `skill_category` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`skill_id`, `skill_name`, `skill_category`, `description`) VALUES
(1, 'Customer Service', 'Soft Skills', 'Excellent customer interaction and problem solving'),
(2, 'Cash Handling', 'Technical', 'Cashier operations and money management'),
(3, 'Inventory Management', 'Technical', 'Stock tracking, ordering, and warehouse operations'),
(4, 'Sales Techniques', 'Soft Skills', 'Upselling, product knowledge, and closing deals'),
(5, 'Team Leadership', 'Management', 'Leading, motivating, and managing team members'),
(6, 'POS Systems', 'Technical', 'Point of Sale software and hardware proficiency'),
(7, 'Communication', 'Soft Skills', 'Effective verbal and written communication'),
(8, 'Time Management', 'Soft Skills', 'Prioritization and efficient task completion'),
(9, 'Problem Solving', 'Soft Skills', 'Analytical thinking and creative solutions'),
(10, 'Microsoft Office', 'Technical', 'Word, Excel, PowerPoint proficiency');

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
(4, 'Emma Davis Sun', '555-010-2525', 'emma.davis@company.com', '2026-01-20', 'worker_698de36b3805c_1770906475.jpg', 4, 'Stock'),
(5, 'David.J Lead ', '1234567890', 'david.lee@company.com', '2026-01-18', 'worker_698de34e91997_1770906446.jpg', 3, 'Cashier'),
(6, 'Lisa Wang', '555-0106', 'lisa.wang@company.com', '2026-01-22', 'worker_6984b5c7caf39_1770304967.png', 3, 'Cashier'),
(7, 'hana', '2346578799', 'hana@company.com', '2026-01-25', 'worker_698de316c811e_1770906390.jpg', 1, 'Sales'),
(8, 'semira Mola', '3455768877', 'semira.mola@company.com', '2026-01-28', 'worker_698de43da4315_1770906685.png', 2, 'Manager'),
(10, 'Makeda Habtu', '251 199 6777', 'makeda@company.com', '2026-02-05', 'worker_6984b684f2b3d_1770305156.png', 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `worker_skills`
--

CREATE TABLE `worker_skills` (
  `worker_skill_id` int(11) NOT NULL,
  `worker_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `proficiency_level` enum('Beginner','Intermediate','Advanced','Expert') DEFAULT 'Intermediate',
  `date_acquired` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `worker_skills`
--

INSERT INTO `worker_skills` (`worker_skill_id`, `worker_id`, `skill_id`, `proficiency_level`, `date_acquired`) VALUES
(1, 1, 1, 'Advanced', '2026-01-15'),
(2, 1, 4, 'Expert', '2026-01-15'),
(3, 1, 7, 'Advanced', '2026-01-15'),
(4, 2, 1, 'Intermediate', '2026-01-15'),
(5, 2, 2, 'Expert', '2026-01-15'),
(6, 2, 6, 'Advanced', '2026-01-15'),
(7, 2, 7, 'Advanced', '2026-01-15'),
(8, 3, 5, 'Expert', '2026-01-10'),
(9, 3, 1, 'Advanced', '2026-01-10'),
(10, 3, 9, 'Expert', '2026-01-10'),
(11, 3, 10, 'Advanced', '2026-01-10'),
(12, 4, 3, 'Advanced', '2026-01-20'),
(13, 4, 8, 'Intermediate', '2026-01-20'),
(14, 4, 9, 'Intermediate', '2026-01-20'),
(15, 5, 2, 'Advanced', '2026-01-18'),
(16, 5, 6, 'Intermediate', '2026-01-18'),
(17, 5, 1, 'Intermediate', '2026-01-18'),
(18, 6, 2, 'Expert', '2026-01-22'),
(19, 6, 6, 'Advanced', '2026-01-22'),
(20, 6, 1, 'Advanced', '2026-01-22'),
(21, 7, 4, 'Intermediate', '2026-01-25'),
(22, 7, 1, 'Intermediate', '2026-01-25'),
(23, 7, 7, 'Beginner', '2026-01-25'),
(24, 8, 5, 'Advanced', '2026-01-28'),
(25, 8, 10, 'Expert', '2026-01-28'),
(26, 8, 9, 'Advanced', '2026-01-28'),
(27, 10, 1, 'Intermediate', '2026-02-05'),
(28, 10, 8, 'Advanced', '2026-02-05');

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
-- Indexes for table `email_logs`
--
ALTER TABLE `email_logs`
  ADD PRIMARY KEY (`email_log_id`),
  ADD KEY `idx_email_type` (`email_type`),
  ADD KEY `idx_sent_date` (`sent_date`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `userName` (`userName`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`skill_id`);

--
-- Indexes for table `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`worker_id`),
  ADD KEY `fk_workers_department` (`department_id`);

--
-- Indexes for table `worker_skills`
--
ALTER TABLE `worker_skills`
  ADD PRIMARY KEY (`worker_skill_id`),
  ADD UNIQUE KEY `unique_worker_skill` (`worker_id`,`skill_id`),
  ADD KEY `worker_id` (`worker_id`),
  ADD KEY `skill_id` (`skill_id`);

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
-- AUTO_INCREMENT for table `email_logs`
--
ALTER TABLE `email_logs`
  MODIFY `email_log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `skill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `workers`
--
ALTER TABLE `workers`
  MODIFY `worker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `worker_skills`
--
ALTER TABLE `worker_skills`
  MODIFY `worker_skill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

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

--
-- Constraints for table `worker_skills`
--
ALTER TABLE `worker_skills`
  ADD CONSTRAINT `fk_ws_skill` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`skill_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ws_worker` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`worker_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
