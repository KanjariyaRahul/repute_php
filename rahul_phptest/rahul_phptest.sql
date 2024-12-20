-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2024 at 09:24 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rahul_phptest`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `fName` varchar(20) NOT NULL,
  `lName` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `hobbies` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(20) NOT NULL,
  `state` varchar(20) NOT NULL,
  `zipcode` int(11) NOT NULL,
  `profilepic` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `fName`, `lName`, `email`, `dob`, `gender`, `hobbies`, `address`, `city`, `state`, `zipcode`, `profilepic`) VALUES
(16, 'jay', 'nakum', 'jay@gmail.com', '1970-01-01', 'Male', 'Reading,Writing', 'rajkot', 'Rajkot', 'Gujarat', 361007, '517397065ea769cde63a25f39f1fe615.jpg'),
(21, 'rahul', 'kanjariya', 'rahulkanjariya9265@gmail.com', '2003-02-22', 'Male', 'Adventure,Playing Games', 'jamnagar', 'Jamnagar', 'Gujarat', 361007, 'ft.png'),
(22, 'hjkhjkjhk', 'hjkhjk', 'ghjghjghj@dfg.com', '1972-02-02', 'Male', 'Reading,Writing,Adventure', 'ghjghjghj', 'Jamnagar', 'UP', 777788, '9ba25796112cad616be27e473ae1e149.jpg'),
(23, 'hjkhjkjhk', 'hjkhjk', 'ghjghjghj@dfg.com', '1972-02-02', 'Male', 'Reading,Writing,Adventure', 'ghjghjghj', 'Jamnagar', 'UP', 777788, 'Thumbs - Copy.jpg'),
(24, 'hjkhjkjhk', 'hjkhjk', 'ghjghjghj@dfg.com', '1972-02-02', 'Male', 'Reading,Writing,Adventure', 'ghjghjghj', 'Jamnagar', 'UP', 777788, ''),
(25, 'hjkhjkjhk', 'hjkhjk', 'ghjghjghj@dfg.com', '1972-02-02', 'Male', 'Reading,Writing,Adventure', 'ghjghjghj', 'Jamnagar', 'UP', 777788, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
