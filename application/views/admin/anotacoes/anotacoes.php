<label><b>anotações</b></label><hr/>
<form name="frmAnotacoes" id="frmAnotacoes" action="<?=URL::base();?>admin/anotacoes/edit" method="post" class="form" enctype="multipart/form-data">
	<input type="hidden" name="object_id" value="<?=$object_id?>">
	<dl>
      <dd>
        <textarea class="required text round" name="anotacao" id="anotacao" style="width:400px; height:100px;"></textarea>
        <span class='error'><?=Arr::get($errors, 'anotacao');?></span>
      </dd>
      <dd>
        <input type="submit" class="round" name="btnCriar" id="btnCriar" data-form="frmAnotacoes" value="salvar" />             
      </dd>	    
	</dl>
</form>