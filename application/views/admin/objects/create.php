<div class="content">
	<div class="bar">
		<a href="<?=$_SERVER["HTTP_REFERER"]?>" class="bar_button round">Voltar</a>
	</div>
    <form name="frmCreateObject" id="frmCreateObject" method="post" class="form" enctype="multipart/form-data">
	  <dl>
            <dt> <label for="title">título</label></dt>
            <dd>
                <input type="text" class="text required round" name="title" id="title" style="width:500px;" value="<?=$objVO['title'];?>"/>
                <span class='error'><?=Arr::get($errors, 'title');?></span>
            </dd>
            <dt><label for="taxonomia">taxonomia</label></dt>
            <dd>
                <input type="text" class="text required round" name="taxonomia" id="taxonomia" style="width:500px;" value="<?=$objVO['taxonomia'];?>"/>
                <span class='error'><?=Arr::get($errors, 'taxonomia');?></span>
            </dd>
            <div class="left">
                <dt> <label for="collection_id">coleção</label> </dt>
                <dd>
                    <select class="required round" name="collection_id" id="collection_id">
                        <option value=''>Selecione</option>
                        <? foreach($collections as $collection){?>
                            <option value="<?=$collection->id?>" <?=((@$objVO["collection_id"] == $collection->id)?('selected'):(''))?> ><?=$collection->name?></option>
                        <? }?>
                    </select>
                    <span class='error'><?=Arr::get($errors, 'collection_id');?></span>
                </dd>
            </div>
            <div class="left">
                <dt> <label for="countries">país</label> </dt>
                <dd>
                    <select class="required round" name="country_id" id="country_id">
                        <option value=''>Selecione</option>
                        <? foreach($countries as $country){?>
                            <option value="<?=$country->id?>" <?=((@$objVO["country_id"] == $country->id)?('selected'):(''))?> ><?=$country->name?></option>
                        <? }?>
                    </select>
                    <span class='error'><?=Arr::get($errors, 'country_id');?></span>
                </dd>
            </div>   
            <div class="left">
                <dt> <label for="fase">fase</label> </dt>
                <dd>
                    <select class="required round" name="fase" id="fase" style="width:100px;">
                        <option value=''>Selecione</option>
                        <option value='0' <?=(($objVO['fase']==0)?('selected="selected"'):(''))?>>Concept</option>
                        <option value='1' <?=(($objVO['fase']==1)?('selected="selected"'):(''))?>>Produção</option>
                        <option value='2' <?=(($objVO['fase']==2)?('selected="selected"'):(''))?>>Acervo</option>
                    </select>
                    <span class='error'><?=Arr::get($errors, 'fase');?></span>
                </dd>  
            </div>

            <div class="clear left">
                <dt> <label for="typeobject_id">tipo do objeto</label> </dt>
                <dd>
                    <select class="required round" name="typeobject_id" id="typeobject_id" style="width:200px;">
                        <option value=''>Selecione</option>
                        <? foreach($typeObjects as $type){?>
                            <option value="<?=$type->id?>" <?=((@$objVO["typeobject_id"] == $type->id)?('selected'):(''))?> ><?=$type->name?></option>
                        <? }?>
                    </select>
                    <span class='error'><?=Arr::get($errors, 'tipo_obj');?></span>
                </dd>
            </div>
            <div class="left">
                <dt> <label for="supplier_id">produtora</label> </dt>
                <dd>
                	<select class="required round" name="supplier_id" id="supplier_id">
                        <option value=''>Selecione</option>
                        <? foreach($suppliers as $supplier){?>
                            <option value="<?=$supplier->id?>" <?=((@$objVO["supplier_id"] == $supplier->id)?('selected'):(''))?> ><?=$supplier->empresa?></option>
                        <? }?>
                    </select>
                    <span class='error'><?=Arr::get($errors, 'supplier_id');?></span>
                </dd>       
            </div>
            <div class="left">
                <dt> <label for="crono_date">Data de fechamento</label> </dt>
                <dd>
                    <input type="text" class="text round date" name="crono_date" id="crono_date" style="width:100px;" value="<?=$objVO['crono_date'];?>"/>
                    <span class='error'><?=Arr::get($errors, 'crono_date');?></span>
                </dd>     
            </div>
            <? if(empty($objVO["id"])){ //criando objeto novo?>
                <div class="left">
                    <dt> <label for="ini_date">Data de início</label> </dt>
                    <dd>
                        <input type="text" class="text round date" name="ini_date" id="ini_date" style="width:100px;" />
                        <span class='error'><?=Arr::get($errors, 'ini_date');?></span>
                    </dd>     
                </div>
            <?}?>
            

            <div class="clear left">
                <dt><label for="cap">capítulo</label></dt>
                <dd>
                    <input type="text" class="text round" name="cap" id="cap" style="width:50px;" value="<?=$objVO['cap'];?>"/>
                    <span class='error'><?=Arr::get($errors, 'cap');?></span>
                </dd>                
            </div>
            <div class="left">
                <dt><label for="uni">unidade</label></dt>
                <dd>
                    <input type="text" class="text round" name="uni" id="uni" style="width:50px;" value="<?=$objVO['uni'];?>"/>
                    <span class='error'><?=Arr::get($errors, 'uni');?></span>
                </dd>
            </div>
            <div class="left">
                <dt><label for="pagina">página</label></dt>
                <dd>
                    <input type="text" class="text round" name="pagina" id="pagina" style="width:50px;" value="<?=$objVO['pagina'];?>"/>
                    <span class='error'><?=Arr::get($errors, 'pagina');?></span>
                </dd>  
            </div>
            <div class="left">
                <dt><label for="tamanho">tam. (kb)</label></dt>
                <dd>
                    <input type="text" class="text round" name="tamanho" id="tamanho" style="width:50px;" value="<?=$objVO['tamanho'];?>"/>
                    <span class='error'><?=Arr::get($errors, 'tamanho');?></span>
                </dd>  
            </div>
            <div class="left">
                <dt><label for="duracao">duração</label></dt>
                <dd>
                    <input type="text" class="text round" name="duracao" id="duracao" style="width:50px;" value="<?=$objVO['duracao'];?>"/>
                    <span class='error'><?=Arr::get($errors, 'duracao');?></span>
                </dd>  
            </div>
            <div class="left"> 
                <dt><label for="cessao">cessão txt/img</label></dt>
                <dd>
                    <select class="required round" name="cessao" id="cessao" style="width:100px;">
                        <option value=''>Selecione</option>
                        <option value='0' <?=(($objVO['cessao'] == 0)?('selected="selected"'):(''))?>>Não</option>
                        <option value='1' <?=(($objVO['cessao'] == 1)?('selected="selected"'):(''))?>>Sim</option>
                    </select>
                    <span class='error'><?=Arr::get($errors, 'cessao');?></span>
                </dd> 
            </div>

            <div class="clear left"> 
                <dt><label for="arq_aberto">arquivo aberto</label></dt>
                <dd>
                    <select class="required round" name="arq_aberto" id="arq_aberto" style="width:100px;">
                        <option value=''>Selecione</option>
                        <option value='0' <?=(($objVO['arq_aberto'] == 0)?('selected="selected"'):(''))?>>Não</option>
                        <option value='1' <?=(($objVO['arq_aberto'] == 1)?('selected="selected"'):(''))?>>Sim</option>
                    </select>
                    <span class='error'><?=Arr::get($errors, 'arq_aberto');?></span>
                </dd> 
            </div>
            <div class="left">
                <dt> <label for="interatividade">interatividade</label> </dt>
                <dd>
                    <select class="required round" name="interatividade" id="interatividade" style="width:100px;">
                        <option value=''>Selecione</option>
                        <option value='0' <?=(($objVO['interatividade']==0)?('selected="selected"'):(''))?>>Não</option>
                        <option value='1' <?=(($objVO['interatividade']==1)?('selected="selected"'):(''))?>>Sim</option>
                    </select>
                    <span class='error'><?=Arr::get($errors, 'interatividade');?></span>
                </dd>
            </div>
            
            <div class="left">
                <dt> <label for="format_id">formato final</label> </dt>
                <dd>
                    <select class="required round" name="format_id" id="format_id">
                        <option value=''>Selecione</option>
                        <? foreach($formats as $format){?>
                            <option value="<?=$format->id?>" <?=((@$objVO["format_id"] == $format->id)?('selected'):(''))?> ><?=$format->name?></option>
                        <? }?>
                    </select>
                    <span class='error'><?=Arr::get($errors, 'format_id');?></span>
                </dd>
            </div>  
            <div class="clear left"> 
                <dt><label for="reaproveitamento">reaproveitamento</label></dt>
                <dd>
                    <select class="required round" name="reaproveitamento" id="reaproveitamento" style="width:100px;">
                        <option value=''>Selecione</option>
                        <option value='0' <?=(($objVO['reaproveitamento'] == 0)?('selected="selected"'):(''))?>>Não</option>
                        <option value='1' <?=(($objVO['reaproveitamento'] == 1)?('selected="selected"'):(''))?>>Sim</option>
                    </select>
                    <span class='error'><?=Arr::get($errors, 'arq_aberto');?></span>
                </dd> 
            </div>
            <div class="left">
                <dt><label for="taxonomia_reap">Taxonomia do reap.</label></dt>
                <dd>
                    <input type="text" class="text round" name="taxonomia_reap" id="taxonomia_reap" style="width:250px;" value="<?=$objVO['taxonomia_reap'];?>"/>
                    <span class='error'><?=Arr::get($errors, 'taxonomia_reap');?></span>
                </dd>  
            </div>
            <div class="clear">
                <dt> <label for="obs">obsevações</label> </dt>
                <dd>
                    <textarea class="text round" name="obs" id="obs" style="width:500px; height:100px;"><?=$objVO['obs'];?></textarea>
                    <span class='error'><?=Arr::get($errors, 'obs');?></span>
                </dd>
            </div>
            <div class="clear">
                <dt> <label for="sinopse">sinopse</label> </dt>
                <dd>
                    <textarea class="text round" name="sinopse" id="sinopse" style="width:500px; height:100px;"><?=$objVO['sinopse'];?></textarea>
                    <span class='error'><?=Arr::get($errors, 'sinopse');?></span>
                </dd>
            </div>
	    <dd>
	      <input type="submit" class="round" name="btnSubmit" id="btnSubmit" value="<? if($isUpdate){ ?>Salvar<? }else{?>Criar<? }?>" />
	    </dd>
	  </dl>
	</form>
</div>