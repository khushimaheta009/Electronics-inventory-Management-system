-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Oct 13, 2026 at 06:14 AM
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
-- Database: `inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `role`) VALUES
(1, 'mitali09', '12345', 'm@gmail.com', 'Staff'),
(2, 'gargi29', '292929', 'g@gmail.com', 'Admin'),
(3, 'mivan12', 'mivan', 'm@gmail.com', 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `description`, `status`, `created_at`) VALUES
(1, 'Mobile', 'Smartphones and accessories', 'Active', '2026-07-22 06:08:28'),
(2, 'Laptop', 'Laptops and notebooks', 'Active', '2026-07-22 06:08:28'),
(3, 'Television', 'LED, LCD and Smart TVs', 'Active', '2026-07-22 06:08:28'),
(4, 'Refrigerator', 'Single and double door refrigerators', 'Inactive', '2026-07-22 06:08:28'),
(5, 'Washing Machine', 'Front load and top load washing machines', 'Active', '2026-07-22 06:08:28'),
(6, 'earphone', 'new', 'Active', '2026-09-01 14:52:46'),
(17, 'charger', 'Mobile chargers, fast charging adapters, USB cables, and wireless charging pads.', 'Active', '2026-10-09 16:50:15'),
(19, 'covers', 'new updated', 'Active', '2026-10-11 13:39:31');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `contact_no` varchar(15) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `customer_name`, `contact_no`, `email_address`, `address`) VALUES
(1, 'Rahul Patel', '9876543210', 'r@gmail.com', 'Ahmedabad'),
(2, 'Priya Shah', '9876543211', 'p@gmail.com', 'Surat'),
(3, 'Amit Kumar', '9876543212', 'a@gmail.com', 'surat'),
(4, 'raju', '2323232323', 'raju@gmail.cpm', 'rajkot'),
(5, 'raju', '7878654576', 'raju01@gmail.com', 'gandhinagar'),
(6, 'jayeshbhai', '6767676789', 'j@gmail.com', 'gandhinagar'),
(7, 'jayeshbhai', '6767676789', 'j@gmail.com', 'gandhinagar'),
(8, 'mirag', '9876543212', 'm@gmail.com', 'ahemdabad'),
(9, 'jignesh patel', '6787890987', 'jig@gmail.com', 'gandhinagar');

-- --------------------------------------------------------

--
-- Table structure for table `inventorytable`
--

CREATE TABLE `inventorytable` (
  `inventory_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity_in_stock` int(11) NOT NULL DEFAULT 0,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventorytable`
--

INSERT INTO `inventorytable` (`inventory_id`, `product_id`, `quantity_in_stock`, `last_updated`) VALUES
(1, 1, 26, '2026-10-10 10:38:04'),
(2, 2, 15, '2026-07-02 00:00:00'),
(3, 3, 10, '2026-07-03 00:00:00'),
(4, 4, 8, '2026-07-04 00:00:00'),
(5, 5, 12, '2026-07-05 00:00:00'),
(11, 3, 123, '2026-09-01 20:59:00'),
(101, 2, 111, '2026-09-01 20:50:00'),
(102, 2, 111, '2026-09-02 20:53:00'),
(111, 3, 12, '2026-09-03 20:56:00'),
(113, 3, 123, '2026-09-01 21:00:00'),
(114, 1114, 2, '2026-10-11 09:53:33'),
(115, 1115, 10, '2026-10-11 09:51:53'),
(116, 1116, 0, '2026-10-11 19:15:30');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_type` varchar(100) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model` varchar(100) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `purchase_cost` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `storage_location` varchar(100) DEFAULT NULL,
  `warranty_period` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_type`, `category_id`, `brand`, `model`, `sku`, `barcode`, `price`, `purchase_cost`, `quantity`, `storage_location`, `warranty_period`) VALUES
(1, 'Samsung Galaxy A56', NULL, 1, 'Samsung', NULL, NULL, NULL, 28999.00, NULL, 25, NULL, NULL),
(2, 'HP Pavilion 15', NULL, 2, 'HP', NULL, NULL, NULL, 62999.00, NULL, 10, NULL, NULL),
(3, 'Sony Bravia 43-inch', NULL, 3, 'Sony', NULL, NULL, NULL, 45999.00, NULL, 8, NULL, NULL),
(4, 'LG Double Door Refrigerator', NULL, 4, 'LG', NULL, NULL, NULL, 38999.00, NULL, 6, NULL, NULL),
(5, 'IFB Front Load Washing Machine', NULL, 5, 'IFB', NULL, NULL, NULL, 32999.00, NULL, 5, NULL, NULL),
(1112, 'p001', 'phone', 1, 'apple', 'A3090', 'apl-ip15-128', '198723456765', 78000.00, 70000.00, 25, 'rack A-1', '1 year'),
(1113, 'iphone 17', 'phone', 1, 'apple', 'A3090', 'apl-ip17-128', '198723456765', 400000.00, 300000.00, 12, 'rack A-2', '1 year'),
(1114, 'iphone 18', 'phone', 1, 'apple', 'A3090', 'apl-ip17-128', '198723456765', 400000.00, 300000.00, 5, 'rack A-2', '1 year'),
(1115, 'one plus node 5', 'phone', 1, 'one plus', 'node 5', 'apl-ip5-256', '198787676754', 34000.00, 30000.00, 10, 'rack 2', '1 year'),
(1116, 'motorola', 'phone', 1, 'motorola', '12', 'apl-plus-256', '123456789876', 34000.00, 30000.00, 30, 'rack 2-a', '1 year');

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `purchase_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`purchase_id`, `product_id`, `supplier_id`, `quantity`, `total_amount`, `purchase_date`) VALUES
(7, 1, 6, 10, 270000.00, '2026-07-01 00:00:00'),
(10, 2, 6, 11, 450000.00, '2026-07-01 00:00:00'),
(11, 3, 7, 10, 470000.00, '2026-07-01 00:00:00'),
(15, 1, 6, 10, 10000.00, '2026-09-02 00:00:00'),
(17, 1, 6, 1, 100000.00, '2026-10-10 07:08:04'),
(18, 1113, 6, 10, 4000000.00, '2026-10-10 07:13:21'),
(19, 1114, 7, 5, 10000000.00, '2026-10-10 07:20:02'),
(20, 1115, 8, 10, 300000.00, '2026-10-11 06:21:53'),
(21, 1116, 6, 1, 30000.00, '2026-10-11 15:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `sale_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`sale_id`, `product_id`, `customer_id`, `quantity`, `total_amount`, `sale_date`) VALUES
(11, 1, 1, 2, 57998.00, '2026-07-01 00:00:00'),
(12, 2, 2, 2, 125998.00, '2026-07-02 00:00:00'),
(13, 3, 3, 1, 45999.00, '2026-07-03 00:00:00'),
(14, 5, 2, 15, 120000.00, '2026-09-02 00:00:00'),
(15, 1114, 3, 1, 400000.00, '2026-10-10 07:26:19'),
(16, 1114, 3, 2, 800000.00, '2026-10-11 06:23:33'),
(17, 1116, 9, 1, 30000.00, '2026-10-11 15:45:30');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `contact_no` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `supplier_name`, `contact_no`, `email`, `address`) VALUES
(6, 'jimeshbhai', '9876500001', 'tech@gmail.com', 'Ahmedabad'),
(7, 'ram bhai', '9876500002', 'digital@gmail.com', 'Surat'),
(8, 'ramesh bhai', '9876500003', 'shree@gmail.com', 'Vadodara'),
(9, 'kishorbhai patel', '9876543212', 'kishor@gmail.com', 'surat'),
(10, 'kishorbhai patel', '1234567898', 'j@gmail.com', 'gandhinagar');

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `inventorytable`
--
ALTER TABLE `inventorytable`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_categories` (`category_id`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`purchase_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`sale_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `inventorytable`
--
ALTER TABLE `inventorytable`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1117;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventorytable`
--
ALTER TABLE `inventorytable`
  ADD CONSTRAINT `inventorytable_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `purchase_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  ADD CONSTRAINT `purchase_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
