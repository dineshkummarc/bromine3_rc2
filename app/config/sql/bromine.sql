-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Vært: localhost
-- Genereringstid: 24. 07 2010 kl. 19:28:05
-- Serverversion: 5.1.41
-- PHP-version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dev`
--

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `activities`
--

CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `browsers`
--

CREATE TABLE IF NOT EXISTS `browsers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `path` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=99 ;

--
-- Data dump for tabellen `browsers`
--

INSERT INTO `browsers` (`id`, `name`, `path`) VALUES
(1, 'Internet Explorer 7', '*iexplore'),
(2, 'Internet Explorer 6', '*iexplore'),
(3, 'Firefox 2', '*firefox'),
(6, 'Safari', '*safari'),
(7, 'Firefox 3', '*firefox'),
(8, 'Opera', '*opera'),
(23, 'Internet Explorer 8', '*iexplore');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `browsers_nodes`
--

CREATE TABLE IF NOT EXISTS `browsers_nodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `browser_id` int(11) NOT NULL,
  `node_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `browser_id` (`browser_id`),
  KEY `node_id` (`node_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=375 ;

--
-- Data dump for tabellen `browsers_nodes`
--

INSERT INTO `browsers_nodes` (`id`, `browser_id`, `node_id`) VALUES
(374, 7, 88);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `combinations`
--

CREATE TABLE IF NOT EXISTS `combinations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `browser_id` int(11) NOT NULL,
  `operatingsystem_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `browser_id` (`browser_id`),
  KEY `operatingsystem_id` (`operatingsystem_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=896 ;

--
-- Data dump for tabellen `combinations`
--

INSERT INTO `combinations` (`id`, `browser_id`, `operatingsystem_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(8, 2, 1),
(9, 2, 2),
(10, 2, 3),
(11, 2, 4),
(12, 2, 5),
(13, 2, 6),
(15, 3, 1),
(16, 3, 2),
(17, 3, 3),
(18, 3, 4),
(19, 3, 5),
(20, 3, 6),
(22, 4, 1),
(23, 4, 2),
(24, 4, 3),
(25, 4, 4),
(26, 4, 5),
(27, 4, 6),
(29, 5, 1),
(30, 5, 2),
(31, 5, 3),
(32, 5, 4),
(33, 5, 5),
(34, 5, 6),
(36, 6, 1),
(37, 6, 2),
(38, 6, 3),
(39, 6, 4),
(40, 6, 5),
(41, 6, 6),
(43, 7, 1),
(44, 7, 2),
(45, 7, 3),
(46, 7, 4),
(47, 7, 5),
(48, 7, 6),
(50, 8, 1),
(51, 8, 2),
(52, 8, 3),
(53, 8, 4),
(54, 8, 5),
(55, 8, 6),
(92, 23, 1),
(93, 23, 2),
(94, 23, 3),
(95, 23, 4),
(96, 23, 5),
(97, 23, 6),
(99, 1, 18),
(100, 2, 18),
(101, 3, 18),
(102, 6, 18),
(103, 7, 18),
(104, 8, 18),
(105, 23, 18);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `combinations_requirements`
--

CREATE TABLE IF NOT EXISTS `combinations_requirements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `requirement_id` int(11) NOT NULL,
  `combination_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `requirement_id` (`requirement_id`),
  KEY `combination_id` (`combination_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3036 ;

--
-- Data dump for tabellen `combinations_requirements`
--

INSERT INTO `combinations_requirements` (`id`, `requirement_id`, `combination_id`) VALUES
(1859, 357, 43),
(1816, 355, 43),
(1806, 354, 43),
(1793, 353, 43),
(1643, 342, 46),
(1642, 342, 53),
(1640, 342, 43),
(1639, 342, 1),
(1638, 342, 15),
(1648, 334, 15),
(1647, 334, 1),
(1637, 342, 39),
(1781, 352, 43),
(1768, 351, 43),
(1876, 345, 1),
(1650, 344, 1),
(1663, 343, 43),
(1826, 356, 43),
(1751, 359, 43),
(1845, 358, 43),
(1888, 369, 43),
(1730, 361, 43),
(1713, 362, 43),
(1700, 363, 43),
(1690, 364, 43),
(1893, 365, 1),
(1892, 365, 43),
(1878, 366, 43),
(1889, 368, 43),
(3034, 270, 43),
(3033, 268, 43),
(3035, 269, 43),
(3032, 271, 43);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `commands`
--

CREATE TABLE IF NOT EXISTS `commands` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(255) DEFAULT NULL,
  `action` longtext,
  `var1` longtext,
  `var2` longtext,
  `test_id` int(10) NOT NULL DEFAULT '0',
  `comment` text,
  PRIMARY KEY (`id`),
  KEY `test_id` (`test_id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Data dump for tabellen `commands`
--


-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `configs`
--

CREATE TABLE IF NOT EXISTS `configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

--
-- Data dump for tabellen `configs`
--

INSERT INTO `configs` (`id`, `name`, `value`) VALUES
(1, 'registered', '0'),
(4, 'echelon', '0'),
(8, 'email_enabled', '0'),
(7, 'version', '1121'),
(9, 'email_host', 'ssl://smtp.gmail.com'),
(10, 'email_port', ''),
(11, 'email_username', ''),
(12, 'email_password', ''),
(16, 'Socket timeout', '0'),
(19, 'sauce_enabled', '0'),
(22, 'Max input time', '0'),
(21, 'sauce_nodepath', '67.23.20.87:4444'),
(23, 'Magic Quotes', '0'),
(24, 'Max execution time', '0'),
(25, 'Argument separator', '0'),
(26, 'Permissions', '0'),
(27, 'Selfcontact', '0'),
(28, 'Scheduler', '0'),
(29, 'Java', '0'),
(31, 'PHP', '0');
-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `echelons`
--

CREATE TABLE IF NOT EXISTS `echelons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `time` int(11) NOT NULL,
  `ip` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Data dump for tabellen `echelons`
--


-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

--
-- Data dump for tabellen `groups`
--

INSERT INTO `groups` (`id`, `name`) VALUES
(1, 'admin'),
(63, 'testers');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `jobs`
--

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `testcase_id` int(11) NOT NULL,
  `operatingsystem_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `suite_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=722 ;

--
-- Data dump for tabellen `jobs`
--


-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `myacos`
--

CREATE TABLE IF NOT EXISTS `myacos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `parent_id` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`),
  KEY `parent_id` (`parent_id`),
  KEY `foreign_key` (`foreign_key`),
  KEY `model` (`model`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=348 ;

--
-- Data dump for tabellen `myacos`
--

INSERT INTO `myacos` (`id`, `model`, `foreign_key`, `parent_id`, `alias`) VALUES
(1, '', 0, NULL, '/everything'),
(318, '', 0, 314, '/everything/Testcases/testlabview'),
(317, '', 0, 314, '/everything/Testcases/view'),
(316, '', 0, 314, '/everything/Testcases/lilist'),
(315, '', 0, 314, '/everything/Testcases/index'),
(314, '', 0, 1, '/everything/Testcases'),
(313, '', 0, 307, '/everything/Testcasesteps/delete'),
(312, '', 0, 307, '/everything/Testcasesteps/edit'),
(311, '', 0, 307, '/everything/Testcasesteps/add'),
(310, '', 0, 307, '/everything/Testcasesteps/view'),
(309, '', 0, 307, '/everything/Testcasesteps/reorder'),
(308, '', 0, 307, '/everything/Testcasesteps/index'),
(307, '', 0, 1, '/everything/Testcasesteps'),
(306, '', 0, 301, '/everything/Suites/delete'),
(305, '', 0, 301, '/everything/Suites/edit'),
(304, '', 0, 301, '/everything/Suites/add'),
(303, '', 0, 301, '/everything/Suites/view'),
(302, '', 0, 301, '/everything/Suites/index'),
(301, '', 0, 1, '/everything/Suites'),
(300, '', 0, 295, '/everything/Sites/delete'),
(299, '', 0, 295, '/everything/Sites/edit'),
(298, '', 0, 295, '/everything/Sites/add'),
(297, '', 0, 295, '/everything/Sites/view'),
(296, '', 0, 295, '/everything/Sites/select'),
(295, '', 0, 1, '/everything/Sites'),
(294, '', 0, 293, '/everything/Selftest/build'),
(293, '', 0, 1, '/everything/Selftest'),
(292, '', 0, 290, '/everything/Seleniumserver/executeCommand'),
(291, '', 0, 290, '/everything/Seleniumserver/driver'),
(290, '', 0, 1, '/everything/Seleniumserver'),
(289, '', 0, 285, '/everything/Runrctests/runTestcase'),
(288, '', 0, 285, '/everything/Runrctests/runRequirement'),
(287, '', 0, 285, '/everything/Runrctests/runAndViewRequirement'),
(286, '', 0, 285, '/everything/Runrctests/runAndViewTestcase'),
(285, '', 0, 1, '/everything/Runrctests'),
(284, '', 0, 275, '/everything/Requirements/delete'),
(283, '', 0, 275, '/everything/Requirements/edit'),
(282, '', 0, 275, '/everything/Requirements/add'),
(281, '', 0, 275, '/everything/Requirements/testlabview'),
(280, '', 0, 275, '/everything/Requirements/view'),
(279, '', 0, 275, '/everything/Requirements/index'),
(278, '', 0, 275, '/everything/Requirements/updateCombination'),
(277, '', 0, 275, '/everything/Requirements/updatetc'),
(276, '', 0, 275, '/everything/Requirements/reorder'),
(275, '', 0, 1, '/everything/Requirements'),
(274, '', 0, 267, '/everything/Projects/select'),
(273, '', 0, 267, '/everything/Projects/testlabsview'),
(272, '', 0, 267, '/everything/Projects/delete'),
(271, '', 0, 267, '/everything/Projects/edit'),
(270, '', 0, 267, '/everything/Projects/add'),
(269, '', 0, 267, '/everything/Projects/view'),
(268, '', 0, 267, '/everything/Projects/index'),
(267, '', 0, 1, '/everything/Projects'),
(266, '', 0, 261, '/everything/Operatingsystems/delete'),
(265, '', 0, 261, '/everything/Operatingsystems/edit'),
(264, '', 0, 261, '/everything/Operatingsystems/add'),
(263, '', 0, 261, '/everything/Operatingsystems/view'),
(262, '', 0, 261, '/everything/Operatingsystems/index'),
(261, '', 0, 1, '/everything/Operatingsystems'),
(260, '', 0, 255, '/everything/Nodes/delete'),
(259, '', 0, 255, '/everything/Nodes/edit'),
(258, '', 0, 255, '/everything/Nodes/add'),
(257, '', 0, 255, '/everything/Nodes/view'),
(256, '', 0, 255, '/everything/Nodes/index'),
(255, '', 0, 1, '/everything/Nodes'),
(254, '', 0, 253, '/everything/News/index'),
(253, '', 0, 1, '/everything/News'),
(252, '', 0, 247, '/everything/Myaros/delete'),
(251, '', 0, 247, '/everything/Myaros/edit'),
(250, '', 0, 247, '/everything/Myaros/add'),
(249, '', 0, 247, '/everything/Myaros/view'),
(248, '', 0, 247, '/everything/Myaros/index'),
(247, '', 0, 1, '/everything/Myaros'),
(246, '', 0, 241, '/everything/Myacos/delete'),
(245, '', 0, 241, '/everything/Myacos/edit'),
(244, '', 0, 241, '/everything/Myacos/add'),
(243, '', 0, 241, '/everything/Myacos/view'),
(242, '', 0, 241, '/everything/Myacos/index'),
(241, '', 0, 1, '/everything/Myacos'),
(240, '', 0, 235, '/everything/Menus/delete'),
(239, '', 0, 235, '/everything/Menus/edit'),
(238, '', 0, 235, '/everything/Menus/add'),
(237, '', 0, 235, '/everything/Menus/view'),
(236, '', 0, 235, '/everything/Menus/index'),
(235, '', 0, 1, '/everything/Menus'),
(234, '', 0, 229, '/everything/ManageAcl/listAcos'),
(233, '', 0, 229, '/everything/ManageAcl/removeACL'),
(232, '', 0, 229, '/everything/ManageAcl/createACL'),
(231, '', 0, 229, '/everything/ManageAcl/listAros'),
(230, '', 0, 229, '/everything/ManageAcl/index'),
(229, '', 0, 1, '/everything/ManageAcl'),
(228, '', 0, 224, '/everything/Items/updateItemSize'),
(227, '', 0, 224, '/everything/Items/updateItemPosition'),
(226, '', 0, 224, '/everything/Items/edit'),
(225, '', 0, 224, '/everything/Items/index'),
(224, '', 0, 1, '/everything/Items'),
(223, '', 0, 218, '/everything/Groups/delete'),
(222, '', 0, 218, '/everything/Groups/edit'),
(221, '', 0, 218, '/everything/Groups/add'),
(220, '', 0, 218, '/everything/Groups/view'),
(219, '', 0, 218, '/everything/Groups/index'),
(218, '', 0, 1, '/everything/Groups'),
(217, '', 0, 212, '/everything/Configs/stateOfTheSystem'),
(216, '', 0, 212, '/everything/Configs/sendUsMailWhenBromineFails'),
(215, '', 0, 212, '/everything/Configs/help'),
(214, '', 0, 212, '/everything/Configs/register'),
(213, '', 0, 212, '/everything/Configs/checkForUpdates'),
(212, '', 0, 1, '/everything/Configs'),
(211, '', 0, 205, '/everything/Commands/delete'),
(210, '', 0, 205, '/everything/Commands/edit'),
(209, '', 0, 205, '/everything/Commands/add'),
(208, '', 0, 205, '/everything/Commands/view'),
(207, '', 0, 205, '/everything/Commands/belongsToProject'),
(206, '', 0, 205, '/everything/Commands/index'),
(205, '', 0, 1, '/everything/Commands'),
(204, '', 0, 199, '/everything/Browsers/delete'),
(203, '', 0, 199, '/everything/Browsers/edit'),
(202, '', 0, 199, '/everything/Browsers/add'),
(201, '', 0, 199, '/everything/Browsers/view'),
(200, '', 0, 199, '/everything/Browsers/index'),
(199, '', 0, 1, '/everything/Browsers'),
(198, '', 0, 197, '/everything/Pages/display'),
(197, '', 0, 1, '/everything/Pages'),
(319, '', 0, 314, '/everything/Testcases/viewscript'),
(320, '', 0, 314, '/everything/Testcases/add'),
(321, '', 0, 314, '/everything/Testcases/edit'),
(322, '', 0, 314, '/everything/Testcases/upload'),
(323, '', 0, 314, '/everything/Testcases/delete'),
(324, '', 0, 314, '/everything/Testcases/addToJira'),
(325, '', 0, 1, '/everything/Testlabs'),
(326, '', 0, 325, '/everything/Testlabs/index'),
(327, '', 0, 1, '/everything/Tests'),
(328, '', 0, 327, '/everything/Tests/index'),
(329, '', 0, 327, '/everything/Tests/view'),
(330, '', 0, 327, '/everything/Tests/add'),
(331, '', 0, 327, '/everything/Tests/edit'),
(332, '', 0, 327, '/everything/Tests/delete'),
(333, '', 0, 1, '/everything/Types'),
(334, '', 0, 333, '/everything/Types/index'),
(335, '', 0, 333, '/everything/Types/view'),
(336, '', 0, 333, '/everything/Types/add'),
(337, '', 0, 333, '/everything/Types/edit'),
(338, '', 0, 333, '/everything/Types/delete'),
(339, '', 0, 1, '/everything/Users'),
(340, '', 0, 339, '/everything/Users/assign'),
(341, '', 0, 339, '/everything/Users/login'),
(342, '', 0, 339, '/everything/Users/logout'),
(343, '', 0, 339, '/everything/Users/index'),
(344, '', 0, 339, '/everything/Users/view'),
(345, '', 0, 339, '/everything/Users/add'),
(346, '', 0, 339, '/everything/Users/edit'),
(347, '', 0, 339, '/everything/Users/delete');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `myaros`
--

CREATE TABLE IF NOT EXISTS `myaros` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `foreign_key` int(11) NOT NULL,
  `parent_id` int(10) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`),
  KEY `parent_id` (`parent_id`),
  KEY `foreign_key` (`foreign_key`),
  KEY `model` (`model`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101 ;

--
-- Data dump for tabellen `myaros`
--

INSERT INTO `myaros` (`id`, `model`, `foreign_key`, `parent_id`, `alias`) VALUES
(1, 'group', 1, NULL, '/admin'),
(82, 'user', 50, 1, '/admin/admin'),
(95, 'group', 63, NULL, '/testers'),
(96, 'user', 63, 95, '/testers/tester'),
(100, 'user', 65, 1, '/admin/admin2');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `myaros_myacos`
--

CREATE TABLE IF NOT EXISTS `myaros_myacos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `myaro_id` int(10) NOT NULL,
  `myaco_id` int(10) NOT NULL,
  `access` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ARO_ACO_KEY` (`myaro_id`,`myaco_id`),
  KEY `myaco_id` (`myaco_id`),
  KEY `myaro_id` (`myaro_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=67 ;

--
-- Data dump for tabellen `myaros_myacos`
--

INSERT INTO `myaros_myacos` (`id`, `myaro_id`, `myaco_id`, `access`) VALUES
(66, 96, 272, 1),
(54, 95, 1, 1),
(64, 1, 1, 1),
(55, 95, 272, 0);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `nodes`
--

CREATE TABLE IF NOT EXISTS `nodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nodepath` varchar(255) NOT NULL,
  `operatingsystem_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `running` tinyint(4) NOT NULL DEFAULT '0',
  `limit` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `operatingsystem_id` (`operatingsystem_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=107 ;

--
-- Data dump for tabellen `nodes`
--

INSERT INTO `nodes` (`id`, `nodepath`, `operatingsystem_id`, `description`, `running`, `limit`) VALUES
(88, '127.0.0.1:4444', 1, 'Selenium server on the localhost machine', 0, 1);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `operatingsystems`
--

CREATE TABLE IF NOT EXISTS `operatingsystems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- Data dump for tabellen `operatingsystems`
--

INSERT INTO `operatingsystems` (`id`, `name`) VALUES
(1, 'Windows Vista'),
(2, 'Ubuntu'),
(3, 'Windows 2000'),
(4, 'Mac OSx'),
(5, 'Windows 98'),
(6, 'Windows 95'),
(18, 'Windows 7');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `plugins`
--

CREATE TABLE IF NOT EXISTS `plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `activated` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Data dump for tabellen `plugins`
--

INSERT INTO `plugins` (`id`, `name`, `activated`) VALUES
(19, 'pizza', 1),
(22, 'zipupdate', 1);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=145 ;

--
-- Data dump for tabellen `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`) VALUES
(143, 'SampleProject', 'This is the sample project that comes with Bromine. Run the testscript to see the functions of BRUnit in both Java and PHP');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `projects_reports`
--

CREATE TABLE IF NOT EXISTS `projects_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Data dump for tabellen `projects_reports`
--


-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `projects_users`
--

CREATE TABLE IF NOT EXISTS `projects_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=127 ;

--
-- Data dump for tabellen `projects_users`
--

INSERT INTO `projects_users` (`id`, `project_id`, `user_id`) VALUES
(123, 143, 50);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `qrtz_blob_triggers`
--

CREATE TABLE IF NOT EXISTS `QRTZ_BLOB_TRIGGERS` (
  `TRIGGER_NAME` varchar(200) NOT NULL,
  `TRIGGER_GROUP` varchar(200) NOT NULL,
  `BLOB_DATA` blob,
  PRIMARY KEY (`TRIGGER_NAME`,`TRIGGER_GROUP`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `qrtz_blob_triggers`
--


-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `qrtz_calendars`
--

CREATE TABLE IF NOT EXISTS `QRTZ_CALENDARS` (
  `CALENDAR_NAME` varchar(200) NOT NULL,
  `CALENDAR` blob NOT NULL,
  PRIMARY KEY (`CALENDAR_NAME`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `qrtz_calendars`
--


-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `qrtz_cron_triggers`
--

CREATE TABLE IF NOT EXISTS `QRTZ_CRON_TRIGGERS` (
  `TRIGGER_NAME` varchar(200) NOT NULL,
  `TRIGGER_GROUP` varchar(200) NOT NULL,
  `CRON_EXPRESSION` varchar(200) NOT NULL,
  `TIME_ZONE_ID` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`TRIGGER_NAME`,`TRIGGER_GROUP`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `qrtz_fired_triggers`
--

CREATE TABLE IF NOT EXISTS `QRTZ_FIRED_TRIGGERS` (
  `ENTRY_ID` varchar(95) NOT NULL,
  `TRIGGER_NAME` varchar(200) NOT NULL,
  `TRIGGER_GROUP` varchar(200) NOT NULL,
  `IS_VOLATILE` varchar(1) NOT NULL,
  `INSTANCE_NAME` varchar(200) NOT NULL,
  `FIRED_TIME` bigint(13) NOT NULL,
  `PRIORITY` int(11) NOT NULL,
  `STATE` varchar(16) NOT NULL,
  `JOB_NAME` varchar(200) DEFAULT NULL,
  `JOB_GROUP` varchar(200) DEFAULT NULL,
  `IS_STATEFUL` varchar(1) DEFAULT NULL,
  `REQUESTS_RECOVERY` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`ENTRY_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `qrtz_job_details`
--

CREATE TABLE IF NOT EXISTS `QRTZ_JOB_DETAILS` (
  `JOB_NAME` varchar(200) NOT NULL,
  `JOB_GROUP` varchar(200) NOT NULL,
  `DESCRIPTION` varchar(250) DEFAULT NULL,
  `JOB_CLASS_NAME` varchar(250) NOT NULL,
  `IS_DURABLE` varchar(1) NOT NULL,
  `IS_VOLATILE` varchar(1) NOT NULL,
  `IS_STATEFUL` varchar(1) NOT NULL,
  `REQUESTS_RECOVERY` varchar(1) NOT NULL,
  `JOB_DATA` blob,
  PRIMARY KEY (`JOB_NAME`,`JOB_GROUP`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `qrtz_job_listeners`
--

CREATE TABLE IF NOT EXISTS `QRTZ_JOB_LISTENERS` (
  `JOB_NAME` varchar(200) NOT NULL,
  `JOB_GROUP` varchar(200) NOT NULL,
  `JOB_LISTENER` varchar(200) NOT NULL,
  PRIMARY KEY (`JOB_NAME`,`JOB_GROUP`,`JOB_LISTENER`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `qrtz_job_listeners`
--


-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `qrtz_locks`
--

CREATE TABLE IF NOT EXISTS `QRTZ_LOCKS` (
  `LOCK_NAME` varchar(40) NOT NULL,
  PRIMARY KEY (`LOCK_NAME`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `qrtz_locks`
--

INSERT INTO `QRTZ_LOCKS` (`LOCK_NAME`) VALUES
('CALENDAR_ACCESS'),
('JOB_ACCESS'),
('MISFIRE_ACCESS'),
('STATE_ACCESS'),
('TRIGGER_ACCESS');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `qrtz_paused_trigger_grps`
--

CREATE TABLE IF NOT EXISTS `QRTZ_PAUSED_TRIGGER_GRPS` (
  `TRIGGER_GROUP` varchar(200) NOT NULL,
  PRIMARY KEY (`TRIGGER_GROUP`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `qrtz_paused_trigger_grps`
--


-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `qrtz_scheduler_state`
--

CREATE TABLE IF NOT EXISTS `QRTZ_SCHEDULER_STATE` (
  `INSTANCE_NAME` varchar(200) NOT NULL,
  `LAST_CHECKIN_TIME` bigint(13) NOT NULL,
  `CHECKIN_INTERVAL` bigint(13) NOT NULL,
  PRIMARY KEY (`INSTANCE_NAME`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `qrtz_scheduler_state`
--


-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `qrtz_simple_triggers`
--

CREATE TABLE IF NOT EXISTS `QRTZ_SIMPLE_TRIGGERS` (
  `TRIGGER_NAME` varchar(200) NOT NULL,
  `TRIGGER_GROUP` varchar(200) NOT NULL,
  `REPEAT_COUNT` bigint(7) NOT NULL,
  `REPEAT_INTERVAL` bigint(12) NOT NULL,
  `TIMES_TRIGGERED` bigint(10) NOT NULL,
  PRIMARY KEY (`TRIGGER_NAME`,`TRIGGER_GROUP`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `qrtz_simple_triggers`
--


-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `qrtz_triggers`
--

CREATE TABLE IF NOT EXISTS `QRTZ_TRIGGERS` (
  `TRIGGER_NAME` varchar(200) NOT NULL,
  `TRIGGER_GROUP` varchar(200) NOT NULL,
  `JOB_NAME` varchar(200) NOT NULL,
  `JOB_GROUP` varchar(200) NOT NULL,
  `IS_VOLATILE` varchar(1) NOT NULL,
  `DESCRIPTION` varchar(250) DEFAULT NULL,
  `NEXT_FIRE_TIME` bigint(13) DEFAULT NULL,
  `PREV_FIRE_TIME` bigint(13) DEFAULT NULL,
  `PRIORITY` int(11) DEFAULT NULL,
  `TRIGGER_STATE` varchar(16) NOT NULL,
  `TRIGGER_TYPE` varchar(8) NOT NULL,
  `START_TIME` bigint(13) NOT NULL,
  `END_TIME` bigint(13) DEFAULT NULL,
  `CALENDAR_NAME` varchar(200) DEFAULT NULL,
  `MISFIRE_INSTR` smallint(2) DEFAULT NULL,
  `JOB_DATA` blob,
  PRIMARY KEY (`TRIGGER_NAME`,`TRIGGER_GROUP`),
  KEY `JOB_NAME` (`JOB_NAME`,`JOB_GROUP`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `qrtz_trigger_listeners`
--

CREATE TABLE IF NOT EXISTS `QRTZ_TRIGGER_LISTENERS` (
  `TRIGGER_NAME` varchar(200) NOT NULL,
  `TRIGGER_GROUP` varchar(200) NOT NULL,
  `TRIGGER_LISTENER` varchar(200) NOT NULL,
  PRIMARY KEY (`TRIGGER_NAME`,`TRIGGER_GROUP`,`TRIGGER_LISTENER`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Data dump for tabellen `qrtz_trigger_listeners`
--


-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `user_id` int(11) NOT NULL,
  `testcase_in_summary` tinyint(4) NOT NULL,
  `specify_requirements` tinyint(4) NOT NULL,
  `specify_testcases` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Data dump for tabellen `reports`
--

INSERT INTO `reports` (`id`, `name`, `user_id`, `testcase_in_summary`, `specify_requirements`, `specify_testcases`) VALUES
(1, 'All projects. Summary only', 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `requirements`
--

CREATE TABLE IF NOT EXISTS `requirements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `project_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `project_id` (`project_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=277 ;

--
-- Data dump for tabellen `requirements`
--

INSERT INTO `requirements` (`id`, `name`, `description`, `project_id`, `parent_id`, `user_id`) VALUES
(271, 'BRUnit', 'This shows the functions of BRUnit in both PHP and Java', 143, 0, 50),
(270, 'Features(PHP)', 'This is an example of PHP testscripts...\r\nGoto app/webroot/testscripts/sampleproject/php and see the sourcecode in the php files', 143, 268, 50),
(269, 'Home(Java)', 'This is examples of java testscript... goto app/webroot/testscripts/sampleproject/jar/\nextract 329.jar and 330.jar to see source code', 143, 268, 50),
(268, 'BromineFoundation', 'This is just a container for other requirements', 143, 0, 50);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `requirements_testcases`
--

CREATE TABLE IF NOT EXISTS `requirements_testcases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `testcase_id` int(11) NOT NULL,
  `requirement_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `requirement_id` (`requirement_id`),
  KEY `testcase_id` (`testcase_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=867 ;

--
-- Data dump for tabellen `requirements_testcases`
--

INSERT INTO `requirements_testcases` (`id`, `testcase_id`, `requirement_id`) VALUES
(866, 329, 269),
(862, 332, 271),
(864, 333, 270),
(861, 331, 271),
(863, 334, 270),
(865, 330, 269);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `seleniumservers`
--

CREATE TABLE IF NOT EXISTS `seleniumservers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_id` int(11) NOT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `lastCommand` int(11) NOT NULL,
  `nodepath` varchar(255) NOT NULL,
  `node_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `node_id` (`node_id`),
  KEY `test_id` (`test_id`),
  KEY `session_id` (`session_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1201 ;

--
-- Data dump for tabellen `seleniumservers`
--


-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `sites`
--

CREATE TABLE IF NOT EXISTS `sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=81 ;

--
-- Data dump for tabellen `sites`
--

INSERT INTO `sites` (`id`, `name`, `project_id`) VALUES
(79, 'http://www.brominefoundation.org', 143);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `suites`
--

CREATE TABLE IF NOT EXISTS `suites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(11) DEFAULT NULL,
  `project_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Data dump for tabellen `suites`
--


-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `testcases`
--

CREATE TABLE IF NOT EXISTS `testcases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `project_id` int(11) NOT NULL,
  `description` longtext NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=340 ;

--
-- Data dump for tabellen `testcases`
--

INSERT INTO `testcases` (`id`, `name`, `project_id`, `description`, `user_id`) VALUES
(329, 'Screencast', 143, '', 50),
(330, 'Footer Links', 143, '', 50),
(331, 'PHP', 143, 'Test BRUnit for PHP', 50),
(332, 'Java', 143, '', 50),
(333, 'Screencasts', 143, 'Verify the screencast on the feature site', 50),
(334, 'Check feature list', 143, 'Checks 2 of the features in the feature list', 50);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `testcasesteps`
--

CREATE TABLE IF NOT EXISTS `testcasesteps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderby` int(11) NOT NULL,
  `action` text NOT NULL,
  `reaction` text NOT NULL,
  `testcase_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `testcase_id` (`testcase_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=74 ;

--
-- Data dump for tabellen `testcasesteps`
--

INSERT INTO `testcasesteps` (`id`, `orderby`, `action`, `reaction`, `testcase_id`) VALUES
(64, 1, 'some action', 'some reaction', 329),
(63, 2, 'another action', 'another reaction', 334),
(62, 1, 'some action', 'some reaction', 334),
(65, 2, 'another action', 'another reaction', 329),
(66, 1, 'some action', 'some reaction', 330),
(67, 2, 'another action', 'another reaction', 330),
(68, 1, 'some action', 'some reaction', 331),
(69, 2, 'another action', 'another reaction', 331),
(70, 1, 'some action', 'some reaction', 332),
(71, 2, 'another action', 'another reaction', 332),
(72, 1, 'some action', 'some reaction', 333),
(73, 2, 'another action', 'another reaction', 333);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `tests`
--

CREATE TABLE IF NOT EXISTS `tests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(255) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `suite_id` int(10) unsigned DEFAULT NULL,
  `browser_id` int(11) NOT NULL,
  `operatingsystem_id` int(11) NOT NULL,
  `testcase_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `session_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `testcase_id` (`testcase_id`),
  KEY `operatingsystem_id` (`operatingsystem_id`),
  KEY `browser_id` (`browser_id`),
  KEY `suite_id` (`suite_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Data dump for tabellen `tests`
--


-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `types`
--

CREATE TABLE IF NOT EXISTS `types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `command` varchar(255) NOT NULL,
  `spacer` varchar(255) NOT NULL,
  `extension` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Data dump for tabellen `types`
--

INSERT INTO `types` (`id`, `name`, `command`, `spacer`, `extension`) VALUES
(1, 'php', 'php', ' ', 'php'),
(4, 'java', 'java -jar', ' ', 'jar');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '1',
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

--
-- Data dump for tabellen `users`
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
