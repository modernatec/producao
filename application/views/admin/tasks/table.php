<div class="list_body">
<? 
if(count($taskList) <= 0){
    echo '<span class="list_alert round">nenhum registro encontrado</span>';    
}else{
    echo '<ul class="list_item" id="sortable">';
    foreach($taskList as $key=>$task){?>
        <li class="dd-item" id="item-<?=$task->id?>">
            <div class="list_order left"><?=$key+1?></div>
                <div class="left">
                    
                    <? 
                        if($task->task_to != "0"){
                            $nome = explode(" ", $task->to->nome);?>
                            <a href="<?=URL::base();?>admin/objects/view/<?=$task->object_id?>">
                                <div class="round_imgDetail <?=$task->to->team->color?>">
                                    <img class='round_imgList' src='<?=URL::base();?><?=($task->to->foto)?($task->to->foto):('public/image/admin/default.png')?>' height="20" style='float:left' alt="<?=ucfirst($task->to->nome);?>" />
                                    <span><?$nome = explode(" ", $task->to->nome); echo $nome[0];?></span>
                                    
                                </div>
                            </a>
                        <?}?>
                </div>
                <div class="left">
                    <p><a href="<?=URL::base();?>admin/objects/view/<?=$task->object_id?>"><b><?=$task->topic;?></b></a></p>
                    <p><a href="<?=URL::base();?>admin/objects/view/<?=$task->object_id?>"><?=$task->object->taxonomia;?></a></p>
                    <p class="red round list_faixa"><?=$task->status->status;?> &bull; retorno: <?=Utils_Helper::data($task->crono_date)?></p>
                </div>
            
        </li>
    <?}
    echo '<ul>';
}?>
</div>