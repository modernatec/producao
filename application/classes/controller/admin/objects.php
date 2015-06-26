<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Admin_Objects extends Controller_Admin_Template {
 
	public $auth_required = array('login'); //Auth is required to access this controller
 	
	public $secure_actions = array(
                                    'create' => array('login', 'assistente 2'),
                                    'edit' => array('login', 'assistente 2'),
                                    'delete' => array('login', 'coordenador'),
                                 );
                                 
    const ITENS_POR_PAGINA = 20;
	
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);
	}

	/*
	* função apenas de transição, remover
	*/
	public function action_geraPastas(){
		$this->auto_render = false;  

		$db = Database::instance();
        $db->begin();
		
		try 
		{     
			$objects = ORM::factory('object')->where('fase', '=', '1')->find_all();
			foreach ($objects as $key => $object) {
				$projeto = ORM::factory('project', $object->project_id);
				$obj = ORM::factory('object', $object->id);
				$pastaObjeto = Utils_Helper::criaPasta('public/upload/projetos/'.$projeto->segmento->pasta.'/'.$projeto->pasta.'/', $object->pasta , trim($object->taxonomia));
				$obj->pasta = $pastaObjeto;	
				$obj->save();
				echo $obj->taxonomia.'<br/>';
			}
			$db->commit();

		}  catch (ORM_Validation_Exception $e) {
            $errors = $e->errors('models');
			$erroList = '';
			foreach($errors as $erro){
				$erroList.= $erro.'<br/>';	
			}
            $msg = 'Houveram alguns erros na validação <br/><br/>'.$erroList;
            $db->rollback();
        } catch (Database_Exception $e) {
            $msg = 'Houveram alguns erros na base <br/><br/>'.$e->getMessage();
            $db->rollback();
        }

		echo 'fim';
	}
	        
	public function action_index($ajax = null)
	{	
		$view = View::factory('admin/objects/list')
			->bind('message', $message);

		$view->projectList = ORM::factory('project')->where('status', '=', '1')->order_by('name', 'ASC')->find_all(); 

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
		}           
	} 

	public function action_edit($id)
    {    
    	$this->auto_render = false;       
		$view = View::factory('admin/objects/create')
			->bind('errors', $errors)
			->bind('message', $message)
			->set('values', $this->request->post());

		$objeto = ORM::factory('object', $id);
        $view->objVO = $this->setVO('object', $objeto);

        if($objeto->country_id == ''){
        	$view->objVO["country_id"] = 1; //Brasil
        }
                
		$view->workflowList = ORM::factory('workflow')->order_by('name', 'ASC')->find_all();              
		$view->typeObjects = ORM::factory('typeobject')->order_by('name', 'ASC')->find_all();
        $view->countries = ORM::factory('country')->order_by('name', 'ASC')->find_all();
        $view->suppliers = ORM::factory('supplier')->where('team_id', '=', '1')->order_by('order', 'ASC')->order_by('empresa', 'ASC')->find_all();        
        $view->suppliers_arte = ORM::factory('supplier')->where('team_id', '=', '3')->order_by('order', 'ASC')->order_by('empresa', 'ASC')->find_all();        
        $view->collections = ORM::factory('collection')->join('collections_projects')->on('collections_projects.collection_id', '=', 'collections.id')->where('collections_projects.project_id', '=', $objeto->project_id)->order_by('name', 'ASC')->find_all();  
        $view->formats = ORM::factory('format')->order_by('name', 'ASC')->find_all(); 
        $view->projectList = ORM::factory('project')->where('status', '=', '1')->order_by('name', 'ASC')->find_all(); 
        $view->repoList = ORM::factory('repositorio')->order_by('name', 'DESC')->find_all(); 

        $objects_repo = ORM::factory('objects_repositorio')->where('object_id','=', $id)->find_all();
		$repo_arr = array();
		foreach ($objects_repo as $value) {
			array_push($repo_arr, $value->repositorio_id);
		}
		$view->repo_arr = $repo_arr;        
                
        header('Content-Type: application/json');
		echo json_encode(
			array(
				array('container' => $this->request->post('container'), 'type'=>'html', 'content'=> json_encode($view->render())),
			)						
		);
        return false;
	}

	public function action_view($id, $ajax = null)
    {       
    	$this->auto_render = false;
        $view = View::factory('admin/objects/view')
            ->bind('errors', $errors)
            ->bind('message', $message);

		$object = ORM::factory('object', $id);
        $view->obj = $object;   
        $view->user = $this->current_user->userInfos;                          
		
        //ALTERAR APOS INCLUSAO DAS TASKS NO STATUS??
        $view->objects_status = ORM::factory('objects_statu')->where('object_id', '=', $id)->order_by('created_at', 'DESC')->find_all();
		$view->last_status = ORM::factory('objects_statu')->where('object_id', '=', $id)->order_by('id', 'DESC')->limit('1')->find();

		$view->taskList = ORM::factory('task')
							->join('tags_teams', 'INNER')->on('tasks.tag_id', '=', 'tags_teams.tag_id')
							->where('tasks.object_id', '=', $id)
							->where('tags_teams.team_id', '=', $this->current_user->userInfos->team_id)
							->order_by('tasks.id', 'desc')->find_all();

 		$view->current_auth = $this->current_auth;

 		//ini_set('upload_max_filesize', '100M');
 		//ini_set('post_max_size', '100M');

 		if($ajax != null){
 			return $view;
 		}else{
	        header('Content-Type: application/json');		
			echo json_encode(
				array(
					array('container' => '#direita', 'type'=>'html', 'content'=> json_encode($view->render())),
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
		
		$object = ORM::factory('object', $id);
		try 
		{            
			$object->values($this->request->post(), array( 
                    'title', 
                    'taxonomia', 
                    'typeobject_id', 
                    'project_id',
                    'collection_id', 
                    'supplier_id', 
                    'audiosupplier_id',
                    'country_id',
                    'format_id',
                    'reaproveitamento', 
                    'interatividade',
                    'transcricao',
                    'pnld',
                    'fase', 
                    'obs', 
                    'uni', 
                    'cap', 
                    'pagina',
                    'status',
                    'tamanho',
                    'duracao',
                    'cessao',
                    'sinopse',
                    'taxonomia_reap',
                    'arq_aberto',
                    'locutor',
                    'ilustrador',
                    'keywords',

                     ));

			
			if($this->request->post('taxonomia_reap') != ""){
				$object_source = ORM::factory('object')->where('taxonomia', '=', $this->request->post('taxonomia_reap'))->find();				
				$object->object_id = $object_source->id;	
			}else{
				$object->object_id = null;
			}

			$projeto = ORM::factory('project', $this->request->post('project_id'));

			$pastaObjeto = Utils_Helper::criaPasta('public/upload/projetos/'.$projeto->segmento->pasta.'/'.$projeto->pasta.'/', $object->pasta , trim($this->request->post('taxonomia')));
			$object->pasta = $pastaObjeto;		
							
			
			$object->save();

			if(is_null($id) || $id == ""){
				$objectStatus = ORM::factory('objects_statu');
		        $objectStatus->object_id = $object->id;
		        $objectStatus->status_id = '1'; //não iniciado
		        //$objectStatus->crono_date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $this->request->post('ini_date'))));
		        //$objectStatus->planned_date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $this->request->post('ini_date'))));
				$objectStatus->userInfo_id = $this->current_user->userInfos->id;	
				$objectStatus->save();
			}
			
			$delete_repos = DB::delete('objects_repositorios')->where('object_id','=', $id)->execute();

			$repos = $this->request->post('repositorio');

			if($repos != ""){
				foreach ($repos as $key => $value) {
					$repositorio = ORM::factory('objects_repositorio');
					$repositorio->object_id = $object->id;	
					$repositorio->repositorio_id = $repos[$key];	
					$repositorio->save();
				}	
			}

			$msg = 'Objeto salvo com sucesso.';
			$db->commit();
		}  catch (ORM_Validation_Exception $e) {
            $errors = $e->errors('models');
			$erroList = '';
			foreach($errors as $erro){
				$erroList.= $erro.'<br/>';	
			}
            $msg = 'Houveram alguns erros na validação <br/><br/>'.$erroList;
            $db->rollback();
        } catch (Database_Exception $e) {
            $msg = 'Houveram alguns erros na base <br/><br/>'.$e->getMessage();
            $db->rollback();
        }
        
        header('Content-Type: application/json');
		echo json_encode(
			array(
				array('container' => '#direita', 'type'=>'html', 'content'=>  json_encode($this->action_view($object->id, true)->render())),
				array('type'=>'msg', 'content'=> $msg),
			)						
		);
       
        return false;
	}

    /********************************/
    public function action_getCollections($project_id){
    	$this->auto_render = false;
    	$query = ORM::factory('Collections_Project')->where('project_id', '=', $project_id)->find_all();

    	$result = array('dados' => array());
    	foreach ($query as $collection) {
    		array_push($result['dados'], array('id' => $collection->collection_id, 'display' => $collection->collection->name));
    	}

    	print json_encode($result);
    }

    public function action_getObjects($project_id, $ajax = null){
    	//$this->startProfilling();

    	$project_id = ($project_id != "") ? $project_id : Session::instance()->get('kaizen')['parameters'];

		$this->auto_render = false;
		$view = View::factory('admin/objects/table');
		$viewFiltros = View::factory('admin/objects/filtros');
		
		$view->project_id = $project_id;
		$viewFiltros->project_id = $project_id;

		//diferente de "finalizado" e "nao iniciado"
		$status_init = ORM::factory('statu')
			->where('type', '=', 'object')
			->where('id', 'NOT IN', array('1', '8'))->find_all(); 
		
		$status_arr = array();
		foreach ($status_init as $status) {
			array_push($status_arr, $status->id);
		}
		
		if(count($this->request->post('project_id')) > '0' || Session::instance()->get('kaizen')['model'] != 'objects'){
			$kaizen_arr = Utils_Helper::setFilters($this->request->post(), $project_id, "objects");
		}else{
			$kaizen_arr = Session::instance()->get('kaizen');
		}

  		Session::instance()->set('kaizen', $kaizen_arr);

  		$filtros = Session::instance()->get('kaizen')['filtros'];
  		foreach ($filtros as $key => $value) {
  			$view->$key = json_decode($value);
  			$viewFiltros->$key = json_decode($value);
  		}

  		if(!isset($view->filter_status)){
  			$view->filter_status = json_decode(json_encode($status_arr));
  			$viewFiltros->filter_status = json_decode(json_encode($status_arr));
  		}

		$query = ORM::factory('objectStatu')->where('fase', '=', '1');

		/***Filtros***/
		(isset($view->filter_tipo)) ? $query->where('typeobject_id', 'IN', $view->filter_tipo) : '';
		(isset($view->filter_status)) ? $query->where('objectStatus.status_id', 'IN', $view->filter_status) : '';
		(isset($view->filter_collection)) ? $query->where('collection_id', 'IN', $view->filter_collection ) : '';
		(isset($view->filter_supplier)) ? $query->where('supplier_id', 'IN', $view->filter_supplier) : '';
		(isset($view->filter_origem)) ? $query->where('reaproveitamento', 'IN', $view->filter_origem) : '';
		(isset($view->filter_materia)) ? $query->where('materia_id', 'IN', $view->filter_materia) : '';
		(isset($view->filter_taxonomia)) ? $query->where_open()->where('taxonomia', 'LIKE', '%'.$view->filter_taxonomia.'%')->or_where('title', 'LIKE', '%'.$view->filter_taxonomia.'%')->where_close() : '';
		
		$view->objectsList = $query->where('project_id', '=', $project_id)
			->order_by('retorno','ASC')->order_by('taxonomia', 'ASC')->find_all();
		
		
		/****Filtros*****/

		$typeObjectsList = array();
		$typeObjectsList_arr = array();
		$typeObjectsList_index = array();

		$statusList = array();
		$statusList_arr = array();
		$statusList_index = array();

		$collectionList = array();
		$collectionList_arr = array();
		$collectionList_index = array();

		$suppliersList = array();
		$suppliersList_arr = array();
		$suppliersList_index = array();

		$materiasList = array();
		$materiasList_arr = array();
		$materiasList_index = array();

		$query_filters = DB::select('*')->from('objectStatus')
							->where('fase', '=', '1')
							->where('project_id', '=', $project_id)
							->execute();

		//->where('collection_id', 'IN', DB::select('collection_id')->from('collections_projects')
							//->where('project_id', '=', $project_id))

		foreach ($query_filters as $object) {
			array_push($typeObjectsList_arr, array('typeobject_id' => $object['typeobject_id'], 'typeobject_name' => $object['typeobject_name']));
			array_push($typeObjectsList_index, $object['typeobject_name']);

			array_push($statusList_arr, array('status_id' => $object['status_id'], 'statu_status' => $object['statu_status']));
			array_push($statusList_index, $object['statu_status']);

			array_push($collectionList_arr, array('collection_id' => $object['collection_id'], 'collection_name' => $object['collection_name']));
			array_push($collectionList_index, $object['collection_name']);

			array_push($suppliersList_arr, array('supplier_id' => $object['supplier_id'], 'supplier_empresa' => $object['supplier_empresa']));
			array_push($suppliersList_index, $object['supplier_empresa']);

			array_push($materiasList_arr, array('materia_id' => $object['materia_id'], 'materia_name' => $object['materia_name']));
			array_push($materiasList_index, $object['materia_name']);
		}

		array_multisort($typeObjectsList_index, SORT_ASC, SORT_STRING, $typeObjectsList_arr);
		array_multisort($statusList_index, SORT_ASC, SORT_STRING, $statusList_arr);
		array_multisort($collectionList_index, SORT_ASC, SORT_STRING, $collectionList_arr);
		array_multisort($suppliersList_index, SORT_ASC, SORT_STRING, $suppliersList_arr);
		array_multisort($materiasList_index, SORT_ASC, SORT_STRING, $materiasList_arr);

		foreach ($typeObjectsList_arr as $typeObject) {
			array_push($typeObjectsList, json_encode($typeObject));
		}

		foreach ($statusList_arr as $status) {
			array_push($statusList, json_encode($status));
		}

		foreach ($collectionList_arr as $collection) {
			array_push($collectionList, json_encode($collection));
		}

		foreach ($suppliersList_arr as $supplier) {
			array_push($suppliersList, json_encode($supplier));
		}

		foreach ($materiasList_arr as $materia) {
			array_push($materiasList, json_encode($materia));
		}

		$view->typeObjectsList = array_unique($typeObjectsList);
		$view->statusList = array_unique($statusList);
		$view->collectionList = array_unique($collectionList);
		$view->suppliersList = array_unique($suppliersList);
		$view->materiasList = array_unique($materiasList);

		$viewFiltros->typeObjectsList = array_unique($typeObjectsList);
		$viewFiltros->statusList = array_unique($statusList);
		$viewFiltros->collectionList = array_unique($collectionList);
		$viewFiltros->suppliersList = array_unique($suppliersList);
		$viewFiltros->materiasList = array_unique($materiasList);

		//$this->endProfilling();
		
		if($ajax != null){
			return $view;
		}else{
			header('Content-Type: application/json');
			echo json_encode(
				array(
					array('container' => '#tabs_content', 'type'=>'html', 'content'=> json_encode($view->render())),
					array('container' => '#filtros', 'type'=>'html', 'content'=> json_encode($viewFiltros->render())),
				)						
			);
	       
	        return false;
	    }
	} 

	public function action_upload($object_id){
        $this->auto_render = false;
        // A list of permitted file extensions
        $allowed = array('.zip', 'zip');

        if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){
            $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            if(!in_array(strtolower($extension), $allowed)){
                echo '1';
                exit();
            }



            $object = ORM::factory('object', $object_id);
            
            $file_path = 'public/upload/projetos/'.$object->project->segmento->pasta.'/'.$object->project->pasta.'/'.$object->pasta.'/';
            //Utils_Helper::rrmdir($file_path);

            $file = $file_path.$_FILES['file']['name'];

            if(move_uploaded_file($_FILES['file']['tmp_name'], $file)){
                chmod($file, 0777);

				// get the absolute path to $file
				$path = pathinfo(realpath($file), PATHINFO_DIRNAME);

				$zip = new ZipArchive;
				$res = $zip->open($file);
				if ($res === TRUE) {
					// extract it to the path we determined above
					$zip->extractTo($path);
					$zip->close();
					unlink($file);	

					$object->uploaded = '1';
					$object->save();
					//echo $file;
				} else {
					echo '0';
				}

                
                exit();
            }
        }else{
            echo '0';
            exit();
        }
        
    }   


	/****
	*
	* Gerencia Object Status
	*
	*******/ 
	public function action_updateForm($id){
		$this->auto_render = false;
		$view = View::factory('admin/objects_status/edit');

		$view->bind('errors', $errors)
			->bind('message', $message);

		$objStatus = ORM::factory('objects_statu', $id);
		$arr_objstatus = $this->setVO('objects_statu', $objStatus);

		$view->current_auth = $this->current_auth;

		$object_id = $objStatus->object_id;
		if($id == ""){
			$object_id = $this->request->query('object_id');
			$arr_objstatus['object_id'] = $object_id;
		}	

		$object = ORM::factory('object', $object_id);

		$query = ORM::factory('statu')
		->join('status_teams', 'INNER')->on('status.id', '=', 'status_teams.status_id')
		->join('workflows_status', 'INNER')->on('status.id', '=', 'workflows_status.status_id');

		if($this->current_auth != 'admin'){
			$query->where('status_teams.team_id', '=', $this->current_user->userInfos->team_id);
		}

		$view->statusList = $query->where('workflows_status.workflow_id', '=', $object->workflow_id)->where('type', '=', 'object')->group_by('status')->order_by('order', 'ASC')->find_all();
		
		
		$object_status = ORM::factory('objects_statu')->where('object_id', '=', $object_id)->order_by('created_at', 'DESC')->find();  

        $query = ORM::factory('tag')
		->join('tags_teams', 'INNER')->on('tags.id', '=', 'tags_teams.tag_id')
		->join('workflows_status_tags', 'INNER')->on('tags.id', '=', 'workflows_status_tags.tag_id');

		if($this->current_auth != 'admin'){
			$query->where('tags_teams.team_id', '=', $this->current_user->userInfos->team_id);
		}

		$tagList = $query->where('workflows_status_tags.workflow_id', '=', $object->workflow_id)->where('workflows_status_tags.status_id', '=', $object_status->status_id)->where('type', '=', 'task')->group_by('tags.id')->order_by('workflows_status_tags.order', 'ASC')->find_all(); 

		$tag_arr = array();
		$i = 0;
		foreach ($tagList as $key => $tag) {
			$tag_arr[$i][$key] = $tag;

			if($tag->sync == '0'){
				$i++;
			}
		}
		$view->tag_arr = $tag_arr;
		

		$view->obj = $object;			

		$view->objVO = $arr_objstatus;

		echo $view;
	}

	public function action_updateStatus($id = null){
		$this->auto_render = false;
		if (HTTP_Request::POST == $this->request->method()) 
		{ 

			$db = Database::instance();
	        $db->begin();

			$object_status = ORM::factory('objects_statu', $id);
			try 
			{ 
				if(empty($id)){
					$last_status = ORM::factory('objects_statu')->where('object_id', '=', $this->request->post('object_id'))->order_by('id', 'DESC')->limit('1')->find();
					$last_status->delivered_date = date('Y-m-d', strtotime("now"));

					$last_status->diff = Utils_Helper::dataDiff($last_status->delivered_date, $last_status->planned_date);
					$last_status->save();
				}

				$object_status->values($this->request->post(), array( 
		                    'object_id', 
		                    'status_id',
		                    'prova',
		                    'description',
		                    'crono_date',
							));

				if(empty($id)){
					$object_status->planned_date = $this->request->post('crono_date');				
				}

				$date1 = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->post('crono_date'))));
				$object_status->diff = Utils_Helper::dataDiff($date1, $object_status->planned_date);
				$object_status->userInfo_id = (empty($id)) ? $this->current_user->userInfos->id : $object_status->userInfo_id;					
				$object_status->save();				

				
				if($object_status->status->tag_id != '0'){
					
					$tag = ORM::factory('tag', $object_status->status->tag_id);

		            $new_task = ORM::factory('task');
	            	$new_task->object_id = $object_status->object_id;
	            	$new_task->object_status_id = $object_status->id;
	            	$new_task->tag_id = $tag->id;
	            	$new_task->team_id = $this->current_user->userInfos->team_id;
	            	$new_task->crono_date = Controller_Admin_Feriados::getNextWorkDay($tag->days);
	            	$new_task->planned_date = $new_task->crono_date;

	            	//$new_task->topic = '1';
	            	//$new_task->description = $description;
	            	
	            	switch ($object_status->status->to) {
	            		case '1':
	            			/*
	            			* busca usuário do time, responsável pela coleção
	            			*/
	            			$object = ORM::factory('object', $object_status->object_id);

	            			$user_collection = ORM::factory('collections_userinfo')
	            								->where('collection_id', '=', $object->collection_id)
	            								->and_where('team_id', '=', $object_status->status->team_id)->find();

	            			if($user_collection->userInfo_id != ''){
		            			$task_to = $user_collection->userInfo_id;
		            		}else{
		            			$task_to = '0';
		            		}

	            			break;
	            		case '2':
	            			/*
							* fazer buscar usuário do time, menos atarefado
							* verificar se vale a pena - coordenadores sao usuários tbm!
							* SELECT 
									u.nome, 
									(SELECT COUNT(*) FROM moderna_tasks WHERE task_to = u.id AND ended = '0') AS t,
									u.team_id
								FROM moderna_userinfos u
								WHERE u.team_id = '1' 
	            			*/

	            			$task_to = '0';
	            			break;
	            		default:
	            			$task_to = '0';
	            			break;
	            	}
	            	

	            	$new_task->task_to = $task_to;
	            	$new_task->status_id = '5'; //aberto
	            	$new_task->userInfo_id = $this->current_user->userInfos->id;
		            $new_task->save(); 
		            
		        }
		        
				

				$db->commit();
				$msg = 'status salvo com sucesso.';
			} catch (ORM_Validation_Exception $e) {
	            $errors = $e->errors('models');
				$erroList = '';
				foreach($errors as $erro){
					$erroList.= $erro.'<br/>';	
				}
	            $db->rollback();
	            $msg = 'houveram alguns erros na validação <br/><br/>'.$erroList;
	        } catch (Database_Exception $e) {
	            $db->rollback();
	            $msg = 'houveram alguns erros na base <br/><br/>'.$e->getMessage();
	        }

	        $from = strpos($this->request->post('from'), 'objects');
	        header('Content-Type: application/json');

	        /**ERRO??***/
	        
	        if($from !== false){
				echo json_encode(
					array(
						array('container' => '#direita', 'type'=>'html', 'content'=> json_encode($this->action_view($object_status->object_id, true)->render())),
						array('container' => '#tabs_content', 'type'=>'html', 'content'=> json_encode($this->action_getObjects($object_status->object->project_id, true)->render())),
						array('type'=>'msg', 'content'=> $msg),
					)						
				);	
	        }else{
				echo json_encode(
					array(
						array('container' => '#direita', 'type'=>'html', 'content'=>  json_encode($this->action_view($object_status->object_id, true)->render())),
						array('type'=>'msg', 'content'=> $msg),
					)						
				);		       
	        }

	        return false;	
	    }
	}


	public function action_deleteStatus($id){   
		$this->auto_render = false; 
		$db = Database::instance();
        $db->begin();
		
		$object_status = ORM::factory('objects_statu', $id);
		$object_id = $object_status->object_id;
		$project_id = $object_status->object->project_id;

		try {  
			$tasks = ORM::factory('task')->where('object_status_id', '=', $id)->find_all();
			foreach($tasks as $task){
				$task_status = ORM::factory('tasks_statu')->where('task_id', '=', $task->id)->find_all();
				foreach($task_status as $status){
					$status->delete();
				}

				$task->delete();
			}

			$object_status->delete();

            $db->commit();

            $msg = "Status excluído com sucesso."; 
        } catch (ORM_Validation_Exception $e) {
            $errors = $e->errors('models');
			$erroList = '';
			foreach($errors as $erro){
				$erroList.= $erro.'<br/>';	
			}
            $msg = 'Houveram alguns erros na validação <br/><br/>'.$erroList;
            $db->rollback();
        } catch (Database_Exception $e) {
            $msg = 'Houveram alguns erros na base <br/><br/>'.$e->getMessage();
            $db->rollback();
        }

        header('Content-Type: application/json');
        
    	echo json_encode(
			array(
				array('container' => '#direita', 'type'=>'html', 'content'=> json_encode($this->action_view($object_id, true)->render())),
				array('container' => '#tabs_content', 'type'=>'html', 'content'=> json_encode($this->action_getObjects($project_id, true)->render())),
				array('type'=>'msg', 'content'=> $msg),
			)						
		);	

        return false;		        
	} 
}