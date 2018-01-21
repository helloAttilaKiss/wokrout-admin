-- phpMyAdmin SQL Dump
-- version 4.0.10.20
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 21, 2018 at 11:46 AM
-- Server version: 10.1.24-MariaDB-cll-lve
-- PHP Version: 7.0.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `exercise`
--

CREATE TABLE IF NOT EXISTS `exercise` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(13) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_ts` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_ts` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `exercise`
--

INSERT INTO `exercise` (`id`, `name`, `code`, `status`, `created_ts`, `deleted`, `deleted_ts`) VALUES
(1, 'Crunch', '5a5fe64e01795', 1, '2018-01-18 01:07:06', 0, NULL),
(2, 'Air squat', '5a5fe65a93d4d', 1, '2018-01-18 01:07:06', 0, NULL),
(3, 'Windmill', '5a5fe663affcb', 1, '2018-01-18 01:07:06', 0, NULL),
(4, 'Push-up', '5a5fe66cb92f5', 1, '2018-01-18 01:07:06', 0, NULL),
(5, 'Rowing Machine', '5a5fe67c328bc', 1, '2018-01-18 01:07:06', 0, NULL),
(6, 'Walking', '5a5fe68cee4d6', 1, '2018-01-18 01:07:06', 0, NULL),
(7, 'Running', '5a5fe68351a67', 1, '2018-01-18 01:07:06', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `exercise_instances`
--

CREATE TABLE IF NOT EXISTS `exercise_instances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exercise_id` int(11) NOT NULL,
  `day_id` int(11) NOT NULL COMMENT 'optional, filled when this is part of a trainingplan (day)',
  `exercise_duration` int(11) NOT NULL DEFAULT '0' COMMENT 'duration in seconds',
  `order` int(11) NOT NULL DEFAULT '0',
  `code` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_ts` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_ts` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

--
-- Dumping data for table `exercise_instances`
--

INSERT INTO `exercise_instances` (`id`, `exercise_id`, `day_id`, `exercise_duration`, `order`, `code`, `status`, `created_ts`, `deleted`, `deleted_ts`) VALUES
(37, 1, 16, 120, 1, '5a63befcb2353', 1, '2018-01-20 23:13:16', 0, NULL),
(38, 6, 16, 1200, 2, '5a63bf21d8ad0', 1, '2018-01-20 23:13:53', 0, NULL),
(39, 7, 16, 3600, 3, '5a63bf3d00d21', 1, '2018-01-20 23:14:21', 0, NULL),
(40, 1, 17, 120, 1, '5a63bf547a302', 1, '2018-01-20 23:14:44', 0, NULL),
(41, 6, 17, 1800, 2, '5a63bf5dce65f', 1, '2018-01-20 23:14:53', 0, NULL),
(42, 7, 17, 1800, 3, '5a63bf614218d', 1, '2018-01-20 23:14:57', 0, NULL),
(43, 3, 18, 600, 1, '5a63bf8edc2e3', 1, '2018-01-20 23:15:42', 0, NULL),
(44, 5, 18, 600, 2, '5a63bf945d355', 1, '2018-01-20 23:15:48', 0, NULL),
(45, 7, 18, 600, 3, '5a63bf9932d25', 1, '2018-01-20 23:15:53', 0, NULL),
(46, 6, 18, 1800, 4, '5a63bf9eaae5a', 1, '2018-01-20 23:15:58', 0, NULL),
(47, 2, 19, 3600, 1, '5a63c02058ebc', 1, '2018-01-20 23:18:08', 0, NULL),
(48, 2, 20, 3600, 1, '5a63c04c65b72', 1, '2018-01-20 23:18:52', 0, NULL),
(49, 1, 22, 25, 3, '5a63ebad33c6d', 1, '2018-01-21 02:23:57', 0, NULL),
(50, 2, 22, 25, 2, '5a63ebb076923', 1, '2018-01-21 02:24:00', 0, NULL),
(51, 3, 22, 25, 1, '5a63ebb15dbee', 1, '2018-01-21 02:24:01', 0, NULL),
(52, 4, 22, 25, 4, '5a63ebb2348bb', 1, '2018-01-21 02:24:02', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `plan`
--

CREATE TABLE IF NOT EXISTS `plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_name` varchar(150) NOT NULL COMMENT 'contains plan name',
  `plan_description` text NOT NULL COMMENT 'contains plan description (optional)',
  `plan_difficulty` int(1) NOT NULL DEFAULT '1' COMMENT '1=beginner,2=intermediate,3=expert',
  `code` varchar(13) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_ts` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_ts` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='contains basic plan data' AUTO_INCREMENT=13 ;

--
-- Dumping data for table `plan`
--

INSERT INTO `plan` (`id`, `plan_name`, `plan_description`, `plan_difficulty`, `code`, `status`, `created_ts`, `deleted`, `deleted_ts`) VALUES
(9, 'Men cardio', 'Cardio workout plan for men. Give it a try!', 3, '5a63ba55e8032', 1, '2018-01-20 22:53:25', 1, '2018-01-21 02:02:17'),
(10, 'Women cardio', 'This is a cardio plan for women.', 2, '5a63ba684e51a', 1, '2018-01-20 22:53:44', 1, '2018-01-21 02:24:30'),
(11, 'Leg workout plann', 'This is a nice workout plan for our fellow skinny legged users.', 3, '5a63ba7f383e0', 1, '2018-01-20 22:54:07', 0, NULL),
(12, 'Chest workout plan', 'This is a nice workut plan for chest.', 2, '5a63baaad899f', 1, '2018-01-20 22:54:50', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `plan_days`
--

CREATE TABLE IF NOT EXISTS `plan_days` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_id` int(11) NOT NULL COMMENT 'id from plan table',
  `day_name` varchar(100) NOT NULL DEFAULT '' COMMENT 'name for this day, optional',
  `order` int(11) NOT NULL DEFAULT '0',
  `code` varchar(13) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_ts` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_ts` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `plan_days`
--

INSERT INTO `plan_days` (`id`, `plan_id`, `day_name`, `order`, `code`, `status`, `created_ts`, `deleted`, `deleted_ts`) VALUES
(16, 9, 'Indoor running', 1, '5a63bed99f2a8', 1, '2018-01-20 23:12:41', 0, NULL),
(17, 9, 'Outdoor running', 3, '5a63bf4de27da', 1, '2018-01-20 23:14:37', 0, NULL),
(18, 9, 'Indoor cardio', 2, '5a63bf716f2e2', 1, '2018-01-20 23:15:13', 0, NULL),
(19, 11, 'squat day 1', 1, '5a63c0192fda3', 1, '2018-01-20 23:18:01', 0, NULL),
(20, 11, 'squat day 2', 2, '5a63c048ae089', 1, '2018-01-20 23:18:48', 0, NULL),
(21, 11, 'squat day 3', 3, '5a63c05419a04', 1, '2018-01-20 23:19:00', 0, NULL),
(22, 10, 'Teszta', 1, '5a63eb890e757', 1, '2018-01-21 02:23:21', 0, NULL),
(23, 10, 'T szt', 2, '5a63eb97e9b05', 1, '2018-01-21 02:23:35', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `code` varchar(13) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_ts` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_ts` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `email`, `code`, `status`, `created_ts`, `deleted`, `deleted_ts`) VALUES
(7, 'John', 'Doe', 'john@example.com', '5a63bae965dc4', 1, '2018-01-20 22:55:53', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_plans`
--

CREATE TABLE IF NOT EXISTS `user_plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `code` varchar(13) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_ts` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_ts` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

--
-- Dumping data for table `user_plans`
--

INSERT INTO `user_plans` (`id`, `user_id`, `plan_id`, `code`, `status`, `created_ts`, `deleted`, `deleted_ts`) VALUES
(12, 7, 9, '5a63d69521243', 1, '2018-01-21 00:53:57', 1, 127),
(13, 7, 9, '5a63d699722ea', 1, '2018-01-21 00:54:01', 1, 127),
(14, 7, 9, '5a63d81749515', 1, '2018-01-21 01:00:23', 1, 127),
(15, 7, 10, '5a63d827bada9', 1, '2018-01-21 01:00:39', 1, 127),
(16, 7, 11, '5a63d87494d70', 1, '2018-01-21 01:01:56', 1, 127),
(17, 7, 12, '5a63d8e61ef4d', 1, '2018-01-21 01:03:50', 1, 127),
(18, 7, 9, '5a63d91ddab99', 1, '2018-01-21 01:04:45', 1, 127),
(19, 7, 9, '5a63d933939ff', 1, '2018-01-21 01:05:07', 1, 127),
(20, 7, 10, '5a63d9b954442', 1, '2018-01-21 01:07:21', 1, 127),
(21, 7, 11, '5a63da8a3dc40', 1, '2018-01-21 01:10:50', 1, 127),
(22, 7, 11, '5a63db2690852', 1, '2018-01-21 01:13:26', 1, 127),
(23, 7, 9, '5a63dc4f131cd', 1, '2018-01-21 01:18:23', 1, 127),
(24, 7, 10, '5a63dd291e126', 1, '2018-01-21 01:22:01', 1, 127),
(25, 7, 12, '5a63dd68e131b', 1, '2018-01-21 01:23:04', 1, 127),
(26, 7, 9, '5a63ddb653e0b', 1, '2018-01-21 01:24:22', 1, 127),
(27, 7, 10, '5a63df202a9fc', 1, '2018-01-21 01:30:24', 1, 127),
(28, 7, 11, '5a63df2b45db8', 1, '2018-01-21 01:30:35', 1, 127),
(29, 7, 12, '5a63df3441ce0', 1, '2018-01-21 01:30:44', 1, 127),
(30, 7, 9, '5a63dffb04f80', 1, '2018-01-21 01:34:03', 1, 127),
(31, 7, 12, '5a63dffd6e8cb', 1, '2018-01-21 01:34:05', 1, 127),
(32, 7, 9, '5a63e02f937f5', 1, '2018-01-21 01:34:55', 1, 127),
(33, 7, 9, '5a63e23b3ca7e', 1, '2018-01-21 01:43:39', 1, 127),
(34, 7, 9, '5a63e2e25631c', 1, '2018-01-21 01:46:26', 1, 127),
(35, 7, 9, '5a63e314b4e1a', 1, '2018-01-21 01:47:16', 1, 127),
(36, 7, 9, '5a63e33f5fd1f', 1, '2018-01-21 01:47:59', 1, 127),
(37, 7, 9, '5a63e38d838da', 1, '2018-01-21 01:49:17', 1, 127),
(38, 7, 11, '5a63e3a36d2b8', 1, '2018-01-21 01:49:39', 1, 127),
(39, 7, 9, '5a63e575de15c', 1, '2018-01-21 01:57:25', 0, NULL),
(40, 7, 10, '5a63e7010458d', 1, '2018-01-21 02:04:01', 1, 127),
(41, 7, 10, '5a63e76ba898d', 1, '2018-01-21 02:05:47', 0, NULL),
(42, 7, 12, '5a63eb46dda92', 1, '2018-01-21 02:22:14', 1, 127),
(43, 7, 11, '5a646533a6560', 1, '2018-01-21 11:02:27', 0, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
