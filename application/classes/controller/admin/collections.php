<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Admin_Collections extends Controller_Admin_Template {
 
	public $auth_required		= array('login', 'admin');
 	
	/*
	public $secure_actions     	= array(
								   	'create' => array('login', 'coordenador'),
									'edit' => array('login', 'coordenador'),
								   	'delete' => array('login', 'coordenador'),
								 );
	*/
					 
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);	
	}
             
	public function action_index($ajax = null)
	{	
		$view = View::factory('admin/collections/list')
			->bind('message', $message);
		
		$view->anosList = ORM::factory('collection')->group_by('ano')->order_by('ano', 'DESC')->find_all();
		
		if($ajax == null){
			$this->template->content = $view;             
		}else{
			$this->auto_render = false;
			header('Content-Type: application/json');
			echo json_encode(
				array(
					array('container' => '#content', 'type'=>'html', 'content'=> json_encode($view->render())),
				)						
			);
	        return false;
		}   
	} 

	public function action_edit($id)
    {       	      
    	$this->auto_render = false;
		$view = View::factory('admin/collections/create')
				->bind('errors', $errors)
				->bind('message', $message);
				
		$collection = ORM::factory('collection', $id);
		$view->collectionVO = $this->setVO('collection', $collection);
		$view->materiaList = ORM::factory('materia')->find_all();
		$view->segmentoList = ORM::factory('segmento')->find_all();
		$view->teamList = ORM::factory('team')->find_all();
		$view->userList = ORM::factory('userinfo')->where('status', '=', '1')->find_all();

		$view->objectList = ORM::factory('object')->where('collection_id' , '=', $id)->find_all();

		header('Content-Type: application/json');
		echo json_encode(
			array(
				array('container' => $this->request->post('container'), 'type'=>'html', 'content'=> json_encode($view->render())),
			)						
		);
        return false;	
	}

	public function action_salvar($id = null)
	{
		$this->auto_render = false;
		$db = Database::instance();
        $db->begin();
		
		try 
		{            
			$colecao = ORM::factory('collection', $id)->values($this->request->post(), array(
				'name',
				'op',
				'materia_id',
				'segmento_id',
				'ano',
				'fechamento',
				'repositorio',
			));
			               
			
			$colecao->save();
			
			$db->commit();
			$msg = "coleção salva com sucesso.";
			//Utils_Helper::mensagens('add','Coleção '.$colecao->name.' salvo com sucesso.');
			//Request::current()->redirect('admin/collections');

		} catch (ORM_Validation_Exception $e) {
            $errors = $e->errors('models');
			$erroList = '';
			foreach($errors as $erro){
				$erroList.= $erro.'<br/>';	
			}
            $msg = 'Houveram alguns erros na validação <br/><br/>'.$erroList;
		    //Utils_Helper::mensagens('add',$message);    
            $db->rollback();
        } catch (Database_Exception $e) {
            $msg = 'Houveram alguns erros na base <br/><br/>'.$e->getMessage();
            //Utils_Helper::mensagens('add',$message);
            $db->rollback();
        }

		header('Content-Type: application/json');
		echo json_encode(
			array(
				array('container' => '#content', 'type'=>'url', 'content'=> URL::base().'admin/collections/index/ajax'),
				array('type'=>'msg', 'content'=> $msg),
			)						
		);

        return false;
	}
	
	public function action_delete($id)
	{
		$this->auto_render = false;
		try 
		{            
			$projeto = ORM::factory('collection', $id);
			$projeto->delete();
			$msg = "coleção excluída com sucesso.";
			//Utils_Helper::mensagens('add',''); 
		} catch (ORM_Validation_Exception $e) {
			//Utils_Helper::mensagens('add',''); 
			$errors = $e->errors('models');
			$msg = "houveram alguns erros na exclusão dos dados.";
		}

		header('Content-Type: application/json');
		echo json_encode(array(
			'content' => URL::base().'admin/collections/index/ajax',				
			'msg' => $msg,
		));
		//Request::current()->redirect('admin/collections');
	}

	/*******************************************/
    public function getFiltros($ano){
    	$this->auto_render = false;
    	$viewFiltros = View::factory('admin/collections/filtros');
    	$viewFiltros->ano = $ano;

    	$filtros = Session::instance()->get('kaizen')['filtros'];

  		$viewFiltros->filter_segmento = array();
  		$viewFiltros->segmentoList = ORM::factory('segmento')->order_by('name', 'ASC')->find_all();

		foreach ($filtros as $key => $value) {
  			$viewFiltros->$key = json_decode($value);
  		}

  		return $viewFiltros;
    }

	public function action_getList($ano, $ajax = null){
		$this->auto_render = false;
		$view = View::factory('admin/collections/table');

		if(count($this->request->post('collection')) > '0' || Session::instance()->get('kaizen')['model'] != 'collection'){
			$kaizen_arr = Utils_Helper::setFilters($this->request->post(), $ano, "collection");
		}else{
			$kaizen_arr = Session::instance()->get('kaizen');	
		}
		Session::instance()->set('kaizen', $kaizen_arr);

  		$filtros = Session::instance()->get('kaizen')['filtros'];
  		
  		foreach ($filtros as $key => $value) {
  			$view->$key = json_decode($value);
  		}

		$query = ORM::factory('collection')->where('ano', '=', $ano);

		(isset($view->filter_segmento)) ? $query->where('segmento_id', 'IN', $view->filter_segmento) : '';
		(isset($view->filter_name)) ? $query->where('name', 'LIKE', '%'.$view->filter_name.'%') : '';
		
		$view->collectionsList = $query->order_by('name','ASC')->find_all();
		
		if($ajax != null){
			return $view;
		}else{
			header('Content-Type: application/json');
			echo json_encode(
				array(
					array('container' => '#esquerda', 'type'=>'html', 'content'=> json_encode($view->render())),
					array('container' => '#filtros', 'type'=>'html', 'content'=> json_encode($this->getFiltros($ano)->render())),
					
				)						
			);
	       
	        return false;
	    }
	}
	
	public function action_getListProject($ano){
		$this->auto_render = false;

		$view = View::factory('admin/collections/select');


		$collectionsArr = array();
		$collections = ORM::factory('collections_project')->where('project_id', '=', $this->request->query('project_id'))->find_all();
		foreach ($collections as $collection) {
			array_push($collectionsArr, $collection->collection_id);
		}
		$view->collectionsArr = $collectionsArr;

		$view->collectionsList = ORM::factory('collection')->where('ano', '=', $ano)->order_by('name','ASC')->find_all();
		echo $view;
	}
}