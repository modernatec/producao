<form name="frmCreateCollection" id="frmCreateCollection" action="<?=URL::base();?>admin/collections/edit/<?=@$collectionVO["id"]?>" method="post" class="form" enctype="multipart/form-data">
  <dl>
    <dt>
      <label for="name">coleção</label>
    </dt>
    <dd>
      <input type="text" class="text required round" name="name" id="name" style="width:500px;" value="<?=@$collectionVO['name'];?>"/>
      <span class='error'><?=Arr::get($errors, 'name');?></span>
    </dd>
    <div class="left">
      <dt>
        <label for="target">matéria</label>
      </dt>
      <dd>
        <select name="materia_id" id="materia_id">
              <option value="">selecione</option>
              <? foreach($materiaList as $materia){?>
              <option value="<?=$materia->id?>" <?=((@$collectionVO["materia_id"] == $materia->id)?('selected'):(''))?> ><?=$materia->name?></option>
              <? }?>
          </select>
          <span class='error'><?=($errors) ? $errors['materia_id'] : '';?></span>
      </dd>
    </div>
    <div class="left">
      <dt>
        <label for="target">segmento</label>
      </dt>
      <dd>
        <select name="segmento_id" id="segmento_id">
              <option value="">selecione</option>
              <? foreach($segmentoList as $segmento){?>
              <option value="<?=$segmento->id?>" <?=((@$collectionVO["segmento_id"] == $segmento->id)?('selected'):(''))?> ><?=$segmento->name?></option>
              <? }?>
          </select>
          <span class='error'><?=($errors) ? $errors['segmento_id'] : '';?></span>
      </dd>
    </div>
    <dt>
      <label for="ano">ano</label>
    </dt>     
    <dd>
      <select name="ano" id="ano">
          <option value="">selecione</option>
          <? 
          for($i = date("Y") - 5; $i <= date("Y") + 5; $i++){?>
            <option value="<?=$i?>" <?=((@$collectionVO["ano"] == $i)?('selected'):(''))?> ><?=$i;?></option>
          <?}?>
      </select>
      <span class='error'><?=($errors) ? $errors['ano'] : '';?></span>
    </dd>
    <div class="clear">
      <div class="left">
        <dt>
          <label for="op">OP</label>
        </dt>	    
        <dd>
          <input type="text" class="text required round" name="op" id="op" style="width:50px;" value="<?=@$collectionVO['op'];?>"/>
          <span class='error'><?=Arr::get($errors, 'op');?></span>
        </dd>
      </div>
      
      <div>
        <dt>
          <label for="fechamento">fechamento</label>
        </dt>	    
        <dd>
          <input type="text" class="text date round" name="fechamento" id="fechamento" style="width:80px;" value="<?=@$collectionVO['fechamento'];?>"/>
          <span class='error'><?=Arr::get($errors, 'fechamento');?></span>
        </dd>
      </div>
    </div>
    <dt>
      <label for="repositorio">repositório</label>
    </dt>
    <dd>
      <input type="text" class="text round" name="repositorio" id="repositorio" style="width:500px;" value="<?=@$collectionVO['repositorio'];?>"/>
      <span class='error'><?=Arr::get($errors, 'repositorio');?></span>
    </dd>
    <dd class='clear'>
      <input type="submit" class="round" name="btnSubmit" id="btnSubmit" value="Salvar" />
    </dd>
  </dl>
</form>
