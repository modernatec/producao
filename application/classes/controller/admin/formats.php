<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Admin_Formats extends Controller_Admin_Template {
 
	public $auth_required		= array('login', 'admin'); //Auth is required to access this controller
 	
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
		$this->auto_render = false;
		$view = View::factory('admin/formats/list')
			->bind('message', $message);
		
		$view->delete_msg = Kohana::message('models/format', 'delete');
		$view->formatList = ORM::factory('format')->order_by('id','DESC')->find_all();
		
		if($ajax != ''){
			return $view;
		}else{
			header('Content-Type: application/json');
			echo json_encode(
				array(
					array('container' => '#tabs_content', 'type'=>'html', 'content'=> json_encode($view->render())),
					array('container' => '#filtros', 'type'=>'html', 'content'=> json_encode('')),
					array('container' => '#direita', 'type'=>'html', 'content'=> json_encode('')),
				)						
			);
	        return false;
		}		           
	} 
        
	public function action_edit($id)
    {  
		$this->auto_render = false;
		$view = View::factory('admin/formats/create')
		->bind('errors', $errors)
		->bind('message', $message);
		
		$this->addValidateJs();
		$view->isUpdate = true;   
		
		$sfwprod = ORM::factory('format', $id);
		$view->sfwprodVO = $this->setVO('format', $sfwprod);
		
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
			$sfwprod = ORM::factory('format', $id)->values($this->request->post(), array(
				'name',
				'ext'
			));
			                
			$sfwprod->save();
			$db->commit();

			$msg = "formato salvo com sucesso.";
		} catch (ORM_Validation_Exception $e) {
            $errors = $e->errors('models');
			$erroList = '';
			foreach($errors as $erro){
				$erroList.= $erro.'<br/>';	
			}
            $msg = 'houveram alguns erros na validação <br/><br/>'.$erroList;

            $db->rollback();
        } catch (Database_Exception $e) {
            $msg = 'houveram alguns erros na base <br/><br/>'.$e->getMessage();
            $db->rollback();
        }

        header('Content-Type: application/json');
		echo json_encode(
			array(
				array('container' => '#content', 'type'=>'url', 'content'=> URL::base().'admin/format/index/ajax'),
				array('type'=>'msg', 'content'=> $msg),
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
			if($this->request->post('format_id') != ''){
				$new = ORM::factory('format', $this->request->post('format_id'));
				DB::update('objects')->set(array('format_id' => $new->id))->where('format_id', '=', $id)->execute();
			}

			DB::delete('formats')->where('id','=', $id)->execute();

			$db->commit();
			$msg = "formato final excluído com sucesso.";

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
				array('container' => '#tabs_content', 'type'=>'html', 'content'=> json_encode($this->action_index(true)->render())),
				array('container' => '#direita', 'type'=>'html', 'content'=> json_encode('')),
				array('container' => $msg_type, 'type'=>'msg', 'content'=> $msg),
			)						
		);

        return false;
	}

	public function action_deletePanel($id)
	{
		$this->auto_render = false;
		$view = View::factory('admin/formats/delete')
					->bind('errors', $errors)
					->bind('message', $message);

		$view->current_auth = $this->current_auth;

		$objects = ORM::factory('object')->where('format_id', '=', $id)->find_all();
		$view->total_objects = count($objects);
		$view->delete_msg = Kohana::message('models/format', 'delete');
		$view->format = ORM::factory('format', $id);
		$view->formatList = ORM::factory('format')
									->where('id', '!=', $id)
									->order_by('name', 'ASC')->find_all();

		echo $view;
	}
}