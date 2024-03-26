-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2024 at 11:56 PM
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
-- Database: `aquademia`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `adminID` int(11) NOT NULL,
  `fullAccess` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `professors`
--

CREATE TABLE `professors` (
  `professorID` int(11) NOT NULL,
  `Department` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `professors`
--

INSERT INTO `professors` (`professorID`, `Department`) VALUES
(3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `studentID` int(11) NOT NULL,
  `GPA` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`studentID`, `GPA`) VALUES
(2, NULL),
(5, NULL),
(7, NULL),
(8, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `userType` enum('Admin','Professor','Student') NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `userName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phoneNumber` varchar(20) NOT NULL,
  `mailingAddress` varchar(255) DEFAULT NULL,
  `passwordHash` varbinary(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `userType`, `firstName`, `lastName`, `userName`, `email`, `phoneNumber`, `mailingAddress`, `passwordHash`) VALUES
(2, 'Student', 'John', 'Doe', 'John_Doe', 'johnDoe@gmail.com', '(250) 863-0735', NULL, 0x243279243130244a4a54693439595353664a2e5853456f5070664d644f55564e6d66786a46536375724b507343564d32302f5a597036343731464e4f),
(3, 'Professor', 'Nelson', 'Ngumo', 'Nelson_Ngumo', 'ngurunelson@gmail.com', '12508630731', NULL, 0x2432792431302454326a3170622e7569416c535a2e484d71637735746567497767697763566c56744b7670585741314764774e2e7a45466b55416971),
(5, 'Student', 'Adrian', 'Reid', 'Adrian_Reid', 'adrianReid@gmail.com', '12508630732', NULL, 0x2432792431302437465149744c4f7653525665657a2e6b57786259514f35655936687a562e69597663554c446e2e3149345844376d45465461613743),
(7, 'Student', 'Ahmed', 'Mirza', 'Ahmed_Mirza', 'ahmedMirza@gmail.com', '12508630733', NULL, 0x2432792431302430334e38563070465a5649747a7778393239694f462e3933785463445343455965326d35326f4a466162496c792f39305339415553),
(8, 'Student', 'Jermane', 'Cole', 'Jermane_Cole', 'JermaneCole@gmail.cok', '12508630734', NULL, 0x2432792431302443446e6d4d6c754131536b6a6f3559596f54704138656566343471343956345a747836636e4b43576b78645650704c344437325a6d),
(9, 'Student',  'test', 'student', 'test_student','teststudent@gmail.com', '12508630735', NULL, 'student'),
(10, 'Professor',  'test', 'teacher', 'test_teacher','testteacher@gmail.com', '12508630736', NULL, 'teacher'),
(11, 'Admin',  'test', 'admin', 'test_admin','testadmin@gmail.com', '12508630737', NULL, 'admin');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `AfterUserInsert` AFTER INSERT ON `users` FOR EACH ROW BEGIN
    IF NEW.userType = 'Student' THEN
        INSERT INTO Students (StudentID) VALUES (NEW.userID);
    ELSEIF NEW.userType = 'Professor' THEN
        INSERT INTO Professors (ProfessorID, Department) VALUES (NEW.userID, null);
    ELSEIF NEW.UserType = 'Admin' THEN
        INSERT INTO Admins (adminID, fullAccess) VALUES (NEW.userID, TRUE);
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`adminID`);

--
-- Indexes for table `professors`
--
ALTER TABLE `professors`
  ADD PRIMARY KEY (`professorID`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`studentID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `passwordHash` (`passwordHash`),
  ADD UNIQUE KEY `phoneNumber` (`phoneNumber`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `professors`
--
ALTER TABLE `professors`
  MODIFY `professorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `studentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`adminID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `professors`
--
ALTER TABLE `professors`
  ADD CONSTRAINT `professors_ibfk_1` FOREIGN KEY (`professorID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `users` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
