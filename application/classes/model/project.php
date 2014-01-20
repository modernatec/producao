<?php defined('SYSPATH') or die('No direct script access.');

class Model_Project extends ORM {

	public function rules()
	{
        return array(
            'name' => array(
                array('not_empty'),                
            ),
            'segmento_id' => array(
                array('not_empty'),
            ),
            'description' => array(
                array('not_empty'),
            ),
            'pasta' => array(
                array('not_empty'),
                )
        );
	}
	//array(array($this, 'name_available'), array(':validation', ':field')),

	public function labels()
	{
        return array(
            'name'  => 'Projeto',
            'segmento_id' => 'Segmento',
            'description' => 'Descrição',
            'pasta' => 'Pasta',
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
		if ($this->unique_name_exists($validation[$field], 'name'))
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
}
