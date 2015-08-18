<a href="javascript:void(0)" class="close_pop bar_button round right">fechar</a> 
<? if(count($array_pathFoward) > 0 || count($array_path) > 0 ){?>
<a class="collapse left round" data-show="replies" title="abrir/fechar infos"><span class="expand_ico">contrair</span></a>
<?}?>
<div class="clear left" style="width:400px;">
    <div class="clear">  
        <ul class="list_item"> 
            <?
            foreach ($array_pathFoward as $objeto) {?>
                <li>
                    <?
                        $view = View::factory('admin/acervo/list_item');
                        $view->objeto = $objeto;
                        $view->current_auth = $current_auth;
                        echo $view;
                    ?>
                </li>
            <?};?>
        </ul>
        <div class="boxwired_selected round">
            <?if($obj->uploaded == 1){?>
            <a href='<?=URL::base();?>/admin/acervo/acervoPreview/<?=$obj->id?>' data-rel="load-content" data-panel="#preview" class="bar_button round right load acervo_view">visualizar</a>
            <?
                if($current_auth != "assistente" || $current_auth != "assistente 2"){
                    
            ?>
            <a href='<?=URL::base();?>/admin/acervo/download/<?=$obj->id?>' class="bar_button round right">baixar</a>
            <?
                    }
                }
            ?>
            
            <b><span class="wordwrap"><?=@$obj->title;?></span></b><br/>
            <span class="wordwrap"><?=@$obj->taxonomia;?></span>
            <hr style="margin:8px 0;" />
            
            <p><?=@$obj->collection->name?></p>
            <?if($obj->reaproveitamento == 0){ 
                $origem = "novo";
            }elseif($obj->reaproveitamento == 1){
                $origem = "reap.";
            }else{
                $origem = "reap. integral";
            }?>
            <div class="clear">
                <span class="list_faixa cyan round left"><?=$obj->collection->segmento->name?></span>
                <span class="list_faixa cyan round left"><?=$obj->collection->materia->name?></span>
                <span class="list_faixa light_blue round left"><?=$origem?></span>
                <span class="list_faixa light_blue round left"><?=@$obj->typeobject->name;?></span>
                <span class="list_faixa light_blue round left"><?=@$obj->collection->ano?></span>
            </div>
            <table class="clear">
                <thead>
                    <th>formato</th>
                    <th>interativo</th>
                    <th>legendado</th>
                    <th>editável</th>
                    <th>cessão</th>
                </thead>
                <tbody>
                    <tr>
                        <td><?=$obj->format->name;?></td>
                        <td><?=(@$obj->interatividade == 0) ? 'não' : 'sim'; ?></td>
                        <td><?=(@$obj->transcricao == 0) ? 'não' : 'sim'; ?></td>
                        <td><?=(@$obj->arq_aberto == 0) ? 'não' : 'sim'; ?></td>
                        <td><?=(@$obj->cessao == 0) ? 'não' : 'sim'; ?></td>
                    </tr>
                </tbody>
            </table>
            <table class="clear">
                <thead>
                    <th>un.</th>
                    <th>cap.</th>
                    <th>pág.</th>
                    <th>tamanho</th>
                    <th>duração</th>
                    <th>resultado PNLD</th>
                </thead>
                <tbody>
                    <tr>
                        <td><?=(@$obj->uni != '') ? @$obj->uni : '-'; ?></td>
                        <td><?=(@$obj->cap != '') ? @$obj->cap : '-'; ?></td>
                        <td><?=(@$obj->pagina != '') ? @$obj->pagina : '-'; ?></td>
                        <td><?=(@$obj->tamanho != '') ? @$obj->tamanho : '-'; ?></td>
                        <td><?=(@$obj->duracao != '') ? @$obj->duracao : '-'; ?></td>
                        <td>
                            <?
                                /*melhorar*/
                                switch($obj->pnld){
                                    case '1':
                                        echo 'não se aplica';
                                        break;
                                    case '2':
                                        echo 'aprovado';
                                        break;
                                    case '3':
                                        echo 'reprovado';
                                        break;
                                    case '4':
                                        echo 'não avaliado';
                                        break;
                                }; 
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="clear">
                <div class="clear">
                    <?
                        $repos = $obj->repositorios->find_all();
                        if(count($repos) > 0){
                    ?>
                        <p class="text_blue clear">compartilhado com</p>
                        <?
                        foreach ($repos as $repo) {?>
                            <span class="list_faixa light_blue round left"><?=$repo->name?></span>
                    <?  }
                        }?>
                </div>  
            </div>
        </div>
        <ul class="list_item">           
        <?
            foreach ($array_path as $objeto) {?>
                <li>
                    <?
                        $view = View::factory('admin/acervo/list_item');
                        $view->objeto = $objeto;
                        $view->current_auth = $current_auth;
                        echo $view;
                    ?>
                </li>
        <?};?>
        </ul>
    </div> 
</div>
<div class="left hide" id="acervo_preview">
    <iframe class="iframe_body" frameBorder="0" scrolling="no" src="" allowtransparency="true" ></iframe>
</div>
