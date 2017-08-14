
    <div class="sidebar" data-background-color="white" data-active-color="danger">

    <!--
		Tip 1: you can change the color of the sidebar's background using: data-background-color="white | black"
		Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
	-->

    	<div class="sidebar-wrapper">
            <div class="logo">
                <a href="<?php echo $MAIN_URL.'/controlpanel.php'; ?>" class="simple-text">
                    Kontrol Paneli
                </a>
            </div>

            <ul class="nav">
                <li class="active">
                    <a href="controlpanel.php">
                        <i class="ti-panel"></i>
                        <p>Anasayfa</p>
                    </a>
                </li>
				<?php
					if(userInfo('role_form')!=0){
				?>
                <li>
                    <a href="formlistesi.php">
                        <i class="ti-view-list-alt"></i>
                        <p>Form Listesi</p>
                    </a>
				</li>
				<?php
					} // Role End
					if(userInfo('role_projects')!=0){
				?>
                <li>
                    <a href="projeler.php">
                        <i class="ti-home"></i>
                        <p>Projeler</p>
                    </a>
                </li>  
				<?php
					} // Role End
					if(userInfo('role_users')!=0){
				?>
                <li>
                    <a href="kullaniciyonetimi.php">
                        <i class="ti-user"></i>
                        <p>Kullanıcı Yönetimi</p>
                    </a>
                </li>  
				<?php
					} // Role End
					if(userInfo('role_sliders')!=0){
				?>
                <li>
                    <a href="sliderlar.php">
                        <i class="ti-layers-alt"></i>
                        <p>Sliderlar</p>
                    </a>
                </li> 
				<?php
					} // Role End
					if(userInfo('role_languages')!=0){
				?>
                <li>
                    <a href="dilyonetimi.php">
                        <i class="ti-flag-alt-2"></i>
                        <p>Dil Yönetimi</p>
                    </a>
                </li> 
				<?php
					} // Role End
					if(userInfo('role_pages')!=0){
				?> 
                <li>
                    <a href="sayfalar.php">
                        <i class="ti-files"></i>
                        <p>Sayfalar</p>
                    </a>
                </li>    
				<?php
					} // Role End
					if(userInfo('role_settings')!=0){
				?>          
                <li>
                    <a href="genelayarlar.php">
                        <i class="ti-settings"></i>
                        <p>Genel Ayarlar</p>
                    </a>
                </li>
				<?php
					} // Role End
					if(userInfo('role_localphoto')!=0){
				?>          
                <li>
                    <a href="localphotos.php">
                        <i class="ti-upload"></i>
                        <p>Dosya/Fotoğraf Yükle</p>
                    </a>
                </li>
				<?php
					} // Role End
				?>
            </ul>
    	</div>
    </div>