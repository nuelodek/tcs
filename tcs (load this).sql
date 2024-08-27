-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 27, 2024 at 04:52 PM
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
-- Database: `tcs`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `facultyid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`Id`, `Name`, `status`, `facultyid`) VALUES
(1, 'Mathematics', NULL, NULL),
(2, 'Computer Science', NULL, NULL),
(3, 'Physics', NULL, NULL),
(4, 'Escuela de Pyton', 'approved', 3),
(5, 'muuuu', 'approved', 1),
(6, 'Sports', 'approved', 2),
(9, 'Music ', 'approved', 7),
(10, 'Fashion', 'approved', 7),
(11, 'dgd7ugqudg', 'approved', 7),
(12, 'politics', 'approved', 7);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `Id` int(11) NOT NULL,
  `Category_Id` int(11) DEFAULT NULL,
  `Name` varchar(255) NOT NULL,
  `Period_Id` int(11) DEFAULT NULL,
  `Type_of_Course_Id` int(11) DEFAULT NULL,
  `Descriptive_Synthesis` text DEFAULT NULL,
  `Development_Competencies` text DEFAULT NULL,
  `Content_Structure` text DEFAULT NULL,
  `Teaching_Strategies` text DEFAULT NULL,
  `Technology_Tools` text DEFAULT NULL,
  `Assessment_Strategies` text DEFAULT NULL,
  `Programmatic_Synopsis` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`Id`, `Category_Id`, `Name`, `Period_Id`, `Type_of_Course_Id`, `Descriptive_Synthesis`, `Development_Competencies`, `Content_Structure`, `Teaching_Strategies`, `Technology_Tools`, `Assessment_Strategies`, `Programmatic_Synopsis`) VALUES
(18, 4, 'Economics of Coding', 1, 1, '1', 'wwjj', 'dj', 'jdjdj', 'jdjdjj', 'jjsj', 'djjdj'),
(19, 5, 'hhhh', 2, 1, 'jj', 'nnn', 'jj', 'jjj', 'jj', 'jj', 'jj'),
(20, 12, 'i', 1, 1, 'ii', 'ii', 'II', 'II', 'II', 'II', 'II');

-- --------------------------------------------------------

--
-- Table structure for table `faculties`
--

CREATE TABLE `faculties` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculties`
--

INSERT INTO `faculties` (`Id`, `Name`) VALUES
(1, 'Facultad de Ingenieria'),
(2, 'Facultad Odontoligia'),
(3, 'Escuela de computacion'),
(7, 'Humanities'),
(8, 'Social Sciences');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `Id` int(11) NOT NULL,
  `Action` varchar(255) NOT NULL,
  `Date` datetime NOT NULL,
  `User_Id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`Id`, `Action`, `Date`, `User_Id`) VALUES
(1, 'course solicited', '2024-08-13 15:01:09', 22),
(8, 'course solicited', '2024-08-13 15:22:33', 24),
(9, 'Approved Course Request', '2024-08-13 16:00:20', 24),
(10, 'Course created by admin', '2024-08-27 13:37:28', NULL),
(11, 'course solicited', '2024-08-27 13:45:44', 24);

-- --------------------------------------------------------

--
-- Table structure for table `periods`
--

CREATE TABLE `periods` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `periods`
--

INSERT INTO `periods` (`Id`, `Name`) VALUES
(1, 'Fall 2024'),
(2, 'Spring 2025');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Applicant_Id` int(11) DEFAULT NULL,
  `Coordinator_Id` int(11) DEFAULT NULL,
  `Status_Id` int(11) DEFAULT NULL,
  `Course_Id` int(11) DEFAULT NULL,
  `Date_Request` datetime NOT NULL,
  `Date_Update` datetime DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`Id`, `Name`, `Applicant_Id`, `Coordinator_Id`, `Status_Id`, `Course_Id`, `Date_Request`, `Date_Update`, `rejection_reason`) VALUES
(11, 'Course Enrollment Request', 24, 17, 1, 18, '2024-08-13 15:01:09', NULL, NULL),
(12, 'Course Request Update', 24, 22, 4, 19, '2024-08-13 15:22:33', '2024-08-13 16:00:20', NULL),
(13, 'Course Enrollment Request', 24, 3, 1, 20, '2024-08-27 13:45:44', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `Id` int(11) NOT NULL,
  `Role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`Id`, `Role`) VALUES
(1, 'Admin'),
(2, 'Coordinator'),
(3, 'Professor'),
(5, 'k');

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`Id`, `Name`) VALUES
(1, 'Harvard University'),
(2, 'Stanford University'),
(3, 'MIT');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `Id` int(11) NOT NULL,
  `State` enum('request_submitted','request_approved','request_rejected','request_created','account_approved','account_rejected') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`Id`, `State`) VALUES
(1, 'request_submitted'),
(2, 'request_approved'),
(3, 'request_rejected'),
(4, 'request_created'),
(5, 'account_approved'),
(6, 'account_rejected');

-- --------------------------------------------------------

--
-- Table structure for table `tempupdate`
--

CREATE TABLE `tempupdate` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tempupdate`
--

INSERT INTO `tempupdate` (`id`, `user_id`, `firstname`, `lastname`, `email`, `phone`, `password`) VALUES
(1, NULL, 'Emmyboy', 'Soji', 'emmanuelodekunle247@gmail.com', '903838383', '@Emmanuel654321'),
(2, NULL, 'Pero', 'Teet', 'pero@gmail.com', '08080808080', '@Emmanuel654321'),
(3, NULL, 'Pero', 'Teet', 'pero@gmail.com', '08080808080', '@Emmanuel654321');

-- --------------------------------------------------------

--
-- Table structure for table `types_of_courses`
--

CREATE TABLE `types_of_courses` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Field` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `types_of_courses`
--

INSERT INTO `types_of_courses` (`Id`, `Name`, `Field`) VALUES
(1, 'Lecture', 'Science'),
(2, 'Lab', 'Engineering');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `First_Name` varchar(255) NOT NULL,
  `Last_Name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Institutional_Email` varchar(255) DEFAULT NULL,
  `Moodle_Id` varchar(255) DEFAULT NULL,
  `Identification_Number` varchar(255) NOT NULL,
  `Faculty_Id` int(11) DEFAULT NULL,
  `Created_At` datetime NOT NULL,
  `Role_Id` int(11) DEFAULT NULL,
  `Password` varchar(255) NOT NULL,
  `Token` varchar(255) DEFAULT NULL,
  `Updated_At` datetime DEFAULT NULL,
  `School_Id` int(11) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Validation` enum('validated','not_validated') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `Username`, `First_Name`, `Last_Name`, `Email`, `Institutional_Email`, `Moodle_Id`, `Identification_Number`, `Faculty_Id`, `Created_At`, `Role_Id`, `Password`, `Token`, `Updated_At`, `School_Id`, `Phone`, `Validation`) VALUES
(1, 'jdoe', 'John', 'Doe', 'jdoe@example.com', 'jdoe@harvard.edu', '15', 'ID123456', 1, '2024-07-19 12:00:00', 3, 'Pass12345-', 'token123', '2024-07-25 14:31:56', 1, '123-456-7890', 'not_validated'),
(2, 'asmith', 'Alice', 'Smith', 'asmith@example.com', 'asmith@stanford.edu', '16', 'ID789012', 2, '2024-07-19 12:00:00', 3, 'Pass1234-', 'token456', '2024-07-25 14:32:32', 2, '234-567-8901', 'validated'),
(3, 'bjonsson', 'Bjorn', 'Jonsson', 'bjonsson@example.com', 'bjonsson@mit.edu', '14', 'ID345678', 3, '2024-07-19 12:00:00', 2, 'Hashedpassword789@', 'token789', '2024-07-24 14:14:10', 3, '345-678-9012', 'validated'),
(17, 'Adeleke', 'Emmanuel', 'Odekunle', 'nuelodekunle20@gmail.com', 'emmy2@gmail.com', '17', '12345', 1, '2024-07-25 14:12:24', 2, '@Emmanuel654321', NULL, '2024-07-25 15:29:11', 1, '123456', 'validated'),
(20, 'Tayo', 'Gershom', 'Tayo', 'gershomtayo@gmail.com', 'gershomtayo@express.com', NULL, '910191019', 1, '2024-07-25 14:14:32', 1, 'Gershom1234-', NULL, NULL, 1, '19293494959', 'validated'),
(21, 'tesbousot', 'losl', 'kckck', 'askchronicler@gmail.com', NULL, '19', 'dddd', 1, '2024-07-25 15:31:38', 2, 'Test@1234', NULL, '2024-07-25 15:46:27', 1, NULL, 'not_validated'),
(22, 'emmylinlldl', 'Emmyboy', 'Soji', 'emmanuelodekunle247@gmail.com', 'askforemmy@gmail.com', '26', 'kkk', 1, '2024-07-25 18:25:33', 2, 'Teet@1234', NULL, '2024-07-26 14:09:17', 1, '903838383', 'validated'),
(24, 'emmy212121', 'Temitaye', 'Blessing', 'ask4emmanuel@gmail.com', 'ask4emmanuel@gmail.com', '27', '12349884994944949', 7, '2024-08-09 12:39:26', 3, '@Pass12345-', NULL, '2024-08-09 13:59:09', 2, '08020294929', 'validated'),
(25, 'Pero', 'Pero', 'Teet', 'pero@gmail.com', 'pero@gmail.com', NULL, '0', 7, '2024-08-22 12:37:20', 3, 'Teet@1234-', NULL, NULL, 1, '08080808080', 'not_validated');

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE `user_permissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `permission` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_permissions`
--

INSERT INTO `user_permissions` (`id`, `user_id`, `permission`, `created_at`, `updated_at`) VALUES
(3, 22, 'solicit', '2024-08-14 11:15:57', '2024-08-14 11:15:57');

-- --------------------------------------------------------

--
-- Table structure for table `user_request`
--

CREATE TABLE `user_request` (
  `Id` int(11) NOT NULL,
  `User_Id` int(11) DEFAULT NULL,
  `Status_Id` int(11) DEFAULT NULL,
  `Date_Request` datetime NOT NULL,
  `Notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_request`
--

INSERT INTO `user_request` (`Id`, `User_Id`, `Status_Id`, `Date_Request`, `Notes`) VALUES
(1, 1, 1, '2024-07-19 12:00:00', 'Initial request for course enrollment'),
(2, 2, 2, '2024-07-19 12:00:00', 'Course request approved');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_faculty` (`facultyid`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Category_Id` (`Category_Id`),
  ADD KEY `Period_Id` (`Period_Id`),
  ADD KEY `Type_of_Course_Id` (`Type_of_Course_Id`);

--
-- Indexes for table `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `User_Id` (`User_Id`);

--
-- Indexes for table `periods`
--
ALTER TABLE `periods`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Applicant_Id` (`Applicant_Id`),
  ADD KEY `Coordinator_Id` (`Coordinator_Id`),
  ADD KEY `Status_Id` (`Status_Id`),
  ADD KEY `Course_Id` (`Course_Id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tempupdate`
--
ALTER TABLE `tempupdate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `types_of_courses`
--
ALTER TABLE `types_of_courses`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Identification_Number` (`Identification_Number`),
  ADD KEY `Faculty_Id` (`Faculty_Id`),
  ADD KEY `Role_Id` (`Role_Id`),
  ADD KEY `School_Id` (`School_Id`);

--
-- Indexes for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `user_request`
--
ALTER TABLE `user_request`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `User_Id` (`User_Id`),
  ADD KEY `Status_Id` (`Status_Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `faculties`
--
ALTER TABLE `faculties`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `periods`
--
ALTER TABLE `periods`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tempupdate`
--
ALTER TABLE `tempupdate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `types_of_courses`
--
ALTER TABLE `types_of_courses`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_request`
--
ALTER TABLE `user_request`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_faculty` FOREIGN KEY (`facultyid`) REFERENCES `faculties` (`Id`);

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`Category_Id`) REFERENCES `categories` (`Id`),
  ADD CONSTRAINT `courses_ibfk_2` FOREIGN KEY (`Period_Id`) REFERENCES `periods` (`Id`),
  ADD CONSTRAINT `courses_ibfk_3` FOREIGN KEY (`Type_of_Course_Id`) REFERENCES `types_of_courses` (`Id`);

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`User_Id`) REFERENCES `users` (`Id`);

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`Applicant_Id`) REFERENCES `users` (`Id`),
  ADD CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`Coordinator_Id`) REFERENCES `users` (`Id`),
  ADD CONSTRAINT `requests_ibfk_3` FOREIGN KEY (`Status_Id`) REFERENCES `status` (`Id`),
  ADD CONSTRAINT `requests_ibfk_4` FOREIGN KEY (`Course_Id`) REFERENCES `courses` (`Id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`Faculty_Id`) REFERENCES `faculties` (`Id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`Role_Id`) REFERENCES `roles` (`Id`),
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`School_Id`) REFERENCES `schools` (`Id`);

--
-- Constraints for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD CONSTRAINT `user_permissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `user_request`
--
ALTER TABLE `user_request`
  ADD CONSTRAINT `user_request_ibfk_1` FOREIGN KEY (`User_Id`) REFERENCES `users` (`Id`),
  ADD CONSTRAINT `user_request_ibfk_2` FOREIGN KEY (`Status_Id`) REFERENCES `status` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;