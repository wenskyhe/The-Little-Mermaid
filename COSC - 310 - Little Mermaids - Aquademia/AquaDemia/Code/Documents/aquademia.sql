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

CREATE TABLE `professors` (
  `professorID` int(11) NOT NULL,
  `Department` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `students` (
  `studentID` int(11) NOT NULL,
  `GPA` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS Users (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    userType ENUM('Student', 'Professor', 'Admin') NOT NULL,
    firstName VARCHAR(255) NOT NULL,
    lastName VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL UNIQUE,
    userName VARCHAR(255) NOT NULL DEFAULT '',
    passwordHash VARCHAR(255) NOT NULL UNIQUE,
    phoneNumber VARCHAR(20)
);

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

CREATE TABLE IF NOT EXISTS Courses (
    courseID INT AUTO_INCREMENT PRIMARY KEY,
    courseName VARCHAR(255),
    courseDescription TEXT,
    coursePrequisiteID TEXT,
    professorID INT,
    isCourseActive BOOLEAN,
    FOREIGN KEY (professorID) REFERENCES Users(userID)
);


CREATE TABLE IF NOT EXISTS CoursePrerequisites (
    prequisiteID INT AUTO_INCREMENT PRIMARY KEY,
    courseID INT,
    prequisiteCourseID INT,
    MinimumGradeRequired DECIMAL(5,2),
    FOREIGN KEY (courseID) REFERENCES Courses(courseID),
    FOREIGN KEY (prequisiteCourseID) REFERENCES Courses(courseID)
);

CREATE TABLE IF NOT EXISTS Enrollments (
    enrollmentID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT,
    courseID INT,
    enrollmentDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    pending BOOLEAN,
    FOREIGN KEY (userID) REFERENCES Users(userID),
    FOREIGN KEY (courseID) REFERENCES Courses(courseID)
);


CREATE TABLE IF NOT EXISTS Assignments (
    AssignmentID INT AUTO_INCREMENT PRIMARY KEY,
    courseID INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    dueDate DATETIME NOT NULL,
    weight DECIMAL(5,2) NOT NULL,
    type ENUM('quiz', 'project', 'homework', 'exam') NOT NULL,
    visibilityStatus BOOLEAN DEFAULT FALSE,
    assignmentFilePath TEXT,
    FOREIGN KEY (courseID) REFERENCES Courses(courseID)
);


CREATE TABLE IF NOT EXISTS Submissions (
    submissionID INT AUTO_INCREMENT PRIMARY KEY,
    assignmentID INT,
    userID INT,
    submissionDate DATETIME NOT NULL,
    submissionFilePath TEXT,
    grade DECIMAL(5,2),
    feedback TEXT,
    FOREIGN KEY (assignmentID) REFERENCES Assignments(AssignmentID),
    FOREIGN KEY (userID) REFERENCES Users(userID)
);

CREATE TABLE IF NOT EXISTS QuizQuestions (
    quizQuestionID INT AUTO_INCREMENT PRIMARY KEY, -- Added for easier referencing individual questions
    courseID INT,
    assignmentID INT,
    QuestionNum INT,
    QuestionText TEXT NOT NULL,
    ChoiceA TEXT NOT NULL,
    ChoiceB TEXT NOT NULL,
    ChoiceC TEXT NOT NULL,
    ChoiceD TEXT NOT NULL,
    CorrectChoice ENUM('ChoiceA', 'ChoiceB', 'ChoiceC', 'ChoiceD') NOT NULL,
    FOREIGN KEY (courseID) REFERENCES Courses(courseID),
    FOREIGN KEY (AssignmentID) REFERENCES Assignments(AssignmentID) -- Adjusted to reference Assignments directly
);


-- 6. Discussions Table
CREATE TABLE IF NOT EXISTS Discussions (
    DiscussionID INT AUTO_INCREMENT PRIMARY KEY,
    CourseID INT,
    UserID INT,
    Title VARCHAR(255) NOT NULL,
    InitialPost TEXT NOT NULL,
    FOREIGN KEY (CourseID) REFERENCES Courses(CourseID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);


-- 6. Discussions Table
CREATE TABLE IF NOT EXISTS Discussions (
    DiscussionID INT AUTO_INCREMENT PRIMARY KEY,
    CourseID INT,
    UserID INT,
    Title VARCHAR(255) NOT NULL,
    InitialPost TEXT NOT NULL,
    FOREIGN KEY (CourseID) REFERENCES Courses(CourseID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- 7. DiscussionResponses Table
CREATE TABLE IF NOT EXISTS DiscussionResponses (
    ResponseID INT AUTO_INCREMENT PRIMARY KEY,
    DiscussionID INT,
    UserID INT,
    ResponseText TEXT NOT NULL,
    ResponseDate DATETIME NOT NULL,
    FOREIGN KEY (DiscussionID) REFERENCES Discussions(DiscussionID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- -- Creating a view to display student information along with their final course grades
-- CREATE VIEW StudentInformationAndGrades AS
-- SELECT
--     u.UserID,
--     u.Username,
--     u.FirstName,
--     u.LastName,
--     u.Email,
--     u.UserType,
--     c.CourseID,
--     c.CourseName,
--     AVG(s.Grade) AS FinalCourseGrade
-- FROM
--     Users u
-- JOIN Enrollments e ON u.UserID = e.UserID
-- JOIN Courses c ON e.CourseID = c.CourseID
-- LEFT JOIN Submissions s ON u.UserID = s.UserID AND c.CourseID = s.CourseID
-- GROUP BY
--     u.UserID, c.CourseID
-- ORDER BY
--     u.LastName, u.FirstName, c.CourseName;

INSERT INTO `users` (`userID`, `userType`, `firstName`, `lastName`, `userName`, `email`, `phoneNumber`, `passwordHash`) VALUES
(2, 'Student', 'John', 'Doe', 'John_Doe', 'johnDoe@gmail.com', '(250) 863-0735',0x243279243130244a4a54693439595353664a2e5853456f5070664d644f55564e6d66786a46536375724b507343564d32302f5a597036343731464e4f),
(3, 'Professor', 'Nelson', 'Ngumo', 'Nelson_Ngumo', 'ngurunelson@gmail.com', '12508630731',0x2432792431302454326a3170622e7569416c535a2e484d71637735746567497767697763566c56744b7670585741314764774e2e7a45466b55416971),
(5, 'Student', 'Adrian', 'Reid', 'Adrian_Reid', 'adrianReid@gmail.com', '12508630732',0x2432792431302437465149744c4f7653525665657a2e6b57786259514f35655936687a562e69597663554c446e2e3149345844376d45465461613743),
(7, 'Student', 'Ahmed', 'Mirza', 'Ahmed_Mirza', 'ahmedMirza@gmail.com', '12508630733',0x2432792431302430334e38563070465a5649747a7778393239694f462e3933785463445343455965326d35326f4a466162496c792f39305339415553),
(8, 'Student', 'Jermane', 'Cole', 'Jermane_Cole', 'JermaneCole@gmail.cok', '12508630734',0x2432792431302443446e6d4d6c754131536b6a6f3559596f54704138656566343471343956345a747836636e4b43576b78645650704c344437325a6d),
(9, 'Student',  'test', 'student', 'test_student','teststudent@gmail.com', '12508630735','student'),
(10, 'Professor',  'test', 'teacher', 'test_teacher','testteacher@gmail.com', '12508630736','teacher'),
(11, 'Admin',  'test', 'admin', 'test_admin','testadmin@gmail.com', '12508630737','admin');
