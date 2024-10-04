-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2023 at 01:39 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `university`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `Course_id` int(11) NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`Course_id`, `Name`, `faculty_id`, `dept_id`, `credits`) VALUES
(23001, 'Matrix Computation', 5, 1, 3),
(23002, 'Data Structures and Algorithm', 4, 3, 6),
(23003, 'Machine learning', 8, 3, 6),
(23004, 'Data Base Management system', 8, 4, 6);

--
-- Triggers `courses`
--
DELIMITER $$
CREATE TRIGGER `after_course_creation` AFTER INSERT ON `courses` FOR EACH ROW BEGIN
    -- Enroll all students in the newly added course
    INSERT INTO taken (Course_id, student_id)
    SELECT NEW.Course_id, s.StudentID
    FROM student s
    WHERE s.departmentID = NEW.dept_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `dept_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `hod` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`dept_id`, `name`, `hod`) VALUES
(1, 'UNNASIGNED', NULL),
(3, 'Data Science', 4),
(4, 'Computer Science', 6);

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `faculty_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `father_name` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `qualification` varchar(255) NOT NULL,
  `experience` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `salary` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`faculty_id`, `name`, `dob`, `father_name`, `gender`, `mobile`, `email`, `qualification`, `experience`, `dept_id`, `salary`) VALUES
(4, 'Nandeesh H U', '2000-02-18', 'Umesh H', 'Male', '07026061715', '10nandeeshhu@gmail.com', 'MTECH', 12, 3, '1000000.00'),
(5, 'Pushpender', '2000-02-18', 'Father', 'Male', '4578123265', 'abc@gmail.com', 'MTECH', 0, 3, '99999999.99'),
(6, 'Manikanta Dandi', '1999-11-13', 'Father', 'Male', '7845651232', 'abcd@gmail.com', 'MTECH', 20, 4, '1000000.00'),
(7, 'Bhushan Buckchodi', '2002-02-02', 'Father', 'Male', '4521533222', 'buckchodi@gmail.com', 'MTECH', 12, 1, '1000000.00'),
(8, 'Gourav Trivedi', '1965-02-02', 'Trivedi', 'Male', '7845656544', 'gtrivedi@gmail.com', 'PHD', 30, 3, '2500000.00');

-- --------------------------------------------------------

--
-- Table structure for table `fees_details`
--

CREATE TABLE `fees_details` (
  `Student_id` int(11) DEFAULT NULL,
  `fees_type` varchar(255) DEFAULT NULL,
  `fee_paid` decimal(10,2) DEFAULT NULL,
  `transaction_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `fees_details`
--
DELIMITER $$
CREATE TRIGGER `before_insert_Fees_details` BEFORE INSERT ON `fees_details` FOR EACH ROW BEGIN
    DECLARE date_str VARCHAR(8);
    DECLARE increment_val INT;
    DECLARE new_transaction_id VARCHAR(20);

    -- Extract the current date in the format yyyymmdd
    SET date_str = DATE_FORMAT(NOW(), '%Y%m%d');

    -- Find the next increment value
    SELECT COALESCE(MAX(SUBSTRING(transaction_id, 11) + 1), 1) INTO increment_val
    FROM Fees_details
    WHERE SUBSTRING(transaction_id, 3, 8) = date_str;

    -- Generate the new transaction_id
    SET new_transaction_id = CONCAT('TN', date_str, LPAD(increment_val, 6, '0'));

    -- Set the new_transaction_id for the current insert
    SET NEW.transaction_id = new_transaction_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `hostels`
--

CREATE TABLE `hostels` (
  `hostel_id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `ESTD` date NOT NULL,
  `capacity` int(11) NOT NULL,
  `warden_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hostels`
--

INSERT INTO `hostels` (`hostel_id`, `Name`, `ESTD`, `capacity`, `warden_id`) VALUES
(1, 'UMIAM', '2000-02-18', 1000, 5),
(2, 'DANASRI', '2001-02-20', 10000, 6),
(3, 'Bhrahma Putra', '2000-02-18', 2000, 4),
(7, 'Barak', '1996-10-02', 600, 7);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `StudentID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `FatherName` varchar(255) NOT NULL,
  `DOB` date NOT NULL,
  `Gender` varchar(10) NOT NULL,
  `Mobile` varchar(15) NOT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `HostelID` int(11) DEFAULT NULL,
  `RoomNo` int(11) DEFAULT NULL,
  `departmentID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`StudentID`, `Name`, `FatherName`, `DOB`, `Gender`, `Mobile`, `Email`, `HostelID`, `RoomNo`, `departmentID`) VALUES
(234161004, 'Nandeesh H U', '', '2000-02-18', 'Male', '7026061715', '10nandeeshhu@gmail.com', 1, 202, 3);

--
-- Triggers `student`
--
DELIMITER $$
CREATE TRIGGER `after_student_registration` AFTER INSERT ON `student` FOR EACH ROW BEGIN
    -- Enroll the student in all courses of their department
    INSERT INTO taken(Course_id, student_id)
    SELECT c.Course_id, NEW.studentID
    FROM courses c
    WHERE c.dept_id = NEW.departmentID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `taken`
--

CREATE TABLE `taken` (
  `Course_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `taken`
--

INSERT INTO `taken` (`Course_id`, `student_id`) VALUES
(23002, 234161004),
(23003, 234161004);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`Course_id`),
  ADD UNIQUE KEY `uc_Course_id` (`Course_id`),
  ADD KEY `faculty_id` (`faculty_id`),
  ADD KEY `dept_id` (`dept_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`dept_id`),
  ADD KEY `fk_department_hod` (`hod`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`faculty_id`),
  ADD KEY `dept_id` (`dept_id`);

--
-- Indexes for table `fees_details`
--
ALTER TABLE `fees_details`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `Student_id` (`Student_id`);

--
-- Indexes for table `hostels`
--
ALTER TABLE `hostels`
  ADD PRIMARY KEY (`hostel_id`),
  ADD KEY `fk_warden` (`warden_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`StudentID`),
  ADD KEY `HostelID` (`HostelID`),
  ADD KEY `departmentID` (`departmentID`);

--
-- Indexes for table `taken`
--
ALTER TABLE `taken`
  ADD PRIMARY KEY (`Course_id`,`student_id`),
  ADD KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `Course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23005;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `dept_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `hostels`
--
ALTER TABLE `hostels`
  MODIFY `hostel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `StudentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=234161005;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`),
  ADD CONSTRAINT `courses_ibfk_2` FOREIGN KEY (`dept_id`) REFERENCES `department` (`dept_id`);

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `fk_department_hod` FOREIGN KEY (`hod`) REFERENCES `faculty` (`faculty_id`) ON DELETE CASCADE;

--
-- Constraints for table `faculty`
--
ALTER TABLE `faculty`
  ADD CONSTRAINT `faculty_ibfk_1` FOREIGN KEY (`dept_id`) REFERENCES `department` (`dept_id`);

--
-- Constraints for table `fees_details`
--
ALTER TABLE `fees_details`
  ADD CONSTRAINT `fees_details_ibfk_1` FOREIGN KEY (`Student_id`) REFERENCES `student` (`StudentID`);

--
-- Constraints for table `hostels`
--
ALTER TABLE `hostels`
  ADD CONSTRAINT `fk_warden` FOREIGN KEY (`warden_id`) REFERENCES `faculty` (`faculty_id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`HostelID`) REFERENCES `hostels` (`hostel_id`),
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`departmentID`) REFERENCES `department` (`dept_id`);

--
-- Constraints for table `taken`
--
ALTER TABLE `taken`
  ADD CONSTRAINT `taken_ibfk_1` FOREIGN KEY (`Course_id`) REFERENCES `courses` (`Course_id`),
  ADD CONSTRAINT `taken_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`StudentID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
