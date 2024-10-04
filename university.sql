-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2023 at 07:54 AM
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
(23005, 'Data Structures and Algorithm', 9, 4, 6),
(23006, 'Machine learning', 10, 3, 6),
(23007, 'Data Base Management system', 11, 4, 6),
(23008, 'Matrix Computation', 9, 5, 3),
(23009, 'Scientific computing', 10, 5, 3),
(23010, 'Operating System', 6, 4, 6);

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
(4, 'Computer Science', 6),
(5, 'Mathematics', 10),
(6, 'Mechanical Engineering', 11),
(7, 'Electrical engineering', 9),
(8, 'Physics ', 13);

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
(6, 'Manikanta Dandi', '1999-11-13', 'Father', 'Male', '7845651232', 'abcd@gmail.com', 'MTECH', 20, 4, '1000000.00'),
(9, 'John Doe', '1985-05-15', 'John Doe Sr.', 'Male', '1234567890', 'john.doe@example.com', 'Ph.D', 15, 7, '120000.00'),
(10, 'Eva Martinez', '1982-02-05', 'Carlos Martinez', 'Female', '5678901234', 'eva.martinez@example.com', 'Ph.D', 12, 5, '110000.00'),
(11, 'Michael Brown\'', '1971-09-20', 'Christopher Brown', 'Male', '7890123456', 'michael.brown@example.com', 'Ph.D', 30, 6, '140000.00'),
(12, 'Joh issac', '1975-02-08', 'Issac', 'Male', '456789235', 'abc@gmail.com', 'Ph.D', 5, 4, '100000.00'),
(13, 'Issac Joseph', '1975-02-02', 'Joseph', 'Male', '9874563215', 'joseph@gmail.com', 'Ph.D', 12, 8, '2500000.00');

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
-- Dumping data for table `fees_details`
--

INSERT INTO `fees_details` (`Student_id`, `fees_type`, `fee_paid`, `transaction_id`) VALUES
(234161005, 'Tution Fees', '10000.00', 'TN20231129000001'),
(234161006, 'Hostel Fees', '20000.00', 'TN20231129000002'),
(234161008, 'Tution Fees', '50000.00', 'TN20231129000003');

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
(8, 'UMIAM', '2000-02-02', 1000, 9),
(9, 'Brahmaputra', '2005-05-05', 1200, 11),
(10, 'Lohith', '2010-01-05', 1200, 10),
(11, 'Barak', '2000-01-01', 500, 6),
(12, 'Manas', '1995-05-05', 600, 12);

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
(234161005, 'Nandeesh H U', '', '2000-02-18', 'Male', '7026061715', '10nandeeshhu@gmail.com', 9, 202, 3),
(234161006, 'Alice Smith', '', '2002-02-18', 'Male', '7569123244', 'as@gmail.com', 10, 18, 4),
(234161007, 'Tarun Sharma', '', '2000-02-18', 'Male', '7894521455', '10nandeeshhu@gmail.com', 9, 10, 4),
(234161008, 'Vinay Kumar', '', '2002-12-02', 'Male', '8765451232', 'vinay@gmail.com', 9, 2, 4);

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
(23005, 234161006),
(23005, 234161007),
(23005, 234161008),
(23006, 234161005),
(23007, 234161006),
(23007, 234161007),
(23007, 234161008),
(23010, 234161006),
(23010, 234161007),
(23010, 234161008);

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
  MODIFY `Course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23011;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `dept_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `hostels`
--
ALTER TABLE `hostels`
  MODIFY `hostel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `StudentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=234161009;

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
