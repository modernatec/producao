<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Admin_Tasks extends Controller_Admin_Template {
 
	public $auth_required		= array('login'); 
	public $secure_actions     	= array(
										'create' => array('login','coordenador'),
										'delete' => array('login','assistente 2'),);
					 
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);                
	}

	public function action_index($ajax = null)
	{	
		$view = View::factory('admin/tasks/list');
		$view->userInfo_id = $this->current_user->userInfos->id;
        //$query = ORM::factory('taskView')
        //						->join('userInfos', 'INNER')->on('userInfos.id', '=', 'task_to')
        //						->where('ended', '=', '0');
		
		//if($this->current_auth != 'admin'){
		//	$query->where('taskViews.team_id', '=', $this->current_user->userInfos->team_id);
		//}
        //$view->has_task = $query->where('task_to', '!=', '0')->group_by('task_to')->order_by('nome', 'ASC')->find_all();
		if(is_array($this->request->post('post'))){
	        if(array_key_exists('to', $this->request->post('post'))){
	        	$tasks_of = ORM::factory('userInfo', $this->request->post('post')['to']);	
	        	$nome = explode(" ", $tasks_of->nome);
	        	
	        	$view->title = "tarefas - ".$nome[0];
	        	$view->data_post = json_encode(array('to' => $tasks_of->id));
	        }elseif(array_key_exists('team', $this->request->post('post'))){
	        	
	        	$team_id = $this->request->post('post')['team'];
	        	
	        	$team = ORM::factory('team', $team_id);
	        	$name = explode(" ", $team->name);

	        	$view->title = "tarefas - ".$name[0];
	        	$view->data_post = json_encode(array('team' => $team->id));
	        }
	    }else{
	    	$team_id = $this->current_user->userInfos->team_id;

	    	$team = ORM::factory('team', $team_id);
	        $name = explode(" ", $team->name);

        	$view->title = "tarefas - ".$name[0];
        	$view->data_post = json_encode(array('team' => $team->id)); //"?status=".json_encode(array("5")).'&team='.$team->id;
	    }

		$view->current_auth = $this->current_auth;	
	  	
	  	/*alert de nova tarefa*/
	  	//$view->update = false;
  		//if(Session::instance()->get('total_tarefas') < $view->totalTasks){
  		//	$view->update = true;	  			
  		//}
	  	//Session::instance()->set('total_tarefas', $view->totalTasks);

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

	/**
	**Reordena as tarefas por drag. 	
	**/
	public function action_reorder(){
		$this->auto_render = false;
		if (HTTP_Request::POST == $this->request->method()) 
		{
			$i = '0';
			foreach($this->request->post('item') as $task_id){
				$task = ORM::factory('task', $task_id);
				$task->order = $i;
				$task->save();

				$i++;
			}
		}
	}
    
	/**
	**Formulario popup. 	
	**/
   	public function action_update($id){
		$this->auto_render = false;
		$view = View::factory('admin/tasks/edit');

		$view->bind('errors', $errors)
			->bind('message', $message);
		
		$task = ORM::factory('task', $id);
		$taskVO = $this->setVO('task', $task);
		$view->current_auth = $this->current_auth;


		$object_status_id = $task->object_status_id;
		$object_id = $task->object_id;
		$view->title = 'Editar tarefa';

		if($id == ""){
			$taskVO['object_id'] = $this->request->post('object_id');
			$taskVO['object_status_id'] = $this->request->post('status_id');
			
			$object_status_id = $this->request->post('status_id');
			$object_id = $this->request->post('object_id');
			$view->title = 'Nova tarefa';
		}		

		$view->taskVO = $taskVO;

		$object_status = ORM::factory('objects_statu', $object_status_id);

		$object = ORM::factory('object', $object_id);

		$query_team =  ORM::factory('userInfo')->where('status', '=', '1');
		
		$view->teams = ORM::factory('team')->order_by('name', 'ASC')->find_all();
		$view->teamList = $query_team->order_by('nome', 'ASC')->find_all(); 

		$query = ORM::factory('workflows_status_tag')
				->join('tags', 'INNER')->on('tags.id', '=', 'workflows_status_tags.tag_id');

		if($this->current_auth != 'admin'){
			$query->join('tags_teams', 'INNER')->on('tags.id', '=', 'tags_teams.tag_id');
			$query->where('tags_teams.team_id', '=', $this->current_user->userInfos->team_id);
		}

		$view->status_tagList = $query->where('workflows_status_tags.workflow_id', '=', $object->workflow_id)->where('workflows_status_tags.status_id', '=', $object_status->status_id)->where('type', '=', 'task')->group_by('tags.id')->order_by('workflows_status_tags.order', 'ASC')->find_all(); 
		
		$view_sequence = View::factory('admin/tasks/sequence');
		$view_sequence->status_tagList = $view->status_tagList;
		$view->sequence = $view_sequence;

		echo $view;
	}

	/**repetição??**/
	public function action_getSequenceList($workflow_id){
		$this->auto_render = false;
		$view = View::factory('admin/tasks/sequence');

		$status_id = $this->request->post('status_id');

		$query = ORM::factory('workflows_status_tag')
				->join('tags', 'INNER')->on('tags.id', '=', 'workflows_status_tags.tag_id');

		if($this->current_auth != 'admin'){
			$query->join('tags_teams', 'INNER')->on('tags.id', '=', 'tags_teams.tag_id');
			$query->where('tags_teams.team_id', '=', $this->current_user->userInfos->team_id);
		}

		$view->status_tagList = $query->where('workflows_status_tags.workflow_id', '=', $workflow_id)->where('workflows_status_tags.status_id', '=', $status_id)->where('type', '=', 'task')->group_by('tags.id')->order_by('workflows_status_tags.order', 'ASC')->find_all(); 

		echo $view;
	}



	/**
	**Formulario popup. 	
	**/
   	public function action_endtask($id){
		$this->auto_render = false;
		$view = View::factory('admin/tasks/end');

		$view->bind('errors', $errors)
			->bind('message', $message);
		
		$view->task = ORM::factory('task', $id);

		echo $view;
	}

	public function action_updateReply($id){
		$this->auto_render = false;
		$view = View::factory('admin/tasks/reply');

		$view->bind('errors', $errors)
			->bind('message', $message);
		
		$task = ORM::factory('tasks_statu', $id);
		$view->taskVO = $this->setVO('tasks_statu', $task);

		echo $view;
	}
	
	public function action_salvar($id = null)
	{
		$this->auto_render = false;
		if (HTTP_Request::POST == $this->request->method()){  
	        $db = Database::instance();
	        $db->begin();
			
			$task = ORM::factory('task', $id);
			$object_id = $task->object_id;
			$project_id = $task->object->project_id;
			
			try {  						
				$task->values($this->request->post(), array(
					'object_id',
					'object_status_id',
					'tag_id',
					'topic',
					'crono_date',
					'description',
					'task_to',
				)); 
				
				if(empty($id)){				
					/**para não atualizar o criador da tarefa no update**/
					$task->planned_date = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->post('crono_date'))));
					$task->userInfo_id = $this->current_user->userInfos->id;
					$task->team_id = $this->current_user->userInfos->team_id;
					$task->status_id = '5';
	            }
	            $date1 = date('Y-m-d', strtotime(str_replace('/', '-', $this->request->post('crono_date'))));
				$task->diff = Utils_Helper::dataDiff($date1, $task->planned_date);

	            $task->save();  
	            $object_id = $task->object_id;


	            $db->commit();

	            $msg = "Tarefa salva com sucesso."; 
	        } catch (ORM_Validation_Exception $e) {
	            $errors = $e->errors('models');
				$erroList = '';
				foreach($errors as $erro){
					$erroList.= $erro.'<br/>';	
				}
	            $db->rollback();
	            $msg = 'Houveram alguns erros na validação <br/><br/>'.$erroList;
	        } catch (Database_Exception $e) {
	            $db->rollback();
	            $msg = 'Houveram alguns erros na base <br/><br/>'.$e->getMessage();
	        }

			header('Content-Type: application/json');
			echo json_encode(
				array(
					array('container' => '#direita', 'type'=>'url', 'content'=> URL::base().'admin/objects/view/'.$object_id),
					//array('container' => '#tabs_content', 'type'=>'url', 'content'=> URL::base().'admin/objects/getObjects/'.$project_id),
					array('type'=>'msg', 'content'=> $msg),

				)						
			);
	        return false;
	    }
    }
	
	public function action_delete($id){    
		$this->auto_render = false;
		$db = Database::instance();
        $db->begin();
		
		$task = ORM::factory('task', $id);
		$object_id = $task->object_id;
		$project_id = $task->object->project_id;

		try {  	
			DB::delete('tasks_status')->where('task_id', '=', $id)->execute();
			$task->delete();

            $db->commit();

            $msg = "Tarefa excluída com sucesso."; 
        } catch (ORM_Validation_Exception $e) {
            $errors = $e->errors('models');
			$erroList = '';
			foreach($errors as $erro){
				$erroList.= $erro.'<br/>';	
			}

            $db->rollback();
            $msg = 'Houveram alguns erros na validação <br/><br/>'.$erroList;
        } catch (Database_Exception $e) {
            $db->rollback();
            $msg = 'Houveram alguns erros na base <br/><br/>'.$e->getMessage();
        }
	
		header('Content-Type: application/json');
		echo json_encode(
			array(
				array('container' => '#direita', 'type'=>'url', 'content'=> URL::base().'admin/objects/view/'.$object_id),
				//array('container' => '#tabs_content', 'type'=>'url', 'content'=> URL::base().'admin/objects/getObjects/'.$project_id),
				array('type'=>'msg', 'content'=> $msg),
			)						
		);
	    
	    return false;        
	}

    /********************************/
    public function action_getTasks(){
        $this->auto_render = false;
        $view = View::factory('admin/tasks/table');
        $view->current_auth = $this->current_auth;

        //$this->startProfilling();

        $query = ORM::factory('task')->where('ended', '=', '0');

        /***Filtros***/
        $status_arr = array('5', '6');
        if(is_array($this->request->post('post'))){
	        if(array_key_exists('to', $this->request->post('post'))){
	        	$view->filter_task_to = $this->request->post('post')['to'];  

	        	$query->and_where('task_to', '=', $view->filter_task_to);
	        }

	       	if(array_key_exists('team', $this->request->post('post'))){
	        	$view->filter_team = $this->request->post('post')['team'];  

	        	$query->and_where('team_id', '=', $view->filter_team);

	        	if(strpos('assistente', $this->current_auth) !== false){
	        		$query->and_where('task_to', '=', '0');
					$status_arr = array('5');
				}
	        }

	    }

		$query->and_where('status_id', 'IN', $status_arr);
        //(isset($view->filter_status)) ? $query->and_where('status_id', 'IN', $status_arr)->and_where('team_id', '=', $view->filter_team) : '';
        //(isset($view->filter_team)) ? $query->and_where('team_id', '=', $view->filter_team) : '';
        //(isset($view->filter_task_to)) ? $query->and_where('task_to', '=', $view->filter_task_to) : '';

        $view->taskList = $query->order_by('order', 'ASC')->order_by('crono_date','ASC')->find_all();

        /**notificacao de leitura? **/
        $query2 = ORM::factory('task')
        			->where('ended', '=', '0')
        			->and_where('status_id', '=', '7')
        			->and_where('userInfo_id', '=', $this->current_user->userInfos->id);

        //(isset($view->filter_task_to)) ? $query2 : '';

        $view->notifications = $query2->order_by('order', 'ASC')->order_by('crono_date','ASC')->find_all();
        			
               
        //$this->endProfilling();
        header('Content-Type: application/json');
		echo json_encode(
			array(
				array('container' => '#tabs_content', 'type'=>'html', 'content'=> json_encode($view->render())),
			)						
		);
        return false;
    }  
        
	/*--------------*/
    public function action_searchnew()
    {	
    	$this->auto_render = false;
		$data = json_decode($this->request->query('data'));

        $taskList = ORM::factory('task')
                ->where('created_at', '>', $data)
                ->find_all();

        $arr = array('total'=>$taskList->count());
        print $callback.json_encode($arr);        
    } 

    
}
