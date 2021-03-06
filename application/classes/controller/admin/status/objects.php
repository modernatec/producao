<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Admin_Status_Objects extends Controller_Admin_Template {
 
	public $auth_required		= array('login', 'admin'); //Auth is required to access this controller
 					 
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);	
	}
    
	public function action_getListStatus($ajax = null){
		$this->auto_render = false;
		$table_view = View::factory('admin/objects_status/table');
		$table_view->delete_msg = Kohana::message('models/statu', 'delete');
		$table_view->statusList = ORM::factory('statu')->where('type', '=', 'objects')->and_where('id', 'NOT IN', array('53'))->order_by('order','ASC')->find_all();

		if($ajax != null){
       		return $table_view;
        }else{
            header('Content-Type: application/json');
            echo json_encode(
                array(
                    array('container' => '#tabs_content', 'type'=>'html', 'content'=> json_encode($table_view->render())),
                    array('container' => '#filtros', 'type'=>'html', 'content'=> json_encode("")),
                    array('container' => '#direita', 'type'=>'html', 'content'=> json_encode("")),
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
			foreach($this->request->post('item') as $status_id){
				$task = ORM::factory('statu', $status_id);
				$task->order = $i;
				$task->save();

				$i++;
			}
		}
	}

	public function action_edit($id, $ajax = null)
    {    
		$this->auto_render = false;
		$view = View::factory('admin/objects_status/create')
			->bind('errors', $errors)
			->bind('message', $message);
			
		$status = ORM::factory('statu', $id);
		$view->statusVO = $this->setVO('statu', $status); 

		
		if($ajax != null){
			return $view;
		}else{
			header('Content-Type: application/json');
			echo json_encode(
				array(
					array('container' => '#direita', 'type'=>'html', 'content'=> json_encode($view->render())),
				)						
			);
		}
        return false;		
	}

	public function action_salvar($id = null)
	{
		$this->auto_render = false;
		$db = Database::instance();
        $db->begin();
		
		try 
		{            
			$objeto = ORM::factory('statu', $id)->values($this->request->post(), array(
				'status',
			));
			$objeto->type = 'objects';
			                
			$objeto->save();

			$db->commit();
			
			$msg = "tudo certo!";
			$msg_type = 'normal';

		} catch (ORM_Validation_Exception $e) {
            $errors = $e->errors('models');
			$erroList = '';
			foreach($errors as $erro){
				$erroList.= $erro.'<br/>';	
			}
            $msg = $erroList;
            $msg_type = 'error';

            $db->rollback();
        } catch (Database_Exception $e) {
            $msg = 'Houveram alguns erros na base <br/><br/>'.$e->getMessage();
            $msg_type = 'error';

            $db->rollback();
        }

		header('Content-Type: application/json');
		echo json_encode(
			array(	
				array('container' => '#tabs_content', 'type'=>'html', 'content'=> json_encode($this->action_getListStatus(true)->render())),
				array('container' => '#direita', 'type'=>'html', 'content'=> json_encode($this->action_edit($id, true)->render())),
				array('container' => $msg_type, 'type'=>'msg', 'content'=> $msg),
			)						
		);
		
		return false;	
	}

	public function action_deletePanel($id)
	{
		$this->auto_render = false;
		$view = View::factory('admin/objects_status/delete')
					->bind('errors', $errors)
					->bind('message', $message);

		$view->current_auth = $this->current_auth;

		$collection_objects = ORM::factory('object')->where('fase', '=', $id)->find_all();
		$view->total_objects = count($collection_objects);
		$view->delete_msg = Kohana::message('models/statu', 'delete');
		$view->status = ORM::factory('statu', $id);
		$view->statusList = ORM::factory('statu')
									->where('type', '=', 'objects')
									->and_where('id', '!=', $id)
									->order_by('status', 'ASC')->find_all();

		echo $view;
	}
	
	public function action_delete($id)
	{	
		$this->auto_render = false;
		$db = Database::instance();
        $db->begin();

        $msg_type = 'normal';
		
		try 
		{    
			if($this->request->post('status_id') != ''){
				$new_status = ORM::factory('statu', $this->request->post('status_id'));
				DB::update('objects')->set(array('fase' => $new_status->id))->where('fase', '=', $id)->execute();
			}

			DB::delete('status')->where('id','=', $id)->execute();

			$db->commit();
			$msg = "status excluído com sucesso.";
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
				array('container' => '#tabs_content', 'type'=>'html', 'content'=> json_encode($this->action_getListStatus(true)->render())),
				array('container' => '#direita', 'type'=>'html', 'content'=> json_encode('')),
				array('container' => $msg_type, 'type'=>'msg', 'content'=> $msg),
			)						
		);

        return false;
	}
}