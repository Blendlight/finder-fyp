-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2018 at 12:09 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `finder`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `user_account_user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `body` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'user to whom feedback is given',
  `item_id` int(11) NOT NULL COMMENT 'item on which feedback given',
  `feedback` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `user_id`, `item_id`, `feedback`) VALUES
(1, 2, 12, 'Fahad zaman help me to find my lost watch thanks fahadi.');

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `image_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`image_id`, `item_id`, `name`) VALUES
(26, 12, '5bd94ec60b3b20052.jpg'),
(27, 12, '5bd94ec60b3bf0935.jpg'),
(28, 13, '5bd94f170ff900236.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `user_account_user_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `location` text,
  `item_status` enum('lost','find') DEFAULT NULL,
  `lost_date` timestamp NULL DEFAULT NULL,
  `post_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `found_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `user_account_user_id`, `name`, `description`, `location`, `item_status`, `lost_date`, `post_date`, `found_date`) VALUES
(12, 1, 'samsung x86 z32', 'hello world dagha ao agha', 'Peshawar, saddar cantt', 'find', '2018-10-30 07:00:00', '2018-10-31 06:42:14', NULL),
(13, 1, 'metro ka mandola', 'fahad ka kandola', 'nokhar, bando kale', 'find', '2018-01-01 08:00:00', '2018-10-31 06:43:35', NULL),
(14, 2, 'apple', 'mango', 'peshawar, saddar cantt', 'lost', '2018-11-17 08:00:00', '2018-11-17 01:26:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'sender id',
  `user_id2` int(11) NOT NULL COMMENT 'reciver id',
  `message` text NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`message_id`, `user_id`, `user_id2`, `message`, `date`) VALUES
(1, 1, 2, 'adf', '2018-11-14 23:55:57'),
(2, 1, 2, 'How are you moin', '2018-11-14 23:56:08'),
(3, 1, 2, 'this is fun', '2018-11-14 23:56:12'),
(4, 1, 2, 'this is funright', '2018-11-14 23:56:14'),
(5, 2, 1, 'nice', '2018-11-14 23:55:57'),
(6, 2, 1, 'nice', '2018-11-15 00:07:58'),
(7, 2, 1, 'Hello', '2018-11-15 00:09:02'),
(8, 2, 1, 'How are you', '2018-11-15 00:09:09'),
(9, 2, 1, 'hello', '2018-11-15 00:09:39'),
(10, 2, 1, 'hwo are you', '2018-11-15 00:09:43'),
(11, 1, 2, 'i am good', '2018-11-15 00:09:47'),
(12, 1, 2, 'thanks', '2018-11-15 00:09:50'),
(13, 2, 1, 'ok', '2018-11-15 00:14:23'),
(14, 2, 1, 'see', '2018-11-15 00:14:28'),
(15, 2, 1, 'thanks', '2018-11-15 00:20:07'),
(16, 4, 1, 'af', '2018-11-15 00:21:26'),
(17, 2, 1, 'sdf', '2018-11-15 00:21:52'),
(18, 1, 3, 'he', '2018-11-15 00:22:26'),
(19, 2, 1, 'adf', '2018-11-15 00:24:01'),
(20, 2, 1, 'asdf', '2018-11-15 00:24:05'),
(21, 1, 2, 'boss', '2018-11-15 00:24:24'),
(22, 1, 5, 'asjklfjlk', '2018-11-14 12:55:58'),
(23, 1, 2, 'hello', '2018-11-15 13:15:18'),
(24, 2, 1, 'Hi', '2018-11-15 13:15:35'),
(25, 2, 1, 'how are you', '2018-11-15 13:15:38'),
(26, 2, 1, 'asdfjka', '2018-11-15 13:15:42'),
(27, 1, 2, 'Hello', '2018-11-15 13:19:21');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `name`) VALUES
(1, 'admin'),
(2, 'authenticated'),
(3, 'anonymouse');

-- --------------------------------------------------------

--
-- Table structure for table `user_account`
--

CREATE TABLE `user_account` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `address` text,
  `user_password` varchar(255) DEFAULT NULL,
  `user_status` tinyint(1) DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `cnic` varchar(20) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_account`
--

INSERT INTO `user_account` (`user_id`, `role_id`, `user_name`, `name`, `email`, `phone`, `address`, `user_password`, `user_status`, `gender`, `cnic`, `profile_image`) VALUES
(1, 1, 'admin', 'Moin khan', 'moin@gmail.com', '+92-32849234', 'Arbab road saddar peshawr', 'admin', 1, 'Male', '177482830288482', NULL),
(2, 2, 'fahad', 'Fahad zaman', 'fahad@gmail.com', '+92-383493', 'xyz address d', 'admin', 0, 'Male', '32482948', '5bedd30c91163-pika.png'),
(3, 2, 'adamkhan', 'adam khan charsi', 'khan@charsi.com', '03828482', 'ajdsklfjasldkfj', 'admin', 0, 'Male', '2849283', NULL),
(4, 2, 'fahad khan', 'asdklfj', 'laskdjf@gmail.com', '23948', 'kasjdaflj asdjf l', 'admin', 0, 'Male', '2390832', NULL),
(5, 2, 'apple', 'safk', 'aklsdjf@gmail.com', '2389', '8as9d;kl', 'admin', 0, 'Male', '2893984', NULL),
(6, 2, 'admasdfkj', 'askldfj', 'askjdf@gmail.com', '23989', 'jas;dfkjlkasjflk', 'ADMIN', 0, 'Male', '23898932', '5bedd30c91163-pika.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `comments_FKIndex1` (`item_id`),
  ADD KEY `comments_FKIndex2` (`user_account_user_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `image_FKIndex1` (`item_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `item_FKIndex1` (`user_account_user_id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `user_account`
--
ALTER TABLE `user_account`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_account_FKIndex1` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `user_account`
--
ALTER TABLE `user_account`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
