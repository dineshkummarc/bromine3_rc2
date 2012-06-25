INSERT INTO  `menus` (
`id` ,
`parent_id` ,
`title` ,
`controller` ,
`action` ,
`odr`
)
VALUES (
NULL ,  '61',  'Server options',  'configs',  'server',  '5'
);

INSERT INTO `configs` (`id`, `name`, `value`) VALUES (NULL, 'servername', '127.0.0.1'), (NULL, 'port', '80');