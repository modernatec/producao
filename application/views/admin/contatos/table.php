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
				<a href="<?=URL::base().'admin/contatos/edit/'.$contato->id;?>" rel="load-content" data-panel="#direita" title="+ informações">
					<div>
						<p><b><?=$contato->nome?></b></p>					
						<p><?=$contato->email?></p>
						<p><?=$contato->telefone?></p>
					</div>
				</a>
			</li>
			<?}?>
		</ul>
		<?}?>
	</div>
