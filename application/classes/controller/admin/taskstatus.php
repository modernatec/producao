<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Admin_Taskstatus extends Controller_Admin_Template {
 
	public $auth_required		= array('login'); 
	public $secure_actions     	= array(
										'create' => array('login','coordenador'),
										'delete' => array('login','assistente 2'),);
					 
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response); 
		$this->check_login();	               
	}
  
	
	/*
	* inicia uma tarefa 
	*/
	public function action_start(){
		$this->auto_render = false;

		if (HTTP_Request::POST == $this->request->method()) 
		{
			$task_ini = ORM::factory('tasks_statu')->where('task_id', '=',$this->request->post('task_id'))->and_where('status_id', '=', '6')->find_all();
			if(count($task_ini) > 0){
				$message = "Tarefa já foi iniciada ".$this->request->post('task_id'); 
			
				Utils_Helper::mensagens('add',$message);
            	Request::current()->redirect('admin/objects/view/'.$this->request->post('object_id'));
			}else{
				$db = Database::instance();
		        $db->begin();
				
				try {  					
					$task_statu = ORM::factory('tasks_statu');
					$task_statu->userInfo_id = $this->current_user->userInfos->id;
					$task_statu->status_id = '6';
					$task_statu->task_id = $this->request->post('task_id');
					$task_statu->description = $this->request->post('description'); 
					$task_statu->save();

					/*
					* atualiza tarefa com info do user que a iniciou
					*/
					$task = ORM::factory('task', $this->request->post('task_id'));
					$task->task_to = $this->current_user->userInfos->id;
		            $task->save();
		            
		            $db->commit();
					
		            $message = "Tarefa iniciada com sucesso."; 
					
					Utils_Helper::mensagens('add',$message);
		            //Request::current()->redirect('admin/objects/view/'.$task->object_id);

		            echo URL::base().'admin/objects/view/'.$task->object_id;
		            
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
	}	

	/*
	* encerra uma tarefa
	*/
	public function action_end(){
		$this->auto_render = false;
		if (HTTP_Request::POST == $this->request->method()) 
		{
			$task_end = ORM::factory('tasks_statu')->where('task_id', '=',$this->request->post('task_id'))->and_where('status_id', '=', '7')->find_all();
			if(count($task_end) > 0){
				$message = "Tarefa já finalizada";
			
				Utils_Helper::mensagens('add',$message);
            	Request::current()->redirect('admin/objects/view/'.$this->request->post('object_id'));
			}else{
				$db = Database::instance();
		        $db->begin();
				
				try {  					
					$task_statu = ORM::factory('tasks_statu');
					$task_statu->userInfo_id = $this->current_user->userInfos->id;
					$task_statu->status_id = '7';
					$task_statu->task_id = $this->request->post('task_id');
					$task_statu->description = $this->request->post('description'); 
					$task_statu->save();
		            
		            /*
					* atualiza flag ended, encerrando a tarefa para o user
					*/
					$task = ORM::factory('task', $this->request->post('task_id'));
					$task->ended = '1';
		            $task->save();

		            /*
		            * abre tarefa automaticamente para o próx. fluxo
		            * melhorar data e separaçao de metodos
		            */
		            if($task->tag_id != "7"){
			            if($task->tag_id == "5" || $task->tag_id == "6"){
			            	$new_tag_id = '1';						
			            	$task_to = '0';
			            	$description = 'checagem de prova/correção.';
			            	$date = date('Y-m-d H:i:s', strtotime($task->created_at . ' + 1 day'));
				        }elseif($task->tag_id == '1' && $this->request->post('next_step') == "6"){
			        		$new_tag_id = '6';						
			            	$task_to = '0';
			            	$description = 'corrigir conforme relatório de checagem anterior.';
			            	$date = date('Y-m-d H:i:s', strtotime($task->crono_date . ' + 1 day'));
				        }else{
				        	$new_tag_id = '7';						
			            	$task_to = '0';
			            	$description = 'em trânsito';
			            	$date = $task->crono_date;
				        }

				        $new_task = ORM::factory('task');
		            	$new_task->object_id = $task->object_id;
		            	$new_task->object_status_id = $task->object_status_id;
		            	$new_task->tag_id = $new_tag_id;
		            	$new_task->topic = '1';
		            	$new_task->crono_date = $date;
		            	$new_task->description = $description;
		            	$new_task->task_to = $task_to;
		            	$new_task->userInfo_id = $this->current_user->userInfos->id;
			            $new_task->save();  
			            
						$new_statu = ORM::factory('tasks_statu');
						$new_statu->userInfo_id = $this->current_user->userInfos->id;
						$new_statu->status_id = '5';
						$new_statu->task_id = $new_task->id;
						$new_statu->save();  
					}
					
		            /*
		            * envia email de entrega
		            *
		            $this->sendMail(
			            	array(	
				            	'type' => 'entrega_tarefa',
				            	'post' => $this->request->post(), 
		            			'user' => $this->current_user->userInfos));
		            */
		            
		            $db->commit();
					
		            $message = "Tarefa finalizada com sucesso."; 
					
					Utils_Helper::mensagens('add',$message);
		            //Request::current()->redirect('admin/objects/view/'.$task->object_id);
		            echo URL::base().'admin/objects/view/'.$task->object_id;
		            
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
	}

	/*
	* edita um status 
	*/
	public function action_edit($id){
		$this->auto_render = false;
		if (HTTP_Request::POST == $this->request->method()){
			$db = Database::instance();
	        $db->begin();
			
			try {
				$task_status = ORM::factory('tasks_statu', $id);
				$task_status->description = $this->request->post('description');
				$task_status->save();

				$task = ORM::factory('task', $task_status->task_id);

	            $db->commit();
				
	            $message = "status editado com sucesso."; 
				
				Utils_Helper::mensagens('add',$message);
	            //Request::current()->redirect('admin/objects/view/'.$task->object_id);
	            echo URL::base().'admin/objects/view/'.$task->object_id;
	            
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

	public static function sendMail($arg){
		$object = ORM::factory('object', $arg['post']['object_id']);    	
		$linkTask = URL::base().'admin/objects/view/'.$arg['post']['object_id'];
		$email = new Email_Helper();
		$send = false;
		
		
		switch($arg['type']){
			case 'inicia_tarefa':
				if($arg['post']['task_to'] != 0){
					$taskUser = ORM::factory('userInfo', $arg['post']['task_to']); 
					$send = ($taskUser->mailer == '1' && $taskUser->email != '') ? true: false;

					$email->userInfo = $taskUser;
					$nome = explode(" ", $taskUser->nome);

					$assunto = $arg['subject'].' - '.$object->taxonomia;
					$data_arr = array(
						'mensagem' => 'Olá, '.ucfirst($nome[0]).', você possuí uma nova tarefa.',
						'titulo' => $arg['subject'],
						'por' => $arg['user']->nome,
						'entrega' => $arg['post']['crono_date'],
						'descricao' => $arg['post']['description'],
						'link' => $linkTask
					);
		        }
			break;
			case 'atualiza_tarefa':
				if($arg['post']['task_to'] != 0){
					$taskUser = ORM::factory('userInfo', $arg['post']['task_to']); 
					$send = ($taskUser->mailer == '1' && $taskUser->email != '') ? true: false;

					$email->userInfo = $taskUser;
					$nome = explode(" ", $taskUser->nome);

					$assunto = $arg['subject'].' - '.$object->taxonomia;
					$data_arr = array(
						'mensagem' => 'Olá, '.ucfirst($nome[0]).', a tarefa abaixo foi atualizada.',
						'titulo' => $arg['subject'],
						'por' => $arg['user']->nome,
						'entrega' => $arg['post']['crono_date'],
						'descricao' => $arg['post']['description'],
						'link' => $linkTask
					);
		        }
			break;
			case 'entrega_tarefa':
				/* removemos as entregas uma vez que as tarefas são abertas automaticamente

				$task = ORM::factory('task', $arg['post']['task_id']);
				$taskUser = $task->userInfo;    
				$send = ($taskUser->mailer == '1' && $taskUser->email != '') ? true: false; 	
				$email->userInfo = $taskUser;
				$nome = explode(" ", $taskUser->nome);

				$assunto = $object->taxonomia.' - Tarefa concluída!';
				$data_arr = array(
					'mensagem' => 'Olá, '.ucfirst($nome[0]).', a tarefa abaixo foi concuída.',
					'titulo' => $arg['subject'],
					'entrega' => $arg['post']['crono_date'],
					'descricao' => $arg['post']['description'],
					'link' => $linkTask
				);
                       
				$email->assunto = 
				$email->mensagem = '<font face="arial"><br/><br/>
					<b>Entregue por:</b> '.$arg['user']->nome.'<br/>
					<b>Observações:</b> <pre>'.$arg['post']['description'].'</pre><br/>
					<b>Link:</b> <a href="'.$linkTask.'" title="Ir para a tarefa">'.$linkTask.'</a></font>';
				*/		
			break;
		} 


		if($send){
			$template = View::factory('admin/tasks/layout_mail')
							->bind('data', $data_arr);

			$email->assunto = $assunto;
			$email->mensagem = $template;
			$email->enviaEmail();
		}
	}
}