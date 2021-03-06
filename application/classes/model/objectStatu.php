<?php defined('SYSPATH') or die('No direct script access.');

class Model_ObjectStatu extends ORM {
    
	protected $_has_many = array(
		'sfwprods'       => array('model' => 'sfwprod', 'through' => 'objects_sfwprods'),		
		'tasks' => array('model' => 'task', 'foreign_key' => 'object_id'),
		
	);
        
	protected $_belongs_to  = array(
		'typeobject' => array('foreign_key' => 'typeobject_id'),
		'country' => array('foreign_key' => 'country_id'),
		'collection' => array('foreign_key' => 'collection_id'),		
		'project' => array('foreign_key' => 'project_id'),	
		'supplier' => array('model' => 'supplier', 'through' => 'objects_suppliers'),
		'statu' => array('model' => 'statu', 'foreign_key' => 'status_id'),
		'object' => array('model' => 'object', 'foreign_key' => 'object_id'),
	);

	public function getAnotacoes($object_id){
		$anotacoes = "";
		$rs = ORM::factory('anotacoes_object')->where('object_id', '=', $object_id)->order_by('id', 'DESC')->limit('1')->find_all();
		foreach ($rs as $anotacao) {
			$anotacoes.= $anotacao->anotacao."\n-------------------\n";
		}

		return $anotacoes;
	}

	public function getGdocs($object_id){
		$rs = ORM::factory('gdoc')->where('object_id', '=', $object_id)->find();
		return $rs;
	}

	public function getStatus($objId){
		//$status = ORM::factory('task')->where('object_status_id', '=', $objId)->order_by("id", 'DESC')->find();
		//return (is_null($status->status->status)) ? "" : $status;
	}
}