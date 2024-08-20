-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2024 at 10:20 PM
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
-- Database: `phpproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `date` datetime NOT NULL,
  `content` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`firstname`, `lastname`, `email`, `date`, `content`) VALUES
('george', 'yousef', 'gorg_yosef@outlook.com', '2024-03-20 00:00:00', 'hhhd'),
('george', 'yousef', 'gorg_yosef@outlook.com', '2024-03-20 00:00:00', 'hhhd'),
('george', 'yousef', 'gorg_yosef@outlook.com', '2024-03-20 00:00:00', 'hhhd'),
('george', 'yousef', 'gorg_yosef@outlook.com', '2024-03-20 00:00:00', 'hhhd'),
('george', 'yousef', 'gorg_yosef@outlook.com', '2024-03-20 00:00:00', 'hhhd'),
('george', 'yousef', 'gorg_yosef@outlook.com', '2024-03-25 23:47:00', 'jhcghhgchfcf'),
('george', 'yousef', 'gorg_yosef@outlook.com', '2024-03-25 23:47:00', 'jhcghhgchfcf'),
('george', 'yousef', 'gorg_yosef@outlook.com', '2024-03-25 23:47:00', 'jhcghhgchfcf');

-- --------------------------------------------------------

--
-- Table structure for table `manager`
--

CREATE TABLE `manager` (
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `manager_id` int(33) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manager`
--

INSERT INTO `manager` (`firstname`, `lastname`, `manager_id`) VALUES
('george', 'yosef', 214024333),
('tamer', 'kabha', 212955751);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `username` varchar(255) NOT NULL,
  `id` int(10) NOT NULL,
  `typeofshipping` varchar(255) NOT NULL,
  `totaleprice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`username`, `id`, `typeofshipping`, `totaleprice`) VALUES
('rami1', 16, 'Shipping', 1050);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `img` varchar(255) NOT NULL,
  `id` int(11) NOT NULL,
  `pname` varchar(255) NOT NULL,
  `price` int(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `weight` varchar(255) NOT NULL,
  `inventory` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`img`, `id`, `pname`, `price`, `color`, `weight`, `inventory`) VALUES
('img11.jpeg', 2222, 'test', 300, 'black', '200', 14),
('img10.jpeg', 7234, 'proten', 500, 'black', '500gr', 12),
('img2.jpeg', 11234, 'proten', 300, 'blue', '2kg', 12),
('img6.jpeg', 11945, 'proten', 250, 'yellow', '1000gr', 12),
('img1.jpeg', 12345, 'proten', 300, 'red', '300gr', 12),
('img7.jpeg', 22930, 'proten', 900, 'red', '820gr', 12),
('img9.jpeg', 25571, 'proten', 1000, 'pink', '3kg', 9),
('img3.jpeg', 45538, 'proten', 100, 'pink', '300gr', 12),
('img8.jpeg', 77392, 'proten', 550, 'purple', '800gr', 12),
('img4.jpeg', 88209, 'proten', 600, 'black', '1kg', 12),
('img5.jpeg', 993012, 'proten', 300, 'white', '970gr', 12);

-- --------------------------------------------------------

--
-- Table structure for table `shoppingcart`
--

CREATE TABLE `shoppingcart` (
  `username` varchar(255) NOT NULL,
  `productid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `checked` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shoppingcart`
--

INSERT INTO `shoppingcart` (`username`, `productid`, `quantity`, `checked`) VALUES
('rami1', 25571, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `usercopy`
--

CREATE TABLE `usercopy` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `id` int(255) NOT NULL,
  `entert` int(11) NOT NULL,
  `login_attempts` int(11) NOT NULL,
  `datetimelogin` datetime NOT NULL DEFAULT '2024-03-27 00:00:00',
  `failn` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usercopy`
--

INSERT INTO `usercopy` (`username`, `password`, `firstname`, `lastname`, `email`, `phone`, `birthday`, `id`, `entert`, `login_attempts`, `datetimelogin`, `failn`) VALUES
('ahmed1', '234568', 'ahmed', 'noor', 'ahemt@gmail.com', '057455123', '2003-12-30', 881234, 1, 0, '2024-03-27 00:00:00', 0),
('roro', 'Wgj0HZfs', 'aaaaa', 'aaaa', 'Roneetewies538@gmail.com', '07087896', '2024-02-29', 970798, 1, 0, '2024-03-27 00:00:00', 0),
('lolo', '102050', 'lolo', 'lolo', 'lolo664@gmail.com', '046257105', '2004-03-01', 1222548, 1, 0, '2024-03-27 00:00:00', 0),
('arnold', '120120', 'arnold', 'dodo', 'arnold500@gmail.com', '054662384', '1995-05-20', 1900521, 1, 0, '2024-03-27 00:00:00', 0),
('chen123', '120983', 'chen', 'aharon', 'chen22@gmail.com', '058664521', '2004-03-17', 2254684, 1, 0, '2024-03-27 00:00:00', 0),
('toto', '1234500', 'toto', 'alosh', 'toto22@gmail.com', '058331645', '2004-03-14', 6623123, 1, 0, '2024-03-27 00:00:00', 0),
('melo1', '98765', 'melad', 'nekola', 'meladnet@gmail.com', '0535304181', '2006-03-05', 21366845, 1, 0, '2024-03-27 00:00:00', 0),
('rami1', '1111', 'rami', 'roro', 'roro123@gmail.com', '0521111515', '2014-03-24', 33399654, 3, 2, '2024-03-27 00:00:00', 0),
('tamerk', '1234', 'tamer', 'kabha', 'tamerk11t@gmail.com', '0549105785', '2004-03-31', 212955751, 1, 0, '2024-04-04 21:44:52', 0),
('george', '12321', 'george', 'yousef', 'gorg99831@gmail.com', '0528878542', '2004-03-16', 214024333, 7, 6, '2024-03-31 00:08:47', 0),
('test', '1234567890', 'tttt', 'ttttt', 'gorgy@outlook.com', '7087896', '2024-04-17', 3333, 1, 2, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `id` int(255) NOT NULL,
  `looked` int(11) NOT NULL,
  `login_attempts` int(11) NOT NULL,
  `datetimelogin` datetime NOT NULL DEFAULT '2024-03-27 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `firstname`, `lastname`, `email`, `phone`, `birthday`, `id`, `looked`, `login_attempts`, `datetimelogin`) VALUES
('ahmed1', '234568', 'ahmed', 'noor', 'ahemt@gmail.com', '057455123', '2003-12-30', 881234, 1, 0, '2024-03-27 00:00:00'),
('roro', 'Wgj0HZfs', 'aaaaa', 'aaaa', 'Roneetewies538@gmail.com', '07087896', '2024-02-29', 970798, 1, 0, '2024-03-27 00:00:00'),
('lolo', '102050', 'lolo', 'lolo', 'lolo664@gmail.com', '046257105', '2004-03-01', 1222548, 1, 0, '2024-03-27 00:00:00'),
('arnold', '120120', 'arnold', 'dodo', 'arnold500@gmail.com', '054662384', '1995-05-20', 1900521, 1, 0, '2024-03-27 00:00:00'),
('chen123', '120983', 'chen', 'aharon', 'chen22@gmail.com', '058664521', '2004-03-17', 2254684, 1, 0, '2024-03-27 00:00:00'),
('toto', '1234500', 'toto', 'alosh', 'toto22@gmail.com', '058331645', '2004-03-14', 6623123, 1, 0, '2024-03-27 00:00:00'),
('melo1', '98765', 'melad', 'nekola', 'meladnet@gmail.com', '0535304181', '2006-03-05', 21366845, 1, 0, '2024-03-27 00:00:00'),
('rami1', '1111', 'rami', 'roro', 'roro123@gmail.com', '0521111515', '2014-03-24', 33399654, 1, 0, '2024-04-05 20:48:11'),
('tamerk', '1234', 'tamer', 'kabha', 'tamerk11t@gmail.com', '0549105785', '2004-03-31', 212955751, 1, 0, '2024-04-04 21:44:52'),
('george', '12321', 'george', 'yousef', 'gorg99831@gmail.com', '0528878542', '2004-03-16', 214024333, 1, 0, '2024-04-05 19:38:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `manager`
--
ALTER TABLE `manager`
  ADD KEY `manager_id` (`manager_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=993013;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `manager`
--
ALTER TABLE `manager`
  ADD CONSTRAINT `manager_id` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
