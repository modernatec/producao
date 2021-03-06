<form name="frmCreateProject" id="frmCreateProject" action="<?=URL::base();?>admin/projects/salvar/<?=@$projectVO["id"]?>" method="post" class="form" enctype="multipart/form-data">
    <input type="hidden" name="project_id" id="project_id" value="<?=@$projectVO["id"]?>">
    <div class="left">
	    <label for="name">projeto</label>
	    <dd>
	      <input type="text" class="text required round" name="name" id="name" style="width:400px;" value="<?=@$projectVO['name'];?>"/>
	      <span class='error'><?=Arr::get($errors, 'name');?></span>
	    </dd>
	</div>
    
    <div class="left">
	      <label for="project_ano">ano</label>
	    <dd>
			<select name="ano" id="project_ano" class="required round">
			<option value="">selecione</option>
			<? 
			for($i = 2009; $i <= date("Y") + 5; $i++){?>
			<option value="<?=$i?>" <?=((@$ano == $i)?('selected'):(''))?> ><?=$i;?></option>
			<?}?>
			</select>
			<span class='error'><?=($errors) ? $errors['ano'] : '';?></span>
	    </dd>
   	</div>
    <div class="clear left">
	      <label for="project_segmento">segmento</label>
	    <dd>
	      <select name="segmento_id" id="project_segmento" class="required round" style="width:150px;">
                <option value="">selecione</option>
                <? foreach($segmentosList as $segmento){?>
                <option value="<?=$segmento->id?>" <?=((@$projectVO["segmento_id"] == $segmento->id)?('selected'):(''))?> ><?=$segmento->name?></option>
                <? }?>
            </select>
            <span class='error'><?=($errors) ? $errors['segmento_id'] : '';?></span>
	    </dd>
	</div>
	<div class="left">
	      <label for="status">status</label>
	    <dd>
	      <select class="required round" name="status" id="status" style="width:150px;">
                <option value='1' <?=(($projectVO['status']==1)?('selected="selected"'):(''))?>>produzindo</option>
                <option value='0' <?=(($projectVO['status']==0)?('selected="selected"'):(''))?>>finalizado</option>
            </select>
            <span class='error'><?=($errors) ? $errors['status'] : '';?></span>
	    </dd>
    </div>
	<dd class="clear">
		<input type="submit" class="round bar_button" name="btnSubmit" id="btnSubmit" value="salvar" />		      
    </dd>
	
    <div class="clear"> 
    	<? 
    		$collections = $project->collections->find_all();
    		$qtd_oed = 0;
    		foreach($collections as $collection){
	        	$qtd_oed += $collection->objects->where('fase', '=', '53')->count_all();
	       	}
    	?>	
    	<span class="list_alert"><?=count($collections)?> coleções | <?=$qtd_oed;?> OED</span>	
			<div class="scrollable_content clear">
				<table>
					<thead>
						<th width="10%">OP</th>
				        <th width="70%">Coleção</th>
				        <th width="10%">qtd. OED</th>
				        <th width="10%">ano</th>	
					</thead>
					<tbody>
				        <? 				        
				        foreach($collections as $collection){
				        	$qtd_oed_collection = $collection->objects->where('fase', '=', '53')->count_all();
				        ?>
				        <tr>
				            <td width="10%"><?=$collection->op?></td>
							<td width="70%" class="tl"><?=$collection->name?></td>
							<td width="10%"><?=$qtd_oed_collection;?></td>
							<td width="10%"><?=$collection->ano?></td>
						</tr>
				        <?}?>
					</tbody>
				</table>
			</div>
	</div>
    
</form>
