<div class="topo" >
	<span class="header">times</span>
</div>
<div id="esquerda">
	<div class="bar">
		<a href="<?=URL::base();?>admin/teams/edit" rel="load-content" data-panel="#direita" class="bar_button round">cadastrar time</a>
	</div>
	<ul class="list_item">
		<? foreach($teamsList as $teams){?>
		<li>
			<div class="left">
				<p><a style='display:block' href="<?=URL::base().'admin/teams/edit/'.$teams->id;?>" rel="load-content" data-panel="#direita" title="Editar"><b><?=$teams->name?></b></a></p>
				<p>coord.: <?=$teams->userInfo->nome?></p>
			</div>
			<div class="right">
				<a class="excluir" href="<?=URL::base().'admin/teams/delete/'.$teams->id;?>" title="Excluir">Excluir</a>
			</div>	
		</li>
		<?}?>
	</ul>
</div>
<div id="direita"></div>