-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Apr 07, 2023 at 08:58 PM
-- Server version: 10.9.4-MariaDB-1:10.9.4+maria~ubu2204
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `OurMarketDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `Ads`
--

CREATE TABLE `Ads` (
  `id` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `postedDate` date NOT NULL DEFAULT current_timestamp(),
  `price` decimal(10,2) NOT NULL,
  `userID` int(11) NOT NULL,
  `imageURI` varchar(1000) NOT NULL,
  `status` enum('Available','Sold','Expired') NOT NULL DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Ads`
--

INSERT INTO `Ads` (`id`, `productName`, `description`, `postedDate`, `price`, `userID`, `imageURI`, `status`) VALUES
(8, 'Honda Crf 250', 'New Honda CRf -250 ,2000km run only&amp;quot; Newly', '2022-12-30', '9500.00', 1, '/img/Honda Crf 250-2022-12-30-Test.jpg', 'Expired'),
(13, 'Ktm Duke-390', 'The KTM 790 DUKE  ', '2023-01-01', '900.00', 1, '/img/Ktm Duke-390-2023-01-01-Test.jpg', 'Available'),
(24, 'CrossFire new', 'Bijay', '2023-01-09', '9000.00', 1, '/img/OurMarket-2023-01-09-1673296197-Test.jpg', 'Available'),
(25, 'Bijay', 'sdsdsdsd', '2023-01-12', '90000.00', 1, '/img/OurMarket-2023-01-12-1673562154-Test.jpg', 'Available'),
(26, 'Newly New Bike', 'hvbv b hvv', '2023-01-15', '900.00', 1, '/img/OurMarket-2023-01-15-1673781376-Test.png', 'Available'),
(27, 'Electric Bike-2022 ', 'Americans Bike ', '2023-01-15', '1200.00', 1, '/img/OurMarket-2023-01-15-1673794055-Test.png', 'Available'),
(28, 'Benelli Tnt- 300', 'Mode Benelli TNT-300 ', '2023-01-16', '5000.60', 1, '/img/OurMarket-2023-01-16-1673912055-Test.png', 'Available'),
(30, 'Yatri Bike ', 'Brand New', '2023-01-20', '900.00', 4, '/img/OurMarket-2023-01-20-1674226194-Muna.png', 'Available'),
(36, 'Joshua ', 'On Sale', '2023-04-05', '644.00', 1, '/img/64306305762b9.jpeg', 'Sold'),
(38, 'Joshua 11', 'sfdfdfdfdfddsdsddf', '2023-04-07', '4500.90', 1, '/img/642ff9a142076.png', 'Sold');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `id` int(11) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `HashPassword` varchar(255) NOT NULL,
  `Salt` varchar(255) NOT NULL,
  `Role` enum('Admin','Customer') NOT NULL DEFAULT 'Customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`id`, `firstName`, `lastName`, `email`, `HashPassword`, `Salt`, `Role`) VALUES
(1, 'Test', 'Inholland', 'test@inholland.nl', '$argon2i$v=19$m=65536,t=4,p=1$N1RxQVBSOFowY0hlY09LLg$Rqrkc89iZO5Y4eM8ZfLQxiVYq4ivqo39d+d+Ru5Z/SI', '355d378154265e840889f2a394337f9061b68dac95f1331d6b4ba562ac0a56a1', 'Admin'),
(2, 'Bijay', 'Sapkota', 'bijay@inholland.nl', '$argon2i$v=19$m=65536,t=4,p=1$RmNaaEt1dDVNNXZQZFhiaA$iJ69sxER++TaC40D1VrpQdCMNb2o8cUZuW1uc6x0n3Y', 'defb4a15b13f74b82bab20461ab6ff4508886f92ade08431db5a9b241853d1cf', 'Admin'),
(3, 'Durga Devi', 'Sapkota', 'Durga@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$Y1ZGdzl5UTZ5dVRvWThDYw$w3isE1URIXsowejxrO7VlnyaN4ru1IfJw1ZF5UTK8Wc', '845ec4e7be54c9b75906bc797df2a276e620e09e7eef436e90528a831c897230', 'Admin'),
(4, 'Muna', 'Kandel', 'Muna@inholland.nl', '$argon2i$v=19$m=65536,t=4,p=1$Nktxay9RUUpqNUl0Z3ZiSw$Ol7kNEd1CbQXWi5kO7Kq+EMGe3emQDZ2XsGdKDxSv1Y', '18f9edfefb4f4e991e5ea215fcfc500ee6d89e89dcf5a49d24893a4b305dcb14', 'Admin'),
(12, 'df', 'dfdf', 'test112@inholland.nl', '$argon2i$v=19$m=65536,t=4,p=1$bklhZGd3T1Y1WnpnWk82VA$gxVoLN7P9P0Ic30d3gGU+OLdqaKF+dli711ews/QXmQ', '21023330bf502b77b270cd85b3416560fbb012403d2581edf156f9f6ce97d628', 'Customer'),
(13, 'df', 'dfdf', 'test11@inholland.nl', '$argon2i$v=19$m=65536,t=4,p=1$SFdKNjQyL1ZFWEZBM0laZA$9qNaUM9rJ81wOumta1vm+DpEFIMVKMrnAxDp3XkHRmE', '39c9e365395ed88eb995c054dafb308e796cc9dde49ad3ab80e7836999a3333e', 'Customer'),
(19, 'johsua', 'Joshua', 'jo@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$SHdMTlRTNVg2TUREWDJUVA$zaQTD0G3TPyn6JNvGfLqmqEnFjBltc6dym/0XJMi3SM', 'bede53fc086c492c3137f9ab0d9f2bbea28848210ccc19bc659dbee85b267d59', 'Customer'),
(20, 'Bijay', 'Sapkota', 'jo11@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$ZWVya3U3aFdUWlBmd3JyMw$su1zqf8+CRQ1TY03JOSWN/8L/Ko6i2oAk8AjDVc2haE', '3a78016674349d0652a63bab4ca70124d751a1bc5caa5bb961c30a5942b145ba', 'Customer'),
(21, 'Test', 'Inholland', 'nojavascript@inholland.nl', '$argon2i$v=19$m=65536,t=4,p=1$RlFjNFFUcWRYY2hjV1VEZQ$CRucK0lC9mpcWKfJIFfnDPJG2yrwEaQQErquMXq5Eh0', '239c841e1cb3260c93d6c983814cd3dd6c498e0eb0efa45809f5d762eea5ac51', 'Customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Ads`
--
ALTER TABLE `Ads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Ads_fk` (`userID`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Ads`
--
ALTER TABLE `Ads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Ads`
--
ALTER TABLE `Ads`
  ADD CONSTRAINT `Ads_fk` FOREIGN KEY (`userID`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
