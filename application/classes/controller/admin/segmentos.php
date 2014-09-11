<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Admin_Segmentos extends Controller_Admin_Template {
 
	public $auth_required		= array('login', 'admin'); //Auth is required to access this controller
	
	/*
	public $secure_actions     	= array(
										'create' => array('login', 'admin'),
										'edit' => array('login', 'admin'),
										'delete' => array('login', 'admin'),
                                  );
	*/
					 
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);	
	}

	public function action_index($ajax = null)
	{	
		$view = View::factory('admin/segmentos/list')
			->bind('message', $message);
		
		$view->segmentosList = ORM::factory('segmento')->order_by('name','ASC')->find_all();
		
		if($ajax == null){
			$this->template->content = $view;             
		}else{
			$this->auto_render = false;
			echo $view;
		}
	} 

	/*
	public function action_create()
    { 
		$view = View::factory('admin/segmentos/create')
			->bind('errors', $errors)
			->bind('message', $message);

		$this->addValidateJs("public/js/admin/validateSegmentos.js");
		$view->isUpdate = false; 
		$view->segmentoVO = $this->setVO('segmento');		
		$this->template->content = $view;
		
		if (HTTP_Request::POST == $this->request->method()) 
		{           
        	$this->salvar();
		}		
	}
	*/

	public function action_edit($id)
    {   
		if (HTTP_Request::POST == $this->request->method()) 
		{                                              
			$this->salvar($id);
		}else{
			$this->auto_render = false;
			$view = View::factory('admin/segmentos/create')
				->bind('errors', $errors)
				->bind('message', $message);

			$view->isUpdate = true;              

			$segmento = ORM::factory('segmento', $id);		
			$view->segmentoVO = $this->setVO('segmento', $segmento);				
			//$this->template->content = $view;
			echo $view;
		}
	}

	protected function salvar($id = null)
	{
		$this->auto_render = false;
		$db = Database::instance();
        $db->begin();
		
		try 
		{            
			$segmento = ORM::factory('segmento', $id)->values($this->request->post(), array(
				'name',
			));
			                
			$segmento->save();
			$db->commit();
			//Utils_Helper::mensagens('add','Segmento '.$segmento->name.' salvo com sucesso.');
			//Request::current()->redirect('admin/segmentos');

			header('Content-Type: application/json');
			echo json_encode(array(
				'esquerda' => URL::base().'admin/segmentos/index/ajax',				
				'msg' => "Segmento salvo com sucesso.",
			));

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
	
	public function action_delete($id)
	{	
		$this->auto_render = false;
		try 
		{            
			$objeto = ORM::factory('segmento', $id);
			$objeto->delete();
			//Utils_Helper::mensagens('add','Segmento excluído com sucesso.'); 
			$msg = "segmento excluído com sucesso.";
		} catch (ORM_Validation_Exception $e) {
			//Utils_Helper::mensagens('add','Houveram alguns erros na exclusão dos dados.'); 
			$msg = "houveram alguns erros na exclusão dos dados.";
			$errors = $e->errors('models');
		}
		

		header('Content-Type: application/json');
		echo json_encode(array(
			'esquerda' => URL::base().'admin/segmentos/index/ajax',				
			'msg' => $msg,
		));
		//Request::current()->redirect('admin/segmentos');
	}
}