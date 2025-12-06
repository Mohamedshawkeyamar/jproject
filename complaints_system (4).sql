-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 29 نوفمبر 2025 الساعة 01:36
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `complaints_system`
--

-- --------------------------------------------------------

--
-- بنية الجدول `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'jana', '123456'),
(2, 'aseel', '643011');

-- --------------------------------------------------------

--
-- بنية الجدول `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `citizen_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(150) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','real','fake') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `report_type` varchar(10) NOT NULL DEFAULT 'lost',
  `is_deleted` tinyint(1) DEFAULT 0,
  `last_modified` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `reports`
--

INSERT INTO `reports` (`id`, `citizen_name`, `phone`, `title`, `description`, `location`, `image_path`, `status`, `created_at`, `report_type`, `is_deleted`, `last_modified`) VALUES
(7, 'Amal Alharbi', '0552233119', 'Lost iPad', 'After my morning lecture, I left in a hurry and forgot my iPad in the classroom. When I went back to get it, I couldn’t find it. The iPad is an iPad Air with a black cover.', 'Building G4, Room 12', 'uploads/1764328706_ipad1.jpg', 'real', '2025-11-28 14:18:26', 'lost', 0, NULL),
(8, 'Reem Alotaibi', '0556677112', 'Lost Apple Wired Earphones', 'I lost my Apple wired earphones after leaving the classroom. The last place I remember having them was in the G2 hallway.', 'Building G2 – First Floor', 'uploads/1764370516_سماعة2.jpg', 'fake', '2025-11-29 01:55:16', 'lost', 0, NULL),
(9, 'Sara Alghamdi', '0588888122', 'Found iPad Stylus', 'An iPad stylus (Apple Pencil) was found in the library on the second floor and has been handed over to the Lost & Found Department.', 'Library – Second Floor', 'uploads/1764370704_pencil.jpg', 'real', '2025-11-29 01:58:24', 'found', 0, NULL),
(11, 'Nora Ahmed', '0558387489', 'Lost iPhone', 'My white iPhone 12 is missing. I last had it in the cafeteria.', 'Cafeteria', 'uploads/1764370827_iphone5.jpg', 'real', '2025-11-29 02:00:27', 'lost', 0, '2025-11-28 23:07:18'),
(12, 'Nawal Alshihri', '0558939281', 'Found Power Bank', 'A black power bank was found near the coffee machine and placed in the security office.', 'Building G2 -2', 'uploads/1764371013_شاحن.jpg', 'real', '2025-11-29 02:03:33', 'found', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
