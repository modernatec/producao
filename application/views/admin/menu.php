<div id="lateral">
	<div class="user_info"  >
		<a href="<?=URL::base();?>admin/users/editInfo" rel="load-panel" data-panel="#content" style="float:left;">
			<!--img class="foto" src="<?=URL::base();?><?=$user->userInfos->foto?>" /-->
			<div class='left'><?=Utils_Helper::getUserImage($user->userInfos)?></div>			
	        <div class='left line'><?$nome = explode(" ", $user->userInfos->nome); echo ucfirst($nome[0]);?></div>
	    </a>
	</div>
	<div id="menu">
		<ul >
			<?foreach($menuList as $key=>$menuItem){
				$link = explode("/", $menuItem['link']);
				$link_id = end($link);
				
			?>
				<li ><a class="round menu" rel="load-content" data-panel="#content" data-refresh="true" id="<?=$link_id?>"  href="<?=URL::base();?><?=$menuItem['link']?>/index/ajax" ><?=$menuItem['display']?></a></li>
                <?if(isset($menuItem['sub'])){?>
                	<ul class="submenu">
                	<?
						foreach($menuItem['sub'] as $menuSubItem){
							$subLink = explode("/", $menuSubItem['link']);
							$subLink_id = end($subLink);
					?>

                    	<li ><a class="round menu" rel="load-content" data-panel="#content" id="<?=$subLink_id?>" data-refresh="true" href="<?=URL::base();?><?=$menuSubItem['link']?>/index/ajax" ><?=$menuSubItem['display']?></a></li>
	                <?}?>
					</ul>
				<?}?>
			<?}?>
			<li class="round"><a href="<?=URL::base();?>logout/" class="logout round" title="Logout">logout</a></li>
		</ul>
	</div>
	
</div>