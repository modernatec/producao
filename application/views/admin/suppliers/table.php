	<span class='list_alert light_blue round'>
	<?
        if(count($suppliersList) <= 0){
            echo 'não encontrei fornecedores com estes critérios.';    
        }else{
            echo 'encontrei: '. count($suppliersList).' fornecedores';
        }
    ?>
	</span>
	

	<div class="list_body scrollable_content">
	    <? 
		if(count($suppliersList) <= 0){
			echo '<span class="list_alert round">nenhum registro encontrado</span>';	
		}else{
		?>
		<ul class="list_item">
			<? foreach($suppliersList as $supplier){?>
			<li>
				<a href="<?=URL::base().'admin/suppliers/view/'.$supplier->id;?>" rel="load-content" data-panel="#direita" title="+ informações">
					<div>
						<p><span class="team_<?=$supplier->team->id?> round list_faixa"><?=$supplier->team->name?></span> <b><?=$supplier->empresa?></b></p>					
						<p><span class='text_blue'><?=$supplier->contato->nome?></span></p>
						<p><?=$supplier->contato->email?></p>
						<p><?=$supplier->contato->telefone?></p>
					</div>
				</a>
			</li>
			<?}?>
		</ul>
		<?}?>
	</div>
