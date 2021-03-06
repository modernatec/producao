<?php defined('SYSPATH') or die('No direct script access.');

class Model_TasksNota extends ORM {
    
	protected $_belongs_to  = array(
		'userInfo' => array('model' => 'userInfo', 'foreign_key' => 'userInfo_id'),
    	'status' => array('model' => 'statu', 'foreign_key' => 'status_id'),
    	'to' => array('model' => 'userInfo', 'foreign_key' => 'task_to'),
    	'tag' => array('model' => 'tag', 'foreign_key' => 'tag_id'),
	);	
	
	public function getReplies($id){
		return ORM::factory('tasks_statu')->where('task_id', '=', $id)->find_all();
	}
	/*
	public function getHistory($id){
		return ORM::factory('tasksNota')->where('object_status_id', '=', $id)->order_by('id', 'DESC')->find_all();
	}
	*/
	
}