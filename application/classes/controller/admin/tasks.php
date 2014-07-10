<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Admin_Tasks extends Controller_Admin_Template {
 
	public $auth_required		= array('login'); 
	public $secure_actions     	= array(
										'create' => array('login','coordenador'),
										'delete' => array('login','admin'),);
					 
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response); 
		$this->check_login();	               
	}

	public function action_index()
	{	
		$this->setRefresh();
		$view = View::factory('admin/tasks/list');
		$view->userInfo_id = $this->current_user->userInfos->id;

        $view->has_task = ORM::factory('taskView')
        						->join('userInfos', 'INNER')->on('userInfos.id', '=', 'task_to')
        						->where('ended', '=', '0')
        						->where('task_to', '!=', '0')->group_by('task_to')
        						->order_by('nome', 'ASC')->find_all();
        
        

        if($this->request->query('to')){
        	$tasks_of = ORM::factory('userInfo', $this->request->query('to'));	
        	$nome = explode(" ", $tasks_of->nome);
        	$view->title = "tarefas - ".$nome[0];
        	$view->filter = "?to=".$tasks_of->id;
        }else{
        	$view->title = "tarefas - equipe";
        	$view->filter = "?status=".json_encode(array("5"));
        }
		$view->totalTasks = ORM::factory('task')->where('ended', '=', '0')->count_all();
		$view->current_auth = $this->current_auth;

	  	$this->template->content = $view;	
	  	
		/*
	  	$rs = ORM::factory('task')->where('task_id', '=', '0')->find_all();
	  	foreach($rs as $task){
	  		$rs2 = ORM::factory('tasks_statu');
	  		$rs2->userInfo_id = $task->userInfo_id;
	  		$rs2->status_id = $task->status_id;
	  		$rs2->task_id = $task->id;
	  		$rs2->created_at = $task->created_at;
	  		$rs2->save();
	  	}
	  	*/


	  	/*
	  	$rs = ORM::factory('task')->where('task_id', '!=', '0')->find_all();
	  	foreach($rs as $task){
	  		$rs2 = ORM::factory('tasks_statu');
	  		$rs2->userInfo_id = $task->task_to;
	  		$rs2->status_id = $task->status_id;
	  		$rs2->task_id = $task->task_id;
	  		$rs2->description = $task->description;
	  		$rs2->created_at = $task->created_at;
	  		$rs2->save();
	  	}


	  	APAGAR AS TASKS DE HISTORIES
	  	*/		  	
	} 

	public function action_reorder(){
		$this->auto_render = false;
		if (HTTP_Request::POST == $this->request->method()) 
		{
			$i = '0';
			foreach($this->request->post('item') as $task_id){
				$task = ORM::factory('task', $task_id);
				$task->ordem = $i;
				$task->save();

				$i++;
			}
		}
	}
    
   	public function action_update($id){
		$this->auto_render = false;
		$view = View::factory('admin/tasks/form_edit');

		$view->bind('errors', $errors)
			->bind('message', $message);
		
		$task = ORM::factory('task', $id);
		$view->taskVO = $this->setVO('task', $task);
		$view->teamList = ORM::factory('userInfo')->where('status', '=', '1')->order_by('nome', 'ASC')->find_all(); 

		echo $view;
	}

	public function action_updateReply($id){
		$this->auto_render = false;
		$view = View::factory('admin/tasks/form_edit_reply');

		$view->bind('errors', $errors)
			->bind('message', $message);
		
		$task = ORM::factory('task', $id);
		$view->taskVO = $this->setVO('task', $task);

		echo $view;
	}
	
	public function action_salvar($id = null)
	{
		if (HTTP_Request::POST == $this->request->method()) 
		{  
	        $db = Database::instance();
	        $db->begin();
			
			try {  
				
				$task = ORM::factory('task', $id);
				
				$task->values($this->request->post(), array(
					'object_id',
					'object_status_id',
					'topic',
					'crono_date',
					'description',
					'task_to',
				)); 
				
				$task->userInfo_id = $this->current_user->userInfos->id;
	            $task->save();  

	            if(empty($id)){
	            	/*
					* cria status inicial da tarefa
					*/    
					$task_statu = ORM::factory('tasks_statu');
					$task_statu->userInfo_id = $this->current_user->userInfos->id;
					$task_statu->status_id = '5';
					$task_statu->task_id = $task->id;
					$task_statu->save();  

					/*
		            * envia email de tarefa para o usuário
		            */
					Controller_Admin_Taskstatus::sendMail(array(
															'type' => 'inicia_tarefa',
															'post' => $this->request->post(), 
            												'user' => $this->current_user->userInfos));		
				}

	            $db->commit();

	            $message = "Tarefa salva com sucesso."; 
				
				Utils_Helper::mensagens('add',$message);
	            Request::current()->redirect('admin/objects/view/'.$task->object_id);
	            
	        } catch (ORM_Validation_Exception $e) {
	            $errors = $e->errors('models');
				$erroList = '';
				foreach($errors as $erro){
					$erroList.= $erro.'<br/>';	
				}
	            $message = 'Houveram alguns erros na validação <br/><br/>'.$erroList;

			    Utils_Helper::mensagens('add',$message);  
	            $db->rollback();
	        } catch (Database_Exception $e) {
	            $message = 'Houveram alguns erros na base <br/><br/>'.$e->getMessage();
				Utils_Helper::mensagens('add',$message);
	            $db->rollback();
	        }

	        
	        return false;
	    }
    }
	
	public function action_delete($id){    
		$db = Database::instance();
        $db->begin();
		
		try {  
			
			$task = ORM::factory('task', $id);
			$object_id = $task->object_id;
			
			$task_status = ORM::factory('tasks_statu')->where('task_id', '=', $id)->find_all();
			foreach($task_status as $status){
				$status->delete();
			}

			$task->delete();

            $db->commit();

            $message = "Tarefa excluída com sucesso."; 
			
			Utils_Helper::mensagens('add',$message);
            Request::current()->redirect('admin/objects/view/'.$object_id);
            
        } catch (ORM_Validation_Exception $e) {
            $errors = $e->errors('models');
			$erroList = '';
			foreach($errors as $erro){
				$erroList.= $erro.'<br/>';	
			}
            $message = 'Houveram alguns erros na validação <br/><br/>'.$erroList;

		    Utils_Helper::mensagens('add',$message);  
            $db->rollback();
        } catch (Database_Exception $e) {
            $message = 'Houveram alguns erros na base <br/><br/>'.$e->getMessage();
			Utils_Helper::mensagens('add',$message);
            $db->rollback();
        }

        
        return false;	        
	}

    /********************************/
    public function action_getTasks(){
    	$this->check_login();	
        $this->auto_render = false;
        $view = View::factory('admin/tasks/table');

        $view->current_auth = $this->current_auth;

        //$this->startProfilling();

        //$view->filter_tipo = json_decode($this->request->query('tipo'));
        $view->filter_status = json_decode($this->request->query('status'));  
		//$view->filter_userInfo_id = $this->request->query('userInfo_id'); 
		$view->filter_task_to = $this->request->query('to');             

        //$query = ORM::factory('task')->where('status_id', '=', $view->filter_status)        
        $query = ORM::factory('taskView');

        /***Filtros***/
        (isset($view->filter_status)) ? $query->where('status_id', '=', $view->filter_status) : '';
        //(isset($view->filter_userInfo_id)) ? $query->where('task_to', '=', $view->filter_userInfo_id) : '';
        (isset($view->filter_task_to)) ? $query->where('task_to', '=', $view->filter_task_to) : '';

        $view->taskList = $query->and_where('ended', '=', '0')->order_by('ordem', 'ASC')->order_by('crono_date','ASC')->find_all();

        
        //$this->endProfilling();
        echo $view;
    }  

    public function action_ongoing(){
    	$this->check_login();	
        $this->auto_render = false;
        $view = View::factory('admin/tasks/table_ongoing');

        $view->objectsList = DB::select('*')->from('ongoings')->order_by('crono_date','ASC')->execute();

        echo $view;
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
