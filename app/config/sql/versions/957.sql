INSERT INTO `requirements` (`id`, `name`, `description`, `project_id`, `parent_id`, `user_id`) VALUES
(271, 'BRUnit', 'This shows the functions of BRUnit in both PHP and Java', 143, 0, 50),
(270, 'Features(PHP)', 'This is an example of PHP testscripts...\r\nGoto app/webroot/testscripts/sampleproject/php and see the sourcecode in the php files', 143, 268, 50),
(269, 'Home(Java)', 'This is examples of java testscript... goto app/webroot/testscripts/sampleproject/jar/\nextract 329.jar and 330.jar to see source code', 143, 268, 50),
(268, 'BromineFoundation', '', 143, 0, 50);

INSERT INTO `projects` (`id`, `name`, `description`) VALUES
(143, 'SampleProject', 'This is the sample project that comes with Bromine. Run the testscript to see the functions of BRUnit in both Java and PHP');

INSERT INTO `projects_users` (`id`, `project_id`, `user_id`) VALUES
(123, 143, 50);

INSERT INTO `testcases` (`id`, `name`, `project_id`, `description`, `user_id`) VALUES
(329, 'Screencast', 143, '', 50),
(330, 'Footer Links', 143, '', 50),
(331, 'PHP', 143, 'Test BRUnit for PHP', 50),
(332, 'Java', 143, '', 50),
(333, 'Screencasts', 143, 'Verify the screencast on the feature site', 50),
(334, 'Check feature list', 143, 'Checks 2 of the features in the feature list', 50);

INSERT INTO `requirements_testcases` (`id`, `testcase_id`, `requirement_id`) VALUES
(700, 330, 269),
(699, 329, 269),
(704, 332, 271),
(703, 331, 271),
(705, 333, 270),
(706, 334, 270);

INSERT INTO `sites` (`id`, `name`, `project_id`) VALUES
(79, 'http://www.brominefoundation.org', 143);