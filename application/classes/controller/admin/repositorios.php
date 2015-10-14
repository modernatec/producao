<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Admin_Repositorios extends Controller_Admin_Template {
 
	public $auth_required		= array('login','admin'); //Auth is required to access this controller
 	
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
		$view = View::factory('admin/repositorios/list')
			->bind('message', $message);
		
		$view->repositoriosList = ORM::factory('repositorio')->order_by('name','DESC')->find_all();
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

	/*
	public function action_create()
    { 
		$view = View::factory('admin/materias/create')
			->bind('errors', $errors)
			->bind('message', $message);

		$this->addValidateJs("public/js/admin/validateMaterias.js");
		$view->isUpdate = false;  
		$view->materiaVO = $this->setVO('materia');
		$this->template->content = $view;
		
		if (HTTP_Request::POST == $this->request->method()) 
		{           
            $this->salvar();
		}
	}
	*/
        
	public function action_edit($id)
    {    
		$this->auto_render = false;  
		$view = View::factory('admin/repositorios/create')
			->bind('errors', $errors)
			->bind('message', $message);

		//$this->addValidateJs("public/js/admin/validateMaterias.js");
		$view->isUpdate = true;  
		$materia = ORM::factory('repositorio', $id);
		$view->objVO = $this->setVO('repositorio', $materia);
		//$this->template->content = $view;

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
			$materia = ORM::factory('repositorio', $id)->values($this->request->post(), array(
				'name',
			));
			                
			$materia->save();
			$db->commit();
			//Utils_Helper::mensagens('add','Matéria '.$materia->name.' salvo com sucesso.');
			//Request::current()->redirect('admin/materias');
			$msg = "repositório salvo com sucesso.";		

		} catch (ORM_Validation_Exception $e) {
            $errors = $e->errors('models');
			$erroList = '';
			foreach($errors as $erro){
				$erroList.= $erro.'<br/>';	
			}
            $msg = 'houveram alguns erros na validação <br/><br/>'.$erroList;
		    ///Utils_Helper::mensagens('add',$message);    
            $db->rollback();
        } catch (Database_Exception $e) {
            $msg = 'Houveram alguns erros na base <br/><br/>'.$e->getMessage();
            //Utils_Helper::mensagens('add',$message);
            $db->rollback();
        }

		header('Content-Type: application/json');
		echo json_encode(
			array(
				array('container' => '#content', 'type'=>'url', 'content'=> URL::base().'admin/repositorios/index/ajax'),
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
			$objeto = ORM::factory('repositorio', $id);
			$objeto->delete();
			//Utils_Helper::mensagens('add',''); 
			$msg = "matéria excluído com sucesso";
		} catch (ORM_Validation_Exception $e) {
			//Utils_Helper::mensagens('add','Houveram alguns erros na exclusão dos dados.'); 
			$msg = "houveram alguns erros na exclusão dos dados.";
			$errors = $e->errors('models');
		}
		
		header('Content-Type: application/json');
		echo json_encode(
			array(
				array('container' => '#content', 'type'=>'url', 'content'=> URL::base().'admin/repositorios/index/ajax'),
				array('type'=>'msg', 'content'=> $msg),
			)						
		);
	}

}