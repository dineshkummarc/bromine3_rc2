ALTER TABLE  `browsers` ADD  `saucename` VARCHAR( 255 ) NOT NULL;
ALTER TABLE  `operatingsystems` ADD  `saucename` VARCHAR( 255 ) NOT NULL;
INSERT INTO  `menus` (
`id` ,
`parent_id` ,
`title` ,
`controller` ,
`action` ,
`odr`
)
VALUES (
NULL ,  '-2',  'Sauce RC',  'configs',  'sauce',  '1'
);
