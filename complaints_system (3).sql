-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 26 نوفمبر 2025 الساعة 20:43
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
(1, 'jana', '123456');

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
(3, 'janann', '3465434343', 'سرقة', 'سرقة جوال', 'مكة', 'uploads/1763898091_WhatsApp Image 2025-02-11 at 20.44.14_e2bd2eb1.jpg', 'real', '2025-11-23 13:41:31', 'lost', 0, '2025-11-26 19:26:36'),
(4, 'mohamed', '01029634349', 'مشكلة', 'مشكلة', 'elmonshah', 'uploads/1763898129_WhatsApp Image 2025-01-21 at 15.27.36_a98293c9.jpg', 'fake', '2025-11-23 13:42:09', 'lost', 0, NULL),
(5, 'HHD', '555555555555', 'DDD', 'EEEEEEEEEEEE', 'WWWWWWWW', 'uploads/1763943902_wave.png', 'real', '2025-11-24 03:25:02', 'found', 1, NULL),
(6, 'ssssss', '44444444444', 'dddddd', 'dd', 'dddddddd', NULL, 'real', '2025-11-26 22:13:41', 'found', 1, NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `college` varchar(50) DEFAULT NULL,
  `major` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `college`, `major`, `date`, `role`) VALUES
(1, 'Rana', 'rana123@hotmail.com', '6e9454559ab0f65c702f78d553acab30', '050544599', 'Engineering', 'IT', '2025-11-22', 'admin'),
(2, 'Asil', 'asil@gmail.com', '64c46480bcd47e7c600f3533cb5d5322', '0501111', 'Computer Science', 'CS', '2025-11-22', 'user'),
(3, 'abrar', 'abarar@gmail.com', '177849a379bc4d3296e617dd5e975ac8', '0525555', 'Engineering', 'IT', '2025-11-11', 'user');

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
