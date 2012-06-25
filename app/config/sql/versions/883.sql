ALTER TABLE `browsers`
    DROP `shortname`,
    DROP `saucename`;

ALTER TABLE `operatingsystems`
    DROP `shortname`,
    DROP `saucename`;
  
ALTER TABLE  `tests` ADD `session_id` VARCHAR( 255 ) NULL;

ALTER TABLE  `nodes` 
    DROP  `test_id`,
    DROP  `session_id`,
    DROP  `lastCommand`,
    ADD  `limit` INT NOT NULL DEFAULT  '1';

CREATE TABLE IF NOT EXISTS `seleniumservers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_id` int(11) NOT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `lastCommand` int(11) NOT NULL,
  `nodepath` varchar(255) CHARACTER SET utf8 NOT NULL,
  `node_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `node_id` (`node_id`),
  KEY `test_id` (`test_id`),
  KEY `session_id` (`session_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=240 ;

INSERT INTO configs(id, `name`, value) VALUES (null, "sauce_nodepath", "67.23.20.87:4444");
