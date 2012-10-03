<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Arm Auth Role Model.
 *
 * @package    Arm Auth
 * @author     Devi Mandiri <devi.mandiri@gmail.com>
 * @copyright  (c) 2011 Devi Mandiri
 * @license    MIT
 */
class Model_Team extends ORM {
	//static $belongs_to = array('estado');

	protected $_has_many = array(
		'user'       => array('model' => 'userInfo', 'through' => 'teams_users'),
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
                'name'  => 'Equipe',
            );
	}
}
