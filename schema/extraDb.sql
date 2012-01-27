ALTER TABLE `comments` ADD `event_id` INT NOT NULL ,
ADD `type` VARCHAR( 20 ) NOT NULL 


DROP TABLE IF EXISTS users;

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `name` varchar(25) DEFAULT NULL,
  `twitter_id` int(11) ,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS events;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


INSERT INTO `events` (`event_id`, `user_id`, `title`, `summary`, `logo`,  `location`, `href`, `start_date`, `end_date`, `is_active`, `total_attending`, `create_date`) VALUES
(1, 1, 'PHPBenelux Conference 2012', 'The conference and tutorials will take place at the Best Western Hotel Ter Elst in Antwerp (Belgium). Friday morning January 27th we have a set of tutorials. The conference is spread over 2 days: Friday afternoon (after the tutorials) and Saturday. Tutorials as well as the conference itself are spread over several parallel tracks.\n\nOn Friday evening, we''re having the conference social. This will include drinks and bowling as we managed to reserve the entire bowling alley.', 'http://joind.in/inc/img/event_icons/phpbnl2012-small.png',  'Best Western Ter Elst', 'http://conference.phpbenelux.eu/', '2012-01-27', '2012-01-29', 1, 0, '2012-01-20 10:11:12'),
(2, 1, 'ZendCon 2011', 'The 7th Annual Zend PHP Conference (ZendCon) will take place October 17-20, 2011, in Santa Clara, California. ZendCon is the largest gathering of the PHP Community and brings together PHP developers and IT managers from around the world to discuss PHP best practices and explore new technologies.\r\n\r\nAt ZendCon, youâ€™ll learn from a variety of technical sessions and in-depth tutorials. International industry experts, renowned thought-leaders and experienced PHP practitioners, will discuss PHP best practices and explore future technological developments. ZendCon 2011 will focus on ways that PHP fits into major trends in the IT world. The primary conference themes are Cloud Computing, Mobile and User Experience, and Enterprise and Professional PHP.\r\n\r\nAn Exhibit Hall featuring industry leaders offers a space to meet innovative companies and unique networking opportunities are at hand.', NULL, 'Santa Clara Convention Center', 'http://joind.in/inc/img/event_icons/zendcon-icon.gif', '0000-00-00', '0000-00-00', 1, 0, '2012-01-24 10:13:46'),
(3, 1, 'PHPCon Poland 2011', 'The second edition of Polish weekend meeting for PHP programmers and enthusiasts. Attedees spend the time on lectures, workshops, lightning talks and after hours discussions to late night. There will be also a time for a little sightseeing - hotel is located in national park neighborhood.\r\n\r\nThe Official PHPConPL''s webpage has been just started. Talk proposals can be entered directly at phpcon.pl.', 'http://joind.in/inc/img/event_icons/logo-joind-in.png',  'Przedwiosnie Hotel', 'http://www.phpcon.pl/2011/en/', '0000-00-00', '0000-00-00', 1, 0, '2012-01-24 10:18:35');

--

--
-- Table structure for table `event_category`
--

CREATE TABLE IF NOT EXISTS `event_category` (
  `event_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`event_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `talks`;
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `talks`
--

INSERT INTO `talks` (`talk_id`, `event_id`, `title`, `summary`, `speaker`, `slide_link`) VALUES
(1, 2, 'Profiling PHP Applications', 'The web is full of advice focussed on improving performance. Before you can optimise however, you need to find out if your code is actually slow; then you need to understand the code; and then you need to find out what you can optimise.\n\nThis talk introduces various tools and concepts to optimise the optimisation of your PHP applications.', 'Derick Rethans', NULL);



