<div class="topo" >
    <span class="header"><a href="<?=URL::base();?>admin/status/edit" rel="load-content" data-panel="#direita" class="bar_button round">cadastrar status</a></span>
</div>
<div id="esquerda">
    <div id="tabs_content" class="scrollable_content clear">
        <ul class="list_item">
            <? foreach($statusList as $status){?>
            <li>
                <a class="right excluir" href="<?=URL::base().'admin/status/delete/'.$status->id;?>" title="Excluir">Excluir</a>
                <span class="left ball <?=$status->class;?>"></span>
                <a style='display:block' href="<?=URL::base().'admin/status/edit/'.$status->id;?>" rel="load-content" data-panel="#direita" title="Editar"><?=$status->status?></a>
            </li>
            <?}?>
        </ul>
    </div>
</div>
<div id="direita"></div>