-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2024 at 12:17 PM
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
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `is_ai_response` tinyint(1) DEFAULT 0,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `sender_id`, `admin_id`, `message`, `is_ai_response`, `timestamp`) VALUES
(1, 1, 2, 'hi', 0, '2024-10-25 16:03:06'),
(2, 1, 2, 'hania', 0, '2024-10-25 16:07:40'),
(3, 1, 2, 'hi', 0, '2024-10-25 16:07:49'),
(4, 1, 2, 'where is my order', 0, '2024-10-25 16:08:20'),
(5, 1, 2, 'hi', 0, '2024-10-25 16:11:06');

-- --------------------------------------------------------

--
-- Table structure for table `navbar_buttons`
--

CREATE TABLE `navbar_buttons` (
  `ID` int(11) NOT NULL,
  `button_name` varchar(100) NOT NULL,
  `pagelink` varchar(255) NOT NULL,
  `usertype_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `navbar_buttons`
--

INSERT INTO `navbar_buttons` (`ID`, `button_name`, `pagelink`, `usertype_id`) VALUES
(1, 'Home', 'http://localhost/Management-Inventory/Homepage.php', 0),
(2, 'About us', 'http://localhost/Management-Inventory/Aboutus.php', 0),
(3, 'Contact us', 'http://localhost/Management-Inventory/Contact.php', 0);

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
  `status` enum('pending','completed','shipped') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `product_id`, `supplier_id`, `quantity`, `order_date`, `status`) VALUES
(1, 1, 1, 5, '2024-10-01 00:00:00', 'completed'),
(17, 7, 3, 5, '2024-10-23 16:03:27', 'completed'),
(22, 1, 1, 6, '2024-10-23 16:34:53', 'pending'),
(23, 1, 1, 4, '2024-10-23 16:36:05', 'pending'),
(24, 1, 3, 10, '2024-10-23 16:43:57', 'completed');

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
  `Sales` int(11) DEFAULT 0,
  `Category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ID`, `ProductName`, `Price`, `SellerName`, `Picture`, `Quantity`, `image_url`, `Sales`, `Category`) VALUES
(1, 'Tea', 100, 'Lipton', '', 5, '\\Management-Inventory\\images\\tea.jpg', 0, 'Coffee and Tea'),
(2, 'Nescafe', 50, 'Nestle', '', 25, '\\Management-Inventory\\images\\nescafe.jpg', 0, 'Coffee and Tea'),
(3, 'Ice cream', 40, 'Nestle', '', 20, '\\Management-Inventory\\images\\ice cream.webp', 0, 'Sweets'),
(7, 'chocolate', 20, 'Dairy Milk', '', 40, '/Management-Inventory/images/vegan-milk-chocolate-recipe.jpg', 5, 'Sweets'),
(11, 'Redbull', 70, 'redbull', '', 35, '\\Management-Inventory\\images\\redbull.png', 0, 'Drinks'),
(12, 'croissant', 20, 'TBS', '', 9, '/Management-Inventory/images/images.jpg', 0, 'Bakery'),
(13, 'TOMATO', 10, 'tomato', '', 50, '\\Management-Inventory\\images\\TOMATO.jpg', 0, 'Groceries');

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
(1, 'Supplier 1', 'contact1@example.com', 'Net 4', 0, NULL),
(3, 'Supplier 4', 'supplier4@example.com', 'Net 60', 0, NULL),
(19, 'twinkies', 'twinkies2gmail.com', 'visa', 0, NULL),
(21, 'todo', 'todo@gmail.com', 'cash', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Email` text NOT NULL,
  `Password` text NOT NULL,
  `usertype_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Name`, `Email`, `Password`, `usertype_id`) VALUES
(1, 'Hania Maher', 'Hania2205040@miuegypt.edu.eg', '$2y$10$YHDMaLgXermsvXkkkFinBumhe3bfAqEZiiK1M29AbqsdW0qdfFmtW', 0),
(2, 'jana', 'jana@kjhb', '$2y$10$7JJm9g8nmEO9x6jJCZOcE.Zm5MqKamrb0UY7ywqAyTqSvbmqKnBBi', 2),
(3, 'drashraf', 'janah2202047@miuegypt.edu.eg', '$2y$10$QQV1hxgWGd4iWTbErSlULu/h0UhYo5grrxclA1i1BBKQgQwjQxA9m', 0),
(7, 'ahmed', 'ahmed@gmail.com', '$2y$10$rho/mS8dLu1fRZ9mcuc39.rCD9h/4ucUqklnd5LFh7VQ90pyxBZ/y', 1),
(8, 'omar', 'omar@gmail.com', '$2y$10$68HIQ36YuO7F/oLpWz1e5OaV9CEiprmfhEqsyi6JgySZFkOU76.Ze', 0),
(9, 'Hania Maher', 'Hania2205040@miue', '$2y$10$0ytwQ2OQfoDW3w0oyQ1TneI6NBzMQnJM5V8CoFzvTdQ77NtxhB9aq', 0),
(13, 'omar', 'omar100@gmail.com', '$2y$10$XKT.Fz4VsD6.CbSWrr6J/..XcLMYzUJjdexAm6UQ89h1uBCk/XVQC', 0),
(14, 'NESCAFEomar', 'nescafeomar@exaample.com', '$2y$10$A/qrI1trzqcfmB97x11YjeFWV9J0YRsFgOF64j40xDhMGvDtq4TOG', 2),
(17, 'mohamed', 'mohamed2103008@miuegypt.edu.eg', '$2y$10$A0eRAfUtFhGklN3iugSL7OBb8osGwgMa6eYDOYfi6jHuGoK5tuD9O', 0),
(18, 'sandy', 'sandy@gmail.com', '$2y$10$Id5Oqtw4UpGPegwlaPoxl.mjtMlrcqSx/dhP5QwtEX65bELY10fHW', 1),
(19, 'twinkies', 'twinkies2@gmail.com', '$2y$10$Wha.sz7EQaxGrTwvyXmYTekfFUQ29SF.zFKo6lYucyO2KmLqdwn5O', 2),
(20, 'bah', 'bahigmahmoud11@gamil.com', '$2y$10$sA1oitAkvBHhdgDc1HnDmeIMxIHGmchsp.N9Lhdmi.gFNeRH6c/ui', 2),
(21, 'todo', 'todo@gmail.com', '$2y$10$FruzkauxZE2KGbx0vu.mN.2MiIbFybTPGygWIEcq/2xgsKZQSd14K', 2);

-- --------------------------------------------------------

--
-- Table structure for table `usertypes`
--

CREATE TABLE `usertypes` (
  `ID` int(11) NOT NULL,
  `Name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usertypes`
--

INSERT INTO `usertypes` (`ID`, `Name`) VALUES
(0, 'Customer'),
(1, 'Admin'),
(2, 'Supplier');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`admin_id`);

--
-- Indexes for table `navbar_buttons`
--
ALTER TABLE `navbar_buttons`
  ADD PRIMARY KEY (`ID`);

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
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Usertype-id` (`usertype_id`);

--
-- Indexes for table `usertypes`
--
ALTER TABLE `usertypes`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `navbar_buttons`
--
ALTER TABLE `navbar_buttons`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`ID`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `Usertype-id` FOREIGN KEY (`usertype_id`) REFERENCES `usertypes` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
