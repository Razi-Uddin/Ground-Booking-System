-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 30, 2025 at 04:22 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ground_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `ground_id` int(11) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `total_hours` decimal(4,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `ground_id`, `customer_name`, `customer_id`, `staff_id`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_amount`, `status`, `created_at`) VALUES
(1, 1, 'Qamar', 1, 3, '2025-08-15', '18:24:00', '19:24:00', 1.00, 2000.00, 'confirmed', '2025-08-15 13:28:07'),
(2, 1, 'Qamar', 1, 3, '2025-08-15', '20:29:00', '21:29:00', 1.00, 2000.00, 'confirmed', '2025-08-15 13:29:46'),
(3, 1, 'Qamar', 1, 3, '2025-08-15', '18:30:00', '19:30:00', 1.00, 2000.00, 'confirmed', '2025-08-15 13:30:18'),
(4, 1, 'Qamar', 1, 3, '2025-08-15', '00:00:00', '01:00:00', 1.00, 2000.00, 'confirmed', '2025-08-15 13:48:48');

-- --------------------------------------------------------

--
-- Table structure for table `grounds`
--

CREATE TABLE `grounds` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `location` text DEFAULT NULL,
  `per_hour_charge` decimal(10,2) NOT NULL,
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grounds`
--

INSERT INTO `grounds` (`id`, `admin_id`, `name`, `location`, `per_hour_charge`, `opening_time`, `closing_time`, `image`, `status`, `created_at`) VALUES
(1, 2, 'Usama Ground', 'Baloch Colony, Karachi', 2000.00, '06:00:00', '02:00:00', '1755258353_g1.jpeg', 1, '2025-08-15 11:45:53');

-- --------------------------------------------------------

--
-- Table structure for table `ground_items`
--

CREATE TABLE `ground_items` (
  `id` int(11) NOT NULL,
  `ground_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ground_items`
--

INSERT INTO `ground_items` (`id`, `ground_id`, `name`, `quantity`, `status`, `created_at`) VALUES
(1, 1, 'Wicket', 2, 0, '2025-08-15 12:19:32');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','card','online') NOT NULL,
  `payment_status` enum('paid','unpaid') DEFAULT 'unpaid',
  `paid_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin','staff','customer') NOT NULL,
  `parent_admin_id` int(11) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `parent_admin_id`, `phone`, `status`, `created_at`) VALUES
(1, 'Super Admin', 'superadmin@gmail.com', 'f35364bc808b079853de5a1e343e7159', 'superadmin', NULL, '0000000000', 1, '2025-08-15 10:40:56'),
(2, 'Jamaluddin', 'jamal@gmail.com', '7592628bce37dd14e0b36ea66d5ba847', 'admin', NULL, '0312', 1, '2025-08-15 11:35:21'),
(3, 'Razi Uddin', 'razi@gmail.com', '65ca27770cedc0adc95d9a48020b9f93', 'staff', 2, NULL, 1, '2025-08-15 12:08:58'),
(4, 'Saaduddin', 'saad@gmail.com', 'f290ca45b0dec2ec16cf3afcafbea6ac', 'customer', NULL, '03132156464', 1, '2025-08-15 13:52:20');

-- --------------------------------------------------------

--
-- Table structure for table `walkin_bookings`
--

CREATE TABLE `walkin_bookings` (
  `id` int(11) NOT NULL,
  `ground_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `customer_name` varchar(150) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `total_hours` decimal(4,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walkin_bookings`
--

INSERT INTO `walkin_bookings` (`id`, `ground_id`, `staff_id`, `customer_name`, `customer_phone`, `booking_date`, `start_time`, `end_time`, `total_hours`, `total_amount`, `status`, `created_at`) VALUES
(1, 1, 3, 'Qamar', '03021565', '2025-08-15', '03:00:00', '16:00:00', 13.00, 26000.00, 'confirmed', '2025-08-15 13:21:59'),
(2, 1, 3, 'Qamar', '0315545454545', '2025-08-15', '18:22:00', '19:22:00', 1.00, 2000.00, 'confirmed', '2025-08-15 13:22:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ground_id` (`ground_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `grounds`
--
ALTER TABLE `grounds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `ground_items`
--
ALTER TABLE `ground_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ground_id` (`ground_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `walkin_bookings`
--
ALTER TABLE `walkin_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ground_id` (`ground_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `grounds`
--
ALTER TABLE `grounds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ground_items`
--
ALTER TABLE `ground_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `walkin_bookings`
--
ALTER TABLE `walkin_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`ground_id`) REFERENCES `grounds` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `grounds`
--
ALTER TABLE `grounds`
  ADD CONSTRAINT `grounds_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ground_items`
--
ALTER TABLE `ground_items`
  ADD CONSTRAINT `ground_items_ibfk_1` FOREIGN KEY (`ground_id`) REFERENCES `grounds` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `walkin_bookings`
--
ALTER TABLE `walkin_bookings`
  ADD CONSTRAINT `walkin_bookings_ibfk_1` FOREIGN KEY (`ground_id`) REFERENCES `grounds` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `walkin_bookings_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
