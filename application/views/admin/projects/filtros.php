    <div class="filters clear">
        <form action='<?=URL::base();?>admin/projects/getList' id="filter_projects" data-panel="#tabs_content" method="post" class="form">
            <input type="hidden" name="projects" value="1">
            <div class="left filter">
                <input type="text" class="round" style="width:200px" name="name" placeholder="nome" value="<?=@$filter_name?>" >
            </div>
            <div class="left filter" >
                <ul>
                    <li class="round" >
                        <span id="reaproveitamento">status <?=(!empty($filter_status) ? "<img src='".URL::base()."public/image/admin/filter_active.png' />": "<img src='".URL::base()."public/image/admin/filter.png' />")?></span>
                        <div class="filter_panel round " >
                            <ul>
                                <li class="round bar_button"><input type="checkbox" class="checkAll" id="filter_status" /><label for="filter_status">selecionar tudo</label></li>
                            </ul>
                            <div class="scrollable_content" data-bottom="false">
                                <ul>
                                    <li><input type="checkbox" class="filter_status" name="status[]" value="1" id="o_1" <?if(isset($filter_status)){ if(in_array("1", $filter_status)){ echo "checked";}}?> ><label for="o_1">produzindo</label></li>
                                    <li><input type="checkbox" class="filter_status" name="status[]" value="0" id="o_0" <?if(isset($filter_status)){ if(in_array("0", $filter_status)){ echo "checked";}}?> ><label for="o_0">finalizado</label></li>
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
                        <span class="round" id="segmento">segmento <?=(!empty($filter_segmento) ? "<img src='".URL::base()."public/image/admin/filter_active.png' />": "<img src='".URL::base()."public/image/admin/filter.png' />")?></span>
                        <div class="filter_panel round scrollable_content" data-bottom="false">
                            <ul>
                                <li class="round bar_button"><input type="checkbox" class="checkAll" id="filter_segmento" /><label for="filter_segmento">selecionar tudo</label></li>
                                <? foreach ($segmentoList as $segmento) {?>
                                    <li>
                                        <input class="filter_segmento" type="checkbox" name="segmento[]" value="<?=$segmento->id?>" id="col_<?=$segmento->id?>" <?if(isset($filter_segmento)){ if(in_array($segmento->id, $filter_segmento)){ echo "checked";}}?>  />
                                        <label for="col_<?=$segmento->id?>"><?=$segmento->name?></label>
                                    </li>
                                <?}?>
                                <p>
                                    <input type="submit" class="round bar_button" value="OK" /> 
                                    <input type="button" class="round bar_button cancelar" value="Cancelar" /> 
                                </p> 
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            
            
            <div class="left filter" >
                <ul>
                    <li class="round" >
                        <span class="round" id="ano">ano <?=(!empty($filter_ano) ? "<img src='".URL::base()."public/image/admin/filter_active.png' />": "<img src='".URL::base()."public/image/admin/filter.png' />")?></span>
                        <div class="filter_panel round" >
                            <ul>
                                <li class="round bar_button"><input type="checkbox" class="checkAll" id="filter_ano" /><label for="filter_ano">selecionar tudo</label></li>
                            </ul>
                            <div class="scrollable_content" data-bottom="false">
                                <ul>                                    
                                    <? foreach ($anosList as $ano) {?>
                                        <li>
                                            <input class="filter_ano" type="checkbox" name="ano[]" value="<?=$ano->ano?>" id="ano_<?=$ano->ano?>" <?if(isset($filter_ano)){ if(in_array($ano->ano, $filter_ano)){ echo "checked";}}?>  />
                                            <label for="ano_<?=$ano->ano?>"><?=$ano->ano?></label>
                                        </li>
                                    <?}?>
                                    
                                </ul>
                            </div>
                            <input type="submit" class="round bar_button" value="OK" /> 
                            <input type="button" class="round bar_button cancelar" value="Cancelar" /> 
                        </div>
                    </li>
                </ul>
            </div>
            <input type="submit" class="round bar_button left" value="buscar"> 
        </form> 
        <div class="left filter">
            <form action='<?=URL::base();?>admin/projects/getList/' id="reset_filter_projects" data-panel="#tabs_content" method="post" class="form">
                <input type="hidden" name="projects" value="true">
                <input type="submit" class="bar_button round" value="limpar filtros" />
            </form>
        </div>
    </div>