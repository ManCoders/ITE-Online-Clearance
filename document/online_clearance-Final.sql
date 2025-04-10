-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2025 at 08:21 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `online_clearance`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `admin_name` varchar(50) NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `admin_name`, `create_at`) VALUES
(1, 'admin', '2025-02-13 10:52:47');

-- --------------------------------------------------------

--
-- Table structure for table `admin_login`
--

CREATE TABLE `admin_login` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin_login`
--

INSERT INTO `admin_login` (`id`, `name`, `status`, `email`, `password`, `phone`, `profile_picture`, `admin_id`) VALUES
(8, 'admin', 'admin', 'admin@gmail.com', '$2y$10$RM95yTLuVBXewAXbl0ObOOA6t3RzAsIBQPL5PrTeLv0DtfZRH5/Sa', '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `clearance`
--

CREATE TABLE `clearance` (
  `id` int(11) NOT NULL,
  `status_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clearance`
--

INSERT INTO `clearance` (`id`, `status_name`) VALUES
(6, 'Pending'),
(7, 'Cleared');

-- --------------------------------------------------------

--
-- Table structure for table `college_level`
--

CREATE TABLE `college_level` (
  `id` int(11) NOT NULL,
  `year_level` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `college_level`
--

INSERT INTO `college_level` (`id`, `year_level`) VALUES
(1, '1st year college'),
(2, '2nd year college'),
(3, '3rd year college'),
(4, '4th year college\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `course_name`, `created_at`) VALUES
(1, 'ELECTRICAL ENGINEERING', '2025-03-20 13:31:14'),
(2, 'FOOD TECHNOLOGY', '2025-03-20 13:30:38'),
(3, 'ELECTRONIC ENGINEERING', '2025-03-20 13:31:20'),
(5, 'AUTOMOTIVE ENGINEERING', '0000-00-00 00:00:00'),
(6, 'CIVIL TECHNOLOGY ENGINEERING', '2025-03-20 13:31:59');

-- --------------------------------------------------------

--
-- Table structure for table `grading`
--

CREATE TABLE `grading` (
  `id` int(11) NOT NULL,
  `midterm_grade` varchar(20) NOT NULL,
  `final_grade` varchar(20) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `remark` varchar(50) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `grading`
--

INSERT INTO `grading` (`id`, `midterm_grade`, `final_grade`, `semester`, `remark`, `student_id`, `teacher_id`, `admin_id`, `subject_id`) VALUES
(2, '1.2', '2.5', '1st sem', 'passed', NULL, NULL, 8, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `program_code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `program_code`) VALUES
(57, 'DT'),
(58, 'AIT'),
(61, 'TITE'),
(62, 'TTIC');

-- --------------------------------------------------------

--
-- Table structure for table `programs_with_subjects`
--

CREATE TABLE `programs_with_subjects` (
  `id` int(11) NOT NULL,
  `program_course` varchar(100) NOT NULL,
  `department_program` varchar(100) NOT NULL,
  `school_year` varchar(100) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `subject_code` varchar(100) NOT NULL,
  `semester` int(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `teacher_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs_with_subjects`
--

INSERT INTO `programs_with_subjects` (`id`, `program_course`, `department_program`, `school_year`, `subject_name`, `subject_code`, `semester`, `created_at`, `teacher_name`) VALUES
(73, 'AUTOMOTIVE ENGINEERING', 'TITE', '2025-2026', '', '', 0, '2025-03-23 21:10:35', ''),
(74, 'ELECTRICAL ENGINEERING', 'DT', '2025-2026', '', '', 0, '2025-03-31 22:21:25', ''),
(75, 'CIVIL TECHNOLOGY ENGINEERING', 'DT', '2025-2026', '', '', 0, '2025-04-01 21:02:23', ''),
(83, 'CIVIL TECHNOLOGY ENGINEERING', 'TTIC', '2025-2026', '', '', 0, '2025-04-05 14:38:00', ''),
(94, 'ELECTRICAL ENGINEERING', 'PROGRAM TEST', '20225-2022', '', '', 0, '2025-04-08 08:11:38', ''),
(95, 'AI INFORMATION', 'BS', '2021-2022', '', '', 0, '2025-04-08 12:21:04', '');

-- --------------------------------------------------------

--
-- Table structure for table `school_year`
--

CREATE TABLE `school_year` (
  `id` int(11) NOT NULL,
  `school_year` varchar(100) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `school_year`
--

INSERT INTO `school_year` (`id`, `school_year`, `create_at`) VALUES
(1, '2024-2025', '2025-03-09 14:04:33'),
(2, '2025-2026', '2025-03-09 14:04:33');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `section_name` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `admin_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `section_name`, `created_at`, `admin_id`, `teacher_id`) VALUES
(67, '1-A', '2025-04-01 22:34:42', NULL, NULL),
(68, '1-B', '2025-04-01 22:34:42', NULL, NULL),
(69, '1-C', '2025-04-01 22:34:42', NULL, NULL),
(70, '1-D', '2025-04-01 22:34:42', NULL, NULL),
(71, '2-A', '2025-04-01 22:35:07', NULL, NULL),
(72, '2-B', '2025-04-01 22:35:07', NULL, NULL),
(73, '2-C', '2025-04-01 22:35:07', NULL, NULL),
(74, '2-D', '2025-04-01 22:35:07', NULL, NULL),
(75, '3-A', '2025-04-01 22:35:29', NULL, NULL),
(76, '3-B', '2025-04-01 22:35:29', NULL, NULL),
(77, '3-C', '2025-04-01 22:35:29', NULL, NULL),
(78, '3-D', '2025-04-01 22:35:29', NULL, NULL),
(79, '4-A', '2025-04-01 22:35:51', NULL, NULL),
(80, '4-B', '2025-04-01 22:35:51', NULL, NULL),
(81, '4-C', '2025-04-01 22:35:51', NULL, NULL),
(82, '4-D', '2025-04-01 22:35:51', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `id` int(11) NOT NULL,
  `semester` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`id`, `semester`) VALUES
(1, '1st Semester'),
(2, '2nd Semester');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_code` varchar(100) DEFAULT NULL,
  `fname` varchar(100) NOT NULL,
  `mname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `profile` varchar(255) NOT NULL,
  `school_year` varchar(100) NOT NULL,
  `program` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `levels` int(10) NOT NULL,
  `section_id` varchar(11) DEFAULT NULL,
  `contact` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `verify` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_code`, `fname`, `mname`, `lname`, `profile`, `school_year`, `program`, `course`, `levels`, `section_id`, `contact`, `email`, `verify`) VALUES
(82, '1006', 'Ewayan', 'D', 'Man', '', '2025-2026', 'TTIC', 'AUTOMOTIVE ENGINEERING', 3, '69', '09691265655', 'man@gmail.com', ''),
(83, '1007', 'Ewayan', 'D', 'Max', '', '2025-2026', 'DT', 'FOOD TECHNOLOGY', 3, '66', '096912656552', 'max@gmail.com', ''),
(84, '1008', 'Lalamove', 'Midz', 'rose', '', '2025-2026', 'AIT', 'ELECTRONIC ENGINEERING', 3, '68', '091213123213', 'rose@gmail.com', ''),
(88, '19999', 'test', 'test', 'test', '', '2025-2026', 'TTIC', 'CIVIL TECHNOLOGY ENGINEERING', 4, '67', '09695196520', 'test@gmail.com', '');

-- --------------------------------------------------------

--
-- Table structure for table `student_login`
--

CREATE TABLE `student_login` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_login`
--

INSERT INTO `student_login` (`id`, `email`, `contact`, `password`, `student_id`) VALUES
(81, 'man@gmail.com', '', '$2y$10$CMCyRzU0WGFHoG/jDYli1OvtVcVuzkBQW58UJRwg5ka5VRtFBzWzq', 82),
(82, 'max@gmail.com', '', '$2y$10$LnIV.gYhfNW8miIuOJ/jZ.fv73pyWfntPtF2rZNoYuK17ITeloPWK', 83);

-- --------------------------------------------------------

--
-- Table structure for table `student_subjects`
--

CREATE TABLE `student_subjects` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `midterm` varchar(50) NOT NULL,
  `finalterm` varchar(50) NOT NULL,
  `remark` varchar(50) NOT NULL,
  `clearance` varchar(100) NOT NULL,
  `grade_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `semester_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_with_subjects`
--

CREATE TABLE `student_with_subjects` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `teacher_id` int(100) NOT NULL,
  `semester` int(11) NOT NULL,
  `program_id` varchar(10) NOT NULL,
  `school_year` varchar(100) NOT NULL,
  `subject_code` varchar(100) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `college_level` varchar(100) NOT NULL,
  `teacher_name` varchar(100) NOT NULL,
  `grade` double NOT NULL,
  `status` varchar(100) NOT NULL,
  `remark` varchar(100) NOT NULL,
  `final` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_with_subjects`
--

INSERT INTO `student_with_subjects` (`id`, `student_id`, `teacher_id`, `semester`, `program_id`, `school_year`, `subject_code`, `subject_name`, `college_level`, `teacher_name`, `grade`, `status`, `remark`, `final`, `created_at`) VALUES
(84, 83, 48, 1, 'DT', '2025-2026', 'ITPC 106', 'COMPUTER PROGRAMMING 2', '1', '', 2.5, 'Completed', 'Lack of Requirements', 'Cleared', '2025-04-03 21:24:09'),
(85, 83, 51, 2, 'DT', '2025-2026', 'ITPC 102', 'PROGRAMMING 1', '1', '', 5, 'Drop', 'Lack of Requirements', 'Cleared', '2025-04-03 21:25:37'),
(86, 83, 48, 1, 'DT', '2025-2026', 'ITPC 106', 'COMPUTER PROGRAMMING 2', '2', '', 1, 'Completed', 'Completed', 'Cleared', '2025-04-03 21:33:26'),
(87, 83, 48, 1, 'DT', '2025-2026', 'ITPC 106', 'COMPUTER PROGRAMMING 2', '3', '', 2, 'Completed', 'Completed', 'Cleared', '2025-04-03 21:33:50'),
(90, 84, 48, 1, 'AIT', '2025-2026', 'ITPC 106', 'COMPUTER PROGRAMMING 2', '2', '', 0, '', '', '', '2025-04-03 21:43:36'),
(92, 82, 51, 1, 'TTIC', '2025-2026', 'ITPC 105', 'COMPUTER PROGRAMMING 5', '1', '', 2.5, 'Completed', 'Completed', 'Cleared', '2025-04-03 21:50:15'),
(94, 84, 48, 1, 'AIT', '2025-2026', 'ITPC 106', 'COMPUTER PROGRAMMING 2', '1', '', 2.5, 'Completed', 'Completed', 'Not Cleared', '2025-04-03 21:53:19'),
(98, 82, 48, 1, 'TTIC', '2025-2026', 'ITPC 106', 'COMPUTER PROGRAMMING 2', '3', '', 0, '', '', '', '2025-04-04 21:01:47'),
(100, 83, 48, 1, 'DT', '2025-2026', 'ITPC 106', 'COMPUTER PROGRAMMING 2', '4', '', 1, 'Completed', 'Completed', 'Cleared', '2025-04-05 11:39:49'),
(101, 88, 56, 1, 'TTIC', '2025-2026', 'AI 101', 'AI DEVELOPMENT', '1', '', 1.2, 'Completed', 'Completed', 'Cleared', '2025-04-06 23:12:27'),
(102, 82, 48, 1, 'TTIC', '2025-2026', 'ITPC 106', 'COMPUTER PROGRAMMING 2', '1', '', 0, '', '', '', '2025-04-08 12:29:17'),
(103, 82, 57, 1, 'TTIC', '2025-2026', 'program qweqwe', 'subject qweqwe', '1', '', 2.5, 'Completed', 'Completed', 'Cleared', '2025-04-08 12:29:59');

-- --------------------------------------------------------

--
-- Table structure for table `subject_with_program_id`
--

CREATE TABLE `subject_with_program_id` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `school_year` varchar(100) NOT NULL,
  `college_level` int(11) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `subject_code` varchar(100) NOT NULL,
  `teacher_name` varchar(100) NOT NULL,
  `semester` int(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_with_program_id`
--

INSERT INTO `subject_with_program_id` (`id`, `program_id`, `teacher_id`, `school_year`, `college_level`, `subject_name`, `subject_code`, `teacher_name`, `semester`, `created_at`) VALUES
(158, 73, 48, '', 0, 'COMPUTER PROGRAMMING 2', 'ITPC 106', '', 1, '2025-03-23 21:10:46'),
(159, 73, 51, '', 0, 'COMPUTER PROGRAMMING 5', 'ITPC 105', '', 1, '2025-03-23 21:11:09'),
(160, 73, 51, '', 0, 'PROGRAMMING 1', 'ITPC 102', '', 2, '2025-03-23 21:11:18'),
(163, 75, 47, '', 0, 'Planing architecture design 1', 'ITCC 101', '', 1, '2025-04-01 21:03:12'),
(164, 75, 51, '', 0, 'Designer 1', 'ITCR 101', '', 2, '2025-04-01 21:07:46'),
(166, 83, 48, '', 0, 'AI DEVELOPMENT', 'ITAI 101', '', 1, '2025-04-06 23:10:19'),
(167, 73, 56, '', 0, 'AI DEVELOPMENT', 'AI 101', '', 1, '2025-04-06 23:12:13');

-- --------------------------------------------------------

--
-- Table structure for table `subject_year`
--

CREATE TABLE `subject_year` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `program_id` int(11) DEFAULT NULL,
  `semester_id` int(11) DEFAULT NULL,
  `year_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `teacher_code` varchar(255) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `mname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `profile` varchar(100) NOT NULL,
  `profession` varchar(100) NOT NULL,
  `specialized` varchar(100) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `section_id` varchar(11) NOT NULL,
  `school_year` varchar(20) NOT NULL,
  `verify` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `teacher_code`, `fname`, `mname`, `lname`, `profile`, `profession`, `specialized`, `contact`, `email`, `section_id`, `school_year`, `verify`) VALUES
(47, '2001', 'Belyn', 'Ber', 'Gregorio', '', 'Associate Professor', 'Software Engineering', '096951965202', 'gregoriob@gmail.com', '70', '2025-2026', ''),
(48, '2001', 'Roy', 'Rex', 'Penas', '', 'Instructor', 'Cybersecurity', '09695965210', 'penas@gmail.com', '69', '2025-2026', ''),
(51, '2007', 'Sansons', 'lim', 'Bogard', '', 'Associate Professor', 'Network Administration 2', '09695196555', 'lim@gmail.com', '68', '2025-2026', '');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_login`
--

CREATE TABLE `teacher_login` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `teacher_login`
--

INSERT INTO `teacher_login` (`id`, `email`, `contact`, `password`, `teacher_id`) VALUES
(35, 'penas@gmail.com', '', '$2y$10$.qaQgbaikoWqB7kAPx4E/e8Yyw/uLAH9qAoTrBWdGIymjBFAF6izu', 47),
(37, 'lim@gmail.com', '', '$2y$10$8G5/yNQD6cKpDvuscvROSeHoUALflSMYvROqH8JtTfG1a2HAz0Mba', 51);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_profession`
--

CREATE TABLE `teacher_profession` (
  `id` int(11) NOT NULL,
  `profession` varchar(100) NOT NULL,
  `Specialized` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_profession`
--

INSERT INTO `teacher_profession` (`id`, `profession`, `Specialized`, `created_at`) VALUES
(1, 'Professor', 'Computer Science', '2025-03-21 23:47:20'),
(2, 'Associate Professor', 'Cybersecurity', '2025-03-21 23:47:20'),
(3, 'Instructor', 'Software Engineering', '2025-03-21 23:47:20'),
(4, 'Senior Lecturer', 'Data Science', '2025-03-21 23:47:20'),
(5, 'Lecturer', 'Network Administration 2', '2025-03-21 23:47:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_login`
--
ALTER TABLE `admin_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admin_login` (`admin_id`);

--
-- Indexes for table `clearance`
--
ALTER TABLE `clearance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `college_level`
--
ALTER TABLE `college_level`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grading`
--
ALTER TABLE `grading`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_grading_student` (`student_id`),
  ADD KEY `fk_grading_teacher` (`teacher_id`),
  ADD KEY `fk_grading_admin` (`admin_id`),
  ADD KEY `fk_subject_id` (`subject_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `programs_with_subjects`
--
ALTER TABLE `programs_with_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_year`
--
ALTER TABLE `school_year`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sections` (`admin_id`),
  ADD KEY `fk_teacher_sections` (`teacher_id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_login`
--
ALTER TABLE `student_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_login_student_key` (`student_id`);

--
-- Indexes for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_grade_id_key` (`grade_id`),
  ADD KEY `fk_student_subject_teacher_key` (`teacher_id`),
  ADD KEY `fk_semester_id` (`semester_id`),
  ADD KEY `fk_students_keys_id` (`student_id`),
  ADD KEY `fk_student_subject_id` (`subject_id`);

--
-- Indexes for table `student_with_subjects`
--
ALTER TABLE `student_with_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject_with_program_id`
--
ALTER TABLE `subject_with_program_id`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject_year`
--
ALTER TABLE `subject_year`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_program_id_key` (`program_id`),
  ADD KEY `fk_semester_id_key` (`semester_id`),
  ADD KEY `fk_teacher_id_key` (`teacher_id`),
  ADD KEY `fk_year_id_key` (`year_id`),
  ADD KEY `section_id_key` (`section_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_login`
--
ALTER TABLE `teacher_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id_key` (`teacher_id`);

--
-- Indexes for table `teacher_profession`
--
ALTER TABLE `teacher_profession`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_login`
--
ALTER TABLE `admin_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `clearance`
--
ALTER TABLE `clearance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `college_level`
--
ALTER TABLE `college_level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `grading`
--
ALTER TABLE `grading`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `programs_with_subjects`
--
ALTER TABLE `programs_with_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `school_year`
--
ALTER TABLE `school_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `student_login`
--
ALTER TABLE `student_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `student_subjects`
--
ALTER TABLE `student_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=233;

--
-- AUTO_INCREMENT for table `student_with_subjects`
--
ALTER TABLE `student_with_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `subject_with_program_id`
--
ALTER TABLE `subject_with_program_id`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `subject_year`
--
ALTER TABLE `subject_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `teacher_login`
--
ALTER TABLE `teacher_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `teacher_profession`
--
ALTER TABLE `teacher_profession`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_login`
--
ALTER TABLE `admin_login`
  ADD CONSTRAINT `fk_admin_login` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD CONSTRAINT `student_subjects_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_subjects_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_subjects_ibfk_3` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_subjects_ibfk_4` FOREIGN KEY (`subject_id`) REFERENCES `subject_year` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subject_year`
--
ALTER TABLE `subject_year`
  ADD CONSTRAINT `fk_program_id_key` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_semester_id_key` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_teacher_id_key` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_year_id_key` FOREIGN KEY (`year_id`) REFERENCES `school_year` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `section_id_key` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teacher_login`
--
ALTER TABLE `teacher_login`
  ADD CONSTRAINT `teacher_id_key` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
