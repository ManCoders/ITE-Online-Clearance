-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2025 at 05:17 PM
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
(5, ''),
(6, 'Pending'),
(7, 'Cleared');

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
-- Table structure for table `majors`
--

CREATE TABLE `majors` (
  `id` int(11) NOT NULL,
  `major_name` varchar(50) NOT NULL,
  `major_code` varchar(50) NOT NULL,
  `assign` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(73, 'AUTOMOTIVE ENGINEERING', 'TITE', '2025-2026', '', '', 0, '2025-03-23 21:10:35', '');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `id` int(11) NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `room_code` varchar(100) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
  `teacher_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `section_name`, `created_at`, `admin_id`, `teacher_id`, `student_id`) VALUES
(47, 'asd', '2025-03-16 23:42:55', 8, NULL, NULL),
(48, 'asd', '2025-03-16 23:43:36', 8, NULL, NULL);

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
  `contact` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `verify` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_code`, `fname`, `mname`, `lname`, `profile`, `school_year`, `program`, `course`, `contact`, `email`, `verify`) VALUES
(82, '1006', 'Ewayan', 'D', 'Man', '', '2025-2026', 'TTIC', 'AUTOMOTIVE ENGINEERING', '09691265655', 'man@gmail.com', ''),
(83, '1007', 'Ewayan', 'D', 'Max', '', '2025-2026', 'DT', 'FOOD TECHNOLOGY', '096912656552', 'max@gmail.com', '');

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
  `teacher_name` varchar(100) NOT NULL,
  `grade` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `remark` varchar(100) NOT NULL,
  `final` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_with_subjects`
--

INSERT INTO `student_with_subjects` (`id`, `student_id`, `teacher_id`, `semester`, `program_id`, `school_year`, `subject_code`, `subject_name`, `teacher_name`, `grade`, `status`, `remark`, `final`, `created_at`) VALUES
(53, 77, 51, 1, 'DT', '2025-2026', 'ETHICS', 'ETHICAL', '', 0, 'Drop', 'Not Attending', 'Failed', '2025-03-23 15:55:15'),
(54, 77, 51, 2, 'DT', '2025-2026', 'ITPC 101', 'PROGRAMMING 1', '', 0, 'Drop', 'Lack of Requirements', 'Passed', '2025-03-23 15:55:20'),
(55, 77, 51, 2, 'DT', '2025-2026', 'ITPC 102', 'COMPUTER PROGRAMMING 2', '', 0, 'Drop', 'Lack of Requirements', 'Failed', '2025-03-23 15:55:24'),
(63, 82, 51, 2, 'TTIC', '2025-2026', 'ITPC 102', 'PROGRAMMING 1', '', 0, 'Drop', 'Lack of Requirements', 'Failed', '2025-03-23 21:11:31'),
(64, 82, 51, 1, 'TTIC', '2025-2026', 'ITPC 106', 'COMPUTER PROGRAMMING 2', '', 0, 'Completed', 'Completed', 'Passed', '2025-03-23 21:11:38'),
(65, 82, 51, 1, 'TTIC', '2025-2026', 'ITPC 105', 'COMPUTER PROGRAMMING 5', '', 0, 'Drop', 'Lack of Requirements', 'Failed', '2025-03-23 21:11:50'),
(66, 83, 51, 1, 'DT', '2025-2026', 'ITPC 106', 'COMPUTER PROGRAMMING 2', '', 0, 'Drop', 'Not Attending', 'Passed', '2025-03-23 21:20:04'),
(67, 83, 51, 2, 'DT', '2025-2026', 'ITPC 102', 'PROGRAMMING 1', '', 0, 'Drop', 'Lack of Requirements', 'Failed', '2025-03-23 21:20:14'),
(68, 83, 51, 1, 'DT', '2025-2026', 'ITPC 105', 'COMPUTER PROGRAMMING 5', '', 0, 'Completed', 'Completed', 'Passed', '2025-03-23 21:20:19');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject_name` varchar(100) DEFAULT NULL,
  `subject_code` varchar(100) NOT NULL,
  `assign` varchar(100) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `semester_id` int(11) DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_with_program_id`
--

CREATE TABLE `subject_with_program_id` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `school_year` varchar(100) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `subject_code` varchar(100) NOT NULL,
  `teacher_name` varchar(100) NOT NULL,
  `semester` int(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_with_program_id`
--

INSERT INTO `subject_with_program_id` (`id`, `program_id`, `teacher_id`, `school_year`, `subject_name`, `subject_code`, `teacher_name`, `semester`, `created_at`) VALUES
(158, 73, 51, '', 'COMPUTER PROGRAMMING 2', 'ITPC 106', '', 1, '2025-03-23 21:10:46'),
(159, 73, 51, '', 'COMPUTER PROGRAMMING 5', 'ITPC 105', '', 1, '2025-03-23 21:11:09'),
(160, 73, 51, '', 'PROGRAMMING 1', 'ITPC 102', '', 2, '2025-03-23 21:11:18');

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
  `course` varchar(100) NOT NULL,
  `school_year` varchar(20) NOT NULL,
  `verify` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `teacher_code`, `fname`, `mname`, `lname`, `profile`, `profession`, `specialized`, `contact`, `email`, `course`, `school_year`, `verify`) VALUES
(47, '2001', 'Belyn', 'Ber', 'Gregorio', '', 'Associate Professor', 'Software Engineering', '096951965202', 'gregoriob@gmail.com', '', '2025-2026', ''),
(48, '2001', 'Roy', 'Rex', 'Penas', '', 'Instructor', 'Cybersecurity', '09695965210', 'penas@gmail.com', '', '2025-2026', ''),
(51, '2007', 'Sansons', 'lim', 'Bogard', '', 'Associate Professor', 'Network Administration 2', '09695196555', 'lim@gmail.com', '', '2025-2026', '');

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
-- Indexes for table `majors`
--
ALTER TABLE `majors`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_room_student` (`student_id`),
  ADD KEY `fk_room_teacher` (`teacher_id`),
  ADD KEY `fk_room_admin` (`admin_id`),
  ADD KEY `fk_room_subject` (`subject_id`),
  ADD KEY `fk_room_section` (`section_id`),
  ADD KEY `fk_room_course` (`course_id`);

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
  ADD KEY `fk_teacher_sections` (`teacher_id`),
  ADD KEY `fk_student_id` (`student_id`);

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
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_teacher_id_id` (`teacher_id`),
  ADD KEY `fk_semester_id_key` (`semester_id`),
  ADD KEY `program_id_key` (`program_id`),
  ADD KEY `FK_section_id_key` (`section_id`);

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
  ADD KEY `fk_year_id_key` (`year_id`),
  ADD KEY `fk_teacher_id_key` (`teacher_id`),
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
-- AUTO_INCREMENT for table `majors`
--
ALTER TABLE `majors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `programs_with_subjects`
--
ALTER TABLE `programs_with_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `school_year`
--
ALTER TABLE `school_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `subject_with_program_id`
--
ALTER TABLE `subject_with_program_id`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT for table `subject_year`
--
ALTER TABLE `subject_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `teacher_login`
--
ALTER TABLE `teacher_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

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
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `FK_section_id_key` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `program_id_key` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subjects_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
