-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2024 at 03:52 PM
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
-- Database: `absensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `subject` varchar(255) NOT NULL,
  `status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `grade` int(11) NOT NULL,
  `roll_number` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `grade`, `roll_number`) VALUES
(3, 'Jesica Cantika Pongilatan', 8, '1'),
(4, 'Jesicca Putri', 8, '2'),
(5, 'Kenzo Tampinongkol Pesik', 8, '3'),
(6, 'Yehezkiel A. A. Mawikere', 8, '4'),
(7, 'Zipora M. P. Manangkot', 8, '5'),
(8, 'Alfania M. Koogouw', 7, '1'),
(9, 'Alvino G. Manangkot', 7, '2'),
(10, 'Andra I.G. Mokolensang', 7, '3'),
(11, 'Asmiranda A. Moningkey', 7, '4'),
(12, 'Aura H. Besouw', 7, '5'),
(13, 'Blessing S. Rotinsulu', 7, '6'),
(14, 'Hillary B. Marande', 7, '7'),
(15, 'Jedytian S. Vergouw', 7, '8'),
(16, 'Jeremi C. Lanawaang', 7, '9'),
(17, 'Julianie H.D. Wohon', 7, '10'),
(18, 'Justicyo M. Vergouw', 7, '11'),
(19, 'Lionel M. Maengkom', 7, '12'),
(20, 'Rolend W. Sumarti', 7, '13'),
(21, 'Tattiana F.K. Mambu', 7, '14'),
(22, 'Tsar Flow Mukuan', 7, '15'),
(23, 'Veron K. Lukas', 7, '16'),
(24, 'Amelia Tatilu', 7, '17'),
(25, 'Israel Tatilu', 7, '18'),
(26, 'Aira Pratasik', 9, '1'),
(27, 'Alexandra Lapian', 9, '2'),
(28, 'Aylin Item', 9, '3'),
(29, 'Christalita Kandouw', 9, '4'),
(30, 'Christian Vergouw', 9, '5'),
(31, 'Fabiano Roeth', 9, '6'),
(32, 'Febriano Kawilarang', 9, '7'),
(33, 'Jelia Tooleng', 9, '8'),
(34, 'Kayla Manapo', 9, '9'),
(35, 'Kyrei Posumah', 9, '10'),
(36, 'Marsya Subroto', 9, '11'),
(37, 'Mikhael Pratasik', 9, '12'),
(38, 'Narisky Karisoh', 9, '13'),
(39, 'Natalia Sumanti', 9, '14'),
(40, 'Rockly Karisoh', 9, '15'),
(41, 'Queensy Pratasik', 9, '16'),
(42, 'Yoga Repi', 9, '17');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','guru') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'adminakses', '$2a$12$atDlsVGTZGvI0Rx2HY4uWeJod49X69BfH27eSwBlj4yoEC4Qv.ih6', 'admin'),
(2, 'guruakses', '$2a$12$b7Kz7Kbr/Vll7UXdrZaNkOmSNU7oVeKe9kloDgR1F73hnplqAriiC', 'guru');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
