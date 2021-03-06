<?php defined('SYSPATH') or die('No direct script access.');

class Model_Table extends ORM {
	protected $_has_many = array(
		'objects' => array('through' => 'objects_tables'),
	);
        
	public function rules()
	{
		return array(
			'name' => array(
				array('not_empty'),
			)
		);
	}

	public function labels()
	{
		return array(
			'name'  => 'Nome',
		);
	}
}