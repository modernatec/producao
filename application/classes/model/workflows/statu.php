<?php defined('SYSPATH') or die('No direct script access.');

class Model_Workflows_statu extends ORM {
	protected $_belongs_to = array(
		'statu' => array('foreign_key' => 'status_id'),
	);
}