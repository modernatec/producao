<div class="header">
    <div class="left icon icon_task_white">tarefas</div>
    <span> Entregar tarefa</span>
</div>
<form id="formEndTask" name="formEndTask" action="<?=URL::base();?>admin/tasks_status/end" method="post" class="form">
    <input type="hidden" name='task_id' value="<?=$task->id?>" />
    <input type="hidden" name='object_id' value="<?=$task->object_id?>" />
    <input type="hidden" name='next_step' id="next_step" value="0" />
    <dt>
        <label for="description">observações</label>
    </dt>
    <dd>
        <textarea placeholder="observações" class="text round" name="description" id="description" style="width:470px; height:300px;"></textarea>
        <span class='error'><?=Arr::get($errors, 'description');?></span>
    </dd>
    <? if($task->tag_id == '1'){?>
        <input type="submit" value="liberar" id="submit_btn" class="bar_button round" />
    <?}else{?>
        <input type="submit" value="entregar" class="bar_button round" />
    <?}?>
    <a href="javascript:void(0)" class="close_pop bar_button round">cancelar</a> 
</form>