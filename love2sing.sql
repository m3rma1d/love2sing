-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2017 at 01:09 PM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `love2sing`
--

-- --------------------------------------------------------

--
-- Table structure for table `componist`
--

CREATE TABLE `componist` (
  `componistId` int(20) NOT NULL,
  `componistName` varchar(100) NOT NULL,
  `componistYearOfBirth` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facemap`
--

CREATE TABLE `facemap` (
  `facemapId` int(20) NOT NULL,
  `facemapName` varchar(100) NOT NULL,
  `facemapUrl` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `guestbook`
--

CREATE TABLE `guestbook` (
  `guestbookId` int(20) NOT NULL,
  `guestbookTitle` varchar(40) NOT NULL,
  `guestbookMessage` varchar(600) NOT NULL,
  `guestbookDate` date NOT NULL,
  `guestbookApproved` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `music`
--

CREATE TABLE `music` (
  `musicId` int(20) NOT NULL,
  `musicName` varchar(60) NOT NULL,
  `componistId` int(20) NOT NULL,
  `musicPitch` varchar(30) NOT NULL,
  `musicPdf` varchar(100) DEFAULT NULL,
  `musicMp3` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `photoalbum`
--

CREATE TABLE `photoalbum` (
  `photoalbumId` int(11) NOT NULL,
  `photoalbumDescription` varchar(100) NOT NULL,
  `photoalbumUrl` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userId` int(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `userEmail` varchar(40) NOT NULL,
  `userPassword` varchar(40) NOT NULL,
  `userRights` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `username`, `userEmail`, `userPassword`, `userRights`) VALUES
(1, 'test', 'test@test.com', 'test123', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `componist`
--
ALTER TABLE `componist`
  ADD PRIMARY KEY (`componistId`);

--
-- Indexes for table `facemap`
--
ALTER TABLE `facemap`
  ADD PRIMARY KEY (`facemapId`);

--
-- Indexes for table `guestbook`
--
ALTER TABLE `guestbook`
  ADD PRIMARY KEY (`guestbookId`);

--
-- Indexes for table `music`
--
ALTER TABLE `music`
  ADD PRIMARY KEY (`musicId`),
  ADD KEY `componistId` (`componistId`);

--
-- Indexes for table `photoalbum`
--
ALTER TABLE `photoalbum`
  ADD PRIMARY KEY (`photoalbumId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `componist`
--
ALTER TABLE `componist`
  MODIFY `componistId` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `facemap`
--
ALTER TABLE `facemap`
  MODIFY `facemapId` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `guestbook`
--
ALTER TABLE `guestbook`
  MODIFY `guestbookId` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `music`
--
ALTER TABLE `music`
  MODIFY `musicId` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `photoalbum`
--
ALTER TABLE `photoalbum`
  MODIFY `photoalbumId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `music`
--
ALTER TABLE `music`
  ADD CONSTRAINT `music_ibfk_1` FOREIGN KEY (`componistId`) REFERENCES `componist` (`componistId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
