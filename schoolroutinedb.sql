-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 13, 2025 at 06:21 PM
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
-- Table structure for table `academic_routine`
--

CREATE TABLE `academic_routine` (
  `ar_id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `subject_id1` int(11) DEFAULT NULL,
  `subject_id2` int(11) DEFAULT NULL,
  `teacher_id1` int(11) DEFAULT NULL,
  `teacher_id2` int(11) DEFAULT NULL,
  `day_range1` enum('1-3','4-6','1-6') DEFAULT NULL,
  `day_range2` enum('1-3','4-6','1-6') DEFAULT NULL,
  `is_break` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_routine`
--

INSERT INTO `academic_routine` (`ar_id`, `class_id`, `start_time`, `end_time`, `subject_id1`, `subject_id2`, `teacher_id1`, `teacher_id2`, `day_range1`, `day_range2`, `is_break`) VALUES
(1, 1, '10:15:00', '11:00:00', 3, NULL, 2, NULL, '1-6', NULL, '0'),
(2, 1, '11:00:00', '11:45:00', 2, NULL, 2, NULL, '1-6', NULL, '0'),
(3, 1, '11:45:00', '12:30:00', 4, NULL, 2, NULL, '1-6', NULL, '0'),
(4, 1, '12:30:00', '13:15:00', 1, NULL, 2, NULL, '1-6', NULL, '0'),
(5, 1, '13:15:00', '14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, '1'),
(9, 1, '14:00:00', '14:40:00', 9, 8, 7, 2, '1-3', '4-6', '0'),
(10, 1, '14:40:00', '15:20:00', 7, NULL, 7, NULL, '1-6', NULL, '0'),
(11, 1, '15:20:00', '16:00:00', 10, 12, 2, 7, '1-3', '4-6', '0');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `att_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent') NOT NULL,
  `reason` text DEFAULT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`att_id`, `teacher_id`, `date`, `status`, `reason`, `recorded_at`) VALUES
(11, 2, '2025-08-12', 'Present', 'ok', '2025-08-13 08:26:44'),
(12, 2, '2025-08-13', 'Absent', 'Sick', '2025-08-13 08:27:05');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `cls_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `section` enum('A','B','C','NA') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`cls_id`, `name`, `section`, `created_at`) VALUES
(1, '4', 'A', '2025-08-11 11:54:01'),
(2, '4', 'B', '2025-08-11 11:54:08'),
(3, '5', 'A', '2025-08-11 11:54:14'),
(4, '5', 'B', '2025-08-11 11:54:19'),
(5, '6', 'A', '2025-08-11 11:54:25'),
(6, '6', 'B', '2025-08-11 11:54:34'),
(7, '7', 'NA', '2025-08-11 11:54:39'),
(8, '8', 'NA', '2025-08-11 11:54:42');

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
(2, 'Amit Ghimire', 'amitghimire102@gmail.com', 'Inquiry', 'Hello.', '2025-08-13 06:52:29');

-- --------------------------------------------------------

--
-- Table structure for table `leisure_routines`
--

CREATE TABLE `leisure_routines` (
  `lr_id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `class_taken_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leisure_teacher`
--

CREATE TABLE `leisure_teacher` (
  `lt_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `st_time` time DEFAULT NULL,
  `ed_time` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leisure_teacher`
--

INSERT INTO `leisure_teacher` (`lt_id`, `teacher_id`, `st_time`, `ed_time`, `created_at`) VALUES
(1, 2, '10:15:00', '11:00:00', '2025-08-13 15:27:30'),
(4, 7, '10:15:00', '11:00:00', '2025-08-13 15:39:36');

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
(2, 'Khandbari-9 Tumlingtar, Sankhuwasabha', '9807072190', 'manakamana2016@gmail.com', 'www.manakamanara.edu.np', '10.00 AM - 4.00 PM', '2025-08-12 11:58:49');

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
(1, 'MATH', '2025-08-11 11:55:17'),
(2, 'SCIENCE', '2025-08-11 11:55:27'),
(3, 'ENGLISH', '2025-08-11 11:55:33'),
(4, 'NEPALI', '2025-08-11 11:55:38'),
(6, 'HEALTH', '2025-08-11 11:55:48'),
(7, 'GRAMMAR', '2025-08-11 11:56:19'),
(8, 'COMPUTER', '2025-08-11 11:56:26'),
(9, 'LOCAL', '2025-08-11 11:56:31'),
(10, 'O.P.T. MATH', '2025-08-11 11:56:39'),
(11, 'G.K.', '2025-08-11 11:56:45'),
(12, 'Social', '2025-08-12 15:16:45');

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
  `role` varchar(50) NOT NULL DEFAULT 'teacher',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`teach_id`, `username`, `full_name`, `contact`, `email`, `pan`, `photo`, `password`, `otp`, `verified`, `level`, `role`, `created_at`) VALUES
(1, 'admin', 'Admin', '9807072190', 'amitghimire100@gmail.com', '123456789', 'teacher_526.jpg', '$2y$10$Z57XsIjN6A7.A6.way/vtOrZCtucN9qtPm5Q0tXf8BvZ1KR69f4N.', NULL, 1, 'secondary', 'admin', '2025-08-11 13:02:52'),
(2, 'Amit', 'Amit Ghimire', '9862195577', 'amitghimire102@gmail.com', '12345678', 'teacher_3643.jpg', '$2y$10$Siv7IpoNZrvVcqyfaHdVKe1fQIoHw9tF5nYXgY.UAzB6O3/aFC1lS', NULL, 1, 'secondary', 'teacher', '2025-08-11 13:38:26'),
(7, 'dai', 'Dai khanal', '9862195577', 'amitghimire10234@gmail.com', '123456', 'teacher_4255.png', '$2y$10$Pp3t75b/HrDjWSmuLFRmDeBO5Vbh3b2Vl4FxtfAMse5oQutXCpxm6', NULL, 1, 'secondary', 'teacher', '2025-08-12 15:23:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_routine`
--
ALTER TABLE `academic_routine`
  ADD PRIMARY KEY (`ar_id`),
  ADD KEY `fk_academic_class` (`class_id`),
  ADD KEY `fk_academic_subject` (`subject_id1`),
  ADD KEY `fk_academic_teacher1` (`teacher_id1`),
  ADD KEY `fk_academic_teacher2` (`teacher_id2`),
  ADD KEY `fk_academic_subject2` (`subject_id2`);

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
-- Indexes for table `leisure_teacher`
--
ALTER TABLE `leisure_teacher`
  ADD PRIMARY KEY (`lt_id`),
  ADD KEY `leisure_teacher` (`teacher_id`);

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
-- AUTO_INCREMENT for table `academic_routine`
--
ALTER TABLE `academic_routine`
  MODIFY `ar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `att_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `cls_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `contact_msgs`
--
ALTER TABLE `contact_msgs`
  MODIFY `cm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `leisure_routines`
--
ALTER TABLE `leisure_routines`
  MODIFY `lr_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leisure_teacher`
--
ALTER TABLE `leisure_teacher`
  MODIFY `lt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `school_details`
--
ALTER TABLE `school_details`
  MODIFY `sd_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subj_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teach_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `academic_routine`
--
ALTER TABLE `academic_routine`
  ADD CONSTRAINT `fk_academic_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`cls_id`),
  ADD CONSTRAINT `fk_academic_subject` FOREIGN KEY (`subject_id1`) REFERENCES `subjects` (`subj_id`),
  ADD CONSTRAINT `fk_academic_subject2` FOREIGN KEY (`subject_id2`) REFERENCES `subjects` (`subj_id`),
  ADD CONSTRAINT `fk_academic_teacher1` FOREIGN KEY (`teacher_id1`) REFERENCES `teachers` (`teach_id`),
  ADD CONSTRAINT `fk_academic_teacher2` FOREIGN KEY (`teacher_id2`) REFERENCES `teachers` (`teach_id`);

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

--
-- Constraints for table `leisure_teacher`
--
ALTER TABLE `leisure_teacher`
  ADD CONSTRAINT `leisure_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teach_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
