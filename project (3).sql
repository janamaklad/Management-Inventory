-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2024 at 06:04 PM
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
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','completed','canceled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `product_id`, `supplier_id`, `quantity`, `order_date`, `status`) VALUES
(1, 1, 1, 5, '2024-10-01 00:00:00', 'completed'),
(2, 2, 2, 3, '2024-10-05 00:00:00', 'completed'),
(3, 7, 3, 10, '2024-10-10 00:00:00', 'completed'),
(4, 11, 4, 7, '2024-10-15 00:00:00', 'pending'),
(15, 1, 18, 8, '2024-10-22 20:52:02', 'pending'),
(16, 1, 1, 4, '2024-10-23 12:57:01', 'pending'),
(17, 7, 3, 5, '2024-10-23 16:03:27', 'completed'),
(22, 1, 1, 6, '2024-10-23 16:34:53', 'pending'),
(23, 1, 1, 4, '2024-10-23 16:36:05', 'pending'),
(24, 1, 3, 10, '2024-10-23 16:43:57', 'completed'),
(25, 7, 3, 5, '2024-10-23 17:06:10', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `ID` int(11) NOT NULL,
  `PageName` text NOT NULL,
  `Linkaddress` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ID` int(11) NOT NULL,
  `ProductName` text NOT NULL,
  `Price` int(11) NOT NULL,
  `SellerName` text NOT NULL,
  `Picture` blob NOT NULL,
  `Quantity` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `Sales` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ID`, `ProductName`, `Price`, `SellerName`, `Picture`, `Quantity`, `image_url`, `Sales`) VALUES
(1, 'Tea', 100, 'Lipton', '', 5, '\\Management-Inventory\\images\\tea.jpg', 0),
(2, 'Nescafe', 50, 'Nestle', '', 30, '\\Management-Inventory\\images\\nescafe.jpg', 0),
(3, 'Ice cream', 40, 'Nestle', '', 20, '\\Management-Inventory\\images\\ice cream.webp', 0),
(7, 'chocolate', 20, 'Dairy Milk', '', 40, '/Management-Inventory/images/vegan-milk-chocolate-recipe.jpg', 5),
(11, 'Redbull', 70, 'redbull', '', 50, '\\Management-Inventory\\images\\redbull.png', 0),
(12, 'croissant', 20, 'TBS', '', 9, '/Management-Inventory/images/images.jpg', 0),
(13, 'TOMATO', 10, 'tomato', '', 50, '\\Management-Inventory\\images\\TOMATO.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `report_type` varchar(255) NOT NULL,
  `report_date` date NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `contact_info` varchar(255) NOT NULL,
  `payment_terms` varchar(255) NOT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `supplier_name`, `contact_info`, `payment_terms`, `is_deleted`, `user_id`) VALUES
(1, 'Supplier 1', 'contact1@example.com', 'Net 3', 0, NULL),
(2, 'Supplier 2', 'contact2@example.com', 'Net 15', 0, NULL),
(3, 'Supplier 3', 'contact3@example.com', 'Net 60', 0, NULL),
(4, 'Supplier 4', 'contact4@example.com', 'Net 45', 1, NULL),
(18, 'juhaina', '0', 'hfn', 1, NULL),
(24, 'juhaina', '45', 'hfn', 1, NULL),
(25, 'juhaina', '45', 'hfn', 0, NULL),
(26, 'shipcy', '11', 'visa', 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Email` text NOT NULL,
  `Password` text NOT NULL,
  `Usertype-id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Name`, `Email`, `Password`, `Usertype-id`) VALUES
(3, 'shipcy', 'shipcy@gmail.com', '$2y$10$25jILiIFDSlkselz.m0vCOCKwXcRXQEWfzbd.24bZjmFV8P/H60Ze', 2);

-- --------------------------------------------------------

--
-- Table structure for table `usertypes`
--

CREATE TABLE `usertypes` (
  `ID` int(11) NOT NULL,
  `Name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usertypes-pages`
--

CREATE TABLE `usertypes-pages` (
  `ID` int(11) NOT NULL,
  `usertype-id` int(11) NOT NULL,
  `page-id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `usertypes`
--
ALTER TABLE `usertypes`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `usertypes-pages`
--
ALTER TABLE `usertypes-pages`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `usertypes-pages`
--
ALTER TABLE `usertypes-pages`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`ID`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
