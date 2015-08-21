<div class="bar" style='margin-bottom:5px;'>
	<a href="<?=URL::base();?>admin/contatos/edit" rel="load-content" data-panel="#direita" class="bar_button round">cadastrar contato</a>
</div>
	<span class='list_alert light_blue round'>
	<?
        if(count($contatosList) <= 0){
            echo 'não encontrei contatos com estes critérios.';    
        }else{
            echo 'encontrei '. count($contatosList).' contatos';
        }
    ?>
	</span>	

	<div class="list_body scrollable_content">
	    <? 
		if(count($contatosList) <= 0){
			echo '<span class="list_alert round">nenhum registro encontrado</span>';	
		}else{
		?>
		<ul class="list_item">
			<? foreach($contatosList as $contato){?>
			<li>
				<a class="right excluir" href="<?=URL::base().'admin/contatos/delete/'.$contato->id;?>" title="Excluir">Excluir</a>	
				<a href="<?=URL::base().'admin/contatos/edit/'.$contato->id;?>" rel="load-content" data-panel="#direita" title="+ informações">
					
					<div>
						<b><?=$contato->nome?></b><br/>				
						<?=$contato->email?><br/>
						<?=$contato->telefone?>
					</div>
					
						<span class="list_faixa round blue" style="margin:5px 0;"><?=$contato->service->name?></span>
					
				</a>
			</li>
			<?}?>
		</ul>
		<?}?>
	</div>
