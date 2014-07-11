<div class="content">
	<div class="bar">
		<a href="<?=URL::base();?>admin/projects/create" class="bar_button round">Criar Projeto</a>
	</div>
	<div id="tabs">
		<ul>
			<li><a href="#tabs_content">projetos</a></li>
		</ul>
		<div id="tabs_content" >
			<div class="list_body">
				<ul class="list_item">
					<? foreach($projectsList as $projeto){?>
					<li>
						<a style='display:block' href="<?=URL::base().'admin/projects/edit/'.$projeto->id;?>" title="Editar">
							<p><b><?=$projeto->name?></b></p>
							<p class="<?=($projeto->status == 0) ? "object_finished" : "task_open"?> round list_faixa"><?=($projeto->status == 0) ? "finalizado" : "em produção"?></p>
						</a>
					</li>
					<?}?>
				</ul>
			</div>
		</div>
	</div>	
</div>
