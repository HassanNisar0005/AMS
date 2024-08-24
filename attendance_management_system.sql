-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2024 at 08:51 PM
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
-- Database: `attendance_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `AttendanceID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Status` enum('present','absent') NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`AttendanceID`, `UserID`, `Date`, `Status`, `CreatedAt`) VALUES
(1, 4, '2024-08-22', 'present', '2024-08-22 18:44:56'),
(2, 9, '2024-08-22', 'present', '2024-08-22 18:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `grading_system`
--

CREATE TABLE `grading_system` (
  `GradeID` int(11) NOT NULL,
  `Grade` varchar(1) NOT NULL,
  `Threshold` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `LeaveID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `LeaveReason` text NOT NULL,
  `LeaveDate` date NOT NULL,
  `Status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`LeaveID`, `UserID`, `LeaveReason`, `LeaveDate`, `Status`, `CreatedAt`) VALUES
(1, 4, 'Sick leave', '2024-08-24', 'pending', '2024-08-22 18:44:41'),
(2, 9, 'marriage leave application', '2024-08-26', 'pending', '2024-08-22 18:46:07'),
(3, 10, 'function leave application', '2024-08-23', 'pending', '2024-08-22 18:47:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Useremail` varchar(255) NOT NULL,
  `ProfilePicture` varchar(255) DEFAULT 'default.jpg',
  `Password` varchar(255) NOT NULL,
  `UserType` enum('admin','student') NOT NULL DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Useremail`, `ProfilePicture`, `Password`, `UserType`) VALUES
(1, 'ahmed_khan', 'ahmed.khan@example.com', 'boy.jpg', '$2y$10$KVkfPnm.eG3doNo1bGdJ8.LvQNU8QuqnvG3XtosqFZD9phl0Z6b0C', 'student'),
(4, 'aisha_farooq', 'aisha.farooq@example.com', 'girl.jpg', '$2y$10$KVkfPnm.eG3doNo1bGdJ8.LvQNU8QuqnvG3XtosqFZD9phl0Z6b0C', 'student'),
(9, 'yusuf_malik', 'yusuf.malik@example.com', 'boy.jpg', '$2y$10$KVkfPnm.eG3doNo1bGdJ8.LvQNU8QuqnvG3XtosqFZD9phl0Z6b0C', 'student'),
(10, 'asma_saeed', 'asma.saeed@example.com', 'boy.jpg', '$2y$10$KVkfPnm.eG3doNo1bGdJ8.LvQNU8QuqnvG3XtosqFZD9phl0Z6b0C', 'student'),
(11, 'Hassan', 'hassan@gmail.com', 'default.jpg', '$2y$10$KVkfPnm.eG3doNo1bGdJ8.LvQNU8QuqnvG3XtosqFZD9phl0Z6b0C', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`AttendanceID`),
  ADD KEY `idx_user_id` (`UserID`);

--
-- Indexes for table `grading_system`
--
ALTER TABLE `grading_system`
  ADD PRIMARY KEY (`GradeID`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`LeaveID`),
  ADD KEY `idx_user_id_leave` (`UserID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Useremail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `AttendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `grading_system`
--
ALTER TABLE `grading_system`
  MODIFY `GradeID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `LeaveID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
