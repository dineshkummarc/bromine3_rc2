ALTER TABLE `browsers` ADD `shortname` VARCHAR( 10 ) NOT NULL ;
UPDATE `browsers` SET `shortname` = 'ie7' WHERE `browsers`.`id` =1 LIMIT 1 ;
UPDATE `browsers` SET `shortname` = 'ie6' WHERE `browsers`.`id` =2 LIMIT 1 ;
UPDATE `browsers` SET `shortname` = 'ff2' WHERE `browsers`.`id` =3 LIMIT 1 ;
UPDATE `browsers` SET `shortname` = 'safari' WHERE `browsers`.`id` =6 LIMIT 1 ;
UPDATE `browsers` SET `shortname` = 'ff3' WHERE `browsers`.`id` =7 LIMIT 1 ;
UPDATE `browsers` SET `shortname` = 'opera' WHERE `browsers`.`id` =8 LIMIT 1 ; 
ALTER TABLE `operatingsystems` ADD `shortname` VARCHAR( 10 ) NOT NULL ;
UPDATE `operatingsystems` SET `shortname` = 'vista' WHERE `operatingsystems`.`id` =1 LIMIT 1 ;
UPDATE `operatingsystems` SET `shortname` = 'ubuntu' WHERE `operatingsystems`.`id` =2 LIMIT 1 ;
UPDATE `operatingsystems` SET `shortname` = 'win2000' WHERE `operatingsystems`.`id` =3 LIMIT 1 ;
UPDATE `operatingsystems` SET `shortname` = 'macos' WHERE `operatingsystems`.`id` =4 LIMIT 1 ;
UPDATE `operatingsystems` SET `shortname` = 'win98' WHERE `operatingsystems`.`id` =5 LIMIT 1 ;
UPDATE `operatingsystems` SET `shortname` = 'win95' WHERE `operatingsystems`.`id` =6 LIMIT 1 ;
UPDATE `operatingsystems` SET `shortname` = 'winxp' WHERE `operatingsystems`.`id` =7 LIMIT 1 ;