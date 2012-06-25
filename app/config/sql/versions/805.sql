DROP TABLE seleniumservers;

ALTER TABLE `nodes` ADD COLUMN `session_id` VARCHAR(255) NULL;
ALTER TABLE `nodes` ADD COLUMN `running` TINYINT NOT NULL  AFTER `session_id` , ADD COLUMN `test_id` INT NULL DEFAULT NULL  AFTER `running` , ADD COLUMN `lastCommand` INT NULL DEFAULT NULL  AFTER `test_id` ;
ALTER TABLE `suites` DROP COLUMN `analysis` , DROP COLUMN `browser_id` , DROP COLUMN `name` , DROP COLUMN `operating_system_id` , DROP COLUMN `selenium_revision` , DROP COLUMN `selenium_version` , DROP COLUMN `status` , DROP COLUMN `timedate` , DROP COLUMN `timetaken` , DROP INDEX `browser_id`, DROP INDEX `operating_system_id` ;

ALTER TABLE `tests` DROP COLUMN `manstatus` ;
ALTER TABLE `tests` CHANGE COLUMN `name` `name` VARCHAR(255) NULL DEFAULT NULL  ;

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `testcase_id` int(11) NOT NULL,
  `operatingsystem_id` int(11) NOT NULL,
  `browser_id` int(11) NOT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `suite_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32;