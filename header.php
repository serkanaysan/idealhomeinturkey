<?php
// Menu ids
$CorporateMenuID = 1;
$InfoMenuID = 3;
$ServicesMenuID = 2;
?>
<header id="header"<?php if(DEFINED('THISPAGE') AND THISPAGE == 'INDEX'){?> class="absolute"<?php } ?>>
    <div class="container clearfix">
        <div class="header-left">
            <a class="logo" href="<?php echo $MAIN_URL; ?>/<?php echo $DilSef; ?>/" title="Ideal Home in Turkey Logo">
                <img src="<?php echo $MAIN_URL; ?>/assets/img/logo.jpg" alt="Ideal Home in Turkey Logo" width="404" height="170"/>
            </a>
        </div>
        <div class="header-right">
            <nav id="menu" class="hidden-sm hidden-xs">
                <a class="menu-item"<?php if($DilSef=='ar-sa'){ echo ' style="float: right;"'; } ?> href="<?php echo $MAIN_URL; ?>/<?php echo $DilSef; ?>/" title="<?php echo $Lang['menu_anasayfa']; ?>"><?php echo $Lang['menu_anasayfa']; ?></a>
                <div class="menu-item dropdown"<?php if($DilSef=='ar-sa'){ echo ' style="float: right;"'; } ?>>
                    <div data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $Lang['menu_projeler']; ?>
                        <span class="caret"></span>
                    </div>
                    <ul class="dropdown-menu">
							<li><a href="<?php
		 
			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/تركيا-العقارات/';
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/turkey-real-estate/';
				
			}
		 
		 ?>"><?php echo $Lang['menu_tumprojeler']; ?></a></li>
						  <?php
						  
							$CityList = $DB->query("SELECT * FROM ihit_projects_citys ORDER BY rank");
							
							foreach($CityList->fetchAll(PDO::FETCH_ASSOC) as $City){
							
						  ?>
							<li><a href="<?php
		 
			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/تركيا-العقارات/home/'.$City['id'].'_0_0_0';
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/turkey-real-estate/home/'.$City['id'].'_0_0_0';
				
			}
		 
		 ?>"><?php echo $City['title']; ?></a></li>
						<?php } ?>
                    </ul>
                </div>
                <div class="menu-item dropdown dropdown-menu-mega-container"<?php if($DilSef=='ar-sa'){ echo ' style="float: right;"'; } ?>>
                    <div data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $Lang['menu_hizmetlerimiz']; ?>
                        <span class="caret"></span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-mega">
                        <?php
						
						$HizmetlerimizListesi = $DB->prepare("SELECT * FROM ihit_menu WHERE menu_id = '".$ServicesMenuID."' AND lang_id = :lang_id ORDER BY rank ASC");
						$HizmetlerimizListesi->execute(array(':lang_id'=>$CurrentLangInfo['id']));

						foreach($HizmetlerimizListesi->fetchAll(PDO::FETCH_ASSOC) as $Hizmet){

						?>
						<ul>
							<?php /* <li class="title"><a<?php if(trim($Hizmet['url'])!='') echo ' href="'.$Hizmet['url'].'"'; ?>><?php echo $Hizmet['title']; ?></a></li> */ ?>
							<li class="title"><?php echo $Hizmet['title']; ?></li>
							<?php
								
								$SubMenu = $DB->prepare("SELECT * FROM ihit_menu WHERE menu_id = '{$Hizmet['id']}' AND lang_id = :lang_id ORDER BY rank ASC");
								$SubMenu->execute(array(':lang_id'=>$CurrentLangInfo['id']));
								
								if($SubMenu->rowCount() > 0){
									
									foreach($SubMenu->fetchAll(PDO::FETCH_ASSOC) as $SubMenu){
							?>
								   <li><a href="<?php echo $SubMenu['url']; ?>"><?php echo $SubMenu['title']; ?></a></li>
								<?php
									} // Foreach
								}// Submenu
							?>
						</ul>
						<?php } ?>
                    </div>
                </div>
				
                <div class="menu-item dropdown"<?php if($DilSef=='ar-sa'){ echo ' style="float: right;"'; } ?>>
                    <div data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $Lang['menu_info']; ?>
                        <span class="caret"></span>
                    </div>
                    <ul class="dropdown-menu">
						<?php

							$InfoListesi = $DB->prepare("SELECT * FROM ihit_menu WHERE menu_id = '".$InfoMenuID."' AND lang_id = :lang_id ORDER BY rank ASC");
							$InfoListesi->execute(array(':lang_id'=>$CurrentLangInfo['id']));
							
							foreach($InfoListesi->fetchAll(PDO::FETCH_ASSOC) as $Info){

						?>
							<li><a<?php if(trim($Info['url'])!='') echo ' href="'.$Info['url'].'"'; ?>><?php echo $Info['title']; ?></a></li>
						<?php } ?>
                    </ul>
                </div>
				
                <div class="menu-item dropdown"<?php if($DilSef=='ar-sa'){ echo ' style="float: right;"'; } ?>>
                    <div data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $Lang['menu_kurumsal']; ?>
                        <span class="caret"></span>
                    </div>
                    <ul class="dropdown-menu">
						<?php

							$KurumsalList = $DB->prepare("SELECT * FROM ihit_menu WHERE menu_id = '".$CorporateMenuID."' AND lang_id = :lang_id ORDER BY rank ASC");
							$KurumsalList->execute(array(':lang_id'=>$CurrentLangInfo['id']));
							
							foreach($KurumsalList->fetchAll(PDO::FETCH_ASSOC) as $Kurumsal){

						?>
							<li><a<?php if(trim($Kurumsal['url'])!='') echo ' href="'.$Kurumsal['url'].'"'; ?>><?php echo $Kurumsal['title']; ?></a></li>
						<?php } ?>
                    </ul>
                </div>
                <a class="menu-item"<?php if($DilSef=='ar-sa'){ echo ' style="float: right;"'; } ?> href="<?php
		 
			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/التواصل/';
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/contact/';
				
			}
		 
		 ?>" title="<?php echo $Lang['menu_iletisim']; ?>"><?php echo $Lang['menu_iletisim']; ?></a>
		 <?php
		 
			$ListLanguages = $DB->query("SELECT * FROM ihit_languages WHERE status = '1' ORDER BY rank ASC");
			
			if($ListLanguages->rowCount() > 1){
			?>
                <div class="menu-item-language dropdown"<?php if($DilSef=='ar-sa'){ echo ' style="float: right;"'; } ?>>
                    <div class="menu-item-language-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="language <?php echo $CurrentLangInfo['short']; ?>"></span>
                        <span class="text"><?php echo strtoupper($CurrentLangInfo['short']); ?></span>
                        <i class="fa fa-caret-down"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-right">
					<?php
					
						foreach($ListLanguages->fetchAll(PDO::FETCH_ASSOC) as $Language){

					?>
                        <li>
                            <a<?php if(isset($CurrentPage) AND $CurrentPage=='404PAGE'){ echo ' href="'.$MAIN_URL.'/'.$Language['lang_id'].'/"'; }else{ ?> onclick="chooseLanguage('<?php echo $Language['id']; ?>')" <?php } ?>title="<?php echo $Language['lang_name']; ?>">
                                <span class="language <?php echo $Language['short']; ?>"></span>
                                <span style="padding-left:5px;"><?php echo $Language['lang_name']; ?></span>
                            </a>
                        </li>
					<?php } ?>
                    </ul>
                </div>
				<?php } ?>
            </nav>
            <button class="hidden-lg hidden-md btn btn-mobile-menu" type="button">
                <i class="fa fa-navicon"></i>
            </button>
        </div>
    </div>
</header>