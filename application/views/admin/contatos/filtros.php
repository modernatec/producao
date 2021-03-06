<div class="filters second_filter clear">
<form action="<?=URL::base();?>admin/contatos/getContatos" id="frm_suppliers" data-panel="#tabs_content" method="post" class="form">
	<input type="hidden" name="contatos" value="true">
		<div class="left">
			<input type="text" class="round" style="width:310px" placeholder="nome ou email" name="nome" value="<?=@$filter_nome?>" >
   		</div>
   		<div class="filter" >
		    <ul>
		        <li>
		            <span id="service_id" class="<?=(!empty($filter_service_id)) ? 'filter_active': '';?>">tipo <div class="icon_filtros <?=(!empty($filter_service_id)) ? 'icon_filter_active': 'icon_filter';?>"></div></span>
		            <div class="filter_panel_arrow"></div>
		            <div class="filter_panel round">
		            	<div class="scrollable_content" data-bottom="false"> 
			            <ul>
			                <? foreach ($services as $service) {?>
			                	<li>
			                		<input type="checkbox" name="filter_service_id[]" value="<?=$service->id?>" id="service_<?=$service->id?>" <?if(isset($filter_service_id)){ if(in_array($service->id, $filter_service_id)){ echo "checked";}}?>  />
			                		<label for="service_<?=$service->id?>" ><?=$service->name?></label>
			                	</li>
			                <?}?>
			                <p>
                                <input type="submit" class="round bar_button" value="buscar" /> 
                                <input type="button" class="round bar_button cancelar" value="cancelar" />  
                            </p>
			            </ul>
			            </div>
			        </div>
		        </li>
		    </ul>
		</div>
	<input type="submit" class="round bar_button left" value="buscar">        	
</form>	
<form action='<?=URL::base();?>admin/contatos/getContatos' id="frm_reset_contatos" data-panel="#tabs_content" method="post" class="form">
	<input type="hidden" name="contatos" value="true">
	<input type="submit" class="bar_button round" value="limpar filtros" />
</form>

</div>