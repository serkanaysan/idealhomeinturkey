<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <img class="img-responsive img-footer-logo" src="<?php echo $MAIN_URL; ?>/assets/img/logo-white.png" width="150" height="82" alt="Ideal Home In Turkey White Footer Logo"/>
                <p class="text-justify text-footer">
                    <?php echo $Lang['footer_aciklama']; ?>
                </p>
            </div>
            <div class="col-md-4">
                <h2 class="footer-title"><span><?php echo $Lang['footer_linkler']; ?></span></h2>
                <ul class="footer-list">
					<?php
						$getFooterLinks = $DB->prepare("SELECT * FROM ihit_footerlink WHERE lang_id = :lang_id ORDER BY id ASC");
						$getFooterLinks->execute(array(':lang_id'=>$CurrentLangInfo['id']));

						foreach($getFooterLinks->fetchAll(PDO::FETCH_ASSOC) as $Footer){
						?>
						<li>
							<a href="<?php echo $Footer['url']; ?>"><?php echo $Footer['title']; ?></a>
						</li>
					<?php } ?>
                </ul>
				
                <button style="margin-top: 21px;" class="btn btn-write-us" type="button" data-toggle="modal" data-target="#writeus"><?php echo $Lang['footer_writeus']; ?></button>
            </div>
            <div class="col-md-4">
                <h2 class="footer-title"><span><?php echo $Lang['footer_kurumsal']; ?></span></h2>
                <ul class="footer-list">
                    <li class="phone">
                        <a href="tel:<?php echo str_replace(array(' '),null,getSettings('footer_phone')); ?>"><?php echo getSettings('footer_phone'); ?></a>
                    </li>
                    <li class="mail">
                        <a href="mailto:<?php echo getSettings('footer_email'); ?>"><?php echo getSettings('footer_email'); ?></a>
                    </li>
                    <li class="adress">
                        <p>
                            <?php echo getSettings('footer_address'); ?>
                        </p>
                    </li>
                </ul>
                <h2 class="footer-title" style="margin-top: 60px;"><span><?php echo $Lang['footer_followsocial']; ?></span></h2>
                <div class="footer-social">
                    <?php if(trim(getSettings('facebook_username'))!=''){ ?><a class="footer-social-item facebook" href="https://www.facebook.com/<?php echo getSettings('facebook_username'); ?>" title="Facebook" target="_blank"><i class="fa fa-facebook"></i></a><?php } ?>
                    <?php if(trim(getSettings('instagram_username'))!=''){ ?><a class="footer-social-item instagram" href="https://www.instagram.com/<?php echo getSettings('instagram_username'); ?>" title="Instagram" target="_blank"><i class="fa fa-instagram"></i></a><?php } ?>
                    <?php if(trim(getSettings('twitter_username'))!=''){ ?><a class="footer-social-item twitter" href="https://www.twitter.com/<?php echo getSettings('twitter_username'); ?>" title="Twitter" target="_blank"><i class="fa fa-twitter"></i></a><?php } ?>
                    <?php if(trim(getSettings('linkedin_username'))!=''){ ?><a class="footer-social-item linkedin" href="https://www.linkedin.com/<?php echo getSettings('linkedin_username'); ?>" title="Linkedin" target="_blank"><i class="fa fa-linkedin"></i></a><?php } ?>
                    <?php if(trim(getSettings('youtube_username'))!=''){ ?><a class="footer-social-item youtube" href="https://www.youtube.com/<?php echo getSettings('youtube_username'); ?>" title="Youtube" target="_blank"><i class="fa fa-youtube-play"></i></a><?php } ?>
                    <?php if(trim(getSettings('pinterest_username'))!=''){ ?><a class="footer-social-item pinterest" href="https://www.pinterest.com/<?php echo getSettings('pinterest_username'); ?>" title="Pinterest" target="_blank"><i class="fa fa-pinterest"></i></a><?php } ?>
                    <?php if(trim(getSettings('googleplus_username'))!=''){ ?><a class="footer-social-item google" href="https://plus.google.com/+<?php echo getSettings('googleplus_username'); ?>" title="Google Plus" target="_blank"><i class="fa fa-google-plus"></i></a><?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <?php echo getSettings('footer_text'); ?>
    </div>
</footer>
<div class="modal fade" id="listcontactus" tabindex="-1" role="dialog" aria-labelledby="writeusLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"><button data-dismiss="modal" class="close">×</button>
                <h4 class="modal-title" id="writeusLabel"><?php echo $Lang['footer_getinformation']; ?></h4>
            </div>
            <form class="contact-form" method="post" action="javascript:void(0);" id="contact_form_listpage">
                <div class="modal-body">
                    <label class="writeus-input">
                        <span class="text"><?php echo $Lang['form_adsoyad']; ?></span>
                        <input type="text" name="fullname" id="fullname" class="form-control"/>
                    </label>
                    <label class="writeus-input">
                        <span class="text">Project</span>
                        <input type="text" name="formname" id="formname" class="ListProjectID form-control" disabled="disabled"/>
                    </label>
                    <div class="writeus-input">
                        <span class="text"><?php echo $Lang['form_telefon']; ?></span>
						<input id="phone_number_listpage" class="form-control" type="tel" />
                    </div>
                    <label class="writeus-input">
                        <span class="text"><?php echo $Lang['form_eposta']; ?></span>
                        <input type="email" name="email" id="email" class="form-control"/>
                    </label>
                    <label class="writeus-input">
                        <span class="text"><?php echo $Lang['form_mesaj']; ?></span>
                        <textarea rows="4" id="message" name="message" class="form-control"></textarea>
                    </label>
                </div>
				<input type="hidden" id="page" name="page" value="<?php echo addslashes(htmlspecialchars(strip_tags($_SERVER['REQUEST_URI']))); ?>" />
                <div class="modal-footer">
                    <button type="reset" class="btn btn-reset"><?php echo $Lang['form_reset']; ?></button>
                    <button type="submit" onclick="sendForm('contact_form_listpage','phone_number_listpage');" id="submitted" class="btn btn-submit"><?php echo $Lang['form_gonder']; ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="writeus" tabindex="-1" role="dialog" aria-labelledby="writeusLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"><button data-dismiss="modal" class="close">×</button>
                <h4 class="modal-title" id="writeusLabel">Write Us!</h4>
            </div>
           
            <form method="post" action="javascript:void(0);" id="contact_form_footer">
                <div class="modal-body">
                    <label class="writeus-input">
                        <span class="text"><?php echo $Lang['form_adsoyad']; ?></span>
                        <input type="text" id="fullname" name="fullname" class="form-control"/>
                    </label>
                    <div class="writeus-input">
                        <span class="text"><?php echo $Lang['form_telefon']; ?></span>
						<input type="tel" id="phone_number_footer" class="form-control" />
                    </div>
                    <label class="writeus-input">
                        <span class="text"><?php echo $Lang['form_eposta']; ?></span>
                        <input id="email" type="email" name="email" class="form-control"/>
                    </label>
                    <label class="writeus-input">
                        <span class="text"><?php echo $Lang['form_mesaj']; ?></span>
                        <textarea name="message" id="message" rows="4" class="form-control"></textarea>
                    </label>
                </div>
				<input type="hidden" id="formname" name="formname" value="Footer Form" />
				<input type="hidden" id="page" name="page" value="<?php echo addslashes(htmlspecialchars(strip_tags($_SERVER['REQUEST_URI']))); ?>" />
                <div class="modal-footer">
                    <button type="reset" class="btn btn-reset"><?php echo $Lang['form_reset']; ?></button>
                    <button type="submit" onclick="sendForm('contact_form_footer','phone_number_footer');" id="submitted"  class="btn btn-submit"><?php echo $Lang['form_gonder']; ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mobile Menu -->
<section id="mobile-menu" class="hidden-lg hidden-md">
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
			<li class="mobile-menu-list-title"><?php echo $Lang['mobilemenu_chooselanguage']; ?></li>
			<li class="mobile-menu-list-social">
				<?php
				
					foreach($ListLanguages->fetchAll(PDO::FETCH_ASSOC) as $Language){

				?>
					<a class="mobile-menu-list-language-icon" <?php if(isset($CurrentPage) AND $CurrentPage=='404PAGE'){ echo ' href="'.$MAIN_URL.'/'.$Language['lang_id'].'/"'; }else{ ?> onclick="chooseLanguage('<?php echo $Language['id']; ?>')" <?php } ?>title="<?php echo $Language['lang_name']; ?>"><img src="<?php echo $REAL_URL; ?>/uploads/languages/<?php echo $Language['image']; ?>" style="width:24px;" alt="<?php echo $Language['lang_name']; ?>"/></a>
				<?php } ?>
			</li>
			<?php } ?>
			
			<!-- Usefull Links -->
			<li class="mobile-menu-list-title"><?php echo $Lang['mobilemenu_menu']; ?></li>
			<li class="mobile-menu-list-dropdown"><a href="<?php echo $MAIN_URL; ?>/<?php echo $DilSef; ?>/"><?php echo $Lang['menu_anasayfa']; ?></a></li>
			
			<!-- Projeler -->
			<li class="mobile-menu-list-dropdown">
				<a class="clearfix" data-toggle="collapse" href="#mobile-dropdown-citys" aria-expanded="false" aria-controls="mobile-dropdown-citys"><?php echo $Lang['menu_projeler']; ?> <i class="fa fa-angle-down"></i> </a>
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
				<a class="clearfix" data-toggle="collapse" href="#mobile-dropdown-services" aria-expanded="false" aria-controls="mobile-dropdown-services"><?php echo $Lang['menu_hizmetlerimiz']; ?> <i class="fa fa-angle-down"></i></a>
				<ul id="mobile-dropdown-services" class="mobile-menu-sub-list collapse">
						<?php

						$ServicesList = $DB->prepare("SELECT * FROM ihit_menu WHERE menu_id = '2' AND lang_id = :lang_id ORDER BY rank ASC");
						$ServicesList->execute(array(':lang_id'=>$CurrentLangInfo['id']));
						
						foreach($ServicesList->fetchAll(PDO::FETCH_ASSOC) as $Services){

						?>
						<li><a<?php if(trim($Services['url'])!='') echo ' href="'.$Services['url'].'"'; ?> style="font-weight:bold"><?php echo $Services['title']; ?></a>
							<?php
								
								$SubMenu = $DB->prepare("SELECT * FROM ihit_menu WHERE menu_id = '{$Services['id']}' AND lang_id = :lang_id ORDER BY rank ASC");
								$SubMenu->execute(array(':lang_id'=>$CurrentLangInfo['id']));
								
								if($SubMenu->rowCount() > 0){
							?>
									<?php
										foreach($SubMenu->fetchAll(PDO::FETCH_ASSOC) as $SubMenu){
									?>
									   <li style="padding-left: 8px;"><a href="<?php echo $SubMenu['url']; ?>"><?php echo $SubMenu['title']; ?></a></li>
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
				<a class="clearfix" data-toggle="collapse" href="#mobile-dropdown-info" aria-expanded="false" aria-controls="mobile-dropdown-info"><?php echo $Lang['menu_info']; ?> <i class="fa fa-angle-down"></i></a>
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
									   <li style="padding-left: 8px;"><a href="<?php echo $SubMenu['url']; ?>"><?php echo $SubMenu['title']; ?></a></li>
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
				<a class="clearfix" data-toggle="collapse" href="#mobile-dropdown-corporate" aria-expanded="false" aria-controls="mobile-dropdown-corporate"><?php echo $Lang['menu_kurumsal']; ?> <i class="fa fa-angle-down"></i></a>
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
			
			<li class="mobile-menu-list-title"><?php echo $Lang['mobilemenu_socialmedia']; ?></li>
			<li class="mobile-menu-list-social">
				<a target="_blank" class="mobile-menu-list-social-icon fa fa-facebook social-round-container-facebook" href="https://www.facebook.com/<?php echo getSettings('facebook_username'); ?>" title="Facebook"></a>
				<a target="_blank" class="mobile-menu-list-social-icon fa fa-instagram social-round-container-instagram" href="https://www.instagram.com/<?php echo getSettings('instagram_username'); ?>" title="Instagram"></a>
				<a target="_blank" class="mobile-menu-list-social-icon fa fa-twitter social-round-container-twitter" href="https://www.twitter.com/<?php echo getSettings('twitter_username'); ?>" title="Twitter"></a>
				<a target="_blank" class="mobile-menu-list-social-icon fa fa-linkedin social-round-container-linkedin" href="https://www.linkedin.com/<?php echo getSettings('linkedin_username'); ?>" title="Linkedin"></a>
				<a target="_blank" class="mobile-menu-list-social-icon fa fa-youtube social-round-container-youtube" href="https://www.youtube.com/<?php echo getSettings('youtube_username'); ?>" title="Youtube"></a>
				<a target="_blank" class="mobile-menu-list-social-icon fa fa-pinterest social-round-container-pinterest" href="https://www.pinterest.com/<?php echo getSettings('pinterest_username'); ?>" title="Pinterest"></a>
				<a target="_blank" class="mobile-menu-list-social-icon fa fa-google-plus social-round-container-google-plus" href="https://plus.google.com/+<?php echo getSettings('googleplus_username'); ?>" title="Google +"></a>
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

<!-- Scripts -->
<script type="text/javascript" src="<?php echo $MAIN_URL; ?>/assets/js/libraries.jquery.js"></script>
<script type="text/javascript" src="<?php echo $MAIN_URL; ?>/assets/js/libraries.bootstrap.js"></script>
<script type="text/javascript" src="<?php echo $MAIN_URL; ?>/assets/js/default.custom.js"></script>
<script type="text/javascript" src="<?php echo $MAIN_URL; ?>/assets/js/lazy.js"></script>
<script type="text/javascript" src="<?php echo $MAIN_URL; ?>/assets/js/sweetalert.min.js"></script>
<link href="<?php echo $MAIN_URL; ?>/assets/css/library/intlTelInput.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo $MAIN_URL; ?>/assets/js/intlTelInput.min.js"></script>
<?php
	if(DEFINED('PAGE') AND PAGE == 'LIST'){
?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVa7Ve4hZE8zaPX2KCmMaFwbHrARz03dQ&region=GB"></script>
<script type="text/javascript" src="<?php echo $MAIN_URL; ?>/assets/js/libraries.gmap.js"></script>
<?php } ?>
<script type="text/javascript"><?php
	if(DEFINED('PAGE') AND PAGE == 'LIST'){
?>
$(".lazyload").lazyload({
	effect : "fadeIn"
});

$('#collapseMap').on('show.bs.collapse', function () {
	$("#map").remove();
	$(this).append('<div id="map"></div>');
});
$('#collapseMap').on('shown.bs.collapse', function () {
	var map;
	var info = [
	<?php
		foreach($MapProjectList as $MapProjectInfo){
	?>
		{
			coordinates: [<?php echo $MapProjectInfo['MapLat']; ?>,<?php echo $MapProjectInfo['MapLng']; ?>],
			html: '<a class="map-info" href="<?php echo $MapProjectInfo['ProjectURL']; ?>">'+
			'<div class="map-info-image">'+
			'<img src="https://www.idealhomeinturkey.com/mthumb.php?src=<?php echo $REAL_URL; ?>/uploads/project/<?php echo $MapProjectInfo['Media']; ?>&w=300&h=255&q=75&zc=0" />'+
			'</div>'+
			'<div class="col-md-6 col-sm-12 col-xs-12 map-info-title">'+
			'<?php echo $MapProjectInfo['ProjectID']; ?>'+
			'</div> <div class="col-md-6 col-sm-12 col-xs-12 map-info-price" style="text-align:right;"><?php echo $MapProjectInfo['Price']; ?></div>'+
			'<div class="col-md-12 map-info-coast">'+
			'<button class="btn btn-primary" style="width: 100%; background-color: #E51B24; color: #fff;border:0;"><?php echo $Lang['list_review']; ?></button>'+
			'</div>'+
			'</a>'
		},
		<?php } ?>
		
	];
	map = new GMaps({
		div: '#map',
		lat: "41.098527",
		lng: "28.9893913",
		zoom: 10
	});
	for(var i=0; i<info.length; i++){
		map.addMarker({
			lat: info[i].coordinates[0],
			lng: info[i].coordinates[1],
			infoWindow: {
				content: info[i].html
			},
			icon: "<?php echo $MAIN_URL; ?>/assets/img/marker.png"
		});
	}
})
<?php } ?>
function ListProjectID(project_id){
	
	$('.ListProjectID').val(project_id);
	
}

function goSearch() {

	var search_ps = $("#search_ps").val();
	var search_text = $("#search_text").val();
	var search_type = $("#search_type").val();
	var search_city = $("#search_city").val();
	var search_pricerange = $("#search_pr").val();
	$("#searchPropertySubmit").attr("disabled", "disabled");
	
	if(search_ps==''){ var search_ps = 0; }
	if(search_text==''){ var search_text = 0; }
	if(search_type==''){ var search_type = 0; }
	if(search_city==''){ var search_city = 0; }
	if(search_pricerange==''){ var search_pricerange = 0; }
	<?php
		if(isset($DilSef)!='' AND $DilSef=='ar-sa'){
	?>
	window.location.href = "<?php echo $MAIN_URL; ?>/<?php echo $DilSef; ?>/تركيا-العقارات/home/"+search_city+"_"+search_type+"_"+search_ps+"_"+search_text+"_"+search_pricerange;
	<?php }else{?>
	window.location.href = "<?php echo $MAIN_URL; ?>/<?php echo $DilSef; ?>/turkey-real-estate/home/"+search_city+"_"+search_type+"_"+search_ps+"_"+search_text+"_"+search_pricerange;<?php } ?>

}
<?php
	if(DEFINED('PAGE') AND PAGE == 'LIST'){
?>
/*
    $.gmap3({
        key: "AIzaSyCVa7Ve4hZE8zaPX2KCmMaFwbHrARz03dQ"
    })
	*/
	
// Listpage Phone Number
var telInput = $("#phone_number_listpage"),
	errorMsg = $("#error-msg"),
	validMsg = $("#valid-msg");
telInput.intlTelInput({
	autoHideDialCode: true,
	autoPlaceholder: true,
	customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
		return "e.g. " + selectedCountryPlaceholder;
	},
	separateDialCode: false,
	nationalMode: false,
  utilsScript: "<?php echo $MAIN_URL; ?>/assets/js/utils.js",
		geoIpLookup: function(callback) {
		  $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
			var countryCode = (resp && resp.country) ? resp.country : "";
			callback(countryCode);
		  });
		},
		initialCountry: "auto"
});
var reset = function() {
	telInput.removeClass("phone-error");
	errorMsg.addClass("hide");
	validMsg.addClass("hide");
};
telInput.blur(function() {
	reset();
	if ($.trim(telInput.val())) {
		if (telInput.intlTelInput("isValidNumber")) {
			validMsg.removeClass("hide");
		} else {
			swal("<?php echo $Lang['hatamesajlari_telefonnumarasigecersiz']; ?>", " ", "error");
			telInput.addClass("phone-error");
			errorMsg.removeClass("hide");
		}
	}
});
telInput.on("keyup change", reset);
/*
$(document).ready(function(){
     $(".btn-contactus").mouseover(function(){
         $(".btn-review").css("background-color", "#eee");
     });
});
*/
<?php
	} // List
	
	if(DEFINED('THISPAGE') AND THISPAGE == 'INDEX'){
?>

$(document).ready(function(){
	$("#home-cover > div:gt(0)").hide();

	setInterval(function() {
	  $('#home-cover > div:first')
		.fadeOut(1000)
		.next()
		.fadeIn(1000)
		.end()
		.appendTo('#home-cover');
	}, 3000);
});
<?php
	}
	if(DEFINED('PAGE') AND PAGE == 'PROJECT'){
?>
$(document).ready(function(){
	
	$(".smilar_carousel .owl-carousel").owlCarousel({
		items: 3,
		itemsDesktop : [1200,3], // 2 items between 1000px and 901px
		itemsDesktopSmall : [900,3], // betweem 900px and 601px
		itemsTablet: [700,2], // 2 items between 600 and 480
		itemsMobile : [479,1] , // 1 item between 479 and 0
        autoPlay: true,
        pagination: false,
    });

    $(".smilar_carousel .similar-prev").on("click", function(){
        $(".smilar_carousel .owl-carousel").data('owlCarousel').prev();
    });

    $(".smilar_carousel .similar-next").on("click", function(){
        $(".smilar_carousel .owl-carousel").data('owlCarousel').next();
    });

	
	
    $(".slider .owl-carousel").owlCarousel({
        singleItem:true,
        autoPlay: true,
        pagination: false,
        afterAction: function(){
            $(".thumbnails .thumbnails-item").removeClass("active").removeClass("active");
            $(".thumbnails .thumbnails-item:eq("+this.currentItem+")").addClass("active");
        }
    });

    $(".thumbnails").owlCarousel({
		items: 6,
		itemsDesktop : [1200,5], // 2 items between 1000px and 901px
		itemsDesktopSmall : [900,5], // betweem 900px and 601px
		itemsTablet: [700,4], // 2 items between 600 and 480
		itemsMobile : [479,3] , // 1 item between 479 and 0
    });

    $(".thumbnails .thumbnails-item").on("click", function(){
        var index = $(this).parent().index();
        $(".slider .owl-carousel").data('owlCarousel').goTo(index);
        $(".thumbnails .thumbnails-item").removeClass("active");
        $(this).addClass("active");
    });

    $(".thumbnails-container .thumbnails-prev").on("click", function(){
        $(".thumbnails").data('owlCarousel').prev();
    });

    $(".thumbnails-container .thumbnails-next").on("click", function(){
        $(".thumbnails").data('owlCarousel').next();
    });

    $(".slider .slider-prev").on("click", function(){
        $(".slider .owl-carousel").data('owlCarousel').prev();
    });

    $(".slider .slider-next").on("click", function(){
        $(".slider .owl-carousel").data('owlCarousel').next();
    });
	
	  function fixDiv() {
		var $cache = $('#get-full-details');
		if ($(window).scrollTop() > 670)
		  $cache.css({
			'position': 'fixed',
			'top': '10px'
		  });
		else
		  $cache.css({
			'position': 'relative',
			'top': 'auto'
		  });
		  
		  
		if($cache.offset().top + $cache.height()>= $('#stop-animate-sidebar').offset().top)
			$cache.css('position', 'absolute');
	  }
	  $(window).scroll(fixDiv);
	  fixDiv();

});

    var telInput = $("#phone_number_project"),
        errorMsg = $("#error-msg"),
        validMsg = $("#valid-msg");
    telInput.intlTelInput({
        autoHideDialCode: true,
        autoPlaceholder: true,
        customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
            return "e.g. " + selectedCountryPlaceholder;
        },
        separateDialCode: false,
        nationalMode: false,
	  utilsScript: "<?php echo $MAIN_URL; ?>/assets/js/utils.js",
		geoIpLookup: function(callback) {
		  $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
			var countryCode = (resp && resp.country) ? resp.country : "";
			callback(countryCode);
		  });
		},
		initialCountry: "auto"
    });
    var reset = function() {
        telInput.removeClass("phone-error");
        errorMsg.addClass("hide");
        validMsg.addClass("hide");
    };
    telInput.blur(function() {
        reset();
        if ($.trim(telInput.val())) {
            if (telInput.intlTelInput("isValidNumber")) {
                validMsg.removeClass("hide");
            } else {
                swal("Telefon Numarası Geçersiz", " ", "error");
                telInput.addClass("phone-error");
                errorMsg.removeClass("hide");
            }
        }
    });
    telInput.on("keyup change", reset);

    var telInput = $("#phone_number_sidebar"),
        errorMsg = $("#error-msg"),
        validMsg = $("#valid-msg");
    telInput.intlTelInput({
        autoHideDialCode: true,
        autoPlaceholder: true,
        customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
            return "e.g. " + selectedCountryPlaceholder;
        },
        separateDialCode: false,
        nationalMode: false,
	  utilsScript: "<?php echo $MAIN_URL; ?>/assets/js/utils.js",
		geoIpLookup: function(callback) {
		  $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
			var countryCode = (resp && resp.country) ? resp.country : "";
			callback(countryCode);
		  });
		},
		initialCountry: "auto"
    });
    var reset = function() {
        telInput.removeClass("phone-error");
        errorMsg.addClass("hide");
        validMsg.addClass("hide");
    };
    telInput.blur(function() {
        reset();
        if ($.trim(telInput.val())) {
            if (telInput.intlTelInput("isValidNumber")) {
                validMsg.removeClass("hide");
            } else {
                swal("Telefon Numarası Geçersiz", " ", "error");
                telInput.addClass("phone-error");
                errorMsg.removeClass("hide");
            }
        }
    });
    telInput.on("keyup change", reset);

<?php
	} // Is Project Page.
?>

function sendForm(formid,phoneid) {

	// Form Verileri
	var fullname = $("#"+formid).find('#fullname').val();
	var email = $("#"+formid).find('#email').val();
	var message = $("#"+formid).find('#message').val();
	var formname = $("#"+formid).find('#formname').val();
	var page = $("#"+formid).find('#page').val();
	
	// Telefon
	var isValid = $("#"+phoneid).intlTelInput("isValidNumber");
	var Country = $("#"+phoneid).intlTelInput("getSelectedCountryData").name;
	var dialCode = $("#"+phoneid).intlTelInput("getSelectedCountryData").dialCode;
	var iso2 = $("#"+phoneid).intlTelInput("getSelectedCountryData").iso2;
	var phone = $("#"+phoneid).val();
	
	// Butonu kilitleyelim
	$("#"+formid+" > #submitted").attr("disabled", "disabled");
	
	//alert($('li.formlist > .container > article.sliderPost > .sliderPost__content > #contact_form_slider > #sliderfullname').val('farukset'));
	//alert($('li.formlist > .container > article.sliderPost > .sliderPost__content > #contact_form_slider > #sliderfullname').val());
	
	if(isValid){
		
		$.ajax({
			
			type: 'POST',
			url: '<?php echo $MAIN_URL; ?>/sendform',
			data: {fullname: fullname, phone: phone, email: email, message: message, country: Country, dialCode: dialCode, iso2: iso2, formname: formname, page: page},
			success: function(gelen) {
			
				if(gelen == "success"){
				
					swal("<?php echo $Lang['hatamesajlari_basarili']; ?>", "<?php echo $Lang['hatamesajlari_formbasariylagonderildi']; ?>", "success");
					$("#"+formid+" > #submitted").removeAttr("disabled");
					$("#"+formid).trigger('reset');
					
					function gaTracker(id){
					  $.getScript('//www.google-analytics.com/analytics.js'); // jQuery shortcut
					  window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
					  ga('create', id, 'auto');
					  ga('send', 'pageview');
					}
					
					var script = document.createElement('script');
					script.src = '//www.google-analytics.com/ga.js';
					document.getElementsByTagName('head')[0].appendChild(script);
					
					function gaTrack(path, title) {
						ga('set', { page: path, title: title });
						ga('send', 'pageview');
					}
					
					gaTracker('UA-79564150-1');

					ga('send', 'event', 'Ideal Web', formname+' | Form Submitted');
					fbq('track', 'Lead');
					
					$.ajax ({
					  type: 'POST',
					  url: '<?php echo $MAIN_URL; ?>/formsuccess',
					  data: '',
					  success: function(data){}
					});
					
				}else{
				
					swal("Warning", gelen, "warning");
					$("#"+formid+" > #submitted").removeAttr("disabled");
				
				}
				
			}
			
		});
		
		$("#"+formid+" > #submitted").removeAttr("disabled");
	
	}else{
		
		swal("<?php echo $Lang['hatamesajlari_hata']; ?>", "<?php echo $Lang['hatamesajlari_telefonnumarasigecersiz']; ?>", "warning");
		$("#"+formid+" > #submitted").removeAttr("disabled");
		
	}
	
}

// Footer Phone
var telInput = $("#phone_number_footer"),
	errorMsg = $("#error-msg"),
	validMsg = $("#valid-msg");
telInput.intlTelInput({
	autoHideDialCode: true,
	autoPlaceholder: true,
	customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
		return "e.g. " + selectedCountryPlaceholder;
	},
	separateDialCode: false,
	nationalMode: false,
  utilsScript: "<?php echo $MAIN_URL; ?>/assets/js/utils.js",
		geoIpLookup: function(callback) {
		  $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
			var countryCode = (resp && resp.country) ? resp.country : "";
			callback(countryCode);
		  });
		},
		initialCountry: "auto"
});
var reset = function() {
	telInput.removeClass("phone-error");
	errorMsg.addClass("hide");
	validMsg.addClass("hide");
};
telInput.blur(function() {
	reset();
	if ($.trim(telInput.val())) {
		if (telInput.intlTelInput("isValidNumber")) {
			validMsg.removeClass("hide");
		} else {
			swal("<?php echo $Lang['hatamesajlari_telefonnumarasigecersiz']; ?>", " ", "error");
			telInput.addClass("phone-error");
			errorMsg.removeClass("hide");
		}
	}
});
telInput.on("keyup change", reset);
</script>

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1584638268519572', {
});
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1584638268519572&ev=PageView&noscript=1"
/></noscript>
<!-- DO NOT MODIFY -->

<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-79564150-1', 'auto');
ga('send', 'pageview');

</script>

<script charset="UTF-8" src="//cdn.sendpulse.com/28edd3380a1c17cf65b137fe96516659/js/push/5cbef200ce4d8d0fb4bdb4a97d1397b5_1.js" async></script>

<script data-skip-moving="true" async>
        (function(w,d,u,b){
                s=d.createElement('script');r=1*new Date();s.async=1;s.src=u+'?'+r;
                h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
        })(window,document,'https://cdn.bitrix24.com/b2216803/crm/site_button/loader_2_5lv6h0.js');
</script>

<!-- Yandex.Metrika counter --> <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter38039385 = new Ya.Metrika({ id:38039385, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/38039385" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->

