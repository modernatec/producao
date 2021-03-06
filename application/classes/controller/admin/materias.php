<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Admin_Materias extends Controller_Admin_Template {
 
	public $auth_required		= array('login','coordenador'); //Auth is required to access this controller
 	
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
		$view = View::factory('admin/materias/list')
			->bind('message', $message);
		
		$view->materiasList = ORM::factory('materia')->order_by('name','ASC')->find_all();
		$this->auto_render = false;
		header('Content-Type: application/json');
		echo json_encode(
			array(
				array('container' => '#tabs_content', 'type'=>'html', 'content'=> json_encode($view->render())),
				array('container' => '#filtros', 'type'=>'html', 'content'=> json_encode('')),
				array('container' => '#direita', 'type'=>'html', 'content'=> json_encode('')),
			)						
		);
        return false;

		/*
		if($ajax == null){
			$this->template->content = $view;             
		}else{
			
		}     
		*/     
	} 
        
	public function action_edit($id)
    {    
		$this->auto_render = false;  
		$view = View::factory('admin/materias/create')
			->bind('errors', $errors)
			->bind('message', $message);

		$view->isUpdate = true;  
		$materia = ORM::factory('materia', $id);
		$view->materiaVO = $this->setVO('materia', $materia);

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
			$materia = ORM::factory('materia', $id)->values($this->request->post(), array(
				'name',
			));
			                
			$materia->save();
			$db->commit();

			$msg = "tudo certo!";		
		} catch (ORM_Validation_Exception $e) {
            $errors = $e->errors('models');
			$erroList = '';
			foreach($errors as $erro){
				$erroList.= $erro.'<br/>';	
			}
            $msg = 'houveram alguns erros na validação <br/><br/>'.$erroList;
            $db->rollback();
        } catch (Database_Exception $e) {
            $msg = 'Houveram alguns erros na base <br/><br/>'.$e->getMessage();
            $db->rollback();
        }

		header('Content-Type: application/json');
		echo json_encode(
			array(
				array('container' => '#content', 'type'=>'url', 'content'=> URL::base().'admin/materias/index/ajax'),
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
			$objeto = ORM::factory('materia', $id);
			$objeto->delete();

			$msg = "matéria excluída.";
		} catch (ORM_Validation_Exception $e) {

			$msg = "houveram alguns erros na exclusão dos dados.";
			$errors = $e->errors('models');
		}
		
		header('Content-Type: application/json');
		echo json_encode(
			array(
				array('container' => '#content', 'type'=>'url', 'content'=> URL::base().'admin/materias/index/ajax'),
				array('type'=>'msg', 'content'=> $msg),
			)						
		);
	}

}