	<div class="acervo_header">
		<span class='left text_cyan'>
		<?
			if(count($objectsList) <= 0){
                echo 'não encontrei objetos com estes critérios.';    
            }else{
                echo $total_objects.' objeto(s) encontrados';
            }
        ?>
		</span>

		<div class="right">
			<?=$pagination?>
		</div>
		<p class='right'>resultados por página</p>
	</div>
	<div class="clear scrollable_content list_body">
			<?foreach($objectsList as $objeto){
				if($objeto->reaproveitamento == 0){ 
	                $origem = "N";
	            }elseif($objeto->reaproveitamento == 1){
	                $origem = "R";
	            }else{
	                $origem = "Ri";
	            }

	            $tipo = explode('-', $objeto->tipo);
			?>
			<div class="acervo_item round" id="obj_<?=$objeto->id?>">
				<a class="popup" href="<?=URL::base().'admin/acervo/view/'.$objeto->id?>" data-select="obj_<?=$objeto->id?>" title="+ informações">
					<div class="acervo_item_top">
						<p><b><?=$objeto->title?></b></p>
						<p><?=$objeto->collection_name?></p>
					</div>
					<div class="acervo_item_bottom">
						<div class="right ball cyan"><?=$tipo[0];?></div>
						<div class="right ball cyan"><?=$origem;?></div>
					</div>
				</a>
			</div>
			<?}?>
	</div>
