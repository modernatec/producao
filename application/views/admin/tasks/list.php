<div class="topo">
    <div id='filtros'>
    	<div class="tabs_panel">
		    <ul class="tabs">
		        <li><a id="task_1" class="aba ajax" href='<?=URL::base();?>admin/tasks/getTasks/?to=<?=$userInfo_id?>'>Minhas tarefas</a></li>
		        <li><a id="task_2" class="aba ajax" href='<?=URL::base();?>admin/tasks/getTasks/<?=$filter?>'><?=$title?></a></li>
		        <?if($current_auth != "assistente" || $current_auth == "coordenador" || $current_auth == "admin"){?>
		        <!--li class="round"><a id="tab_2" class="ajax" href='<?=URL::base();?>admin/tasks/ongoing'>em fluxo</a></li-->
		        <?}?>
		    </ul>  
		</div>
    </div>
</div>
<div id="esquerda">
    <div id="tabs_content" >
        
    </div>
</div>
<div id="direita"></div>