-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 27, 2012 at 02:06 PM
-- Server version: 5.1.53
-- PHP Version: 5.3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tech_adda`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendees`
--

CREATE TABLE IF NOT EXISTS `attendees` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`event_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attendees`
--

INSERT INTO `attendees` (`event_id`, `user_id`) VALUES
(1, 2),
(1, 3),
(1, 5),
(2, 2),
(2, 3),
(3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `title`) VALUES
(1, 'PHP'),
(2, 'Ruby'),
(3, '.NET');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `talk_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `body` text,
  `rating` tinyint(1) DEFAULT NULL,
  `is_private` tinyint(1) DEFAULT '0',
  `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `event_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `talk_id`, `user_id`, `body`, `rating`, `is_private`, `create_date`, `event_id`, `type`) VALUES
(1, 1, 1, 'Excellent session!', NULL, 0, '2012-01-23 14:01:53', 0, ''),
(2, 1, 1, 'It did really open my eyes; as web developers we very often ignore the actual protocol that we''re sending stuff through. Understanding the underlying mechanics of HTTP is indeed really important and even though I was half asleep the talk did indeed have many valid points and was nicely presented.', NULL, 0, '2012-01-23 14:11:34', 0, ''),
(4, 1, 1, 'Great talk! It''s very nice to listen the basics explained by a master. Specially some basics I even knew about, thought.', NULL, 0, '2012-01-23 14:36:27', 0, ''),
(5, 1, 1, 'Although much of the talk was what you can see in Symfony2 caching documentation, but anyway it is a pleasure to hear Fabien live. Big todo to all of us, read the HTTP Specification.', NULL, 0, '2012-01-23 14:39:31', 0, ''),
(11, 1, 3, 'dfafaf', NULL, 0, '0000-00-00 00:00:00', 0, ''),
(12, 1, 3, 'sdfsdf', NULL, 0, '2012-01-27 06:27:51', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `summary` text CHARACTER SET latin1,
  `logo` varchar(200) DEFAULT NULL,
  `location` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `href` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `total_attending` int(11) DEFAULT '0',
  `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `user_id`, `title`, `summary`, `logo`, `location`, `href`, `start_date`, `end_date`, `is_active`, `total_attending`, `create_date`) VALUES
(1, 1, 'PHPBenelux Conference 2012', 'The conference and tutorials will take place at the Best Western Hotel Ter Elst in Antwerp (Belgium). Friday morning January 27th we have a set of tutorials. The conference is spread over 2 days: Friday afternoon (after the tutorials) and Saturday. Tutorials as well as the conference itself are spread over several parallel tracks.\n\nOn Friday evening, we''re having the conference social. This will include drinks and bowling as we managed to reserve the entire bowling alley.', 'http://joind.in/inc/img/event_icons/phpbnl2012-small.png', 'Best Western Ter Elst', 'http://conference.phpbenelux.eu/', '2012-01-27', '2012-01-29', 1, 0, '2012-01-20 04:11:12'),
(2, 1, 'ZendCon 2011', 'The 7th Annual Zend PHP Conference (ZendCon) will take place October 17-20, 2011, in Santa Clara, California. ZendCon is the largest gathering of the PHP Community and brings together PHP developers and IT managers from around the world to discuss PHP best practices and explore new technologies.\r\n\r\nAt ZendCon, youâ€™ll learn from a variety of technical sessions and in-depth tutorials. International industry experts, renowned thought-leaders and experienced PHP practitioners, will discuss PHP best practices and explore future technological developments. ZendCon 2011 will focus on ways that PHP fits into major trends in the IT world. The primary conference themes are Cloud Computing, Mobile and User Experience, and Enterprise and Professional PHP.\r\n\r\nAn Exhibit Hall featuring industry leaders offers a space to meet innovative companies and unique networking opportunities are at hand.', NULL, 'Santa Clara Convention Center', 'http://joind.in/inc/img/event_icons/zendcon-icon.gif', '0000-00-00', '0000-00-00', 1, 0, '2012-01-24 04:13:46'),
(3, 1, 'PHPCon Poland 2011', 'The second edition of Polish weekend meeting for PHP programmers and enthusiasts. Attedees spend the time on lectures, workshops, lightning talks and after hours discussions to late night. There will be also a time for a little sightseeing - hotel is located in national park neighborhood.\r\n\r\nThe Official PHPConPL''s webpage has been just started. Talk proposals can be entered directly at phpcon.pl.', 'http://joind.in/inc/img/event_icons/logo-joind-in.png', 'Przedwiosnie Hotel', 'http://www.phpcon.pl/2011/en/', '0000-00-00', '0000-00-00', 1, 0, '2012-01-24 04:18:35'),
(4, 8, 'Testing', 'sdfasdf', NULL, 'sdafs', 'http://www.test.com', '0000-00-00', '0000-00-00', 1, 0, '2012-01-27 13:56:20'),
(5, 8, 'asdf', 'fasdfasf', NULL, 'sadfas', 'http://www.test.com', '0000-00-00', '0000-00-00', 1, 0, '2012-01-27 13:57:59'),
(6, 8, 'adfas', 'asdfasdfasdf', NULL, 'adsfasd', 'http://www.test.com', '0000-00-00', '0000-00-00', 1, 0, '2012-01-27 14:00:28'),
(7, 8, 'adfas', 'asdfasdfasdf', NULL, 'adsfasd', 'http://www.test.com', '0000-00-00', '0000-00-00', 1, 0, '2012-01-27 14:01:45'),
(8, 8, 'adfas', 'asdfasdfasdf', NULL, 'adsfasd', 'http://www.test.com', '0000-00-00', '0000-00-00', 1, 0, '2012-01-27 14:03:06');

-- --------------------------------------------------------

--
-- Table structure for table `event_category`
--

CREATE TABLE IF NOT EXISTS `event_category` (
  `event_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`event_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `event_category`
--

INSERT INTO `event_category` (`event_id`, `category_id`) VALUES
(6, 1),
(6, 3),
(7, 1),
(8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `talks`
--

CREATE TABLE IF NOT EXISTS `talks` (
  `talk_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `title` varchar(200) NOT NULL DEFAULT '',
  `summary` text,
  `speaker` varchar(50) NOT NULL DEFAULT '',
  `slide_link` varchar(200) DEFAULT NULL,
  `total_comments` int(11) DEFAULT '0',
  `tags` varchar(200) DEFAULT '',
  PRIMARY KEY (`talk_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `talks`
--

INSERT INTO `talks` (`talk_id`, `event_id`, `title`, `summary`, `speaker`, `slide_link`, `total_comments`, `tags`) VALUES
(1, 2, 'Profiling PHP Applications', 'The web is full of advice focussed on improving performance. Before you can optimise however, you need to find out if your code is actually slow; then you need to understand the code; and then you need to find out what you can optimise.\n\nThis talk introduces various tools and concepts to optimise the optimisation of your PHP applications.', 'Derick Rethans', NULL, 0, ''),
(5, 2, 'sdfasdf', 'asdfasdf', 'sdfasdf', 'http://www.tset.com', 0, ''),
(6, 0, 'asdfa', 'asdfsaf', 'adfasdfs', 'http://www.tset.com', 0, ''),
(7, 0, 'asdfa', 'asdfasd', 'sdfsd', 'http://www.tset.com', 0, ''),
(8, 2, 'asdfa', 'adsfa', 'asdfas', 'http://www.tset.com', 0, ''),
(9, 2, 'asdfa', 'asdf', 'asdfa', 'http://www.tset.com', 0, ''),
(10, 2, 'asdfa', 'asdf', 'asdfa', 'http://www.tset.com', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `name` varchar(25) DEFAULT NULL,
  `twitter_id` int(11) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `name`, `twitter_id`, `create_date`) VALUES
(1, 'phpfour@gmail.com', '', NULL, '2012-01-23 01:36:06'),
(5, '', 'Ibrahim', 291885642, '2012-01-27 13:25:48'),
(8, 'ibrahim12@gmail.com', NULL, NULL, '2012-01-27 13:38:28'),
(9, 'sharemanger@gmail.com', 'Ibrahim', NULL, '2012-01-27 13:48:43');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
