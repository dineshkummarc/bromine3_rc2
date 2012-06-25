<?php
class Job extends AppModel {
                                  
    var $cacheQueries = false;
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
		'Testcase' => array(
			'className' => 'Testcase',
			'foreignKey' => 'testcase_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Operatingsystem' => array(
			'className' => 'Operatingsystem',
			'foreignKey' => 'operatingsystem_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Browser' => array(
			'className' => 'Browser',
			'foreignKey' => 'browser_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Suite' => array(
			'className' => 'Suite',
			'foreignKey' => 'suite_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}
?>