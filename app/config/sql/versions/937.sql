DROP TABLE IF EXISTS `menus`;
CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `odr` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=91 ;

--
-- Data dump for tabellen `menus`
--

INSERT INTO `menus` (`id`, `parent_id`, `title`, `controller`, `action`, `odr`) VALUES
(-2, 0, 'Admin menu', '', '', 0),
(47, 40, 'Add testcase', 'requirements#/testcases', 'add', 2),
(-1, 0, 'Main menu', '', '', 0),
(41, -1, 'TestLabs', 'testlabs#/projects/testlabview', '', 1),
(46, 40, 'Add requirement', 'requirements#/requirements', 'add', 1),
(40, -1, 'Planning', 'requirements#/activities', '', 0),
(49, -2, 'Help', '', '', 5),
(50, 49, 'State of the system', 'configs', 'stateOfTheSystem', 0),
(51, 49, 'About Bromine', 'pages', 'about', 2),
(52, -2, 'Projects', 'projects', '', 2),
(53, 61, 'Types', 'types', '', 0),
(54, 56, 'Users', 'users', 'index', 3),
(55, 56, 'Groups', 'groups', '', 1),
(56, -2, 'Users and access', '', '', 3),
(57, 61, 'Browsers', 'browsers', '', 0),
(58, 61, 'Operating systems', 'operatingsystems', '', 0),
(59, -2, 'Nodes', 'nodes', '', 1),
(60, 56, 'Access control', 'manage_acl', '', 2),
(61, -2, 'Settings', '', '', 4),
(67, 56, 'Logs >>', 'echelons', 'index', 4),
(68, 67, 'Set to: Off', 'configs', 'setEchelon/false', 2),
(69, 67, 'Set to: On', 'configs', 'setEchelon/true', 1),
(90, 49, 'News', 'news', 'index', 4),
(75, 61, 'Email Settings', 'configs', 'email', 5),
(78, 41, 'Latest tests failed', 'testlabs#/tests/index/failed', '', 2),
(79, 41, 'Latest tests', 'testlabs#/tests/index', '', 1),
(80, 41, 'Latest tests passed', 'testlabs#/tests/index/passed', '', 3),
(81, 61, 'Server options', 'configs', 'server', 5),
(85, 61, 'Scheduler', 'qrtz_job_details', 'index', 7),
(89, 61, 'Cache', 'configs', 'cache', 1),
(88, 40, 'Import from CSV', 'requirements', 'importFromCSV', 3);
