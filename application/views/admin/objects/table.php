	<span class='list_alert cyan'>
	<?
        if(count($objectsList) <= 0){
            echo 'não encontrei objetos com estes critérios.';    
        }else{
            echo 'encontrei '. count($objectsList).' objeto(s)';
        }
    ?>
	</span>
	<div class="scrollable_content list_body">
		<ul class="list_item">
			<?foreach($objectsList as $objeto){
	    		$calendar = URL::base().'/public/image/admin/calendar2.png';
	    		$class_obj = "object_late";

	    		//$status = $objeto->status->order_by('id', 'DESC')->limit(1)->find();
	    		/*
	    		$last_status = ORM::factory('objects_statu')->where('object_id', '=', $objeto->id)->order_by('id', 'DESC')->limit('1')->find();
	    		//var_dump($last_status->status->status);
				*/
	    		
	    		if(strtotime($objeto->retorno) < strtotime(date("Y-m-d H:i:s")) && $objeto->status_id != '8'){
        			$class_obj = "object_late";
        		}else{
    				$class_obj 	= "object_status".$objeto->status_id;
    			}
    			

    			$diff = '';
                if(!empty($objeto->diff) && $objeto->diff != 0){
                    if($objeto->diff < 0){
                        $diff = '<span class="list_faixa green round">'.$objeto->diff.'</span>';
                        //$status_class = 'green';
                    }else{
                        $diff = '<span class="list_faixa red round">+'.$objeto->diff.'</span>';
                        $status_class = 'red';
                    }
                }

    			$taskList = $objeto->tasks->where('ended', '=', '0')->find_all();
			?>
			<li>
				<a class="load" href="<?=URL::base().'admin/objects/view/'.$objeto->id?>" rel="load-content" data-panel="#direita" title="+ informações">
					<p><b><?=$objeto->title?></b><br/><?=$objeto->taxonomia?></p>
					
					<div class="clear">
						<?=$objeto->statu_status?> | <?=($objeto->retorno != '') ? Utils_Helper::data($objeto->retorno,'d/m/Y') : 'aguardando definição'?> <?=$diff?>
					</div>
					<?foreach ($taskList as $task) {
						$task_to = ($task->task_to != 0) ? Utils_Helper::getUserImage($task->to) : '<div class="round_imgList"><span>?</span></div>';

						$task_diff = '';

		                if(!empty($task->diff) && $task->diff != 0){
                            if($task->diff < 0){
                                $task_diff = '<span class="list_faixa green round">'.$task->diff.'</span>';
                            }else{
                                $task_diff = '<span class="list_faixa red round">+'.$task->diff.'</span>';
                            }
                        }
					?>
						<div class="clear task_line" >
							<div class='left'><?=$task_to;?></div>
							<span class="round list_faixa left tag" style="background:<?=$task->tag->color?>"><?=$task->tag->tag?></span>	
							<span class="round <?=$task->status->type.'_status'.$task->status->id?> list_faixa left"><?=$task->status->status;?></span><?=$task_diff?>
						</div>
					<?}?>
				</a>
			</li>
			<?}?>
		</ul>
	</div>
