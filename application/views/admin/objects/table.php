
<table class="list">
		<thead>
			<th width="200">nome</th>	
            <th width="50">
            	<div class="filter" >
				    <ul>
				        <li class="round" >
				            <span id="tipo">tipo</span>
				            <ul class="round" >
				            	<form action="<?=URL::base();?>admin/objects" method="post" class="form">
					                <? foreach ($typeObjectsjsList as $typeobject) {?>
					                <li><input type="checkbox" name="tipo[]" value="<?=$typeobject->typeobject->id?>" id="t_<?=$typeobject->typeobject->id?>" <?=(in_array($typeobject->typeobject->id, $filter_tipo)) ? "checked" : ""?>><label for="t_<?=$typeobject->typeobject->id?>"><?=$typeobject->typeobject->name?></label></li>
					                <?}?>
					                <input type="submit" class="round bar_button" value="OK"> 
					                <input type="button" class="round bar_button cancelar" value="Cancelar"> 
				                </form>
				            </ul>
				        </li>
				    </ul>

				</div>
            </th>
            <th width="100">
            	<div class="filter" >
				    <ul>
				        <li class="round" >
				            <span id="supplier">produtora</span>
				            <ul class="round" >
				            	<form action="<?=URL::base();?>admin/objects" method="post" class="form">
					                <? foreach ($suppliersList as $supplier) {?>
					                <li><input type="checkbox" name="supplier[]" value="<?=$supplier->supplier->id?>" id="s_<?=$supplier->supplier->id?>" <?=(in_array($supplier->supplier->id, $filter_supplier)) ? "checked" : ""?>><label for="s_<?=$supplier->supplier->id?>"><?=$supplier->supplier->empresa?></label></li>
					                <?}?>
					                <input type="submit" class="round bar_button" value="OK"> 
					                <input type="button" class="round bar_button cancelar" value="Cancelar"> 
				                </form>
				            </ul>
				        </li>
				    </ul>

				</div>
            </th>
            <th width="50">
            	<div class="filter" >
				    <ul>
				        <li class="round" >
				            <span class="round" id="status">status</span>
				            <ul class="round" >
				            	<form action="<?=URL::base();?>admin/objects" method="post" class="form">
					                <? foreach ($statusList as $status) {?>
					                <li><input type="checkbox" name="status[]" value="<?=$status->statu->id?>" id="s_<?=$status->statu->id?>" <?=(in_array($status->statu->id, $filter_status)) ? "checked" : ""?>><label for="s_<?=$status->statu->id?>" ><?=$status->statu->status?></label></li>
					                <?}?>
					                <input type="submit" class="round bar_button" value="OK"> 
					                <input type="button" class="round bar_button cancelar" value="Cancelar"> 
				                </form>
				            </ul>
				        </li>
				    </ul>
				</div>
			</th>
            <th width="300">
            	<div class="filter" >
				    <ul>
				        <li class="round" >
				            <span class="round" id="colecao">coleção</span>
				            <ul class="round" >
				            	<form action="<?=URL::base();?>admin/objects" method="post" class="form">
					                <? foreach ($collectionList as $collection) {?>
					                <li style="width:400px"><input type="checkbox" name="collection[]" value="<?=$collection->id?>" id="c_<?=$collection->id?>" <?=(in_array($collection->id, $filter_collection)) ? "checked" : ""?>><label for="c_<?=$collection->id?>"><?=$collection->collection->name?></label></li>
					                <?}?>
					                <input type="submit" class="round bar_button" value="OK"> 
					                <input type="button" class="round bar_button cancelar" value="Cancelar"> 
				                </form>
				            </ul>
				        </li>
				    </ul>
				</div>
            </th>
            <th>data prevista</th>
		</thead>
		<tbody>
            <? foreach($objectsList as $objeto){?>
            <tr>
                <td>
                    <a href="<?=URL::base().'admin/objects/view/'.$objeto->id;?>" title="Editar"><?=$objeto->taxonomia?><br/><?=$objeto->title?></a>
                </td>
                <td><?=$objeto->typeobject->name?></td>
                <td><?=$objeto->supplier->empresa?></td>
                <td><?=$objeto->statu->status?></td>
                <td><?=$objeto->collection->name?></td>
                <td><?=Utils_Helper::data($objeto->crono_date,'d/m/Y')?></td>
			</tr>
            <?}?>
		</tbody>
	</table>