<form name="frmCreateTipoObj" id="frmCreateTipoObj" action="<?=URL::base();?>admin/status/salvar/<?=@$statusVO["id"]?>" method="post" class="form" enctype="multipart/form-data">
  <dl>
    <div class="left">
        <label for="status">status</label>
        <dd>
            <input type="text" class="text required round" placeholder="status" name="status" id="status" style="width:300px;" value="<?=@$statusVO['status'];?>"/>
            <span class='error'><?=Arr::get($errors, 'status');?></span>
        </dd>  
    </div>
    <div class="left">
        <label for="color">cor</label>
        <dd>
            <input type="text" id="hue-demo" name="color" class="pickcolor round" value="<?=@$statusVO['color'];?>">
            <span class='error'><?=Arr::get($errors, 'color');?></span>
        </dd> 
    </div>
    <div class="clear">
        <label for="team">times</label>
        <dd>
            <?foreach ($teamList as $team) {?>
            	<input type="checkbox" name="team[]" id="team_<?=$team->id?>" value="<?=$team->id?>" <?if(in_array($team->id, $teamsArray)){ echo "checked";}?>><label for="team_<?=$team->id?>"><?=$team->name;?></label>
            <?}?>
            <span class='error'><?=Arr::get($errors, 'class');?></span>
        </dd>
    </div>
    <dd>
      <input type="submit" class="round" name="btnSubmit" id="btnSubmit" value="Salvar" />
    </dd>
  </dl>
</form>
