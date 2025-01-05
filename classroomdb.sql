-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2025 at 04:18 PM
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
-- Database: `classroomdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(3, 'Admin1', 'admin123@gmail.com', '$2y$10$Jr.6JggAzv7U06/H7gLi8.Q/0qTzqwPZwd1CAoEUPr8OaZRGgwi9m', '2024-12-22 17:39:02');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `course_name` varchar(255) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('Pending','Confirmed','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `room_id`, `faculty_id`, `course_name`, `start_time`, `end_time`, `status`, `created_at`) VALUES
(100, 57, 1234, 'CSE101', '2025-01-06 18:30:00', '2025-01-06 19:30:00', 'Rejected', '2025-01-05 12:29:43'),
(112, 88, 2562, NULL, '2025-01-08 14:40:00', '2025-01-08 16:10:00', 'Confirmed', '2025-01-05 15:11:52'),
(113, 88, 2562, NULL, '2025-01-13 14:40:00', '2025-01-13 16:10:00', 'Confirmed', '2025-01-05 15:11:52'),
(114, 88, 2562, NULL, '2025-01-15 14:40:00', '2025-01-15 16:10:00', 'Confirmed', '2025-01-05 15:11:52'),
(115, 89, 2562, NULL, '2025-01-08 16:20:00', '2025-01-08 17:50:00', 'Confirmed', '2025-01-05 15:11:52'),
(116, 89, 2562, NULL, '2025-01-13 16:20:00', '2025-01-13 17:50:00', 'Confirmed', '2025-01-05 15:11:52'),
(117, 89, 2562, NULL, '2025-01-15 16:20:00', '2025-01-15 17:50:00', 'Confirmed', '2025-01-05 15:11:52'),
(118, 57, 1234, 'CMN201', '2025-01-24 21:12:00', '2025-01-24 22:12:00', 'Pending', '2025-01-05 15:12:40'),
(119, 88, 1234, 'MIS111', '2025-01-16 16:12:00', '2025-01-16 17:12:00', 'Confirmed', '2025-01-05 15:13:13'),
(120, 88, 1234, 'SOC401', '2025-01-15 17:10:00', '2025-01-15 18:10:00', 'Pending', '2025-01-05 15:14:45'),
(121, 90, 4321, 'MKT315', '2025-01-09 21:16:00', '2025-01-09 22:16:00', 'Pending', '2025-01-05 15:16:11');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `department` enum('Professor','Assistant Professor','Lecturer') DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `office_room` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `name`, `email`, `department`, `phone_number`, `office_room`, `password`, `created_at`) VALUES
(1234, 'Tausia', 'tausia.tahsin@gmail.com', NULL, NULL, NULL, '$2y$10$BEolxUZA7cjiB7Q54DKYEu.9SwlmcyXfRnhCn.lViM3znZnOn3qZK', '2024-12-20 06:40:21'),
(2562, 'MR Sanjoy Chakraborty', '', NULL, NULL, NULL, '', '2025-01-01 06:59:10'),
(4321, 'Rakin', 'rakin@gmail.com', NULL, NULL, NULL, '$2y$10$4IBd3EtZ9hwWqBZz3X5L8OaNSYKzbRIGjq3.Mw17138nwCRA51WHC', '2025-01-05 15:15:30');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `building` varchar(50) DEFAULT NULL,
  `capacity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `type`, `building`, `capacity`, `created_at`) VALUES
(57, 'BC6006', 'Classroom', 'Main Building', 50, '2025-01-01 06:45:55'),
(88, 'BC10018-S', 'Classroom', NULL, 50, '2025-01-05 15:11:52'),
(89, 'BC9017-S', 'Classroom', NULL, 50, '2025-01-05 15:11:52'),
(90, 'BC2009-S', 'Classroom', NULL, 50, '2025-01-05 15:11:52'),
(91, 'BC2021-S', 'Classroom', NULL, 50, '2025-01-05 15:11:52'),
(93, 'BC2012-S', 'Classroom', NULL, 50, '2025-01-05 15:11:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `faculty_id` (`faculty_id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
