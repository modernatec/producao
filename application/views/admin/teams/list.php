<div class="content">
	<div class="bar">
		<a href="<?=URL::base();?>admin/teams/create" class="bar_button round">cadastrar equipe</a>
	</div>
	<span class="header">equipes</span>
		<ul class="list_item">
			<? foreach($teamsList as $teams){?>
			<li>
				<div class="left">
					<p><a style='display:block' href="<?=URL::base().'admin/teams/edit/'.$teams->id;?>" title="Editar"><b><?=$teams->name?></b></a></p>
					<p>coord.: <?=$teams->userInfo->nome?></p>
				</div>
				<div class="right">
					<a class="excluir" href="<?=URL::base().'admin/teams/delete/'.$teams->id;?>" title="Excluir">Excluir</a>
				</div>	
			</li>
			<?}?>
		</ul>
		</div>
	
</div>
