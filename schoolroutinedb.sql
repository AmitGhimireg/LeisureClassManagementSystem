-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2025 at 04:43 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `schoolroutinedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_routines`
--

CREATE TABLE `academic_routines` (
  `ar_id` int(11) NOT NULL,
  `day` enum('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `subject_id` int(11) NOT NULL,
  `is_break` enum('0','1') NOT NULL DEFAULT '0',
  `class_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `adm_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pan` varchar(20) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `otp` varchar(10) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `level` enum('basic','secondary') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `att_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent','Leave') NOT NULL,
  `reason` text DEFAULT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`att_id`, `teacher_id`, `date`, `status`, `reason`, `recorded_at`) VALUES
(1, 1, '2025-08-03', 'Absent', 'Sick', '2025-08-03 14:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `cls_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `section` enum('A','B','C') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`cls_id`, `name`, `section`, `created_at`) VALUES
(1, '4', 'A', '2025-08-03 14:16:02'),
(2, '4', 'B', '2025-08-03 14:16:08'),
(3, '5', 'A', '2025-08-03 14:16:14'),
(4, '5', 'B', '2025-08-03 14:16:20'),
(5, '6', 'A', '2025-08-03 14:16:27'),
(6, '6', 'B', '2025-08-03 14:16:32'),
(7, '7', '', '2025-08-03 14:16:36'),
(8, '8', '', '2025-08-03 14:16:42'),
(9, '9', '', '2025-08-03 14:16:47');

-- --------------------------------------------------------

--
-- Table structure for table `contact_msgs`
--

CREATE TABLE `contact_msgs` (
  `cm_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_msgs`
--

INSERT INTO `contact_msgs` (`cm_id`, `full_name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'Amit Ghimire', 'amitghimire100@gmail.com', 'Leave letter', 'I am unable to come to school because I am sick.', '2025-08-03 10:38:09');

-- --------------------------------------------------------

--
-- Table structure for table `leisure_routines`
--

CREATE TABLE `leisure_routines` (
  `lr_id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `class_taken_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leisure_routines`
--

INSERT INTO `leisure_routines` (`lr_id`, `teacher_id`, `class_taken_by`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `school_details`
--

CREATE TABLE `school_details` (
  `sd_id` int(11) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `opening_hour` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `school_details`
--

INSERT INTO `school_details` (`sd_id`, `address`, `phone`, `email`, `website`, `opening_hour`, `created_at`) VALUES
(1, 'Khandbari-9 Tumlingtar, Sankhuwasabha', '०२९-५७५०७२', 'manakamanara2016@gmail.com', 'https://manakamanara.edu.np/', '10:00 to 4:00', '2025-08-03 14:22:11');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subj_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subj_id`, `name`, `created_at`) VALUES
(1, 'Math', '2025-08-03 14:17:07'),
(2, 'Science', '2025-08-03 14:17:14'),
(3, 'English', '2025-08-03 14:17:24'),
(4, 'Nepali', '2025-08-03 14:17:30'),
(5, 'Social', '2025-08-03 14:17:41'),
(6, 'Local', '2025-08-03 14:17:55'),
(7, 'Grammar', '2025-08-03 14:18:08'),
(8, 'Computer', '2025-08-03 14:18:14'),
(9, 'G.K.', '2025-08-03 14:18:21'),
(10, 'O.P.T. Math', '2025-08-03 14:19:24');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teach_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pan` varchar(20) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `otp` varchar(10) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `level` enum('basic','secondary') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`teach_id`, `username`, `full_name`, `contact`, `email`, `pan`, `photo`, `password`, `otp`, `verified`, `level`, `created_at`) VALUES
(1, 'amitghimire', 'Amit Ghimire', '9807072190', 'amitghimire100@gmail.com', '123456789', 'Teacher_884.jpg', '$2y$10$18SF3vUNhM9F0DuG9hfPdeALpTb/ZNReOUdnH5LuzHGHdf48h2F4.', NULL, 1, 'secondary', '2025-08-03 12:23:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_routines`
--
ALTER TABLE `academic_routines`
  ADD PRIMARY KEY (`ar_id`),
  ADD KEY `fk_academic_class` (`class_id`),
  ADD KEY `fk_academic_subject` (`subject_id`),
  ADD KEY `fk_academic_teacher` (`teacher_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`adm_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `pan` (`pan`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`att_id`),
  ADD UNIQUE KEY `teacher_attendance_unique` (`teacher_id`,`date`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`cls_id`);

--
-- Indexes for table `contact_msgs`
--
ALTER TABLE `contact_msgs`
  ADD PRIMARY KEY (`cm_id`);

--
-- Indexes for table `leisure_routines`
--
ALTER TABLE `leisure_routines`
  ADD PRIMARY KEY (`lr_id`),
  ADD KEY `fk_leisure_taken_by_teacher` (`class_taken_by`),
  ADD KEY `fk_leisure_teacher` (`teacher_id`);

--
-- Indexes for table `school_details`
--
ALTER TABLE `school_details`
  ADD PRIMARY KEY (`sd_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subj_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teach_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `pan` (`pan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_routines`
--
ALTER TABLE `academic_routines`
  MODIFY `ar_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `adm_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `att_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `cls_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `contact_msgs`
--
ALTER TABLE `contact_msgs`
  MODIFY `cm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `leisure_routines`
--
ALTER TABLE `leisure_routines`
  MODIFY `lr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `school_details`
--
ALTER TABLE `school_details`
  MODIFY `sd_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subj_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teach_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `academic_routines`
--
ALTER TABLE `academic_routines`
  ADD CONSTRAINT `fk_academic_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`cls_id`),
  ADD CONSTRAINT `fk_academic_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subj_id`),
  ADD CONSTRAINT `fk_academic_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teach_id`);

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teach_id`);

--
-- Constraints for table `leisure_routines`
--
ALTER TABLE `leisure_routines`
  ADD CONSTRAINT `fk_leisure_taken_by_teacher` FOREIGN KEY (`class_taken_by`) REFERENCES `teachers` (`teach_id`),
  ADD CONSTRAINT `fk_leisure_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teach_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
