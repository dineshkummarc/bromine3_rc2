<?php
class Seleniumserver extends AppModel {

    var $validate = array(
        'test_id' => 'numeric',
        'lastCommand' => 'numeric',
        'nodepath' => 'notEmpty',
        'node_id' => 'numeric'
    );
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
		'Test' => array(
			'className' => 'Test',
			'foreignKey' => 'test_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Node' => array(
			'className' => 'Node',
			'foreignKey' => 'node_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	function afterSave($created){
        if($created === true){
            $this->Node->updateAll(array('running'=>'running+1'), array('Node.id'=>$this->data['Seleniumserver']['node_id']));
        }
    }
    
    function beforeDelete(){
        $data = $this->findById($this->id);
        $this->Node->updateAll(array('running'=>'running-1'), array('Node.id'=>$data['Seleniumserver']['node_id']));
        return true;
    }

}
?>