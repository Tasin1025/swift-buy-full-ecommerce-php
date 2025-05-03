-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2025 at 01:38 PM
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
-- Database: `swiftbuy`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_name`, `product_price`, `quantity`, `created_at`) VALUES
(15, 1, 'Laptop Pro 15', 799.99, 1, '2025-05-03 11:05:02');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `payment_method`, `address`, `created_at`) VALUES
(1, 1, 138.00, 'Pending', 'bkash', 'aa', '2025-05-03 08:40:44'),
(2, 1, 68.00, 'Pending', 'bkash', 'as', '2025-05-03 08:47:00'),
(3, 1, 204.00, 'Pending', 'cod', '', '2025-05-03 08:58:16'),
(4, 1, 48.00, 'Pending', 'bank', 'axa', '2025-05-03 09:05:00'),
(5, 1, 102.00, 'Pending', 'bkash', 'sasa', '2025-05-03 09:12:56'),
(6, 1, 172.00, 'canceled', 'cod', 'asdas', '2025-05-03 09:33:52'),
(32, 1, 299.99, 'pending', 'bkash', '1234 Dhanmondi, Dhaka, Bangladesh', '2025-01-05 04:15:00'),
(33, 1, 799.99, 'delivered', 'bank', '5678 Gulshan-2, Dhaka, Bangladesh', '2025-02-12 06:45:00'),
(34, 1, 149.99, 'canceled', 'cod', '9101 Banani, Dhaka, Bangladesh', '2025-03-03 08:30:00'),
(35, 1, 199.99, 'pending', 'bkash', '1112 Mirpur, Dhaka, Bangladesh', '2025-03-25 10:20:00'),
(36, 1, 59.99, 'delivered', 'bank', '1314 Uttara, Dhaka, Bangladesh', '2025-04-05 03:00:00'),
(37, 1, 499.99, 'delivered', 'bkash', '1516 Banasree, Dhaka, Bangladesh', '2025-04-17 12:40:00'),
(38, 1, 29.99, 'pending', 'cod', '1718 Shajahanpur, Dhaka, Bangladesh', '2025-05-01 05:25:00'),
(39, 1, 19.99, 'delivered', 'bank', '1920 Bashundhara, Dhaka, Bangladesh', '2025-05-03 07:50:00'),
(40, 1, 799.99, 'delivered', 'bkash', '2122 Kamalapur, Dhaka, Bangladesh', '2025-05-05 11:35:00'),
(41, 1, 199.99, 'pending', 'cod', '2324 Mirpur-10, Dhaka, Bangladesh', '2025-05-10 09:10:00');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_name`, `product_price`, `quantity`) VALUES
(1, 1, 'bb', 34.00, 3),
(2, 1, 'aa', 12.00, 3),
(3, 2, 'bb', 34.00, 2),
(4, 3, 'bb', 34.00, 6),
(5, 4, 'aa', 12.00, 4),
(6, 5, 'bb', 34.00, 3),
(7, 6, 'bb', 34.00, 4),
(8, 6, 'aa', 12.00, 3);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `image`, `created_at`) VALUES
(8, 'bb', 'bb', 34.00, 4, 'product_images/index.png', '2025-05-02 22:41:40'),
(9, 'Smartphone X1', 'Latest model with high performance.', 299.99, 50, 'product_images/1.jpg', '2025-05-03 10:57:44'),
(10, 'Laptop Pro 15', 'Powerful laptop with advanced features.', 799.99, 30, 'product_images/2.jpg', '2025-05-03 10:57:44'),
(11, 'Wireless Headphones', 'Noise-cancelling wireless headphones.', 149.99, 100, 'product_images/3.jpg', '2025-05-03 10:57:44'),
(12, 'Smartwatch 4', 'Track your fitness and notifications on the go.', 199.99, 75, 'product_images/4.jpg', '2025-05-03 10:57:44'),
(13, 'Bluetooth Speaker', 'Portable speaker with great sound quality.', 59.99, 200, 'product_images/5.jpg', '2025-05-03 10:57:44'),
(14, '4K UHD TV', 'High-definition TV with stunning picture quality.', 499.99, 20, 'product_images/6.jpg', '2025-05-03 10:57:44'),
(15, 'Gaming Mouse', 'Precision gaming mouse for ultimate control.', 29.99, 150, 'product_images/7.jpg', '2025-05-03 10:57:44'),
(16, 'Smartphone Charger', 'Fast charger for your devices.', 19.99, 300, 'product_images/8.jpg', '2025-05-03 10:57:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `address` text NOT NULL,
  `profile_picture` varchar(255) DEFAULT 'profile_picture/default.jpg',
  `phone` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`, `address`, `profile_picture`, `phone`, `created_at`) VALUES
(1, 'Tahla', 'talha@gmail.com', '$2y$10$MW/jrhBqVZpZjq1Ytk2/9.W7bmNt4zffSmbEyo1Z0JmTFlAT3hLB6', 'user', 'Uttara', 'profile_picture/default.jpg', '12345678', '2025-05-02 13:18:30'),
(2, 'Tahla', 'admin@gmail.com', '$2y$10$vWnSp6c.XvGdZtFENo8kJ.Il6YW/DlQ1svAH9VHTu3D8hKoEsGMdq', 'admin', 'Uttara', 'profile_picture/default.jpg', '123456', '2025-05-02 13:25:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
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
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
