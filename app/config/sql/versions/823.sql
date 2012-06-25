UPDATE  `menus` SET  `title` =  'Scheduler' WHERE  `menus`.`title` = 'Scheduled jobs';
DELETE FROM `menus` WHERE  `menus`.`title` = 'Test scheduler';