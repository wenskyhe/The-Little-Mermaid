-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2024 at 08:18 AM
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

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`adminID`, `fullAccess`) VALUES
(11, 1);

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `AssignmentID` int(11) NOT NULL,
  `courseID` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `dueDate` datetime NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `type` enum('quiz','project','homework','essay') NOT NULL,
  `visibilityStatus` tinyint(1) DEFAULT 0,
  `assignmentFilePath` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`AssignmentID`, `courseID`, `title`, `description`, `dueDate`, `weight`, `type`, `visibilityStatus`, `assignmentFilePath`) VALUES
(1, 1, 'Essay1', 'lmao', '2024-11-11 01:01:00', 10.00, 'essay', 1, NULL),
(2, 1, 'Essay2', 'lmao', '2024-11-11 01:01:00', 10.00, 'essay', 1, NULL),
(3, 1, 'Quiz1', 'lmao', '2024-11-11 01:01:00', 10.00, 'quiz', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `courseprerequisites`
--

CREATE TABLE `courseprerequisites` (
  `prequisiteID` int(11) NOT NULL,
  `courseID` int(11) DEFAULT NULL,
  `prequisiteCourseID` int(11) DEFAULT NULL,
  `MinimumGradeRequired` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `courseID` int(11) NOT NULL,
  `courseName` varchar(255) DEFAULT NULL,
  `courseDescription` text DEFAULT NULL,
  `coursePrequisiteID` text DEFAULT NULL,
  `professorID` int(11) DEFAULT NULL,
  `isCourseActive` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`courseID`, `courseName`, `courseDescription`, `coursePrequisiteID`, `professorID`, `isCourseActive`) VALUES
(1, 'COSC310', 'BLANK', '', 10, 1),
(4, 'COSC111', '1', '', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `discussionresponses`
--

CREATE TABLE `discussionresponses` (
  `ResponseID` int(11) NOT NULL,
  `DiscussionID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `ResponseText` text NOT NULL,
  `ResponseDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discussions`
--

CREATE TABLE `discussions` (
  `DiscussionID` int(11) NOT NULL,
  `CourseID` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL,
  `Title` varchar(255) NOT NULL,
  `InitialPost` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

CREATE TABLE `enrollment` (
  `EnrollmentID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `CourseID` int(11) DEFAULT NULL,
  `EnrollmentDate` date DEFAULT NULL,
  `Accepted` enum('-1','0','1') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollment`
--

INSERT INTO `enrollment` (`EnrollmentID`, `UserID`, `CourseID`, `EnrollmentDate`, `Accepted`) VALUES
(38, 2, 1, '2024-03-29', '1'),
(39, 2, 4, '2024-03-29', '1'),
(40, 9, 1, '2024-03-31', '1'),
(41, 9, 1, '2024-03-31', '1'),
(43, 9, 4, '2024-04-04', '-1');

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
(3, NULL),
(10, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quizquestions`
--

CREATE TABLE `quizquestions` (
  `quizQuestionID` int(11) NOT NULL,
  `courseID` int(11) DEFAULT NULL,
  `assignmentID` int(11) DEFAULT NULL,
  `QuestionNum` int(11) DEFAULT NULL,
  `QuestionText` text NOT NULL,
  `ChoiceA` text NOT NULL,
  `ChoiceB` text NOT NULL,
  `ChoiceC` text NOT NULL,
  `ChoiceD` text NOT NULL,
  `CorrectChoice` enum('ChoiceA','ChoiceB','ChoiceC','ChoiceD') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(8, NULL),
(9, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `submissionID` int(11) NOT NULL,
  `assignmentID` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL,
  `submissionDate` datetime NOT NULL,
  `submissionFilePath` text DEFAULT NULL,
  `grade` decimal(5,2) DEFAULT NULL,
  `feedback` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `userType` enum('Student','Professor','Admin') NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `userName` varchar(255) NOT NULL DEFAULT '',
  `passwordHash` varchar(255) NOT NULL,
  `phoneNumber` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `userType`, `firstName`, `lastName`, `Email`, `userName`, `passwordHash`, `phoneNumber`) VALUES
(2, 'Student', 'John', 'Doe', 'johnDoe@gmail.com', 'John_Doe', 'hello', '(250) 863-0735'),
(3, 'Professor', 'Nelson', 'Ngumo', 'ngurunelson@gmail.com', 'Nelson_Ngumo', '$2y$10$T2j1pb.uiAlSZ.HMqcw5tegIwgiwcVlVtKvpXWA1GdwN.zEFkUAiq', '12508630731'),
(5, 'Student', 'Adrian', 'Reid', 'adrianReid@gmail.com', 'Adrian_Reid', '$2y$10$7FQItLOvSRVeez.kWxbYQO5eY6hzV.iYvcULDn.1I4XD7mEFTaa7C', '12508630732'),
(7, 'Student', 'Ahmed', 'Mirza', 'ahmedMirza@gmail.com', 'Ahmed_Mirza', '$2y$10$03N8V0pFZVItzwx929iOF.93xTcDSCEYe2m52oJFabIly/90S9AUS', '12508630733'),
(8, 'Student', 'Jermane', 'Cole', 'JermaneCole@gmail.cok', 'Jermane_Cole', 'jcole', '12508630734'),
(9, 'Student', 'test', 'student', 'teststudent@gmail.com', 'test_student', 'student', '12508630735'),
(10, 'Professor', 'test', 'teacher', 'testteacher@gmail.com', 'test_teacher', 'teacher', '12508630736'),
(12, 'Student', 'firstname', 'lastname', 'example@gmail.com', 'firstname_lastname', 'password', '12508630738'),
(11, 'Admin', 'test', 'admin', 'testadmin@gmail.com', 'test_admin', 'admin', '12508630737');

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
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`AssignmentID`),
  ADD KEY `courseID` (`courseID`);

--
-- Indexes for table `courseprerequisites`
--
ALTER TABLE `courseprerequisites`
  ADD PRIMARY KEY (`prequisiteID`),
  ADD KEY `courseID` (`courseID`),
  ADD KEY `prequisiteCourseID` (`prequisiteCourseID`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`courseID`),
  ADD KEY `professorID` (`professorID`);

--
-- Indexes for table `discussionresponses`
--
ALTER TABLE `discussionresponses`
  ADD PRIMARY KEY (`ResponseID`),
  ADD KEY `DiscussionID` (`DiscussionID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `discussions`
--
ALTER TABLE `discussions`
  ADD PRIMARY KEY (`DiscussionID`),
  ADD KEY `CourseID` (`CourseID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD PRIMARY KEY (`EnrollmentID`);

--
-- Indexes for table `professors`
--
ALTER TABLE `professors`
  ADD PRIMARY KEY (`professorID`);

--
-- Indexes for table `quizquestions`
--
ALTER TABLE `quizquestions`
  ADD PRIMARY KEY (`quizQuestionID`),
  ADD KEY `courseID` (`courseID`),
  ADD KEY `assignmentID` (`assignmentID`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`studentID`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`submissionID`),
  ADD KEY `assignmentID` (`assignmentID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `phoneNumber`  (`phoneNumber`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `AssignmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `courseprerequisites`
--
ALTER TABLE `courseprerequisites`
  MODIFY `prequisiteID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `courseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;
CREATE TABLE IF NOT EXISTS Assignments (
    AssignmentID INT AUTO_INCREMENT PRIMARY KEY,
    courseID INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    dueDate DATETIME NOT NULL,
    weight DECIMAL(5,2) NOT NULL,
    type ENUM('quiz', 'essay') NOT NULL,
    visibilityStatus BOOLEAN DEFAULT FALSE,
    assignmentFilePath TEXT,
    FOREIGN KEY (courseID) REFERENCES Courses(courseID)
);

--
-- AUTO_INCREMENT for table `discussionresponses`
--
ALTER TABLE `discussionresponses`
  MODIFY `ResponseID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discussions`
--
ALTER TABLE `discussions`
  MODIFY `DiscussionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enrollment`
--
ALTER TABLE `enrollment`
  MODIFY `EnrollmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `quizquestions`
--
ALTER TABLE `quizquestions`
  MODIFY `quizQuestionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `submissionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `courses` (`courseID`);

--
-- Constraints for table `courseprerequisites`
--
ALTER TABLE `courseprerequisites`
  ADD CONSTRAINT `courseprerequisites_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `courses` (`courseID`),
  ADD CONSTRAINT `courseprerequisites_ibfk_2` FOREIGN KEY (`prequisiteCourseID`) REFERENCES `courses` (`courseID`);

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`professorID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `discussionresponses`
--
ALTER TABLE `discussionresponses`
  ADD CONSTRAINT `discussionresponses_ibfk_1` FOREIGN KEY (`DiscussionID`) REFERENCES `discussions` (`DiscussionID`),
  ADD CONSTRAINT `discussionresponses_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `discussions`
--
ALTER TABLE `discussions`
  ADD CONSTRAINT `discussions_ibfk_1` FOREIGN KEY (`CourseID`) REFERENCES `courses` (`courseID`),
  ADD CONSTRAINT `discussions_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `quizquestions`
--
ALTER TABLE `quizquestions`
  ADD CONSTRAINT `quizquestions_ibfk_1` FOREIGN KEY (`courseID`) REFERENCES `courses` (`courseID`),
  ADD CONSTRAINT `quizquestions_ibfk_2` FOREIGN KEY (`assignmentID`) REFERENCES `assignments` (`AssignmentID`);

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`assignmentID`) REFERENCES `assignments` (`AssignmentID`),
  ADD CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
