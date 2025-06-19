-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 25, 2023 at 08:07 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
CREATE DATABASE IF NOT EXISTS `project` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `project`;

-- --------------------------------------------------------

--
-- Table structure for table `choices`
--

CREATE TABLE `choices` (
  `chid` int(11) NOT NULL,
  `qid` int(11) NOT NULL,
  `choice` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `choices`
--

INSERT INTO `choices` (`chid`, `qid`, `choice`) VALUES
(83, 32, 'football'),
(84, 32, 'basketball'),
(85, 32, 'baseball'),
(86, 33, 'the boy and the heron'),
(87, 33, 'spirited away'),
(88, 33, 'my neighbor totoro'),
(89, 33, 'howl\'s moving castle'),
(90, 34, 'Harry Potter'),
(91, 34, 'Percy Jackson & the Olympians'),
(92, 34, 'Hunger Games'),
(102, 37, 'red'),
(103, 37, 'blue'),
(104, 37, 'green'),
(105, 38, 'stardew valley'),
(106, 38, 'harvest moon'),
(107, 38, 'animal crossing'),
(108, 39, 'silent'),
(109, 39, 'clicky'),
(110, 40, 'Google chrome'),
(111, 40, 'Microsoft edge'),
(112, 40, 'Firefox'),
(113, 40, 'Brave'),
(114, 41, 'Leonardo da Vinci'),
(115, 41, 'Pablo Picasso'),
(116, 41, 'Vincent van Gogh'),
(117, 42, 'Mario'),
(118, 42, 'Luigi');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `qid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `question` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `dueDate` text DEFAULT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`qid`, `uid`, `question`, `category`, `dueDate`, `status`) VALUES
(32, 6, 'what is your favourite sport?', 'SPORTS', '', 'inactive'),
(33, 6, 'what is your favorite ghibli movie?', 'GENERAL', '2023-12-31 10:06:00', 'active'),
(34, 7, 'Best book series?', 'BOOKS', '2023-12-25 22:33:00', 'active'),
(37, 6, 'what is your favorite color from the choices?', 'GENERAL', '2024-01-01 19:32:00', 'active'),
(38, 6, 'what is your favourite farming game from the below?', 'VIDEO GAMES', '', 'active'),
(39, 7, 'silent or clicky keyboard?', 'TECHNOLOGY', '2024-01-31 19:49:00', 'active'),
(40, 7, 'the best browser?', 'TECHNOLOGY', '', 'active'),
(41, 8, 'favourtie artist?', 'ART', '2023-12-26 07:54:00', 'active'),
(42, 8, 'Mario or Luigi?', 'VIDEO GAMES', '', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `uid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `name`, `email`, `password`) VALUES
(6, 'zainab', 'zainab@gmail.com', '$2y$10$.mO1TongbM5DkSox1W0oIezrg2Rfi1DNiglkfBhJQ38NS3BM9tEdW'),
(7, 'zahraa', 'zahraa@hotmail.com', '$2y$10$dpme5MdhKp4yOiyuNb/Ir.oUZtAICzM6hwUtVVoqJu645v4WQ03wS'),
(8, 'mario', 'super.mario@gmail.com', '$2y$10$5IH48gtAoXimctOxAxn0Ge3CuRDte8ji6MnmLyodberyCpbQ9Kiuu');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `uid` int(11) NOT NULL,
  `qid` int(11) NOT NULL,
  `chid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`uid`, `qid`, `chid`) VALUES
(6, 32, 84),
(7, 32, 83),
(7, 34, 90),
(6, 34, 90),
(6, 38, 105),
(7, 33, 89),
(7, 38, 107),
(8, 40, 110),
(8, 39, 109),
(8, 32, 83);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `choices`
--
ALTER TABLE `choices`
  ADD PRIMARY KEY (`chid`),
  ADD KEY `qid` (`qid`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`qid`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `unique` (`email`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD KEY `chid` (`chid`),
  ADD KEY `qid` (`qid`),
  ADD KEY `uid` (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `choices`
--
ALTER TABLE `choices`
  MODIFY `chid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `qid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `choices`
--
ALTER TABLE `choices`
  ADD CONSTRAINT `choices_ibfk_1` FOREIGN KEY (`qid`) REFERENCES `questions` (`qid`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`);

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`chid`) REFERENCES `choices` (`chid`),
  ADD CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`qid`) REFERENCES `questions` (`qid`),
  ADD CONSTRAINT `votes_ibfk_3` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
