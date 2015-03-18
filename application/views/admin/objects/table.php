<div class="fixed clear">
	<div class="list_header round">
		<div class="table_info round">
			<div class="left"><?=count($objectsList)?> objetos encontrados</div>
			<div class="left">
				<form action='<?=URL::base();?>admin/objects/getObjects/<?=$project_id?>' id="frm_reset_oeds" data-panel="#tabs_content" method="post" class="form">
					<input type="hidden" name="reset_form" value="true">
					<input type="submit" class="bar_button round green" value="limpar filtros" />
				</form>
			</div>
		</div>
		<div class="filters clear">
		<form action='<?=URL::base();?>admin/objects/getObjects/<?=$project_id?>' id="frm_oeds" data-panel="#tabs_content" method="post" class="form">
				<input type="hidden" name="project_id" value="<?=$project_id?>">
				<div>
					<input type="text" class="round left" style="width:135px" name="taxonomia" placeholder="tax. ou título" value="<?=$filter_taxonomia?>" >
	       			<input type="submit" class="round bar_button left" value="OK"> 
	       		</div>

	       		<div class="clear filter" >
				    <ul>
				        <li class="round" >
				            <span class="round" id="colecao">coleção <?=(!empty($filter_collection) ? "<img src='".URL::base()."public/image/admin/filter_active.png' />": "<img src='".URL::base()."public/image/admin/filter.png' />")?></span>
				            <ul class="round" style="width:400px;">
				                <li class="round bar_button"><input type="checkbox" class="checkAll" id="filter_collection" checked /><label for="filter_collection">selecionar tudo</label></li>
				                <? foreach ($collectionList as $json_collection) { $collection = json_decode($json_collection);?>
				                	<li>
				                		<input class="filter_collection" type="checkbox" name="collection[]" value="<?=$collection->collection_id?>" id="col_<?=$collection->collection_id?>" <?if(empty($filter_collection)){ echo "checked"; }?> <?=(in_array($collection->collection_id, $filter_collection)) ? "checked" : ""?> />
				                		<label for="col_<?=$collection->collection_id?>"><?=$collection->collection_name?></label>
				                	</li>
				                <?}?>
				                <p>
					                <input type="submit" class="round bar_button" value="OK" /> 
					                <input type="button" class="round bar_button cancelar" value="Cancelar" /> 
					            </p> 
				            </ul>
				        </li>
				    </ul>
				</div>

				<div class="filter" >
				    <ul>
				        <li class="round" >
				            <span class="round" id="colecao">matéria <?=(!empty($filter_materia) ? "<img src='".URL::base()."public/image/admin/filter_active.png' />": "<img src='".URL::base()."public/image/admin/filter.png' />")?></span>
				            <ul class="round" >
					                <li class="round bar_button"><input type="checkbox" class="checkAll" id="filter_materia" checked /><label for="filter_materia">selecionar tudo</label></li>

					                <?foreach ($materiasList as $json_materia) { $materia = json_decode($json_materia);?>
					                	<li>
					                		<input class="filter_materia" type="checkbox" name="materia[]" value="<?=$materia->materia_id?>" id="mat_<?=$materia->materia_id?>" <?if(empty($filter_materia)){ echo "checked"; }?> <?=(in_array($materia->materia_id, $filter_materia)) ? "checked" : ""?> />
					                		<label for="mat_<?=$materia->materia_id?>"><?=$materia->materia_name?></label>
					                	</li>
					                <?}?>
					                <p>
						                <input type="submit" class="round bar_button" value="OK" /> 
						                <input type="button" class="round bar_button cancelar" value="Cancelar" /> 
						            </p>
				            </ul>
				        </li>
				    </ul>
				</div>

				

				<div class="filter" >
				    <ul>
				        <li class="round" >
				            <span class="round" id="status">status <?=(!empty($filter_status) ? "<img src='".URL::base()."public/image/admin/filter_active.png' />": "<img src='".URL::base()."public/image/admin/filter.png' />")?></span>
				            <ul class="round" >
				            	<li class="round bar_button"><input type="checkbox" class="checkAll" id="filter_status" checked /><label for="filter_status">selecionar tudo</label></li>
				                <? foreach ($statusList as $json_status) { $status = json_decode($json_status);?>
				                	<li>
				                		<input type="checkbox" class="filter_status" name="status[]" value="<?=$status->status_id?>" id="sta_<?=$status->status_id?>" <?if(empty($filter_status)){ echo "checked"; }?> <?=(in_array($status->status_id, $filter_status)) ? "checked" : ""?> />
				                		<label for="sta_<?=$status->status_id?>" ><?=$status->statu_status?></label>
				                	</li>
				                <?}?>
				                <p>
					                <input type="submit" class="round bar_button" value="OK" /> 
					                <input type="button" class="round bar_button cancelar" value="Cancelar" /> 
					            </p> 
				            </ul>
				        </li>
				    </ul>
				</div>

				<div class="filter" >
				    <ul>
				        <li class="round" >
				            <span id="supplier">produtora <?=(!empty($filter_supplier) ? "<img src='".URL::base()."public/image/admin/filter_active.png' />": "<img src='".URL::base()."public/image/admin/filter.png' />")?></span>
				            <ul class="round" >
				            	<li class="round bar_button"><input type="checkbox" class="checkAll" id="filter_supplier" checked /><label for="filter_supplier">selecionar tudo</label></li>
				                <? foreach ($suppliersList as $json_supplier) { $supplier = json_decode($json_supplier);?>
				                <li>
				                	<input class="filter_supplier" type="checkbox" name="supplier[]" value="<?=$supplier->supplier_id?>" id="s_<?=$supplier->supplier_id?>" <?if(empty($filter_supplier)){ echo "checked"; }?> <?=(in_array($supplier->supplier_id, $filter_supplier)) ? "checked" : ""?> />
				                	<label for="s_<?=$supplier->supplier_id?>"><?=$supplier->supplier_empresa?></label>
				                </li>
				                <?}?>
				                <p>
					                <input type="submit" class="round bar_button" value="OK" /> 
					                <input type="button" class="round bar_button cancelar" value="Cancelar" /> 
					            </p>
				            </ul>
				        </li>
				    </ul>
				</div>

				<div class="clear filter" >
				    <ul>
				        <li class="round" >
				        	<span id="tipo">tipo <?=(!empty($filter_tipo) ? "<img src='".URL::base()."public/image/admin/filter_active.png' />": "<img src='".URL::base()."public/image/admin/filter.png' />")?></span>
				            <ul class="round" >	
					            	<li class="round bar_button"><input type="checkbox" class="checkAll" id="filter_tipo" checked /><label for="filter_tipo">selecionar tudo</label></li>

					                <? foreach ($typeObjectsList as $json_typeobject) {
					                	$typeobject = json_decode($json_typeobject);
					                ?>
					                	<li><input class="filter_tipo" type="checkbox" name="tipo[]" value="<?=$typeobject->typeobject_id?>" id="t_<?=$typeobject->typeobject_id?>" <?if(empty($filter_tipo)){ echo "checked"; }?> <?=(in_array($typeobject->typeobject_id, $filter_tipo)) ? "checked" : ""?>><label for="t_<?=$typeobject->typeobject_id?>"><?=$typeobject->typeobject_name?></label></li>
					                <?}?>
					                <p>
						                <input type="submit" class="round bar_button" value="OK" /> 
						                <input type="button" class="round bar_button cancelar" value="Cancelar" /> 
						            </p>
				            </ul>
				        </li>
				    </ul>
				</div>

				<div class="filter" >
				    <ul>
				        <li class="round" >
				            <span id="reaproveitamento">origem <?=(!empty($filter_origem) ? "<img src='".URL::base()."public/image/admin/filter_active.png' />": "<img src='".URL::base()."public/image/admin/filter.png' />")?></span>
				            <ul class="round" >
				            	<li class="round bar_button"><input type="checkbox" class="checkAll" id="filter_origem" checked /><label for="filter_origem">selecionar tudo</label></li>

				                <li><input type="checkbox" class="filter_origem" name="origem[]" value="0" id="o_0" <?if(empty($filter_origem)){ echo "checked"; }?> <?=(in_array("0", $filter_origem)) ? "checked" : ""?>><label for="o_0">novo</label></li>
				                <li><input type="checkbox" class="filter_origem" name="origem[]" value="1" id="o_1" <?if(empty($filter_origem)){ echo "checked"; }?> <?=(in_array("1", $filter_origem)) ? "checked" : ""?>><label for="o_1">reap.</label></li>
				                
				                <p>
					                <input type="submit" class="round bar_button" value="OK" /> 
					                <input type="button" class="round bar_button cancelar" value="Cancelar" /> 
					            </p>
				            </ul>
				        </li>
				    </ul>
				</div>
			
		</form>	
		</div>
	</div>
	<div class="scrollable_content list_body">
	    <? 
		if(count($objectsList) <= 0){
			echo '<span class="list_alert round">nenhum registro encontrado</span>';	
		}else{
		?>
		<ul class="list_item">
			<?foreach($objectsList as $objeto){
				$status = "";
	    		$tag = "";
	    		$task_to = "";
				
				switch($objeto->status_id){
	    			case 1:
	    				if(strtotime($objeto->retorno) < strtotime(date("Y-m-d H:i:s"))){
	            			$class_obj = "object_late";
	            		}else{
	        				$class_obj 	= $objeto->statu_class;
	        			}
	    				break;
	    			case 2:
	    				$mod = "";
	    				if($objeto->supplier_id != 10){//producao externa
	    					$mod = "_out";	
	    				}else{
	    					$mod = "_in"; 
	    				}

	    				if(strtotime($objeto->retorno) < strtotime(date("Y-m-d H:i:s"))){
	            			$class_obj = "object_late";
	            		}else{
	        				$class_obj 	= $objeto->statu_class.$mod;
	        			}

	        			

	        			if(is_object($objeto->getStatus($objeto->object_status_id))){
			    			$obj_taskView = $objeto->getStatus($objeto->object_status_id); 
			    			
			    			if($obj_taskView->tag->id == '7' && $obj_taskView->status->id == '7'){
			    				$status = "";
			    				$tag = "";
					    	}else{
					    		$status = '<span class="round '.$obj_taskView->status->class.' list_faixa">'.$obj_taskView->status->status.'</span>';
				    			$tag = '<span class="round list_faixa '.$obj_taskView->tag->class.'">'.$obj_taskView->tag->tag.'</span>';	
				    		}

				    		if($obj_taskView->task_to != 0){
				    			$nome = explode(" ", $obj_taskView->to->nome); 
				    			$img = ($obj_taskView->to->foto)?($obj_taskView->to->foto):('public/image/admin/default.png');
				    			$task_to = ($status != '') ? Utils_Helper::getUserImage($obj_taskView->to) : '';
                            }
			    		}
	   				
	    				break;
	    			case 8://finalizado
	    				$class_obj 	= $objeto->statu_class;
	    				$class 		= $objeto->statu_class;
	    				break;	
	    			default:
	    				if(strtotime($objeto->retorno) < strtotime(date("Y-m-d H:i:s"))){
	            			$class_obj = "object_late";
	            		}else{
	        				$class_obj 	= $objeto->statu_class;
	        			}
	    		}
	    		//href="
			?>
			<li>

				<a class="load" href="<?=URL::base().'admin/objects/view/'.$objeto->id?>" rel="load-content" data-panel="#direita" title="+ informações">
					<div>
						<p><b><?=$objeto->taxonomia?></b></p>
						<hr style="margin:8px 0;" />
						<?if($objeto->supplier_id != 10){ //moderna(interno)?>
							<p><span class="light_blue round list_faixa"><?=$objeto->supplier_empresa?></span></p>
						<?}?>
						<p>
							<span class="<?=$class_obj?> round list_faixa"><?=$objeto->statu_status?> &bull; <?=$objeto->prova?></span> <span class="red round list_faixa"><img src="<?=URL::base()?>/public/image/admin/calendar2.png" height="16" valign='middle'> <?=Utils_Helper::data($objeto->retorno,'d/m/Y')?></span>
							<div>
								<div class='left' style="width:25px;"><?=$task_to;?></div>
								<?=$tag;?> 
								<?=$status;?> 
								
							</div>
						</p>
					</div>
				</a>
			</li>
			<?}?>
		</ul>
		<?}?>
	</div>
</div>