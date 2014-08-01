<label><b>editar status</b></label><hr/>
	<form name="frmStatus2" id="frmStatus2" action="<?=URL::base();?>admin/objects/updateStatus/<?=$objVO['id']?>" method="post" class="form" enctype="multipart/form-data">
		<input type="hidden" name="object_id" value="<?=$objVO['object_id']?>">
		<dl>
			<div class="left">
				<dt>
		            <label for="status_id">status:</label>
		        </dt>
		        <dd>
		            <select name="status_id" id="status_id" class="required round" style="width:150px;">
		                <option value="">selecione</option>
		                <? foreach($statusList as $status){?>
		                    <option value="<?=$status->id?>" <?=($objVO['status_id'] == $status->id) ? "selected" : ""?> ><?=$status->status?></option>
		                <?}?>
		            </select>
		            <span class='error'><?=Arr::get($errors, 'status_id');?></span>
		        </dd>				        
			</div>
			<div class="left">
				<dt>
		            <label for="prova">prova:</label>
		        </dt>
		        <dd>
		            <select name="prova" id="prova" class="required round" style="width:150px;">
		                <option value="">selecione</option>
			                <? for($i = 1; $i < 11; $i++){
		                		if($i < 10){
		                			$i = '0'.$i;
		                		}
			                ?>
		                    <option value="prova<?=$i?>" <?=($objVO['prova'] == "prova".$i) ? "selected" : ""?> >prova <?=$i?></option>
		                <?}?>
		            </select>
		            <span class='error'><?=Arr::get($errors, 'status_id');?></span>
		        </dd>				        
			</div>
			<dt>
	            <label for="crono_date">retorno para:</label>
	        </dt>
	        <dd>
	            <input type="text" name="crono_date" id="crono_date_status3" class="round required date" style="width:100px;" value="<?=$objVO['crono_date']?>" />
	            <span class='error'><?=Arr::get($errors, 'crono_date');?></span>
	        </dd>			
            <dt>
            	<label for="description">observações</label>
            </dt>
            <dd>
                  <textarea class="text round" name="description" id="description" style="width:600px; height:70px;"><?=$objVO['description']?></textarea>
                  <span class='error'><?=Arr::get($errors, 'description');?></span>
            </dd>
            <dd>
              <input type="submit" class="round green" name="btnCriar" id="btnCriar" data-form="frmStatus" value="salvar" />             
            </dd>	    
		</dl>
	</form>