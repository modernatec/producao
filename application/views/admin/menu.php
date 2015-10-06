<div id="lateral">
	<div class="user_info"  >
		<!--a href="" rel="load-content" data-panel="#content" data-refresh="true" style="float:left;"-->
			<!--img class="foto" src="<?=URL::base();?><?=$user->userInfos->foto?>" /-->
			<div class='left'><?=Utils_Helper::getUserImage($user->userInfos)?></div>			
	        <!--div class='left line'><?$nome = explode(" ", $user->userInfos->nome); echo ucfirst($nome[0]);?></div-->
	        <!--div class='right'></div-->

	        <div class="left filter" >
                <ul>
                    <li class="round" >
                        <span class="round user_name" id="user"><?$nome = explode(" ", $user->userInfos->nome); echo ucfirst($nome[0]);?></span>
                        <span class="filter_panel_arrow"></span>
                        <div class="filter_panel round" >
                            <ul>
                                <li class="user_menu_item">
                                	<a href="<?=URL::base();?>admin/#users/editInfo" class='user_menu' title="editar perfil">
                                		<div class="left icon icon_edit"></div> <span>Editar perfil</span>
                                	</a>
                                </li>
                                <li class="user_menu_item">
                                	<a href="<?=URL::base();?>logout/" class='user_menu' title="logout">
                                		<div class="left icon icon_logout"></div> <span>Logout</span>
                                	</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>

	    <!--/a-->
	</div>	
	<div id="menu">
		<ul >
			<?foreach($menuList as $key=>$menuItem){?>
				<li ><a class="menu" rel="load-content" data-panel="#content" data-refresh="true" href="<?=$menuItem['link']?>/index/ajax" ><?=$menuItem['display']?></a></li>
                <?if(isset($menuItem['sub'])){?>
                	<ul class="submenu">
                	<?foreach($menuItem['sub'] as $menuSubItem){?>
                    	<li><a class="menu" rel="load-content" data-panel="#content" data-refresh="true" href="<?=$menuSubItem['link']?>/index/ajax" ><?=$menuSubItem['display']?></a></li>
	                <?}?>
					</ul>
				<?}?>
			<?}?>
		</ul>
	</div>
	
</div>