ALTER TABLE `comments` ADD `event_id` INT NOT NULL ,
ADD `type` VARCHAR( 20 ) NOT NULL 



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
