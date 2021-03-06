<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Admin_Collections extends Controller_Admin_Template {
 
	public $auth_required		= array('login', 'coordenador');
 	
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
            
	/*
	public function action_index($ajax = null)
	{	
		$view = View::factory('admin/collections/list')
			->bind('message', $message);
		
		if($ajax == null){
			$this->template->content = $view;             
		}else{
			$this->auto_render = false;
			header('Content-Type: application/json');
			echo json_encode(
				array(
					array('container' => '#content', 'type'=>'html', 'content'=> json_encode($view->render())),
					array('container' => '#tabs_content', 'type'=>'html', 'content'=> json_encode($this->action_getList(true)->render())),
					array('container' => '#filtros', 'type'=>'html', 'content'=> json_encode($this->getFiltros()->render())),
				)						
			);
	        return false;
		}   
	} 
	*/

	public function action_edit($id, $ajax = null)
    {       	      
    	$this->auto_render = false;
		$view = View::factory('admin/collections/create')
				->bind('errors', $errors)
				->bind('message', $message);
				
		$collection = ORM::factory('collection', $id);
		$view->collection = $collection;
		$view->collectionVO = $this->setVO('collection', $collection);
		$view->materiaList = ORM::factory('materia')->order_by('name', 'ASC')->find_all();
		$view->segmentoList = ORM::factory('segmento')->order_by('name', 'ASC')->find_all();
		$view->projectList = ORM::factory('project')->order_by('name', 'ASC')->find_all();//->where('status', '=','1')

		$view->teamList = ORM::factory('team')->find_all();
		$view->userList = ORM::factory('userinfo')->where('status', '=', '1')->order_by('nome', 'ASC')->find_all();
		$view->collection_users = DB::select('userInfo_id')->from('collections_userinfos')->where('collection_id', '=', $id)->execute()->as_array('userInfo_id');

		if($id != ""){
			$collection_workflow = ORM::factory('object')
									->where('fase', '=', '53')
									->where('collection_id' , '=', $id)
									->group_by('workflow_id')->find_all();

			$workflows_arr = array();
			foreach ($collection_workflow as $workflow_item) {
				array_push($workflows_arr, $workflow_item->workflow_id);
			}						
			if(count($workflows_arr) > 0){
				$view->workflows = DB::select('workflows.id', 'name, sum("days") days')->from('workflows')
					->join('workflows_status')->on('workflows.id', '=', 'workflows_status.workflow_id')
					->where('workflows.id', 'IN', $workflows_arr)
					->group_by('workflows.id')
					->as_object()->execute();


				$view->objectList = ORM::factory('object')->where('fase', '=', '53')->where('collection_id' , '=', $id)->find_all();	
			}		
		}

		if($ajax != null){
			return $view;
		}else{
			header('Content-Type: application/json');
			echo json_encode(
				array(
					array('container' => $this->request->post('container'), 'type'=>'html', 'content'=> json_encode($view->render())),
				)						
			);
        	return false;	
        }
	}

	public function action_salvar($id = null)
	{
		$this->auto_render = false;
		$db = Database::instance();
        $db->begin();

        $collection_id = $id;
        $msg_type = 'normal';
		
		try 
		{            
			$colecao = ORM::factory('collection', $id)->values($this->request->post(), array(
				'name',
				'op',
				'materia_id',
				'segmento_id',
				'project_id',
				'ano',
				'fechamento',
				'repositorio',
			));
			$colecao->save();

			$collection_id = $colecao->id;

			$team = DB::delete('collections_userinfos')->where('collection_id','=', $id)->execute();

			$team_users = $this->request->post('team');
			if($team_users != ""){
				foreach ($team_users as $key => $value) {
					if($value != ''){
						$user = ORM::factory('userinfo', $team_users[$key]);

						$collection_users = ORM::factory('collections_userinfo');
						$collection_users->collection_id = $colecao->id;	
						$collection_users->userInfo_id = $user->id;//$team_users[$key];	
						$collection_users->team_id = $user->team_id;
						$collection_users->save();
					}
				}	
			}

			$objects = $this->request->post('objects');
			if($objects != ""){
				$starts = $this->request->post('start');
				$ends = $this->request->post('end');
				foreach ($objects as $key => $value) {
					if($starts[$key] != '' && $ends[$key] != ''){
						$object = ORM::factory('object', $objects[$key]);
						$object->crono_date = $starts[$key];
						$object->planned_date = $ends[$key];	
						$object->save();

						//achar o status não iniciado
						$objectStatus_result = ORM::factory('objects_statu')
											->where('object_id', '=', $objects[$key])
											->where('status_id', '=', '1')->limit('1')->find_all(); 

						//cria o status "não iniciado" e habilita o OED para produção caso não haja.
						if(count($objectStatus_result) == 0){
							$objectStatus = ORM::factory('objects_statu');
						}else{
							$objectStatus = ORM::factory('objects_statu', $objectStatus_result[0]->id);
						}	

						if($objectStatus->crono_date == ''){
							$objectStatus->planned_date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $starts[$key])));
						}

						$objectStatus->status_id = '1';
						$objectStatus->object_id = $objects[$key];
						$objectStatus->crono_date = $starts[$key];
						$objectStatus->userInfo_id = $this->current_user->userInfos->id;		

						$date1 = date('Y-m-d', strtotime(str_replace('/', '-', $starts[$key])));
						$objectStatus->diff = Utils_Helper::dataDiff($date1, $objectStatus->planned_date);

						$objectStatus->save();
					}
				}
			}

			$db->commit();
			$msg = "coleção salva com sucesso.";
			
		} catch (ORM_Validation_Exception $e) {
            $errors = $e->errors('models');
			$erroList = '';
			foreach($errors as $erro){
				$erroList.= $erro.'<br/>';	
			}
			$msg_type = 'error';
            $msg = $erroList;
            $db->rollback();

            
        } catch (Database_Exception $e) {
        	$msg_type = 'error';
            $msg = 'Houveram alguns erros na base <br/><br/>'.$e->getMessage();
            $db->rollback();
        }


		header('Content-Type: application/json');
		echo json_encode(
			array(
				array('container' => '#tabs_content', 'type'=>'html', 'content'=> json_encode($this->action_getList(true)->render())),
				array('container' => '#direita', 'type'=>'html', 'content'=> json_encode($this->action_edit($collection_id, true)->render())),
				array('container' => $msg_type, 'type'=>'msg', 'content'=> $msg),
			)						
		);

        return false;
	}
	
	public function action_delete($id)
	{
		$this->auto_render = false;
		$db = Database::instance();
        $db->begin();

        $collection_id = $id;
        $msg_type = 'normal';
		
		try 
		{    
			if($this->request->post('collection_id') != ''){
				$new_collection = ORM::factory('collection', $this->request->post('collection_id'));
				DB::update('objects')->set(array('project_id' => $new_collection->project_id, 'collection_id' => $new_collection->id))->where('collection_id', '=', $id)->execute();
			}

			DB::delete('collections_userinfos')->where('collection_id','=', $id)->execute();
			DB::delete('collections')->where('id','=', $id)->execute();

			$db->commit();
			$msg = "coleção excluída com sucesso.";

		} catch (ORM_Validation_Exception $e) {
            $errors = $e->errors('models');
			$erroList = '';
			foreach($errors as $erro){
				$erroList.= $erro.'<br/>';	
			}
			$msg_type = 'error';
            $msg = $erroList;
            $db->rollback();
        } catch (Database_Exception $e) {
        	$msg_type = 'error';
            $msg = 'Houveram alguns erros na base <br/><br/>'.$e->getMessage();
            $db->rollback();
        }

		header('Content-Type: application/json');
		echo json_encode(
			array(
				array('container' => '#tabs_content', 'type'=>'html', 'content'=> json_encode($this->action_getList(true)->render())),
				array('container' => '#direita', 'type'=>'html', 'content'=> json_encode('')),
				array('container' => $msg_type, 'type'=>'msg', 'content'=> $msg),
			)						
		);

        return false;
	}

	public function action_deletePanel($id)
	{
		$this->auto_render = false;
		$view = View::factory('admin/collections/delete')
					->bind('errors', $errors)
					->bind('message', $message);

		$view->current_auth = $this->current_auth;

		$collection_objects = ORM::factory('object')->where('collection_id', '=', $id)->find_all();
		$view->total_objects = count($collection_objects);
		$view->delete_msg = Kohana::message('models/collection', 'delete');
		$view->collection = ORM::factory('collection', $id);
		$view->collectionList = ORM::factory('collection')
									->join('projects')->on('collections.project_id', '=', 'projects.id')
									->where('projects.status', '=', '1')
									->and_where('collections.id', '!=', $id)
									->order_by('collections.name', 'ASC')->find_all();

		echo $view;
	}

	/*******************************************/
    public function getFiltros(){
    	$this->auto_render = false;
    	$viewFiltros = View::factory('admin/collections/filtros');

    	$filtros = Session::instance()->get('kaizen')['filtros'];

  		$viewFiltros->filter_segmento = array();

  		/*
  		if(!isset($view->filter_ano)){
  			$viewFiltros->filter_ano = array(date('Y'));
  		}
  		*/

  		$viewFiltros->filter_materia = array();

  		$viewFiltros->segmentoList = ORM::factory('segmento')->order_by('name', 'ASC')->find_all();
  		$viewFiltros->anosList = ORM::factory('collection')->group_by('ano')->order_by('ano', 'DESC')->find_all();
  		$viewFiltros->materiasList = ORM::factory('materia')->order_by('name', 'ASC')->find_all();
  		$viewFiltros->projetosList = ORM::factory('project')->order_by('name', 'ASC')->find_all();


		foreach ($filtros as $key => $value) {
  			$viewFiltros->$key = json_decode($value);
  		}

  		return $viewFiltros;
    }

	public function action_getList($ajax = null){
		$this->auto_render = false;
		$view = View::factory('admin/collections/table');
		$view->delete_msg = Kohana::message('models/collection', 'delete');

		if(count($this->request->post('collection')) > '0' || Session::instance()->get('kaizen')['model'] != 'collection'){
			$kaizen_arr = Utils_Helper::setFilters($this->request->post(), '', "collection");
		}else{
			$kaizen_arr = Session::instance()->get('kaizen');	
		}
		Session::instance()->set('kaizen', $kaizen_arr);

  		$filtros = Session::instance()->get('kaizen')['filtros'];
  		
  		foreach ($filtros as $key => $value) {
  			$view->$key = json_decode($value);
  		}

		$query = ORM::factory('collection');

		(isset($view->filter_segmento)) ? $query->where('segmento_id', 'IN', $view->filter_segmento) : '';
		(isset($view->filter_ano)) ? $query->where('ano', 'IN', $view->filter_ano) : '';
		(isset($view->filter_materia)) ? $query->where('materia_id', 'IN', $view->filter_materia) : '';
		(isset($view->filter_name)) ? $query->where('name', 'LIKE', '%'.$view->filter_name.'%') : '';
		(isset($view->filter_projects)) ? $query->where('project_id', 'IN', $view->filter_projects) : '';
		
		$view->collectionsList = $query->order_by('ano','DESC')->order_by('name','ASC')->find_all();
		
		if($ajax != null){
			return $view;
		}else{
			header('Content-Type: application/json');
			echo json_encode(
				array(
					array('container' => '#tabs_content', 'type'=>'html', 'content'=> json_encode($view->render())),
					array('container' => '#filtros', 'type'=>'html', 'content'=> json_encode($this->getFiltros()->render())),
					array('container' => '#direita', 'type'=>'html', 'content'=> json_encode('')),
				)						
			);
	       
	        return false;
	    }
	}

	public function action_getCollectionList($project_id){
		$this->auto_render = false;
		$view = View::factory('admin/collections/select');

		$query = ORM::factory('collection');

		if($this->request->post('ano') != ''){
			$query->where('ano', '=', $this->request->post('ano'));
		}

		if($this->request->post('segmento') != ''){
			$query->where('segmento_id', '=', $this->request->post('segmento'));
		}

		$view->collectionsArr = DB::select('collection_id')->from('collections_projects')->where('project_id', '=', $project_id)->execute()->as_array('collection_id');

		/*
		$collectionsArr = array();
		$collections = ORM::factory('collections_project')->where('project_id', '=', $project_id)->find_all();
		foreach ($collections as $collection) {
			array_push($collectionsArr, $collection->collection_id);
		}
		$view->collectionsArr = $collectionsArr;
		*/

		$view->collectionsList = $query->find_all();

		echo $view->render();
	}
}