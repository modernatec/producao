<?php defined('SYSPATH') or die('No direct script access.');

class Model_Task extends ORM {
	//protected $load_with = array('statu');

	protected $_belongs_to  = array(
		'object' => array('foreign_key' => 'object_id'),
		'priority' => array('model' => 'priority', 'foreign_key' => 'priority_id'),
		'userInfo' => array('model' => 'userInfo', 'foreign_key' => 'userInfo_id'),
		'statu' => array('model' => 'statu', 'foreign_key' => 'status_id'),
    	'step' => array('model' => 'step', 'foreign_key' => 'step_id'),
    	'task' => array('model' => 'task', 'foreign_key' => 'task_id'),
	);	

	protected $_has_one = array(
	    'task_to' => array('model' => 'tasks_user', 'foreign_key' =>'task_id', 'through' => 'tasks_users'),
	);


	public function rules()
	{
	    return array(
            'userInfo_id' => array(
                array('not_empty'),
            ),
	    );
	}

	public function filters()
	{
		return array(
			'crono_date' => array(
				array(array($this, 'setup_date'))
			),
			'taxonomia' => array(
				array('Utils_Helper::limparStr')
			)
		);
	}

	/**
	 * Does the reverse of unique_key_exists() by triggering error if folder exists.
	 * Validation callback.
	 *
	 * @param   Validation  Validation object
	 * @param   string      Field folder
	 * @return  void
	 */
	public function name_available(Validation $validation, $field)
	{	
		if ($this->unique_name_exists($validation[$field], 'title'))
		{
			$validation->error($field, 'name_available', array($validation[$field]));
		}
	}

	/**
	 * Tests if a unique key value exists in the database.
	 *
	 * @param   mixed    the value to test
	 * @param   string   field name
	 * @return  boolean
	 */
	public function unique_name_exists($value, $field = NULL)
	{
		return (bool) DB::select(array('COUNT("*")', 'total_count'))
			->from($this->_table_name)
			->where($field, '=', $value)
			->execute($this->_db)
			->get('total_count');
	}


	public function setup_date($value)
	{
		return  date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $value)));
	}

	public function labels()
	{
		return array(
			'title'         => __('Título'),
			'priority_id'   => __('Prioridade'),
			'project_id'    => __('Projeto'),
			'userInfo_id'	=> __('Usuário responsável'),
			'crono_date'	=> __('Data de entrega'),
			'description'	=> __('Descrição'),
			'taxonomia'	=> __('Taxonomia'),
		);
	}
}
