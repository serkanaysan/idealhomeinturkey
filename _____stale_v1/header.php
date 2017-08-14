<!-- Header -->
<header class="header">
	<div class="container cf">
	
		<!-- Logo -->
		<div class="logo">
			<a href="<?php echo $MAIN_URL; ?>/<?php echo $DilSef; ?>/">
				<img src="<?php echo $MAIN_URL; ?>/assets/img/logo_header.png" class="logo__image" alt="Ideal Home in Turke Logo">
			</a>
		</div>
		<div class="header-bars hidden-lg hidden-md">
			<div class="header-bars-button">
				<i class="fa fa-bars" style="color: #1a6db3;"></i>
			</div>
		</div>
		<div class="contactsbox hidden-sm hidden-xs">
			<a href="<?php
		 
			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/contact/';
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/contact/';
				
			}
		 
		 ?>"><div class="btn" style="padding: 6px 8px;background-color: #e72e36; border-color: #e72e36;color:#fff;"><i class="fa fa-envelope fa-lg"></i></div></a>
			<a href="tel:<?php echo str_replace(array(' '),null,getSettings('footer_phone')); ?>"><div class="btn" style="padding: 6px 8px;background-color: #e72e36; border-color: #e72e36; color: #fff; font-family: Verdana; font-weight: bold;"><i class="fa fa-phone fa-lg"></i> &nbsp; <?php echo getSettings('footer_phone'); ?></div></a>
			<div class="phonecontact" style="margin-top: 7px; color: #0a63ae; position: inherit;">
				<?php

					$ListLanguages = $DB->query("SELECT * FROM ihit_languages WHERE status = '1' ORDER BY rank ASC");
					
					if($ListLanguages->rowCount() > 1){
					foreach($ListLanguages->fetchAll(PDO::FETCH_ASSOC) as $Language){

				?>
					<a<?php if(isset($CurrentPage) AND $CurrentPage=='404PAGE'){ echo ' href="'.$MAIN_URL.'/'.$Language['lang_id'].'/"'; }else{ ?> onclick="chooseLanguage('<?php echo $Language['id']; ?>')" <?php } ?>title="<?php echo $Language['lang_name']; ?>"><img src="<?php echo $MAIN_URL; ?>/uploads/languages/<?php echo $Language['image']; ?>" style="width:24px;" alt="<?php echo $Language['lang_name']; ?>"/></a>
					<?php } } ?>
			</div>
			
			<!--
			<span class="contacts__email blink"><?php echo $Lang['bizi_arayin']; ?></span>
			<div class="contacts__phone"><?php echo getSettings('header_phone'); ?></div>
			-->
		</div>
		
		<nav class="nav hidden-sm hidden-xs">
			<div class="container cf">
				<ul id="menu-top-menu" class="insNav cf">
					<!-- Anasayfa -->
					<li class="menu-item"><a href="<?php echo $MAIN_URL; ?>/<?php echo $DilSef; ?>/"><?php echo $Lang['menu_anasayfa']; ?></a></li>
					
					<!-- Projeler -->
					<li class="menu-item">
						<a><?php echo $Lang['menu_projeler']; ?></a>
						<ul class="sub-menu">
							<li class="menu-item"><a href="<?php
		 
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
							<li class="menu-item"><a href="<?php
		 
			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/تركيا-العقارات/home/'.$City['id'].'_0_0_0';
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/turkey-real-estate/home/'.$City['id'].'_0_0_0';
				
			}
		 
		 ?>"><?php echo $City['title']; ?></a></li>
						<?php } ?>
						</ul>
					</li>
					
					<!-- Hizmetlerimiz -->
					<li class="menu-item">
						<a><?php echo $Lang['menu_hizmetlerimiz']; ?></a>
						<?php

						$HizmetlerimizListesi = $DB->prepare("SELECT * FROM ihit_menu WHERE menu_id = '2' AND lang_id = :lang_id ORDER BY rank ASC");
						$HizmetlerimizListesi->execute(array(':lang_id'=>$CurrentLangInfo['id']));

						if($HizmetlerimizListesi->rowCount() > 0){

							echo '<ul class="sub-menu" style="width:265px;">';

						}

						foreach($HizmetlerimizListesi->fetchAll(PDO::FETCH_ASSOC) as $Hizmet){

						?>
						<li class="menu-item">
							<a<?php if(trim($Hizmet['url'])!='') echo ' href="'.$Hizmet['url'].'"'; ?>><?php echo $Hizmet['title']; ?></a>
							<?php
								
								$SubMenu = $DB->prepare("SELECT * FROM ihit_menu WHERE menu_id = '{$Hizmet['id']}' AND lang_id = :lang_id ORDER BY rank ASC");
								$SubMenu->execute(array(':lang_id'=>$CurrentLangInfo['id']));
								
								if($SubMenu->rowCount() > 0){
							?>
							<ul class="sub-menu" style="width: 240px;">
								<?php
									foreach($SubMenu->fetchAll(PDO::FETCH_ASSOC) as $SubMenu){
								?>
								   <li class="menu-item"><a href="<?php echo $SubMenu['url']; ?>"><?php echo $SubMenu['title']; ?></a></li>
								<?php } // Foreach ?>
							</ul>
							<?php
								}// Submenu
							?>
						</li>
						<?php

						}


						if($HizmetlerimizListesi->rowCount() > 0){

							echo '</ul>';

						}

						?>
					</li>
					<!-- Info -->
					
					<li class="menu-item">
						<a><?php echo $Lang['menu_info']; ?></a>
						<?php

						$InfoListesi = $DB->prepare("SELECT * FROM ihit_menu WHERE menu_id = '3' AND lang_id = :lang_id ORDER BY rank ASC");
						$InfoListesi->execute(array(':lang_id'=>$CurrentLangInfo['id']));

						if($InfoListesi->rowCount() > 0){

							echo '<ul class="sub-menu" style="width:265px;">';

						}

						foreach($InfoListesi->fetchAll(PDO::FETCH_ASSOC) as $Info){

						?>
						<li class="menu-item">
							<a<?php if(trim($Info['url'])!='') echo ' href="'.$Info['url'].'"'; ?>><?php echo $Info['title']; ?></a>
							<?php
								
								$SubMenu = $DB->prepare("SELECT * FROM ihit_menu WHERE menu_id = '{$Info['id']}' AND lang_id = :lang_id ORDER BY rank ASC");
								$SubMenu->execute(array(':lang_id'=>$CurrentLangInfo['id']));
								
								if($SubMenu->rowCount() > 0){
							?>
							<ul class="sub-menu" style="width: 240px;">
								<?php
									foreach($SubMenu->fetchAll(PDO::FETCH_ASSOC) as $SubMenu){
								?>
								   <li class="menu-item"><a href="<?php echo $SubMenu['url']; ?>"><?php echo $SubMenu['title']; ?></a></li>
								<?php } // Foreach ?>
							</ul>
							<?php
								}// Submenu
							?>
						</li>
						<?php

						}


						if($InfoListesi->rowCount() > 0){

							echo '</ul>';

						}

						?>
					</li>
					
					<!-- Kurumsal -->
					<li class="menu-item">
						<a><?php echo $Lang['menu_kurumsal']; ?></a>
						<?php

						$KurumsalList = $DB->prepare("SELECT * FROM ihit_menu WHERE menu_id = '1' AND lang_id = :lang_id ORDER BY rank ASC");
						$KurumsalList->execute(array(':lang_id'=>$CurrentLangInfo['id']));

						if($KurumsalList->rowCount() > 0){

							echo '<ul class="sub-menu">';

						}
						
						foreach($KurumsalList->fetchAll(PDO::FETCH_ASSOC) as $Kurumsal){

						?>
						<li class="menu-item"><a<?php if(trim($Kurumsal['url'])!='') echo ' href="'.$Kurumsal['url'].'"'; ?>><?php echo $Kurumsal['title']; ?></a>
							<?php
								
								$SubMenu = $DB->prepare("SELECT * FROM ihit_menu WHERE menu_id = '{$Kurumsal['id']}' AND lang_id = :lang_id ORDER BY rank ASC");
								$SubMenu->execute(array(':lang_id'=>$CurrentLangInfo['id']));
								
								if($SubMenu->rowCount() > 0){
							?>
								<ul class="sub-menu" style="width: 240px;">
									<?php
										foreach($SubMenu->fetchAll(PDO::FETCH_ASSOC) as $SubMenu){
									?>
									   <li class="menu-item"><a href="<?php echo $SubMenu['url']; ?>"><?php echo $SubMenu['title']; ?></a></li>
									<?php } // Foreach ?>
								</ul>
							<?php
								}// Submenu
							?>
						</li>
						<?php

							}

						if($KurumsalList->rowCount() > 0){

							echo '</ul>';

						}

						?>
					</li>
					
					<!-- Blog -->
					<?php if(getSettings('showblog')!=0){ ?><li class="menu-item"><a href="#"><?php echo $Lang['menu_blog']; ?></a></li><?php } ?>
					
					
					<!-- İletişim -->
					<li class="menu-item" style="border-right: 0px!important;"><a href="<?php
		 
			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/contact/';
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/contact/';
				
			}
		 
		 ?>"><?php echo $Lang['menu_iletisim']; ?></a></li>
				</ul>
			</div>
		</nav>
	</div>
</header>
<!-- Header -->

<!-- Mobile Menu -->
<section id="mobile-menu" class="hidden-lg hidden-md hidden">
	<div class="mobile-menu-navbar">
		<div class="mobile-menu-navbar-left">
			<a class="mobile-menu-home" href="<?php echo $MAIN_URL; ?>/<?php echo $DilSef; ?>/" title="Ideal Home in Turkey">
				<i class="fa fa-home"></i>
			</a>
		</div>
		<div class="mobile-menu-navbar-right">
			<div class="mobile-menu-close">
				<i class="fa fa-close"></i>
			</div>
		</div>
	</div>
	
	<div class="mobile-menu-main">
		<ul class="mobile-menu-main-list">
			
			<?php
			/*
			<li class="mobile-menu-list-title" style="text-align:center;"><i style="font-size:16px;" class="fa fa-phone"></i> <a style="font-family:sans-serif;font-size:16px;font-weight:100;" href="tel:<?php echo getSettings('footer_phone'); ?>"><?php echo getSettings('footer_phone'); ?></a></li>
			 */
			 ?>
			
			<?php
			
				$ListLanguages = $DB->query("SELECT * FROM ihit_languages WHERE status = '1' ORDER BY rank ASC");
				if($ListLanguages->rowCount() > 1){
			
			?>
			<li class="mobile-menu-list-title">Choose Language</li>
			<li class="mobile-menu-list-social">
				<?php
				
					foreach($ListLanguages->fetchAll(PDO::FETCH_ASSOC) as $Language){

				?>
					<a class="mobile-menu-list-language-icon" <?php if(isset($CurrentPage) AND $CurrentPage=='404PAGE'){ echo ' href="'.$MAIN_URL.'/'.$Language['lang_id'].'/"'; }else{ ?> onclick="chooseLanguage('<?php echo $Language['id']; ?>')" <?php } ?>title="<?php echo $Language['lang_name']; ?>"><img src="<?php echo $MAIN_URL; ?>/uploads/languages/<?php echo $Language['image']; ?>" style="width:24px;" alt="<?php echo $Language['lang_name']; ?>"/></a>
				<?php } ?>
			</li>
			<?php } ?>
			
			<!-- Usefull Links -->
			<li class="mobile-menu-list-title">Menu</li>
			<li class="mobile-menu-list-dropdown"><a href="<?php echo $MAIN_URL; ?>/<?php echo $DilSef; ?>/"><?php echo $Lang['menu_anasayfa']; ?></a></li>
			
			<!-- Projeler -->
			<li class="mobile-menu-list-dropdown">
				<a class="clearfix" data-toggle="collapse" href="#mobile-dropdown-citys" aria-expanded="false" aria-controls="mobile-dropdown-citys"><?php echo $Lang['menu_projeler']; ?></a>
				<ul id="mobile-dropdown-citys" class="mobile-menu-sub-list collapse">
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
			</li>
			
					
			
			<!-- Services -->
			<li class="mobile-menu-list-dropdown">
				<a class="clearfix" data-toggle="collapse" href="#mobile-dropdown-services" aria-expanded="false" aria-controls="mobile-dropdown-services"><?php echo $Lang['menu_hizmetlerimiz']; ?></a>
				<ul id="mobile-dropdown-services" class="mobile-menu-sub-list collapse">
						<?php

						$ServicesList = $DB->prepare("SELECT * FROM ihit_menu WHERE menu_id = '2' AND lang_id = :lang_id ORDER BY rank ASC");
						$ServicesList->execute(array(':lang_id'=>$CurrentLangInfo['id']));
						
						foreach($ServicesList->fetchAll(PDO::FETCH_ASSOC) as $Services){

						?>
						<li><a<?php if(trim($Services['url'])!='') echo ' href="'.$Services['url'].'"'; ?>><?php echo $Services['title']; ?></a>
							<?php
								
								$SubMenu = $DB->prepare("SELECT * FROM ihit_menu WHERE menu_id = '{$Services['id']}' AND lang_id = :lang_id ORDER BY rank ASC");
								$SubMenu->execute(array(':lang_id'=>$CurrentLangInfo['id']));
								
								if($SubMenu->rowCount() > 0){
							?>
									<?php
										foreach($SubMenu->fetchAll(PDO::FETCH_ASSOC) as $SubMenu){
									?>
									   <li style="padding-left: 26px;"><a href="<?php echo $SubMenu['url']; ?>"><?php echo $SubMenu['title']; ?></a></li>
									<?php } // Foreach ?>
							<?php
								}// Submenu
							?>
						</li>
						<?php } ?>
					</ul>
				</li>
			
			<!-- Info -->
			<li class="mobile-menu-list-dropdown">
				<a class="clearfix" data-toggle="collapse" href="#mobile-dropdown-info" aria-expanded="false" aria-controls="mobile-dropdown-info"><?php echo $Lang['menu_info']; ?></a>
				<ul id="mobile-dropdown-info" class="mobile-menu-sub-list collapse">
						<?php

						$InfoList = $DB->prepare("SELECT * FROM ihit_menu WHERE menu_id = '3' AND lang_id = :lang_id ORDER BY rank ASC");
						$InfoList->execute(array(':lang_id'=>$CurrentLangInfo['id']));
						
						foreach($InfoList->fetchAll(PDO::FETCH_ASSOC) as $Info){

						?>
						<li><a<?php if(trim($Info['url'])!='') echo ' href="'.$Info['url'].'"'; ?>><?php echo $Info['title']; ?></a>
							<?php
								
								$SubMenu = $DB->prepare("SELECT * FROM ihit_menu WHERE menu_id = '{$Info['id']}' AND lang_id = :lang_id ORDER BY rank ASC");
								$SubMenu->execute(array(':lang_id'=>$CurrentLangInfo['id']));
								
								if($SubMenu->rowCount() > 0){
							?>
									<?php
										foreach($SubMenu->fetchAll(PDO::FETCH_ASSOC) as $SubMenu){
									?>
									   <li style="padding-left: 26px;"><a href="<?php echo $SubMenu['url']; ?>"><?php echo $SubMenu['title']; ?></a></li>
									<?php } // Foreach ?>
							<?php
								}// Submenu
							?>
						</li>
						<?php } ?>
					</ul>
				</li>
			
			<!-- Corporate -->
			<li class="mobile-menu-list-dropdown">
				<a class="clearfix" data-toggle="collapse" href="#mobile-dropdown-corporate" aria-expanded="false" aria-controls="mobile-dropdown-corporate"><?php echo $Lang['menu_kurumsal']; ?></a>
				<ul id="mobile-dropdown-corporate" class="mobile-menu-sub-list collapse">
						<?php

						$KurumsalList = $DB->prepare("SELECT * FROM ihit_menu WHERE menu_id = '1' AND lang_id = :lang_id ORDER BY rank ASC");
						$KurumsalList->execute(array(':lang_id'=>$CurrentLangInfo['id']));
						
						foreach($KurumsalList->fetchAll(PDO::FETCH_ASSOC) as $Kurumsal){

						?>
						<li><a<?php if(trim($Kurumsal['url'])!='') echo ' href="'.$Kurumsal['url'].'"'; ?>><?php echo $Kurumsal['title']; ?></a>
							<?php
								
								$SubMenu = $DB->prepare("SELECT * FROM ihit_menu WHERE menu_id = '{$Kurumsal['id']}' AND lang_id = :lang_id ORDER BY rank ASC");
								$SubMenu->execute(array(':lang_id'=>$CurrentLangInfo['id']));
								
								if($SubMenu->rowCount() > 0){
							?>
									<?php
										foreach($SubMenu->fetchAll(PDO::FETCH_ASSOC) as $SubMenu){
									?>
									   <li><a href="<?php echo $SubMenu['url']; ?>"><?php echo $SubMenu['title']; ?></a></li>
									<?php } // Foreach ?>
							<?php
								}// Submenu
							?>
						</li>
						<?php } ?>
					</ul>
				</li>
					
			<!-- İletişim -->
			<li class="mobile-menu-list-dropdown"><a href="<?php
		 
			if($DilSef=='ar-sa'){
				
				echo $MAIN_URL.'/ar-sa/contact/';
				
			}elseif($DilSef=='en-us'){
				
				echo $MAIN_URL.'/en-us/contact/';
				
			}
		 
		 ?>"><?php echo $Lang['menu_iletisim']; ?></a></li>
			
			<li class="mobile-menu-list-title">Social Media</li>
			<li class="mobile-menu-list-social">
				<a class="mobile-menu-list-social-icon fa fa-facebook social-round-container-facebook" href="https://www.facebook.com/<?php echo getSettings('facebook_username'); ?>" title="Facebook"></a>
				<a class="mobile-menu-list-social-icon fa fa-instagram social-round-container-instagram" href="https://www.instagram.com/<?php echo getSettings('instagram_username'); ?>" title="Instagram"></a>
				<a class="mobile-menu-list-social-icon fa fa-twitter social-round-container-twitter" href="https://www.twitter.com/<?php echo getSettings('twitter_username'); ?>" title="Twitter"></a>
				<a class="mobile-menu-list-social-icon fa fa-linkedin social-round-container-linkedin" href="https://www.linkedin.com/<?php echo getSettings('linkedin_username'); ?>" title="Linkedin"></a>
				<a class="mobile-menu-list-social-icon fa fa-youtube social-round-container-youtube" href="https://www.youtube.com/<?php echo getSettings('youtube_username'); ?>" title="Youtube"></a>
				<a class="mobile-menu-list-social-icon fa fa-pinterest social-round-container-pinterest" href="https://www.pinterest.com/<?php echo getSettings('pinterest_username'); ?>" title="Pinterest"></a>
				<a class="mobile-menu-list-social-icon fa fa-google-plus social-round-container-google-plus" href="https://plus.google.com/+<?php echo getSettings('googleplus_username'); ?>" title="Google +"></a>
			</li>
			
			<!-- Usefull Links -->
			<li class="mobile-menu-list-title"><?php echo $Lang['footer_linkler']; ?></li>
				<?php
				$getFooterLinks = $DB->prepare("SELECT * FROM ihit_footerlink WHERE lang_id = :lang_id ORDER BY id ASC");
				$getFooterLinks->execute(array(':lang_id'=>$CurrentLangInfo['id']));

				foreach($getFooterLinks->fetchAll(PDO::FETCH_ASSOC) as $Footer){
				?>
					<li class="mobile-menu-list-item"><a href="<?php echo $Footer['url']; ?>" title="<?php echo $Footer['title']; ?>"><?php echo $Footer['title']; ?></a></li>
				<?php } ?>

		</ul>
	</div>
</section>
<!-- #Mobile Menu# -->