<label><b>tarefas</b></label><hr/>
<div class="left" style="min-width:478px;">
    <a class="collapse right" data-show="append" title="abrir/fechar infos"><span class="collapse_ico">contrair</span></a>
        
    <form name="frmCreateTask2" id="frmCreateTask2"  data-panel="#direita" action="<?=URL::base();?>admin/tasks/salvar/<?=@$taskVO['id']?>" method="post" class="form">
    	
    	<input type="hidden" name="object_id" value="<?=@$taskVO['object_id']?>">
    	<input type="hidden" name="status_id" value="5">
        <input type="hidden" name="object_status_id" value="<?=@$taskVO['object_status_id']?>">
    	<dl>
    		<div class="left">
    			<dt>
    	            <label for="tag_id">tarefas</label>
    	        </dt>
    	        <dd>
    	            <select name="tag_id" id="tag_id" class="required round" style="width:150px;">
    	                <option value="">selecione</option>
    	                <? foreach($status_tagList as $status_tag){?>
    	                    <option value="<?=$status_tag->tag_id?>" data-days="<?=$status_tag->days?>" <?=($taskVO['tag_id'] == $status_tag->tag_id) ? "selected" : ""?> ><?=$status_tag->tag->tag?></option>
    	                <?}?>
    	            </select>
    	            <span class='error'><?=Arr::get(@$errors, 'tag_id');?></span>
    	        </dd>
    	    </div>  
            <div class="left">  
        		<dt>
                    <label for="crono_date">retorno para:</label>
                </dt>
                <dd>
                    <input type="text" name="crono_date" id="crono_date" class="required round date" style="width:100px;" value="<?=@$taskVO['crono_date']?>" />
                    <span class='error'><?=Arr::get($errors, 'crono_date');?></span>
                </dd>
            </div>	
            <div class="left">  
                <dt>
                    <label for="task_to">para:</label>
                </dt>
                <dd>
                    <select name="task_to" id="task_to" class="required round" style="width:150px;">
                        <option value="0">selecione</option>
                        <? foreach($teams as $team){?>
                            <optgroup label="<?=$team->name?>">
                            <? foreach($teamList as $userInfo){ if($userInfo->team_id == $team->id){?>
                                    <option value="<?=$userInfo->id?>" <?=($taskVO['task_to'] == $userInfo->id) ? "selected" : ""?> ><?=$userInfo->nome?></option>
                                <?}}?>
                            </optgroup>
                        <?}?>                    
                    </select>
                    <span class='error'><?=Arr::get($errors, 'task_to');?></span>
                </dd>
            </div>
            <div class="clear">		
                <dt>
                	<label for="description">observações</label>
                </dt>
                <dd>
                      <textarea class="text required round" name="description" id="description" style="width:420px; height:300px;"><?=@$taskVO['description']?></textarea>
                      <span class='error'><?=Arr::get($errors, 'description');?></span>
                </dd>
            </div>
            <!--input type="checkbox" name="sendmail" id="sendmail" value="1"><label for="sendmail">enviar e-mail de atualização</label-->
            <dd>
              <input type="submit" class="round green" value="salvar" />  
              <a href="javascript:void(0)" class="close_pop bar_button round">cancelar</a>                  
            </dd>	    
    	</dl>
    </form>
</div>
<div class="append left">
    <?if($current_auth != "assistente"){?>
    <div class="clear"><label>sugestão de tarefas  </label></div>
    <div class="suggestions">
        <?
        $x = 0;
        foreach ($status_tagList as $status_tag) {
            $class = ($status_tag->sync == '0') ? 'list_view' : 'list_view_sub';
            if($status_tag->sync == '0'){$x++;}
            $display = ($status_tag->sync == '0') ? $x : '';
        ?>
            <span class="<?=$class?> round clear"><span class="left ball gray"><?=$display;?></span><?=$status_tag->tag->tag;?></span>
        <?}?>
    </div>
    <?}
    ?>  
</div>