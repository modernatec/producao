
    <div class="filters clear">
        <form action='<?=URL::base();?>admin/acervo/getObjects/' id="frm_acervo" data-panel="#tabs_content" method="post" class="form">
            <input type="hidden" name="acervo" value="1">
            <div class="filter">
                <input type="text" class="round left" style="width:200px" name="taxonomia" placeholder="título ou palavra-chave" value="<?=@$filter_taxonomia?>" >                
            </div>
            <div class="filter" >
                <ul>
                    <li class="round" >

                        <span class="round" id="segmento">segmento <?=(!empty($filter_segmento) ? "<img src='".URL::base()."public/image/admin/filter_active.png' />": "<img src='".URL::base()."public/image/admin/filter.png' />")?></span>
                        <div class="filter_panel round scrollable_content" data-bottom="false">
                            <ul>
                                <li class="round bar_button"><input type="checkbox" class="checkAll" id="filter_segmento" /><label for="filter_segmento">selecionar tudo</label></li>
                                <? foreach ($segmentoList as $segmento) {?>
                                    <li>
                                        <input class="filter_segmento" type="checkbox" name="segmento[]" value="<?=$segmento->id?>" id="col_<?=$segmento->id?>" <?if(isset($filter_segmento)){ if(in_array($segmento->id, $filter_segmento)){ echo "checked";}}?> />
                                        <label for="col_<?=$segmento->id?>"><?=$segmento->name?></label>
                                    </li>
                                <?}?>
                                
                            </ul>
                            <p>
                                <input type="submit" class="round bar_button" value="buscar" /> 
                                <input type="button" class="round bar_button cancelar" value="cancelar" /> 
                            </p> 
                        </div>
                    </li>
                </ul>
            </div>
            <div class="filter" >
                <ul>
                    <li class="round" >
                        <span class="round" id="collection">coleção <?=(!empty($filter_collection) ? "<img src='".URL::base()."public/image/admin/filter_active.png' />": "<img src='".URL::base()."public/image/admin/filter.png' />")?></span>
                        <div class="filter_panel round">
                            <ul style="width:550px;">
                                <li class="round bar_button"><input type="checkbox" class="checkAll" id="filter_collection" /><label for="filter_collection">selecionar tudo</label></li>
                            </ul>
                            <div class="scrollable_content" data-bottom="false">
                                
                                <ul>
                                    <? foreach ($collectionList as $collection) {?>
                                        <li>
                                            <input class="filter_collection" type="checkbox" name="collection[]" value="<?=$collection->id?>" id="col_<?=$collection->id?>" <?if(isset($filter_collection)){ if(in_array($collection->id, $filter_collection)){ echo "checked";}}?> />
                                            <label for="col_<?=$collection->id?>"><?=$collection->name?></label>
                                        </li>
                                    <?}?>
                                    
                                </ul>
                            </div>
                            <p>
                                <input type="submit" class="round bar_button" value="buscar" /> 
                                <input type="button" class="round bar_button cancelar" value="cancelar" /> 
                            </p> 
                        </div>
                    </li>
                </ul>
            </div>

            <div class="filter" >
                <ul>
                    <li class="round" >
                        <span id="reaproveitamento">origem <?=(!empty($filter_origem) ? "<img src='".URL::base()."public/image/admin/filter_active.png' />": "<img src='".URL::base()."public/image/admin/filter.png' />")?></span>
                        <div class="filter_panel round " >
                            <ul>
                                <li class="round bar_button"><input type="checkbox" class="checkAll" id="filter_origem" /><label for="filter_origem">selecionar tudo</label></li>
                            </ul>
                            <div class="scrollable_content" data-bottom="false">
                                <ul>
                                    <li><input type="checkbox" class="filter_origem" name="origem[]" value="0" id="o_0" <?if(isset($filter_origem)){ if(in_array("0", $filter_origem)){ echo "checked";}}?> ><label for="o_0">novo</label></li>
                                    <li><input type="checkbox" class="filter_origem" name="origem[]" value="1" id="o_1" <?if(isset($filter_origem)){ if(in_array("1", $filter_origem)){ echo "checked";}}?> ><label for="o_1">reap.</label></li>
                                    <li><input type="checkbox" class="filter_origem" name="origem[]" value="2" id="o_2" <?if(isset($filter_origem)){ if(in_array("2", $filter_origem)){ echo "checked";}}?> ><label for="o_2">reap. integral</label></li>
                                </ul>
                            </div>
                            <p>
                                <input type="submit" class="round bar_button" value="buscar" /> 
                                <input type="button" class="round bar_button cancelar" value="cancelar" />  
                            </p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="filter" >
                <ul>
                    <li class="round" >
                        <span class="round" id="project">projetos <?=(!empty($filter_project) ? "<img src='".URL::base()."public/image/admin/filter_active.png' />": "<img src='".URL::base()."public/image/admin/filter.png' />")?></span>
                        <div class="filter_panel round" >
                            <ul>
                                <li class="round bar_button"><input type="checkbox" class="checkAll" id="filter_project" /><label for="filter_project">selecionar tudo</label></li>
                            </ul>
                            <div class="scrollable_content" data-bottom="false">
                                <ul >
                                    
                                    <? foreach ($projectList as $project) {?>
                                        <li>
                                            <input class="filter_project" type="checkbox" name="project[]" value="<?=$project->id?>" id="proj_<?=$project->id?>" <?if(isset($filter_project)){ if(in_array($project->id, $filter_project)){ echo "checked";}}?> />
                                            <label for="proj_<?=$project->id?>"><?=$project->name?></label>
                                        </li>
                                    <?}?>
                                    
                                </ul>
                            </div>
                            <p>
                                <input type="submit" class="round bar_button" value="buscar" /> 
                                <input type="button" class="round bar_button cancelar" value="cancelar" /> 
                            </p> 
                        </div>
                    </li>
                </ul>
            </div>

            <div class="left filter" >
                <ul>
                    <li class="round" >
                        <span class="round" id="typeobject">tipos de OED's <?=(!empty($filter_tipo) ? "<img src='".URL::base()."public/image/admin/filter_active.png' />": "<img src='".URL::base()."public/image/admin/filter.png' />")?></span>
                        <div class="filter_panel round " style="width:200px;" >                        
                             <ul style="width:300px;">
                                <li class="round bar_button"><input type="checkbox" class="checkAll" id="filter_typeobject" /><label for="filter_typeobject" style="color:#fff">selecionar tudo</label></li>
                            </ul>
                            <div class="scrollable_content" data-bottom="false">
                                <ul >
                                    <? foreach ($typeList as $typeobject) {?>
                                        <li>
                                            <input class="filter_typeobject" type="checkbox" name="tipo[]" value="<?=$typeobject->id?>" id="type_<?=$typeobject->id?>" <?if(isset($filter_tipo)){ if(in_array($typeobject->id, $filter_tipo)){ echo "checked";}}?> />
                                            <label for="type_<?=$typeobject->id?>"><?=$typeobject->name?></label>
                                        </li>
                                    <?}?>
                                </ul>
                            </div>
                            <p>
                                <input type="submit" class="round bar_button" value="buscar" /> 
                                <input type="button" class="round bar_button cancelar" value="cancelar" /> 
                            </p> 
                        </div>
                    </li>
                </ul>
            </div>
            <input type="submit" class="round bar_button left" value="buscar"> 
        </form> 
        <div class="left filter">
            <form action='<?=URL::base();?>admin/acervo/getObjects/' id="frm_reset_acervo" data-panel="#tabs_content" method="post" class="form">
                <input type="hidden" name="acervo" value="true">
                <input type="submit" class="bar_button round" value="limpar filtros" />
            </form>
        </div>
    </div>