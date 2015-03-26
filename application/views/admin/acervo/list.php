<div class="topo form" >
    <div class="filters clear">
        <form action='<?=URL::base();?>admin/acervo/getObjects/' id="frm_acervo" data-panel="#tabs_content" method="post" class="form">
            <input type="hidden" name="project_id" value="">
            <div class="left filter">
                <input type="text" class="round left" style="width:135px" name="taxonomia" placeholder="tax. ou título" value="" >                
            </div>

            <div class="left filter" >
                <ul>
                    <li class="round" >
                        <span class="round" id="segmento">segmento <?=(!empty($filter_segmento) ? "<img src='".URL::base()."public/image/admin/filter_active.png' />": "<img src='".URL::base()."public/image/admin/filter.png' />")?></span>
                        <div class="filter_panel round scrollable_content" data-bottom="false">
                            <ul>
                                <li class="round bar_button"><input type="checkbox" class="checkAll" id="filter_segmento" /><label for="filter_segmento">selecionar tudo</label></li>
                                <? foreach ($segmentoList as $segmento) {?>
                                    <li>
                                        <input class="filter_segmento" type="checkbox" name="segmento[]" value="<?=$segmento->id?>" id="col_<?=$segmento->id?>" <?=(in_array($segmento->id, $filter_segmento)) ? "checked" : ""?> />
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
                        <span class="round" id="collection">coleção <?=(!empty($filter_segmento) ? "<img src='".URL::base()."public/image/admin/filter_active.png' />": "<img src='".URL::base()."public/image/admin/filter.png' />")?></span>
                        <div class="filter_panel round">
                            <ul style="width:310px;">
                                <li class="round bar_button"><input type="checkbox" class="checkAll" id="filter_collection" /><label for="filter_collection">selecionar tudo</label></li>
                            </ul>
                            <div class="scrollable_content" data-bottom="false">
                                
                                <ul style="width:310px;">
                                    <? foreach ($collectionList as $collection) {?>
                                        <li>
                                            <input class="filter_collection" type="checkbox" name="collection[]" value="<?=$collection->id?>" id="col_<?=$collection->id?>" <?=(in_array($collection->id, $filter_collection)) ? "checked" : ""?> />
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
                        <span class="round" id="project">projetos <?=(!empty($filter_project) ? "<img src='".URL::base()."public/image/admin/filter_active.png' />": "<img src='".URL::base()."public/image/admin/filter.png' />")?></span>
                        <div class="filter_panel round" >
                            <ul>
                                <li class="round bar_button"><input type="checkbox" class="checkAll" id="filter_project" /><label for="filter_project">selecionar tudo</label></li>
                            </ul>
                            <div class="scrollable_content" data-bottom="false">
                                <ul >
                                    
                                    <? foreach ($projectList as $project) {?>
                                        <li>
                                            <input class="filter_project" type="checkbox" name="project[]" value="<?=$project->id?>" id="proj_<?=$project->id?>" <?=(in_array($project->id, $filter_project)) ? "checked" : ""?> />
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
                        <span class="round" id="typeobject">tipos de OED's <?=(!empty($filter_typeobject) ? "<img src='".URL::base()."public/image/admin/filter_active.png' />": "<img src='".URL::base()."public/image/admin/filter.png' />")?></span>
                        <div class="filter_panel round " >                        
                            <ul>
                                <li class="round bar_button"><input type="checkbox" class="checkAll" id="filter_typeobject" /><label for="filter_typeobject" style="color:#fff">selecionar tudo</label></li>
                            </ul>
                            <div class="scrollable_content" data-bottom="false">
                                <ul >
                                    
                                    
                                    <? foreach ($typeList as $typeobject) {?>
                                        <li>
                                            <input class="filter_typeobject" type="checkbox" name="tipo[]" value="<?=$typeobject->id?>" id="type_<?=$typeobject->id?>" <?=(in_array($typeobject->id, $filter_typeobject)) ? "checked" : ""?> />
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
            <div class="left" >
                <input type="submit" class="round bar_button left" value="buscar"> 
            </div>
        </form> 
        <div class="left filter">
            <form action='<?=URL::base();?>admin/acervo/getObjects/' id="frm_reset_acervo" data-panel="#tabs_content" method="post" class="form">
                <input type="hidden" name="reset_form" value="true">
                <input type="submit" class="bar_button round green" value="limpar filtros" />
            </form>
        </div>
    </div>
</div>
    <!--div class="left">
        <input type="text" class="round left" style="width:135px" name="taxonomia" placeholder="tax. ou título" value="" >
    </div>
    <div class="left">
        <select name="segmento_id" id="segmento_id" data-target="project_id" data-url="admin/acervo/getProjects" class="populate required round" style="width:150px;">
            <option value=''>segmento</option>
            <? foreach($segmentoList as $segmento){?>
                <option value='<?=$segmento->id?>' <?=((@$objVO["segmento_id"] == $segmento->id)?('selected'):(''))?> ><?=$segmento->name?></option>
            <? }?>
        </select>
    </div>
    <div class="clear left">
        <select name="project_id" id="project_id" data-target="collection_id" data-url="admin/acervo/getCollections" class="populate required round"  style="width:150px;">
            <option value=''>projeto</option>
        </select>
    </div>
    <div class="left">
        <select name="collection_id" id="collection_id" class="required round">
            <option value=''>coleções</option>
        </select>
    </div>
    <div class="clear left">
        <form action='<?=URL::base();?>admin/objects/getObjects/' id="frm_reset_oeds" data-panel="#tabs_content" method="post" class="form">
            <input type="hidden" name="reset_form" value="true">
            <input type="submit" class="bar_button round blue" value="buscar" />
        </form>
    </div>
    <div class="left">
        <form action='<?=URL::base();?>admin/objects/getObjects/' id="frm_reset_oeds" data-panel="#tabs_content" method="post" class="form">
            <input type="hidden" name="reset_form" value="true">
            <input type="submit" class="bar_button round blue" value="limpar filtros" />
        </form>
    </div-->
</div>
<div id="esquerda">
    <div class="fixed clear">
        <div id="tabs_content" class="scrollable_content clear">
            
        </div>
    </div>
</div>
<div id="direita"></div>