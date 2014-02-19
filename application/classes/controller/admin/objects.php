<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Admin_Objects extends Controller_Admin_Template {
 
	public $auth_required = array('login'); //Auth is required to access this controller
 
	public $secure_actions = array(
                                    'create' => array('login', 'coordenador'),
                                    'edit' => array('login', 'coordenador'),
                                    'delete' => array('login', 'coordenador'),
                                 );
    const ITENS_POR_PAGINA = 20;
					 
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);	
	}
        
	public function action_index()
	{	
		$view = View::factory('admin/objects/list')
			->bind('message', $message);
		
		$view->filter_tipo = ($this->request->post('tipo') != "") ? json_encode($this->request->post('tipo')) : json_encode(array());
		$view->filter_status = ($this->request->post('status') != "") ? json_encode($this->request->post('status')) : json_encode(array());
		$view->filter_collection = ($this->request->post('collection') != "") ? json_encode($this->request->post('collection')) : json_encode(array());
		$view->filter_supplier = ($this->request->post('supplier') != "") ? json_encode($this->request->post('supplier')) : json_encode(array());
		
		
		//$query = ORM::factory('object');						
		//$count = $query->count_all();
		//$pag = new Pagination( array( 'total_items' => $count, 'items_per_page' => self::ITENS_POR_PAGINA, 'auto_hide' => true ) );
		//$view->page_links = $pag->render();
        $view->projectList = ORM::factory('project')->find_all(); 


		//$view->objectsList = $query->order_by('id','DESC')->limit($pag->items_per_page)->offset($pag->offset)->find_all();
		//$view->linkPage = ($this->assistente)?('view'):('edit');
		//$view->styleExclusao = ($this->assistente)?('style="display:none"'):('');
		$this->template->content = $view;             
	} 
    
	public function action_create(){ 		
        $view = View::factory('admin/objects/create')
			->bind('errors', $errors)
			->bind('message', $message);
		
		$this->addValidateJs('public/js/admin/validateObjects.js');
		$view->objVO = $this->setVO('object');
        
        $view->typeObjects = ORM::factory('typeobject')->find_all();
        $view->countries = ORM::factory('country')->find_all();
        $view->suppliers = ORM::factory('supplier')->find_all();        
        $view->collections = ORM::factory('collection')->order_by('name', 'ASC')->find_all();
		
		       
        if (HTTP_Request::POST == $this->request->method()) 
		{           
            $this->salvar();
        }    
        
        $this->template->content = $view;                     
	}
      
	public function action_delete($id)
	{
		$view = View::factory('admin/objects/list')
			->bind('errors', $errors)
			->bind('message', $message);
		
		try 
		{            
			$objeto = ORM::factory('object', $id);
			$objeto->delete();
			Utils_Helper::mensagens('add','Objeto excluído com sucesso.'); 
		} catch (ORM_Validation_Exception $e) {
			Utils_Helper::mensagens('add','Houveram alguns erros na exclusão dos dados.'); 
			$errors = $e->errors('models');
		}
		
		Request::current()->redirect('admin/objects');
	}

	public function action_edit($id)
    {           
		$view = View::factory('admin/objects/create')
			->bind('errors', $errors)
			->bind('message', $message)
			->set('values', $this->request->post());
                
        //$this->addPlupload();
		$this->addValidateJs();

		$objeto = ORM::factory('object', $id);
        $view->objVO = $this->setVO('object', $objeto);
		$view->isUpdate = true;                             
                
		$view->typeObjects = ORM::factory('typeobject')->find_all();
        $view->countries = ORM::factory('country')->find_all();
        $view->suppliers = ORM::factory('supplier')->find_all();        
        $view->collections = ORM::factory('collection')->order_by('name', 'ASC')->find_all();   
                
		if (HTTP_Request::POST == $this->request->method()) 
		{                                              
            $this->salvar($id);
        }

        $this->template->content = $view;
	}
        
    public function action_view($id, $task_id = null)
    {           
        $view = View::factory('admin/tasks/assign')
            ->bind('errors', $errors)
            ->bind('message', $message);

		$this->addValidateJs();

		$objeto = ORM::factory('object', $id);
        $view->obj = $objeto;                             
		        
        $view->taskflows = ORM::factory('task')->where('object_id', '=', $id)->order_by('created_at', 'DESC')->find_all();

        if($this->current_auth != 'assistente'){
            $view->assign_form = View::factory('admin/tasks/assign_form');
            $view->assign_form->statusList = ORM::factory('statu')->where('type', '=', 'object')->find_all();
            $view->assign_form->equipeUsers = ORM::factory('userInfo')->order_by('nome', 'asc')->find_all();
            $view->assign_form->obj = $objeto;  
        }else{
            $view->reply_form = View::factory('admin/tasks/reply_form');
            $view->reply_form->task = ORM::factory('task')
                                        ->join('tasks_users', 'INNER')->on('tasks.id', '=', 'tasks_users.task_id')
                                        ->where('tasks_users.userInfo_id', '=', $this->current_user->userInfos->id)
                                        ->find();

            $view->reply_form->statusList = ORM::factory('statu')->find_all();
            $view->reply_form->equipeUsers = ORM::factory('userInfo')->order_by('nome', 'asc')->find_all();
        }
        
        $this->template->content = $view;

	}
    

	protected function salvar($id = null)
	{
		$db = Database::instance();
        $db->begin();
		
		try 
		{            
			$object = ORM::factory('object', $id)->values($this->request->post(), array( 
                    'title', 
                    'taxonomia', 
                    'typeobject_id', 
                    'collection_id', 
                    'supplier_id', 
                    'country_id',
                    'parent_id', 
                    'interatividade',
                    'fase', 
                    'data_lancamento', 
                    'sinopse', 
                    'uni', 
                    'cap', 
                    'status', ));
			
			$object->save();

			if(is_null($id)){
				$statusType = ORM::factory('status_type');
				$statusType->item_id = $object->id;
				$statusType->userInfo_id = $this->current_user->userInfos->id;
				$statusType->status_id = 1;
				$statusType->type = 'object';
				$statusType->save();
			}



			Utils_Helper::mensagens('add','Objeto salvo com sucesso.');
			$db->commit();
			Request::current()->redirect('admin/objects');

		}  catch (ORM_Validation_Exception $e) {
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
    public function action_getCollections($project_id){
		$this->auto_render = false;
		$view = View::factory('admin/objects/table');
		$view->project_id = $project_id;

		//$this->startProfilling();

		$view->filter_tipo = json_decode($this->request->query('tipo'));
		$view->filter_status = json_decode($this->request->query('status'));
		$view->filter_collection  = json_decode($this->request->query('collection'));
		$view->filter_supplier  = json_decode($this->request->query('supplier'));		

		$view->typeObjectsjsList = ORM::factory('objectStatu')->where('typeobject_id', 'IN', DB::Select('id')->from('typeobjects'))->where('project_id', '=', $project_id)->find_all();
		$view->statusList = ORM::factory('objectStatu')->where('status_id', 'IN', DB::Select('id')->from('status'))->where('project_id', '=', $project_id)->find_all();
		
		//$view->statusList = ORM::factory('object')->where('collection_id', 'IN', DB::Select('collection_id')->from('collections_projects')->where('project_id', '=', $project_id))->join('status_types')->on('objects.id', '=', 'status_types.item_id')->where('type', '=', 'object')->group_by('item_id')->execute();
		$view->collectionList = ORM::factory('objectStatu')->where('collection_id', 'IN', DB::Select('collection_id')->from('collections_projects'))->where('project_id', '=', $project_id)->find_all();
		$view->suppliersList = ORM::factory('objectStatu')->where('supplier_id', 'IN', DB::Select('id')->from('suppliers'))->where('project_id', '=', $project_id)->find_all();

		$query = ORM::factory('objectStatu')->where('fase', '=', '1')->where('project_id', '=', $project_id);

		/***Filtros***/
		(count($view->filter_tipo) > 0) ? $query->where('typeobject_id', 'IN', $view->filter_tipo) : '';
		//(count($view->filter_status ) > 0) ? $query->where('status_id', 'IN', $view->filter_status)->group_by('id') : '';
		(count($view->filter_collection ) > 0) ? $query->where('collection_id', 'IN', $view->filter_collection ) : '';
		(count($view->filter_supplier) > 0) ? $query->where('supplier_id', 'IN', $view->filter_supplier) : '';

		$view->objectsList = $query->order_by('data_lancamento','ASC')->find_all();
		
		// $this->endProfilling();
		echo $view;
	}    
}