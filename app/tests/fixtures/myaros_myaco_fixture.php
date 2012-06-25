<?php 
/* SVN FILE: $Id$ */
/* MyarosMyaco Fixture generated on: 2010-07-15 17:15:18 : 1279206918*/

class MyarosMyacoFixture extends CakeTestFixture {
	var $table = 'myaros_myacos';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'myaro_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'myaco_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
		'access' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 4),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'ARO_ACO_KEY' => array('column' => array('myaro_id', 'myaco_id'), 'unique' => 1), 'myaco_id' => array('column' => 'myaco_id', 'unique' => 0), 'myaro_id' => array('column' => 'myaro_id', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'myaro_id'  => 1,
		'myaco_id'  => 1,
		'access'  => 1
	));
}
?>